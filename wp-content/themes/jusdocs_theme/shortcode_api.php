<?php

function callback_function_name($atts) {
$request = wp_remote_get( 'https://api.jusdocs.com/api/v2/petitions?limit=4&page=1&orderBy=updated_at' );

if( is_wp_error( $request ) ) {
	return false; // Bail early
}

$body = wp_remote_retrieve_body( $request );
$data = json_decode( $body );
$dt = new DateTime($item->updated_at);


if( ! empty( $data ) ) {

	echo '<div class="container" style="display:flex; flex-direction:row; ">';
	foreach( $data->items as $item ) {
		echo '<div class="card">';
        echo '<p>AUTOR: ' . $item->author->name . '</p>';
        echo '<img src="' . $item->author->picture .'" /> <br>';
        echo '<p>TITULO: <a href="https://jusdocs.com/peticoes/' . $item->slug . '">' . $item->title . '</a></p>';
        echo '<p>DATA: ' . json_encode($dt) . '</p>';
        echo '<p>TAGS: ' . $item->tags->name . '</p>';
        echo '<p> QTD DOWNLOADS: ' . $item->downloads . '</p>';
        echo '<p>TIPO: ' . $item->type->name . '</p>';
        echo '<p>AREA: ' . $item->area . '</p>';

		echo '</div>';
	}
	echo '</div>';
}
}

add_shortcode('petitions', 'callback_function_name');
?>