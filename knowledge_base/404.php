<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Knowledge
 */

// Assuming 'knowledge' is the correct primary text domain.
// If $ss_framework is essential, ensure it's available.
global $ss_framework; 

if ( $ss_framework && function_exists('ss_get_template_part') ) { // Check if framework function exists
    ss_get_template_part('templates/page', 'header'); // Framework-specific header part
} else {
    get_header(); // Fallback to standard WordPress header if framework part is unavailable
}
?>

<main id="primary" class="site-main error-404 not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'knowledge' ); // Standardized text domain ?></h1>
    </header><div class="page-content">
        <?php
        if ( $ss_framework && method_exists($ss_framework, 'alert') ) {
            $alert_message = __( 'Sorry, but the page you were trying to view does not exist.', 'knowledge' ); // Standardized text domain
            // Assuming $ss_framework->alert() handles its own escaping.
            echo $ss_framework->alert( 'warning', $alert_message ); 
        } else {
            // Fallback message if framework alert is not available
            echo '<p class="alert alert-warning">' . esc_html__( 'Sorry, but the page you were trying to view does not exist.', 'knowledge' ) . '</p>';
        }
        ?>

        <p><?php esc_html_e( 'It looks like this was the result of either:', 'knowledge' ); // Standardized text domain ?></p>
        <ul>
            <li><?php esc_html_e( 'a mistyped address', 'knowledge' ); // Standardized text domain ?></li>
            <li><?php esc_html_e( 'an out-of-date link', 'knowledge' ); // Standardized text domain ?></li>
        </ul>
        <?php get_search_form(); ?>
    </div></main><?php
// Assuming ss_get_template_part would include a sidebar/footer if needed via its own logic.
// If not, standard get_sidebar() / get_footer() might be needed if framework header doesn't include them.
if ( $ss_framework && function_exists('ss_get_template_part') ) {
    // Assuming the framework handles sidebar/footer if 'templates/page-header' implies a full structure.
    // Or, if 'templates/page-header' is just the <header> part, you'd need framework equivalents of get_sidebar/get_footer
    // or fallbacks. For this example, we assume the framework might handle it or we use standard WP.
} else {
    get_sidebar(); 
    get_footer();
}


// Removed extraneous closing brace '}'
// Final PHP closing tag (?>) should also be omitted.