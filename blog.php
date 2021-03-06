<?php /* Template Name: Mindo Blog Template */ get_header(); ?>

	<main role="main">
        <!-- section -->

        <!-- Blog Section -->
        <section id="blog">
            <div class="container">
                <div class="row down">
                    <div class="col-lg-7 col-lg-offset-1">
                        <?php 
                            // the query
                            $wpb_all_query = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish')); ?>

                            <?php if ( $wpb_all_query->have_posts() ) : while ($wpb_all_query->have_posts()) : $wpb_all_query->the_post(); ?>

                            <!-- article -->
                                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                    <!-- post thumbnail -->
                                    <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                            <?php the_post_thumbnail(array(120,120)); // Declare pixel size you need inside the array ?>
                                        </a>
                                    <?php endif; ?>
                                    <!-- /post thumbnail -->

                                    <!-- post title -->
                                    <h2>
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <!-- /post title -->

                                    <!-- post details -->
                                    <span class="date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>
                                    <span class="author"><?php _e( 'Published by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
                                    <span class="comments"><?php if (comments_open( get_the_ID() ) ) comments_popup_link( __( 'Leave your thoughts', 'html5blank' ), __( '1 Comment', 'html5blank' ), __( '% Comments', 'html5blank' )); ?></span>
                                    <!-- /post details -->

                                    <span class="blog"><?php mindowp_excerpt('mindowp_index'); // Build your custom callback length in functions.php ?></span>

                                    <?php edit_post_link(); ?>

                                </article>
                                <!-- /article -->

                            <?php endwhile; ?>

                            <?php else: ?>

                                <!-- article -->
                                <article>
                                    <h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
                                </article>
                                <!-- /article -->

                        <?php endif; ?>
                    </div>
                    <div class="col-lg-3">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- /section -->

    </main>

<?php get_footer(); ?>
