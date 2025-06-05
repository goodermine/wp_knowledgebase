<?php
global $ss_settings;
$side_admin_btn = $ss_settings['side_admin_btn'];
$side_login_btn = $ss_settings['side_login_btn'];
$side_register_btn = $ss_settings['side_register_btn'];
?>
<nav id="offcanvas" class="navmenu navmenu-inverse navmenu-fixed-right offcanvas" role="navigation">
    <?php if (is_user_logged_in()) { ?>
        <ul class="nav navmenu-nav nav-user">
            <li>
                <?php 
                $current_user = wp_get_current_user();
                $current_user_name = $current_user->display_name;
                ?>
                <a href="<?php echo get_author_posts_url(get_current_user_id()); ?>"><?php echo get_avatar( get_current_user_id(), 40 ); ?><?php echo esc_attr($current_user_name); ?></a>
            </li>
        </ul>
    <?php } ?>

	<?php
	if (has_nav_menu('sidebar_navigation')) :
		wp_nav_menu( array( 'theme_location' => 'sidebar_navigation', 'walker' => new Shoestrap_Sidebar_Walker(), 'menu_class' => 'nav navmenu-nav' ) );
	endif;
	?>

	<?php if ( is_active_sidebar( 'side-navbar' ) ) : ?>
		<div class="side-navbar">
			<?php dynamic_sidebar( 'side-navbar' ); ?>
		</div>
	<?php endif; ?>

	<?php if (is_user_logged_in() && ($side_login_btn || $side_admin_btn)) { ?>
	    <ul class="nav navmenu-nav">
	    	<?php if ($side_admin_btn) { ?>
				<li class="has-button"><a class="btn navmenu-btn" href="<?php echo admin_url(); ?>"><?php _e('Admin', 'knowledgepress' ); ?></a></li>
			<?php } ?>
	    	<?php if ($side_login_btn) { ?>
				<li class="has-button"><a class="btn navmenu-btn" href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e('Logout', 'knowledgepress' ); ?></a></li>
			<?php } ?>
	    </ul>
	<?php } elseif (!is_user_logged_in() && ($side_login_btn || $side_register_btn)) { ?>
	    <ul class="nav navmenu-nav">
	        <?php if ($side_login_btn) { ?>
	        	<li class="has-button"><a href="<?php echo wp_login_url(get_permalink() ); ?>" title="Login" class="btn navmenu-btn"><?php _e('Login', 'knowledgepress' ); ?></a></li>
	        <?php } ?>
	        <?php if ($side_register_btn) { ?>
	            <li class="has-button"><a href="<?php echo wp_registration_url(); ?>" title="Register" class="btn navmenu-btn"><?php _e('Register', 'knowledgepress' ); ?></a></li>
	        <?php } ?>
	    </ul>
    <?php } ?>
    <?php do_action( 'shoestrap_side_nav_end' ); ?>
</nav>

