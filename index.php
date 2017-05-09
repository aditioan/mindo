<?php get_header(); ?>
<script type="text/javascript">
   $('.page-scroll a').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top - 50)
        }, 1250, 'easeInOutExpo');
        event.preventDefault();
    });

    // Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 51
    });

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function(){ 
            $('.navbar-toggle:visible').click();
    });

    // Offset for Main Navigation
    $('#mainNav').affix({
        offset: {
            top: 100
        }
    })

    // Floating label headings for the contact form
    $(function() {
        $("body").on("input propertychange", ".floating-label-form-group", function(e) {
            $(this).toggleClass("floating-label-form-group-with-value", !!$(e.target).val());
        }).on("focus", ".floating-label-form-group", function() {
            $(this).addClass("floating-label-form-group-with-focus");
        }).on("blur", ".floating-label-form-group", function() {
            $(this).removeClass("floating-label-form-group-with-focus");
        });
    });
</script>
    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div id="fullpage">
	<!-- Portfolio Grid Section -->
    <section id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Portfolio (Using Post Category)</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <?php
					$catquery = new WP_Query( 'cat=3&posts_per_page=10' );
					while($catquery->have_posts()) : $catquery->the_post();
					?>
					<div class="col-sm-4 portfolio-item">
	                    <a href="<?php the_permalink() ?>" class="portfolio-link" data-toggle="modal">
	                        <div class="caption">
	                            <div class="caption-content">
	                                <i class="fa fa-search-plus fa-3x"></i>
	                            </div>
	                        </div>
	                        <?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
	                    </a>
	                </div>
					<?php endwhile; 
				?>
            </div>
        </div>
    </section>

    <!-- Service Grid Section -->
    <section id="service">
        <div class="container">
            <div class="row" style="padding-bottom: 50px;">
                <div class="col-lg-12 text-center">
                    <h2>Service (Using Widget)</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <?php if(is_active_sidebar('widget-area-2')): ?>
                    <div class="col-sm-3 service-item">
                            <?php dynamic_sidebar('widget-area-2'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if(is_active_sidebar('widget-area-3')): ?>
                    <div class="col-sm-3 service-item">
                            <?php dynamic_sidebar('widget-area-3'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if(is_active_sidebar('widget-area-4')): ?>
                    <div class="col-sm-3 service-item">
                            <?php dynamic_sidebar('widget-area-4'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if(is_active_sidebar('widget-area-5')): ?>
                    <div class="col-sm-3 service-item">
                            <?php dynamic_sidebar('widget-area-5'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

     <!-- About Section -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>About (Using Page)</h2>
                    <hr class="star-light">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <p>
                    <?php
                    	$about = get_post(16); 
						echo $about->post_content;
                    ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Contact Me (Static HTML)</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                    <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" placeholder="Name" id="name" required data-validation-required-message="Please enter your name.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" placeholder="Email Address" id="email" required data-validation-required-message="Please enter your email address.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" placeholder="Phone Number" id="phone" required data-validation-required-message="Please enter your phone number.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label for="message">Message</label>
                                <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" class="btn btn-success btn-lg">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
	<!-- <main role="main"> -->
		<!-- section -->
		<!-- <section> -->

			<!-- <h1><?php //_e( 'Latest Posts', 'mindo' ); ?></h1> -->

			<?php //get_template_part('loop'); ?>

			<?php //get_template_part('pagination'); ?>

		<!-- </section> -->
		<!-- /section -->
	<!-- </main> -->

<?php //get_sidebar(); ?>

<?php get_footer(); ?>
