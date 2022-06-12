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
            <div class="name">
                <div class="avatar"><?php echo $initials; ?></div>
                <div class="user-name">
                    <p><?php echo $author_name; ?></p>
                    <span class="subtitle"><?php esc_html_e( 'Advogado(a)', 'jusdocs' ); ?></span>
                </div>
            </div>

            <div class="meta">
                <div class="stars-container"><?php echo $stars; ?></div>
                <div class="last-updated">
                    <?php
						// translators: petition date
						printf( __( '<i>Atualizada em:</i> <span class="date">%s</span>', 'jusdocs' ), $date );
					?>
                </div>
            </div>
        </div>
        <div class="title">
            <h4><a href="https://jusdocs.com/peticoes/<?php echo $slug; ?>"
                    target="_blank"><?php echo $title; ?></a></h4>
        </div>
        <div class="info">
            <div class="left">
                <span class="pet-title">Área do direito:</span>
                <p class="pet-type"><?php echo $area; ?></p>
            </div>
            <div class="right">
                <span class="pet-title">Tipo de petição:</span>
                <p class="pet-type"><?php echo $petition_type; ?></p>
            </div>
        </div>
    </div>
</div>