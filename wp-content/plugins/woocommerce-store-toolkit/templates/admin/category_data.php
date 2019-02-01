<tr class="form-field">
	<th scope="row" valign="top"><label><?php _e( 'Category Term meta', 'woocommerce-store-toolkit' ); ?></label></th>
	<td>

		<table class="widefat page fixed category_data">

			<thead>
				<tr>
					<th class="manage-column"><?php _e( 'Meta key', 'woocommerce-store-toolkit' ); ?></th>
					<th class="manage-column"><?php _e( 'Meta value', 'woocommerce-store-toolkit' ); ?></th>
					<th class="manage-column">&nbsp;</th>
				</tr>
			</thead>

			<tbody>
<?php if( !empty( $term_meta ) ) { ?>
	<?php foreach( $term_meta as $meta_name => $meta_value ) { ?>
<?php
		if( count( maybe_unserialize( $meta_value ) ) == 1 )
			$meta_value = $meta_value[0];
		$meta_value = maybe_unserialize( $meta_value );
?>
				<tr>
		<?php if( is_array( $meta_value ) || is_object( $meta_value ) ) { ?>
				<tr>
					<th colspan="2"><?php echo $meta_name; ?></th>
					<td class="actions"><?php do_action( 'woo_st_category_data_actions', $term->term_id, $meta_name ); ?></td>
				</tr>
			<?php foreach( $meta_value as $inner_meta_name => $inner_meta_value ) { ?>
				<tr>
					<th style="width:20%;">&raquo; <?php echo $inner_meta_name; ?></th>
					<td><?php echo ( is_array( $inner_meta_value ) || is_object( $inner_meta_value ) ? print_r( $inner_meta_value, true ) : $inner_meta_value ); ?></td>
				</tr>
			<?php } ?>
	<?php } else { ?>
					<td style="width:20%;"><?php echo $meta_name; ?></td>
					<td><?php echo ( is_array( $meta_value ) || is_object( $meta_value ) ? print_r( $meta_value, true ) : $meta_value ); ?></td>
					<td class="actions"><?php do_action( 'woo_st_category_data_actions', $term->term_id, $meta_name ); ?></td>
	<?php } ?>
				</tr>
	<?php } ?>
<?php } else { ?>
				<tr>
					<td colspan="2"><?php _e( 'No Term meta has been saved.', 'woocommerce-store-toolkit' ); ?></td>
				</tr>
<?php } ?>
			</tbody>

		</table>

	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label><?php _e( 'Category heirachy', 'woocommerce-store-toolkit' ); ?></label></th>
	<td>
		<?php echo $category_heirachy; ?><br />
	</tr>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label><?php _e( 'Category heirachy depth', 'woocommerce-store-toolkit' ); ?></label></th>
	<td>
		<?php printf( __( '%d level(s) deep', 'woocommerce-store-toolkit' ), $category_depth ); ?>
	</td>
</tr>
