<?php
/*
     Plugin Name: JusDocs API
     Plugin URI: https://jusdocs.com.br
     Description: Exibe Petições recentes
    Author: JusDocs
    Version: 1.0
    Author URI: https://jusdocs.com.br
*/
// If this file is access directly, abort!!!
defined( 'ABSPATH' ) or die( 'Unauthorized Access' );

add_shortcode('external_data', 'callback_function_name');

function callback_function_name(){
   $url = 'https://api.jusdocs.com/api/v2/petitions?limit=4&page=1&orderBy=updated_at';

   $arguments = array(
       'method' => 'GET'
  );

  $response = wp_remote_get( $url, $arguments );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
	}

    $results = json_decode( wp_remote_retrieve_body($response));

    var_dump($results);

    $html = '';
    $html .='<h2>Title</h2>';
    $html .='<table>';
    $html .='<tr>';
    $html .='<td>id</td>';
    $html .='<td>name</td>';
    $html .='<td>username</td>';
    $html .='<td>email</td>';
    $html .='<td>address</td>';
    $html .='</tr>';

    foreach($results as $results){
        $html .='<tr>';
        $html .='<td>' . $result->id . '</td>';
        $html .='<td>' . $result->name . '</td>';
        $html .='<td>' . $result->username . '</td>';
        $html .='<td>' . $result->email . '</td>';
        $html .='<td>' . $result->address->street . '</td>';
        $html .='</tr>';
    }
$html .='</table>';
    return $html;