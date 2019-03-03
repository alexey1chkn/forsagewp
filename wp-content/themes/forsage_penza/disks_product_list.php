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
									<option value="" selected> <?php echo $proizvoditel != NULL ? "Производитель выбран" : " Производитель "; ?> </option>
									<?php if($proizvoditel != NULL):?><option value=""> Любой </option><?php endif;?>
									<option value="kitaj">Китай</option>
									<option value="k-amp-k-krasnoyarsk">K&K Красноярск</option>
									<option value="tech-line">Tech-Line</option>
									<option value="vicom">VICOM</option>
									<option value="nitro">NITRO</option>
									<option value="nw">NW</option>
									<option value="neo">NEO</option>
									<option value="replica">REPLICA</option>
									<option value="yaponiya">Япония</option>
									<option value="turciya">Турция</option>
									<option value="tolyatti">ТОЛЬЯТТИ</option>
									<option value="tajvan">Тайвань</option>
									<option value="skad">СКАД</option>
									<option value="rossiya">Россия</option>
									<option value="nizhnij-novgorod">Нижний Новгород</option>
									<option value="megaljum">МЕГАЛЮМ</option>
									<option value="kursk">КУРСК</option>
									<option value="kremenchug">Кременчуг</option>
									<option value="vsmpo">ВСМПО</option>
									<option value="avtovaz">АвтоВАЗ</option>
									<option value="zumbo-wheels">Zumbo Wheels</option>
									<option value="yokatta">YOKATTA</option>
									<option value="yam">YAM</option>
									<option value="x-race">X-RACE</option>
									<option value="xtrike">X’trike</option>									
									<option value="venti">VENTI</option>
									<option value="usw">USW</option>
									<option value="trebl">TREBL</option>									
									<option value="stilavto">STILAVTO</option>
									<option value="sant">SANT</option>
									<option value="sanfox">SanFox</option>
									<option value="roner">Roner</option>
									<option value="replikey">RepliKey</option>									
									<option value="r-steel">R-Steel</option>
									<option value="pdw">PDW</option>
									<option value="od">OD</option>
									<option value="nz">NZ</option>													
									<option value="mw">MW</option>
									<option value="megami">MEGAMI</option>
									<option value="ls">LS</option>
									<option value="legeartis">LegeArtis</option>
									<option value="kronprinz">KRONPRINZ</option>
									<option value="kfz">KFZ</option>									
									<option value="ifree">iFREE</option>
									<option value="forse">FORSE</option>
									<option value="fire-ball">FIRE BALL</option>
									<option value="eurodisk">EURODISK</option>
									<option value="dezent">DEZENT</option>
									<option value="cross-street">Cross Street</option>
									<option value="cam">CAM</option>
									<option value="bap">BAP</option>
									<option value="alfa-wheels">Alfa Wheels</option>
									<option value="alcasta">ALCASTA</option>
									<option value="4-go">4 GO</option>
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

<?php require 'pagination.php'; ?>

<div class="tires_not_in_stock">
	<h2>Нет нужных дисков в наличии?</h2>
	<span>Посмотрите в каталоге “Диски под заказ”</span>
	<span>Привезем в кратчайшие сроки!</span>
	<a href="/disks-pod-zakaz">Посмотреть каталог</a>
</div>

<?php get_footer(); ?>
