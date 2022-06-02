<?php
use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use Elementor\Utils;
use ElementorPro\Modules\Forms\Module;
use ElementorPro\Plugin;
use ElementorPro\Modules\Forms\Classes\Form_Record;
function amp_pc_elementor_form_submit(&$ajaxObj){
	$message = array();
	$ajax_Handler = new Ajax_Handler();
	$return_messages = $ajax_Handler->ajax_send_form();
	return $return_messages;
}
	$ajaxObj = new Ajax_Handler();
	$messages = amp_pc_elementor_form_submit($ajaxObj);
	$json = array();
	$status = "200 SUCCESS";


	$form_id = $_POST['form_id'];
	$form = Module::find_element_recursive( $meta, $form_id );
	$current_form = $form;

	if ( count($messages)==0 ) {
		$json = [
			'message' => Ajax_Handler::get_default_message( Ajax_Handler::SUCCESS, $current_form['settings'] ),
			'data' => $ajaxObj->data,
		] ;
	}else{

		if ( empty( $ajaxObj->messages['error'] ) && ! empty( $ajaxObj->errors ) ) {
			$messages['error'][] = $ajaxObj->get_default_message( Ajax_Handler::INVALID_FORM, $current_form['settings'] );
			$status = "501 Forbidden";
		}

		$error_msg = implode( "\n", $messages['error'] );
		$error_msg .= "\n".implode( "\n", $ajaxObj->messages['error'] );
		if ( current_user_can( 'edit_post', $_POST['post_id'] ) && ! empty( $ajaxObj->messages['admin_error'] ) ) {
			$ajaxObj->add_admin_error_message( __( 'This Message is not visible for site visitors.', 'elementor-pro' ) );
			$error_msg .= '<div class="elementor-forms-admin-errors">' . implode( '<br>', $ajaxObj->messages['admin_error'] ) . '</div>';
		}
		if($error_msg){
			$error_msg .= implode( "\n", $ajaxObj->errors );
			$json = [
				'message' => $error_msg,
				'errors' => $ajaxObj->errors,
				'data' => $ajaxObj->data,
			] ;
			$status = "502 Forbidden";
		}
	}

	echo json_encode($json);









	header("access-control-allow-credentials:true");
    header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
    header("Access-Control-Allow-Origin:".$_SERVER['HTTP_ORIGIN']);
    $siteUrl = parse_url(get_site_url());
    header("AMP-Access-Control-Allow-Source-Origin:".$siteUrl['scheme'] . '://' . $siteUrl['host']);
    header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
    header("Content-Type:application/json;charset=utf-8");
    header("HTTP/1.0 ".$status);
    if(isset($json['data']['redirect_url'])){
     $redirect_url = $json['data']['redirect_url'];
     $secure_url =  str_replace('http:', 'https:', $redirect_url);
     header("AMP-Redirect-To:$secure_url");
     header("Access-Control-Expose-Headers:AMP-Redirect-To");
    }

    die;
    
	/*$ajaxClass = new ElementorPro\Modules\Forms\Classes\Ajax_Handler();
	$ajaxClass->ajax_send_form();*/
