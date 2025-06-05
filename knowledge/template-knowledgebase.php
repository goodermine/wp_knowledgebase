<?php
/*
Template Name: Knowledge Base
*/

global $post, $wpdb; // $knowledgepress is not a standard WP global. Removed unless defined elsewhere.

// Initialize variables and set defaults if Redux or options are not available
$meta = array();
if ( function_exists('redux_post_meta') && class_exists('ReduxFramework') ) {
    // Ensure 'knowledgepress' is the correct opt_name for your Redux instance
    $meta = redux_post_meta( 'knowledgepress', get_the_ID() ); 
}

$show_3rd_level_cat = isset($meta['3rd_level_cat']) ? (bool)$meta['3rd_level_cat'] : false;
$kb_aticles_per_cat = isset($meta['kb_aticles_per_cat']) && !empty($meta['kb_aticles_per_cat']) ? (int)$meta['kb_aticles_per_cat'] : 5;
$kb_category_ids_to_include = 'all'; // Default to all categories

if (isset($meta['kb_categories']) && is_array($meta['kb_categories']) && !empty($meta['kb_categories'])) {
    $kb_category_ids_to_include = array_map('intval', $meta['kb_categories']); // Ensure IDs are integers
} elseif (isset($meta['kb_categories']) && !is_array($meta['kb_categories']) && !empty($meta['kb_categories'])) {
    // Handle case where it might be a comma-separated string from older Redux versions or manual input
    $kb_category_ids_to_include = array_map('intval', explode(',', $meta['kb_categories']));
}


$kb_columns = isset($meta['kb_columns']) && !empty($meta['kb_columns']) ? (int)$meta['kb_columns'] : 3;
$col_class = 4; // Default for 3 columns (12/3)

if ($kb_columns == 2) {
    $col_class = 6;
} elseif ($kb_columns == 4) {
    $col_class = 3;
} elseif ($kb_columns == 1) {
    $col_class = 12;
} else { // Fallback for invalid column numbers or ensure it's one of the supported ones
    $kb_columns = 3;
    $col_class = 4;
}

/**
 * Return the total no of unique posts in given terms/Categories
 * Note: This counts posts. The name get_total_cat_count might be misleading.
 * Consider WP_Query for a more WordPress-standard way to count posts.
 * * @global wpdb $wpdb
 * @param array $term_ids Array of term IDs.
 * @return int Count of posts.
 */
function knowledgebase_get_post_count_for_terms( $term_ids = array() ){ // Renamed for clarity
    global $wpdb;
    
    if ( empty($term_ids) || !is_array($term_ids) ) {
        return 0;
    }
    
    $safe_term_ids = array_map('intval', $term_ids);
    if (empty($safe_term_ids)) {
        return 0;
    }

    $placeholders = implode( ',', array_fill( 0, count( $safe_term_ids ), '%d' ) );
    
    // Using $wpdb->prepare
    $query = $wpdb->prepare(
        "SELECT COUNT(DISTINCT p.ID) 
         FROM {$wpdb->posts} p
         INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
         INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
         WHERE p.post_type = 'post' 
         AND p.post_status = 'publish' 
         AND tt.term_id IN ( {$placeholders} )", // Note: No quotes around placeholders for IN clause
        $safe_term_ids
    );
    
    $count = $wpdb->get_var($query);
    return (int)$count;
}

$cat_args = array(
    'orderby'         => 'name', // 'slug' might be better for consistent ordering if names change
    'order'           => 'ASC',
    'hierarchical'    => true, // Important for parent=0 to work as expected
    'parent'          => 0,    // Only top-level categories
    'hide_empty'      => false, // Consider setting to true if empty categories are not desired
);

if ($kb_category_ids_to_include !== 'all') {
    $cat_args['include'] = $kb_category_ids_to_include;
}

$categories = get_categories($cat_args); 

$i    = 0;
$row_opened = false;

