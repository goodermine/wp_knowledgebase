<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://pressapps.co
 * @since      1.0.0
 *
 * @package    Pressapps_Accordion_Faq
 * @subpackage Pressapps_Accordion_Faq/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pressapps_Accordion_Faq
 * @subpackage Pressapps_Accordion_Faq/public
 * @author     PressApps
 */
class Pressapps_Accordion_Faq_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	protected $options;

	public function __construct( $plugin_name, $version ) {
		$skelet_pafa        = new Skelet("pafa");
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pressapps-accordion-faq-public.css', array(), $this->version, 'all' );
		
		global $skelet_path;
		wp_register_style( 'sk-icons', $skelet_path["uri"] .'/assets/css/sk-icons.css', array(), '1.0.0', 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $pafa;

		wp_register_script( $this->plugin_name    , plugin_dir_url(__FILE__) . 'js/pressapps-accordion-faq-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, "icons",
			array (
				'faq_close'     => $pafa->get('icon_closed') != '' ? $pafa->get('icon_closed') : 'sk-plus-add',
				'faq_open'      => $pafa->get('icon_opened') != '' ? $pafa->get('icon_opened') : 'sk-minus',
			)
		);

	}

    /**
     * Registers all shortcodes at once
     */
    public function register_shortcodes() {
    	/**
    	 * Register shortcode eg:
    	 * add_shortcode( 'voting_butons', array( $this, 'shortcode_voting_butons' ) );
    	 */
	    add_shortcode( 'faq', array( $this, 'shortcode_faq' ) );
	    add_shortcode( 'pafa_faq', array( $this, 'shortcode_faq' ) );

    }

	public function shortcode_faq( $atts = array() ) {
		return $this->get_display_faq($atts);
	}

	/**
	 *
	 * return the Final output of the FAQ html generated based on the template file
	 * and Data based on the parameter
	 *
	 * @param array $args
	 * @return string
	 */
	public function get_display_faq( $args = array() ) {
		global $pafa_faq_data,$pafa;

		$default = array (
			'category'          => -1,
			'template'          => 'accordion',
			'bg_color'  		=> '#ef3737',
			'icon_bg_color'		=> '#ef3737',
			'icon_bg_radius'	=> '0',
			'block_radius'		=> '0',
			'icon_color'  		=> '#ffffff'
		);

		$args = shortcode_atts($default,$args);

		if ( $pafa->get('reorder') == 1 ) {
			$qry_args = array(
				'post_type'     => 'faq',
				'numberposts'   => -1,
				'orderby'       => 'menu_order',
				'order'         => 'ASC',
			);
		} else {
			$qry_args = array(
				'post_type'     => 'faq',
				'numberposts'   => -1,
			);
		}

		if( isset( $args['category'] ) && $args['category'] != -1 ) {
			$qry_args['tax_query']   = array(
				array(
					'taxonomy'  => 'faq_category',
					'field'     => 'id',
					'terms'     => $args['category'],
				),
			);
			$pressapps_terms            = get_terms('faq_category',
				array(
					'child_of' => $args['category']
				)
			);
			if ( $pafa->get('reorder') == 1 ) {
				$pressapps_terms       = get_terms('faq_category',
					array(
						'child_of'  => $args['category'],
						'orderby'   => 'term_group',
						'order'     => 'ASC'
					)
				);
			} else {
				$pressapps_terms       = get_terms('faq_category',
					array(
						'child_of'  => $args['category']
					)
				);
			}
		} else {

			if ( $pafa->get('reorder') == 1 ) {
				$pressapps_terms        = get_terms('faq_category',
					array(
						'orderby'   => 'term_group',
						'order'     => 'ASC'
					)
				);
			} else {
				$pressapps_terms        = get_terms('faq_category');
			}

		}

		if ( count( $pressapps_terms ) > 0 ) {
			foreach ( $pressapps_terms as $term ) {
				$pressapps_terms_questions[ $term->term_id ] = get_posts( array_merge( $qry_args,
					array('tax_query'     =>
						array(
							array(
								'taxonomy'  => 'faq_category',
								'field'     => 'id',
								'terms'     => $term->term_id,
							)
						)
					)
				));

			}

			$pafa_faq_data = array(
				'dispaly_terms' => TRUE,
				'terms'         => $pressapps_terms,
				'questions'     => $pressapps_terms_questions,
				'template'      => $args['template'],
			);
		} else {

			$pressapps_question = get_posts($qry_args);

			$pafa_faq_data = array(
				'dispaly_terms' => FALSE,
				'questions'     => $pressapps_question,
				'template'      => $args['template'],
			);
		}

		/**
		 * Select the Proper Template file to be Render the FAQ Structure
		 *
		 */

		$default_filename           = plugin_dir_path( __FILE__ ) . "partials/pressapps-accordion-faq-public-list.php";
		$theme_default_filename     = get_stylesheet_directory() . "/pressapps-accordion-faq-public-list.php";

		$default_template_filename  = plugin_dir_path( __FILE__ ) . "partials/pressapps-accordion-faq-public-{$args['template']}.php";
		$theme_template_filename    = get_stylesheet_directory() . "/pressapps-accordion-faq-public-{$args['template']}.php";

		if( @file_exists( $theme_template_filename ) ) {
			$filename = $theme_template_filename;
		} elseif( @file_exists( $default_template_filename ) ) {
			$filename = $default_template_filename;
		} elseif( @file_exists( $theme_default_filename ) ) {
			$filename = $theme_default_filename;
		}else{
			$filename = $default_filename;
		}

		ob_start();
		include $filename;

		//enqueue frontend js
		wp_enqueue_script( $this->plugin_name );

		wp_enqueue_style( 'sk-icons' );
		wp_enqueue_style( $this->plugin_name );

		if( is_admin() ) {
			wp_enqueue_style( $this->plugin_name );
		}

		wp_reset_query();
		return ob_get_clean();

	}

	function dynamic_css() {

		global $pafa;

		$css  =  '<style type="text/css" id="faq-dynamic-css">' . "\n";
		$font_size_h2 = $pafa->get('font_size_h2') != '' ? $pafa->get('font_size_h2') : '24';
		$font_size_h3 = $pafa->get('font_size_h3') != '' ? $pafa->get('font_size_h3') : '24';
		$css .= '.pafa-list .pafa-list-cat, .pafa-accordion-cat > h2, .pafa-block-cat > h2 { font-size: ' . $font_size_h2 . 'px; }';
		$css .= '.pafa-list .pafa-list-q, .pafa-accordion .pafa-accordion-q, .pafa-block .pafa-block-q { font-size: ' . $font_size_h3 . 'px; }';
		if ( $pafa->get('icon_closed') != '' ) {
			$accordion_a_padding = $font_size_h3 + 36;
			$css .= '.pafa-accordion-a { padding-left: ' . $accordion_a_padding . 'px; }';
			$block_a_padding = $font_size_h3 + 51;
			$css .= '.pafa-block.pafa-icon .pafa-block-a { padding-left: ' . $block_a_padding . 'px; }';
		}
		if ( trim($pafa->get('custom_css')) != "" ) {
			$css .= sanitize_text_field( $pafa->get('custom_css') );
		}
		$css .= "\n</style>\n";
		echo $css;

	}
}


