<?php
/**
 * Single forum topic template
 *
 * @see Theme\Pages\SingleForumTopic
 *
 * @package joyjet
 */

use Theme\Components;

global $post;
?>

<main id="single-forum-topic" class="main-content page-bg-pattern-3">
    <div class="page-wrapper">
        <div class="container mb-4">
            <?php echo new Components\Breadcrumbs(); ?>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <?php
					echo new Components\Forum\TopicEntry(
                        array(
							'class'         => 'single-entry mb-4',
							'title'         => get_the_title(),
							'category_tags' => true,
							'author'        => $post->post_author,
							'excerpt'       => get_the_content(),
							'cta_link'      => '#respond',
							'cta_text'      => '<i class="icon text-18 mr-1 icon-arrow-up"></i> Responder',
							'post_date'     => get_the_date( 'j \d\e F \d\e Y H:i' ),
							// translators: comment count
							'footer_text'   => '20 likes',
                        )
					);

					?>

                    <div class="topic-comments">
                        <?php echo new Components\Forum\TopicComments(); ?>
                    </div>

                </div>

                <div class="col-md-3">
                    <?php
					echo new Components\SidebarListThin(
                        array(
							'from_posts' => array(
								'posts'         => $related_topics->posts,
								'comment_count' => true,
							),
                        )
					);
					?>
                </div>
            </div>
        </div>
    </div>
</main>