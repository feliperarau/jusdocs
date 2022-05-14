<?php
add_action('ampforwp_single_design_type_handle', "ampforwp_singlepage_design_type");
function ampforwp_singlepage_design_type(){
	global $redux_builder_amp;global $post, $redux_builder_amp;
$post_author = get_userdata($post->post_author); $author_name = $post_author->display_name; if ( function_exists('coauthors') ) { 
    $author_name = coauthors($and_text,$and_text,null,null,false);
}
$amp_layout_single_excerpt = has_excerpt() ? get_the_excerpt() : '';
if(isset($redux_builder_amp['ampforwp-author-layout-link']) && $redux_builder_amp['ampforwp-author-layout-link'] == 1){
$author_link = esc_url(get_author_posts_url($post_author->ID));
}
else{
$author_link = esc_url(ampforwp_url_controller(get_author_posts_url($post_author->ID)));	
}
if ( ampforwp_get_setting('ampforwp-social-share-amp')  ) {
		$amp_permalink = ampforwp_url_controller(get_the_permalink());
}else{
		$amp_permalink = get_the_permalink();
}
$twitter_amp_permalink = $amp_permalink;
if(false == ampforwp_get_setting('enable-single-twitter-share-link')){
		$twitter_amp_permalink =  wp_get_shortlink();
}

if(!isset($author_prefix)){
 $author_prefix = '';
}

if ( function_exists('coauthors_posts_links') ) {
    $author_link = coauthors_posts_links($and_text,$and_text,null,null,false);
}
	if( isset($redux_builder_amp['single-design-type']) && $redux_builder_amp['single-design-type'] == '2'){ ?>
	<div class="sd-2 sgl">
 		<div class="cntr">
 			<?php if( 1 == $redux_builder_amp['ampforwp-bread-crumb'] ) { ?>
 			<?php amp_breadcrumb();?>
 			<?php } ?>
 			<div class="cat-aud">
	 			<?php amp_categories_list();?>
	 			<?php if(isset($redux_builder_amp['swift-date']) && $redux_builder_amp['swift-date'] == true) { ?>
	 			<div class="author-details">
	 		   <?php if(isset($redux_builder_amp['ampforwp_layout_date_seperator']) && $redux_builder_amp['ampforwp_layout_date_seperator'] == true){echo "|";}?>
		            <strong><?php echo ampforwp_translation( $redux_builder_amp['amp-translator-on-text'].'  ', 'On  ' ) ?></strong> 
		           	<span><?php amp_date('date'); ?></span>
			    </div>
			    <?php do_action('ampforwp_post_views_amp_layouts'); ?>
			    <?php } ?>
		    </div>
			<?php amp_title(); ?>
			<?php if( true == $redux_builder_amp['enable-excerpt-single'] && !empty($amp_layout_single_excerpt)){ ?>
				<div class="exc">
				   <?php echo $amp_layout_single_excerpt; ?>
			    </div>
			<?php } ?>
					<?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-name') ) { ?>	
						<?php
						$author_box = array( 'avatar'=>true,
													'avatar_size'=>38,
													'author_prefix'=> ampforwp_translation( $redux_builder_amp['amp-translator-by-text'].' ', 'By ' )
														);
						if( true == ampforwp_get_setting('amp-author-bio-name')){
							$author_box['author_pub_name'] = true ;
						}
						$author_box['author_link'] = $author_link;
						$author_box['is_author_link_amp'] = false;
						amp_author_box( $author_box ); ?>	
					<?php } ?>
		
			<div class="artl">
				<div class="lft">
					<?php if ( isset($redux_builder_amp['swift-featued-image']) && $redux_builder_amp['swift-featued-image'] && ampforwp_has_post_thumbnail() ) { ?>
						<?php amp_featured_image();?>
					<?php } 
						if(true == ampforwp_get_setting('ampforwp-social-share')){
					?>
						<div class="ss-shr">	
						<?php if (isset($redux_builder_amp['swift-social-position']) && 'default' == $redux_builder_amp['swift-social-position']){
						design_social_3(); 
						}?>				
						<?php if (isset($redux_builder_amp['swift-social-position']) && 'above-content' == $redux_builder_amp['swift-social-position']){
							design_social_3(); 
 						}?>	
 						</div>
 						<?php } ?>
					<div class="cntn-wrp">											
						<div>
						<?php if ( 'above-content' ==  ampforwp_get_setting('swift-layout-addthis-pos') ){  echo add_this_for_layouts();
						   } ?>
					     </div>
						<?php amp_content(); ?>
						 <div>
						<?php if ( 'below-content' ==  ampforwp_get_setting('swift-layout-addthis-pos') ){ echo add_this_for_layouts(); }  ?>
					    </div>

					</div>
					<?php 
					if(true == ampforwp_get_setting('ampforwp-social-share')){
					?>
					    <div class="ss-shr">					
					<?php if (isset($redux_builder_amp['swift-social-position']) && 'below-content' == $redux_builder_amp['swift-social-position']){
							design_social_3(); 
 						}?>	
 						</div>
 					<?php } ?>				
					<?php amp_post_navigation();?>
					<?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-description') ) { ?>
							<div class="artl-atr">	
								<?php
								$author_box = array( 'avatar'=>true,
															'avatar_size'=>50,	
															'author_description'=>true,	
														    'ads_below_the_author'=>true);
								if( true == ampforwp_get_setting('amp-author-bio-name')){
									$author_box['author_pub_name'] = true ;
								}
								$author_box['author_link'] = $author_link;
								$author_box['is_author_link_amp'] = false;
								amp_author_box( $author_box ); ?>	
							</div>
						<?php } ?>
					<?php if( true == $redux_builder_amp['ampforwp-tags-single'] && amp_tags_list()){ ?>
			            <div class="tags">
			            	<?php amp_tags_list();?>
			            </div>
		            <?php } ?>
					<div class="cmts">
						<?php amp_comments();?>
						<?php do_action('ampforwp_post_after_design_elements'); ?>
					</div>
				</div>
				<div class="rft">
					<div class="ads"></div>
					<?php if ( isset($redux_builder_amp['ampforwp-single-related-posts-switch']) && $redux_builder_amp['ampforwp-single-related-posts-switch'] ) {
						 $my_query = ampforwp_related_post_loop_query();
						if( $my_query->have_posts() ) { ?>
						  	<div class="srp">
						  		<?php ampforwp_related_post(); ?>
					            <ul class="clearfix">
							        <?php
							          while( $my_query->have_posts() ) {
							            $my_query->the_post();
							        ?>
							        <li class="<?php if ( has_post_thumbnail() ) { echo'has_thumbnail'; } else { echo 'no_thumbnail'; } ?>">
							            <div class="rlp-image">
								            <?php if ( true == $redux_builder_amp['ampforwp-single-related-posts-image'] ) { ?>     
								                <?php ampforwp_get_relatedpost_image('full',array('image_crop'=>'true','image_crop_width'=>65,'image_crop_height'=>65) );?>
											<?php } ?>
											<?php $argsdata = array(
													'show_author' => false,
													'show_excerpt' =>false
														);
											ampforwp_get_relatedpost_content($argsdata); ?> 
										</div>
							        </li><?php
							        } ?>
		                        </ul>
		                    </div>
				   		<?php } ?>
		    		<?php wp_reset_postdata(); }?>
					<?php if ( isset($redux_builder_amp['ampforwp-swift-recent-posts']) && $redux_builder_amp['ampforwp-swift-recent-posts']){?>
						<div class="rc-p">
							<h3><?php echo ampforwp_translation($redux_builder_amp['amp-translator-recent-text'], 'Recent Posts' ); ?></h3>
								<?php $number_of_posts = 6;
								$rcp = ampforwp_get_setting('ampforwp-number-of-recent-posts');
								if( !empty($rcp) ){
									$number_of_posts = (int) ampforwp_get_setting('ampforwp-number-of-recent-posts');
								} ?>
								<?php while( amp_loop('start', array( 'posts_per_page' => $number_of_posts ) ) ): ?>
									<div class="rp">
										<?php
										$width 	= 65;
										$height = 65;
										if( true == $redux_builder_amp['ampforwp-homepage-posts-image-modify-size'] ){
											$width 	= $redux_builder_amp['ampforwp-swift-homepage-posts-width'];
											$height = $redux_builder_amp['ampforwp-swift-homepage-posts-height'];
										}
										 $args = array("tag"=>'div',"tag_class"=>'image-container','image_size'=>'full','image_crop'=>'true','image_crop_width'=>$width,'image_crop_height'=>$height, 'responsive'=> true); ?>
									    <div class="rp-img">
									    	<?php amp_loop_image($args); ?>
									    </div>
									    <div class="rp-cnt">
										    <?php amp_loop_title(); ?>
									    </div>
									</div>
								<?php endwhile; amp_loop('end'); ?>
							<?php wp_reset_postdata(); ?>
						</div>
					<?php } ?>
					<?php // Single Design 2 Sidebar 
					if ( true == ampforwp_get_setting('gnrl-sidebar') &&  true == ampforwp_get_setting('swift-sidebar')  ){?>
					<div class="layouts-sidebar">
						<?php 
						$sanitized_sidebar = ampforwp_sidebar_content_sanitizer('swift-sidebar');
						if ( $sanitized_sidebar) {
							$sidebar_output = $sanitized_sidebar->get_amp_content();
							$sidebar_output = apply_filters('ampforwp_modify_sidebars_content',$sidebar_output);
						}
			            echo do_shortcode($sidebar_output); // amphtml content, no kses
						?>
					</div>
				<?php } // sidebar condition ends here ?>
				</div><!-- /.rft -->
 			</div>
 		</div>
 	</div>
