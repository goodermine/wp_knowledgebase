<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Knowledge
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
                <?php
                knowledge_posted_on();
                knowledge_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php knowledge_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'knowledge' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            )
        );

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knowledge' ),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php knowledge_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
