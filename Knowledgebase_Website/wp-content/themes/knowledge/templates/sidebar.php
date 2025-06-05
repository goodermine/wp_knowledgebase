<?php
global $post, $knowledgepress, $meta;
$meta = redux_post_meta( 'knowledgepress', get_the_ID() );

if ( isset( $meta['page_primary_sidebar'] ) && $meta['page_primary_sidebar'] != '' ) {
	dynamic_sidebar($meta['page_primary_sidebar']); 
} else {
	dynamic_sidebar( 'sidebar-primary' );
}