if ( !empty($categories) ) :
    foreach($categories as $category) { 
        // Get direct child categories of this top-level category
        $sub_categories_args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'parent' => $category->term_id,
            'hide_empty' => false,
            'hierarchical' => true, // get direct children
        );
        $child_categories = get_categories($sub_categories_args);

        // Get posts directly under this parent category (not including sub-categories)
        $cat_posts_args = array(
            'posts_per_page'   => $kb_aticles_per_cat > 0 ? $kb_aticles_per_cat : -1, // Use -1 if 0 means all
            'category__in'     => array($category->term_id), // Only posts directly in this category
            'category__not_in' => !empty($child_categories) ? wp_list_pluck($child_categories, 'term_id') : array(), // Exclude posts also in its children if desired
            'post_status'      => 'publish',
            'orderby'          => 'date',
            'order'            => 'DESC',
        );
        $cat_posts = get_posts($cat_posts_args);
        
        // Skip this category if it has no direct posts and no subcategories with posts (more complex check needed for subcat posts)
        // For simplicity here, we'll display if it has subcats or direct posts.
        if ( empty($child_categories) && empty($cat_posts) ) {
            continue;
        }

        if ($i % $kb_columns == 0) {
            if ($row_opened) {
                echo '</div>'; // Close previous row
            }
            echo '<div class="row knowledge-base-row">';
            $row_opened = true;
        }
        $i++;
        ?>
        <div class="col-sm-<?php echo esc_attr($col_class); ?> kb-category">
            <h2>
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" title="<?php echo esc_attr($category->name); ?>">
                <?php echo esc_html($category->name); ?>
                </a>
            </h2>
            <?php
            // Display sub-categories (1st level children)
            if ( !empty($child_categories) ) {
                echo '<ul class="sub-categories">';
                foreach ($child_categories as $sub_category) {
                    echo '<li class="list-post pa-post-format">';
                    // Check for 3rd level categories (children of this sub_category)
                    if ( $show_3rd_level_cat ) {
                        $grandchild_categories_args = array(
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'parent' => $sub_category->term_id,
                            'hide_empty' => false,
                        );
                        $grandchild_categories = get_categories($grandchild_categories_args);

                        if ( !empty($grandchild_categories) ) {
                            echo function_exists('category_open_icon') ? category_open_icon() : ''; // Assuming these functions exist and are safe
                            echo '<a href="' . esc_url(get_category_link($sub_category->term_id)) . '" title="' . esc_attr($sub_category->name) . '">' . esc_html($sub_category->name) . '</a>';
                            echo '<ul class="subcat">';
                            foreach ($grandchild_categories as $grandchild_category) {
                                echo '<li class="list-post pa-post-format">';
                                echo function_exists('category_icon') ? category_icon() : '';
                                echo '<a href="' . esc_url(get_category_link($grandchild_category->term_id)) . '" title="' . esc_attr($grandchild_category->name) . '">' . esc_html($grandchild_category->name) . '</a>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        } else {
                             echo function_exists('category_icon') ? category_icon() : '';
                             echo '<a href="' . esc_url(get_category_link($sub_category->term_id)) . '" title="' . esc_attr($sub_category->name) . '">' . esc_html($sub_category->name) . '</a>';
                        }
                    } else { // Don't show 3rd level
                        echo function_exists('category_icon') ? category_icon() : '';
                        echo '<a href="' . esc_url(get_category_link($sub_category->term_id)) . '" title="' . esc_attr($sub_category->name) . '">' . esc_html($sub_category->name) . '</a>';
                    }
                    echo '</li>';
                }
                echo '</ul>';
            }
            
            // Display posts directly under the main category
            if ( !empty($cat_posts) ) {
                echo '<ul class="category-posts">';
                foreach ( $cat_posts as $p ) { // Renamed $post to $p to avoid conflict with global $post before setup_postdata
                    setup_postdata($p); // Setup post data for current post in loop
                    ?>
                    <li class="list-post pa-post-format">
                        <?php echo function_exists('post_icon') ? post_icon() : ''; ?>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </li>
                <?php
                }
                wp_reset_postdata(); // Important after custom loop with setup_postdata
                echo '</ul>';
            }
            ?>
            <a class="view-all" href="<?php echo esc_url(get_category_link($category->term_id)); ?>" > 
                <?php esc_html_e('View all', 'knowledgepress'); ?> 
                <?php echo esc_html(knowledgebase_get_post_count_for_terms(array($category->term_id))); // Counts posts only in this specific category for this link. For all children too, pass all relevant term IDs. ?>
                <?php esc_html_e('articles', 'knowledgepress'); ?>
            </a>
        </div> <?php		
    } // end foreach $categories

    if ( $row_opened ) { // Close the last row if it was opened
        echo '</div>';
    }
endif; // end if !empty($categories)

// Removed extraneous closing brace '}'
// Removed wp_reset_query(); as wp_reset_postdata() is used within the loop if needed.
// If the main page query was altered by this template, wp_reset_query() might be needed here.
// But since we use get_posts() and get_categories(), the main query is not directly affected.