<?php } // single desing 2 ended 
if ( isset($redux_builder_amp['single-design-type']) && $redux_builder_amp['single-design-type'] == '3') { ?>
<div class="sd-3 sgl">
	<div class="sd-3-wrp">
			<?php if( isset($redux_builder_amp['ampforwp-bread-crumb']) && 1 == $redux_builder_amp['ampforwp-bread-crumb'] ) { ?>
 			<?php amp_breadcrumb();?>
 			<?php } ?>
		<div class="fg-img">
			<?php if ( isset($redux_builder_amp['swift-featued-image']) && $redux_builder_amp['swift-featued-image'] && ampforwp_has_post_thumbnail() ) { ?>
				<?php amp_featured_image();?>
			<?php }?>
		</div>
		<div class="artl-cntn">
			<?php amp_title(); ?>
			<?php if ( isset($redux_builder_amp['enable-excerpt-single']) && $redux_builder_amp['enable-excerpt-single'] && !empty($amp_layout_single_excerpt)){?>
				<div class="exc" style="line-height: 28px;margin-bottom: 0.7em;">
				   <?php echo $amp_layout_single_excerpt; ?>
			    </div>
			<?php } ?>
			
			<div class="pt-info">
				<div class="athr-info">
                  
                    <?php if ( isset($redux_builder_amp['amp-author-name']) && $redux_builder_amp['amp-author-name']){?>
                      <?php if( $redux_builder_amp['amp-translator-by-text'] ){ ?>
                    <span><?php echo $redux_builder_amp['amp-translator-by-text']; ?></span>
                    <?php } ?>
			        <?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-name')){
							$author_box['author_pub_name'] = true ;
						}
						$author_html = '<div class="amp-author "> <div class="author-details "><span class="author-name">' .esc_html($author_prefix) . ' <a href="'. esc_url($author_link).'" title="'. esc_html($author_name).'"> ' .esc_html( $author_name ).'</a></span></div></div>';
						$author_html = apply_filters('ampforwp_sd_3_layout_author_html',$author_html);
						echo $author_html; ?>	
					
					<?php } ?>
					<?php if(isset($redux_builder_amp['swift-date']) && $redux_builder_amp['swift-date'] == true) { ?>
					<span class="athr-ptd"><?php amp_date('date');?></span>
					<?php } ?>
				</div>
				
				<div class="soc-shr"> 
					<?php if (isset($redux_builder_amp['swift-social-position']) && 'default' == $redux_builder_amp['swift-social-position']){
						design_social_4(); 
						}?>
						<?php if (isset($redux_builder_amp['swift-social-position']) && 'above-content' == $redux_builder_amp['swift-social-position']){
							design_social_4(); 
						}?>
					
				</div>
			</div>
			
			<div class="pt-cntn">
				<div class = "pt-addthis" >
			<?php	if ( 'above-content' ==  ampforwp_get_setting('swift-layout-addthis-pos') ){
							echo  add_this_for_layouts();
						} ?></div>
				<?php amp_content(); ?>
				<div class="cmts">
				<?php amp_comments();?>
				<?php do_action('ampforwp_post_after_design_elements'); ?>
			</div>
				<div class = "pt-addthis" >
				<?php	if ( 'below-content' ==  ampforwp_get_setting('swift-layout-addthis-pos') ){
							echo add_this_for_layouts();
						} ?>
					</div>
					<div class="soc-shr">
					<?php if (isset($redux_builder_amp['swift-social-position']) && 'below-content' == $redux_builder_amp['swift-social-position']){
							?><span class="shr-txt"><?php echo esc_attr(ampforwp_translation($redux_builder_amp['amp-translator-share-text'], 'Share')); ?>
								</span> <?php 
							design_social_4(); 
						}?>
				</div>

			</div>
			<?php if( true == $redux_builder_amp['ampforwp-tags-single'] && amp_tags_list()){ ?>
	            <div class="tags">
	            	<?php amp_tags_list();?>
	            </div>

            <?php } ?>
            <?php if( true == ampforwp_get_setting('ampforwp-social-share') ) { ?>
			<div class="scl-shr-btns">
				<span class="shr-txt"><?php echo esc_attr(ampforwp_translation($redux_builder_amp['amp-translator-share-text'], 'Share')); ?>
				</span>
				<ul class= "scl-shr-btns-ul" >
					<?php if($redux_builder_amp['enable-single-facebook-share']){
						$facebook_icon = '';
                                if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                                $facebook_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDg2LjAzNyAxMDA3IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTEyNCAxMDA1VjUzNkgwVjM2N2gxMjRWMjIzQzEyNCAxMTAgMTk3IDUgMzY2IDVjNjggMCAxMTkgNyAxMTkgN2wtNCAxNThzLTUyLTEtMTA4LTFjLTYxIDAtNzEgMjgtNzEgNzV2MTIzaDE4M2wtOCAxNjlIMzAydjQ2OUgxMjMiPjwvcGF0aD48L3N2Zz4=" width="16" height="16" ></amp-img>';    
                                }?>
					<li>
						<a class="s_fb" target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo esc_url($amp_permalink); ?>"><?php echo $facebook_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-twitter-share']){
						$twitter_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$twitter_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQwLjAxNzEgNjAxLjA4NjkiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCA1MzAuMTU1YzEwLjQyIDEuMDE1IDIwLjgyNiAxLjU0OCAzMS4yMiAxLjU0OCA2MS4wNSAwIDExNS41MjgtMTguNzMgMTYzLjM4Ny01Ni4xNy0yOC40MjQtLjM1Mi01My45MzMtOS4wNC03Ni40NzctMjYuMDQzLTIyLjU3LTE2Ljk5LTM3Ljk4NC0zOC42NzUtNDYuMzIzLTY1LjA1NiA2LjkzMyAxLjQxOCAxNS4xMDIgMi4wOTUgMjQuNDU2IDIuMDk1IDEyLjE1IDAgMjMuNzY3LTEuNTc1IDM0Ljg2Mi00LjY4NC0zMC41MTctNS44NjctNTUuNzY2LTIwLjg5Mi03NS43MS00NC45OTctMTkuOTU0LTI0LjEzMi0yOS45Mi01MS45Ny0yOS45Mi04My41Mjh2LTEuNTc0YzE4LjM5NiAxMC40MiAzOC4zMTIgMTUuODA2IDU5LjgyOCAxNi4xMy0xOC4wMTctMTEuNzk4LTMyLjM0LTI3LjMwNC00Mi45MTUtNDYuNTctMTAuNTc2LTE5LjI0LTE1Ljg3LTQwLjEzLTE1Ljg3LTYyLjY3NCAwLTIzLjU5OCA2LjA4Ny00NS42MDggMTguMjEtNjYuMDk2IDMyLjYgNDAuNTg2IDcyLjQyIDcyLjkzOCAxMTkuNDMyIDk3LjA1NiA0NyAyNC4wOSA5Ny4zNyAzNy41MyAxNTEuMTU4IDQwLjMyNi0yLjQzMi0xMS40NDctMy42NTUtMjEuNTE2LTMuNjU1LTMwLjE4IDAtMzYuMDg1IDEyLjg0LTY2Ljk1NCAzOC41MDUtOTIuNjIgMjUuNjgtMjUuNjY2IDU2LjcwNC0zOC41MDUgOTMuMTUzLTM4LjUwNSAzNy43OSAwIDY5LjcwMiAxMy44OCA5NS43MyA0MS42NCAzMC4xNjgtNi4yNTcgNTcuOTI4LTE3LjAxNSA4My4yNTYtMzIuMjYtOS43MTggMzEuNTU4LTI4LjgxNSA1NS44NDUtNTcuMjM4IDcyLjg0NyAyNS4zMjgtMy4xMSA1MC4zMDQtMTAuMDU2IDc0LjkzLTIwLjgxNC0xNi42NTIgMjYuMDE3LTM4LjMzNyA0OC43NDItNjUuMDU3IDY4LjE1MnYxNy4xOTdjMCAzNC45OTItNS4xMjQgNzAuMTI4LTE1LjM0OCAxMDUuMzU1LTEwLjIxMiAzNS4yMTQtMjUuODUgNjguODUzLTQ2LjgzIDEwMC45NzItMjAuOTk2IDMyLjA2NS00Ni4wNSA2MC42Mi03NS4xOSA4NS41Ny0yOS4xMjYgMjQuOTc2LTY0LjA4IDQ0Ljg1My0xMDQuODUgNTkuNTktNDAuNzU0IDE0Ljc1My04NC41NTMgMjIuMDktMTMxLjM5NyAyMi4wOUMxMjguODYyIDU4OC45NCA2MS43NCA1NjkuMzUgMCA1MzAuMTU0eiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16"></amp-img>';}?>
					<li>
						<a class="s_tw" target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo esc_url($twitter_amp_permalink); ?>&text=<?php echo esc_attr(ampforwp_sanitize_twitter_title(get_the_title())); ?>">
						<?php echo $twitter_icon; ?></a>
					</li>
					<?php } ?>
					<?php if(isset($redux_builder_amp['enable-single-gplus-share']) && $redux_builder_amp['enable-single-gplus-share']){?>
					<li>
						<a class="s_gp" target="_blank" href="https://plus.google.com/share?url=<?php echo esc_url($amp_permalink); ?>"></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-email-share']){
						$email_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$email_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgODk2IDEwMjYiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCAxOTN2NjQwaDg5NlYxOTNIMHptNzY4IDY0TDQ0OCA1MjEgMTI4IDI1N2g2NDB6TTY0IDMyMWwyNTIuMDMgMTkxLjYyNUw2NCA3MDVWMzIxem02NCA0NDhsMjU0LTIwNi4yNUw0NDggNjEzbDY1Ljg3NS01MC4xMjVMNzY4IDc2OUgxMjh6bTcwNC02NEw1NzkuNjI1IDUxMi45MzggODMyIDMyMXYzODR6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_em" target="_blank" href="mailto:?subject=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>&body=<?php echo esc_url($amp_permalink); ?>"><?php echo $email_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-pinterest-share']){
						$thumb_id = $image = '';
						if (has_post_thumbnail( ) ){
								$thumb_id = get_post_thumbnail_id(get_the_ID());
							$image = wp_get_attachment_image_src( $thumb_id, 'full' );
							$image = $image[0]; 
							}
							$pinterest_icon = '';
	 							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$pinterest_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNOC42MTcgMTMuMjI3QzguMDkgMTUuOTggNy40NSAxOC42MiA1LjU1IDIwYy0uNTg3LTQuMTYyLjg2LTcuMjg3IDEuNTMzLTEwLjYwNS0xLjE0Ny0xLjkzLjEzOC01LjgxMiAyLjU1NS00Ljg1NSAyLjk3NSAxLjE3Ni0yLjU3NiA3LjE3MiAxLjE1IDcuOTIyIDMuODkuNzggNS40OC02Ljc1IDMuMDY2LTkuMkMxMC4zNy0uMjc0IDMuNzA4IDMuMTggNC41MjggOC4yNDZjLjIgMS4yMzggMS40NzggMS42MTMuNTEgMy4zMjItMi4yMy0uNDk0LTIuODk2LTIuMjU0LTIuODEtNC42LjEzOC0zLjg0IDMuNDUtNi41MjcgNi43Ny02LjkgNC4yMDItLjQ3IDguMTQ1IDEuNTQzIDguNjkgNS40OTQuNjEzIDQuNDYyLTEuODk2IDkuMjk0LTYuMzkgOC45NDYtMS4yMTctLjA5NS0xLjcyNy0uNy0yLjY4LTEuMjh6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';
	 							}?>
					<li>
						<a class="s_pt" target="_blank" href="https://pinterest.com/pin/create/bookmarklet/?media=<?php echo $image; ?>&url=<?php echo esc_url($amp_permalink); ?>&description=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $pinterest_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-linkedin-share']){
						$linkedin_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$linkedin_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTA0NiAxMDA3IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTIzNyAxMDA1VjMzMEgxM3Y2NzVoMjI0ek0xMjUgMjM4Yzc4IDAgMTI3LTUyIDEyNy0xMTdDMjUxIDU1IDIwMyA0IDEyNyA0IDUwIDQgMCA1NCAwIDEyMWMwIDY1IDQ5IDExNyAxMjQgMTE3aDF6bTIzNiA3NjdoMjI0VjYyOGMwLTIwIDEtNDAgNy01NSAxNi00MCA1My04MiAxMTUtODIgODEgMCAxMTQgNjIgMTE0IDE1M3YzNjFoMjI0VjYxOGMwLTIwNy0xMTEtMzA0LTI1OC0zMDQtMTIxIDAtMTc0IDY4LTIwNCAxMTRoMXYtOThIMzYwYzMgNjMgMCA2NzUgMCA2NzV6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_lk" target="_blank" href="https://www.linkedin.com/shareArticle?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $linkedin_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-whatsapp-share']){
						$whatsapp_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$whatsapp_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgOTAgOTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDkwIDkwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggaWQ9IldoYXRzQXBwIiBkPSJNOTAsNDMuODQxYzAsMjQuMjEzLTE5Ljc3OSw0My44NDEtNDQuMTgyLDQzLjg0MWMtNy43NDcsMC0xNS4wMjUtMS45OC0yMS4zNTctNS40NTVMMCw5MGw3Ljk3NS0yMy41MjIgICBjLTQuMDIzLTYuNjA2LTYuMzQtMTQuMzU0LTYuMzQtMjIuNjM3QzEuNjM1LDE5LjYyOCwyMS40MTYsMCw0NS44MTgsMEM3MC4yMjMsMCw5MCwxOS42MjgsOTAsNDMuODQxeiBNNDUuODE4LDYuOTgyICAgYy0yMC40ODQsMC0zNy4xNDYsMTYuNTM1LTM3LjE0NiwzNi44NTljMCw4LjA2NSwyLjYyOSwxNS41MzQsNy4wNzYsMjEuNjFMMTEuMTA3LDc5LjE0bDE0LjI3NS00LjUzNyAgIGM1Ljg2NSwzLjg1MSwxMi44OTEsNi4wOTcsMjAuNDM3LDYuMDk3YzIwLjQ4MSwwLDM3LjE0Ni0xNi41MzMsMzcuMTQ2LTM2Ljg1N1M2Ni4zMDEsNi45ODIsNDUuODE4LDYuOTgyeiBNNjguMTI5LDUzLjkzOCAgIGMtMC4yNzMtMC40NDctMC45OTQtMC43MTctMi4wNzYtMS4yNTRjLTEuMDg0LTAuNTM3LTYuNDEtMy4xMzgtNy40LTMuNDk1Yy0wLjk5My0wLjM1OC0xLjcxNy0wLjUzOC0yLjQzOCwwLjUzNyAgIGMtMC43MjEsMS4wNzYtMi43OTcsMy40OTUtMy40Myw0LjIxMmMtMC42MzIsMC43MTktMS4yNjMsMC44MDktMi4zNDcsMC4yNzFjLTEuMDgyLTAuNTM3LTQuNTcxLTEuNjczLTguNzA4LTUuMzMzICAgYy0zLjIxOS0yLjg0OC01LjM5My02LjM2NC02LjAyNS03LjQ0MWMtMC42MzEtMS4wNzUtMC4wNjYtMS42NTYsMC40NzUtMi4xOTFjMC40ODgtMC40ODIsMS4wODQtMS4yNTUsMS42MjUtMS44ODIgICBjMC41NDMtMC42MjgsMC43MjMtMS4wNzUsMS4wODItMS43OTNjMC4zNjMtMC43MTcsMC4xODItMS4zNDQtMC4wOS0xLjg4M2MtMC4yNy0wLjUzNy0yLjQzOC01LjgyNS0zLjM0LTcuOTc3ICAgYy0wLjkwMi0yLjE1LTEuODAzLTEuNzkyLTIuNDM2LTEuNzkyYy0wLjYzMSwwLTEuMzU0LTAuMDktMi4wNzYtMC4wOWMtMC43MjIsMC0xLjg5NiwwLjI2OS0yLjg4OSwxLjM0NCAgIGMtMC45OTIsMS4wNzYtMy43ODksMy42NzYtMy43ODksOC45NjNjMCw1LjI4OCwzLjg3OSwxMC4zOTcsNC40MjIsMTEuMTEzYzAuNTQxLDAuNzE2LDcuNDksMTEuOTIsMTguNSwxNi4yMjMgICBDNTguMiw2NS43NzEsNTguMiw2NC4zMzYsNjAuMTg2LDY0LjE1NmMxLjk4NC0wLjE3OSw2LjQwNi0yLjU5OSw3LjMxMi01LjEwN0M2OC4zOTgsNTYuNTM3LDY4LjM5OCw1NC4zODYsNjguMTI5LDUzLjkzOHoiIGZpbGw9IiNGRkZGRkYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" width="16" height="16" ></amp-img>';

								}?>
					<li>
						<a class="s_wp" target="_blank" href="whatsapp://send?text=<?php echo esc_url($amp_permalink); ?>" data-action="share/whatsapp/share"><?php echo $whatsapp_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-vk-share']){
						$vk_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$vk_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMDQuMzYgMzA0LjM2IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAzMDQuMzYgMzA0LjM2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGcgaWQ9IlhNTElEXzFfIj4KCTxwYXRoIGlkPSJYTUxJRF84MDdfIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7IiBkPSJNMjYxLjk0NSwxNzUuNTc2YzEwLjA5Niw5Ljg1NywyMC43NTIsMTkuMTMxLDI5LjgwNywyOS45ODIgICBjNCw0LjgyMiw3Ljc4Nyw5Ljc5OCwxMC42ODQsMTUuMzk0YzQuMTA1LDcuOTU1LDAuMzg3LDE2LjcwOS02Ljc0NiwxNy4xODRsLTQ0LjM0LTAuMDJjLTExLjQzNiwwLjk0OS0yMC41NTktMy42NTUtMjguMjMtMTEuNDc0ICAgYy02LjEzOS02LjI1My0xMS44MjQtMTIuOTA4LTE3LjcyNy0xOS4zNzJjLTIuNDItMi42NDItNC45NTMtNS4xMjgtNy45NzktNy4wOTNjLTYuMDUzLTMuOTI5LTExLjMwNy0yLjcyNi0xNC43NjYsMy41ODcgICBjLTMuNTIzLDYuNDIxLTQuMzIyLDEzLjUzMS00LjY2OCwyMC42ODdjLTAuNDc1LDEwLjQ0MS0zLjYzMSwxMy4xODYtMTQuMTE5LDEzLjY2NGMtMjIuNDE0LDEuMDU3LTQzLjY4Ni0yLjMzNC02My40NDctMTMuNjQxICAgYy0xNy40MjItOS45NjgtMzAuOTMyLTI0LjA0LTQyLjY5MS0zOS45NzFDMzQuODI4LDE1My40ODIsMTcuMjk1LDExOS4zOTUsMS41MzcsODQuMzUzQy0yLjAxLDc2LjQ1OCwwLjU4NCw3Mi4yMiw5LjI5NSw3Mi4wNyAgIGMxNC40NjUtMC4yODEsMjguOTI4LTAuMjYxLDQzLjQxLTAuMDJjNS44NzksMC4wODYsOS43NzEsMy40NTgsMTIuMDQxLDkuMDEyYzcuODI2LDE5LjI0MywxNy40MDIsMzcuNTUxLDI5LjQyMiw1NC41MjEgICBjMy4yMDEsNC41MTgsNi40NjUsOS4wMzYsMTEuMTEzLDEyLjIxNmM1LjE0MiwzLjUyMSw5LjA1NywyLjM1NCwxMS40NzYtMy4zNzRjMS41MzUtMy42MzIsMi4yMDctNy41NDQsMi41NTMtMTEuNDM0ICAgYzEuMTQ2LTEzLjM4MywxLjI5Ny0yNi43NDMtMC43MTMtNDAuMDc5Yy0xLjIzNC04LjMyMy01LjkyMi0xMy43MTEtMTQuMjI3LTE1LjI4NmMtNC4yMzgtMC44MDMtMy42MDctMi4zOC0xLjU1NS00Ljc5OSAgIGMzLjU2NC00LjE3Miw2LjkxNi02Ljc2OSwxMy41OTgtNi43NjloNTAuMTExYzcuODg5LDEuNTU3LDkuNjQxLDUuMTAxLDEwLjcyMSwxMy4wMzlsMC4wNDMsNTUuNjYzICAgYy0wLjA4NiwzLjA3MywxLjUzNSwxMi4xOTIsNy4wNywxNC4yMjZjNC40MywxLjQ0OCw3LjM1LTIuMDk2LDEwLjAwOC00LjkwNWMxMS45OTgtMTIuNzM0LDIwLjU2MS0yNy43ODMsMjguMjExLTQzLjM2NiAgIGMzLjM5NS02Ljg1Miw2LjMxNC0xMy45NjgsOS4xNDMtMjEuMDc4YzIuMDk2LTUuMjc2LDUuMzg1LTcuODcyLDExLjMyOC03Ljc1N2w0OC4yMjksMC4wNDNjMS40MywwLDIuODc3LDAuMDIxLDQuMjYyLDAuMjU4ICAgYzguMTI3LDEuMzg1LDEwLjM1NCw0Ljg4MSw3Ljg0NCwxMi44MTdjLTMuOTU1LDEyLjQ1MS0xMS42NSwyMi44MjctMTkuMTc0LDMzLjI1MWMtOC4wNDMsMTEuMTI5LTE2LjY0NSwyMS44NzctMjQuNjIxLDMzLjA3MiAgIEMyNTIuMjYsMTYxLjU0NCwyNTIuODQyLDE2Ni42OTcsMjYxLjk0NSwxNzUuNTc2TDI2MS45NDUsMTc1LjU3NnogTTI2MS45NDUsMTc1LjU3NiIgZmlsbD0iI0ZGRkZGRiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_vk" target="_blank" href="http://vk.com/share.php?url=<?php echo esc_url($amp_permalink); ?>"><?php echo $vk_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-odnoklassniki-share']){
						$odnoklassniki_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$odnoklassniki_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjY0cHgiIGhlaWdodD0iNjRweCIgdmlld0JveD0iMCAwIDk1LjQ4MSA5NS40ODEiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk1LjQ4MSA5NS40ODE7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8Zz4KCQk8cGF0aCBkPSJNNDMuMDQxLDY3LjI1NGMtNy40MDItMC43NzItMTQuMDc2LTIuNTk1LTE5Ljc5LTcuMDY0Yy0wLjcwOS0wLjU1Ni0xLjQ0MS0xLjA5Mi0yLjA4OC0xLjcxMyAgICBjLTIuNTAxLTIuNDAyLTIuNzUzLTUuMTUzLTAuNzc0LTcuOTg4YzEuNjkzLTIuNDI2LDQuNTM1LTMuMDc1LDcuNDg5LTEuNjgyYzAuNTcyLDAuMjcsMS4xMTcsMC42MDcsMS42MzksMC45NjkgICAgYzEwLjY0OSw3LjMxNywyNS4yNzgsNy41MTksMzUuOTY3LDAuMzI5YzEuMDU5LTAuODEyLDIuMTkxLTEuNDc0LDMuNTAzLTEuODEyYzIuNTUxLTAuNjU1LDQuOTMsMC4yODIsNi4yOTksMi41MTQgICAgYzEuNTY0LDIuNTQ5LDEuNTQ0LDUuMDM3LTAuMzgzLDcuMDE2Yy0yLjk1NiwzLjAzNC02LjUxMSw1LjIyOS0xMC40NjEsNi43NjFjLTMuNzM1LDEuNDQ4LTcuODI2LDIuMTc3LTExLjg3NSwyLjY2MSAgICBjMC42MTEsMC42NjUsMC44OTksMC45OTIsMS4yODEsMS4zNzZjNS40OTgsNS41MjQsMTEuMDIsMTEuMDI1LDE2LjUsMTYuNTY2YzEuODY3LDEuODg4LDIuMjU3LDQuMjI5LDEuMjI5LDYuNDI1ICAgIGMtMS4xMjQsMi40LTMuNjQsMy45NzktNi4xMDcsMy44MWMtMS41NjMtMC4xMDgtMi43ODItMC44ODYtMy44NjUtMS45NzdjLTQuMTQ5LTQuMTc1LTguMzc2LTguMjczLTEyLjQ0MS0xMi41MjcgICAgYy0xLjE4My0xLjIzNy0xLjc1Mi0xLjAwMy0yLjc5NiwwLjA3MWMtNC4xNzQsNC4yOTctOC40MTYsOC41MjgtMTIuNjgzLDEyLjczNWMtMS45MTYsMS44ODktNC4xOTYsMi4yMjktNi40MTgsMS4xNSAgICBjLTIuMzYyLTEuMTQ1LTMuODY1LTMuNTU2LTMuNzQ5LTUuOTc5YzAuMDgtMS42MzksMC44ODYtMi44OTEsMi4wMTEtNC4wMTRjNS40NDEtNS40MzMsMTAuODY3LTEwLjg4LDE2LjI5NS0xNi4zMjIgICAgQzQyLjE4Myw2OC4xOTcsNDIuNTE4LDY3LjgxMyw0My4wNDEsNjcuMjU0eiIgZmlsbD0iI0ZGRkZGRiIvPgoJCTxwYXRoIGQ9Ik00Ny41NSw0OC4zMjljLTEzLjIwNS0wLjA0NS0yNC4wMzMtMTAuOTkyLTIzLjk1Ni0yNC4yMThDMjMuNjcsMTAuNzM5LDM0LjUwNS0wLjAzNyw0Ny44NCwwICAgIGMxMy4zNjIsMC4wMzYsMjQuMDg3LDEwLjk2NywyNC4wMiwyNC40NzhDNzEuNzkyLDM3LjY3Nyw2MC44ODksNDguMzc1LDQ3LjU1LDQ4LjMyOXogTTU5LjU1MSwyNC4xNDMgICAgYy0wLjAyMy02LjU2Ny01LjI1My0xMS43OTUtMTEuODA3LTExLjgwMWMtNi42MDktMC4wMDctMTEuODg2LDUuMzE2LTExLjgzNSwxMS45NDNjMC4wNDksNi41NDIsNS4zMjQsMTEuNzMzLDExLjg5NiwxMS43MDkgICAgQzU0LjM1NywzNS45NzEsNTkuNTczLDMwLjcwOSw1OS41NTEsMjQuMTQzeiIgZmlsbD0iI0ZGRkZGRiIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_od" target="_blank" href="https://ok.ru/dk?st.cmd=addShare&st._surl=<?php echo esc_url($amp_permalink); ?>"><?php echo $odnoklassniki_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-reddit-share']){
						$reddit_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$reddit_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDQ5IDUxMiIgZmlsbD0iI2ZmZmZmZiIgPjxwYXRoIGQ9Ik00NDkgMjUxYzAgMjAtMTEgMzctMjcgNDUgMSA1IDEgOSAxIDE0IDAgNzYtODkgMTM4LTE5OSAxMzhTMjYgMzg3IDI2IDMxMWMwLTUgMC0xMCAxLTE1LTE2LTgtMjctMjUtMjctNDUgMC0yOCAyMy01MCA1MC01MCAxMyAwIDI0IDUgMzMgMTMgMzMtMjMgNzktMzkgMTI5LTQxaDJsMzEtMTAzIDkwIDE4YzgtMTQgMjItMjQgMzktMjRoMWMyNSAwIDQ0IDIwIDQ0IDQ1cy0xOSA0NS00NCA0NWgtMWMtMjMgMC00Mi0xNy00NC00MGwtNjctMTQtMjIgNzRjNDkgMyA5MyAxNyAxMjUgNDAgOS04IDIxLTEzIDM0LTEzIDI3IDAgNDkgMjIgNDkgNTB6TTM0IDI3MWM1LTE1IDE1LTI5IDI5LTQxLTQtMy05LTUtMTUtNS0xNCAwLTI1IDExLTI1IDI1IDAgOSA0IDE3IDExIDIxem0zMjQtMTYyYzAgOSA3IDE3IDE2IDE3czE3LTggMTctMTctOC0xNy0xNy0xNy0xNiA4LTE2IDE3ek0xMjcgMjg4YzAgMTggMTQgMzIgMzIgMzJzMzItMTQgMzItMzItMTQtMzEtMzItMzEtMzIgMTMtMzIgMzF6bTk3IDExMmM0OCAwIDc3LTI5IDc4LTMwbC0xMy0xMnMtMjUgMjQtNjUgMjRjLTQxIDAtNjQtMjQtNjQtMjRsLTEzIDEyYzEgMSAyOSAzMCA3NyAzMHptNjctODBjMTggMCAzMi0xNCAzMi0zMnMtMTQtMzEtMzItMzEtMzIgMTMtMzIgMzEgMTQgMzIgMzIgMzJ6bTEyNC00OGM3LTUgMTEtMTMgMTEtMjIgMC0xNC0xMS0yNS0yNS0yNS02IDAtMTEgMi0xNSA1IDE0IDEyIDI0IDI3IDI5IDQyeiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16" ></amp-img>';
							}?>
					<li>
						<a title="reddit share" class="s_rd" target="_blank" <?php ampforwp_nofollow_social_links(); ?> href="https://reddit.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $reddit_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-tumblr-share']){$tumblr_icon ='';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$tumblr_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMzYuMDAyIDI4djE0LjYzNmMwIDMuNzE0LS4wNDggNS44NTMuMzQ2IDYuOTA2LjM5IDEuMDQ3IDEuMzcgMi4xMzQgMi40MzcgMi43NjMgMS40MTguODUgMy4wMzQgMS4yNzMgNC44NTcgMS4yNzMgMy4yNCAwIDUuMTU1LS40MjggOC4zNi0yLjUzNHY5LjYyYy0yLjczMiAxLjI4Ni01LjExOCAyLjAzOC03LjMzNCAyLjU2LTIuMjIuNTE0LTQuNjE2Ljc3NC03LjE5Ljc3NC0yLjkyOCAwLTQuNjU1LS4zNjgtNi45MDItMS4xMDMtMi4yNDctLjc0Mi00LjE2Ni0xLjgtNS43NS0zLjE2LTEuNTkyLTEuMzctMi42OS0yLjgyNC0zLjMwNC00LjM2M3MtLjkyLTMuNzc2LS45Mi02LjcwM1YyNi4yMjRoLTguNTl2LTkuMDYzYzIuNTE0LS44MTUgNS4zMjQtMS45ODcgNy4xMTItMy41MSAxLjc5Ny0xLjUyNyAzLjIzNS0zLjM1NiA0LjMyLTUuNDk2QzI0LjUzIDYuMDIyIDI1LjI3NiAzLjMgMjUuNjgzIDBoMTAuMzJ2MTZINTJ2MTJIMzYuMDA0eiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16" ></amp-img>';}?>
					<li>
						<a class="s_tb" target="_blank" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo esc_url($amp_permalink); ?>"><?php echo $tumblr_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-telegram-share']){
						$telegram_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$telegram_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ1NS43MzEgNDU1LjczMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDU1LjczMSA0NTUuNzMxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGc+Cgk8cmVjdCB4PSIwIiB5PSIwIiBzdHlsZT0iZmlsbDojNjFBOERFOyIgd2lkdGg9IjQ1NS43MzEiIGhlaWdodD0iNDU1LjczMSIvPgoJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0zNTguODQ0LDEwMC42TDU0LjA5MSwyMTkuMzU5Yy05Ljg3MSwzLjg0Ny05LjI3MywxOC4wMTIsMC44ODgsMjEuMDEybDc3LjQ0MSwyMi44NjhsMjguOTAxLDkxLjcwNiAgIGMzLjAxOSw5LjU3OSwxNS4xNTgsMTIuNDgzLDIyLjE4NSw1LjMwOGw0MC4wMzktNDAuODgybDc4LjU2LDU3LjY2NWM5LjYxNCw3LjA1NywyMy4zMDYsMS44MTQsMjUuNzQ3LTkuODU5bDUyLjAzMS0yNDguNzYgICBDMzgyLjQzMSwxMDYuMjMyLDM3MC40NDMsOTYuMDgsMzU4Ljg0NCwxMDAuNnogTTMyMC42MzYsMTU1LjgwNkwxNzkuMDgsMjgwLjk4NGMtMS40MTEsMS4yNDgtMi4zMDksMi45NzUtMi41MTksNC44NDcgICBsLTUuNDUsNDguNDQ4Yy0wLjE3OCwxLjU4LTIuMzg5LDEuNzg5LTIuODYxLDAuMjcxbC0yMi40MjMtNzIuMjUzYy0xLjAyNy0zLjMwOCwwLjMxMi02Ljg5MiwzLjI1NS04LjcxN2wxNjcuMTYzLTEwMy42NzYgICBDMzIwLjA4OSwxNDcuNTE4LDMyNC4wMjUsMTUyLjgxLDMyMC42MzYsMTU1LjgwNnoiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" width="16" height="16" ></amp-img>';
							}?>
					<li>
						<a class="s_tg" target="_blank" href="https://telegram.me/share/url?url=<?php echo esc_url($amp_permalink); ?>&text=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $telegram_icon; ?></a>
					</li>
					<?php } ?>
					<?php if(isset($redux_builder_amp['enable-single-digg-share']) && $redux_builder_amp['enable-single-digg-share']){?>
					<li>
						<a class="s_dg" target="_blank" href="http://digg.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-stumbleupon-share']){
						$stumbleupon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$stumbleupon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjcwLjIyMzMgNjAxLjA4NjkiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCA0MzcuMjQ3di05Mi42NzJoMTE0LjY4OHY5MS42NjRjMCA5LjU2NyAzLjQwOCAxNy44MjMgMTAuMjQgMjQuODNzMTUuMTg0IDEwLjQ5NyAyNS4wODggMTAuNDk3IDE4LjMzNi0zLjQyNCAyNS4zNDQtMTAuMjRjNy4wMDgtNi44NDggMTAuNDk2LTE1LjIgMTAuNDk2LTI1LjA4OFYyMTkuNjQ2YzAtMzkuOTM1IDE0Ljc1Mi03My45ODQgNDQuMjg4LTEwMi4xNDQgMjkuNTM2LTI4LjE2IDY0LjYwOC00Mi4yNCAxMDUuMjE2LTQyLjI0IDQwLjYwOCAwIDc1LjY4IDE0LjE2IDEwNS4yMTYgNDIuNDk2IDI5LjUyIDI4LjMzNSA0NC4zMDUgNjIuNjQgNDQuMzA1IDEwMi45MXY0Ny4xMDRsLTY4LjYyMyAyMC40OC00NS41Ny0yMS41MDN2LTQwLjk2YzAtOS45MDMtMy40MDctMTguMjU2LTEwLjI1NS0yNS4wODgtNi44MTYtNi44MzItMTUuMTgzLTEwLjI0LTI1LjA3Mi0xMC4yNC05LjkwMyAwLTE4LjMzNiAzLjQwOC0yNS4zNDQgMTAuMjRzLTEwLjQ5NiAxNS4xODUtMTAuNDk2IDI1LjA5djIxMy41MDNjMCA0MC45NzYtMTQuNjcyIDc1Ljg3Mi00NC4wMzIgMTA0LjcyLTI5LjM0NCAyOC44NDgtNjQuNTEyIDQzLjI0OC0xMDUuNDcyIDQzLjI0OC00MS4zMSAwLTc2LjY0LTE0LjU5Mi0xMDUuOTg0LTQzLjc3NkMxNC42ODggNTE0LjMwMy4wMDIgNDc4Ljg4LjAwMiA0MzcuMjQ3em0zNzAuNjg4IDEuNTM2di05My42OTVsNDUuNTY4IDIxLjUyIDY4LjYyNC0yMC40OTd2OTQuMjI2YzAgOS45MDMgMy40MDggMTguMzM2IDEwLjIyNCAyNS4zNDQgNi44NDcgNy4wMDcgMTUuMiAxMC40OTYgMjUuMDg3IDEwLjQ5NiA5LjkwNiAwIDE4LjI3NC0zLjUwNCAyNS4wOS0xMC40OTYgNi44MTYtNi45OTMgMTAuMjU1LTE1LjQ0IDEwLjI1NS0yNS4zNDR2LTk1Ljc0NGgxMTQuNjg4djkyLjY3MmMwIDQxLjI5NS0xNC41OSA3Ni42NC00My43NzYgMTA1Ljk4My0yOS4xODQgMjkuMzYtNjQuNDMyIDQ0LjAzMi0xMDUuNzI4IDQ0LjAzMnMtNzYuNjI1LTE0LjQ5Ny0xMDUuOTg1LTQzLjUyYy0yOS4zNi0yOS4wNC00NC4wNDgtNjQuMDE3LTQ0LjA0OC0xMDQuOTc4eiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_su" target="_blank" href="http://www.stumbleupon.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $stumbleupon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-wechat-share']){
						$wechat_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$wechat_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjA0OCAxODk2LjA4MzMiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNNTgwIDQ2MXEwLTQxLTI1LTY2dC02Ni0yNXEtNDMgMC03NiAyNS41VDM4MCA0NjFxMCAzOSAzMyA2NC41dDc2IDI1LjVxNDEgMCA2Ni0yNC41dDI1LTY1LjV6bTc0MyA1MDdxMC0yOC0yNS41LTUwdC02NS41LTIycS0yNyAwLTQ5LjUgMjIuNVQxMTYwIDk2OHEwIDI4IDIyLjUgNTAuNXQ0OS41IDIyLjVxNDAgMCA2NS41LTIydDI1LjUtNTF6bS0yMzYtNTA3cTAtNDEtMjQuNS02NlQ5OTcgMzcwcS00MyAwLTc2IDI1LjVUODg4IDQ2MXEwIDM5IDMzIDY0LjV0NzYgMjUuNXE0MSAwIDY1LjUtMjQuNVQxMDg3IDQ2MXptNjM1IDUwN3EwLTI4LTI2LTUwdC02NS0yMnEtMjcgMC00OS41IDIyLjVUMTU1OSA5NjhxMCAyOCAyMi41IDUwLjV0NDkuNSAyMi41cTM5IDAgNjUtMjJ0MjYtNTF6bS0yNjYtMzk3cS0zMS00LTcwLTQtMTY5IDAtMzExIDc3VDg1MS41IDg1Mi41IDc3MCAxMTQwcTAgNzggMjMgMTUyLTM1IDMtNjggMy0yNiAwLTUwLTEuNXQtNTUtNi41LTQ0LjUtNy01NC41LTEwLjUtNTAtMTAuNWwtMjUzIDEyNyA3Mi0yMThRMCA5NjUgMCA2NzhxMC0xNjkgOTcuNS0zMTF0MjY0LTIyMy41VDcyNSA2MnExNzYgMCAzMzIuNSA2NnQyNjIgMTgyLjVUMTQ1NiA1NzF6bTU5MiA1NjFxMCAxMTctNjguNSAyMjMuNVQxNzk0IDE1NDlsNTUgMTgxLTE5OS0xMDlxLTE1MCAzNy0yMTggMzctMTY5IDAtMzExLTcwLjVUODk3LjUgMTM5NiA4MTYgMTEzMnQ4MS41LTI2NFQxMTIxIDY3Ni41dDMxMS03MC41cTE2MSAwIDMwMyA3MC41dDIyNy41IDE5MlQyMDQ4IDExMzJ6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';}?>
					<li>
						<a class="s_wc" target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/wechat/offer?url=<?php echo esc_url($amp_permalink); ?>"><?php echo $wechat_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-viber-share']){
					$viber_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$viber_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAyNiAxMjM0IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTkwNCA3OTRxLTY5IDYxLTIwMCA4Ny41VDQzNCA4OTdsLTE3NiAxMzJWODY0cS04Ny0yNy0xMzYtNzAtNTgtNTEtOTAtMTQ2LjV0LTMyLTE5NSAzMi0xOTUgOTAuNS0xNDcgMTY3LjUtNzlUNTEzIDR0MjIzIDI3LjUgMTY3LjUgNzkgOTAuNSAxNDcgMzIgMTk1LTMyIDE5NVQ5MDQgNzk0ek02MzkgNTQ5bDY1IDExcS04LTEyMC05Mi41LTIwNVQ0MDcgMjYybDExIDY1cTg2IDExIDE0OCA3M3Q3MyAxNDl6TTQyOSAzOTRsMTIgNzJxNDAgMjAgNTkgNTlsNzIgMTJxLTEyLTUzLTUxLTkxLjVUNDI5IDM5NHptLTEwNyA1OXYtNjRxMC0xNy0xMi41LTM0VDI4MyAzMzAuNXQtMjEtMS41bC00NiA0N3EtMzkgMzktMTEuNSAxMjEuNXQxMDUgMTYwIDE2MCAxMDVUNTkwIDc1MWw0Ny00N3E3LTYtLjUtMjAuNVQ2MTIgNjU3dC0zNC0xMmgtNjRsLTM3IDMycS00NC0xMi0xMDkuNS03Ny41VDI5MCA0ODl6bTY0LTMyMGwxMCA2NXExMDAgMiAxODUgNTIuNXQxMzUgMTM1VDc2OSA1NzBsNjUgMTFxMC05MS0zNS41LTE3NFQ3MDMgMjY0dC0xNDMtOTUuNVQzODYgMTMzeiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_vb" target="_blank" href="viber://forward?text=<?php echo esc_url($amp_permalink); ?>"><?php echo $viber_icon; ?></a>
					</li>
					<?php } ?>
					<?php if ( isset($redux_builder_amp['enable-single-yummly-share']) && $redux_builder_amp['enable-single-yummly-share']){
						$yummly_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$yummly_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgODk2IDEwMjYiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCAxOTN2NjQwaDg5NlYxOTNIMHptNzY4IDY0TDQ0OCA1MjEgMTI4IDI1N2g2NDB6TTY0IDMyMWwyNTIuMDMgMTkxLjYyNUw2NCA3MDVWMzIxem02NCA0NDhsMjU0LTIwNi4yNUw0NDggNjEzbDY1Ljg3NS01MC4xMjVMNzY4IDc2OUgxMjh6bTcwNC02NEw1NzkuNjI1IDUxMi45MzggODMyIDMyMXYzODR6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_ym" target="_blank" href="http://www.yummly.com/urb/verify?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>&yumtype=button"><?php echo $yummly_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['ampforwp-facebook-like-button']){?>
					<li>
						<amp-facebook-like width=90 height=28
		 					layout="fixed"
		 					data-size="large"
		    				data-layout="button_count"
		    				data-href="<?php echo esc_url(get_the_permalink());?>">
						</amp-facebook-like>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		<?php amp_post_navigation();?>
		<div class="amp_post_nav_border"></div>

			<?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-description') ) { ?>	
							<div class="pt-authr-info">
						<?php
						$author_box = array( 'avatar'=>true,
													'avatar_size'=>82,	
													'author_description'=>true,	
													'ads_below_the_author'=>true);
						if( true == ampforwp_get_setting('amp-author-bio-name')){
							$author_box['author_pub_name'] = true ;
						}
						$author_box['author_link'] = $author_link;
						$author_box['is_author_link_amp'] = false;
						amp_author_box( $author_box ); ?>
						</div>	
					<?php } ?>
            <?php
            if ( isset($redux_builder_amp['ampforwp-single-related-posts-switch']) && $redux_builder_amp['ampforwp-single-related-posts-switch'] ) {
			 $my_query = ampforwp_related_post_loop_query();
				if( $my_query->have_posts() ) { ?>
				  	<div class="srp">
				  		<?php ampforwp_related_post(); ?>
			            <ul class="clearfix">
					        <?php
					          while( $my_query->have_posts() ) {
					            $my_query->the_post();
					        ?>
					        <li class="<?php if ( has_post_thumbnail() ) { echo'has_thumbnail'; } else { echo 'no_thumbnail'; } ?>">
					            <div class="rlp-image">
						            <?php if ( true == $redux_builder_amp['ampforwp-single-related-posts-image'] ) { ?>     
						                <?php ampforwp_get_relatedpost_image('full',array('image_crop'=>'true','image_crop_width'=>350,'image_crop_height'=>190) );?>
									<?php } ?>
									<?php $argsdata = array(
											'show_author' => false,
											'show_excerpt' =>false
												);
									ampforwp_get_relatedpost_content($argsdata); ?> 
								</div>
								<div class="rp-cnt">
								    <span class="athr-nm"><?php if( true == ampforwp_get_setting('amp-author-name')){ echo $redux_builder_amp['amp-translator-by-text']; ?></span><?php 
 						              $author_box = array();
							   $author_box['author_pub_name'] = true ;
							   amp_author_box($author_box);} ?>	
	                                <span class="athr-dt"><?php amp_date('date');?></span>
						    	</div>
					        </li><?php
					        } ?>
                        </ul>
                    </div>
				   <?php } ?>
		    <?php wp_reset_postdata(); }?>
		     <?php if ( isset($redux_builder_amp['ampforwp-swift-recent-posts']) && $redux_builder_amp['ampforwp-swift-recent-posts']){?>
			<div class="rc-p">

				<h3><?php echo ampforwp_translation($redux_builder_amp['amp-translator-recent-text'], 'Recent Posts' ); ?></h3>	
					<?php $number_of_posts = 6;
					$rcp = ampforwp_get_setting('ampforwp-number-of-recent-posts');
					if( !empty($rcp) ){
						$number_of_posts = (int) ampforwp_get_setting('ampforwp-number-of-recent-posts');
					} ?>
					<?php while( amp_loop('start', array( 'posts_per_page' => $number_of_posts ) ) ): ?>
						<div class="rp">
							<?php
							$width 	= 350;
							$height = 190;
							if( true == $redux_builder_amp['ampforwp-homepage-posts-image-modify-size'] ){
								$width 	= $redux_builder_amp['ampforwp-swift-homepage-posts-width'];
								$height = $redux_builder_amp['ampforwp-swift-homepage-posts-height'];
							}
							 $args = array("tag"=>'div',"tag_class"=>'image-container','image_size'=>'full','image_crop'=>'true','image_crop_width'=>$width,'image_crop_height'=>$height, 'responsive'=> true); ?>
						    <div class="rp-img">
						    	<?php amp_loop_image($args); ?>
						    	<?php amp_loop_title(); ?>
						    </div>
						    <div class="rp-cnt">
							    <span class="athr-nm"><?php if( true == ampforwp_get_setting('amp-author-name')){ echo $redux_builder_amp['amp-translator-by-text']; ?></span><?php $author_box = array();	
							$author_box['author_pub_name'] = true ;
							amp_author_box($author_box);} ?>	
                                <span class="athr-dt"><?php amp_date('date');?></span>
						    </div>
						</div>
					<?php endwhile; amp_loop('end'); ?>
				<?php wp_reset_postdata(); ?>
			</div>
			<?php } ?>
        </div><!-- /.artl-cntn -->
	</div><!-- /.sd3-wrap -->
