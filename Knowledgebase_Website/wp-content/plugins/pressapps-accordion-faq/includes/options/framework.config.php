<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Framework page settings
 */
$settings = array(
    'header_title' => __('Accordion Faq', 'pressapps-accordion-faq'),
    'menu_title'   => __('Accordion Faq', 'pressapps-accordion-faq'),
    'menu_type'    => 'add_submenu_page',
    'menu_slug'    => 'pressapps-accordion-faq',
    'ajax_save'    => false,
);


/**
 * sections and fields option
 * @var array
 */
$options        = array();

/*
 *  Styling options tab and fields settings
 */
$options[]      = array (
    'name'        => 'general',
    'title'       => __('General', 'pressapps-accordion-faq'),
    'icon'        => 'fa fa-cogs',
    'fields'      => array(
        array(
            'title'     => __('Drag & Drop Reorder', 'pressapps-accordion-faq'),
            'id'        => 'reorder',
            'type'      => 'switcher',
            'default' => true
        ),
        array(
            'id'        => 'icon_closed',
            'type'      => 'icon',
            'title'     => __('Closed Icon', 'pressapps-accordion-faq'),
            'default'   => 'si-plus3'
        ),
        array(
            'id'        => 'icon_opened',
            'type'      => 'icon',
            'title'     => __('Opened Icon', 'pressapps-accordion-faq'),
            'default'   => 'si-minus3'
        ),
        array(
            'id'      => 'font_size_h2',
            'type'    => 'number',
            'title'   => 'Category Title Font Size',
            'after'   => ' <i class="sk-text-muted">px</i>',
            'default' => 26,
        ),
        array(
            'id'      => 'font_size_h3',
            'type'    => 'number',
            'title'   => 'Faq Title Font Size',
            'after'   => ' <i class="sk-text-muted">px</i>',
            'default' => 20,
        ),
        array(
            'id'        => 'custom_css',
            'type'      => 'textarea',
            'title'     => __('Custom CSS', 'pressapps-accordion-faq'),
        ),
    ),
);

SkeletFramework::instance( $settings, $options );
