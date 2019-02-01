<?php if( !empty( $orders ) ) { ?>
<ul>
	<?php foreach( $orders as $order ) { ?>
	<li><a href="<?php echo add_query_arg( 'post', $order ); ?>"><?php echo sprintf( '#%s', $order ); ?></a></li>
	<?php } ?>
</ul>
<p class="description">* Orders matched by <code><?php echo $matching; ?></code></p>
<?php } else { ?>
No other Orders were found.
<?php } ?>