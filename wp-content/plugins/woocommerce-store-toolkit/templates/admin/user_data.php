<table class="form-table">
	<tr>
		<th>
			<label><?php _e( 'User Meta', 'woocommerce-store-toolkit' ); ?></label>
		</th>
		<td>

			<table class="widefat page fixed user_data">

				<thead>
					<tr>
						<th class="manage-column"><?php _e( 'Meta key', 'woocommerce-store-toolkit' ); ?></th>
						<th class="manage-column"><?php _e( 'Meta value', 'woocommerce-store-toolkit' ); ?></th>
						<th class="manage-column"><?php _e( 'Actions', 'woocommerce-store-toolkit' ); ?></th>
					</tr>
				</thead>

				<tbody>
<?php if( !empty( $user_meta ) ) { ?>
	<?php foreach( $user_meta as $meta_name => $meta_value ) { ?>
<?php
		if( count( maybe_unserialize( $meta_value ) ) == 1 )
			$meta_value = $meta_value[0];
		$meta_value = maybe_unserialize( $meta_value );
?>
					<tr>
		<?php if( is_array( $meta_value ) || is_object( $meta_value ) ) { ?>
					<tr>
						<th colspan="3"><?php echo $meta_name; ?></th> 
					</tr>
			<?php foreach( $meta_value as $inner_meta_name => $inner_meta_value ) { ?>
					<tr>
						<th style="width:20%;">&raquo; <?php echo $inner_meta_name; ?></th>
						<td><?php echo ( is_array( $inner_meta_value ) || is_object( $inner_meta_value ) ? print_r( $inner_meta_value, true ) : $inner_meta_value ); ?></td>
						<td>&nbsp;</td>
					</tr>
			<?php } ?>
		<?php } else { ?>
						<td><?php echo $meta_name; ?></td>
						<td><?php echo ( is_array( $meta_value ) || is_object( $meta_value ) ? print_r( $meta_value, true ) : $meta_value ); ?></td>
						<td class="actions"><?php do_action( 'woo_st_user_data_actions', $user_id, $meta_name ); ?></td>
		<?php } ?>
					</tr>
	<?php } ?>
<?php } else { ?>
					<tr>
						<td colspan="3"><?php _e( 'No custom User meta is associated with this User.', 'woocommerce-store-toolkit' ); ?></td>
					</tr>
<?php } ?>
				</tbody>

			</table>

		</td>
	</tr>
</table>