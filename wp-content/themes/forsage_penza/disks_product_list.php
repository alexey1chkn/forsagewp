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
?>

	<ul class="products">
	<?php
	if(empty($_GET) || $_GET['product-page'] != NULL){
		echo do_shortcode('[products per_page="12" columns="4" paginate="true" category="diski" orderby="popularity"]');
	}
	elseif(!empty($_GET)){
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 12,
			'product_cat' => 'diski',
			'tax_query' => array(
				'relation' => 'AND',
			    array(
			      'taxonomy' => 'pa_diametr-diska',
			      'field' => 'slug',
			      'terms' => $diametr_diska,
			      'operator' => 'IN',
			    ),
			    array(
			      'taxonomy' => 'pa_vylet-diska',
			      'field' => 'slug',
			      'terms' => $vylet_diska,
			      'operator' => 'IN',
			    ),
		  )
		);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	}
	?>
</ul>
<?php get_footer(); 
//posadochnyj-diametr 14
//attribute="shirina-shiny" filter="185"
?>