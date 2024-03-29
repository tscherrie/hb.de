<?php
define('THEME_SHORT_NAME', 'Ristorante');
define('THEME_LONG_NAME', 'AIT Ristorante theme');
define('THEME_CODE_NAME', 'ristorante');

if(file_exists(dirname(__FILE__) . '/.dev')){
	define('AIT_DEVELOPMENT', true); // is this development mode?
}

$aitThemeCustomTypes = array(
 'portfolio' => 31,
 'slider-creator' => 32,
 'item' => 35,
 'menu' => 36
);

$aitThemeWidgets = array(
	'post',
	'flickr',
	'submenu',
	'twitter',
	'reservation',
	'featured'
);

$aitEditorShortcodes = array(
	'custom',
	'columns',
	'images',
	'posts',
	'buttons',
	'boxesFrames',
	'lists',
	'notifications',
	'modal',
	'social',
	'video',
	'gMaps',
	'gChart',
	'portfolio',
	'language',
	'tabs',
	'items',
	'menus'
);
$aitThemeShortcodes = array(
	'boxesFrames' => 2,
	'buttons' => 2,
	'columns'=> 1,
	'custom'=> 1,
	'images'=> 1,
	'lists'=> 1,
	'modal'=> 1,
	'notifications'=> 1,
	'portfolio'=> 1,
	'posts'=> 1,
	'sitemap'=> 1,
	'social'=> 1,
	'video'=> 1,
	'language'=> 1,
	'gMaps'=> 1,
	'gChart'=> 1,
	'tabs' => 1,
	'items' => 1,
	'menus' => 1
);

require dirname(__FILE__) . '/AIT/ait-bootstrap.php';

/* EDITABLE CUSTOM TYPES :: START */                          // Must be singular
$editable_ct_name = @$aitThemeOptions->searchConfig->editableTypeName;                        // Must be singular
$menu_ct_name = @$aitThemeOptions->searchConfig->menuTypeName;                        // Must be singular

if($editable_ct_name == ""){
  $editable_ct_name = "Sport";
}
if($menu_ct_name == ""){
  $menu_ct_name = "Menu";
}

define('EDITABLE_CT_NAME',$editable_ct_name);
define('MENU_CT_NAME',$menu_ct_name);

$editableType = @$aitThemeOptions->searchConfig->editableTypeDisplay;
$menuType = @$aitThemeOptions->searchConfig->menuTypeDisplay;

if (isset($editableType)){
} else {
  add_action('admin_menu', 'editableMenuItem');
}
if (isset($menuType)){
} else {
  add_action('admin_menu', 'menuMenuItem');
}

function editableMenuItem(){
  remove_menu_page('edit.php?post_type=ait-item');
}
function menuMenuItem(){
  remove_menu_page('edit.php?post_type=ait-menu');
}

/* EDITABLE CUSTOM TYPES :: END */


remove_action('wp_head', 'wp_generator'); // do not show generator meta element

add_filter('widget_title', 'do_shortcode');
add_filter('widget_text', 'do_shortcode'); // do shortcode in text widget

/**
 * Hook init
 */
function theme_init () {

	if (is_admin()) {

	} elseif (!is_admin()) {

		aitLoadJQuery('1.7.1');

		// HTML 5
		wp_enqueue_script( 'JS_html5', THEME_JS_URL . '/libs/html5.js',  array('jquery') );

		// google maps
		wp_enqueue_script( 'JS_googleMaps', THEME_JS_URL . '/libs/jquery.gmap.min.js',  array('jquery') );

		// Easing
		wp_enqueue_script( 'JS_easy', THEME_JS_URL . '/libs/jquery.easing.1.3.js',  array('jquery') );

        // Background Slider
        wp_enqueue_script( 'JS_slider_script', THEME_JS_URL . '/new-slider.js',  array('jquery') );

		// Colorbox
		wp_enqueue_style( 'CSS_colorbox', THEME_CSS_URL . '/colorbox.css');
		wp_enqueue_script( 'JS_colorbox', THEME_JS_URL . '/libs/jquery.colorbox-min.js',  array('jquery') );

		// Product JCarousel
		wp_enqueue_script( 'JS_jcarousel', THEME_JS_URL . '/libs/jquery.jcarousel.min.js',  array('jquery') );

		// fancybox
		wp_enqueue_style( 'CSS_fancybox', THEME_CSS_URL . '/fancybox/jquery.fancybox-1.3.4.css');
		wp_enqueue_script( 'JS_fancybox', THEME_JS_URL . '/libs/jquery.fancybox-1.3.4.js',  array('jquery') );

		// infield labels
		wp_enqueue_style( 'CSS_contact', THEME_CSS_URL . '/contact.css'); 
		wp_enqueue_script( 'JS_infieldlabel', THEME_JS_URL . '/libs/jquery.infieldlabel.js',  array('jquery') );

		// scroll to
		wp_enqueue_script( 'JS_scrollto', THEME_JS_URL . '/libs/jquery.scrollTo-1.4.3.1.js',  array('jquery') );

		// hoverZoom
		wp_enqueue_style( 'CSS_hover_zoom', THEME_CSS_URL . '/hoverZoom.css');
		wp_enqueue_script( 'JS_hover_zoom', THEME_JS_URL . '/libs/hover.zoom.js',  array('jquery') );

    	// jquery UI for datepicker
		wp_enqueue_script( 'JS_UI', THEME_JS_URL . '/libs/jquery-ui-1.8.17.custom.min.js',  array('jquery') );

		// jqtransform for custom styled select
		wp_enqueue_script( 'JS_jqTransform', THEME_JS_URL . '/libs/jquery.jqtransform.js',  array('jquery') );

		// General script
		wp_enqueue_script( 'JS_general_script', THEME_JS_URL . '/script.js',  array('jquery') );
	}
}
add_action('init', 'theme_init');

