<?php 

/*
	*Template Name: cart_temp
	*Template Post Type: post, page
*/

get_header();

?>

<div class="wrapper">
	<div class="cart_wrapper">
		<h1>Корзина</h1>
		<div class="row">
			<div class="col-12 col-lg-8">
				<?php 
					if (WC () -> cart-> get_cart_contents_count () == 0) : //Если корзина пуста
					  do_action( 'woocommerce_cart_is_empty' );
						if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
							<p class="return-to-shop">
								<a class="button wc-backward" href="/">
									<?php esc_html_e( 'Return to shop', 'woocommerce' ); ?>
								</a>
							</p>
						</div>
						<?php endif; 
					else: //Если есть товары
						include "woocommerce/cart/cart.php";
					?>
			</div>
			<div class="col-12 col-lg-4">
						
			<div class="cart-collaterals">
				<h3>Ваш заказ:</h3>
				<p>Всего товаров: <?php echo sprintf($woocommerce->cart->cart_contents_count); ?></p>
				<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action( 'woocommerce_cart_collaterals' );
				?>
			</div>
		
		<?php do_action( 'woocommerce_after_cart' ); ?>
			</div>
			<?php
		endif;?>
		</div>
		</div>
	</div>


<?php get_footer(); ?>