</div><!-- /.sd-3 -->
<?php } //single design 3 ended.
if ( isset($redux_builder_amp['single-design-type']) && $redux_builder_amp['single-design-type'] == '5') { ?>
	<div class="sd-5 sgl">
		<div class="cntr">
			<div class="a-c">
	            <?php amp_categories_list();?>
	            <?php amp_title(); ?>
	            <?php if( true == $redux_builder_amp['enable-excerpt-single'] && !empty($amp_layout_single_excerpt)){ ?>
				<div class="exc">
				   <?php echo $amp_layout_single_excerpt; ?>
			    </div>
				<?php } ?>
				<div class="athr-info">
                    <?php if ( isset($redux_builder_amp['amp-author-name']) && $redux_builder_amp['amp-author-name']){?>
                      <?php if( $redux_builder_amp['amp-translator-by-text'] ){ ?>
                    	<span><?php echo $redux_builder_amp['amp-translator-by-text']; ?></span>
                    <?php } ?>
						<?php   echo '<span class="author-name">' .esc_html($author_prefix) . ' <a href="'. esc_url($author_link).'"> ' .esc_html( $author_name ).'</a></span>'; ?>
					<?php } ?>
				</div>
				<?php if(isset($redux_builder_amp['swift-date']) && $redux_builder_amp['swift-date'] == true) { ?>
				<span class="athr-ptd"><?php amp_date('date');?></span>
				<?php } ?>
	        </div><!-- /.a-c -->
	        <div class="fg-img">
				<?php if ( isset($redux_builder_amp['swift-featued-image']) && $redux_builder_amp['swift-featued-image'] && ampforwp_has_post_thumbnail() ) { ?>
					<?php amp_featured_image();?>
				<?php }?>
			</div>
			<div class="pt-cntn">
				<?php if (isset($redux_builder_amp['swift-social-position']) && 'default' == $redux_builder_amp['swift-social-position']){
						design_single_6(); 
				}?>
				<?php if (isset($redux_builder_amp['swift-social-position']) && 'above-content' == $redux_builder_amp['swift-social-position']){
						design_single_6();  
				}?>
				<div>
			<?php	if ( 'above-content' ==  ampforwp_get_setting('swift-layout-addthis-pos') ){
							echo add_this_for_layouts();
						} ?></div>
				<?php   
				 ob_start();
			     amp_content();
                 $sd_5_html = ob_get_contents();
                 ob_get_clean();
         	 if(isset($redux_builder_amp['ampforwp-inline-related-posts']) && 1 == $redux_builder_amp['ampforwp-inline-related-posts'] ){     
            	$sd_5_html = preg_replace('/<div class="ampforwp-inline-related-post">(.*?)\s+<div class="related_posts">\s+<ol class="clearfix">\s+(.*?)<li class="has_related_thumbnail">/', '<div class="ampforwp-inline-related-post">$1<div class="related_posts"><span class="b-l"></span><div class="related-title"><h3>Related Post<h3></div><ol class="clearfix"><li class="has_related_thumbnail">', $sd_5_html);

               }
                echo $sd_5_html; ?>
				<div>
			<?php	if ( 'below-content' ==  ampforwp_get_setting('swift-layout-addthis-pos') ){
							echo add_this_for_layouts();
						} ?></div>
			<div class="bcs5-ssr">
			<?php if (isset($redux_builder_amp['swift-social-position']) && 'below-content' == $redux_builder_amp['swift-social-position']){
					design_single_6();   
				}?>
			</div>	
			</div>
			<div class="s-a">
	            <?php if(!checkAMPforPageBuilderStatus(get_the_ID())){ ?>
					<?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-description') ) { ?>	
						<?php
						$author_box = array( 'avatar'=>true,
													'avatar_size'=>60,	
													'author_description'=>true,	
													'ads_below_the_author'=>true);
						if( true == ampforwp_get_setting('amp-author-bio-name')){
							$author_box['author_pub_name'] = true ;
						}
						$author_box['author_link'] = $author_link;
						$author_box['is_author_link_amp'] = false;
						amp_author_box( $author_box ); ?>	
					<?php } ?>
					<?php amp_post_navigation();?>
					
				<?php } ?>
			</div>
			<?php
            if ( isset($redux_builder_amp['ampforwp-single-related-posts-switch']) && $redux_builder_amp['ampforwp-single-related-posts-switch'] ) {
			$my_query = ampforwp_related_post_loop_query();
		  	if( $my_query->have_posts() ) { $r_count = 1;?>
		  	<div class="s-rp">
		  		<span class="b-l"></span>
		  		<div class="rl-t">
		  			<?php ampforwp_related_post(); ?>
		  		</div>
	            <ul class="clearfix">
			        <?php
			          while( $my_query->have_posts() ) {
			            $my_query->the_post();
			        ?>
			        <li class="<?php if ( has_post_thumbnail() ) { echo'has_thumbnail'; } else { echo 'no_thumbnail'; } ?>">
			        	<?php if ( true == $redux_builder_amp['ampforwp-single-related-posts-image'] ) { ?>
				            <div class="rlp-image">     
				                 <?php ampforwp_get_relatedpost_image('full',array('image_crop'=>'true','image_crop_width'=>220,'image_crop_height'=>134) );?>
							</div>
						<?php } ?>	
						<div class="rlp-cnt">
							<?php $argsdata = array(
									'show_author' => false,
									'show_excerpt' =>false
										);
							ampforwp_get_relatedpost_content($argsdata); ?>
							<div class="rp-e">
						    	<?php amp_excerpt(14); ?>
						    </div>
						    <span class="athr-nm"><?php if( true == ampforwp_get_setting('amp-author-name')){ echo $redux_builder_amp['amp-translator-by-text']; ?></span><?php $author_box = array();	
							$author_box['author_pub_name'] = true ;
						    amp_author_box($author_box);} ?>	
				        </div>
			        </li>
			        <?php do_action('ampforwp_between_related_post',$r_count);
         					$r_count++;
			        }
			      } ?>
  				</ul>
  			</div>
            <?php wp_reset_postdata(); } ?>
			<div class="cmts">
				<?php amp_comments();?>
				<?php do_action('ampforwp_post_after_design_elements'); ?>
			</div>
			<?php if ( isset($redux_builder_amp['ampforwp-swift-recent-posts']) && $redux_builder_amp['ampforwp-swift-recent-posts']){?>
			<div class="rc-p">
				<span class="b-l"></span>
				<div class="rp-t">
					<h3><?php echo ampforwp_translation($redux_builder_amp['amp-translator-recent-text'], 'MOST POPULAR' ); ?></h3>
				</div>
					<?php $number_of_posts = 6;
					$rcp = ampforwp_get_setting('ampforwp-number-of-recent-posts');
					if( !empty($rcp) ){
						$number_of_posts = (int) ampforwp_get_setting('ampforwp-number-of-recent-posts');
					} ?>
					<?php while( amp_loop('start', array( 'posts_per_page' => $number_of_posts ) ) ): ?>
						<div class="rp">
							<?php
							$width 	= 350;
							$height = 190;
							if( true == $redux_builder_amp['ampforwp-homepage-posts-image-modify-size'] ){
								$width 	= $redux_builder_amp['ampforwp-swift-homepage-posts-width'];
								$height = $redux_builder_amp['ampforwp-swift-homepage-posts-height'];
							}
							 $args = array("tag"=>'div',"tag_class"=>'image-container','image_size'=>'full','image_crop'=>'true','image_crop_width'=>$width,'image_crop_height'=>$height, 'responsive'=> true); ?>
						    <div class="rp-img">
						    	<?php amp_loop_image($args); ?>
						    </div>
						    <div class="rp-cnt">
						    	<?php amp_tags_list();?>
						    	<?php amp_loop_title(); ?>
						    	<div class="rp-e">
						    		<?php amp_excerpt(14); ?>
						    	</div>
							    <span class="athr-nm"><?php if( true == ampforwp_get_setting('amp-author-name')){ echo $redux_builder_amp['amp-translator-by-text']; ?></span><?php $author_box = array();	
							$author_box['author_pub_name'] = true ;
						    amp_author_box($author_box);} ?>	
						    </div>
						</div>
					<?php endwhile; amp_loop('end'); ?>
				<?php wp_reset_postdata(); ?>
			</div>
			<?php } ?>
		</div><!-- /.cntr -->
	</div><!-- /.sd-5 -->
