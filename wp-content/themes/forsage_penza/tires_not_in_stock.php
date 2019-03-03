<?php 

/*
	*Template Name: tires_not_in_stock
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
$sort_price_value = $_GET['price'];


if ($profil_shiny == 10.5)	$profil_shiny = 10.50;

function tires_filter(){
	global $shirina_shiny, $profil_shiny, $posadochnyj_diamet, $winter, $summer, $spikes, $proizvoditel;

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
	$proizvoditel_arr = array(
		'taxonomy' => 'pa_proizvoditel', 
		'field' => 'slug', 
		'terms' => $proizvoditel, 
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
	if( $proizvoditel != NULL )
		$args_array = array($args_array, $proizvoditel_arr);

	return $args_array;
}


?>



<div class="filter-wrapper">
	<h2>Результаты по вашему запросу:</h2>
	<div class="filter-choice">
		<div class="row">
			<form action="/shiny-pod-zakaz" method="GET">
				<span>Сортировка:</span>
				<div class="for_mobile_adaptive">
					<ul>
						<li>
							<div class="characters">
								<select name="price">	
									<option disabled value="" selected> Цена </option>
									<?php if($sort_price_value != NULL):?><option value=""> Нет </option><?php endif;?>
									<option value="ASC">По возрастанию</option>
									<option value="DESC">По убыванию</option>
								</select>
							</div>
						</li>
						<li>
							<div class="characters">							
								<select name="shirina-shiny">
									<option value="" selected> <?php echo $shirina_shiny != NULL ? "Ширина: " . $shirina_shiny : " Ширина "; ?> </option>
									<?php if($shirina_shiny != NULL):?><option value=""> Любая </option><?php endif;?>
									<!-- values: 10.00, 11.00, 6.45, 7.0, 7.5, 8.25, 8.4, 9.00 -->
									<option value="31">31</option>
									<option value="32">32</option>
									<option value="33">33</option>
									<option value="35">35</option> 
									<option value="135">135</option>
									<option value="155">155</option>
									<option value="165">165</option>
									<option value="175">175</option>
									<option value="185">185</option>
									<option value="195">195</option>
									<option value="205">205</option>
									<option value="215">215</option>
									<option value="225">225</option>
									<option value="235">235</option>
									<option value="245">245</option>
									<option value="255">255</option>
									<option value="260">260</option>
									<option value="265">265</option>
									<option value="275">275</option>
									<option value="280">280</option>
									<option value="285">285</option>
									<option value="295">295</option>
									<option value="300">300</option>
									<option value="305">305</option>
									<option value="315">315</option>
									<option value="385">385</option>
									<option value="386">386</option>
								</select>
							</div>
						</li>
						<li>												
							<div class="characters">
								<select name="profil-shiny">
									<option value="" selected> <?php echo $profil_shiny != NULL ? "Высота: " . $profil_shiny : " Высота " ?> </option>
									<?php if($profil_shiny != NULL):?><option value=""> Любая </option><?php endif;?>
									<option value="6.45">6.45</option>		
									<option value="9.5">9.5</option>
									<option value="10.50">10.5</option>
									<option value="12.50">12.50</option>
									<option value="22.5">22.5</option>
									<option value="35">35</option>
									<option value="40">40</option>
									<option value="45">45</option>
									<option value="50">50</option>
									<option value="55">55</option>
									<option value="60">60</option>
									<option value="65">65</option>
									<option value="70">70</option>
									<option value="75">75</option>
									<option value="80">80</option>
									<option value="85">85</option>
									<option value="90">90</option>
									<option value="508">508</option>
									<option value="R1">R1</option>
								</select>
							</div>
						</li>
					</ul>
					<ul>
						<li>
							<div class="characters">
								<select name="posadochnyj-diametr">
									<option value="" selected> <?php echo $posadochnyj_diametr != NULL ? "Диаметр: " . $posadochnyj_diametr : " Диаметр "; ?> </option>
									<?php if($posadochnyj_diametr != NULL):?><option value=""> Любой </option><?php endif;?>	
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="13С">13С</option>
									<option value="14">14</option>
									<option value="14C">14C</option>
									<option value="15">15</option>
									<option value="15C">15C</option>
									<option value="16">16</option>
									<option value="16C">16C</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="17.5">17.5</option>
									<option value="19.5">19.5</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="22.5">22.5</option>
								</select>												
							</div>
						</li>
						<li>
							<div class="characters" id="cb">
								<div class="characters-checkbox">
									<img src="<?php echo get_template_directory_uri();?>/assets/img/cold.svg" alt="">
									<input type="checkbox" id="winter" name="winter" <?php echo $winter != NULL ? "checked" : "  "; ?> >
									<label for="winter"></label>
								</div>
								<div class="characters-checkbox">
									<img src="<?php echo get_template_directory_uri();?>/assets/img/sun.svg" class="summer" alt="" <?php echo $summer != NULL ? "checked" : "  "; ?>>
									<input type="checkbox" id="summer" name="summer">
									<label for="summer"></label>
								</div>
							</div>
						</li>
						<li>
							<div class="characters">													
								<div class="characters-checkbox spikes">
									<span>Шипы</span>
									<input type="checkbox" id="spikes" name="spikes" <?php echo $spikes != NULL ? "checked" : "  "; ?>>
									<label for="spikes"></label>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<input type="submit" value="Сортировать">
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
			'product_cat' => 'shiny',
			'paged' => $page,
			'tax_query' => array(
				'relation' => 'AND',
				tires_filter()
			),
			'meta_query' => array(
        array(
            'key' => '_stock_status',
            'value' => 'outofstock',
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

<?php get_footer(); ?>