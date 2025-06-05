<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://pressapps.co
 * @since      1.0.0
 *
 * @package    Pressapps_Accordion_Faq
 * @subpackage Pressapps_Accordion_Faq/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pressapps_Accordion_Faq
 * @subpackage Pressapps_Accordion_Faq/admin
 * @author     PressApps
 */
class Pressapps_Accordion_Faq_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	protected $options;

	public function __construct( $plugin_name, $version ) {
		$skelet_pafa        = new Skelet("pafa");
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
	}

	/**
	 * Add the Additional Columns For the faq_category Taxonomy
	 *
	 * @param array $columns
	 * @return array
	 */
	public function manage_edit_faq_category_columns( $columns ) {

		$new_columns['cb']          = $columns['cb'];
		$new_columns['name']        = $columns['name'];
		$new_columns['shortcode']   = __("Shortcode",'pressapps-accordion-faq');
		$new_columns['slug']        = $columns['slug'];
		$new_columns['posts']       = $columns['posts'];

		return $new_columns;
	}

	/**
	 *
	 * Rename the Columns for the faq post type and adding new Columns
	 *
	 * @param array $columns
	 * @return array
	 */

	public function manage_edit_faq_columns( $columns ) {

		$new_columns['cb']          = $columns['cb'];
		$new_columns['title']       = __('Question','pressapps-accordion-faq');
		$new_columns['category']    = __('Category','pressapps-accordion-faq');
		$new_columns['date']        = $columns['date'];

		return $new_columns;
	}

	/**
	 *
	 * Add the Additional column Values for the faq_category Taxonomy
	 *
	 * @param string $out
	 * @param string $column
	 * @param int $term_id
	 * @return string
	 */
	public function manage_faq_category_custom_column( $out, $column, $term_id ) {
		switch( $column ) {
			case 'shortcode':
				$temp = '[pafa_faq category=' . $term_id . ']';
				return $temp;
				break;
		}
	}

	/**
	 *
	 * Add the Additional column Values for the faq Post Type
	 *
	 * @global type $post
	 * @param string $column
	 */

	public function manage_faq_custom_column( $column ) {
		global $post;
		switch( $column ) {
			case 'category':
				$terms = wp_get_object_terms($post->ID  ,'faq_category');
				foreach($terms as $term){
					$temp  = " <a href=\"" . esc_url( admin_url( 'edit-tags.php?action=edit&taxonomy=faq_category&tag_ID=' . $term->term_id . '&post_type=faq' ) ) . "\" ";
					$temp .= " class=\"row-title\">{$term->name}</a><br/>";
					echo $temp;
				}
				break;
		}
	}

	/**
	 * Category Based Filtering options
	 *
	 * @global string $typenow
	 */

	function restrict_manage_posts() {
		global $typenow;

		if ( $typenow == 'faq' ) {
			?>
			<select name="faq_category">
				<option value="0"><?php _e('Selecte Category','pressapps-accordion-faq'); ?></option>
				<?php
				$categories = get_terms( 'faq_category' );
				if ( count($categories) > 0 ) {
					foreach ( $categories as $cat ) {
						if( isset( $_GET['faq_category'] ) && $_GET['faq_category'] == $cat->slug ) {
							echo "<option value={$cat->slug} selected=\"selected\">{$cat->name}</option>";
						} else {
							echo "<option value={$cat->slug} >{$cat->name}</option>";
						}
					}
				}
				?>
			</select>
			<?php
		}
	}

	/**
	 * Shortcode field for the Edit Taxonomy Page
	 *
	 * @param string $taxonomy
	 */

	public function faq_category_edit_form_fields( $taxonomy ) {
		$tag_id = $_GET['tag_ID'];
		?>
		<tr>
			<th scope="row" valign="top"><label for="shortcode"><?php _e('Shortcode','pressapps-accordion-faq');?></label></th>
			<td>[pafa_faq category=<?php echo $tag_id; ?>]</td>
		</tr>
		<?php
	}

	public function order_save_order() {

		global $wpdb;

		$action             = $_POST['action'];
		$posts_array        = $_POST['post'];
		$listing_counter    = 1;

		foreach ($posts_array as $post_id) {

			$wpdb->update(
				$wpdb->posts,
				array('menu_order'  => $listing_counter),
				array('ID'          => $post_id)
			);

			$listing_counter++;
		}

		die();
	}

	public function order_save_taxonomies_order() {
		global $wpdb;

		$action             = $_POST['action'];
		$tags_array         = $_POST['tag'];
		$listing_counter    = 1;

		foreach ($tags_array as $tag_id) {

			$wpdb->update(
				$wpdb->terms,
				array('term_group'  => $listing_counter),
				array('term_id'     => $tag_id)
			);

			$listing_counter++;
		}

		die();
	}

	public function order_reorder_taxonomies_list( $orderby, $args ) {
		$orderby = "t.term_group";
		return $orderby;
	}


	public function order_reorder_list( $query ) {
		$query->set( 'orderby',  'menu_order' );
		$query->set( 'order',    'ASC' );
		return $query;
	}

	/**
	 * Register FAQ Custom Post Type
	 */
	public function register_cpt() {
		register_post_type( 'faq',array(
			'description'           => __('FAQ Articles','pressapps-accordion-faq'),
			'labels'                => array(
				'name'                  => __('FAQ'                     ,'pressapps-accordion-faq'),
				'all_items'             => __('All FAQs'                 ,'pressapps-accordion-faq'),
				'singular_name'         => __('FAQ'                     ,'pressapps-accordion-faq'),
				'add_new'               => __('Add New'                 ,'pressapps-accordion-faq'),
				'add_new_item'          => __('Add New FAQ'             ,'pressapps-accordion-faq'),
				'edit_item'             => __('Edit FAQ'                ,'pressapps-accordion-faq'),
				'new_item'              => __('New FAQ'                 ,'pressapps-accordion-faq'),
				'view_item'             => __('View FAQ'                ,'pressapps-accordion-faq'),
				'search_items'          => __('Search FAQ'              ,'pressapps-accordion-faq'),
				'not_found'             => __('No FAQ found'            ,'pressapps-accordion-faq'),
				'not_found_in_trash'    => __('No FAQ found in Trash'   ,'pressapps-accordion-faq'),
			),
			'public'                => true,
			'menu_position'         => 5,
			'rewrite'               => array( 'slug' => 'faq' ),
			'supports'              => array( 'title', 'editor' /*,'page-attributes' */),
			'public'                => true,
			'show_ui'               => true,
			'publicly_queryable'    => true,
			'exclude_from_search'   => false,
			'menu_icon'				=> 'dashicons-editor-help',
		));
	}

	/**
	 * Register faq custom taxonomy
	 */
	public function register_taxonomy() {
		register_taxonomy( 'faq_category',array( 'faq' ),array(
			'hierarchical'  => false,
			'labels'        => array(
				'name'              => __( 'Categories'             ,'pressapps-accordion-faq'),
				'singular_name'     => __( 'Category'               ,'pressapps-accordion-faq'),
				'search_items'      => __( 'Search Categories'      ,'pressapps-accordion-faq'),
				'all_items'         => __( 'All Categories'         ,'pressapps-accordion-faq'),
				'parent_item'       => __( 'Parent Category'        ,'pressapps-accordion-faq'),
				'parent_item_colon' => __( 'Parent Category:'       ,'pressapps-accordion-faq'),
				'edit_item'         => __( 'Edit Category'          ,'pressapps-accordion-faq'),
				'update_item'       => __( 'Update Category'        ,'pressapps-accordion-faq'),
				'add_new_item'      => __( 'Add New Category'       ,'pressapps-accordion-faq'),
				'new_item_name'     => __( 'New Category Name'      ,'pressapps-accordion-faq'),
				'popular_items'     => NULL,
				'menu_name'         => __( 'Categories'             ,'pressapps-accordion-faq'),
			),
			'show_ui'       => true,
			'public'        => true,
			'query_var'     => true,
			'hierarchical'  => true,
			'rewrite'       => array( 'slug' => 'faq_category' ),
		));
	}

	public function help_tab() {
		$screen = get_current_screen();
		if( in_array( $screen->id, array( 'edit-faq_category', 'faq', 'edit-faq') ) ) {
			$screen->add_help_tab( array (
				'id'	=> 'pressappsfaq_shortcode',
				'title'	=> __( 'Faq Shortcodes', 'pressapps-accordion-faq' ),
				'content'	=>
					'<p>' . __('<h2>Faq Shortcodes</h2>','pressapps-accordion-faq') . '</p>' .
					'<p>' . __( 'You can use <code>[pafa_faq]</code> shortcode to include the Faqs on any page, post or custom post type.', 'pressapps-accordion-faq' ) . '</p>' .
					'<p>' . __( 'The shortcode accepts two optional attributes:', 'pressapps-accordion-faq' ) . '</p>' .
					'<p>' . __( '(1) <b>category</b> = <i>-1</i> <b>|</b> <i>{any faq category id}</i>', 'pressapps-accordion-faq' ) . '</p>' .
					'<p>' . __( '(2) <b>template</b> = <i>accordion</i> <b>|</b> <i>{any custom/existing template}</i>', 'pressapps-accordion-faq' ) . '</p>' .
					'<p>' . __( '<b>Examples</b>', 'pressapps-accordion-faq' ) . '</p>' .
					'<p>' . __( '1. <code>[pafa_faq]</code>', 'pressapps-accordion-faq' ) . '</p>' .
					'<p>' . sprintf(__( '2. <code>[pafa_faq category={category_id}]</code> {category_id} you will find it <a href="%s">here</a> under shortcode column', 'pressapps-accordion-faq' ),admin_url('edit-tags.php?taxonomy=faq_category&post_type=faq') ). '</p>' .
					'<p>' . __( '3. <code>[pafa_faq category={category_id} template=\'accordion\']</code>', 'pressapps-accordion-faq' ) . '</p>'
			));
		}
	}

	public function order_load_scripts() {
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( $this->plugin_name . '_order-update-post-order' );

		wp_enqueue_style( $this->plugin_name );
	}

	public function order_load_scripts_taxonomies() {
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( $this->plugin_name . '_order-update-taxonomy-order' );

		wp_enqueue_style( $this->plugin_name );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pressapps-accordion-faq-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name,                                     plugin_dir_url( __FILE__ ) . 'js/pressapps-accordion-faq-admin.js',             array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name . '_order-update-post-order',        plugin_dir_url( __FILE__ ) . 'js/pressapps-accordion-faq-order-posts.js',       array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name . '_order-update-taxonomy-order',    plugin_dir_url( __FILE__ ) . 'js/pressapps-accordion-faq-order-taxonomies.js',  array( 'jquery' ), $this->version, false );

	}

    /**
     * Adds a link to the plugin settings page
     */
    public function settings_link( $links ) {

        $settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=' . $this->plugin_name ), __( 'Settings', 'pressapps-accordion-faq' ) );

        array_unshift( $links, $settings_link );

        return $links;

    }

    /**
     * Adds links to the plugin links row
     */
    public function row_links( $links, $file ) {

        if ( strpos( $file, $this->plugin_name . '.php' ) !== false ) {

            $link = '<a href="http://pressapps.co/help/" target="_blank">' . __( 'Help', 'pressapps-accordion-faq' ) . '</a>';

            array_push( $links, $link );

        }

        return $links;

    }
}