<?php }// Single 5 End
if ( isset($redux_builder_amp['single-design-type']) && $redux_builder_amp['single-design-type'] == '6') { ?>
<div class="sd-6 sgl">
	<div class="fg-img">
		<span class="ovl"></span>
		<div class="s-tlt">
			<div class="at-nm">
				<?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-name')){
							$author_box['author_pub_name'] = true ;
						}
						echo '<div class="amp-author "> <div class="author-details "><span class="author-name">' .esc_html($author_prefix) . ' <a href="'. esc_url($author_link).'" title="'. esc_html($author_name).'"> ' .esc_html( $author_name ).'</a></span></div></div>'; ?>		
				<?php if(isset($redux_builder_amp['swift-date']) && $redux_builder_amp['swift-date'] == true) { ?>		
				<span class="athr-ptd"><?php amp_date('date');?></span>
				<?php } ?>
			</div>
			<?php amp_title(); ?>
		</div><!-- /.s-tlt -->
	</div><!-- /.fg-img -->
	<div class="s-cnt">
		<div class="cntr">
			<div class="pt-cntn">
				<div class="acs5-ssr">
				<?php if (isset($redux_builder_amp['swift-social-position']) && 'default' == $redux_builder_amp['swift-social-position']){
						design_single_6(); 
						}?>
				<?php if (isset($redux_builder_amp['swift-social-position']) && 'above-content' == $redux_builder_amp['swift-social-position']){
						design_single_6();  
						}?>
				</div>	
				<?php if( true == $redux_builder_amp['enable-excerpt-single'] && !empty($amp_layout_single_excerpt)){ ?>
				<div class="exc" style="line-height: 28px;">
				   <?php echo $amp_layout_single_excerpt; ?>
			    </div>
			    <?php } ?>
			    <div class="sa-cn">
			    	<div>
			<?php	if ( 'above-content' ==  ampforwp_get_setting('swift-layout-6-addthis-pos') ){
							echo add_this_for_layouts();
						} ?></div>
					<?php 
                ob_start();
			    amp_content();
                $sd_6_html = ob_get_contents();
                ob_get_clean();
              if(isset($redux_builder_amp['ampforwp-inline-related-posts']) && 1 == $redux_builder_amp['ampforwp-inline-related-posts'] ){           
               $sd_6_html = preg_replace('/<div class="ampforwp-inline-related-post">(.*?)\s+<div class="related_posts">\s+<ol class="clearfix">\s+(.*?)<li class="has_related_thumbnail">/', '<div class="ampforwp-inline-related-post">$1<div class="related_posts"><div class="related-title"><h3>Related Post<h3></div><ol class="clearfix"><li class="has_related_thumbnail">', $sd_6_html);
               }
                echo $sd_6_html;
					?>

					
					<div>
			<?php	if ( 'below-content' ==  ampforwp_get_setting('swift-layout-6-addthis-pos') ){
							echo add_this_for_layouts();
						} ?></div>

				</div>
			</div><!-- /.pt-cnt -->
			<div>
					<?php if (isset($redux_builder_amp['swift-social-position']) && 'below-content' == $redux_builder_amp['swift-social-position']){
							design_single_6();   
						}?>
					</div>
			<div class="s-a">
	            <?php if(!checkAMPforPageBuilderStatus(get_the_ID())){ ?>
				  <?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-description') ) { ?>	
						<?php
						$author_box = array( 'avatar'=>true,
													'avatar_size'=>60,	
													'author_description'=>true,	
													'ads_below_the_author'=>true);
						if( true == ampforwp_get_setting('amp-author-bio-name')){
							$author_box['author_pub_name'] = true ;
						}
						$author_box['author_link'] = $author_link;
						$author_box['is_author_link_amp'] = false;
						amp_author_box( $author_box ); ?>	
					<?php } ?>
					<?php amp_post_navigation();?>
					
				<?php } ?>
			</div>
			<?php
            if ( isset($redux_builder_amp['ampforwp-single-related-posts-switch']) && $redux_builder_amp['ampforwp-single-related-posts-switch'] ) {
			$my_query = ampforwp_related_post_loop_query();
		  	if( $my_query->have_posts() ) { $r_count = 1;?>
		  	<div class="s-rp">
		  		<span class="b-l"></span>
		  		<div class="rl-t">
		  			<?php ampforwp_related_post(); ?>
		  		</div>
	            <ul class="clearfix">
			        <?php
			          while( $my_query->have_posts() ) {
			            $my_query->the_post();
			        ?>
			        <li class="<?php if ( has_post_thumbnail() ) { echo'has_thumbnail'; } else { echo 'no_thumbnail'; } ?>">
			        	<?php if ( true == $redux_builder_amp['ampforwp-single-related-posts-image'] ) { ?>
				            <div class="rlp-image">     
				                 <?php ampforwp_get_relatedpost_image('full',array('image_crop'=>'true','image_crop_width'=>378,'image_crop_height'=>213) );?>
							</div>
						<?php } ?>
							<?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-bio-name')){
							$author_box['author_pub_name'] = true ;
						}
						amp_author_box($author_box); ?>	
					
							<div class="rlp-cnt">
								<?php $argsdata = array(
										'show_author' => false,
										'show_excerpt' =>false
											);
								ampforwp_get_relatedpost_content($argsdata); ?>
					        </div>
			        </li>
			        <?php do_action('ampforwp_between_related_post',$r_count);
         					$r_count++;
			        }
			      } ?>
  				</ul>
  			</div>
            <?php wp_reset_postdata(); } ?>
			<?php if ( isset($redux_builder_amp['ampforwp-swift-recent-posts']) && $redux_builder_amp['ampforwp-swift-recent-posts']){?>
			<div class="rc-p">
				<span class="b-l"></span>
				<div class="rp-t">
					<h3><?php echo ampforwp_translation($redux_builder_amp['amp-translator-recent-text'], 'MOST POPULAR' ); ?></h3>
				</div>
					<?php $number_of_posts = 6;
					$rcp = ampforwp_get_setting('ampforwp-number-of-recent-posts');
					if( !empty($rcp) ){
						$number_of_posts = (int) ampforwp_get_setting('ampforwp-number-of-recent-posts');
					} ?>
					<?php while( amp_loop('start', array( 'posts_per_page' => $number_of_posts ) ) ): ?>
						<div class="rp">
							<?php
							$width 	= 350;
							$height = 190;
							if( true == $redux_builder_amp['ampforwp-homepage-posts-image-modify-size'] ){
								$width 	= $redux_builder_amp['ampforwp-swift-homepage-posts-width'];
								$height = $redux_builder_amp['ampforwp-swift-homepage-posts-height'];
							}
							 $args = array("tag"=>'div',"tag_class"=>'image-container','image_size'=>'full','image_crop'=>'true','image_crop_width'=>$width,'image_crop_height'=>$height, 'responsive'=> true); ?>
						    <div class="rp-img">
						    	<?php amp_loop_image($args); ?>
						    </div>
						    <div class="rp-cnt">
							   <?php 
 						$author_box = array();
						if( true == ampforwp_get_setting('amp-author-bio-name')){
							$author_box['author_pub_name'] = true ;
						}
						amp_author_box($author_box); ?>	
							    <?php amp_loop_title(); ?>
							    </span>
						    </div>
						</div>
					<?php endwhile; amp_loop('end'); ?>
				<?php wp_reset_postdata(); ?>
			</div>
			<?php } ?>
			<div class="cmts">
				<?php amp_comments();?>
				<?php do_action('ampforwp_post_after_design_elements'); ?>
			</div>
		</div><!-- /.cntr -->
	</div><!-- /.s-cnt -->
</div><!-- /.sd-6 -->
<?php }// single page 6 End
}//Function closed ampforwp_singlepage_design_type

function design_single_6()
{ 
global $redux_builder_amp; 
if ( ampforwp_get_setting('ampforwp-social-share-amp')  ) {
		$amp_permalink = ampforwp_url_controller(get_the_permalink());
}else{
		$amp_permalink = get_the_permalink();
}
$twitter_amp_permalink = $amp_permalink;
if(false == ampforwp_get_setting('enable-single-twitter-share-link')){
		$twitter_amp_permalink =  wp_get_shortlink();
}
if(true == ampforwp_get_setting('ampforwp-social-share')){
?>
	<span class="shr-txt"><?php echo esc_attr(ampforwp_translation($redux_builder_amp['amp-translator-share-text'], 'Share')); ?>
	</span>
	<ul class="s-scl">
					<?php if($redux_builder_amp['enable-single-facebook-share']){
						$facebook_icon = '';
                                if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                                $facebook_icon = '<amp-img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTKV7RYUULWZFQaT8j6oQVs239TDXYbHyj6_QB4RB3VE2HLDDQd&usqp=CAU" width="24" height="24" ></amp-img>';    
                                }?>
					<li>
						<a class="s_fb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo esc_url($amp_permalink); ?>"><?php echo $facebook_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-twitter-share']){$twitter_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$twitter_icon = '<amp-img src="https://pluspng.com/img-png/raunr7ow6f45t-oqr-afdc4tosutyax0vqeee4yggkl52toe9wgy6kbtglahiexgzvq-w300-300.png" width="24" height="24"></amp-img>';}?>
					<li>
						<a class="s_tw" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?><?php echo esc_url($twitter_amp_permalink); ?>&text=<?php echo esc_attr(ampforwp_sanitize_twitter_title(get_the_title())); ?>">
						<?php echo $twitter_icon; ?></a>
					</li>
					<?php } ?>
					<?php if(isset($redux_builder_amp['enable-single-gplus-share']) && $redux_builder_amp['enable-single-gplus-share']){?>
					<li>
						<a class="s_gp" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://plus.google.com/share?url=<?php echo esc_url($amp_permalink); ?>"></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-email-share']){$email_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$email_icon = '<amp-img src="https://getdrawings.com/free-icon/icon-vector-png-66.png" width="24" height="24" ></amp-img>';
								}?>
					<li>
						<a class="s_em" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="mailto:?subject=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>&body=<?php echo esc_url($amp_permalink); ?>"><?php echo $email_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-pinterest-share']){
						$thumb_id = $image = '';
						if (has_post_thumbnail( ) ){
								$thumb_id = get_post_thumbnail_id(get_the_ID());
							$image = wp_get_attachment_image_src( $thumb_id, 'full' );
							$image = $image[0]; 
							}
							$pinterest_icon = '';
	 							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$pinterest_icon = '<amp-img src="https://icons.iconarchive.com/icons/limav/flat-gradient-social/512/Pinterest-icon.png" width="24" height="24" ></amp-img>';
	 							}?>
					<li>
						<a class="s_pt" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://pinterest.com/pin/create/bookmarklet/?media=<?php echo $image; ?>&url=<?php echo esc_url($amp_permalink); ?>&description=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $pinterest_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-linkedin-share']){
						$linkedin_icon = "";
						if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$linkedin_icon = '<amp-img src="https://img.icons8.com/ios/500/linkedin.png" width="24" height="24" ></amp-img>';
								}?>
					<li>
						<a class="s_lk" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.linkedin.com/shareArticle?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $linkedin_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-whatsapp-share']){
						$whatsapp_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$whatsapp_icon = '<amp-img src="data:image/png;base64,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" width="24" height="24" ></amp-img>';

								}?>
					<li>
						<a class="s_wp" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="whatsapp://send?text=<?php echo esc_url($amp_permalink); ?>" data-action="share/whatsapp/share"><?php echo $whatsapp_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-vk-share']){$vk_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$vk_icon = '<amp-img src="data:image/png;base64,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" width="24" height="24" ></amp-img>';
								}
