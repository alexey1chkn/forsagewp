<?php 

/*
	*Template Name: discs_product_list
	*Template Post Type: post, page, product
*/

get_header();
// Ensure visibility.

?>
<?php
global $product;
$diametr_diska = $_GET['diametr-diska'];
$posadochnyj_diametr = $_GET['posadochnyj-diametr'];
$vylet_diska = $_GET['vylet-diska'];
$shirina_diska = $_GET['shirina-diska'];
$pcd = $_GET['pcd'];
$sort_price_value = $_POST['price'];
$proizvoditel = $_POST['proizvoditel'];

function disks_filter(){
	global $diametr_diska, $pcd, $posadochnyj_diametr, $vylet_diska, $shirina_diska, $proizvoditel;

	$diametr_diska_arr = array( 
		'taxonomy' => 'pa_diametr-diska', 
		'field' => 'slug', 
		'terms' => $diametr_diska, 
		'operator' => 'IN',);
	$posadochnyj_diametr_arr = array( 
		'taxonomy' => 'pa_posadochnyj-diametr', 
		'field' => 'slug', 
		'terms' => $posadochnyj_diametr, 
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
	$pcd1_arr = array( 
		'taxonomy' => 'pa_pcd', 
		'field' => 'slug', 
		'terms' => $pcd, 
		'operator' => 'IN',);
	$pcd2_arr = array( 
		'taxonomy' => 'pa_posadochnyj-razmer', 
		'field' => 'slug', 
		'terms' => $pcd, 
		'operator' => 'IN',);
	$proizvoditel_arr = array(
		'taxonomy' => 'pa_proizvoditel', 
		'field' => 'slug', 
		'terms' => $proizvoditel, 
		'operator' => 'IN',);

	if ( $diametr_diska != NULL )
		$args_array = array($args_array, $diametr_diska_arr);
	if ( $posadochnyj_diametr != NULL )
		$args_array = array($args_array, $posadochnyj_diametr_arr);
	if ( $vylet_diska != NULL )
		$args_array = array($args_array, $vylet_diska_arr);
	if ( $shirina_diska != NULL )
		$args_array = array($args_array, $shirina_diska_arr);		
	if ( $pcd != NULL )
		$args_array = array($args_array, $pcd1_arr);
	if( $proizvoditel != NULL )
		$args_array = array($args_array, $proizvoditel_arr);

	return $args_array;
}

?>
<div class="filter-wrapper">
	<h2>Результаты по вашему запросу:</h2>
	<div class="filter-choice">
		<div class="row">
			<form method="POST">
				<div class="for_mobile_adaptive">
					<span>Сортировка:</span>
					<ul>
						<li>
							<div class="characters">
								<select name="proizvoditel" id="">
									<option disabled selected> Производитель </option>
									<option value="neo">NEO</option>
									<option value=""></option>
									<option value=""></option>
								</select>
							</div>
						</li>
						<li>
							<div class="characters">
								<select name="price" id="">
									<option disabled selected> Цена </option>
									<option value="ASC">По возрастанию</option>
									<option value="DESC">По убыванию</option>
								</select>
							</div>
						</li>
					</ul>
					<input type="submit" value="Сортировать">
				</div>
			</form>
		</div>
	</div>
</div>

<?php 
	if ($sort_price_value != NULL) $sort_price = '_price';
?>

<div class="woocommerce columns-4">
	<ul class="products columns-4">
		<?php
			$_GET['page_my'] != NULL ? $page = $_GET['page_my'] : $page = 1;
			$args_array = array( 
			'post_type' => 'product', 
			'orderby'   => 'meta_value_num',
			'meta_key'  => $sort_price,
			'order' => $sort_price_value,
			'posts_per_page' => 12, 
			'product_cat' => 'diski',
			'paged' => $page,
			'tax_query' => array(
				'relation' => 'AND',
				disks_filter()
			),
			'meta_query' => array(
        array(
            'key' => '_stock_status',
            'value' => 'instock',
            'compare' => '=',
        )
      )
		);
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
			elseif( $page_quantity < 10){
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
			elseif( $page_quantity < 100 ){
				$page = $_GET['page_my'];
				if ($i <= 3 || $i > $page_quantity-3 || $i == $page-1 || $i == $page || $i == $page+1)
					if( $i == $page ) 
						echo '<a class="pagination_page_active" href="' . substr($_SERVER['REQUEST_URI'], 0, -2) .  $i . '"><span>' . $i . '</span></a>';
					else 
						echo '<a href="' . substr($_SERVER['REQUEST_URI'], 0, -2) .  $i . '"><span>' . $i . '</span></a>';
				elseif($count < 2 ){
						echo '<span class="pagination_page_between">.</span>';
						$count++;
				}
			}else{
				$page = $_GET['page_my'];
				if ($i <= 3 || $i > $page_quantity-3 || $i == $page-1 || $i == $page || $i == $page+1)
					if( $i == $page ) 
						echo '<a class="pagination_page_active" href="' . substr($_SERVER['REQUEST_URI'], 0, -3) .  $i . '"><span>' . $i . '</span></a>';
					else 
						echo '<a href="' . substr($_SERVER['REQUEST_URI'], 0, -3) .  $i . '"><span>' . $i . '</span></a>';
				elseif($count < 3 ){
						echo '<span class="pagination_page_between">.</span>';
						$count++;
				}
			}
		}
	?>
</nav>

<?php get_footer(); ?>
