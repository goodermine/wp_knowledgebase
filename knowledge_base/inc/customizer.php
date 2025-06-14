<?php
/**
 * Knowledge Theme Customizer
 *
 * @package Knowledge
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function knowledge_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    // Check if header_textcolor setting exists before trying to set transport, core themes do this.
    if ( isset( $wp_customize->get_setting( 'header_textcolor' )->transport ) ) {
        $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    }


    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a', // Ensure this selector matches your theme's HTML
                'render_callback' => 'knowledge_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description', // Ensure this selector matches your theme's HTML
                'render_callback' => 'knowledge_customize_partial_blogdescription',
            )
        );
    }
}
add_action( 'customize_register', 'knowledge_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function knowledge_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function knowledge_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function knowledge_customize_preview_js() {
    $theme_version = wp_get_theme()->get( 'Version' );
    $script_path = get_template_directory() . '/js/customizer.js';
    $script_version = file_exists( $script_path ) ? filemtime( $script_path ) : $theme_version;

    wp_enqueue_script( 'knowledge-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), $script_version, true );
}
add_action( 'customize_preview_init', 'knowledge_customize_preview_js' );

// You would add any custom customizer controls here

// Removed extraneous closing brace '}'