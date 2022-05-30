<?php
add_action('ampforwp_advance_footer_options', 'ampLayout_option_add_footer_type');

function ampLayout_option_add_footer_type(){
	global $redux_builder_amp;
 if ( isset($redux_builder_amp['footer-type']) && '2' == $redux_builder_amp['footer-type'] ) { ?>
<footer class="ftr">
	<div class="cntr">
		<div class="f-t-2">
			<div class="f-lg">
				 <?php amp_logo(); ?>
			</div>
			<?php if ( has_nav_menu( 'amp-footer-menu' ) ) { ?>
				<div class="f-mnu">
					<nav itemscope="" itemtype="https://schema.org/SiteNavigationElement">
		              <?php
		              $menu = wp_nav_menu( array(
		                  'theme_location' => 'amp-footer-menu',
		                  'link_before'     => '<span itemprop="name">',
		                  'link_after'     => '</span>',
		                  'echo' => false
		              ) );
		              $menu = apply_filters('ampforwp_menu_content', $menu);
		              $sanitizer_obj = new AMPFORWP_Content( $menu, array(), apply_filters( 'ampforwp_content_sanitizers', array( 'AMP_Img_Sanitizer' => array(), 'AMP_Style_Sanitizer' => array(), ) ) );
		              $sanitized_menu =  $sanitizer_obj->get_amp_content();
		              echo $sanitized_menu; ?>
		           </nav>
				</div>
			<?php } ?>
		</div>
		<div class="rr">
			<?php amp_non_amp_link(); ?>
			<?php amp_back_to_top_link(); ?>
			<?php do_action('amp_footer_link'); ?>
		</div>
	</div>
</footer>
<?php } 
 if ( isset($redux_builder_amp['footer-type']) && '3' == $redux_builder_amp['footer-type'] ) { ?>
<footer class="ftr">
	<div class="cntr">
		<div class="f-logo">
			 <?php amp_logo(); ?>
		</div>
		<?php if ( has_nav_menu( 'amp-footer-menu' ) ) { ?>
			<div class="f-menu">
				<nav itemscope="" itemtype="https://schema.org/SiteNavigationElement">
	              <?php
	              $menu = wp_nav_menu( array(
	                  'theme_location' => 'amp-footer-menu',
	                  'link_before'     => '<span itemprop="name">',
	                  'link_after'     => '</span>',
	                  'echo' => false
	              ) );
	              $menu = apply_filters('ampforwp_menu_content', $menu);
	              $sanitizer_obj = new AMPFORWP_Content( $menu, array(), apply_filters( 'ampforwp_content_sanitizers', array( 'AMP_Img_Sanitizer' => array(), 'AMP_Style_Sanitizer' => array(), ) ) );
	              $sanitized_menu =  $sanitizer_obj->get_amp_content();
	              echo $sanitized_menu; ?>
	           </nav>
			</div>
		<?php } ?>
		<div class="socl-ico">
			<span><?php echo esc_html(ampforwp_translation($redux_builder_amp['amp-translator-footer-connect-text'], 'CONNECT' )); ?></span>
				<ul class="socl-shr">
					<?php if($redux_builder_amp['enbl-fb']){
						$facebook_icon = '';
                                if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                                $facebook_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDg2LjAzNyAxMDA3IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTEyNCAxMDA1VjUzNkgwVjM2N2gxMjRWMjIzQzEyNCAxMTAgMTk3IDUgMzY2IDVjNjggMCAxMTkgNyAxMTkgN2wtNCAxNThzLTUyLTEtMTA4LTFjLTYxIDAtNzEgMjgtNzEgNzV2MTIzaDE4M2wtOCAxNjlIMzAydjQ2OUgxMjMiPjwvcGF0aD48L3N2Zz4=" width="20" height="20" ></amp-img>';    
                                }?>
                    <li>
                        <a class="s_fb" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-fb-prfl-url']); ?>"><?php echo $facebook_icon; ?></a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-tw']){
                    	$twitter_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$twitter_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQwLjAxNzEgNjAxLjA4NjkiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCA1MzAuMTU1YzEwLjQyIDEuMDE1IDIwLjgyNiAxLjU0OCAzMS4yMiAxLjU0OCA2MS4wNSAwIDExNS41MjgtMTguNzMgMTYzLjM4Ny01Ni4xNy0yOC40MjQtLjM1Mi01My45MzMtOS4wNC03Ni40NzctMjYuMDQzLTIyLjU3LTE2Ljk5LTM3Ljk4NC0zOC42NzUtNDYuMzIzLTY1LjA1NiA2LjkzMyAxLjQxOCAxNS4xMDIgMi4wOTUgMjQuNDU2IDIuMDk1IDEyLjE1IDAgMjMuNzY3LTEuNTc1IDM0Ljg2Mi00LjY4NC0zMC41MTctNS44NjctNTUuNzY2LTIwLjg5Mi03NS43MS00NC45OTctMTkuOTU0LTI0LjEzMi0yOS45Mi01MS45Ny0yOS45Mi04My41Mjh2LTEuNTc0YzE4LjM5NiAxMC40MiAzOC4zMTIgMTUuODA2IDU5LjgyOCAxNi4xMy0xOC4wMTctMTEuNzk4LTMyLjM0LTI3LjMwNC00Mi45MTUtNDYuNTctMTAuNTc2LTE5LjI0LTE1Ljg3LTQwLjEzLTE1Ljg3LTYyLjY3NCAwLTIzLjU5OCA2LjA4Ny00NS42MDggMTguMjEtNjYuMDk2IDMyLjYgNDAuNTg2IDcyLjQyIDcyLjkzOCAxMTkuNDMyIDk3LjA1NiA0NyAyNC4wOSA5Ny4zNyAzNy41MyAxNTEuMTU4IDQwLjMyNi0yLjQzMi0xMS40NDctMy42NTUtMjEuNTE2LTMuNjU1LTMwLjE4IDAtMzYuMDg1IDEyLjg0LTY2Ljk1NCAzOC41MDUtOTIuNjIgMjUuNjgtMjUuNjY2IDU2LjcwNC0zOC41MDUgOTMuMTUzLTM4LjUwNSAzNy43OSAwIDY5LjcwMiAxMy44OCA5NS43MyA0MS42NCAzMC4xNjgtNi4yNTcgNTcuOTI4LTE3LjAxNSA4My4yNTYtMzIuMjYtOS43MTggMzEuNTU4LTI4LjgxNSA1NS44NDUtNTcuMjM4IDcyLjg0NyAyNS4zMjgtMy4xMSA1MC4zMDQtMTAuMDU2IDc0LjkzLTIwLjgxNC0xNi42NTIgMjYuMDE3LTM4LjMzNyA0OC43NDItNjUuMDU3IDY4LjE1MnYxNy4xOTdjMCAzNC45OTItNS4xMjQgNzAuMTI4LTE1LjM0OCAxMDUuMzU1LTEwLjIxMiAzNS4yMTQtMjUuODUgNjguODUzLTQ2LjgzIDEwMC45NzItMjAuOTk2IDMyLjA2NS00Ni4wNSA2MC42Mi03NS4xOSA4NS41Ny0yOS4xMjYgMjQuOTc2LTY0LjA4IDQ0Ljg1My0xMDQuODUgNTkuNTktNDAuNzU0IDE0Ljc1My04NC41NTMgMjIuMDktMTMxLjM5NyAyMi4wOUMxMjguODYyIDU4OC45NCA2MS43NCA1NjkuMzUgMCA1MzAuMTU0eiI+PC9wYXRoPjwvc3ZnPg==" width="20" height="20"></amp-img>';}?>
                    <li>
                        <a class="s_tw" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-tw-prfl-url']); ?>"><?php echo $twitter_icon; ?>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-gol']){
                    	$gol_icon = '';
                                if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                                $gol_icon = '<amp-img src="data:image/jpeg;base64,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" width="20" height="20" ></amp-img>';    
                                }?>
                    <li>
                        <a class="s_gp" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-gol-prfl-url']); ?>"><?php echo $gol_icon;?></a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-lk']){
                    	$linkedin_icon = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$linkedin_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTA0NiAxMDA3IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTIzNyAxMDA1VjMzMEgxM3Y2NzVoMjI0ek0xMjUgMjM4Yzc4IDAgMTI3LTUyIDEyNy0xMTdDMjUxIDU1IDIwMyA0IDEyNyA0IDUwIDQgMCA1NCAwIDEyMWMwIDY1IDQ5IDExNyAxMjQgMTE3aDF6bTIzNiA3NjdoMjI0VjYyOGMwLTIwIDEtNDAgNy01NSAxNi00MCA1My04MiAxMTUtODIgODEgMCAxMTQgNjIgMTE0IDE1M3YzNjFoMjI0VjYxOGMwLTIwNy0xMTEtMzA0LTI1OC0zMDQtMTIxIDAtMTc0IDY4LTIwNCAxMTRoMXYtOThIMzYwYzMgNjMgMCA2NzUgMCA2NzV6Ij48L3BhdGg+PC9zdmc+" width="20" height="20" ></amp-img>';
								}?>
                    <li>
                        <a class="s_lk" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-lk-prfl-url']); ?>"><?php echo $linkedin_icon; ?></a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-pt']){
                    	$pinterest_icon = '';
	 							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$pinterest_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNOC42MTcgMTMuMjI3QzguMDkgMTUuOTggNy40NSAxOC42MiA1LjU1IDIwYy0uNTg3LTQuMTYyLjg2LTcuMjg3IDEuNTMzLTEwLjYwNS0xLjE0Ny0xLjkzLjEzOC01LjgxMiAyLjU1NS00Ljg1NSAyLjk3NSAxLjE3Ni0yLjU3NiA3LjE3MiAxLjE1IDcuOTIyIDMuODkuNzggNS40OC02Ljc1IDMuMDY2LTkuMkMxMC4zNy0uMjc0IDMuNzA4IDMuMTggNC41MjggOC4yNDZjLjIgMS4yMzggMS40NzggMS42MTMuNTEgMy4zMjItMi4yMy0uNDk0LTIuODk2LTIuMjU0LTIuODEtNC42LjEzOC0zLjg0IDMuNDUtNi41MjcgNi43Ny02LjkgNC4yMDItLjQ3IDguMTQ1IDEuNTQzIDguNjkgNS40OTQuNjEzIDQuNDYyLTEuODk2IDkuMjk0LTYuMzkgOC45NDYtMS4yMTctLjA5NS0xLjcyNy0uNy0yLjY4LTEuMjh6Ij48L3BhdGg+PC9zdmc+" width="20" height="20" ></amp-img>';
	 							}?>
                    <li>
                        <a class="s_pt" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-pt-prfl-url']); ?>"><?php echo $pinterest_icon; ?></a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-yt']){
                    	$youtube_icon = '';
                    	if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$youtube_icon = '<amp-img src="https://w0.pngwave.com/png/683/879/symbol-font-youtube-icon-png-clip-art.png" width="20" height="20" ></amp-img>';
	 							}?>
                    <li>
                        <a class="s_yt" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-yt-prfl-url']); ?>"><?php echo $youtube_icon; ?></a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-inst']){
                    	$insta_icon = '';
	 							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$insta_icon = '<amp-img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/599px-Instagram_icon.png" width="20" height="20" ></amp-img>';
	 							}?>
                    <li>
                        <a class="s_inst" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-inst-prfl-url']); ?>"><?php echo $insta_icon; ?></a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-vk']){
                    	$vk_icon = '';
                    	if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$vk_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMDQuMzYgMzA0LjM2IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAzMDQuMzYgMzA0LjM2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGcgaWQ9IlhNTElEXzFfIj4KCTxwYXRoIGlkPSJYTUxJRF84MDdfIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7IiBkPSJNMjYxLjk0NSwxNzUuNTc2YzEwLjA5Niw5Ljg1NywyMC43NTIsMTkuMTMxLDI5LjgwNywyOS45ODIgICBjNCw0LjgyMiw3Ljc4Nyw5Ljc5OCwxMC42ODQsMTUuMzk0YzQuMTA1LDcuOTU1LDAuMzg3LDE2LjcwOS02Ljc0NiwxNy4xODRsLTQ0LjM0LTAuMDJjLTExLjQzNiwwLjk0OS0yMC41NTktMy42NTUtMjguMjMtMTEuNDc0ICAgYy02LjEzOS02LjI1My0xMS44MjQtMTIuOTA4LTE3LjcyNy0xOS4zNzJjLTIuNDItMi42NDItNC45NTMtNS4xMjgtNy45NzktNy4wOTNjLTYuMDUzLTMuOTI5LTExLjMwNy0yLjcyNi0xNC43NjYsMy41ODcgICBjLTMuNTIzLDYuNDIxLTQuMzIyLDEzLjUzMS00LjY2OCwyMC42ODdjLTAuNDc1LDEwLjQ0MS0zLjYzMSwxMy4xODYtMTQuMTE5LDEzLjY2NGMtMjIuNDE0LDEuMDU3LTQzLjY4Ni0yLjMzNC02My40NDctMTMuNjQxICAgYy0xNy40MjItOS45NjgtMzAuOTMyLTI0LjA0LTQyLjY5MS0zOS45NzFDMzQuODI4LDE1My40ODIsMTcuMjk1LDExOS4zOTUsMS41MzcsODQuMzUzQy0yLjAxLDc2LjQ1OCwwLjU4NCw3Mi4yMiw5LjI5NSw3Mi4wNyAgIGMxNC40NjUtMC4yODEsMjguOTI4LTAuMjYxLDQzLjQxLTAuMDJjNS44NzksMC4wODYsOS43NzEsMy40NTgsMTIuMDQxLDkuMDEyYzcuODI2LDE5LjI0MywxNy40MDIsMzcuNTUxLDI5LjQyMiw1NC41MjEgICBjMy4yMDEsNC41MTgsNi40NjUsOS4wMzYsMTEuMTEzLDEyLjIxNmM1LjE0MiwzLjUyMSw5LjA1NywyLjM1NCwxMS40NzYtMy4zNzRjMS41MzUtMy42MzIsMi4yMDctNy41NDQsMi41NTMtMTEuNDM0ICAgYzEuMTQ2LTEzLjM4MywxLjI5Ny0yNi43NDMtMC43MTMtNDAuMDc5Yy0xLjIzNC04LjMyMy01LjkyMi0xMy43MTEtMTQuMjI3LTE1LjI4NmMtNC4yMzgtMC44MDMtMy42MDctMi4zOC0xLjU1NS00Ljc5OSAgIGMzLjU2NC00LjE3Miw2LjkxNi02Ljc2OSwxMy41OTgtNi43NjloNTAuMTExYzcuODg5LDEuNTU3LDkuNjQxLDUuMTAxLDEwLjcyMSwxMy4wMzlsMC4wNDMsNTUuNjYzICAgYy0wLjA4NiwzLjA3MywxLjUzNSwxMi4xOTIsNy4wNywxNC4yMjZjNC40MywxLjQ0OCw3LjM1LTIuMDk2LDEwLjAwOC00LjkwNWMxMS45OTgtMTIuNzM0LDIwLjU2MS0yNy43ODMsMjguMjExLTQzLjM2NiAgIGMzLjM5NS02Ljg1Miw2LjMxNC0xMy45NjgsOS4xNDMtMjEuMDc4YzIuMDk2LTUuMjc2LDUuMzg1LTcuODcyLDExLjMyOC03Ljc1N2w0OC4yMjksMC4wNDNjMS40MywwLDIuODc3LDAuMDIxLDQuMjYyLDAuMjU4ICAgYzguMTI3LDEuMzg1LDEwLjM1NCw0Ljg4MSw3Ljg0NCwxMi44MTdjLTMuOTU1LDEyLjQ1MS0xMS42NSwyMi44MjctMTkuMTc0LDMzLjI1MWMtOC4wNDMsMTEuMTI5LTE2LjY0NSwyMS44NzctMjQuNjIxLDMzLjA3MiAgIEMyNTIuMjYsMTYxLjU0NCwyNTIuODQyLDE2Ni42OTcsMjYxLjk0NSwxNzUuNTc2TDI2MS45NDUsMTc1LjU3NnogTTI2MS45NDUsMTc1LjU3NiIgZmlsbD0iI0ZGRkZGRiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" width="20" height="20" ></amp-img>';
								}?>
                    <li>
                        <a class="s_vk" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-vk-prfl-url']); ?>"><?php echo $vk_icon; ?></a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-rd']){
                    	$reddit_icon = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$reddit_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDQ5IDUxMiIgZmlsbD0iI2ZmZmZmZiIgPjxwYXRoIGQ9Ik00NDkgMjUxYzAgMjAtMTEgMzctMjcgNDUgMSA1IDEgOSAxIDE0IDAgNzYtODkgMTM4LTE5OSAxMzhTMjYgMzg3IDI2IDMxMWMwLTUgMC0xMCAxLTE1LTE2LTgtMjctMjUtMjctNDUgMC0yOCAyMy01MCA1MC01MCAxMyAwIDI0IDUgMzMgMTMgMzMtMjMgNzktMzkgMTI5LTQxaDJsMzEtMTAzIDkwIDE4YzgtMTQgMjItMjQgMzktMjRoMWMyNSAwIDQ0IDIwIDQ0IDQ1cy0xOSA0NS00NCA0NWgtMWMtMjMgMC00Mi0xNy00NC00MGwtNjctMTQtMjIgNzRjNDkgMyA5MyAxNyAxMjUgNDAgOS04IDIxLTEzIDM0LTEzIDI3IDAgNDkgMjIgNDkgNTB6TTM0IDI3MWM1LTE1IDE1LTI5IDI5LTQxLTQtMy05LTUtMTUtNS0xNCAwLTI1IDExLTI1IDI1IDAgOSA0IDE3IDExIDIxem0zMjQtMTYyYzAgOSA3IDE3IDE2IDE3czE3LTggMTctMTctOC0xNy0xNy0xNy0xNiA4LTE2IDE3ek0xMjcgMjg4YzAgMTggMTQgMzIgMzIgMzJzMzItMTQgMzItMzItMTQtMzEtMzItMzEtMzIgMTMtMzIgMzF6bTk3IDExMmM0OCAwIDc3LTI5IDc4LTMwbC0xMy0xMnMtMjUgMjQtNjUgMjRjLTQxIDAtNjQtMjQtNjQtMjRsLTEzIDEyYzEgMSAyOSAzMCA3NyAzMHptNjctODBjMTggMCAzMi0xNCAzMi0zMnMtMTQtMzEtMzItMzEtMzIgMTMtMzIgMzEgMTQgMzIgMzIgMzJ6bTEyNC00OGM3LTUgMTEtMTMgMTEtMjIgMC0xNC0xMS0yNS0yNS0yNS02IDAtMTEgMi0xNSA1IDE0IDEyIDI0IDI3IDI5IDQyeiI+PC9wYXRoPjwvc3ZnPg==" width="20" height="20" ></amp-img>';
							}?>
                    <li>
                        <a class="s_rd" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-rd-prfl-url']); ?>"><?php echo $reddit_icon; ?></a>
                    </li>
                    <?php } ?>
                    <?php if($redux_builder_amp['enbl-tbl']){
                    	$tumblr_icon ='';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$tumblr_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMzYuMDAyIDI4djE0LjYzNmMwIDMuNzE0LS4wNDggNS44NTMuMzQ2IDYuOTA2LjM5IDEuMDQ3IDEuMzcgMi4xMzQgMi40MzcgMi43NjMgMS40MTguODUgMy4wMzQgMS4yNzMgNC44NTcgMS4yNzMgMy4yNCAwIDUuMTU1LS40MjggOC4zNi0yLjUzNHY5LjYyYy0yLjczMiAxLjI4Ni01LjExOCAyLjAzOC03LjMzNCAyLjU2LTIuMjIuNTE0LTQuNjE2Ljc3NC03LjE5Ljc3NC0yLjkyOCAwLTQuNjU1LS4zNjgtNi45MDItMS4xMDMtMi4yNDctLjc0Mi00LjE2Ni0xLjgtNS43NS0zLjE2LTEuNTkyLTEuMzctMi42OS0yLjgyNC0zLjMwNC00LjM2M3MtLjkyLTMuNzc2LS45Mi02LjcwM1YyNi4yMjRoLTguNTl2LTkuMDYzYzIuNTE0LS44MTUgNS4zMjQtMS45ODcgNy4xMTItMy41MSAxLjc5Ny0xLjUyNyAzLjIzNS0zLjM1NiA0LjMyLTUuNDk2QzI0LjUzIDYuMDIyIDI1LjI3NiAzLjMgMjUuNjgzIDBoMTAuMzJ2MTZINTJ2MTJIMzYuMDA0eiI+PC9wYXRoPjwvc3ZnPg==" width="20" height="20" ></amp-img>';}?>
                    <li>
                        <a class="s_tbl" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-tbl-prfl-url']); ?>"><?php echo $tumblr_icon; ?></a>
                    </li>
                    <?php } ?>
				</ul>
		</div>
		<div class="rr">
			<?php amp_non_amp_link(); ?>
			<?php amp_back_to_top_link(); ?>
			<?php do_action('amp_footer_link'); ?>
		</div>
	</div>
