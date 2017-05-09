<?php get_header(); ?>

	<main role="main">
		<!-- section -->
		<section>
            <div class="container">
                <div class="row down">
                    <div class="col-lg-7 col-lg-offset-1">
						<h1><?php echo sprintf( __( '%s Search Results for ', 'mindo' ), $wp_query->found_posts ); echo get_search_query(); ?></h1>

						<?php get_template_part('loop'); ?>

						<?php get_template_part('pagination'); ?>
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
