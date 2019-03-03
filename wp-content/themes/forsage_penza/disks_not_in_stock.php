<?php 

/*
	*Template Name: disks_not_in_stock
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
$sort_price_value = $_GET['price'];


if ($profil_shiny == 10.5)	$profil_shiny = 10.50;

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
								<select name="diametr-diska">
									<option value="" selected> <?php echo $diametr_diska != NULL ? "Диаметр: " . $diametr_diska : " Диаметр "; ?> </option>
									<?php if($diametr_diska != NULL):?><option value=""> Любой </option><?php endif;?>	
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="17-5">17,5</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
								</select>	
							</div>
						</li>
						<li>												
							<div class="characters">
								<select name="pcd">
									<option value="" selected> <?php echo $pcd != NULL ? "PCD: " . $pcd : " PCD "; ?> </option>
									<?php if($pcd != NULL):?><option value=""> Любой </option><?php endif;?>	
									<option value="3">3</option>
									<option value="4-98">4/98</option>
									<option value="4-98-100">4/98-100</option>
									<option value="4-100">4/100</option>
									<option value="4-100-114">4/100-114</option>
									<option value="4-114">4/114</option>
									<option value="4-108">4/108</option>
									<option value="5-100">5/100</option>
									<option value="5-105">5/105</option>
									<option value="5-108">5/108</option>
									<option value="5-110">5/110</option>
									<option value="5-100-112">5/100-112</option>
									<option value="5-112">5/112</option>
									<option value="5-114">5/114</option>
									<option value="5-115">5/115</option>
									<option value="5-118">5*118</option>
									<option value="5-120">5/120</option>
									<option value="5-130">5/130</option>
									<option value="5-139">5/139</option>
									<option value="5-150">5/150</option>
									<option value="5-160">5*160</option>
									<option value="6-114">6/114</option>
									<option value="6-130">6/130</option>
									<option value="6-139">6/139</option>
									<option value="6-170">6*170</option>
									<option value="7">7</option>
								</select>	
							</div>
						</li>
					</ul>
					<ul>
						<li>
							<div class="characters">
								<select name="posadochnyj-diametr">
									<option value="" selected> <?php echo $posadochnyj_diametr != NULL ? "Ст. отверстие: " . $posadochnyj_diametr : " Ст. отверстие "; ?> </option>
									<?php if($posadochnyj_diametr != NULL):?><option value=""> Любой </option><?php endif;?>	
									<option value="50">50</option>
									<option value="52-5">52,5</option>
									<option value="54">54</option>
									<option value="54-1">54,1</option>
									<option value="56-1">56,1</option>
									<option value="56-5">56,5</option>
									<option value="56-6">56,6</option>
									<option value="56-7">56,7</option>
									<option value="57">57</option>
									<option value="57-1">57,1</option>
									<option value="58-1">58,1</option>
									<option value="58-5">58,5</option>
									<option value="58-6">58,6</option>
									<option value="60">60</option>
									<option value="60-1">60,1</option>
									<option value="61-1">61,1</option>
									<option value="63-3">63,3</option>
									<option value="63-4">63,4</option>
									<option value="64-1">64,1</option>
									<option value="65-1">65,1</option>
									<option value="66-1">66,1</option>
									<option value="66-6">66,6</option>
									<option value="67">67</option>
									<option value="67-1">67,1</option>
									<option value="69-1">69,1</option>
									<option value="70-1">70,1</option>
									<option value="70-2">70,2</option>
									<option value="70-3">70,3</option>
									<option value="70-6">70,6</option>
									<option value="71-1">71,1</option>
									<option value="71-5">71,5</option>
									<option value="71-6">71,6</option>
									<option value="72-5">v</option>
									<option value="72-6">72,6</option>
									<option value="73">73</option>
									<option value="73-1">73,1</option>
									<option value="73-2">73,2</option>
									<option value="74-1">74,1</option>
									<option value="84-1">84,1</option>
									<option value="89-1">89,1</option>
									<option value="92-5">92,5</option>
									<option value="98">98</option>
									<option value="98-5">98,5</option>
									<option value="98-6">98,6</option>
								</select>						
							</div>
						</li>
						<li>												
							<div class="characters">
								<select name="vylet-diska">
									<option value="" selected> <?php echo $vylet_diska != NULL ? "Вылет ЕТ: " . $vylet_diska : " Вылет ЕТ "; ?> </option>
									<?php if($vylet_diska != NULL):?><option value=""> Любой </option><?php endif;?>	
									<!-- -10, 102, 106, 3, 4  -->
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="18">18</option>
									<option value="20">20</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="28">28</option>
									<option value="30">30</option>
									<option value="31">31</option>
									<option value="32">32</option>
									<option value="33">33</option>
									<option value="34">34</option>
									<option value="35">35</option>
									<option value="36">36</option>
									<option value="37">37</option>
									<option value="37-5">37,5</option>
									<option value="38">38</option>
									<option value="39">39</option>
									<option value="40">40</option>
									<option value="41">41</option>
									<option value="42">42</option>
									<option value="43">43</option>
									<option value="44">44</option>
									<option value="45">45</option>
									<option value="46">46</option>
									<option value="47">47</option>
									<option value="48">48</option>
									<option value="49">49</option>
									<option value="50">50</option>
									<option value="51">51</option>
									<option value="52">52</option>
									<option value="52-5">52,5</option>
									<option value="53">53</option>
									<option value="54">54</option>
									<option value="55">55</option>
									<option value="56">56</option>
									<option value="57">57</option>
									<option value="58">58</option>
									<option value="60">60</option>
									<option value="62">62</option>
									<option value="63">63</option>
									<option value="66">66</option>
									<option value="67">67</option>
									<option value="68">68</option>
								</select>	
							</div>
						</li>
						<li>												
							<div class="characters">
								<select name="shirina-diska">
									<option value="" selected> <?php echo $shirina_diska != NULL ? "Ширина: " . $shirina_diska : " Ширина "; ?> </option>
									<?php if($shirina_diska != NULL):?><option value=""> Любая </option><?php endif;?>	
									<option value="4">4</option>
									<option value="4-5-j">4.5J</option>
									<option value="5-0-j">5.0J</option>
									<option value="5-5-j">5.5J</option>
									<option value="6-0-j">6.0J</option>
									<option value="6-5">6.5</option>
									<option value="6-5-j">6.5J</option>
									<option value="7-0-j">7.0J</option>
									<option value="7-5-j">7.5J</option>
									<option value="8-0-j">8.0J</option>
									<option value="8-5-j">8.5J</option>
									<option value="9-0j">9.0J</option>
									<option value="9-5">9.5</option>
									<option value="10">10</option>
									<option value="10-5">10.5</option>
								</select>	
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
			'product_cat' => 'diski',
			'paged' => $page,
			'tax_query' => array(
				'relation' => 'AND',
				disks_filter()
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