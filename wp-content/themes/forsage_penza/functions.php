<?php
/**
 * forsage_Penza functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package forsage_Penza
 */

if ( ! function_exists( 'forsage_penza_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function forsage_penza_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on forsage_Penza, use a find and replace
		 * to change 'forsage_penza' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'forsage_penza', get_template_directory() . '/languages' );

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
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'forsage_penza' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'forsage_penza_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'forsage_penza_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function forsage_penza_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'forsage_penza_content_width', 640 );
}
add_action( 'after_setup_theme', 'forsage_penza_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function forsage_penza_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'forsage_penza' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'forsage_penza' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'forsage_penza_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function forsage_penza_scripts() {
	wp_enqueue_style( 'forsage_penza-style', get_stylesheet_uri() );
	wp_enqueue_style('styles', get_template_directory_uri() . '/assets/css/main.css');
	wp_enqueue_script( 'forsage_penza-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'forsage_penza-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'forsage_penza_scripts' );

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
add_action ('woocommerce_after_shop_loop_item_title','my_custom_good_bottom');

function my_custom_good_bottom(){
	global $product; 
	echo '<div class="product-attribute-proizvoditel"><img src="' . get_template_directory_uri() . '/assets/img/info.svg" class="icon-info">' . '<span>' . $product->get_attribute('proizvoditel') . '</span></div>';
	if ( $product->is_in_stock() ) {
    echo 
    '<div class="product-attribute-stock_quantit">
   		<span>В наличии: ' . $product->get_stock_quantity() . '</span>
   	</div>';
  }
  // echo wc_display_product_attributes( $product );
}

//woocommerce_single_product_summary

add_action('woocommerce_before_single_product_summary','my_custom_single_product_summary_title');

function my_custom_single_product_summary_title(){
	global $product;
	echo '<div class="my_custom-single_product"><span class="my_custom-single_product-title">' . $product->get_title() . '</span>';
}

add_action('woocommerce_single_product_summary', 'my_custom_single_product_summary', 25);

function my_custom_single_product_summary(){
	global $product;
	$cat = count( $product->get_category_ids() );
	echo '<p class="my_custom-single_product-summary-price">' . $product->get_price_html() . '</p>';
	if ( $cat == 1 ) {
	echo 
	'<div class="my_custom-single_product-summary-wrapper">
		<div class="my_custom-single_product-summary-content">
		<div class="my_custom-single_product-summary-content-line"><div class="my_custom-single_product-summary-content-line-element">Производитель: ' . $product->get_attribute('proizvoditel') . '</div></div>' . 
		'<div class="my_custom-single_product-summary-content-line"><div class="my_custom-single_product-summary-content-line-element">Ширина: ' . $product->get_attribute('shirina-shiny') . '</div>' .
		'<div class="my_custom-single_product-summary-content-line-element">Высота*: ' . $product->get_attribute('shirina-shiny'). '</div></div>' .
		'<div class="my_custom-single_product-summary-content-line"><div class="my_custom-single_product-summary-content-line-element">Диаметр: ' . $product->get_attribute('posadochnyj-diametr'). '</div>' .
			'<div class="my_custom-single_product-summary-content-line-element">Шипы*: ' . $product->get_attribute('shirina-shiny') . '</div></div>' . 
		'<div class="my_custom-single_product-summary-content-line"><div class="my_custom-single_product-summary-content-line-element">Сезон*: ' . $product->get_attribute('shirina-shiny') . '</div></div>';
	}
	$id = $product->get_id();
	if ( $product->is_in_stock() ) {
    echo '<div class="my_custom-single_product-summary-content-line">
    <div class="my_custom-single_product-summary-content-line-element-bottom"><span>В наличии: ' . $product->get_stock_quantity() . '</span></div>';
    echo '<div class="my_custom-single_product-summary-content-line-element-bottom"><a href="' . do_shortcode('[add_to_cart_url id="'. $id . '"]') . '">В корзину</a></div></div></div></div>';
  }
}


add_filter( 'woocommerce_product_tabs', 'devise_woo_rename_reviews_tab', 98);
function devise_woo_rename_reviews_tab($tabs) {
	$tabs['additional_information']['title'] = 'Характеристики';
	return $tabs;
}

add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
function jk_related_products_args( $args ) { 
	$args['posts_per_page'] = 12; // количество "Похожих товаров"
	$args['columns'] = 4; // количество колонок
	return $args;
}

add_filter('woocommerce_add_to_cart_fragments', 'header_add_to_cart_fragment');

function header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;
    ob_start();
    ?>
    <span class="basket-btn__counter">(<?php echo sprintf($woocommerce->cart->cart_contents_count); ?>)</span>
    <?php
    $fragments['.basket-btn__counter'] = ob_get_clean();
    return $fragments;
}
//ДОБАВЛЯЮ САЙДБАР
add_action( 'widgets_init', 'register_my_widgets' );

function register_my_widgets(){
	register_sidebar( array(
		'name' => "Ширина шины",
		'id' => 'shirina-shiny',
	) );
}


// add_filter('shortcode_atts_products', 'my_own_pagination', 10);

// function my_own_pagination(){

// }

// numbered pagination
function pagination($pages = '', $range = 4)
{  
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
 
     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}