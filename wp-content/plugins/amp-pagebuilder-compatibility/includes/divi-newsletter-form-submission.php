<?php
        header("access-control-allow-credentials:true");
        header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
        header("Access-Control-Allow-Origin:".$_SERVER['HTTP_ORIGIN']);
        $siteUrl = parse_url(  get_site_url() );
        header("AMP-Access-Control-Allow-Source-Origin:".$siteUrl['scheme'] . '://' . $siteUrl['host']);
        header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
        header("Content-Type:application/json;charset=utf-8");
        


		//et_core_security_check( '', 'et_frontend_nonce' );
	
		$providers = ET_Core_API_Email_Providers::instance();
		$utils     = ET_Core_Data_Utils::instance();
	
		$provider_slug = sanitize_text_field( $utils->array_get( $_POST, 'et_provider' ) );
		$account_name  = sanitize_text_field( $utils->array_get( $_POST, 'et_account' ) );
		$custom_fields = $utils->array_get( $_POST, 'et_custom_fields', array() );
	
		if ( ! $provider = $providers->get( $provider_slug, $account_name, 'builder' ) ) {
            $message = esc_html__( 'Configuration Error: Invalid data.', 'et_builder' );
            $result  = array( 'error' => $message );
            header("HTTP/1.1 501 ERROR" );
            die( json_encode( $result ) );
		}
	
		$args = array(
			'list_id'       => sanitize_text_field( $utils->array_get( $_POST, 'et_list_id' ) ),
			'email'         => sanitize_text_field( $utils->array_get( $_POST, 'et_email' ) ),
			'name'          => sanitize_text_field( $utils->array_get( $_POST, 'et_firstname' ) ),
			'last_name'     => sanitize_text_field( $utils->array_get( $_POST, 'et_lastname' ) ),
			'ip_address'    => sanitize_text_field( $utils->array_get( $_POST, 'et_ip_address' ) ),
			'custom_fields' => $utils->sanitize_text_fields( $custom_fields ),
		);
	
		if ( ! is_email( $args['email'] ) ) {
            $message = esc_html__( 'Please input a valid email address.', 'et_builder' );
            $result  = array( 'error' => $message );
            header("HTTP/1.1 501 ERROR" );
            die( json_encode( $result ) );
		}
	
		if ( '' === (string) $args['list_id'] ) {
            $message = esc_html__( 'Configuration x xfc Error: No list has been selected for this form.', 'et_builder' );
            $result  = array( 'error' => $message );
            header("HTTP/1.1 501 ERROR" );
            die( json_encode( $result ) );
		}
	
		et_builder_email_maybe_migrate_accounts();
	
		$result = $provider->subscribe( $args );
	
		if ( 'success' === $result ) {
            $result  = array( 'success' => true, 'message'=> 'form submition successfull' );
            header("HTTP/1.1 200 OK" );
            die( json_encode( $result ) );
		} else {
			$message = esc_html__( 'Subscription Error: ', 'et_builder' );
            $result  = array( 'error' => $message . $result );
            header("HTTP/1.1 501 ERROR" );
            die( json_encode( $result ) );
		}
	
		//die( wp_json_encode( $result ) );
        
        header("HTTP/1.1 200 SUCCESS" );
        die( json_encode( $result ) );