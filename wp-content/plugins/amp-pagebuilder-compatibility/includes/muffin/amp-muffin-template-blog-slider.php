<?php

add_filter('blog_slider','blog_slider_for_amp');
function blog_slider_for_amp($attr, $content = null){

	extract(shortcode_atts(array(
			'title'				=> '',
			'count'				=> 5,

			'category'		=> '',
			'category_multi'	=> '',
			'orderby'			=> 'date',
			'order'				=> 'DESC',

			'more'				=> '',
			'style'				=> '',
			'navigation'	=> '',
		), $attr));

		// translate

		$translate['readmore'] = mfn_opts_get('translate') ? mfn_opts_get('translate-readmore', 'Read more') : __('Read more', 'betheme');

		// classes

		$classes = '';
		if (! $more) {
			$classes .= ' hide-more';
		}
		if ($style) {
			$classes .= ' '. $style;
		}
		if ($navigation) {
			$classes .= ' '. $navigation;
		}

		// query args

		$args = array(
			'posts_per_page'			=> intval($count, 10),
			'orderby' 						=> $orderby,
			'order'								=> $order,
			'no_found_rows'				=> 1,
			'post_status'					=> 'publish',
			'ignore_sticky_posts'	=> 0,
		);

		// private

		if (is_user_logged_in()) {
			$args['post_status'] = array( 'publish', 'private' );
		}

		// categories

		if ($category_multi) {
			$args['category_name'] = trim($category_multi);
		} elseif ($category) {
			$args['category_name'] = $category;
		}

		$query_blog = new WP_Query($args);

		// output -----

		$output = '<div class="blog_slider clearfix '. esc_attr($classes) .'" data-count="'. intval($query_blog->post_count, 10) .'">';

			$output .= '<div class="blog_slider_header clearfix">';
				if ($title) {
					$output .= '<h4 class="title">'. wp_kses($title, mfn_allowed_html()) .'</h4>';
				}
				$output .= '<div class="slider_navigation"></div>';
			$output .= '</div>';

			$output .= '<ul class="blog_slider_ul">';
			$output .= '<amp-carousel width="400" height="300" layout="responsive" type="slides">';

				while ($query_blog->have_posts()) {
					$query_blog->the_post();

					$output .= '<li class="'. implode(' ', get_post_class()) .'">';
						$output .= '<div class="item_wrapper">';

							if (get_post_format() == 'quote') {
								$output .= '<blockquote>';
									$output .= '<a href="'. esc_url(get_permalink()) .'">';
										$output .= wp_kses(get_the_title(), mfn_allowed_html());
									$output .= '</a>';
								$output .= '</blockquote>';
							} else {
								$output .= '<div class="image_frame scale-with-grid">';
									$output .= '<div class="image_wrapper">';
										$output .= '<a href="'. esc_url(get_permalink()) .'">';
											$output .= get_the_post_thumbnail(get_the_ID(), 'blog-portfolio', array( 'class' => 'scale-with-grid' ));
										$output .= '</a>';
									$output .= '</div>';
								$output .= '</div>';
							}

							$output .= '<div class="date_label">'. esc_html(get_the_date()) .'</div>';

							$output .= '<div class="desc">';
								if (get_post_format() != 'quote') {
									$output .= '<h4><a href="'. esc_url(get_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></h4>';
								}
								$output .= '<hr class="hr_color" />';
								$output .= '<a href="'. esc_url(get_permalink()) .'" class="button button_left has-icon"><span class="button_icon"><i class="icon-layout"></i></span><span class="button_label">'. esc_html($translate['readmore']) .'</span></a>';
							$output .= '</div>';

						$output .= '</div>';
					$output .= '</li>';
				}

			$output .= '</amp-carousel>';
			$output .= '</ul>';

			$output .= '<div class="slider_pager slider_pagination"></div>';

		$output .= '</div>'."\n";

		wp_reset_postdata();
	return $output;
}