<?php 
global $post;
$categories = get_the_category($post->ID);
if ($categories) {
	$category_ids = array();
    foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;

        $args=array(
        'category__in' => $category_ids,
        'post__not_in' => array($post->ID),
        'posts_per_page'=> 6, // Number of related posts that will be shown.
        'ignore_sticky_posts'=>1
        );

        $related_query = new wp_query( $args );
        
    	if( $related_query->have_posts() ) { ?>
             
        <div id="related" class="clearfix">
            <h3><?php _e( 'Related Articles', 'knowledgepress' ); ?></h3>
             	<ul class="clearfix">
                <?php
                while( $related_query->have_posts() ) {
                	$related_query->the_post();
                    ?>
            	    <li class="list-post pa-post-format"><?php echo post_icon(); ?><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php 
                }
                ?>
                </ul>
        </div>
        
    <?php    
    }
}
wp_reset_query(); 
