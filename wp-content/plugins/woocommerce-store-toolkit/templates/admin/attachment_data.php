<?php if( $post_meta ) { ?>
<table style="font-family:monospace; text-align:left; width:100%;">
	<tbody>
<!-- <ul class="ul-disc"> -->
	<?php foreach( $post_meta as $meta_name => $meta_value ) { ?>
<?php
		if( count( maybe_unserialize( $meta_value ) ) == 1 )
			$meta_value = $meta_value[0];
		$meta_value = maybe_unserialize( $meta_value );
?>
	<!-- <li> -->
		<?php if( is_array( $meta_value ) ) { ?>

		<tr>
			<th colspan="2"><?php echo $meta_name; ?></th> 
		</tr>
			<!-- <ul class="ul-disc"> -->
			<?php foreach( $meta_value as $inner_meta_name => $inner_meta_value ) { ?>
<?php
				if( is_array( maybe_unserialize( $inner_meta_value ) ) && count( maybe_unserialize( $inner_meta_value ) ) == 1 )
					$inner_meta_value = $inner_meta_value[0];
				$inner_meta_value = maybe_unserialize( $inner_meta_value );
?>
				<?php if( is_array( $inner_meta_value ) ) { ?>
		<tr>
			<th colspan="2"><?php echo $inner_meta_name; ?></th>
		</tr>
					<?php foreach( $inner_meta_value as $inner_meta_name => $inner_meta_value ) { ?>
		<tr>
			<th style="width:20%;">&raquo; <?php echo $inner_meta_name; ?></th>
			<td><?php print_r( $inner_meta_value ); ?></td>
		</tr>
					<?php } ?>

				<?php } else { ?>

		<tr>
			<th style="width:20%;">&raquo; <?php echo $inner_meta_name; ?></th>
			<td><?php echo $inner_meta_value; ?></td>
		</tr>

				<?php } ?>
				<!-- <li><strong class="list_inner"></strong>: <?php echo $inner_meta_value; ?></li> -->
			<?php } ?>
			<!-- </ul> -->

		<?php } else { ?>

		<tr>
			<th style="width:20%;"><?php echo $meta_name; ?></th>
			<td><?php echo $meta_value; ?></td>
		</tr>

		<?php } ?>
	<!-- </li> -->
	<?php } ?>
<!-- </ul> -->
	</tbody>
</table>
<?php } ?>