<?php

global $pafa_faq_data,$post,$pafa;

if ( count( $pafa_faq_data['questions'] ) == 0 ) {
	_e('No Faq Found','pressapps-accordion-faq');
	return ;
}

$icon_closed = $pafa->get( 'icon_closed' );

if ( isset( $icon_closed ) && $icon_closed != '' ) {
	$icon 	= '<span><i class="' . $icon_closed . '"></i></span>';
	$class 	= ' pafa-icon';
} else {
	$icon 	= '';
	$class  = '';
}

?>
<!-- Block template -->
<div class="pressapps_faq_block" id="cat-<?php echo max( $args['category'], 0 ); ?>">
	<style type="text/css" rel="stylesheet">
		#cat-<?php echo max( $args['category'], 0 ); ?> .pafa-block:hover, #cat-<?php echo max( $args['category'], 0 ); ?> .pafa-block-open { background-color: <?php echo $args['bg_color']; ?>; }
		#cat-<?php echo max( $args['category'], 0 ); ?> .pafa-block { border-radius: <?php echo $args['block_radius']; ?>px; }
	</style>
	<?php
	if ( isset( $pafa_faq_data['terms'] ) ) {
		$i = 1;
		foreach ( $pafa_faq_data['terms'] as $terms ) {
			if ( count( $pafa_faq_data['questions'][ $terms->term_id ] ) > 0 ) {
				?>
				<div class="pafa-block-cat">
					<h2><?php echo $terms->name; ?></h2>
					<?php
					foreach( $pafa_faq_data['questions'][ $terms->term_id ] as $post ) {
						setup_postdata( $post );
						?>
						<div class="pafa-block<?php echo $class; ?>">
							<h3 class="pafa-block-q"><?php echo $icon; ?><?php the_title(); ?></h3>
							<div class="pafa-block-a">
								<?php the_content(); ?>
							</div>
						</div>
						<?php
					} ?>
				</div>
			<?php }
		}

	} else {
		foreach ( $pafa_faq_data['questions'] as $post ) {
			setup_postdata($post);
			?>
			<div class="pafa-block<?php echo $class; ?>">
				<h3 class="pafa-block-q"><?php echo $icon; ?><?php the_title(); ?></h3>
				<div class="pafa-block-a">
					<?php the_content(); ?>
				</div>
			</div>
			<?php
		}
	}?>
</div>
