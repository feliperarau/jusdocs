<?php
/**
 * Post item template
 */

if ( has_post_thumbnail() ) {
	$thumb_size = isset( $settings['image_size'] ) ? $settings['image_size'] : 'full';
	$thumb      = get_the_post_thumbnail_url(get_the_ID(), $thumb_size);
}else {
	$thumb = sprintf('//via.placeholder.com/900x600?text=%s', str_replace( ' ', '+', get_the_title() ));
}
?>
<div class="jet-smart-tiles">
	<div class="jet-smart-tiles__box">
		<?php 
		if(!empty($thumb)){
			echo '<amp-img src="'.$thumb.'" alt="AMP" width="475" height="268" layout="intrinsic"></amp-img>';
		}
		?>
		<?php $this->_post_terms(); ?>
		<div class="jet-smart-tiles__box-content">
			<div class="jet-smart-tiles__box-content-inner">
				<?php include $this->_get_global_template( 'post-meta' ); ?>
				<?php $this->_render_meta( 'title_related', 'jet-title-fields', array( 'before' ) ); ?>
				<?php the_title( '<div class="jet-smart-tiles__box-title">', '</div>' ); ?>
				<?php $this->_render_meta( 'title_related', 'jet-title-fields', array( 'after' ) ); ?>
				<?php $this->_render_meta( 'content_related', 'jet-content-fields', array( 'before' ) ); ?>
				<?php $this->_post_excerpt( '<div class="jet-smart-tiles__box-excerpt">', '</div>' ); ?>
				<?php $this->_render_meta( 'content_related', 'jet-content-fields', array( 'after' ) ); ?>
			</div>
		</div>
		<a href="<?php the_permalink(); ?>" class="jet-smart-tiles__box-link" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></a>
	</div>
</div>