?>
					<li>
						<a class="s_vk" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://vk.com/share.php?url=<?php echo esc_url($amp_permalink); ?>"><?php echo $vk_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-odnoklassniki-share']){$odnoklassniki_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$odnoklassniki_icon = '<amp-img src="data:image/png;base64,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" width="24" height="24" ></amp-img>';
								}?>
					<li>
						<a class="s_od" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://ok.ru/dk?st.cmd=addShare&st._surl=<?php echo esc_url($amp_permalink); ?>"><?php echo $odnoklassniki_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-reddit-share']){$reddit_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$reddit_icon = '<amp-img src="data:image/png;base64,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" width="24" height="24" ></amp-img>';
							}?>
					<li>
						<a title="reddit share" class="s_rd" target="_blank" <?php ampforwp_nofollow_social_links(); ?> href="https://reddit.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $reddit_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-tumblr-share']){
						$tumblr_icon ='';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$tumblr_icon = '<amp-img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAdVBMVEX///8AAADLy8vr6+utra0WFhbd3d3v7+9CQkKenp42Njb6+vr09PRlZWXS0tLg4OBZWVm8vLyBgYGKioowMDCysrJPT08rKyuoqKjZ2dltbW2Ojo6Xl5ckJCQREREbGxs9PT13d3dLS0tcXFxoaGh8fHzCwsKIzAbQAAAETklEQVR4nO3d63qiMBCAYbQeQFBBikc8tLXc/yVud7snuygTJpnJZOf7b837VCVEJFHkpuV60NFm6+ipaZp0+b634h4lphwiHEy5h4kIBBwMuYfZv2nwwmXwwiJ4YRK88Bq8cBa8EAYULNwFLxwFL3wJXrgJXQicswkWAmc0goWr4IXn0IXAEwvBQuC0W7AwDl34BAZKFcJfpFKF4E9SqcIFHChUuA9dCJ6TihWWoQtTE6BI4SF0YWYElCh8CV0IXWOTK3wOXVgbAsUJQd9sixYeQxeaTEhFCqEr+WKFpgcKcUKDpQuZwuwUuDA1PdRLE/YFihFm5gdCWcKnfu9BOUKTtTWRwi0CKELYY6omSjgxWN8WKTRaORQobPp/hooQdl+pLlu4BF58KFU4mVvxDQansWH5hMLXvFry9co5cYn+/ESWO+VlSd+TCIu5BMKvk3GZSyHw0mbHOQT2W4qxnkOh+ZK2k1SoQhXyp0IVqpA/FapQhfypUIUq5E+FKlQhfypUoQr5U6EKVcifClX4IKNfhzrr6FAY2bhGCF3tUphBb6zjsKtL4EfNqCWDOwx8rWz7e49aOAa2h3h/8gzYOMQVDN5e13abClXofypUof+pUIX+p0IV+p8KVeh/KlSh/6lQhf6nQhX6nwpV6H8qVKH/qVCF/qdCFfqfClXofypUof+pUIX+p0IV+p8KVeh/KvyvhQ332GGp8EEF99hhIYQV99hhIYQj7rHDQgi33GOHhRAm3GOHhRA6/bmkvRDCA/fYYSGEK+6xw0IIXf/m1VII4Zp77LAQwhn32GEhhGfuscPC3Mk84x48KIzwiXvwoDBCGafAGKGMiSnmjiB77sGDQt3zpM8TLiriFzdKaH4OPIk/HvY8dQC5m+F+6redTJ9t9fk40tlQihEaTr6L3/uEkR5JUUKT1ajh5c/DSDZ++tWYhri42Wdq6ZT0Jeyth0rIkzTx7YNIhXH7wOFtuu7EM03+2aePVGhhF7b57v6fT6u85RGkQisblcVF2qbbJXfuMEYqRNwK66Z1PfzrQD5dVOWDPexIhY0l4Y+O+fw6f4s798ckFfbaOB4bqRA3qZEgjJB75goQ2tq001+hrQ9Tf4WYzcdlCJFnFxKEbdOqsIRV8ELUQoYIYbQPXkj/aUotpN9Jl1w4DF5IfitseiH1fsj0wugQvJD4w4ZDSLv1OoeQdu7GIrSzrOi1kPJkn0mIX+D3XhhduscmXEj2X+QTUr0XSb8h/dKKRMj4P4yiEYWw7ZsqugjOh0HfGzssfQscGDmewdW8L9GfpVdHvJk/P7RZuPhK6s2v7YW2nd/kGnYgvZwNlE3j2tPf8hV2Zqqb2uMLpnfoBfHxgXOOBimrHlw20lVc+877bDrq8w3c+b2Q8XuFz9JFbbJsfFlVHr/17jepyteu4+RsfqhkvDLvli2HRVK+7/PLcfz9cHIan5/jfP9eJkWzs/6y/AZip1+POfRWfgAAAABJRU5ErkJggg==" width="24" height="24" ></amp-img>';}?>
					<li>
						<a class="s_tb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo esc_url($amp_permalink); ?>><?php echo $tumblr_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-telegram-share']){
						$telegram_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$telegram_icon = '<amp-img src="data:image/png;base64,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" width="24" height="24" ></amp-img>';
							}?>
					<li>
						<a class="s_tg" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://telegram.me/share/url?url=<?php echo esc_url($amp_permalink); ?>&text=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $telegram_icon; ?></a>
					</li>
					<?php } ?>
					<?php if(isset($redux_builder_amp['enable-single-digg-share']) && $redux_builder_amp['enable-single-digg-share']){?>
					<li>
						<a class="s_dg" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://digg.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-stumbleupon-share']){
						$stumbleupon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$stumbleupon = '<amp-img src="https://icons.iconarchive.com/icons/sicons/flat-shadow-social/512/stumbleupon-icon.png" width="24" height="24" ></amp-img>';
								}?>
					<li>
						<a class="s_su" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://www.stumbleupon.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $stumbleupon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-wechat-share']){$wechat_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$wechat_icon = '<amp-img src="https://cdn.worldvectorlogo.com/logos/wechat.svg" width="24" height="24" ></amp-img>';}?>
					<li>
						<a class="s_wc" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/wechat/offer?url=<?php echo esc_url($amp_permalink); ?>"><?php echo $wechat_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-viber-share']){
						$viber_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$viber_icon = '<amp-img src="data:image/png;base64,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" width="24" height="24" ></amp-img>';
								}?>
					<li>
						<a class="s_vb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="viber://forward?text=<?php echo esc_url($amp_permalink); ?>"><?php echo $viber_icon; ?></a>
					</li>
					<?php } ?>
					<?php if ( isset($redux_builder_amp['enable-single-yummly-share']) && $redux_builder_amp['enable-single-yummly-share']){$yummly_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$yummly_icon = '<amp-img src="https://is5-ssl.mzstatic.com/image/thumb/Purple124/v4/f2/ac/ab/f2acab60-e345-9d55-d236-161849bf0a45/source/512x512bb.jpg" width="24" height="24" ></amp-img>';
								}?>
					<li>
						<a class="s_ym" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://www.yummly.com/urb/verify?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>&yumtype=button"><?php echo $yummly_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['ampforwp-facebook-like-button']){?>
					<li>
						<amp-facebook-like width=90 height=28
		 					layout="fixed"
		 					data-size="large"
		 					<?php ampforwp_nofollow_social_links(); ?>
		    				data-layout="button_count"
		    				data-href="<?php echo esc_url(get_the_permalink());?>">
						</amp-facebook-like>
					</li>
					<?php } ?>
				</ul>
<?php 
	}
}

