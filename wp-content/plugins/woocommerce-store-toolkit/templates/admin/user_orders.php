<h3><?php _e( 'User Orders', 'woocommerce-store-toolkit' ); ?></h3>
<table class="form-table">
	<tr>
		<th>
			<label><?php _e( 'Orders', 'woocommerce-store-toolkit' ); ?></label>
		</th>
		<td>

			<table class="wp-list-table widefat fixed striped order_data" cellspacing="0">

				<thead>
					<tr>
						<th class="manage-column"><?php _e( 'Order', 'woocommerce-store-toolkit' ); ?></th>
						<th class="manage-column"><?php _e( 'Date', 'woocommerce-store-toolkit' ); ?></th>
						<th scope="col" id="order_status" class="manage-column column-order_status"><?php _e( 'Status', 'woocommerce-store-toolkit' ); ?></th>
						<th class="manage-column"><?php _e( 'Total', 'woocommerce-store-toolkit' ); ?></th>
					</tr>
				</thead>

				<tbody class="the-list">
<?php if( !empty( $orders ) ) { ?>
	<?php foreach( $orders as $order ) { ?>
<?php
if( version_compare( WOOCOMMERCE_VERSION, '2.7', '>=' ) ) {
	// $order = wc_get_order( $order );
	$order = new WC_Order( $order );
	$order_id = trim( str_replace( '#', '', $order->get_order_number() ) );
	$payment_method_title = $order->get_payment_method_title();
	$order_date = $order->get_date_created();
	$order_status = $order->get_status();
	$order_total = $order->get_formatted_order_total();
} else {
	$order = new WC_Order();
	$order->populate( $order );
	$order_id = esc_attr( $order->get_order_number() );
	$order_data = (array)$order;
	$payment_method_title = $order->payment_method_title;
	$order_date = $order->order_date;
	$order_status = $order->post_status;
	$order_total = $order->get_formatted_order_total();
}
?>
					<tr class="type-shop_order status-<?php echo $order_status; ?>">
						<td><a href="<?php echo admin_url( 'post.php?post=' . absint( $order_id ) . '&action=edit' ); ?>" class="row-title"><strong>#<?php echo $order_id; ?></strong></a></td>
						<td>
<?php
if( '0000-00-00 00:00:00' == $order_date ) {
	$t_time = $h_time = __( 'Unpublished', 'woocommerce' );
} else {
	$t_time = get_date_from_gmt( $order_date, __( 'Y/m/d g:i:s A', 'woocommerce' ) );
	$h_time = get_date_from_gmt( $order_date, get_option( 'date_format' ) );
}
echo '<abbr title="' . esc_attr( $t_time ) . '">' . esc_html( $h_time ) . '</abbr>';
?>
						</td>
						<td class="order_status column-order_status" data-colname="<?php _e( 'Status', 'woocommerce-store-toolkit' ); ?>">
							<mark class="order-status status-<?php echo sanitize_title( $order_status ); ?> tips" data-tip="<?php echo wc_get_order_status_name( $order_status ); ?>" style="padding:0 0.8em;"><?php echo wc_get_order_status_name( $order_status ); ?></mark>
						</td>
						<td>
<?php
echo $order_total;

if( $payment_method_title )
	echo '<small class="meta">' . __( 'Via', 'woocommerce' ) . ' ' . esc_html( $payment_method_title ) . '</small>';
?>
						</td>
					</tr>
	<?php } ?>
<?php } else { ?>
					<tr>
						<td colspan="4"><?php _e( 'No Orders have been been saved', 'woocommerce-store-toolkit' ); ?></td>
					</tr>
<?php } ?>
				</tbody>


			</table>
<?php if( !empty( $orders ) ) { ?>
			<div class="tablenav top">
				<div class="tablenav-pages">
					<span class="displaying-num"><?php printf( __( '%d items', 'woocommerce-store-toolkit' ), $total_orders ); ?></span>
<?php if( $paged == 1 ) { ?>
					<span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
					<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
<?php } else { ?>
					<a class="first-page" href="<?php echo add_query_arg( array( 'paged' => NULL ) ); ?>"><span class="screen-reader-text">First page</span><span aria-hidden="true">&laquo;</span></a>
					<a class="prev-page" href="<?php echo add_query_arg( array( 'paged' => ( $paged - 1 ) ) ); ?>"><span class="screen-reader-text">Previous page</span><span aria-hidden="true">&lsaquo;</span></a>
<?php } ?>
					<span class="screen-reader-text">Current Page</span>
					<span id="table-paging" class="paging-input"><span class="tablenav-paging-text"><?php echo $paged; ?> of <span class="total-pages"><?php echo $max_page; ?></span></span></span>
<?php if( $paged == $max_page ) { ?>
					<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
					<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
<?php } else { ?>
					<a class="next-page" href="<?php echo add_query_arg( array( 'paged' => ( $paged + 1 ) ) ); ?>"><span class="screen-reader-text">Next page</span><span aria-hidden="true">&rsaquo;</span></a>
					<a class="last-page" href="<?php echo add_query_arg( array( 'paged' => $max_page ) ); ?>"><span class="screen-reader-text">Last page</span><span aria-hidden="true">&raquo;</span></a></span>
<?php } ?>
				</div>
				<!-- .tablenav-pages -->
			</div>
			<!-- .tablenav -->

<?php } ?>

		</td>
	</tr>
</table>