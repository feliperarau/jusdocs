<?php

function callback_function_name($atts) {
    $default = array(
        'method' => 'GET',
        'infos' => '#',
    );

   $url = 'https://api.jusdocs.com/api/v2/petitions?limit=4&page=1&orderBy=updated_at';


  $response = wp_remote_get( $url, $default );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
	}

    $results = json_decode( wp_remote_retrieve_body($response));

    var_dump($results);

    $a = shortcode_atts($default, $atts);
    return '<iframe width="768" height="432" src="https://miro.com/app/live-embed/' . $a['infos'] . '/?embedAutoplay=true" frameBorder="0" scrolling="no" allowFullScreen></iframe>';
;
}

add_shortcode('petitions', 'callback_function_name');
?>