function design_social_4(){
	global $redux_builder_amp;
	if ( ampforwp_get_setting('ampforwp-social-share-amp')  ) {
		$amp_permalink = ampforwp_url_controller(get_the_permalink());
}else{
		$amp_permalink = get_the_permalink();
}
$twitter_amp_permalink = $amp_permalink;
if(false == ampforwp_get_setting('enable-single-twitter-share-link')){
		$twitter_amp_permalink =  wp_get_shortlink();
}
if(true == ampforwp_get_setting('ampforwp-social-share')){
	?>
	<ul class = "soc-shr-ul-s_4" >
					<?php if($redux_builder_amp['enable-single-facebook-share']){
						$facebook_icon = '';
                                if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                                $facebook_icon = '<amp-img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQB3oqAInyZQ9KXf9Uifpe_GGKZcf_GhwSOJcFTZdbdKh6hU3U9&usqp=CAU" width="16" height="16" ></amp-img>';    
                                }?>

					<li>
						<a class="s_fb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo esc_url($amp_permalink); ?>"><?php echo $facebook_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-twitter-share']){
						$twitter_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$twitter_icon = '<amp-img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAN4AAADjCAMAAADdXVr2AAAAkFBMVEUQEBD////+/v7t7e3s7Oz39/f6+vr09PQAAADx8fH7+/sNDQ0ICAjg4OAJCQkVFRW9vb2Dg4NbW1ulpaXJycmRkZHb29vm5uZRUVGZmZkxMTFvb28+Pj6zs7N5eXnV1dWJiYloaGgoKCg1NTVGRkaioqIcHBy5ubk9PT1PT099fX0hISFqamqsrKwrKytYWFhSfSh9AAASCUlEQVR4nO1daWOrrBIOojJozNasTdO0abo37f//dxcwUUFwiZKYc1++HA/YOI8wK+PQc1hDFLNGEL922ZUrOgnvpLwTiU6fXSIv7VTHA2Wc/6jr8U6fj2PRmRnHunFBCe90gpgSuRfFvenzM0QHMlHiB3oJPDcHz1XgCfLSzrLxmHz+U35Kk3k8hRdTEiSUZN6Uk4GnEh3IRP0H7/bhId6oyxrhVw6/YiueNcKvqBgXnT6/8vgVRrrxIBmPOzG/9PiVL8ZRlfGUkiClJO710t5KRPPeHuXN81nz+BUJ2FVA5E7KO33RSfiVT43jXjruG/+obNxLOgOa3qr2Ks8P1OfzW3s4FYpsMt2j0HMloeamnXwxoHgxmMcDeYW7mRVcNh4olGCc9Kq3pkRjhej49/mtR3gKW6Rr3TevdZWtdGyTY1DtuMpACoPm2AolRDuewrYpg+L/C3g6qVHC9TErl41r5ZMqNSrJp7i3TNSp8off2iO8eaKll9pOz9jZ4I8q/Wi1R6md/PofUgzqFAvF8I+r9f8LeK4BnlbPZOBJKktLvqIczeMZ5VURnmuA5yrwHK7wMRF9mF8GopNfik5HdMaCmF/S5I+KxvkFjmcn7SwbTzszlKi3YuX5KtHxT/HeniIVVMVQwMrnK4aCcb1iqCTqtIrhX1fr/8G7YXj/OO8lQqi+5CwZ14q7NiUn1hDtKZJTLJHu6T2EUkqa6r1OWS18RYgVjCkzi8XVP2KUcV5eTF+/nve/QwAIdw+T8d2UcRah58KrLhUsewwcxejpBUTrD0LWBvF/do93c0LwWR7D+f5Uq+M+opsJxxX2cq3P+verheMV/1S2U/yP+3td8NZZZ/TFV2Me2qmx0b8RW6OVFINDRnPcGaOMBttlIbYTwt8D9crVOkP/HNLOWC1kysCVYTuu0t93hErgIfoEMCMSvJNKu3ycky4eK4KLAS4jvjhlolOViRFZsYUAc3qMc6qxYSQHnC1HqQNnE1YHFy/RFXIMzydBNOarHCb+KUqtSAV0x3BfTDFQdw2DWuh6vRBetlpRhtB8HbMwbBPFIKt1tICxdzG1Trbf9abuNIHviSlzkgWUuIeHo3yCT5PVghj+O3IheGRTg+vkCXzis5cQzTh4xAg//RhMDfDY5LG/HdGLwPNW5crAOIE/CTzWtl/fGcUCS99klD2yNzCIl24Bb7XDe0/nTd0JA3+9TJh4ozWzTzMMHMKWpEZZljK8EE+EYURd2+6sN26CjhH5wNbH6OkDQBFOMDOFkpjOiO/YLahlte41mjtB5Mc3t1Fz3fcI6WMtmLz0T3/KZLZNeOS1KTqm4nUqhWl0ZAgl0W3yTNgfs0vswKOj86VKcYNXlA8lxRqbztJXCp/YL+Gt83kPRbWVeVV0k1RgCN7z01139JFZMfBJeG+cSqBaboqRph3X7u/Hl96++dLUo7v3EqKF6dfLmNBYWjGwd6lhq94QSlLGsc4u5+OksVgxoevhzPMVte6P5Kdy+WJDrdOpJXR9JlaysRZMs/AC9aXC/YJagEde7MDj6LJEIXc1z1gtzqf6WHibe617DCfl2nYDiCSioifm1PbSKIz/lhNnwKzTZrv+uXGFw9tD9x05x0d5AVrcMfdhGZBMKGmheat92BDcrmKwM3mwJOREFN0suakGVDLKtrrnhrAibap1j9iYvAH3kCgPa/vz1T6OucFItlpG+tcKa6ru6jeA59iYPM5DbCHR6WoyOMUTYezLoaQ7w4PhIZJDRU5JKEmFl1GOmAzbt1fg0Sfbw5jPWrI02GJNQkli78rMFMAcQEfaJTPvXeV20dL8AnG1aX/ywt1+KIL1WZJ3rqAqVQzU7IANYEVxO+7sn4W1GeYi9wNYqKEkUuBfhvC3oC2odUQtGSwquVOkxlqK4LHZDkcINYdn4u92G7yfJEBVeGy+Zzz61hDe5BLweKDXUUNJWW9P/1dMgjbkvcCWFyvR+SSslDRVPJacpQuH70xkdvXVrXyq28pP9/d5foEtX0FGh2JxLiRnqvfeS58dx7/P13ukbIG0gW5sCCVVebXAjQF0rtVC7LOekBBaeDqTWvP3w9FxA6o+PLqzFGI5tTCWKvpU8bAS3w/gc869iPoeA44sS5YBvCIllJT6Y6hqgAdg7SK/vr/nz+2uTeGdyk/tiWwS8eJzwYiiH5r5ZygGu4ITelGyWpzjHBaEkopaCPBKSU21XkE2N0G351tc5qwkz63z9BB6K5fUg9dC5N1MzqMwGnNZSRmV9VDr8QPozRZ+jTinpThETMsdcZCsZwW8dIPfQ3W1LnONvxbI0X+bls8/qP37lVs4nPvZr+DSKHUmxofrsz7jwcmI0mqKgdqKTvf6L6g8VRzT39x2WXkD2K0E/aVq3VrwvTfY5bMi81lJZ0ZY+wA/UyS+qS6GZ433BsMq8HA1uyzf2BodPk0pobgIHrUmOcOeCZ6kkcnz2QQwMbMbT6lTxHsW4khxC3sm3pOlQtSEAIYwfNxEhJqsFm2guI0W9qjhy+dUrYsMmPOnTzQeRX2YTYNE1EhqvZbdUKcxeJVyqZtNX/woHnJczqY80ZtKG/Qo6FnyGKrCc0TiTvPHcYj3z7P3bZwdi2OnsLJPUrcN3ki1VHHUnk/WF6Hjh/VqM90uqMd/3pbiG+xItaoDFLUr3kI4prbff/58zX5sbTu/IE3VgUBXdcBKnLwX9tVtgDYb7FHVqgOI2Eo6sddgUslqEX2WnWobDdZGeKnHkASg7flllhoPblavOoDWN4YPZk6dqgNoeVv44JWWVR3Afrqv7Dj14hLXbrAptVrofouSjwJRYMvAsNJgWgyPJ7IB7JlTiuPZQ5fZimupia9O9F9fBkG8wU/emLn/exfxEFDgBNz6vMBmXCstBCylKlAncHJVB3imMbMtJhscf+2IDvlM5W62/g6ZFEOq1uOMOW4kPjxttpgJ0vn9bSxQbrSUWi2pvSvMw4+fp8NhdxPrk2n1cnhykDW2gW8CXQ/ujPBS3jvcxkrUNJFSVlR1gO+1354lfWpML2SrDmBd1QHs3shSzLXBW5wsrdV7iVHmvNyIHlAbLD0Jnj6UFDT8aOlqDZ6IEV7qMdTZne1Ug3eayMeCqgOLG2U+mPu6VAW16gC5MS/v2AZv1UpB0cukI7bdYOKlaUMFoSR8m6sTVqQMXhznbLD/dcXGfNk0/0GNc2aj1OQWDZcQDKkKmqoDtxViEQ3+iiobSztENxjBFZ+TuhWsFrG/d1MRJNEgqgHv5qZvMERF8NT9vVsKkPEmcsnMvKfmKlr6vM5agw2tVcDytkyXEBa4VoXH29pfYBZZTXj0lrYv4UAL4WUyAk/7ezckPUOIcGEBy7TWf5wFyRMvb2f7Ep6RXFpKzefUfVhZ8rVUh1r8rZeTZgOr2bhK2o64EzfNvbpUCwFn4FXISjrt792GdoefoASedvOZGQC2MmxabTD1UqLVUJL4jsH0FYnz1X3zpb8LCr6CEV+hmApYYnLX+e09WJ1fVRyjbc3yfZduIRDUpOw2mXTagIEfVLGqONLC41/9dHgCebm4MnjHb2eDwPGOu+7s8lhVIGB/U6d65mUbTwPUER04sRXDe8sqGxM0n3QUILOmyw/J0qv1jIZEaDvp4i704Fsh+tyy2wzguK+W4rt644VkWqoqjnkBzKG9bNozWtjHVWbPzHvMDU4/HsWUONHhuTszCCvfKDCUVPH0rIHjrjsvILCYirvir4b5K5iu1i+dYcEQXB3R2VIImaoDGrVOv2G4nzw+jsfjx8fJg0hz6dDkPZFUWZ9jtZAVHEvOQ/cSeNjk4WZnoXQ6EYSJzRpnoWhL3nfYZR8MXVztLBSzv+R3N2IGB01quNbfS6sO5GSstTKaTRu86LRZ1lvXVB3IqXXa1UQXUemp8VkouKOZIHw/tio8Oc6ZZMKLO7uZJCiKF+eDs/o4p7LrLp814HcxngszX3sWgnL2bq7qgKIYuIwN3joXTurvnNYOqOvgdgqcPrZo4wQ39NUxfOJbttbgBbRbym/Q9+rBk/fWcwfU4ajfJfaDUex/VuW9klASu2feJT/oWeApJ7pyKImLl664Dv03T6zL6mq9SijJWv39uo1JTbd1eA7adGN9ioMy6sGrtIzJqAtBMtgXHl5SXHUgSI8VOH7AH2SqOEVvV8cXQkSksxACE9FFVQcSa1UqUIm85bUZEN6J4QAFrBBdVHVA1pBp3Vc0u+4C5cdbSRXeWj5W15/+XnECYRLgc+AVegxSgUpKrzeBsPMy1RWrWy21jqkMFuvrAGRiJahbMNNcdcB8QB3aPl8jpAsjWnqqVXHVgYrnzlIy57thl0UIr+Rix+pSgqfPFmvLaNBxodkEnmuAZzrzGTlTcbpYfACubXSPHtbBU0NJMtFJ1YHgdKwAu4wNgkDuFCdyUym/wOFsMH39mnx8h2+W0U1QbKWIrACcpArkiHZOnfF4QKWqA2edt86ejO3GK+CTNjhvvbpa1x8WQiO7sV54wFRlKytWiw6e9cQeeHDztUouBQ85j3Y9QYGuCbxGvDc962DVGuiWLi42M8p4j7+CzLECxrMG8vkF3EKzG0RjUkUsllxWQAnRuqoD5XpPWsEIHfqWFTtMjkDUdVdH79W2Wo6ZWB+24y/wSGJKTPDsGGW8M7KeRxfCzMPN4dX0GBgrM6PafhZkX4QejpTkpEZ1j6G6vxdfBcm52DYbDKaO7LlVpU9fdUCsOyFjudQ4KgYRShJvK2Zl5i1cxt+Dl4jSlJLTxquYGN7r6oimCdEnxVBPrVPi3t1fwA8K4ZnSjAK/gNXC2W76qDmEuP3Gj0SXgDSHVxbnRCha3V/GR4fdHKWUHOGhIv+zKM6p1MbNf8BPPJ9sZ/cXmTixMAlSKcllBZQSbag6ICsGZtX4KDo8607GttPYwvSOZ8QHKSXVFIN+f0/cKWvI2ER18HYz+7tksmMIn1GSIJ3WzW+k1hcRiVNuhVpEvBT4YrEdvX7FZxJeMCTGpk576kEjeGQMw/v9cvK8/vpar5+X+/u3q2SohjBZ6M+saGiURXvItv7gGjsJAO8yQ7XDe/x/3mZ45d0tgHGQlqtSz2pqGkpCzuya2dJ9+IuO56YFioHRklpH+GrfQg3gfsqPSLIIj5uk1/kWKoS3V0+RDy3CS5exT6Z/lwY4AFgx+yJlqLZ5L1t1wEHRRbe3+vB2xyzEov1/X+4NfOVWkrm1vOoAQtvHS31sAnB/iPN+/cwKSyg56j2ttawPJSl2tzbWgkk0G9pfoyHA33sS3VMYqEW1rsIT+3ejT7tTyIyHp4VHk40Ke/C0BgBCCz6FdriwD7AcIVN+wvlWS52qA77jc+e8dYQM28vrAgVV40FllwWdhVUH+NvwRj9tftXGK3q/rOaF0Ua9YlAnpqZRZoi18N/ciI9Km08i//7vc7YNuH2iHoGmMpAFq8WYS03odPbZyD8SFbx/vzYLj1BPlhoXgocM8IRKohT529XyHB8wPoboZTzCBccPZuNDJfD0qQznVB2gmQ/4HfGZ2PTwdfQMyz3C8Pjl5sN6NV344hzs5EcN+QkxJYGGkvhWtbeM6CpVB1SuJ9vD7OfhLXF9ed6HyPzg/4SDfuIUv+3Xs8N24SiCv/g8dgMlTUJJOg3pKGs9s0PE3jXxEImmh9nj8/Ljd5jx83vfH/vJejw7TBeElxKWrJJYF5vPpb2U1VJlAyz2MLiKYS808BesHRNBTh+Ca47VvR14mXGxznhLjtXVk38deDV5L6NG6x9pfQXei48gEipESFPxukTncaueN9HJQ5CnrXrdOFXGA/mPxBmDpeM5Sqh8Ky24VSGquOpAfgNM8a+kVHJpvECvtaP3YqKr6L0a8JS1rrKVUW3jEnhXNMpuHZ7W+K7E9ZWkgip/VFfOtsdQybU6a9zKH9W89RRKEi/WcZIX72S26h3dVn1+Kz9Ixo8rKH6bjpR/UDaeUhIklDi5rABuw+aIDhSiy6oOVFPrxnGsY9Cbs1q6DU9WWXLVAYN/5abwJJWljGNXoxzN43binIVVB8q36mts5av7/9pxLSUFtzarOtARxXC9VPGOq/X/4JngGce7BK94Gd8671Wn7Cbd2X9crf8H75+Ah3PwsEK+MLrSTnU8UMaF0MsZZViBpxplJ0qQCg/njDKF6EAmSsD7H5r77ORgqiWsAAAAAElFTkSuQmCC" width="16" height="16"></amp-img>';}?>
					<li>
						<a class="s_tw" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo esc_url($twitter_amp_permalink); ?>&text=<?php echo esc_attr(ampforwp_sanitize_twitter_title(get_the_title())); ?>">
						<?php echo $twitter_icon; ?></a>
					</li>
					<?php } ?>
					<?php if(isset($redux_builder_amp['enable-single-gplus-share']) && $redux_builder_amp['enable-single-gplus-share']){?>
					<li>
						<a class="s_gp" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://plus.google.com/share?url=<?php echo esc_url($amp_permalink); ?>"></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-email-share']){
						$email_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$email_icon = '<amp-img src="https://webstockreview.net/images/email-clipart-gmail-18.png" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_em" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="mailto:?subject=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>&body=<?php echo esc_url($amp_permalink); ?>"><?php echo $email_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-pinterest-share']){
						$thumb_id = $image = '';
						if (has_post_thumbnail( ) ){
								$thumb_id = get_post_thumbnail_id(get_the_ID());
							$image = wp_get_attachment_image_src( $thumb_id, 'full' );
							$image = $image[0]; 
							}
							$pinterest_icon = '';
	 							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$pinterest_icon = '<amp-img src="data:image/jpeg;base64,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" width="16" height="16" ></amp-img>';
	 							}?>
					<li>
						<a class="s_pt" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://pinterest.com/pin/create/bookmarklet/?media=<?php echo $image; ?>&url=<?php echo esc_url($amp_permalink); ?>&description=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $pinterest_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-linkedin-share']){$linkedin_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$linkedin_icon = '<amp-img src="https://i7.pngguru.com/preview/756/990/413/resume-linkedin-computer-icons-social-media-the-law-office-of-roger-m-nichols-social-media.jpg" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_lk" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.linkedin.com/shareArticle?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $linkedin_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-whatsapp-share']){$whatsapp_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$whatsapp_icon = '<amp-img src="https://lh3.googleusercontent.com/proxy/GjjIlut8ztTbiVgfdr768GRNiWkF8V3J5_EHhGAfOmqW2XLwFEWrWAQn79sUHX-gXckAxfu0Lkju5QeJ9hyM6Z5gT5iQShqG6TW7oanrOdcPLViMjOYX-MH5wq-fnmjMzZ-lQPHsteMWpRSXTtAvSB-3BqVVScwxkg" width="16" height="16" ></amp-img>';

								}?>
					<li>
						<a class="s_wp" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="whatsapp://send?text=<?php echo esc_url($amp_permalink); ?>" data-action="share/whatsapp/share"><?php echo $whatsapp_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-vk-share']){$vk_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$vk_icon = '<amp-img src="https://image.flaticon.com/icons/svg/25/25684.svg" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_vk" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://vk.com/share.php?url=<?php echo esc_url($amp_permalink); ?>"><?php echo $vk_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-odnoklassniki-share']){$whatsapp_icon = '';
								$odnoklassniki_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$odnoklassniki_icon = '<amp-img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAATYAAACjCAMAAAA3vsLfAAAAilBMVEX///8AAAD8/PyXl5fHx8f39/deXl7CwsLU1NSurq6Li4uhoaGzs7P29vbQ0NCbm5u8vLzi4uLo6OhJSUnw8PBYWFimpqY3NzdtbW0qKirj4+MSEhKDg4Pa2tpmZmZxcXFGRkZ7e3sfHx80NDRHR0c+Pj4sLCw2NjYXFxcjIyMLCwtQUFB/f3+QkJDqftsMAAAGq0lEQVR4nO2d7XraMAyFCQTCR4AAKf2AQmErW0d7/7e3BdqOwJHsOCVOLb+/EY9zHjuWZUlpNDwe2TSn3TBKkijsTtu2x/I9aD4OOrfBCT86427T9qjqTTtZ/QkQnXhoe2y1pbuGkr2zHtkeXy0Jf3KiZTyHtsdYO7rPKtEybv2MO2V4pyNaxsq/4z6JdUXL6NkebV1gd4JL7myPtxY83BdTLQjuH2yP2T6PRUXL6NoetW1CE9WCQLgrkpipFgSJ7ZHbZGSqmuh1OjFXLQimtkdvi2EZ1YJAakjpppxsHdvjt8O4nGpBMLf9BDYwctjySHR7n8rLdmP7GaqnV141gd5bm5Vj1gonabOZTsIWOykXth+japj9YDFOT385HePrhQPCdgVmsgElBuSPt9UP3SakEJ0U/TwlXby46pFbZUuo0KIM3giDX1WO2jZdQgQm4E3tvJPqRm2dDZaAfcET6/qtqjHbp4kV2PBW+MpBkA+C1+hOZfYCzR6rGHEtwE6bMvCIg5pyXLcZevyV2q5jaOcG2NfVWGw4aHL9AdcDGAt/1rG8RZZSouPwukrrFQWdECmXf3BH0HJb4TyVkhSCHDDNM/lvYLq87mhrwwo8u2agdg9MpaTSID+ir2e6BKZSPBAUsNU8WvaBqZSLvx/g2cd6pi1gOrvuaGsDmm1koC0Pmm1SZEOh2hLvNimLFO2kms+OdlIpWwJKC3/RM0XB9PV1R1sb0Htd72QJTwmar8VvD8yn17qCghcKUq7mYXB3r2MJr+ilhHdxOqDGKsXZl2LSA2HYTOPNDkuMbq8/3pqAnFaN0BEO7mp6fA6AixGU8V04ScVEKcnEGcW8wXNUzqsNnxMChStBVH5IOVplUCVDEW0SFTdxD0ID+l6AzFmtctTWgeerDCLCTZacCsqcabAFMOCUxRS0wSxCdyG2xYyXXm5zbMdMna4cp+1ISkvxj86gO20eevbMYd6H1MnGpTF/sFgofyIlZnQCuogpiDIjzkFKlOB+ILKZCrMr6PFq+wnsoNWfh0ZOxCgPv5sqEbeLfkCVJ2gh8sV2xLifhZyLF4hxVamUXECCQs20/iOrQg1AxdFY5ATCSQxaDUgqTyNpF+wH0vHtZI8U6ggip1hIyVR7wq2kFG/okeDyvTN2fi84hwviHnkWdUulTcJGcleCT1MK0gERFdnHgi7fTWiH/X2uROj+xnewb2Qzar/487Jmp0/6GMW9ea+XRA9cV+e0d7cNFjdzAZ2f/zf0WJZcd+nm8680i0G+LzkHrdSBPBcEcDyB5izVaGbcuO7cQXa6OOEyvGZ403l5HHM4lITSAbcGzxvuwB+5u9viI3vRE9MI+3buHvGpc+d9rD9VIthEJHD4/o+74OtrFWVMW0znQFcvAPlu9buxImg7nVMT7YirZ1Zlk//f62SKl2saLZV3+K7GRx5UD35g1p9Hk3R4lK+ZTkbx243WBzycrb3Sku2D7e5+q85wO8FZD6R0jhGHu9W4JTv987i6kTaM7+B1cPhwdcVl6njo6Eq6uXuyeudLOtef4/QKPVIqFxDj6vkgx7DkN2DO2Qu4Sjgw/0rVBrafpjoecP22ATtnj1SQL9oZxOWiDlFzrIKUvTL8lqQlhXuVmrOVvjHBWp7FwOEzqJqIrxcl6IiuSjgwjAsq10kkvtIAzbDPXxN8sm+NvGY5RoMNe10wW8aCP+XKMxn1WptzwdatuCt119TnwhNWfCPGkwEiwELLlIsA4+bS2n0Uhqgw9bqxkHW5XjcG5mbL60bC1oDL6p1VAEXlvNcNouw34HUDaHRpENgJSoVWbwuv2xmaHUG8bjm0+6h43U4o0H3G6/ZJoV4gjucW6VOwg4rX7UDhvjOC0hdoDLr1eN3MehyJ181INfG6GaomXDdOtX3a3njdEJxqh1b2ZKP7QECWMwVXuvZe++51u0BDtdP+F163A5xqJ19i5goZxOVSaqvG6yZuvnGqnX3Oj0u6FDbfCqjWaLx63Y5wlTDgU8obr1sG16LhCRlwuomoHDrAZJ0+4dJt8oNNQbCoePDWYBoUP1MF7/Abm0ekHLPoJUeqxukm5YswZIouoxqnm5AKBVI13ozUTUgmtJlqtG5CZMNfuCL20FMI3YRkkW+gajqW0A+R4oGgk5XGXMtAuonJs/xlqhpcp0I2UnAkfdK3vZhvUrzdxkWs+2cR2zPdHO+4mycX676IFPG8ilUtl5pVuDTopB+GuDSa4duxp93eIPAz3RxMF0sxu8EpkySJDB+8OYlCIV6uRzx/AYTtTarEclGuAAAAAElFTkSuQmCC" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_od" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://ok.ru/dk?st.cmd=addShare&st._surl=<?php echo esc_url($amp_permalink); ?>"><?php echo $odnoklassniki_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-reddit-share']){$reddit_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$reddit_icon = '<amp-img src="data:image/png;base64,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" width="16" height="16" ></amp-img>';
							}?>
					<li>
						<a title="reddit share" class="s_rd" target="_blank" <?php ampforwp_nofollow_social_links(); ?> href="https://reddit.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $reddit_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-tumblr-share']){$tumblr_icon ='';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$tumblr_icon = '<amp-img src="https://pluspng.com/img-png/tumblr-vector-png-tumblr-letter-logo-free-icon-626.jpg" width="16" height="16" ></amp-img>';}?>
					<li>
						<a class="s_tb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo esc_url($amp_permalink); ?>"><?php echo $tumblr_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-telegram-share']){$telegram_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$telegram_icon = '<amp-img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcSOrtVbsAeA_0uwEG_uQJZjPTJpu2e9SYEGl_-A7LHr-vRTxGUo&usqp=CAU" width="16" height="16" ></amp-img>';
							}?>
					<li>
						<a class="s_tg" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://telegram.me/share/url?url=<?php echo esc_url($amp_permalink); ?>&text=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $telegram_icon; ?></a>
					</li>
					<?php } ?>
					<?php if(isset($redux_builder_amp['enable-single-digg-share']) && $redux_builder_amp['enable-single-digg-share']){?>
					<li>
						<a class="s_dg" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://digg.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-stumbleupon-share']){$stumbleupon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$stumbleupon = '<amp-img src="data:image/png;base64,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" width="16" height="16" ></amp-img>';
								}
?>
					<li>
						<a class="s_su" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://www.stumbleupon.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $stumbleupon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-wechat-share']){$wechat_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$wechat_icon = '<amp-img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcSDot0LaVj7-uxI0oWttIgbrcBfYvgP7t16tC7_RwkNxMgq6VGx&usqp=CAU" width="16" height="16" ></amp-img>';}?>
					<li>
						<a class="s_wc" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/wechat/offer?url=<?php echo esc_url($amp_permalink); ?>"><?php echo $wechat_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-viber-share']){$viber_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$viber_icon = '<amp-img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTtjuevsICmelvombQM2nAv3h4_F9tSXTDPG6jn3UH8T9PZHOjP&usqp=CAU" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_vb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="viber://forward?text=<?php echo esc_url($amp_permalink); ?>"><?php echo $viber_icon; ?></a>
					</li>
					<?php } ?>
					<?php if ( isset($redux_builder_amp['enable-single-yummly-share']) && $redux_builder_amp['enable-single-yummly-share']){$yummly_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$yummly_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgODk2IDEwMjYiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCAxOTN2NjQwaDg5NlYxOTNIMHptNzY4IDY0TDQ0OCA1MjEgMTI4IDI1N2g2NDB6TTY0IDMyMWwyNTIuMDMgMTkxLjYyNUw2NCA3MDVWMzIxem02NCA0NDhsMjU0LTIwNi4yNUw0NDggNjEzbDY1Ljg3NS01MC4xMjVMNzY4IDc2OUgxMjh6bTcwNC02NEw1NzkuNjI1IDUxMi45MzggODMyIDMyMXYzODR6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_ym" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://www.yummly.com/urb/verify?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>&yumtype=button"><?php echo $yummly_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['ampforwp-facebook-like-button']){?>
					<li>
						<amp-facebook-like width=90 height=28
		 					layout="fixed"
		 					data-size="large"
		    				data-layout="button_count"
		    				<?php ampforwp_nofollow_social_links(); ?>
		    				data-href="<?php echo esc_url(get_the_permalink());?>">
						</amp-facebook-like>
					</li>
					<?php } ?>
					</ul>
