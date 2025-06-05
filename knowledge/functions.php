<?php
/**
 * Knowledge Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Knowledge
 */

if ( ! function_exists( 'knowledge_theme_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function knowledge_theme_setup() {
        load_theme_textdomain( 'knowledge', get_template_directory() . '/languages' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary Menu', 'knowledge' ), // Updated for title case consistency
        ) );
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );
        add_theme_support( 'custom-background', apply_filters( 'knowledge_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );
        add_theme_support( 'customize-selective-refresh-widgets' );
        add_theme_support( 'custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ) );
    }
endif;
add_action( 'after_setup_theme', 'knowledge_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
function knowledge_theme_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' ); // Get theme version for dynamic asset versioning

    wp_enqueue_style( 'knowledge-style', get_stylesheet_uri(), array(), $theme_version );

    // Dynamic versioning for script file.
    $script_path = get_template_directory() . '/js/script.js';
    $script_version = file_exists( $script_path ) ? filemtime( $script_path ) : $theme_version;
    wp_enqueue_script( 'knowledge-script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), $script_version, true );

    // wp_enqueue_style( 'knowledge-print-style', get_template_directory_uri() . '/css/print.css', array(), $theme_version, 'print' );
}
add_action( 'wp_enqueue_scripts', 'knowledge_theme_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

// Removed extraneous closing brace '}'
// Final PHP closing tag (?>) should also be omitted if present.