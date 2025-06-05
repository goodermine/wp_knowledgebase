<?php 
// Assuming ss_get_template_part handles any necessary HTML opening tags if it's a full head.
// If not, standard <!doctype html> and <html> tags would be expected before this.
// However, given the name 'templates/head', it likely includes these.
ss_get_template_part( 'templates/head' ); 
?>
<body <?php body_class(); ?>>
<?php 
// It's better to use wp_localize_script to pass PHP variables to JavaScript.
// Example: wp_localize_script( 'my-main-script', 'siteData', array( 'baseUrl' => esc_url( home_url( '/' ) ) ) );
// If inline script is unavoidable for some reason:
// echo "<script>var knowledgeThemeBaseUrl = '" . esc_js( esc_url( home_url( '/' ) ) ) . "';</script>\n";
?>
<a href="#content" class="skip-link sr-only screen-reader-text"><?php esc_html_e( 'Skip to main content', 'knowledge' ); // Standardized text domain ?></a> 
<?php 
global $ss_framework, $post; // $knowledgepress removed unless defined by theme/framework
// Ensure Redux is active and $meta is used safely
$meta = array();
if ( class_exists( 'ReduxFramework' ) && function_exists('redux_post_meta') ) {
    // Ensure 'knowledgepress' is the correct opt_name
    $meta = redux_post_meta( 'knowledgepress', get_the_ID() ); 
}
?>

    <?php 
    // Removed outdated IE8 conditional comment.
    ?>

    <?php 
    // The line `do_action( 'get_header' );` is unusual here. 
    // Standard WordPress themes use `get_header();` which then internally calls `do_action( 'get_header' );`.
    // This might be intentional by the framework, or `ss_get_template_part( 'templates/top-bar' );` might serve as the header.
    // For now, kept as is, assuming framework handles header output.
    do_action( 'get_header' ); 
    ?>

    <?php ss_get_template_part('templates/offcanvas'); ?>

    <?php do_action( 'shoestrap_pre_top_bar' ); ?>

    <?php ss_get_template_part( 'templates/top-bar' ); // This might be the actual theme header content ?>

    <?php do_action( 'shoestrap_pre_wrap' ); ?>
    
    <?php echo $ss_framework->open_container( 'div', 'wrap-main-section', 'wrap main-section' ); // Output of framework function, assumed safe ?>

        <?php do_action( 'shoestrap_pre_content' ); ?>

        <div id="content" class="content"> <?php // Matches skip link target ?>
            <?php echo $ss_framework->open_row( 'div', null, 'bg' ); // Assumed safe ?>

                <?php do_action( 'shoestrap_pre_main' ); ?>

                <main class="main <?php shoestrap_section_class( 'main', true ); // Assumed safe ?>" <?php if ( is_home() ) { echo 'id="home-blog"'; } ?> role="main">
                    <?php include shoestrap_template_path(); // This includes the actual WordPress template file (index.php, single.php etc.) ?>
                </main><?php do_action( 'shoestrap_after_main' ); ?>

                <?php if ( shoestrap_display_primary_sidebar() ) : // Assumed safe ?>
                    <aside id="sidebar-primary" class="sidebar <?php shoestrap_section_class( 'primary', true ); // Assumed safe ?>" role="complementary">
                        <?php 
                        if ( ! has_action( 'shoestrap_sidebar_override' ) ) {
                            include shoestrap_sidebar_path(); // Assumed safe path
                        } else {
                            do_action( 'shoestrap_sidebar_override' );
                        } 
                        ?>
                    </aside><?php endif; ?>

                <?php do_action( 'shoestrap_post_main' ); ?>

                <?php if ( shoestrap_display_secondary_sidebar() ) : // Assumed safe ?>
                    <aside id="sidebar-secondary" class="sidebar secondary <?php shoestrap_section_class( 'secondary', true ); // Assumed safe ?>" role="complementary">
                        <?php
                        // Check $meta safely
                        $secondary_sidebar_name = 'sidebar-secondary'; // Default
                        if ( isset( $meta['page_secondary_sidebar'] ) && !empty( $meta['page_secondary_sidebar'] ) ) {
                            // Sanitize or validate $meta['page_secondary_sidebar'] if it's user-configurable beyond admin selection
                            $secondary_sidebar_name = $meta['page_secondary_sidebar']; 
                        }
                        dynamic_sidebar( $secondary_sidebar_name );
                        ?>
                    </aside><?php endif; ?>
                <?php echo $ss_framework->clearfix(); // Assumed safe ?>
            <?php echo $ss_framework->close_row( 'div' ); // Assumed safe ?>
        </div><?php do_action( 'shoestrap_after_content' ); ?>
    <?php echo $ss_framework->close_container( 'div' ); // Assumed safe ?><?php

    do_action( 'shoestrap_pre_footer' );

    if ( ! has_action( 'shoestrap_footer_override' ) ) {
        ss_get_template_part( 'templates/footer' ); // Assumes this outputs the footer
    } else {
        do_action( 'shoestrap_footer_override' );
    }

    do_action( 'shoestrap_after_footer' );

    wp_footer(); // Essential WordPress hook

    ?>
</body>
</html>