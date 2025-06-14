<?php
/**
 * The template for displaying the sidebar
 */
?>
<aside id="secondary" class="widget-area">
  <?php if ( is_active_sidebar( 'knowledge-sidebar' ) ) : ?>
    <?php dynamic_sidebar( 'knowledge-sidebar' ); ?>
  <?php endif; ?>
</aside>
