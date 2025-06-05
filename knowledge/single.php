<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Knowledge
 */

get_header();
?>

    <main id="primary" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', 'single' );

            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'knowledge' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'knowledge' ) . '</span> <span class="nav-title">%title</span>',
                )
            );

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>

    </main><?php // Corrected comment to match ID ?>

<?php
get_sidebar();
get_footer();

// Removed extraneous closing brace '}'
// Final PHP closing tag (?>) should also be omitted if present.