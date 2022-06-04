<?php
/**
 * PetitionCard template file
 *
 * @see Theme\Components\PetitionCard
 *
 * @package jusdocs
 */

?>

<div class="_petition-card <?php echo $class; ?>">
    <div class="card">
        <div class="header">
            <div class="avatar"><?php echo $initials; ?></div>
            <div>
                <p><?php echo $author_name; ?></p>
                <span><?php esc_html_e( 'Advogado(a)', 'jusdocs' ); ?></span>
            </div>
        </div>
        <div class="meta">
            <div class="stars-container"><?php echo $stars; ?></div>
            <div class="last-updated"><i>
                    <?php
					// translators: petition date
					printf( __( 'Atualizada em: %s', 'jusdocs' ), $date );
					?>
                </i></div>
        </div>
        <div class="title">
            <h4><a href="https://jusdocs.com/peticoes/<?php echo $slug; ?>"
                    target="_blank"><?php echo $title; ?></a></h4>
        </div>
        <div class="info">
            <div>
                <span>Área do direito:</span>
                <p><?php echo $area; ?></p>
            </div>
            <div>
                <span>Tipo de petição:</span>
                <p><?php echo $petition_type; ?></p>
            </div>
        </div>
    </div>
</div>