$pageOptions = array(
	'post_featured_images' => new WPAlchemy_MetaBox(array(
		'id' => '_ait_featured_images_options',
		'title' => __('Featured Image'),
		'types' => array('ait-item' ,'post'),
		'context' => 'normal',
		'priority' => 'core',
		'config' => dirname(__FILE__) . '/conf/post-featured.neon'
	)),
  'page_slider' => new WPAlchemy_MetaBox(array(
		'id' => '_ait_slider_options',
		'title' => __('Slider Page Settings'),
		'types' => array('ait-item', 'page', 'post'),
		'context' => 'normal',
		'priority' => 'core',
		'config' => dirname(__FILE__) . '/conf/page-slider-creator-meta.neon'
	))
);


/**
 ******* Default definitions ********
 */
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override ait_setup() in a child theme, add your own ait_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
if(!function_exists('ait_setup')):
function ait_setup() {

	/* Make Twenty Eleven available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Eleven, use a find and replace
	 * to change 'magazine' to the name of your theme in all the template files.
	 */
	load_theme_textdomain(THEME_CODE_NAME, get_template_directory() . '/languages');

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if(is_readable($locale_file))
		require_once($locale_file);

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support('automatic-feed-links');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary-menu', __( 'Primary Menu', THEME_CODE_NAME ) );

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support('post-thumbnails');

	// This theme has basic support for woocommerce
	add_theme_support('woocommerce');

	// The next four constants set how Twenty Eleven supports custom headers.

	// The height and width of your custom header.
	// Add a filter to ait_header_image_width and ait_header_image_height to change these values.
	define('HEADER_IMAGE_WIDTH', apply_filters('ait_header_image_width', 1000));
	define('HEADER_IMAGE_HEIGHT', apply_filters('ait_header_image_height', 288));

	// Add Twenty Eleven's custom image sizes
	add_image_size('large-feature', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true); // Used for large feature (header) images
	add_image_size('small-feature', 500, 300); // Used for featured posts if a large-feature doesn't exist

}
endif; // ait_setup

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function ait_widgets_init() {

	register_sidebar(array(
		'name' => __('Left Widget Area', THEME_CODE_NAME),
		'id' => 'left-sidebar',
		'description' => __(''),
		'before_widget' => '<div id="%1$s" class="box widget-container %2$s"><div class="box-wrapper">',
		'after_widget' => "</div></div>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));

	register_sidebar(array(
		'name' => __('Right Widget Area', THEME_CODE_NAME),
		'id' => 'right-sidebar',
		'description' => __(''),
		'before_widget' => '<div id="%1$s" class="box widget-container wdidget %2$s"><div class="box-wrapper">',
		'after_widget' => "</div></div>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));

	// Shop Sidebar
	if(defined('WOOCOMMERCE_VERSION')){
		register_sidebar(array(
			'name' => __('Shop Sidebar', THEME_CODE_NAME),
			'id' => 'shop-sidebar',
			'description' => __('', ''),
			'before_widget' => '<div id="%1$s" class="box widget-container %2$s"><div class="box-wrapper">',
			'after_widget' => "</div></div>",
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		));
	}

}
add_action('widgets_init', 'ait_widgets_init');

/**
 * Tell WordPress to run ait_setup() when the 'after_setup_theme' hook is run.
 */
add_action('after_setup_theme', 'ait_setup');

// colorbox rel (arrows)
add_filter( 'wp_get_attachment_link' , 'add_colorbox_rel' );
function add_colorbox_rel( $attachment_link ) {
	if( strpos( $attachment_link , 'a href') != false && strpos( $attachment_link , 'img') != false && (strpos( $attachment_link , '.jpg') != false || strpos( $attachment_link , '.png') != false || strpos( $attachment_link , '.gif') != false))
		$attachment_link = str_replace( 'a href' , 'a rel="colorbox" class="zoom" href' , $attachment_link );
	return $attachment_link;
}

function default_menu(){
	wp_nav_menu(array('menu' => 'Main Menu', 'fallback_cb' => 'default_page_menu', 'container' => 'nav', 'container_class' => 'mainmenu', 'menu_class' => 'menu clear', 'walker' => new description_walker()));
}

function default_page_menu(){
  echo '<nav class="mainmenu" role="navigation">';
	wp_page_menu(array('menu' => 'Main Menu', 'menu_class' => 'menu clear', 'container' => 'nav', 'container_class' => 'mainmenu'));
	echo '</nav>';
}

function default_footer_menu(){
	wp_nav_menu(array('menu' => 'Main Menu', 'container' => 'nav', 'container_class' => 'footer-menu', 'menu_class' => 'menu clear', 'depth' => 1));
}


class description_walker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth, $args)
	{
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-dw-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes = ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$prepend = '<strong>';
		$append = '</strong>';

		if($depth != 0)
		{
			$description = $append = $prepend = "";
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
