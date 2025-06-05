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
                'default-text-color' => '000000',
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

        /*
         * If no custom header image is set, fallback to the header text color.
         * If no custom header image is set and no custom text color is set,
         * skip the header style entirely.
         * If text color is set to 'blank', hide text.
         */
        if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
            return;
        }

        // If we get this far, we have custom styles. Let's do this.
        ?>
        <style type="text/css">
        <?php
        // Has the text color been enabled?
        if ( ! display_header_text() ) :
            ?>
            .site-title,
            .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
                word-wrap: normal;
            }
        <?php
            // If the user has set a custom color for the text use that.
        else :
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
