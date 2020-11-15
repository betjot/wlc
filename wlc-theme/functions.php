<?php
/**
 * wlc-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wlc-theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'wlc_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wlc_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on wlc-theme, use a find and replace
		 * to change 'wlc-theme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wlc-theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'header-menu' => esc_html__( 'Header Menu', 'wlc-theme' ),
				'top-menu' => esc_html__( 'Top Menu', 'wlc-theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'wlc_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'wlc_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wlc_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wlc_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'wlc_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wlc_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'wlc-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'wlc-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'wlc_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wlc_theme_scripts() {
	wp_enqueue_style( 'wlc-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'wlc-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'wlc-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'wlc-theme-js', get_stylesheet_directory_uri( ) . '/js/theme.js', [ 'jquery' ], true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wlc_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


// add search to header menu
add_filter('wp_nav_menu_items', 'add_search_form', 10, 2);
function add_search_form($items, $args) {
if( $args->theme_location == 'header-menu' )
	$items .= '<li>' . get_search_form( false ) . '</li>';
	return $items;
}

// add login/profile button to top menu
add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );
function wti_loginout_menu_link( $items, $args ) {
   if ($args->theme_location == 'top-menu') {
      if (is_user_logged_in()) {
         $items .= '<li class="log my-profile-btn"><a href="'. get_edit_profile_url() .'">'. __("My Profile") .'</a></li>';
      } else {
         $items .= '<li class="log log-in-btn"><a href="'. wp_login_url(get_permalink()) .'">'. __("Log in") .'</a></li>';
      }
   }
   return $items;
}

// customize search form
function html5_search_form( $form ) { 
	$form = '<section class="search"><form role="search" method="get" id="search-form" action="' . home_url( '/' ) . '" >
   <label class="screen-reader-text" for="s">' . __('',  'domain') . '</label>
	<input type="search" value="' . get_search_query() . '" name="s" id="s" placeholder="Search..." />
	<button type="submit" id="searchsubmit" value="'. esc_attr__('Go', 'domain') .'" /><span class="dashicons dashicons-search"></span></button>
	</form></section>';
	return $form;
}

add_filter( 'get_search_form', 'html5_search_form' );