<?php

global $pafa_faq_data,$post;

if ( count( $pafa_faq_data['questions'] ) == 0 ) {
    _e( 'No Faq Found', 'pressapps-accordion-faq' );
    return ;
}
?>
<!-- List Template -->
<div class="pressapps_faq_list pafa-list">
    <a id="pafa-top"></a>
    <?php if ( isset( $pafa_faq_data['terms'] ) ) { ?>
        <ul class="pafa-toc">
            <?php
            $i=1;
            foreach( $pafa_faq_data['terms'] as $terms ) {
                
                if ( count( $pafa_faq_data['questions'][$terms->term_id] ) > 0 ) {
                    ?>
                    <li>
                        <h2 class="pafa-list-cat"><a href="#<?php echo $terms->slug . $i++ ; ?>"><?php echo $terms->name; ?></a></h2>
                        <ul>
                            <?php
                            foreach( $pafa_faq_data['questions'][$terms->term_id] as $post ) {
                                setup_postdata($post);
                               ?>
                                <li><h3 id="faq-<?php the_ID(); ?>" class="pafa-list-q"><a href="#question-<?php the_ID(); ?>"><?php the_title()?></a></h3></li>
                               <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
        <div class="pafa-list-faqs">
            <?php
            $i=1;
            foreach( $pafa_faq_data['terms'] as $terms ) {
                if( count($pafa_faq_data['questions'][$terms->term_id]) > 0 ) {
                    ?>
                    <h2 id="<?php echo $terms->slug . $i++ ; ?>" class="pafa-list-cat"><?php echo $terms->name; ?></h2>
                    <?php
                    foreach( $pafa_faq_data['questions'][$terms->term_id] as $post ) {
                        setup_postdata($post);
                        ?>
                        <article id="question-<?php the_ID(); ?>" class="pafa type-pressapps_faq status-publish clearfix">
                            <h3 id="pafa-q-<?php the_ID(); ?>" class="pafa-list-q"><a name="<?php the_ID(); ?>"></a><?php the_title(); ?></h3>
                            <div class="pafa-a">
                                <?php the_content(); ?>
                                <a class="pafa-back-top" href="#pafa-top"><?php _e( 'Back To Top', 'pressapps-accordion-faq' ); ?></a>
                            </div>
                        </article>
                        <?php
                    }
                } 
            }
            ?>
        </div>

    <?php } else { ?>

        <ul class="pafa-toc">
        <?php
        foreach ( $pafa_faq_data['questions'] as $post ) {
            setup_postdata($post);
           ?>
            <li><h3 id="faq-<?php the_ID(); ?>" class="pafa-list-q"><a href="#question-<?php the_ID(); ?>"><?php the_title()?></a></h3></li>
           <?php
        }
        ?>
        </ul>
        <?php
        foreach ( $pafa_faq_data['questions'] as $post ) {
            setup_postdata($post);
            ?>
            <article id="question-<?php the_ID(); ?>" class="pafa type-pressapps_faq status-publish clearfix">
                <h3 class="pafa-list-q"><a name="<?php the_ID(); ?>"></a><?php the_title(); ?></h3>
                <div class="pafa-a">
                    <?php the_content(); ?>
                    <a class="pafa-back-top" href="#pafa-top"><?php _e( 'Back To Top', 'pressapps-accordion-faq' ); ?></a>
                </div>
            </article>
            
        <?php } ?>
    <?php } ?>
</div>