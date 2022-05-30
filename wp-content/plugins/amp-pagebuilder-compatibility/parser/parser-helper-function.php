<?php

function amp_pbc_get_proper_transient_name($transient){
	global $post;
	if( function_exists('ampforwp_is_home') && ampforwp_is_home()){
		$transient = "home";
	}elseif(function_exists('ampforwp_is_blog') && ampforwp_is_blog()){
		$transient = "blog";
	}elseif( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page()){
		$transient = "post-".ampforwp_get_frontpage_id();
	}elseif(!empty($post) && is_object($post)){
		$transient = "post-".$post->ID;
	}
	return $transient;
}
function amp_pbc_set_file_transient( $transient, $value, $expiration = 0 ) {

	$transient = amp_pbc_get_proper_transient_name($transient);
	$expiration = (int) $expiration;

	$value = apply_filters( "pre_set_transient_{$transient}", $value, $expiration, $transient );

	
	$expiration = apply_filters( "expiration_of_transient_{$transient}", $expiration, $value, $transient );

	$result = '';
	if ( wp_using_ext_object_cache() ) {
		$result = wp_cache_set( $transient, $value, 'transient', $expiration );
	} else {
		$transient_timeout = '_transient_timeout_' . $transient;
		$transient_option = '_transient_' . $transient;

		/***
		Creating a file
		**/
		/*if($value){
			$upload_dir = wp_upload_dir(); 
			$user_dirname = $upload_dir['basedir'] . '/' . 'pb_compatibility';
			if(!file_exists($user_dirname)) wp_mkdir_p($user_dirname);
			$content = $value;
			$new_file = $user_dirname."/".$transient_option.".css";
			$ifp = @fopen( $new_file, 'w+' );
			if ( ! $ifp ) {
	          return ( array( 'error' => sprintf( __( 'Could not write file %s' ), $new_file ) ));
	        }
	        $result = @fwrite( $ifp, json_encode($value) );
		    fclose( $ifp );
		    //set_transient($transient_option, true, 30 * 24 * 60);
		}*/

	}
	return $result;
}


function amp_pbc_get_file_transient( $transient ) {

	$transient = amp_pbc_get_proper_transient_name($transient);
	$pre = apply_filters( "pre_transient_{$transient}", false, $transient );
	if ( false !== $pre )
		return $pre;

	if ( wp_using_ext_object_cache() ) {
		$value = wp_cache_get( $transient, 'transient' );
	} else {
		$transient_option = '_transient_' . $transient;
		/*if ( ! wp_installing() ) {
			// If option is not in alloptions, it is not autoloaded and thus has a timeout
			$alloptions = wp_load_alloptions();
			if ( !isset( $alloptions[$transient_option] ) ) {
				$transient_timeout = '_transient_timeout_' . $transient;
				$timeout = get_option( $transient_timeout );
				if ( false !== $timeout && $timeout < time() ) {
					delete_option( $transient_option  );
					delete_option( $transient_timeout );
					$value = false;
				}
			}
		}*/

		if ( ! isset( $value ) ){
			$value = '';
			$upload_dir = wp_upload_dir(); 
			$user_dirname = $upload_dir['basedir'] . '/' . 'pb_compatibility';

			do_action('amp_pbc_before_tree_shaking', $user_dirname);

			if(!file_exists($user_dirname)) wp_mkdir_p($user_dirname);
			
			$new_file = $user_dirname."/".$transient_option.".css";

			if(file_exists($new_file) && filesize($new_file)>0){
				$ifp = @fopen( $new_file, 'r' );
				$value = fread($ifp, filesize($new_file)); 
				fclose($ifp);
			}
			//$value = get_option( $transient_option );
		}
	}

	
	return apply_filters( "transient_{$transient}", json_decode($value, true), $transient );
}

add_filter('varnish_http_purge_events', 'amp_pbc_css_cache_support',99);
function amp_pbc_css_cache_support($hook_list){

    // if not array then return back
    if ( ! array($hook_list)) {
        return $hook_list;
    }

    array_push($hook_list, 'amp_pbc_before_tree_shaking');

    return $hook_list;

}