<?php 
	}
}
?>
<?php 
function design_social_3()
{
	global $redux_builder_amp;
	if ( ampforwp_get_setting('ampforwp-social-share-amp')  ) {
		$amp_permalink = ampforwp_url_controller(get_the_permalink());
	} else{
		$amp_permalink = get_the_permalink();
	}
	$twitter_amp_permalink = $amp_permalink;
	if(false == ampforwp_get_setting('enable-single-twitter-share-link')){
		$twitter_amp_permalink =  wp_get_shortlink();
	}
	?>
	<span class="shr-txt"><?php echo esc_attr(ampforwp_translation($redux_builder_amp['amp-translator-share-text'], 'Share')); ?>
	</span>
	<ul class = "ss-shr-ul">
					<?php if($redux_builder_amp['enable-single-facebook-share']){
						$facebook_icon = '';
						if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                                $facebook_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDg2LjAzNyAxMDA3IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTEyNCAxMDA1VjUzNkgwVjM2N2gxMjRWMjIzQzEyNCAxMTAgMTk3IDUgMzY2IDVjNjggMCAxMTkgNyAxMTkgN2wtNCAxNThzLTUyLTEtMTA4LTFjLTYxIDAtNzEgMjgtNzEgNzV2MTIzaDE4M2wtOCAxNjlIMzAydjQ2OUgxMjMiPjwvcGF0aD48L3N2Zz4=" width="16" height="16" ></amp-img>';    
                                }
                    ?>
					<li>
						<a class="s_fb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo esc_url($amp_permalink); ?>"><?php echo $facebook_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-twitter-share']){
						$twitter_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$twitter_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQwLjAxNzEgNjAxLjA4NjkiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCA1MzAuMTU1YzEwLjQyIDEuMDE1IDIwLjgyNiAxLjU0OCAzMS4yMiAxLjU0OCA2MS4wNSAwIDExNS41MjgtMTguNzMgMTYzLjM4Ny01Ni4xNy0yOC40MjQtLjM1Mi01My45MzMtOS4wNC03Ni40NzctMjYuMDQzLTIyLjU3LTE2Ljk5LTM3Ljk4NC0zOC42NzUtNDYuMzIzLTY1LjA1NiA2LjkzMyAxLjQxOCAxNS4xMDIgMi4wOTUgMjQuNDU2IDIuMDk1IDEyLjE1IDAgMjMuNzY3LTEuNTc1IDM0Ljg2Mi00LjY4NC0zMC41MTctNS44NjctNTUuNzY2LTIwLjg5Mi03NS43MS00NC45OTctMTkuOTU0LTI0LjEzMi0yOS45Mi01MS45Ny0yOS45Mi04My41Mjh2LTEuNTc0YzE4LjM5NiAxMC40MiAzOC4zMTIgMTUuODA2IDU5LjgyOCAxNi4xMy0xOC4wMTctMTEuNzk4LTMyLjM0LTI3LjMwNC00Mi45MTUtNDYuNTctMTAuNTc2LTE5LjI0LTE1Ljg3LTQwLjEzLTE1Ljg3LTYyLjY3NCAwLTIzLjU5OCA2LjA4Ny00NS42MDggMTguMjEtNjYuMDk2IDMyLjYgNDAuNTg2IDcyLjQyIDcyLjkzOCAxMTkuNDMyIDk3LjA1NiA0NyAyNC4wOSA5Ny4zNyAzNy41MyAxNTEuMTU4IDQwLjMyNi0yLjQzMi0xMS40NDctMy42NTUtMjEuNTE2LTMuNjU1LTMwLjE4IDAtMzYuMDg1IDEyLjg0LTY2Ljk1NCAzOC41MDUtOTIuNjIgMjUuNjgtMjUuNjY2IDU2LjcwNC0zOC41MDUgOTMuMTUzLTM4LjUwNSAzNy43OSAwIDY5LjcwMiAxMy44OCA5NS43MyA0MS42NCAzMC4xNjgtNi4yNTcgNTcuOTI4LTE3LjAxNSA4My4yNTYtMzIuMjYtOS43MTggMzEuNTU4LTI4LjgxNSA1NS44NDUtNTcuMjM4IDcyLjg0NyAyNS4zMjgtMy4xMSA1MC4zMDQtMTAuMDU2IDc0LjkzLTIwLjgxNC0xNi42NTIgMjYuMDE3LTM4LjMzNyA0OC43NDItNjUuMDU3IDY4LjE1MnYxNy4xOTdjMCAzNC45OTItNS4xMjQgNzAuMTI4LTE1LjM0OCAxMDUuMzU1LTEwLjIxMiAzNS4yMTQtMjUuODUgNjguODUzLTQ2LjgzIDEwMC45NzItMjAuOTk2IDMyLjA2NS00Ni4wNSA2MC42Mi03NS4xOSA4NS41Ny0yOS4xMjYgMjQuOTc2LTY0LjA4IDQ0Ljg1My0xMDQuODUgNTkuNTktNDAuNzU0IDE0Ljc1My04NC41NTMgMjIuMDktMTMxLjM5NyAyMi4wOUMxMjguODYyIDU4OC45NCA2MS43NCA1NjkuMzUgMCA1MzAuMTU0eiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16"></amp-img>';}
					?>
					<li>
						<a class="s_tw" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo esc_url($twitter_amp_permalink); ?>&text=<?php echo esc_attr(ampforwp_sanitize_twitter_title(get_the_title())); ?>"><?php echo $twitter_icon; ?>
						</a>
					</li>
					<?php } ?>
					<?php if(isset($redux_builder_amp['enable-single-gplus-share']) && $redux_builder_amp['enable-single-gplus-share']){?>
					<li>
						<a class="s_gp" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://plus.google.com/share?url=<?php the_permalink(); ?>"></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-email-share']){
						$email_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$email_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgODk2IDEwMjYiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCAxOTN2NjQwaDg5NlYxOTNIMHptNzY4IDY0TDQ0OCA1MjEgMTI4IDI1N2g2NDB6TTY0IDMyMWwyNTIuMDMgMTkxLjYyNUw2NCA3MDVWMzIxem02NCA0NDhsMjU0LTIwNi4yNUw0NDggNjEzbDY1Ljg3NS01MC4xMjVMNzY4IDc2OUgxMjh6bTcwNC02NEw1NzkuNjI1IDUxMi45MzggODMyIDMyMXYzODR6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';
								}
					?>
					<li>
						<a class="s_em" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="mailto:?subject=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>&body=<?php echo esc_url($amp_permalink); ?>"><?php echo $email_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-pinterest-share']){
						$thumb_id = $image = '';
						if (has_post_thumbnail( ) ){
								$thumb_id = get_post_thumbnail_id(get_the_ID());
							$image = wp_get_attachment_image_src( $thumb_id, 'full' );
							$image = $image[0]; 
							}
							$pinterest_icon = '';
	 							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$pinterest_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNOC42MTcgMTMuMjI3QzguMDkgMTUuOTggNy40NSAxOC42MiA1LjU1IDIwYy0uNTg3LTQuMTYyLjg2LTcuMjg3IDEuNTMzLTEwLjYwNS0xLjE0Ny0xLjkzLjEzOC01LjgxMiAyLjU1NS00Ljg1NSAyLjk3NSAxLjE3Ni0yLjU3NiA3LjE3MiAxLjE1IDcuOTIyIDMuODkuNzggNS40OC02Ljc1IDMuMDY2LTkuMkMxMC4zNy0uMjc0IDMuNzA4IDMuMTggNC41MjggOC4yNDZjLjIgMS4yMzggMS40NzggMS42MTMuNTEgMy4zMjItMi4yMy0uNDk0LTIuODk2LTIuMjU0LTIuODEtNC42LjEzOC0zLjg0IDMuNDUtNi41MjcgNi43Ny02LjkgNC4yMDItLjQ3IDguMTQ1IDEuNTQzIDguNjkgNS40OTQuNjEzIDQuNDYyLTEuODk2IDkuMjk0LTYuMzkgOC45NDYtMS4yMTctLjA5NS0xLjcyNy0uNy0yLjY4LTEuMjh6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';
	 							}
					?>
					<li>
						<a class="s_pt" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://pinterest.com/pin/create/bookmarklet/?media=<?php echo $image; ?>&url=<?php echo esc_url($amp_permalink); ?>&description=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $pinterest_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-linkedin-share']){
						$linkedin_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$linkedin_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTA0NiAxMDA3IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTIzNyAxMDA1VjMzMEgxM3Y2NzVoMjI0ek0xMjUgMjM4Yzc4IDAgMTI3LTUyIDEyNy0xMTdDMjUxIDU1IDIwMyA0IDEyNyA0IDUwIDQgMCA1NCAwIDEyMWMwIDY1IDQ5IDExNyAxMjQgMTE3aDF6bTIzNiA3NjdoMjI0VjYyOGMwLTIwIDEtNDAgNy01NSAxNi00MCA1My04MiAxMTUtODIgODEgMCAxMTQgNjIgMTE0IDE1M3YzNjFoMjI0VjYxOGMwLTIwNy0xMTEtMzA0LTI1OC0zMDQtMTIxIDAtMTc0IDY4LTIwNCAxMTRoMXYtOThIMzYwYzMgNjMgMCA2NzUgMCA2NzV6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';
								}
					?>
					<li>
						<a class="s_lk" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.linkedin.com/shareArticle?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $linkedin_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-whatsapp-share']){
						$whatsapp_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$whatsapp_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgOTAgOTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDkwIDkwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggaWQ9IldoYXRzQXBwIiBkPSJNOTAsNDMuODQxYzAsMjQuMjEzLTE5Ljc3OSw0My44NDEtNDQuMTgyLDQzLjg0MWMtNy43NDcsMC0xNS4wMjUtMS45OC0yMS4zNTctNS40NTVMMCw5MGw3Ljk3NS0yMy41MjIgICBjLTQuMDIzLTYuNjA2LTYuMzQtMTQuMzU0LTYuMzQtMjIuNjM3QzEuNjM1LDE5LjYyOCwyMS40MTYsMCw0NS44MTgsMEM3MC4yMjMsMCw5MCwxOS42MjgsOTAsNDMuODQxeiBNNDUuODE4LDYuOTgyICAgYy0yMC40ODQsMC0zNy4xNDYsMTYuNTM1LTM3LjE0NiwzNi44NTljMCw4LjA2NSwyLjYyOSwxNS41MzQsNy4wNzYsMjEuNjFMMTEuMTA3LDc5LjE0bDE0LjI3NS00LjUzNyAgIGM1Ljg2NSwzLjg1MSwxMi44OTEsNi4wOTcsMjAuNDM3LDYuMDk3YzIwLjQ4MSwwLDM3LjE0Ni0xNi41MzMsMzcuMTQ2LTM2Ljg1N1M2Ni4zMDEsNi45ODIsNDUuODE4LDYuOTgyeiBNNjguMTI5LDUzLjkzOCAgIGMtMC4yNzMtMC40NDctMC45OTQtMC43MTctMi4wNzYtMS4yNTRjLTEuMDg0LTAuNTM3LTYuNDEtMy4xMzgtNy40LTMuNDk1Yy0wLjk5My0wLjM1OC0xLjcxNy0wLjUzOC0yLjQzOCwwLjUzNyAgIGMtMC43MjEsMS4wNzYtMi43OTcsMy40OTUtMy40Myw0LjIxMmMtMC42MzIsMC43MTktMS4yNjMsMC44MDktMi4zNDcsMC4yNzFjLTEuMDgyLTAuNTM3LTQuNTcxLTEuNjczLTguNzA4LTUuMzMzICAgYy0zLjIxOS0yLjg0OC01LjM5My02LjM2NC02LjAyNS03LjQ0MWMtMC42MzEtMS4wNzUtMC4wNjYtMS42NTYsMC40NzUtMi4xOTFjMC40ODgtMC40ODIsMS4wODQtMS4yNTUsMS42MjUtMS44ODIgICBjMC41NDMtMC42MjgsMC43MjMtMS4wNzUsMS4wODItMS43OTNjMC4zNjMtMC43MTcsMC4xODItMS4zNDQtMC4wOS0xLjg4M2MtMC4yNy0wLjUzNy0yLjQzOC01LjgyNS0zLjM0LTcuOTc3ICAgYy0wLjkwMi0yLjE1LTEuODAzLTEuNzkyLTIuNDM2LTEuNzkyYy0wLjYzMSwwLTEuMzU0LTAuMDktMi4wNzYtMC4wOWMtMC43MjIsMC0xLjg5NiwwLjI2OS0yLjg4OSwxLjM0NCAgIGMtMC45OTIsMS4wNzYtMy43ODksMy42NzYtMy43ODksOC45NjNjMCw1LjI4OCwzLjg3OSwxMC4zOTcsNC40MjIsMTEuMTEzYzAuNTQxLDAuNzE2LDcuNDksMTEuOTIsMTguNSwxNi4yMjMgICBDNTguMiw2NS43NzEsNTguMiw2NC4zMzYsNjAuMTg2LDY0LjE1NmMxLjk4NC0wLjE3OSw2LjQwNi0yLjU5OSw3LjMxMi01LjEwN0M2OC4zOTgsNTYuNTM3LDY4LjM5OCw1NC4zODYsNjguMTI5LDUzLjkzOHoiIGZpbGw9IiNGRkZGRkYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" width="16" height="16" ></amp-img>';

								}
				    ?>
					<li>
						<a class="s_wp" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="whatsapp://send?text=<?php echo esc_url($amp_permalink); ?>" data-action="share/whatsapp/share"><?php echo $whatsapp_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-vk-share']){
						$vk_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$vk_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMDQuMzYgMzA0LjM2IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAzMDQuMzYgMzA0LjM2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGcgaWQ9IlhNTElEXzFfIj4KCTxwYXRoIGlkPSJYTUxJRF84MDdfIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7IiBkPSJNMjYxLjk0NSwxNzUuNTc2YzEwLjA5Niw5Ljg1NywyMC43NTIsMTkuMTMxLDI5LjgwNywyOS45ODIgICBjNCw0LjgyMiw3Ljc4Nyw5Ljc5OCwxMC42ODQsMTUuMzk0YzQuMTA1LDcuOTU1LDAuMzg3LDE2LjcwOS02Ljc0NiwxNy4xODRsLTQ0LjM0LTAuMDJjLTExLjQzNiwwLjk0OS0yMC41NTktMy42NTUtMjguMjMtMTEuNDc0ICAgYy02LjEzOS02LjI1My0xMS44MjQtMTIuOTA4LTE3LjcyNy0xOS4zNzJjLTIuNDItMi42NDItNC45NTMtNS4xMjgtNy45NzktNy4wOTNjLTYuMDUzLTMuOTI5LTExLjMwNy0yLjcyNi0xNC43NjYsMy41ODcgICBjLTMuNTIzLDYuNDIxLTQuMzIyLDEzLjUzMS00LjY2OCwyMC42ODdjLTAuNDc1LDEwLjQ0MS0zLjYzMSwxMy4xODYtMTQuMTE5LDEzLjY2NGMtMjIuNDE0LDEuMDU3LTQzLjY4Ni0yLjMzNC02My40NDctMTMuNjQxICAgYy0xNy40MjItOS45NjgtMzAuOTMyLTI0LjA0LTQyLjY5MS0zOS45NzFDMzQuODI4LDE1My40ODIsMTcuMjk1LDExOS4zOTUsMS41MzcsODQuMzUzQy0yLjAxLDc2LjQ1OCwwLjU4NCw3Mi4yMiw5LjI5NSw3Mi4wNyAgIGMxNC40NjUtMC4yODEsMjguOTI4LTAuMjYxLDQzLjQxLTAuMDJjNS44NzksMC4wODYsOS43NzEsMy40NTgsMTIuMDQxLDkuMDEyYzcuODI2LDE5LjI0MywxNy40MDIsMzcuNTUxLDI5LjQyMiw1NC41MjEgICBjMy4yMDEsNC41MTgsNi40NjUsOS4wMzYsMTEuMTEzLDEyLjIxNmM1LjE0MiwzLjUyMSw5LjA1NywyLjM1NCwxMS40NzYtMy4zNzRjMS41MzUtMy42MzIsMi4yMDctNy41NDQsMi41NTMtMTEuNDM0ICAgYzEuMTQ2LTEzLjM4MywxLjI5Ny0yNi43NDMtMC43MTMtNDAuMDc5Yy0xLjIzNC04LjMyMy01LjkyMi0xMy43MTEtMTQuMjI3LTE1LjI4NmMtNC4yMzgtMC44MDMtMy42MDctMi4zOC0xLjU1NS00Ljc5OSAgIGMzLjU2NC00LjE3Miw2LjkxNi02Ljc2OSwxMy41OTgtNi43NjloNTAuMTExYzcuODg5LDEuNTU3LDkuNjQxLDUuMTAxLDEwLjcyMSwxMy4wMzlsMC4wNDMsNTUuNjYzICAgYy0wLjA4NiwzLjA3MywxLjUzNSwxMi4xOTIsNy4wNywxNC4yMjZjNC40MywxLjQ0OCw3LjM1LTIuMDk2LDEwLjAwOC00LjkwNWMxMS45OTgtMTIuNzM0LDIwLjU2MS0yNy43ODMsMjguMjExLTQzLjM2NiAgIGMzLjM5NS02Ljg1Miw2LjMxNC0xMy45NjgsOS4xNDMtMjEuMDc4YzIuMDk2LTUuMjc2LDUuMzg1LTcuODcyLDExLjMyOC03Ljc1N2w0OC4yMjksMC4wNDNjMS40MywwLDIuODc3LDAuMDIxLDQuMjYyLDAuMjU4ICAgYzguMTI3LDEuMzg1LDEwLjM1NCw0Ljg4MSw3Ljg0NCwxMi44MTdjLTMuOTU1LDEyLjQ1MS0xMS42NSwyMi44MjctMTkuMTc0LDMzLjI1MWMtOC4wNDMsMTEuMTI5LTE2LjY0NSwyMS44NzctMjQuNjIxLDMzLjA3MiAgIEMyNTIuMjYsMTYxLjU0NCwyNTIuODQyLDE2Ni42OTcsMjYxLjk0NSwxNzUuNTc2TDI2MS45NDUsMTc1LjU3NnogTTI2MS45NDUsMTc1LjU3NiIgZmlsbD0iI0ZGRkZGRiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_vk" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://vk.com/share.php?url=<?php echo esc_url($amp_permalink); ?>"><?php echo $vk_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-odnoklassniki-share']){
						$odnoklassniki_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$odnoklassniki_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjY0cHgiIGhlaWdodD0iNjRweCIgdmlld0JveD0iMCAwIDk1LjQ4MSA5NS40ODEiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk1LjQ4MSA5NS40ODE7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8Zz4KCQk8cGF0aCBkPSJNNDMuMDQxLDY3LjI1NGMtNy40MDItMC43NzItMTQuMDc2LTIuNTk1LTE5Ljc5LTcuMDY0Yy0wLjcwOS0wLjU1Ni0xLjQ0MS0xLjA5Mi0yLjA4OC0xLjcxMyAgICBjLTIuNTAxLTIuNDAyLTIuNzUzLTUuMTUzLTAuNzc0LTcuOTg4YzEuNjkzLTIuNDI2LDQuNTM1LTMuMDc1LDcuNDg5LTEuNjgyYzAuNTcyLDAuMjcsMS4xMTcsMC42MDcsMS42MzksMC45NjkgICAgYzEwLjY0OSw3LjMxNywyNS4yNzgsNy41MTksMzUuOTY3LDAuMzI5YzEuMDU5LTAuODEyLDIuMTkxLTEuNDc0LDMuNTAzLTEuODEyYzIuNTUxLTAuNjU1LDQuOTMsMC4yODIsNi4yOTksMi41MTQgICAgYzEuNTY0LDIuNTQ5LDEuNTQ0LDUuMDM3LTAuMzgzLDcuMDE2Yy0yLjk1NiwzLjAzNC02LjUxMSw1LjIyOS0xMC40NjEsNi43NjFjLTMuNzM1LDEuNDQ4LTcuODI2LDIuMTc3LTExLjg3NSwyLjY2MSAgICBjMC42MTEsMC42NjUsMC44OTksMC45OTIsMS4yODEsMS4zNzZjNS40OTgsNS41MjQsMTEuMDIsMTEuMDI1LDE2LjUsMTYuNTY2YzEuODY3LDEuODg4LDIuMjU3LDQuMjI5LDEuMjI5LDYuNDI1ICAgIGMtMS4xMjQsMi40LTMuNjQsMy45NzktNi4xMDcsMy44MWMtMS41NjMtMC4xMDgtMi43ODItMC44ODYtMy44NjUtMS45NzdjLTQuMTQ5LTQuMTc1LTguMzc2LTguMjczLTEyLjQ0MS0xMi41MjcgICAgYy0xLjE4My0xLjIzNy0xLjc1Mi0xLjAwMy0yLjc5NiwwLjA3MWMtNC4xNzQsNC4yOTctOC40MTYsOC41MjgtMTIuNjgzLDEyLjczNWMtMS45MTYsMS44ODktNC4xOTYsMi4yMjktNi40MTgsMS4xNSAgICBjLTIuMzYyLTEuMTQ1LTMuODY1LTMuNTU2LTMuNzQ5LTUuOTc5YzAuMDgtMS42MzksMC44ODYtMi44OTEsMi4wMTEtNC4wMTRjNS40NDEtNS40MzMsMTAuODY3LTEwLjg4LDE2LjI5NS0xNi4zMjIgICAgQzQyLjE4Myw2OC4xOTcsNDIuNTE4LDY3LjgxMyw0My4wNDEsNjcuMjU0eiIgZmlsbD0iI0ZGRkZGRiIvPgoJCTxwYXRoIGQ9Ik00Ny41NSw0OC4zMjljLTEzLjIwNS0wLjA0NS0yNC4wMzMtMTAuOTkyLTIzLjk1Ni0yNC4yMThDMjMuNjcsMTAuNzM5LDM0LjUwNS0wLjAzNyw0Ny44NCwwICAgIGMxMy4zNjIsMC4wMzYsMjQuMDg3LDEwLjk2NywyNC4wMiwyNC40NzhDNzEuNzkyLDM3LjY3Nyw2MC44ODksNDguMzc1LDQ3LjU1LDQ4LjMyOXogTTU5LjU1MSwyNC4xNDMgICAgYy0wLjAyMy02LjU2Ny01LjI1My0xMS43OTUtMTEuODA3LTExLjgwMWMtNi42MDktMC4wMDctMTEuODg2LDUuMzE2LTExLjgzNSwxMS45NDNjMC4wNDksNi41NDIsNS4zMjQsMTEuNzMzLDExLjg5NiwxMS43MDkgICAgQzU0LjM1NywzNS45NzEsNTkuNTczLDMwLjcwOSw1OS41NTEsMjQuMTQzeiIgZmlsbD0iI0ZGRkZGRiIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_od" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://ok.ru/dk?st.cmd=addShare&st._surl=<?php echo esc_url($amp_permalink); ?>"><?php echo $odnoklassniki_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-reddit-share']){
						$reddit_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$reddit_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDQ5IDUxMiIgZmlsbD0iI2ZmZmZmZiIgPjxwYXRoIGQ9Ik00NDkgMjUxYzAgMjAtMTEgMzctMjcgNDUgMSA1IDEgOSAxIDE0IDAgNzYtODkgMTM4LTE5OSAxMzhTMjYgMzg3IDI2IDMxMWMwLTUgMC0xMCAxLTE1LTE2LTgtMjctMjUtMjctNDUgMC0yOCAyMy01MCA1MC01MCAxMyAwIDI0IDUgMzMgMTMgMzMtMjMgNzktMzkgMTI5LTQxaDJsMzEtMTAzIDkwIDE4YzgtMTQgMjItMjQgMzktMjRoMWMyNSAwIDQ0IDIwIDQ0IDQ1cy0xOSA0NS00NCA0NWgtMWMtMjMgMC00Mi0xNy00NC00MGwtNjctMTQtMjIgNzRjNDkgMyA5MyAxNyAxMjUgNDAgOS04IDIxLTEzIDM0LTEzIDI3IDAgNDkgMjIgNDkgNTB6TTM0IDI3MWM1LTE1IDE1LTI5IDI5LTQxLTQtMy05LTUtMTUtNS0xNCAwLTI1IDExLTI1IDI1IDAgOSA0IDE3IDExIDIxem0zMjQtMTYyYzAgOSA3IDE3IDE2IDE3czE3LTggMTctMTctOC0xNy0xNy0xNy0xNiA4LTE2IDE3ek0xMjcgMjg4YzAgMTggMTQgMzIgMzIgMzJzMzItMTQgMzItMzItMTQtMzEtMzItMzEtMzIgMTMtMzIgMzF6bTk3IDExMmM0OCAwIDc3LTI5IDc4LTMwbC0xMy0xMnMtMjUgMjQtNjUgMjRjLTQxIDAtNjQtMjQtNjQtMjRsLTEzIDEyYzEgMSAyOSAzMCA3NyAzMHptNjctODBjMTggMCAzMi0xNCAzMi0zMnMtMTQtMzEtMzItMzEtMzIgMTMtMzIgMzEgMTQgMzIgMzIgMzJ6bTEyNC00OGM3LTUgMTEtMTMgMTEtMjIgMC0xNC0xMS0yNS0yNS0yNS02IDAtMTEgMi0xNSA1IDE0IDEyIDI0IDI3IDI5IDQyeiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16" ></amp-img>';
							}?>
					<li>
						<a title="reddit share" class="s_rd" target="_blank" <?php ampforwp_nofollow_social_links(); ?> href="https://reddit.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $reddit_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-tumblr-share']){
						$tumblr_icon ='';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$tumblr_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMzYuMDAyIDI4djE0LjYzNmMwIDMuNzE0LS4wNDggNS44NTMuMzQ2IDYuOTA2LjM5IDEuMDQ3IDEuMzcgMi4xMzQgMi40MzcgMi43NjMgMS40MTguODUgMy4wMzQgMS4yNzMgNC44NTcgMS4yNzMgMy4yNCAwIDUuMTU1LS40MjggOC4zNi0yLjUzNHY5LjYyYy0yLjczMiAxLjI4Ni01LjExOCAyLjAzOC03LjMzNCAyLjU2LTIuMjIuNTE0LTQuNjE2Ljc3NC03LjE5Ljc3NC0yLjkyOCAwLTQuNjU1LS4zNjgtNi45MDItMS4xMDMtMi4yNDctLjc0Mi00LjE2Ni0xLjgtNS43NS0zLjE2LTEuNTkyLTEuMzctMi42OS0yLjgyNC0zLjMwNC00LjM2M3MtLjkyLTMuNzc2LS45Mi02LjcwM1YyNi4yMjRoLTguNTl2LTkuMDYzYzIuNTE0LS44MTUgNS4zMjQtMS45ODcgNy4xMTItMy41MSAxLjc5Ny0xLjUyNyAzLjIzNS0zLjM1NiA0LjMyLTUuNDk2QzI0LjUzIDYuMDIyIDI1LjI3NiAzLjMgMjUuNjgzIDBoMTAuMzJ2MTZINTJ2MTJIMzYuMDA0eiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16" ></amp-img>';}?>
					<li>
						<a class="s_tb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo esc_url($amp_permalink); ?>"><?php echo $tumblr_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-telegram-share']){
						$telegram_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$telegram_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ1NS43MzEgNDU1LjczMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDU1LjczMSA0NTUuNzMxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGc+Cgk8cmVjdCB4PSIwIiB5PSIwIiBzdHlsZT0iZmlsbDojNjFBOERFOyIgd2lkdGg9IjQ1NS43MzEiIGhlaWdodD0iNDU1LjczMSIvPgoJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0zNTguODQ0LDEwMC42TDU0LjA5MSwyMTkuMzU5Yy05Ljg3MSwzLjg0Ny05LjI3MywxOC4wMTIsMC44ODgsMjEuMDEybDc3LjQ0MSwyMi44NjhsMjguOTAxLDkxLjcwNiAgIGMzLjAxOSw5LjU3OSwxNS4xNTgsMTIuNDgzLDIyLjE4NSw1LjMwOGw0MC4wMzktNDAuODgybDc4LjU2LDU3LjY2NWM5LjYxNCw3LjA1NywyMy4zMDYsMS44MTQsMjUuNzQ3LTkuODU5bDUyLjAzMS0yNDguNzYgICBDMzgyLjQzMSwxMDYuMjMyLDM3MC40NDMsOTYuMDgsMzU4Ljg0NCwxMDAuNnogTTMyMC42MzYsMTU1LjgwNkwxNzkuMDgsMjgwLjk4NGMtMS40MTEsMS4yNDgtMi4zMDksMi45NzUtMi41MTksNC44NDcgICBsLTUuNDUsNDguNDQ4Yy0wLjE3OCwxLjU4LTIuMzg5LDEuNzg5LTIuODYxLDAuMjcxbC0yMi40MjMtNzIuMjUzYy0xLjAyNy0zLjMwOCwwLjMxMi02Ljg5MiwzLjI1NS04LjcxN2wxNjcuMTYzLTEwMy42NzYgICBDMzIwLjA4OSwxNDcuNTE4LDMyNC4wMjUsMTUyLjgxLDMyMC42MzYsMTU1LjgwNnoiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" width="16" height="16" ></amp-img>';
							}?>
					<li>
						<a class="s_tg" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="https://telegram.me/share/url?url=<?php echo esc_url($amp_permalink); ?>&text=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $telegram_icon; ?></a>
					</li>
					<?php } ?>
					<?php if(isset($redux_builder_amp['enable-single-digg-share']) && $redux_builder_amp['enable-single-digg-share']){?>
					<li>
						<a class="s_dg" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://digg.com/submit?url=<?php the_permalink(); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-stumbleupon-share']){
						$stumbleupon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$stumbleupon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjcwLjIyMzMgNjAxLjA4NjkiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCA0MzcuMjQ3di05Mi42NzJoMTE0LjY4OHY5MS42NjRjMCA5LjU2NyAzLjQwOCAxNy44MjMgMTAuMjQgMjQuODNzMTUuMTg0IDEwLjQ5NyAyNS4wODggMTAuNDk3IDE4LjMzNi0zLjQyNCAyNS4zNDQtMTAuMjRjNy4wMDgtNi44NDggMTAuNDk2LTE1LjIgMTAuNDk2LTI1LjA4OFYyMTkuNjQ2YzAtMzkuOTM1IDE0Ljc1Mi03My45ODQgNDQuMjg4LTEwMi4xNDQgMjkuNTM2LTI4LjE2IDY0LjYwOC00Mi4yNCAxMDUuMjE2LTQyLjI0IDQwLjYwOCAwIDc1LjY4IDE0LjE2IDEwNS4yMTYgNDIuNDk2IDI5LjUyIDI4LjMzNSA0NC4zMDUgNjIuNjQgNDQuMzA1IDEwMi45MXY0Ny4xMDRsLTY4LjYyMyAyMC40OC00NS41Ny0yMS41MDN2LTQwLjk2YzAtOS45MDMtMy40MDctMTguMjU2LTEwLjI1NS0yNS4wODgtNi44MTYtNi44MzItMTUuMTgzLTEwLjI0LTI1LjA3Mi0xMC4yNC05LjkwMyAwLTE4LjMzNiAzLjQwOC0yNS4zNDQgMTAuMjRzLTEwLjQ5NiAxNS4xODUtMTAuNDk2IDI1LjA5djIxMy41MDNjMCA0MC45NzYtMTQuNjcyIDc1Ljg3Mi00NC4wMzIgMTA0LjcyLTI5LjM0NCAyOC44NDgtNjQuNTEyIDQzLjI0OC0xMDUuNDcyIDQzLjI0OC00MS4zMSAwLTc2LjY0LTE0LjU5Mi0xMDUuOTg0LTQzLjc3NkMxNC42ODggNTE0LjMwMy4wMDIgNDc4Ljg4LjAwMiA0MzcuMjQ3em0zNzAuNjg4IDEuNTM2di05My42OTVsNDUuNTY4IDIxLjUyIDY4LjYyNC0yMC40OTd2OTQuMjI2YzAgOS45MDMgMy40MDggMTguMzM2IDEwLjIyNCAyNS4zNDQgNi44NDcgNy4wMDcgMTUuMiAxMC40OTYgMjUuMDg3IDEwLjQ5NiA5LjkwNiAwIDE4LjI3NC0zLjUwNCAyNS4wOS0xMC40OTYgNi44MTYtNi45OTMgMTAuMjU1LTE1LjQ0IDEwLjI1NS0yNS4zNDR2LTk1Ljc0NGgxMTQuNjg4djkyLjY3MmMwIDQxLjI5NS0xNC41OSA3Ni42NC00My43NzYgMTA1Ljk4My0yOS4xODQgMjkuMzYtNjQuNDMyIDQ0LjAzMi0xMDUuNzI4IDQ0LjAzMnMtNzYuNjI1LTE0LjQ5Ny0xMDUuOTg1LTQzLjUyYy0yOS4zNi0yOS4wNC00NC4wNDgtNjQuMDE3LTQ0LjA0OC0xMDQuOTc4eiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_su" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://www.stumbleupon.com/submit?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>"><?php echo $stumbleupon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-wechat-share']){
						$wechat_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$wechat_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjA0OCAxODk2LjA4MzMiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNNTgwIDQ2MXEwLTQxLTI1LTY2dC02Ni0yNXEtNDMgMC03NiAyNS41VDM4MCA0NjFxMCAzOSAzMyA2NC41dDc2IDI1LjVxNDEgMCA2Ni0yNC41dDI1LTY1LjV6bTc0MyA1MDdxMC0yOC0yNS41LTUwdC02NS41LTIycS0yNyAwLTQ5LjUgMjIuNVQxMTYwIDk2OHEwIDI4IDIyLjUgNTAuNXQ0OS41IDIyLjVxNDAgMCA2NS41LTIydDI1LjUtNTF6bS0yMzYtNTA3cTAtNDEtMjQuNS02NlQ5OTcgMzcwcS00MyAwLTc2IDI1LjVUODg4IDQ2MXEwIDM5IDMzIDY0LjV0NzYgMjUuNXE0MSAwIDY1LjUtMjQuNVQxMDg3IDQ2MXptNjM1IDUwN3EwLTI4LTI2LTUwdC02NS0yMnEtMjcgMC00OS41IDIyLjVUMTU1OSA5NjhxMCAyOCAyMi41IDUwLjV0NDkuNSAyMi41cTM5IDAgNjUtMjJ0MjYtNTF6bS0yNjYtMzk3cS0zMS00LTcwLTQtMTY5IDAtMzExIDc3VDg1MS41IDg1Mi41IDc3MCAxMTQwcTAgNzggMjMgMTUyLTM1IDMtNjggMy0yNiAwLTUwLTEuNXQtNTUtNi41LTQ0LjUtNy01NC41LTEwLjUtNTAtMTAuNWwtMjUzIDEyNyA3Mi0yMThRMCA5NjUgMCA2NzhxMC0xNjkgOTcuNS0zMTF0MjY0LTIyMy41VDcyNSA2MnExNzYgMCAzMzIuNSA2NnQyNjIgMTgyLjVUMTQ1NiA1NzF6bTU5MiA1NjFxMCAxMTctNjguNSAyMjMuNVQxNzk0IDE1NDlsNTUgMTgxLTE5OS0xMDlxLTE1MCAzNy0yMTggMzctMTY5IDAtMzExLTcwLjVUODk3LjUgMTM5NiA4MTYgMTEzMnQ4MS41LTI2NFQxMTIxIDY3Ni41dDMxMS03MC41cTE2MSAwIDMwMyA3MC41dDIyNy41IDE5MlQyMDQ4IDExMzJ6Ij48L3BhdGg+PC9zdmc+" width="16" height="16" ></amp-img>';}?>
					<li>
						<a class="s_wc" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/wechat/offer?url=<?php echo esc_url($amp_permalink); ?>"><?php echo $wechat_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['enable-single-viber-share']){
						$viber_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$viber_icon = '<amp-img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTWKJeJ0Rufx3hz-UW5lXwOoh6yQJFCSWwjvahBYWlUasWr3XNk&usqp=CAU" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_vb" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="viber://forward?text=<?php the_permalink(); ?>"><?php echo $viber_icon; ?></a>
					</li>
					<?php } ?>
					<?php if ( isset($redux_builder_amp['enable-single-yummly-share']) && $redux_builder_amp['enable-single-yummly-share']){
						$yummly_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$yummly_icon = '<amp-img src="https://image.shutterstock.com/image-vector/email-icon-260nw-438858262.jpg" width="16" height="16" ></amp-img>';
								}?>
					<li>
						<a class="s_ym" <?php ampforwp_nofollow_social_links(); ?> target="_blank" href="http://www.yummly.com/urb/verify?url=<?php echo esc_url($amp_permalink); ?>&title=<?php echo esc_attr(htmlspecialchars(get_the_title())); ?>&yumtype=button"><?php echo $yummly_icon; ?></a>
					</li>
					<?php } ?>
					<?php if($redux_builder_amp['ampforwp-facebook-like-button']){?>
					<li>
						<amp-facebook-like width=90 height=28
		 					layout="fixed"
		 					data-size="large"
		    				data-layout="button_count"
		    				data-href="<?php echo esc_url(get_the_permalink());?>">
						</amp-facebook-like>
					</li>
					<?php } ?>
					</ul>
 
<?php }?>
<?php 
 ?>