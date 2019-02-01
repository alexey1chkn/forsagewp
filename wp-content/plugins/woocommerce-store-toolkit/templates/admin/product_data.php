<?php if( !empty( $post_meta ) ) { ?>
<table class="widefat striped" style="font-family:monospace; text-align:left; width:100%;">
	<tbody>

	<?php foreach( $post_meta as $meta_name => $meta_value ) { ?>
<?php
		if( count( maybe_unserialize( $meta_value ) ) == 1 )
			$meta_value = $meta_value[0];
		$meta_value = maybe_unserialize( $meta_value );
?>
		<?php if( is_array( $meta_value ) || is_object( $meta_value ) ) { ?>

		<tr>
			<th colspan="3"><?php echo $meta_name; ?></th> 
		</tr>
			<?php foreach( $meta_value as $inner_meta_name => $inner_meta_value ) { ?>
<?php
				if( is_array( maybe_unserialize( $inner_meta_value ) ) && count( maybe_unserialize( $inner_meta_value ) ) == 1 )
					$inner_meta_value = $inner_meta_value[0];
				$inner_meta_value = maybe_unserialize( $inner_meta_value );
?>
				<?php if( is_array( $inner_meta_value ) || is_object( $inner_meta_value ) ) { ?>
		<tr>
			<th colspan="3"><?php echo $inner_meta_name; ?></th>
		</tr>
					<?php foreach( $inner_meta_value as $inner_meta_name => $inner_meta_value ) { ?>
		<tr>
			<th style="width:20%;">&raquo; <?php echo $inner_meta_name; ?></th>
			<td><?php echo ( is_array( $inner_meta_value ) || is_object( $inner_meta_value ) ? print_r( $inner_meta_value, true ) : $inner_meta_value ); ?></td>
			<td>&nbsp;</td>
		</tr>
					<?php } ?>

				<?php } else { ?>

		<tr>
			<th style="width:20%;">&raquo; <?php echo $inner_meta_name; ?></th>
			<td><?php echo ( is_array( $inner_meta_value ) || is_object( $inner_meta_value ) ? print_r( $inner_meta_value, true ) : $inner_meta_value ); ?></td>
			<td>&nbsp;</td>
		</tr>

				<?php } ?>
			<?php } ?>

		<?php } else { ?>

		<tr>
			<th style="width:20%;"><?php echo $meta_name; ?></th>
			<td><?php echo ( is_array( $meta_value ) || is_object( $meta_value ) ? print_r( $meta_value, true ) : $meta_value ); ?></td>
			<td class="actions"><?php do_action( 'woo_st_product_data_actions', $post->ID, $meta_name ); ?></td>
		</tr>

		<?php } ?>
	<?php } ?>
	</tbody>

</table>
<?php } else { ?>
<p>No custom Post meta is associated with this Product.</p>
<?php } ?>