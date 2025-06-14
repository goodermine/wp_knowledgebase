<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add your own custom header style via customizer.php.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Knowledge
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses knowledge_header_style()
 */
function knowledge_custom_header_setup() {
    add_theme_support(
        'custom-header',
        apply_filters(
            'knowledge_custom_header_args',
            array(
                'default-image'      => '',
                'default-text-color' => '000000', // Ensure this is a string
                'width'              => 1000,
                'height'             => 250,
                'flex-height'        => true,
                'wp-head-callback'   => 'knowledge_header_style',
            )
        )
    );
}
add_action( 'after_setup_theme', 'knowledge_custom_header_setup' );

if ( ! function_exists( 'knowledge_header_style' ) ) :
    /**
     * Styles the header image and text displayed on the blog.
     *
     * @see knowledge_custom_header_setup().
     */
    function knowledge_header_style() {
        $header_text_color = get_header_textcolor();
        $default_text_color = get_theme_support( 'custom-header', 'default-text-color' );

        // If text is not displayed, or if it is displayed but the color is the default, and there's no header image, then return.
        // This check ensures that if text is hidden, styles for hiding are applied.
        // If text is displayed and color is default, no color styles are needed.
        // The main purpose is to output styles if there's a custom color or if text needs to be hidden.
        if ( ! display_header_text() && $header_text_color === $default_text_color && !get_header_image() ) {
             // A more comprehensive check could be: if ( empty(get_header_image()) && $header_text_color === $default_text_color && !display_header_text() ) return;
             // However, the key is that if !display_header_text() is true, we must proceed to print the hiding CSS.
             // So, only return if text IS displayed AND color is default (unless other conditions like header image are also default)
             // A simpler way: if ( $header_text_color === $default_text_color && display_header_text() && !get_header_image() ) return;
             // Let's ensure styles are printed if text is hidden OR if text color is custom.
        }


        // If we get this far, we have custom styles or text hiding is needed. Let's do this.
        // We proceed if:
        // 1. Header text is hidden (we need to output .site-title, .site-description { clip... } )
        // 2. Header text is displayed AND its color is NOT the default.
        if ( ! display_header_text() && $header_text_color === $default_text_color ) {
            // If text is hidden and color is default, only print hiding styles if an image exists,
            // otherwise, there's no custom styling to output from this function.
            // But the current structure will output hiding styles regardless, which is fine.
            // The initial check `if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) { return; }`
            // should be `if ( display_header_text() && get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) { return; }`
            // This ensures that if `display_header_text()` is false, we proceed to output the hiding styles.
            if ( display_header_text() && $header_text_color === $default_text_color ) {
                 return; // Only return if text is displayed and its color is default.
            }
        }


        // If `display_header_text()` is false, or if the `header_text_color` is not the default, then we need to output styles.
        // This condition can be simplified: if styles are needed at all.
        // An empty style block is okay if no specific conditions below are met.

        ?>
        <style type="text/css">
        <?php
        // Has the text color been enabled (i.e., "Display Site Title and Tagline" is checked)?
        if ( ! display_header_text() ) : // If display_header_text() is false, hide the text.
            ?>
            .site-title,
            .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
                /* word-wrap: normal; /* This is not standard for this accessible hiding technique, can be removed */
            }
        <?php
            // If the user has set a custom color for the text use that (this implies display_header_text() is true).
        elseif ( $header_text_color !== $default_text_color ) : // Only apply color if it's not the default
            ?>
            .site-title a,
            .site-description {
                color: #<?php echo esc_attr( $header_text_color ); ?>;
            }
        <?php endif; ?>
        </style>
        <?php
    }
endif;

// Removed extraneous closing brace '}'