<?php
/**
 * Page view template.
 */

/**
 * Context.
 *
 * @var AMP_Post_Template $this
 */

//var_dump($this); die;
?>
<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
		<?php $this->load_parts( array( 'style' ) ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>

<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">
<header id="top" class="amp-wp-header">
	<div>
		<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
			<?php $site_icon_url = $this->get( 'site_icon_url' ); ?>
			<?php if ( $site_icon_url ) : ?>
				<amp-img src="<?php echo esc_url( $site_icon_url ); ?>" width="32" height="32" class="amp-wp-site-icon"></amp-img>
			<?php endif; ?>
			<span class="amp-site-title">
				<?php echo esc_html( wptexturize( $this->get( 'blog_name' ) ) ); ?>
			</span>
		</a>

		<?php $canonical_link_url = $this->get( 'post_canonical_link_url' ); ?>
		<?php if ( $canonical_link_url ) : ?>
			<?php $canonical_link_text = $this->get( 'post_canonical_link_text' ); ?>
			<a class="amp-wp-canonical-link" href="<?php echo esc_url( $canonical_link_url ); ?>">
				<?php echo esc_html( $canonical_link_text ); ?>
			</a>
		<?php endif; ?>
	</div>
</header>


<article class="">
	<header class="amp-wp-article-header">
		<?php  $title = get_option('amp_pbc_automattic');
			if( isset($title['single-show-title-pb-for-amp']) && $title['single-show-title-pb-for-amp'] == 1){?>
		<h1 class="amp-wp-title"><?php 
		echo esc_html( $this->get( 'post_title' ) ); ?></h1><?php } ?>
	</header>

	<?php $this->load_parts( array( 'featured-image' ) ); ?>

	<div class="amp-wp-article-content">
		<?php echo $this->get( 'post_amp_content' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</article>

<?php $this->load_parts( array( 'footer' ) ); ?>

<?php do_action( 'amp_post_template_footer', $this ); ?>

</body>
</html>
