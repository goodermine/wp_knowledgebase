<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package Knowledge
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 * See: https://jetpack.com/support/content-options/
 */
function knowledge_jetpack_setup() {
    // Add theme support for Infinite Scroll.
    add_theme_support(
        'infinite-scroll',
        array(
            'container' => 'main', // Ensure 'main' is the ID of your main content area
            'render'    => 'knowledge_infinite_scroll_render',
            'footer'    => 'page', // Can be false if you don't want a footer, or an ID
        )
    );

    // Add theme support for Responsive Videos.
    add_theme_support( 'jetpack-responsive-videos' );

    // Add theme support for Content Options.
    // Ensure selectors accurately match your theme's markup.
    add_theme_support(
        'jetpack-content-options',
        array(
            'post-details'    => array(
                'stylesheet' => 'knowledge-style', // Main stylesheet handle
                'date'       => '.posted-on',
                'categories' => '.cat-links',
                'tags'       => '.tags-links',
                'author'     => '.byline',
                // 'comment'    => '.comments-link', // Optional: if you want to control comment links
            ),
            'featured-images' => array(
                'archive'    => true, // Display featured images on archive pages
                'post'       => true, // Display featured images on single posts
                'page'       => true, // Display featured images on single pages
            ),
        )
    );
}
add_action( 'after_setup_theme', 'knowledge_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function knowledge_infinite_scroll_render() {
    while ( have_posts() ) {
        the_post();
        if ( is_search() ) {
            get_template_part( 'template-parts/content', 'search' ); // Assumes template-parts/content-search.php exists
        } else {
            // Attempts to load template-parts/content-{post_format}.php or template-parts/content-{post_type}.php
            // Falls back to template-parts/content.php if specific ones aren't found.
            get_template_part( 'template-parts/content', get_post_format() ?: get_post_type() );
        }
    }
}

// Removed extraneous closing brace '}'