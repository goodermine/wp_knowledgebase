<?php

/**
 * Page titles
 */
function shoestrap_title() {
    if (is_home()) {
        if (get_option('page_for_posts', true))
            $title = get_the_title(get_option('page_for_posts', true));
        else
            $title = __('Latest Posts', 'knowledgepress');
    } elseif (is_archive()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));

        if ($term) {
            $title = apply_filters('single_term_title', $term->name);
        } elseif (is_post_type_archive()) {
            $title = apply_filters('the_title', get_queried_object()->labels->name);
        } elseif (is_day()) {
            $title = sprintf(__('Daily Archives: %s', 'knowledgepress'), get_the_date());
        } elseif (is_month()) {
            $title = sprintf(__('Monthly Archives: %s', 'knowledgepress'), get_the_date('F Y'));
        } elseif (is_year()) {
            $title = sprintf(__('Yearly Archives: %s', 'knowledgepress'), get_the_date('Y'));
        } elseif (is_author()) {
            $title = sprintf(__('Author Archives: %s', 'knowledgepress'), get_queried_object()->display_name);
        } else {
            $title = single_cat_title('', false);
        }
    } elseif (is_search()) {
        $title = sprintf(__('Search Results for %s', 'knowledgepress'), get_search_query());
    } elseif (is_404()) {
        $title = __('Not Found', 'knowledgepress');
    } else {
        $title = get_the_title();
    }

    return apply_filters('shoestrap_title', $title);
}

/**
 * Header titles
 */
function pa_header_title() {
    if (is_home()) {
        if (get_option('page_for_posts', true))
            $title = get_the_title(get_option('page_for_posts', true));
        else
            $title = __('Latest Posts', 'knowledgepress');
    } elseif (is_archive()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));

        if ($term) {
            $title = apply_filters('single_term_title', $term->name);
        } elseif (is_post_type_archive()) {
            $title = apply_filters('the_title', get_queried_object()->labels->name);
        } elseif (is_day()) {
            $title = sprintf(__('Daily Archives: %s', 'knowledgepress'), get_the_date());
        } elseif (is_month()) {
            $title = sprintf(__('Monthly Archives: %s', 'knowledgepress'), get_the_date('F Y'));
        } elseif (is_year()) {
            $title = sprintf(__('Yearly Archives: %s', 'knowledgepress'), get_the_date('Y'));
        } elseif (is_author()) {
            $title = sprintf(__('Author Archives: %s', 'knowledgepress'), get_queried_object()->display_name);
        } else {
            $title = single_cat_title('', false);
        }
    } elseif (is_search()) {
        $title = sprintf(__('Search Results for %s', 'knowledgepress'), get_search_query());
    } elseif (is_404()) {
        $title = __('Not Found', 'knowledgepress');
    } else {
        $_post = get_queried_object();
//        global $pakb_settings;
//        if (isset($pakb_settings['knowledgebase_page']) && isset($_post->ID)) {
//            if ($pakb_settings['knowledgebase_page'] == $_post->ID) {
//                if (!empty($pakb_settings['knowledgebase_title'])) {
//                    $title = $pakb_settings['knowledgebase_title'];
//                } else {
//                    $title = $_post->post_title;
//                }
//            } else {
//                $title = $_post->post_title;
//            }
//        } else {
//            $title = $_post->post_title;
//        }
        $title = get_the_title(); //$_post->post_title;
    }

    return apply_filters('pa_header_title', $title);
}

