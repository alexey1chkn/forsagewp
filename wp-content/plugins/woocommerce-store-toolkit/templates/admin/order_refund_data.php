<?php if( $refunds ) { ?>
<table class="widefat striped" style="font-family:monospace; text-align:left; width:100%;">
	<tbody>

	<?php foreach( $refunds as $refund ) { ?>
		<tr>
			<th colspan="3">
				refund_id: <?php echo $refund->ID; ?><br />
				refund_name: <?php echo $refund->post_title; ?><br />
				refund_status: <?php echo $refund->post_status; ?>
			</th>
		</tr>
		<?php if( $refund->meta ) { ?>
			<?php foreach( $refund->meta as $meta_key => $meta_value ) { ?>
		<tr>
			<th style="width:20%;">&raquo; <?php echo $meta_key; ?></th>
			<td><?php echo $meta_value[0]; ?></td>
			<td class="actions"><?php do_action( 'woo_st_order_refund_data_actions', $post->ID, $meta_key ); ?></td>
		</tr>
			<?php } ?>
		<?php } ?>

	<?php } ?>
	</tbody>
</table>
<?php } else { ?>
<p>No refund items are associated with this Order.</p>
<?php } ?>