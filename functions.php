<?php
/*
 *  Author: Aditio Agung Nugroho | @aditioan
 *  URL: mindosolutions.com
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
	'default-color' => 'FFF',
	'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
	'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
	'header-text'			=> false,
	'default-text-color'		=> '000',
	'width'				=> 1000,
	'height'			=> 198,
	'random-default'		=> false,
	'wp-head-callback'		=> $wphead_cb,
	'admin-head-callback'		=> $adminhead_cb,
	'admin-preview-callback'	=> $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('mindo', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// Bootstrap_Walker_Nav_Menu setup

add_action( 'after_setup_theme', 'bootstrap_setup' );

if ( ! function_exists( 'bootstrap_setup' ) ):

    function bootstrap_setup(){

        add_action( 'init', 'register_menu' );

        function register_menu(){
            register_nav_menu( 'top-bar', 'Top Menu' ); 
        }

        class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {


            function start_lvl( &$output, $depth = 0, $args = array() ) {

                $indent = str_repeat( "\t", $depth );
                $output    .= "\n$indent<ul class=\"dropdown-menu\">\n";

            }

            function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

                if (!is_object($args)) {
                    return; // menu has not been configured
                }

                $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

                $li_attributes = '';
                $class_names = $value = '';

                $classes = empty( $item->classes ) ? array() : (array) $item->classes;
                $classes[] = ($args->has_children) ? 'dropdown' : '';
                $classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
                $classes[] = 'menu-item-' . $item->ID;


                $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
                $class_names = ' class="' . esc_attr( $class_names ) . '"';

                $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
                $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

                $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

                $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
                $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
                $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
                $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
                $attributes .= ($args->has_children)        ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

                $item_output = $args->before;
                $item_output .= '<a'. $attributes .'>';
                $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
                $item_output .= ($args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
                $item_output .= $args->after;

                $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }

            function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

                if ( !$element )
                    return;

                $id_field = $this->db_fields['id'];

                //display this element
                if ( is_array( $args[0] ) )
                    $args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
                else if ( is_object( $args[0] ) )
                    $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
                $cb_args = array_merge( array(&$output, $element, $depth), $args);
                call_user_func_array(array(&$this, 'start_el'), $cb_args);

                $id = $element->$id_field;

                // descend only when the depth is right and there are childrens for this element
                if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

                    foreach( $children_elements[ $id ] as $child ){

                        if ( !isset($newlevel) ) {
                            $newlevel = true;
                            //start the child delimiter
                            $cb_args = array_merge( array(&$output, $depth), $args);
                            call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
                        }
                        $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
                    }
                        unset( $children_elements[ $id ] );
                }

                if ( isset($newlevel) && $newlevel ){
                    //end the child delimiter
                    $cb_args = array_merge( array(&$output, $depth), $args);
                    call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
                }

                //end this element
                $cb_args = array_merge( array(&$output, $element, $depth), $args);
                call_user_func_array(array(&$this, 'end_el'), $cb_args);
            }
        }
    }
endif;

// mindo Blank navigation
function mindo_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => false,
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'nav navbar-nav navbar-right'
		// 'menu_id'         => '',
		// 'echo'            => true,
		// 'fallback_cb'     => 'wp_page_menu',
		// 'before'          => '',
		// 'after'           => '',
		// 'link_before'     => '',
		// 'link_after'      => '',
		// 'items_wrap'      => '<ul>%3$s</ul>',
		// 'depth'           => 0,
		// 'walker'          => ''
		)
	);
}

// add_filter ( 'nav_menu_css_class', 'mindo_li_style' );

// function mindo_li_style ( $classes, $item, $args, $depth ){
//   $classes[] = 'page-scroll';
//   return $classes;
// }

// Load mindo Blank scripts (header.php)
function mindo_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

        wp_register_script('jqueri-min', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', array('jquery')); // Custom scripts
        wp_enqueue_script('jqueri-min'); // Enqueue it!

    	wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // Conditionizr
        wp_enqueue_script('conditionizr'); // Enqueue it!

        wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
        wp_enqueue_script('modernizr'); // Enqueue it!

        wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery')); // Custom scripts
        wp_enqueue_script('bootstrap'); // Enqueue it!

        //wp_register_script('contact', get_template_directory_uri() . '/js/contact_me.js', array('jquery')); // Custom scripts
        //wp_enqueue_script('contact'); // Enqueue it!

        wp_register_script('easing', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js', array('jquery')); // Custom scripts
        wp_enqueue_script('easing'); // Enqueue it!

        wp_register_script('validation', get_template_directory_uri() . '/js/jqBootstrapValidation.js', array('jquery')); // Custom scripts
        wp_enqueue_script('validation'); // Enqueue it!

        wp_register_script('fullPage', get_template_directory_uri() . '/js/jquery.fullPage.min.js', array('jquery')); // Custom scripts
        wp_enqueue_script('fullPage'); // Enqueue it!

        wp_register_script('retina', get_template_directory_uri() . '/js/retina.min.js', array('jquery')); // Custom scripts
        wp_enqueue_script('retina'); // Enqueue it!

        // wp_register_script('example', get_template_directory_uri() . '/js/examples.js', array('jquery')); // Custom scripts
        // wp_enqueue_script('example'); // Enqueue it!

        wp_register_script('mindoscripts', get_template_directory_uri() . '/js/mindo.js', array('jquery')); // Custom scripts
        wp_enqueue_script('mindoscripts'); // Enqueue it!
    }
}

// Load mindo Blank conditional scripts
function mindo_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}

// Load mindo Blank styles
function mindo_styles()
{
    wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array());
    wp_enqueue_style('mindo', get_stylesheet_uri(), array('bootstrap')); // Enqueue it!

    wp_register_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array());
    wp_enqueue_style('font-awesome'); // Enqueue it!

    wp_register_style('full-page', get_template_directory_uri() . '/css/jquery.fullPage.css', array());
    wp_enqueue_style('full-page'); // Enqueue it!

    // wp_register_style('example', get_template_directory_uri() . '/css/examples.css', array());
    // wp_enqueue_style('example'); // Enqueue it!

    wp_register_style('normalize', get_template_directory_uri() . '/css/normalize.min.css', array(), '1.0', 'all');
    wp_enqueue_style('normalize'); // Enqueue it!
}

// Register mindo Blank Navigation
function register_mindo_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'mindo'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'mindo'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'mindo') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'mindo'),
        'description' => __('Sidebar Area', 'mindo'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'mindo'),
        'description' => __('Service 1', 'mindo'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 3
    register_sidebar(array(
        'name' => __('Widget Area 3', 'mindo'),
        'description' => __('Service 2', 'mindo'),
        'id' => 'widget-area-3',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 4
    register_sidebar(array(
        'name' => __('Widget Area 4', 'mindo'),
        'description' => __('Service 3', 'mindo'),
        'id' => 'widget-area-4',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 5
    register_sidebar(array(
        'name' => __('Widget Area 5', 'mindo'),
        'description' => __('Service 4', 'mindo'),
        'id' => 'widget-area-5',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function mindowp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function mindowp_index($length) // Create 20 Word Callback for Index page Excerpts, call using mindowp_excerpt('mindowp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using mindowp_excerpt('mindowp_custom_post');
function mindowp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function mindowp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p class="small">' . $output . '</p>';
    echo $output;
}

//Retina Display Support

add_filter( 'wp_generate_attachment_metadata', 'retina_support_attachment_meta', 10, 2 );
/**
 * Retina images
 *
 * This function is attached to the 'wp_generate_attachment_metadata' filter hook.
 */