function pa_wp_title() {
    global $wpdb, $wp_locale, $pakb_settings;
    $_post = get_queried_object();
    $sep = '|';
    $m = get_query_var('m');
    $year = get_query_var('year');
    $monthnum = get_query_var('monthnum');
    $day = get_query_var('day');
    $search = get_query_var('s');
    $title = '';
    $seplocation = 'left';

    $t_sep = '-'; // Temporary separator, for accurate flipping, if necessary
    // If there is a post
    if (is_single() || ( is_home() && !is_front_page() ) || ( is_page() && !is_front_page() )) {
        $title = single_post_title('', false);
    }

    if (is_home()) {
        $title = 'Home';
    }
    // If there's a post type archive
    if (is_post_type_archive()) {
        $post_type = get_query_var('post_type');
        if (is_array($post_type))
            $post_type = reset($post_type);
        $post_type_object = get_post_type_object($post_type);
        if (!$post_type_object->has_archive)
            $title = post_type_archive_title('', false);
    }

    // If there's a category or tag
    if (is_category() || is_tag()) {
        $title = single_term_title('', false);
    }

    // If there's a taxonomy
    if (is_tax()) {
        $term = get_queried_object();
        if ($term) {
            $tax = get_taxonomy($term->taxonomy);
            $title = single_term_title('', false);
        }
    }

    // If there's an author
    if (is_author()) {
        $author = get_queried_object();
        if ($author)
            $title = $author->display_name;
    }

    // Post type archives with has_archive should override terms.
    if (is_post_type_archive() && $post_type_object->has_archive)
        $title = post_type_archive_title('', false);

    // If there's a month
    if (is_archive() && !empty($m)) {
        $my_year = substr($m, 0, 4);
        $my_month = $wp_locale->get_month(substr($m, 4, 2));
        $my_day = intval(substr($m, 6, 2));
        $title = $my_year . ( $my_month ? $t_sep . $my_month : '' ) . ( $my_day ? $t_sep . $my_day : '' );
    }

    // If there's a year
    if (is_archive() && !empty($year)) {
        $title = $year;
        if (!empty($monthnum))
            $title .= $t_sep . $wp_locale->get_month($monthnum);
        if (!empty($day))
            $title .= $t_sep . zeroise($day, 2);
    }

    // If it's a search
    if (is_search()) {
        /* translators: 1: separator, 2: search phrase */
        $title = sprintf(__('Search Results %1$s %2$s', 'knowledgepress'), $t_sep, strip_tags($search));
    }

    // If it's a knowledgebase page
//    if (isset($pakb_settings['knowledgebase_page']) && isset($_post->ID)) {
//        if ($pakb_settings['knowledgebase_page'] == $_post->ID) {
//            if (!empty($pakb_settings['knowledgebase_title'])) {
//                $title = $pakb_settings['knowledgebase_title'];
//            } else {
//                $title = $_post->post_title;
//            }
//        }
//    }

    // If it's a 404 page
    if (is_404()) {
        $title = __('Page not found', 'knowledgepress');
    }

    // If it's a page
    if (!is_home() && is_front_page()) {
        $title = $_post->post_title;
    }

    // Determines position of the separator and direction of the breadcrumb
    if ('right' == $seplocation) { // sep on right, so reverse the order
        $title = $title . '-' . get_bloginfo();
        $title_array = explode($t_sep, $title);
        $title_array = array_reverse($title_array);
        $title = implode(" $sep ", $title_array);
    } else {
        $title = $title . '-' . get_bloginfo();
        $title_array = explode($t_sep, $title);
        $title = implode(" $sep ", $title_array);
    }

    return $title;
}

add_filter('wp_title', 'pa_wp_title', 100);

/**
 * The title secion.
 * Includes a <head> element and link.
 */
function shoestrap_title_section($header = true, $element = 'h1', $link = false, $class = 'entry-title') {

    global $ss_settings;

    $content = $header ? '<header>' : '';
    $content .= '<title>' . get_the_title() . '</title>';
    $content .= '<' . $element . ' class="' . $class . '">';
    if ($ss_settings['archive_post_format_icons'] == 1 && ( is_archive() || is_search() || is_home() )) {
        if (get_post_format() == 'image') {
            $post_format_icon = '<i class="kp-image4"></i> ';
        } elseif (get_post_format() == 'video') {
            $post_format_icon = '<i class="kp-film3"></i> ';
        } elseif (get_post_format() == 'gallery') {
            $post_format_icon = '<i class="kp-file-text2"></i> ';
        } elseif (get_post_format() == 'link') {
            $post_format_icon = '<i class="kp-link2"></i> ';
        } elseif (get_post_format() == 'quote') {
            $post_format_icon = '<i class="kp-file-text2"></i> ';
        } elseif (get_post_format() == 'status') {
            $post_format_icon = '<i class="kp-file-text2"></i> ';
        } elseif (get_post_format() == 'audio') {
            $post_format_icon = '<i class="kp-volume-medium3"></i> ';
        } elseif (get_post_format() == 'chat') {
            $post_format_icon = '<i class="kp-file-text2"></i> ';
        } elseif (get_post_format() == 'aside') {
            $post_format_icon = '<i class="kp-file-text2"></i> ';
        } else {
            $post_format_icon = '<i class="kp-file-text2"></i> ';
        }

        $content .= $post_format_icon;
    }


    $content .= $link ? '<a href="' . get_permalink() . '">' : '';
    $content .= $link ? '<a href="' . get_permalink() . '">' : '';
    $content .= is_singular() ? shoestrap_title() : apply_filters('shoestrap_title', get_the_title());
    $content .= $link ? '</a>' : '';
    $content .= $link ? '</a>' : '';
    $content .= '</' . $element . '>';
    $content .= $header ? '</header>' : '';

    echo apply_filters('shoestrap_title_section', $content);
}