</footer>
 <?php } 
if ( isset($redux_builder_amp['footer-type']) && '4' == $redux_builder_amp['footer-type'] ) { ?>
<footer class="ftr">
	<div class="foot-1">
		<div class="cntr">
			<div class="f-t-2">
				<div class="em-alr">
					<?php 
						ob_start();
						dynamic_sidebar('swift-footer-widget-area');
						$swift_footer_widget = ob_get_contents();
						ob_end_clean();
						$sanitizer_obj = new AMPFORWP_Content( 
											$swift_footer_widget,
											array(), 
											apply_filters( 'ampforwp_content_sanitizers', 
												array( 'AMP_Img_Sanitizer' => array(), 
													'AMP_Blacklist_Sanitizer' => array(),
													'AMP_Style_Sanitizer' => array(), 
													'AMP_Video_Sanitizer' => array(),
							 						'AMP_Audio_Sanitizer' => array(),
							 						'AMP_Iframe_Sanitizer' => array(
														 'add_placeholder' => true,
													 ),
												) 
											) 
										);
						 $sanitized_footer_widget =  $sanitizer_obj->get_amp_content();
			              echo $sanitized_footer_widget;
					?>
				</div><!-- /. em-alr -->
				<div class="foot-soc">
					<ul class="socl-shr">
						<?php if($redux_builder_amp['enbl-fb']){
							$facebook_icon = '';
							$facebook_txt = '';
                                if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                                $facebook_txt = '<span class="social_text">Facebook</span>';
                                $facebook_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDg2LjAzNyAxMDA3IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTEyNCAxMDA1VjUzNkgwVjM2N2gxMjRWMjIzQzEyNCAxMTAgMTk3IDUgMzY2IDVjNjggMCAxMTkgNyAxMTkgN2wtNCAxNThzLTUyLTEtMTA4LTFjLTYxIDAtNzEgMjgtNzEgNzV2MTIzaDE4M2wtOCAxNjlIMzAydjQ2OUgxMjMiPjwvcGF0aD48L3N2Zz4=" width="16" height="16" ></amp-img>';    
                                }?>
	                    <li>
	                        <a class="s_fb fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-fb-prfl-url']); ?>"><?php echo $facebook_icon; ?><?php echo $facebook_txt ?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-tw']){
	                    	$twitter_icon = '';
	                    	$twi_txt = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$twi_txt = '<span class="social_text">Twitter</span>';
								$twitter_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQwLjAxNzEgNjAxLjA4NjkiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMCA1MzAuMTU1YzEwLjQyIDEuMDE1IDIwLjgyNiAxLjU0OCAzMS4yMiAxLjU0OCA2MS4wNSAwIDExNS41MjgtMTguNzMgMTYzLjM4Ny01Ni4xNy0yOC40MjQtLjM1Mi01My45MzMtOS4wNC03Ni40NzctMjYuMDQzLTIyLjU3LTE2Ljk5LTM3Ljk4NC0zOC42NzUtNDYuMzIzLTY1LjA1NiA2LjkzMyAxLjQxOCAxNS4xMDIgMi4wOTUgMjQuNDU2IDIuMDk1IDEyLjE1IDAgMjMuNzY3LTEuNTc1IDM0Ljg2Mi00LjY4NC0zMC41MTctNS44NjctNTUuNzY2LTIwLjg5Mi03NS43MS00NC45OTctMTkuOTU0LTI0LjEzMi0yOS45Mi01MS45Ny0yOS45Mi04My41Mjh2LTEuNTc0YzE4LjM5NiAxMC40MiAzOC4zMTIgMTUuODA2IDU5LjgyOCAxNi4xMy0xOC4wMTctMTEuNzk4LTMyLjM0LTI3LjMwNC00Mi45MTUtNDYuNTctMTAuNTc2LTE5LjI0LTE1Ljg3LTQwLjEzLTE1Ljg3LTYyLjY3NCAwLTIzLjU5OCA2LjA4Ny00NS42MDggMTguMjEtNjYuMDk2IDMyLjYgNDAuNTg2IDcyLjQyIDcyLjkzOCAxMTkuNDMyIDk3LjA1NiA0NyAyNC4wOSA5Ny4zNyAzNy41MyAxNTEuMTU4IDQwLjMyNi0yLjQzMi0xMS40NDctMy42NTUtMjEuNTE2LTMuNjU1LTMwLjE4IDAtMzYuMDg1IDEyLjg0LTY2Ljk1NCAzOC41MDUtOTIuNjIgMjUuNjgtMjUuNjY2IDU2LjcwNC0zOC41MDUgOTMuMTUzLTM4LjUwNSAzNy43OSAwIDY5LjcwMiAxMy44OCA5NS43MyA0MS42NCAzMC4xNjgtNi4yNTcgNTcuOTI4LTE3LjAxNSA4My4yNTYtMzIuMjYtOS43MTggMzEuNTU4LTI4LjgxNSA1NS44NDUtNTcuMjM4IDcyLjg0NyAyNS4zMjgtMy4xMSA1MC4zMDQtMTAuMDU2IDc0LjkzLTIwLjgxNC0xNi42NTIgMjYuMDE3LTM4LjMzNyA0OC43NDItNjUuMDU3IDY4LjE1MnYxNy4xOTdjMCAzNC45OTItNS4xMjQgNzAuMTI4LTE1LjM0OCAxMDUuMzU1LTEwLjIxMiAzNS4yMTQtMjUuODUgNjguODUzLTQ2LjgzIDEwMC45NzItMjAuOTk2IDMyLjA2NS00Ni4wNSA2MC42Mi03NS4xOSA4NS41Ny0yOS4xMjYgMjQuOTc2LTY0LjA4IDQ0Ljg1My0xMDQuODUgNTkuNTktNDAuNzU0IDE0Ljc1My04NC41NTMgMjIuMDktMTMxLjM5NyAyMi4wOUMxMjguODYyIDU4OC45NCA2MS43NCA1NjkuMzUgMCA1MzAuMTU0eiI+PC9wYXRoPjwvc3ZnPg==" width="16" height="16"></amp-img>';}
	                    	?>
	                    <li>
	                        <a class="s_tw fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-tw-prfl-url']); ?>"><?php echo $twitter_icon; ?><?php echo $twi_txt ?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-gol']){
	                    	$gol_icon = '';
	                    	$google_txt = '';
                                if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                                $google_txt = '<span class="social_text">Google</span>';
                                $gol_icon = '<amp-img src="data:image/jpeg;base64,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" width="20" height="20" ></amp-img>';    
                                }?>
	                    <li>
	                        <a class="s_gp fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-gol-prfl-url']); ?>"><?php echo $gol_icon; ?><?php echo $google_txt ?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-lk']){
	                    	$linkedin_icon = '';
	                    	$linkedin_text = '';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
									$linkedin_text = '<span class="social_text">LinkedIn</span>';
									$linkedin_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTA0NiAxMDA3IiBmaWxsPSIjZmZmZmZmIiA+PHBhdGggZD0iTTIzNyAxMDA1VjMzMEgxM3Y2NzVoMjI0ek0xMjUgMjM4Yzc4IDAgMTI3LTUyIDEyNy0xMTdDMjUxIDU1IDIwMyA0IDEyNyA0IDUwIDQgMCA1NCAwIDEyMWMwIDY1IDQ5IDExNyAxMjQgMTE3aDF6bTIzNiA3NjdoMjI0VjYyOGMwLTIwIDEtNDAgNy01NSAxNi00MCA1My04MiAxMTUtODIgODEgMCAxMTQgNjIgMTE0IDE1M3YzNjFoMjI0VjYxOGMwLTIwNy0xMTEtMzA0LTI1OC0zMDQtMTIxIDAtMTc0IDY4LTIwNCAxMTRoMXYtOThIMzYwYzMgNjMgMCA2NzUgMCA2NzV6Ij48L3BhdGg+PC9zdmc+" width="20" height="20" ></amp-img>';
								}
	                    	?>
	                    <li>
	                        <a class="s_lk fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-lk-prfl-url']); ?>"><?php echo $linkedin_icon; ?><?php echo $linkedin_text; ?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-pt']){
	                    	$pinterest_icon = '';
	                    	$pinterest_text = '';
	 							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$pinterest_text = '<span class="social_text">Pinterest</span>';
	 								$pinterest_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNOC42MTcgMTMuMjI3QzguMDkgMTUuOTggNy40NSAxOC42MiA1LjU1IDIwYy0uNTg3LTQuMTYyLjg2LTcuMjg3IDEuNTMzLTEwLjYwNS0xLjE0Ny0xLjkzLjEzOC01LjgxMiAyLjU1NS00Ljg1NSAyLjk3NSAxLjE3Ni0yLjU3NiA3LjE3MiAxLjE1IDcuOTIyIDMuODkuNzggNS40OC02Ljc1IDMuMDY2LTkuMkMxMC4zNy0uMjc0IDMuNzA4IDMuMTggNC41MjggOC4yNDZjLjIgMS4yMzggMS40NzggMS42MTMuNTEgMy4zMjItMi4yMy0uNDk0LTIuODk2LTIuMjU0LTIuODEtNC42LjEzOC0zLjg0IDMuNDUtNi41MjcgNi43Ny02LjkgNC4yMDItLjQ3IDguMTQ1IDEuNTQzIDguNjkgNS40OTQuNjEzIDQuNDYyLTEuODk2IDkuMjk0LTYuMzkgOC45NDYtMS4yMTctLjA5NS0xLjcyNy0uNy0yLjY4LTEuMjh6Ij48L3BhdGg+PC9zdmc+" width="20" height="20" ></amp-img>';
	 							}
	                    	?>
	                    <li>
	                        <a class="s_pt fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-pt-prfl-url']); ?>"><?php echo  $pinterest_icon?><?php echo $pinterest_text ?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-yt']){
	                    	$youtube_icon = '';
	                    	$youtube_text = '';
                    	if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                    				$youtube_text = '<span class="social_text">Youtube</span>';
	 								$youtube_icon = '<amp-img src="https://w0.pngwave.com/png/683/879/symbol-font-youtube-icon-png-clip-art.png" width="20" height="20" ></amp-img>';
	 							}
	                    	?>
	                    <li>
	                        <a class="s_yt fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-yt-prfl-url']); ?>"><?php echo $youtube_icon ?><?php echo $youtube_text ?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-inst']){
	                    	$insta_icon = '';
	                    	$insta_text = '';
	 							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
	 								$insta_text = '<span class="social_text">Instagram</span>';
	 								$insta_icon = '<amp-img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/599px-Instagram_icon.png" width="20" height="20" ></amp-img>';
	 							}
	 						?>
	                    <li>
	                        <a class="s_inst fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-inst-prfl-url']); ?>"><?php echo $insta_icon; ?><?php echo $insta_text; ?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-vk']){
	                    	$vk_icon = '';
	                    	$vk_text = '';
                    	if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
                    		        $vk_text = '<span class="social_text">VKontakte</span>';
									$vk_icon = '<amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMDQuMzYgMzA0LjM2IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAzMDQuMzYgMzA0LjM2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGcgaWQ9IlhNTElEXzFfIj4KCTxwYXRoIGlkPSJYTUxJRF84MDdfIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7IiBkPSJNMjYxLjk0NSwxNzUuNTc2YzEwLjA5Niw5Ljg1NywyMC43NTIsMTkuMTMxLDI5LjgwNywyOS45ODIgICBjNCw0LjgyMiw3Ljc4Nyw5Ljc5OCwxMC42ODQsMTUuMzk0YzQuMTA1LDcuOTU1LDAuMzg3LDE2LjcwOS02Ljc0NiwxNy4xODRsLTQ0LjM0LTAuMDJjLTExLjQzNiwwLjk0OS0yMC41NTktMy42NTUtMjguMjMtMTEuNDc0ICAgYy02LjEzOS02LjI1My0xMS44MjQtMTIuOTA4LTE3LjcyNy0xOS4zNzJjLTIuNDItMi42NDItNC45NTMtNS4xMjgtNy45NzktNy4wOTNjLTYuMDUzLTMuOTI5LTExLjMwNy0yLjcyNi0xNC43NjYsMy41ODcgICBjLTMuNTIzLDYuNDIxLTQuMzIyLDEzLjUzMS00LjY2OCwyMC42ODdjLTAuNDc1LDEwLjQ0MS0zLjYzMSwxMy4xODYtMTQuMTE5LDEzLjY2NGMtMjIuNDE0LDEuMDU3LTQzLjY4Ni0yLjMzNC02My40NDctMTMuNjQxICAgYy0xNy40MjItOS45NjgtMzAuOTMyLTI0LjA0LTQyLjY5MS0zOS45NzFDMzQuODI4LDE1My40ODIsMTcuMjk1LDExOS4zOTUsMS41MzcsODQuMzUzQy0yLjAxLDc2LjQ1OCwwLjU4NCw3Mi4yMiw5LjI5NSw3Mi4wNyAgIGMxNC40NjUtMC4yODEsMjguOTI4LTAuMjYxLDQzLjQxLTAuMDJjNS44NzksMC4wODYsOS43NzEsMy40NTgsMTIuMDQxLDkuMDEyYzcuODI2LDE5LjI0MywxNy40MDIsMzcuNTUxLDI5LjQyMiw1NC41MjEgICBjMy4yMDEsNC41MTgsNi40NjUsOS4wMzYsMTEuMTEzLDEyLjIxNmM1LjE0MiwzLjUyMSw5LjA1NywyLjM1NCwxMS40NzYtMy4zNzRjMS41MzUtMy42MzIsMi4yMDctNy41NDQsMi41NTMtMTEuNDM0ICAgYzEuMTQ2LTEzLjM4MywxLjI5Ny0yNi43NDMtMC43MTMtNDAuMDc5Yy0xLjIzNC04LjMyMy01LjkyMi0xMy43MTEtMTQuMjI3LTE1LjI4NmMtNC4yMzgtMC44MDMtMy42MDctMi4zOC0xLjU1NS00Ljc5OSAgIGMzLjU2NC00LjE3Miw2LjkxNi02Ljc2OSwxMy41OTgtNi43NjloNTAuMTExYzcuODg5LDEuNTU3LDkuNjQxLDUuMTAxLDEwLjcyMSwxMy4wMzlsMC4wNDMsNTUuNjYzICAgYy0wLjA4NiwzLjA3MywxLjUzNSwxMi4xOTIsNy4wNywxNC4yMjZjNC40MywxLjQ0OCw3LjM1LTIuMDk2LDEwLjAwOC00LjkwNWMxMS45OTgtMTIuNzM0LDIwLjU2MS0yNy43ODMsMjguMjExLTQzLjM2NiAgIGMzLjM5NS02Ljg1Miw2LjMxNC0xMy45NjgsOS4xNDMtMjEuMDc4YzIuMDk2LTUuMjc2LDUuMzg1LTcuODcyLDExLjMyOC03Ljc1N2w0OC4yMjksMC4wNDNjMS40MywwLDIuODc3LDAuMDIxLDQuMjYyLDAuMjU4ICAgYzguMTI3LDEuMzg1LDEwLjM1NCw0Ljg4MSw3Ljg0NCwxMi44MTdjLTMuOTU1LDEyLjQ1MS0xMS42NSwyMi44MjctMTkuMTc0LDMzLjI1MWMtOC4wNDMsMTEuMTI5LTE2LjY0NSwyMS44NzctMjQuNjIxLDMzLjA3MiAgIEMyNTIuMjYsMTYxLjU0NCwyNTIuODQyLDE2Ni42OTcsMjYxLjk0NSwxNzUuNTc2TDI2MS45NDUsMTc1LjU3NnogTTI2MS45NDUsMTc1LjU3NiIgZmlsbD0iI0ZGRkZGRiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" width="20" height="20" ></amp-img>';
								}
								?>
	                    <li>
	                        <a class="s_vk fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-vk-prfl-url']); ?>"><?php echo $vk_icon;?><?php echo $vk_text;?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-rd']){
	                    	$reddit_icon = '';
	                    	$reddit_text = '';
							if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$reddit_text = '<span class="social_text">Reddit</span>';
								$reddit_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDQ5IDUxMiIgZmlsbD0iI2ZmZmZmZiIgPjxwYXRoIGQ9Ik00NDkgMjUxYzAgMjAtMTEgMzctMjcgNDUgMSA1IDEgOSAxIDE0IDAgNzYtODkgMTM4LTE5OSAxMzhTMjYgMzg3IDI2IDMxMWMwLTUgMC0xMCAxLTE1LTE2LTgtMjctMjUtMjctNDUgMC0yOCAyMy01MCA1MC01MCAxMyAwIDI0IDUgMzMgMTMgMzMtMjMgNzktMzkgMTI5LTQxaDJsMzEtMTAzIDkwIDE4YzgtMTQgMjItMjQgMzktMjRoMWMyNSAwIDQ0IDIwIDQ0IDQ1cy0xOSA0NS00NCA0NWgtMWMtMjMgMC00Mi0xNy00NC00MGwtNjctMTQtMjIgNzRjNDkgMyA5MyAxNyAxMjUgNDAgOS04IDIxLTEzIDM0LTEzIDI3IDAgNDkgMjIgNDkgNTB6TTM0IDI3MWM1LTE1IDE1LTI5IDI5LTQxLTQtMy05LTUtMTUtNS0xNCAwLTI1IDExLTI1IDI1IDAgOSA0IDE3IDExIDIxem0zMjQtMTYyYzAgOSA3IDE3IDE2IDE3czE3LTggMTctMTctOC0xNy0xNy0xNy0xNiA4LTE2IDE3ek0xMjcgMjg4YzAgMTggMTQgMzIgMzIgMzJzMzItMTQgMzItMzItMTQtMzEtMzItMzEtMzIgMTMtMzIgMzF6bTk3IDExMmM0OCAwIDc3LTI5IDc4LTMwbC0xMy0xMnMtMjUgMjQtNjUgMjRjLTQxIDAtNjQtMjQtNjQtMjRsLTEzIDEyYzEgMSAyOSAzMCA3NyAzMHptNjctODBjMTggMCAzMi0xNCAzMi0zMnMtMTQtMzEtMzItMzEtMzIgMTMtMzIgMzEgMTQgMzIgMzIgMzJ6bTEyNC00OGM3LTUgMTEtMTMgMTEtMjIgMC0xNC0xMS0yNS0yNS0yNS02IDAtMTEgMi0xNSA1IDE0IDEyIDI0IDI3IDI5IDQyeiI+PC9wYXRoPjwvc3ZnPg==" width="20" height="20" ></amp-img>';
							}
							?>
	                    <li>
	                        <a class="s_rd fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-rd-prfl-url']); ?>"><?php echo $reddit_icon; ?><?php echo $reddit_text; ?></a>
	                    </li>
	                    <?php } ?>
	                    <?php if($redux_builder_amp['enbl-tbl']){
	                    	$tumblr_icon ='';
	                    	$tumblr_text ='';
								if('css-icons' == ampforwp_get_setting('ampforwp_font_icon')){
								$tumblr_text = '<span class="social_text">Tumblr</span>';
								$tumblr_icon = '<amp-img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIGZpbGw9IiNmZmZmZmYiID48cGF0aCBkPSJNMzYuMDAyIDI4djE0LjYzNmMwIDMuNzE0LS4wNDggNS44NTMuMzQ2IDYuOTA2LjM5IDEuMDQ3IDEuMzcgMi4xMzQgMi40MzcgMi43NjMgMS40MTguODUgMy4wMzQgMS4yNzMgNC44NTcgMS4yNzMgMy4yNCAwIDUuMTU1LS40MjggOC4zNi0yLjUzNHY5LjYyYy0yLjczMiAxLjI4Ni01LjExOCAyLjAzOC03LjMzNCAyLjU2LTIuMjIuNTE0LTQuNjE2Ljc3NC03LjE5Ljc3NC0yLjkyOCAwLTQuNjU1LS4zNjgtNi45MDItMS4xMDMtMi4yNDctLjc0Mi00LjE2Ni0xLjgtNS43NS0zLjE2LTEuNTkyLTEuMzctMi42OS0yLjgyNC0zLjMwNC00LjM2M3MtLjkyLTMuNzc2LS45Mi02LjcwM1YyNi4yMjRoLTguNTl2LTkuMDYzYzIuNTE0LS44MTUgNS4zMjQtMS45ODcgNy4xMTItMy41MSAxLjc5Ny0xLjUyNyAzLjIzNS0zLjM1NiA0LjMyLTUuNDk2QzI0LjUzIDYuMDIyIDI1LjI3NiAzLjMgMjUuNjgzIDBoMTAuMzJ2MTZINTJ2MTJIMzYuMDA0eiI+PC9wYXRoPjwvc3ZnPg==" width="20" height="20" ></amp-img>';}
								?>
	                    <li>
	                        <a class="s_tbl fs" target="_blank" href="<?php echo esc_url($redux_builder_amp['enbl-tbl-prfl-url']); ?>"><?php echo $tumblr_icon;?><?php echo $tumblr_text;?></a>
	                    </li>
                    <?php } ?>
					</ul>
				</div><!-- /.foot-soc -->
				<?php if ( has_nav_menu( 'amp-footer-menu' ) ) { ?>
					<div class="f-mnu">
						<nav itemscope="" itemtype="https://schema.org/SiteNavigationElement">
			              <?php
			              $menu = wp_nav_menu( array(
			                  'theme_location' => 'amp-footer-menu',
			                  'link_before'     => '<span itemprop="name">',
			                  'link_after'     => '</span>',
			                  'echo' => false
			              ) );
			              $menu = apply_filters('ampforwp_menu_content', $menu);
			              $sanitizer_obj = new AMPFORWP_Content( $menu, array(), apply_filters( 'ampforwp_content_sanitizers', array( 'AMP_Img_Sanitizer' => array(), 'AMP_Style_Sanitizer' => array(), ) ) );
			              $sanitized_menu =  $sanitizer_obj->get_amp_content();
			              echo $sanitized_menu; ?>
			           </nav>
					</div>
				<?php } ?>
			</div><!-- /.f-t-2 -->
		</div><!-- /.cntr -->
	</div><!-- /.foot-1 -->
	<div class="rr">
		<div class="cntr">
			<?php amp_non_amp_link(); ?>
			<?php amp_back_to_top_link(); ?>
			<?php do_action('amp_footer_link'); ?>
		</div>
	</div><!-- /. rr -->
</footer>
 <?php }
}//function closed ampLayout_option_add_footer_type