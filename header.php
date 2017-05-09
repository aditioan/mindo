<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <!-- <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon"> -->
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>
		<script>
        // conditionizr.com
        // configure environment tests
        conditionizr.config({
            assets: '<?php echo get_template_directory_uri(); ?>',
            tests: {}
        });
        </script>

	</head>
	<body id="page-top" <?php body_class(); ?>>

	<div id="skipnav"><a href="#maincontent">Skip to main content</a></div>

    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	       		<?php //wp_nav_menu( array('menu' => 'Main', 'menu_class' => 'nav navbar-nav navbar-right', 'depth'=> 4, 'container'=> false, 'walker'=> new Bootstrap_Walker_Nav_Menu)); ?>
                <?php mindo_nav(); ?>
	      	</div><!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <?php if(is_front_page()): ?>

    <!-- Header -->
    <header>
        <div class="container" id="maincontent" tabindex="-1">
            <div class="row">
                <div class="col-lg-12">
                    <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/profile.png" alt="">
                    <div class="intro-text">
                        <h2 class="name">Mindo Sample Template</h2>
                        <hr class="star-light">
                        <span class="skills">Web Developer - Graphic Artist - User Experience Designer</span>
                        <br>
                        <span class="skills">(This section will be only showed in front page)</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <?php endif ?>