function retina_support_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }
 
    return $metadata;
}

/**
 * Create retina-ready images
 *
 * Referenced via retina_support_attachment_meta().
 */
function retina_support_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
 
            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );
 
            $info = $resized_file->get_size();
 
            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}

add_filter( 'delete_attachment', 'delete_retina_support_images' );
/**
 * Delete retina-ready images
 *
 * This function is attached to the 'delete_attachment' filter hook.
 */
function delete_retina_support_images( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
    $path = pathinfo( $meta['file'] );
    foreach ( $meta as $key => $value ) {
        if ( 'sizes' === $key ) {
            foreach ( $value as $sizes => $size ) {
                $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
                $retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
                if ( file_exists( $retina_filename ) )
                    unlink( $retina_filename );
            }
        }
    }
}

// Custom View Article link to Post
function mindo_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'mindo') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function mindo_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function mindogravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function mindocomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

//Page About Mindo
function about(){
    $wp_query = new WP_Query(array('page_id' => 16));
    if ($wp_query->have_posts()) {
        while ($wp_query->have_posts()) {
            # code...
        }
    }
}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'mindo_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'mindo_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'mindo_styles'); // Add Theme Stylesheet
add_action('init', 'register_mindo_menu'); // Add mindo Blank Menu
add_action('init', 'create_post_type_mindo'); // Add our mindo Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'mindowp_pagination'); // Add our mindo Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'mindogravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'mindo_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'mindo_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('mindo_shortcode_demo', 'mindo_shortcode_demo'); // You can place [mindo_shortcode_demo] in Pages, Posts now.
add_shortcode('mindo_shortcode_demo_2', 'mindo_shortcode_demo_2'); // Place [mindo_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [mindo_shortcode_demo] [mindo_shortcode_demo_2] Here's the page title! [/mindo_shortcode_demo_2] [/mindo_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called mindo-Blank
function create_post_type_mindo()
{
    register_taxonomy_for_object_type('category', 'mindo-blank'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'mindo-blank');
    register_post_type('mindo-blank', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Mindo Template Custom Post', 'mindo'), // Rename these to suit
            'singular_name' => __('Mindo Template Custom Post', 'mindo'),
            'add_new' => __('Add New', 'mindo'),
            'add_new_item' => __('Add New Mindo Template Custom Post', 'mindo'),
            'edit' => __('Edit', 'mindo'),
            'edit_item' => __('Edit Mindo Template Custom Post', 'mindo'),
            'new_item' => __('New Mindo Template Custom Post', 'mindo'),
            'view' => __('View Mindo Template Custom Post', 'mindo'),
            'view_item' => __('View Mindo Template Custom Post', 'mindo'),
            'search_items' => __('Search Mindo Template Custom Post', 'mindo'),
            'not_found' => __('No Mindo Template Custom Posts found', 'mindo'),
            'not_found_in_trash' => __('No Mindo Template Custom Posts found in Trash', 'mindo')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom mindo Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function mindo_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function mindo_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

?>
