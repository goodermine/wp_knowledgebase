<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Global skelet shortcodes variable
 */
  global $skelet_shortcodes;

/**
 * Feedback Survey Shortcode options and settings
 */
$skelet_shortcodes[]     = sk_shortcode_apply_prefix(array(
    'title'      => 'ACCORDION FAQ',
    'shortcodes' => array(
        array(
            'name'      => 'faq',
            'title'     => __( 'Insert FAQs',               'pressapps-accordion-faq' ),
            'fields'    => array (
                array (
                    'id'        => 'template',
                    'type'      => 'select',
                    'title'     => __( 'Template',         'pressapps-accordion-faq' ),
                    'options'   => array (
                        'list'          => __( 'List',      'pressapps-accordion-faq' ),
                        'accordion'     => __( 'Accordion', 'pressapps-accordion-faq' ),
                        'block'         => __( 'Block',     'pressapps-accordion-faq' ),
                    ),
                    'default'   => 'accordion',
                ),
                array(
                    'id'             => 'category',
                    'type'           => 'select',
                    'title'          => __( 'Faq Category', 'pressapps-accordion-faq' ),
                    'options'        => 'categories',
                    'query_args'     => array(
                        'type'         => 'faq',
                        'taxonomy'     => 'faq_category',
                    ),
                    'default_option' => 'All Categories',
                ),
                array (
                    'id'        => 'bg_color',
                    'type'      => 'color_picker',
                    'title'     => __( 'Background Color',  'pressapps-accordion-faq' ),
                    'default'   => '#ef3737',
                    'dependency'   => array( 'template', '==', 'block' ),
                ),
                array (
                    'id'        => 'block_radius',
                    'type'    => 'number',
                    'title'   => 'Block Radius',
                    'default' => '0',
                    'after'   => '<i class="sk-text-muted">px</i>',
                    'dependency'   => array( 'template', '==', 'block' ),
                ),
                array (
                    'id'        => 'icon_color',
                    'type'      => 'color_picker',
                    'title'     => __( 'Icon Color',  'pressapps-accordion-faq' ),
                    'default'   => '#ffffff',
                    'dependency'   => array( 'template', '==', 'accordion' ),
                ),
                array (
                    'id'        => 'icon_bg_color',
                    'type'      => 'color_picker',
                    'title'     => __( 'Icon Background Color',  'pressapps-accordion-faq' ),
                    'default'   => '#ef3737',
                    'dependency'   => array( 'template', '==', 'accordion' ),
                ),
                array(
                    'id'      => 'icon_bg_radius',
                    'type'    => 'number',
                    'title'   => 'Icon Background Radius',
                    'default' => '0',
                    'after'   => '<i class="sk-text-muted">px</i>',
                    'dependency'   => array( 'template', '==', 'accordion' ),
                ),
            ),
        ),
    ),
));