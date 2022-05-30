<div class="massonary-workportfolio">
    <ul class="nav nav-pills mb-lg-5 mb-4 mt-lg-0 mt-0 justify-content-center" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active text-uppercase rounded-0" id="pills-all-tab" data-toggle="pill" aria-controls="pills-all" data-portfoliowork="(showfilterd=='all'? 'nav-link text-uppercase rounded-0 active':'nav-link text-uppercase rounded-0')" aria-selected="true" on="tap:AMP.setState({showfilterd:'all'})">all</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase rounded-0" id="pills-web-tab" data-toggle="pill"   aria-controls="pills-web" data-portfoliowork="(showfilterd=='web'? 'nav-link text-uppercase rounded-0 active':'nav-link text-uppercase rounded-0')" aria-selected="true" on="tap:AMP.setState({showfilterd:'web'})">web</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase rounded-0" id="pills-marketing-tab" data-toggle="pill" aria-controls="pills-marketing" data-portfoliowork="(showfilterd=='marketing'? 'nav-link text-uppercase rounded-0 active':'nav-link text-uppercase rounded-0')" aria-selected="false" on="tap:AMP.setState({showfilterd:'marketing'})">marketing</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase rounded-0" id="pills-branding-tab" data-toggle="pill" aria-controls="pills-branding" data-portfoliowork="(showfilterd=='branding'? 'nav-link text-uppercase rounded-0 active':'nav-link text-uppercase rounded-0')" aria-selected="false" on="tap:AMP.setState({showfilterd:'branding'})">branding</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="all" role="tabpanel" data-portfoliowork="(showfilterd=='all'? 'tab-pane fade show':'tab-pane fade hide')" aria-labelledby="pills-all-tab" style="display: block;">
            <div class="card-columns" style="column-gap: 0rem;">
                <?php
                    $i = 30;
                    $args = array(
                        'post_type' => 'portfolio',
                        'posts_per_page' => -1,
                        'order' => 'ASC',
                    );
                    $query = new WP_Query($args);
                if ($query->have_posts()):
                    while ($query->have_posts()):
                        $query->the_post();
                        $term = get_the_terms($post->ID, 'portfolio_type');
                ?>
                    <div class="card border-0 mb-0">
                        <div class="hovereffect">
                            <img class="card-img rounded-0" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="Card image">
                            <div class="overlay">
                                <div class="content">
                                    <ul>
                                    <?php foreach ($term as $term_name){ ?>
                                        <li><?php echo $term_name->name; ?></li>
                                    <?php } ?>
                                    </ul>
                                    <h3><?php the_title(); ?></h3>
                                    <a href="<?php the_permalink() ?>">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                    endwhile;
                endif;
                wp_reset_postdata(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="web" role="tabpanel" data-portfoliowork="(showfilterd=='web'? 'tab-pane fade show':'tab-pane fade hide')" aria-labelledby="pills-web-tab" style="display: none;">
            <div class="card-columns" style="column-gap: 0rem;">
                <?php
                $i = 30;
                $args = array(
                    'post_type' => 'portfolio',
                    'posts_per_page' => 9,
                    'order' => 'ASC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'portfolio_type',
                            'field' => 'slug',
                            'terms' => 'web'
                        ) ,
                    ) ,
                );
                $query = new WP_Query($args);
                if ($query->have_posts()):
                    while ($query->have_posts()):
                        $query->the_post();
                        $term = get_the_terms($post->ID, 'portfolio_type');
                ?>
                <div class="card border-0 mb-0">
                    <div class="hovereffect">
                        <img class="card-img rounded-0" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="Card image">
                        <div class="overlay">
                                <div class="content">
                                    <ul>
                                    <?php foreach ($term as $term_name)
                            { ?>
                                    <li><?php echo $term_name->name; ?></li>
                                    <?php
                            } ?>
                                    </ul>
                                    <h3><?php the_title(); ?></h3>
                                    <a href="<?php the_permalink() ?>">Know more</a>
                                </div>
                            </div>
                    </div>
                </div>
                <?php $i++;
                    endwhile;
                endif;
                wp_reset_postdata(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="marketing" role="tabpanel" aria-labelledby="pills-marketing-tab" data-portfoliowork="(showfilterd=='marketing'? 'tab-pane fade show':'tab-pane fade hide')" style="display: none;">
            <div class="card-columns" style="column-gap: 0rem;"> 
            <?php
                $i = 30;
                $args = array(
                    'post_type' => 'portfolio',
                    'posts_per_page' => 9,
                    'order' => 'ASC',
                    'tax_query' => array(
                            array(
                            'taxonomy' => 'portfolio_type',
                            'field' => 'slug',
                            'terms' => 'marketing'
                            ),
                        ),
                    );
            $query = new WP_Query($args);
            if ($query->have_posts()):
                while ($query->have_posts()):
                $query->the_post();
                $term = get_the_terms($post->ID, 'portfolio_type');
                ?>
                    <div class="card border-0 mb-0">
                        <div class="hovereffect">
                            <img class="card-img rounded-0" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="Card image">
                            <div class="overlay">
                                <div class="content">
                                    <ul>
                                    <?php foreach ($term as $term_name){ ?>
                                    <li><?php echo $term_name->name; ?></li>
                                    <?php } ?>
                                    </ul>
                                    <h3><?php the_title(); ?></h3>
                                    <a href="<?php the_permalink() ?>">Know more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endwhile;
            endif;
            wp_reset_postdata(); ?>
            </div>
        </div>       
        <div class="tab-pane fade" id="branding" role="tabpanel" aria-labelledby="pills-branding-tab" data-portfoliowork="(showfilterd=='branding' ? 'tab-pane fade show':'tab-pane fade hide')" style="display: none;">
            <div class="card-columns" style="column-gap: 0rem;">
                <?php
                $i = 30;
                $args = array(
                    'post_type' => 'portfolio',
                    'posts_per_page' => 9,
                    'order' => 'ASC',
                    'tax_query' => array(
                        array(
                        'taxonomy' => 'portfolio_type',
                        'field' => 'slug',
                        'terms' => 'branding'
                        ),
                    ),
                );
                $query = new WP_Query($args);
                if ($query->have_posts()):
                while ($query->have_posts()):
                $query->the_post();
                $term = get_the_terms($post->ID, 'portfolio_type');
                ?>
                <div class="card border-0 mb-0">
                    <div class="hovereffect">
                        <img class="card-img rounded-0" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="Card image">
                        <div class="overlay">
                            <div class="content">
                                <ul>
                                    <?php foreach ($term as $term_name) { ?>
                                    <li><?php echo $term_name->name; ?></li>
                                    <?php } ?>
                                </ul>
                                <h3><?php the_title(); ?></h3>
                                <a href="<?php the_permalink() ?>">Know more</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $i++;
            endwhile;
            endif;
            wp_reset_postdata(); ?>
            </div>
        </div>
        <span class="hide"></span>
        <span class="show"></span>
    </div>
</div>