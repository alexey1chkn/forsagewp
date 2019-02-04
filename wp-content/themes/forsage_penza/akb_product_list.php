<?php 

/*
	*Template Name: akb_product_list
	*Template Post Type: post, page, product
*/

get_header();
// Ensure visibility.
?>


<div class="woocommerce columns-4">
	<ul class="products columns-4">
		<?php
			$_GET['page_my'] != NULL ? $page = $_GET['page_my'] : $page = 1;
			$args_array = array( 
			'post_type' => 'product', 
			'posts_per_page' => 12, 
			'product_cat' => 'akkumulyatory',
			'paged' => $page,

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