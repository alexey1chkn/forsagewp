<?php 

/*
	*Template Name: tires_product_list
	*Template Post Type: post, page, product
*/

get_header();
// Ensure visibility.
?>
<?php

global $product;
$shirina_shiny = $_GET['shirina-shiny'];
$profil_shiny = $_GET['profil-shiny'];
$posadochnyj_diametr = $_GET['posadochnyj-diametr'];
$winter = $_GET['winter'];
$summer = $_GET['summer'];
$spikes = $_GET['spikes'];

if ($profil_shiny == 10.5)	$profil_shiny = 10.50;

function tires_filter(){
	global $shirina_shiny, $profil_shiny, $posadochnyj_diamet, $winter, $summer, $spikes;

	$shirina_shiny_arr = array( 
		'taxonomy' => 'pa_shirina-shiny', 
		'field' => 'slug', 
		'terms' => $shirina_shiny, 
		'operator' => 'IN',);
	$profil_shiny_arr = array(
		'taxonomy' => 'pa_profil-shiny', 
		'field' => 'slug', 
		'terms' => $profil_shiny, 
		'operator' => 'IN',);
	$posadochnyj_diametr_arr = array(
		'taxonomy' => 'pa_posadochnyj-diametr', 
		'field' => 'slug', 
		'terms' => $posadochnyj_diametr, 
		'operator' => 'IN',);
	$winter_arr = array(
		'taxonomy' => 'pa_sezonnost', 
		'field' => 'slug', 
		'terms' => 'zima', 
		'operator' => 'IN',);
	$summer_arr = array(
		'taxonomy' => 'pa_sezonnost', 
		'field' => 'slug', 
		'terms' => 'leto', 
		'operator' => 'IN',);
	$spikes_arr = array(
		'taxonomy' => 'pa_shipy', 
		'field' => 'slug', 
		'terms' => 'true', 
		'operator' => 'IN',);

	if ( $shirina_shiny != NULL )
		$args_array = array($args_array, $shirina_shiny_arr);
	if ( $profil_shiny != NULL )
		$args_array = array($args_array, $profil_shiny_arr);
	if ( $posadochnyj_diametr != NULL )
		$args_array = array($args_array, $posadochnyj_diametr_arr);
	if( $winter == 'on' && $summer == 'on' )
		;
		else if ( $winter == 'on' )
			$args_array = array($args_array, $winter_arr);				
		else if ( $summer == 'on' )
			$args_array = array($args_array, $summer_arr);
	if ( $spikes != NULL )
		$args_array = array($args_array, $spikes_arr);

	return $args_array;
}

?>
<div class="woocommerce columns-4">
	<ul class="products columns-4">
		<?php
			$_GET['page_my'] != NULL ? $page = $_GET['page_my'] : $page = 1;
			$args_array = array( 
			'post_type' => 'product', 
			'posts_per_page' => 12, 
			'product_cat' => 'shiny',
			'paged' => $page,
			'tax_query' => array( 
				'relation' => 'AND', 
				tires_filter()));
			$loop = new WP_Query( $args_array );
			$all_posts = $loop->found_posts;
			$page_quantity = ceil( $all_posts / 12 );
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) : $loop->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
			} else {
				echo __( '<h1 style="font-size: 40px; padding: 21px">Ничего не найдено. Попробуйте еще раз</h1><br><a style="font-size: 21px; line-height: 40px; padding: 21px" href="/"><< Вернуться назад</a>' );
			}
			wp_reset_postdata();
		?>
	</ul>
</div>

<nav class="navigation pagination_page_down" role="navigation">
	<?php
		if(empty($_GET))
			$get_sign = "?";
		else
			$get_sign = "&";

		for($i = 1; $i <= $page_quantity; $i++){
			if ( $_GET['page_my'] == NULL ){
				$page = $_GET['page_my'];
				if ($i <= 3 || $i > $page_quantity-3 || $i == $page-1 || $i == $page || $i == $page+1)
					if( $i == $page ) 
						echo '<a class="pagination_page_active" href="' . $_SERVER['REQUEST_URI'] . $get_sign . 'page_my=' .  $i . '"><span>' . $i . '</span></a>';
					else 
						echo '<a href="' . $_SERVER['REQUEST_URI'] . $get_sign . 'page_my=' . $i . '"><span>' . $i . '</span></a>';
				elseif($count < 3 ){
						echo '<span class="pagination_page_between">.</span>';
						$count++;
				}
			}
			elseif( $i < 10){
				$page = $_GET['page_my'];
				if ($i <= 3 || $i > $page_quantity-3 || $i == $page-1 || $i == $page || $i == $page+1)
					if( $i == $page ) 
						echo '<a class="pagination_page_active" href="' . substr($_SERVER['REQUEST_URI'], 0, -1) .  $i . '"><span>' . $i . '</span></a>';
					else 
						echo '<a href="' . substr($_SERVER['REQUEST_URI'], 0, -1) .  $i . '"><span>' . $i . '</span></a>';
				elseif($count < 3 ){
						echo '<span class="pagination_page_between">.</span>';
						$count++;
				}
			}
			else{
				$page = $_GET['page_my'];
				if ($i <= 3 || $i > $page_quantity-3 || $i == $page-1 || $i == $page || $i == $page+1)
					if( $i == $page ) 
						echo '<a class="pagination_page_active" href="' . substr($_SERVER['REQUEST_URI'], 0, -2) .  $i . '"><span>' . $i . '</span></a>';
					else 
						echo '<a href="' . substr($_SERVER['REQUEST_URI'], 0, -1) .  $i . '"><span>' . $i . '</span></a>';
				elseif($count < 3 ){
						echo '<span class="pagination_page_between">.</span>';
						$count++;
				}
			}
		}
	?>
</nav>

<?php get_footer(); ?>
