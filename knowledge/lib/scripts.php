<?php
/**
 * Enqueue scripts and stylesheets
 */
global $ss_settings;
function shoestrap_scripts() {

	wp_enqueue_style( 'knowledgepress_css', SHOESTRAP_ASSETS_URL . '/css/main.css', false );

	wp_register_script( 'scripts', SHOESTRAP_ASSETS_URL . '/js/scripts.js', false, null, false);
	wp_register_script( 'modernizr', SHOESTRAP_ASSETS_URL . '/js/vendor/modernizr.js', false, null, false );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'scripts' );

	if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

function pa_admin_scripts() {
  wp_register_script('pa_admin_js', SHOESTRAP_ASSETS_URL . '/js/vendor/admin.js');
  wp_enqueue_script('pa_admin_js');
}

function papc_reorder_scripts() {

  global $pagenow;

  if( $pagenow == 'edit.php') {
      if ( !isset($_GET['post_type']) || 'post' == $_GET['post_type'] ) {
          wp_register_style('pressapps_order-admin-styles', SHOESTRAP_ASSETS_URL . '/css/reorder.css');
          wp_register_script('pressapps_order-update-order', SHOESTRAP_ASSETS_URL . '/js/vendor/order-posts.js');
          wp_enqueue_script('jquery-ui-sortable');
          wp_enqueue_script('pressapps_order-update-order');
          wp_enqueue_style('pressapps_order-admin-styles');         
      }
  } elseif( $pagenow == 'edit-tags.php' ) {
      if ( isset($_GET['taxonomy']) && 'category' == $_GET['taxonomy'] ) {
          wp_register_style('pressapps_order-admin-styles', SHOESTRAP_ASSETS_URL . '/css/reorder.css');
          wp_register_script('pressapps_order-update-order', SHOESTRAP_ASSETS_URL . '/js/vendor/order-taxonomies.js');
          wp_enqueue_script('jquery-ui-sortable');
          wp_enqueue_script('pressapps_order-update-order');
          wp_enqueue_style('pressapps_order-admin-styles');
      }
  } 
}

add_action( 'wp_enqueue_scripts', 'shoestrap_scripts', 100 );
add_action( 'admin_enqueue_scripts', 'pa_admin_scripts' );
if ( isset( $ss_settings['reorder'] ) && $ss_settings['reorder'] == 1 ) {
  add_action( 'admin_enqueue_scripts', 'papc_reorder_scripts' );
}
