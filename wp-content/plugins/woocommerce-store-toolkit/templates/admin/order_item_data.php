<?php if( $order_items ) { ?>
<table class="widefat striped" style="font-family:monospace; text-align:left; width:100%;">
	<tbody>

	<?php foreach( $order_items as $order_item ) { ?>
		<tr>
			<th colspan="3">
				order_item_name: <?php echo $order_item->name; ?><br />
				order_item_type: <?php echo $order_item->type; ?>
			</th>
		</tr>

		<?php if( $order_item->meta ) { ?>
			<?php foreach( $order_item->meta as $meta_value ) { ?>
				<?php if( $meta_value->meta_key == '_tmcartepo_data' ) { ?>
<?php
$epos = maybe_unserialize( $meta_value->meta_value );
if( !is_array( $epos ) )
	continue;
?>
		<tr>
			<th>&raquo; <?php echo $meta_value->meta_key; ?></th> 
			<th colspan="2"><?php _e( 'Extra Product Options', 'woocommerce-store-toolkit' ); ?></th>
		</tr>
					<?php foreach( $epos as $epo_key => $epo ) { ?>
						<?php if( is_array( $epo ) ) { ?>
		<tr>
			<th>&raquo; &raquo; <?php echo $epo_key; ?></th>
			<th>
				name: <?php echo $epo['name']; ?><br />
				value: <?php echo $epo['value']; ?>
			</th>
			<td class="actions"><?php do_action( 'woo_st_order_item_extra_product_option_data_actions', $post->ID, $epo['name'] ); ?></td>
		</tr>
							<?php foreach( $epo as $epo_item_key => $epo_item ) { ?>
							
		<tr>
			<th style="width:20%;">&raquo; &raquo; &raquo; <?php echo $epo_item_key; ?></th>
			<td><?php echo ( is_array( $epo_item ) ? print_r( $epo_item, true ) : $epo_item ); ?></td>
			<td class="actions">&nbsp;</td>
		</tr>
							<?php } ?>
						<?php } else { ?>
		<tr>
			<th style="width:20%;">&raquo; &raquo; <?php echo $epo_key; ?></th>
			<td><?php print_r( $epo ); ?></td>
			<td class="actions">&nbsp;</td>
		</tr>
						<?php } ?>
					<?php } ?>
				<?php } else { ?>
		<tr>
			<th style="width:20%;">&raquo; <?php echo $meta_value->meta_key; ?></th>
			<td><?php echo $meta_value->meta_value; ?></td>
			<td class="actions"><?php do_action( 'woo_st_order_item_data_actions', $post->ID, $meta_value->meta_key ); ?></td>
		</tr>
				<?php } ?>
			<?php } ?>
		<?php } ?>

	<?php } ?>
	</tbody>
</table>
<?php } else { ?>
<p>No Order Items are associated with this Order.</p>
<?php } ?>