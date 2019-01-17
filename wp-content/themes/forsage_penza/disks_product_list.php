<?php 

/*
	*Template Name: discs_product_list
	*Template Post Type: post, page, product
*/

get_header();
// Ensure visibility.
$shirina_shiny = $_GET['shirina_shiny'];
?>
<?php
// if (empty($_GET))
// 	echo do_shortcode('[products per_page="12" columns="4" paginate="true" category="diski" orderby="popularity"]');
// else
// 	echo do_shortcode('[products per_page="100" columns="4" paginate="true" category="diski" orderby="popularity"]');
global $product;
$diametr_diska = $_GET['diametr-diska'];
$vylet_diska = $_GET['vylet-diska'];
$shirina_diska = $_GET['shirina-diska'];

function start_of_filter(){
	return array( 
	'post_type' => 'product', 
	'posts_per_page' => 12, 
	'product_cat' => 'diski', );
}

function disks_filter(){
	global $diametr_diska;
	global $vylet_diska;
	global $shirina_diska;

	$diametr_diska_arr = array( 
		'taxonomy' => 'pa_diametr-diska', 
		'field' => 'slug', 
		'terms' => $diametr_diska, 
		'operator' => 'IN',);
	$vylet_diska_arr = array(
		'taxonomy' => 'pa_vylet-diska', 
		'field' => 'slug', 
		'terms' => $vylet_diska, 
		'operator' => 'IN',);
	$shirina_diska_arr = array(
		'taxonomy' => 'pa_shirina-diska', 
		'field' => 'slug', 
		'terms' => $shirina_diska, 
		'operator' => 'IN',);

	if ( $diametr_diska != NULL )
		$args_array = array($args_array, $diametr_diska_arr);
	if ( $vylet_diska != NULL )
		$args_array = array($args_array, $vylet_diska_arr);
	if ( $shirina_diska != NULL )
		$args_array = array($args_array, $shirina_diska_arr);		

	return $args_array;
}

?>

	<ul class="products">
	<?php
	if( empty($_GET) || $_GET['product-page'] != NULL ){
		echo do_shortcode('[products per_page="12" columns="4" paginate="true" category="diski" orderby="popularity"]');
	}
	elseif( !empty($_GET) ){
		$args_array = array( 
		'post_type' => 'product', 
		'posts_per_page' => 12, 
		'product_cat' => 'diski', 
		'tax_query' => array( 
			'relation' => 'AND', 
			disks_filter()));
		$loop = new WP_Query( $args_array );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( '<h1 style="font-size: 40px; padding: 21px">Ничего не найдено. Попробуйте ещё раз</h1><br><a style="font-size: 21px; line-height: 40px; padding: 21px" href="/"><< Вернуться назад</a>' );
		}
		wp_reset_postdata();
	}
	?>
</ul>
<?php get_footer(); 
//posadochnyj-diametr 14
//attribute="shirina-shiny" filter="185"
?>