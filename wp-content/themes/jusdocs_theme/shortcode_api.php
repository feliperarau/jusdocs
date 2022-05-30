<?php

function get_stars( $rating ) {
    ob_start();

    echo '<div class="stars d-flex">';

    for ($i=0; $i < 5; $i++) :
        $glow = $i < $rating ? 'glow' : '';

        echo <<<HTML
            <span class="star $glow"><i class="fas fa-star"></i></span>
        HTML;
    endfor;
    
    echo '</div>';

    $stars = ob_get_clean();

    return $stars;
}
function petitions_shortcode() {
    $request = wp_remote_get( 'https://api.jusdocs.com/api/v2/petitions?limit=3&page=1&orderBy=updated_at' );

    if ( is_wp_error( $request ) ) {
        return false; // Bail early
    }

    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );

    //var_dump( $data );


    if ( empty( $data ) ) {
        return;
    }
    
    ob_start();

    echo '<div class="petitions-container">';

    foreach ( $data->items as $item ) :
        $dt = new DateTime( $item->updated_at, wp_timezone() );

        $author = $item->author ?? [];
        $author_name = $author->name ?? '';
        $name_arr = explode( ' ', $author_name );

        $f_name = substr($name_arr[0] ?? '', 0, 1);
        $s_name = substr($name_arr[1] ?? '', 0, 1);
        $initials = $f_name . $s_name;
        $stars = get_stars(2);
        $date = $dt ? $dt->format('d/m/Y') : '';

        //var_dump($date);

        echo <<<HTML
            <div class="card">
                <div class="header">
                    <div class="avatar">$initials</div>
                    <div>
                        <p>$author_name</p>
                        <span>Advogado(a)</span>
                    </div>
                </div>
                <div class="meta">
                    <div class="stars-container">$stars</div>
                    <div class="last-updated"><i>Atualizada em: $date</i></div>
                </div>
                <div class="title">
                    <h4><a href="https://jusdocs.com/peticoes/{$item->slug}"
                            target="_blank">{$item->title}</a></h4>
                </div>
                <div class="info">
                    <div>
                        <span>Área do direito:</span>
                        <p>{$item->area}</p>
                    </div>
                    <div>
                        <span>Tipo de petição:</span>
                        <p>{$item->type->name}</p>
                    </div>
                </div>
            </div>
        HTML;
    endforeach;

    echo '</div>';

    $petitions = ob_get_clean();

    return $petitions;
}

add_shortcode( 'petitions', 'petitions_shortcode' );