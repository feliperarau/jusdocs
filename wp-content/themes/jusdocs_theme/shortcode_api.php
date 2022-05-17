<?php
function callback_function_name() {
    $request = wp_remote_get( 'https://api.jusdocs.com/api/v2/petitions?limit=3&page=1&orderBy=updated_at' );

    if ( is_wp_error( $request ) ) {
        return false; // Bail early
    }

    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );

    if ( ! empty( $data ) ) {

        echo '<div class="petitions-container">';
        foreach ( $data->items as $item ) {

            $dt = new DateTime( $item->updated_at );
            echo '<div class="card">';
                echo '<div class="header">
                        <div class="avatar">JS</div>
                        <div>
                          <p>' . $item->author->name . '</p>
                          <span>Advogado(a)</span>
                        </div>
                      </div>';
                echo '<div class="meta">
                        <p>Stars: ' . $item->avaliations->stars . '</p>
                        <p>data: ' . wp_json_encode( $dt ) . '</p>
                      </div>';
                echo '<div class="title">
                        <h4><a href="https://jusdocs.com/peticoes/' . $item->slug . '" target="_blank">' . $item->title . '</a></h4>
                      </div>';
                echo '<div class="info">
                        <div>
                        <span>Área do direito:</span>
                        <p>' . $item->area . '</p>
                        </div>
                        <div>
                        <span>Tipo de petição:</span>
                        <p>' . $item->type->name . '</p>
                        </div>
                      </div>';

            echo '</div>';
        }
        echo '</div>';
    }
}

add_shortcode( 'petitions', 'callback_function_name' );