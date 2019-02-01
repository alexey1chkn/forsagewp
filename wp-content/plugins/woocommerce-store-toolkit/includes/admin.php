<?php
// Display admin notice on screen load
function woo_st_admin_notice( $message = '', $priority = 'updated', $screen = '' ) {

	if( $priority == false || $priority == '' )
		$priority = 'updated';
	if( $message <> '' ) {
		ob_start();
		woo_st_admin_notice_html( $message, $priority, $screen );
		$output = ob_get_contents();
		ob_end_clean();
		// Check if an existing notice is already in queue
		$existing_notice = get_transient( WOO_ST_PREFIX . '_notice' );
		if( $existing_notice !== false ) {
			$existing_notice = base64_decode( $existing_notice );
			$output = $existing_notice . $output;
		}
		set_transient( WOO_ST_PREFIX . '_notice', base64_encode( $output ), MINUTE_IN_SECONDS );
		add_action( 'admin_notices', 'woo_st_admin_notice_print' );
	}

}

// HTML template for admin notice
function woo_st_admin_notice_html( $message = '', $priority = 'updated', $screen = '' ) {

	// Display admin notice on specific screen
	if( !empty( $screen ) ) {

		global $pagenow;

		if( is_array( $screen ) ) {
			if( in_array( $pagenow, $screen ) == false )
				return;
		} else {
			if( $pagenow <> $screen )
				return;
		}

	} ?>
<div id="message" class="<?php echo $priority; ?>">
	<p><?php echo $message; ?></p>
</div>
<?php

}

// Grabs the WordPress transient that holds the admin notice and prints it
function woo_st_admin_notice_print() {

	$output = get_transient( WOO_ST_PREFIX . '_notice' );
	if( $output !== false ) {
		$output = base64_decode( $output );
		echo $output;
		delete_transient( WOO_ST_PREFIX . '_notice' );
	
	}

}

// Add Store Toolkit, Docs links to the Plugins screen
function woo_st_add_settings_link( $links, $file ) {

	$this_plugin = plugin_basename( WOO_ST_RELPATH );
	if( $file == $this_plugin ) {
		$docs_url = 'http://www.visser.com.au/docs/';
		$docs_link = sprintf( '<a href="%s" target="_blank">' . __( 'Docs', 'woocommerce-store-toolkit' ) . '</a>', $docs_url );
		$settings_link = sprintf( '<a href="%s">' . __( 'Settings', 'woocommerce-store-toolkit' ) . '</a>', esc_url( add_query_arg( 'page', 'woo_st', 'admin.php' ) ) );
		array_unshift( $links, $docs_link );
		array_unshift( $links, $settings_link );
	}
	return $links;

}
add_filter( 'plugin_action_links', 'woo_st_add_settings_link', 10, 2 );

// Load CSS and jQuery scripts for Store Toolkit screen
function woo_st_enqueue_scripts( $hook ) {

	// Simple check that WooCommerce is activated
	if( class_exists( 'WooCommerce' ) ) {

		global $woocommerce;

		wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css' );

	}

	if( $hook == 'woocommerce_page_woo_st' ) {
		// Time Picker Addon
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-datepicker', plugins_url( '/templates/admin/jquery-ui-datepicker.css', WOO_ST_RELPATH ) );
/*
		wp_enqueue_script( 'jquery-ui-timepicker', plugins_url( '/js/jquery.timepicker.js', WOO_ST_RELPATH ) );
		wp_enqueue_style( 'jquery-ui-timepicker', plugins_url( '/templates/admin/jquery-ui-timepicker.css', WOO_ST_RELPATH ) );
*/
	}

	// Settings
	$pages = array( 'woocommerce_page_woo_st', 'edit-tags.php', 'user-edit.php', 'profile.php', 'index.php', 'post.php' );
	if( in_array( $hook, $pages ) ) {
		wp_enqueue_style( 'woo_st_styles', plugins_url( '/templates/admin/toolkit.css', WOO_ST_RELPATH ) );
		wp_enqueue_script( 'woo_st_scripts', plugins_url( '/templates/admin/toolkit.js', WOO_ST_RELPATH ), array( 'jquery' ) );
	}

}
add_action( 'admin_enqueue_scripts', 'woo_st_enqueue_scripts' );

// Add WooCommerce store details to WordPress Administration Dashboard
function woo_st_add_dashboard_widgets() {

	// Simple check that WooCommerce is activated
	if( class_exists( 'WooCommerce' ) ) {

		$user_capability = apply_filters( 'woo_st_dashboard_widgets', 'manage_options' );

		// Check for manage_options User Capability
		if( current_user_can( $user_capability ) ) {
			if( apply_filters( 'woo_st_dashboard_right_now', true ) )
				wp_add_dashboard_widget( 'woo_st-dashboard_right_now', __( 'Right Now in Store', 'woocommerce-store-toolkit' ), 'woo_st_dashboard_right_now' );
			if( apply_filters( 'woo_st_dashboard_sales_summary', true ) ) {
				if( function_exists( 'wc_price' ) )
					wp_add_dashboard_widget( 'woo_st-dashboard_sales', __( 'Sales Summary', 'woocommerce-store-toolkit' ), 'woo_st_dashboard_sales_summary' );
			}
		}

	}

}
add_action( 'wp_dashboard_setup', 'woo_st_add_dashboard_widgets' );

function woo_st_dashboard_right_now() {

	$order_count = array();
	$order_statuses = woo_st_get_order_statuses();
	if( !empty( $order_statuses ) && !is_wp_error( $order_statuses ) ) {
		foreach( $order_statuses as $order_status ) {
			switch( $order_status->term_id ) {

				case 'wc-pending':
					$order_count['pending'] = $order_status->count;
					break;

				case 'wc-on-hold':
					$order_count['onhold'] = $order_status->count;
					break;

				case 'wc-processing':
					$order_count['processing'] = $order_status->count;
					break;

				case 'wc-completed':
					$order_count['completed'] = $order_status->count;
					break;

				case 'wc-cancelled':
					$order_count['cancelled'] = $order_status->count;
					break;

				case 'wc-refunded':
					$order_count['refunded'] = $order_status->count;
					break;

				case 'wc-failed':
					$order_count['failed'] = $order_status->count;
					break;

			}
		}
	} else if( is_wp_error( $order_statuses ) ) {
		error_log( sprintf( '[store-toolkit] Warning: Deprecation warning in woo_st_dashboard_right_now(): %s', $order_statuses->get_error_message() ) );
	}

	$template = 'dashboard_right_now.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_dashboard_sales_summary() {

	global $wpdb;

	// Set defaults
	$sales_today = (float)0;
	$sales_yesterday = (float)0;
	$sales_week = (float)0;
	$sales_last_week = (float)0;
	$sales_month = (float)0;
	$sales_last_month = (float)0;

	$post_type = 'shop_order';
	$order_status = implode( "','", apply_filters( 'woo_st_sales_order_status', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) );

	// Get totals all time
	if( false === ( $sales_all_time = get_transient( WOO_ST_PREFIX . '_sales_all_time' ) ) ) {
		$sales_all_time = $wpdb->get_var( "SELECT SUM(meta.meta_value) AS total_sales FROM {$wpdb->posts} AS posts
	LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
	WHERE meta.meta_key = '_order_total'
	AND posts.post_type = 'shop_order'
	AND posts.post_status IN ( '" . $order_status . "' )
	" );
		set_transient( WOO_ST_PREFIX . '_sales_all_time', $sales_all_time, HOUR_IN_SECONDS );
	}

	// Get totals for last month

	// Get totals for this month

	// Get totals for last week

	// Get totals for this week
	if( false === ( $sales_week = get_transient( WOO_ST_PREFIX . '_sales_week' ) ) ) {
		$sales_week = $wpdb->get_var( "SELECT SUM(meta.meta_value) AS total_sales FROM {$wpdb->posts} AS posts
LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
WHERE meta.meta_key = '_order_total'
AND posts.post_type = 'shop_order'
AND posts.post_status IN ( '" . $order_status . "' )
AND posts.post_date > '" . date( "Y-m-d 00:00:00", strtotime( 'last Monday' ) ) . "' 
AND posts.post_date < '" . date( "Y-m-d 23:59:59", current_time( 'timestamp' ) ) . "'
" );
		set_transient( WOO_ST_PREFIX . '_sales_week', $sales_week, HOUR_IN_SECONDS );
	}

	// Get totals for yesterday
	if( false === ( $sales_yesterday = get_transient( WOO_ST_PREFIX . '_sales_yesterday' ) ) ) {
		$sales_yesterday = $wpdb->get_var( "SELECT SUM(meta.meta_value) AS total_sales FROM {$wpdb->posts} AS posts
LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
WHERE meta.meta_key = '_order_total'
AND posts.post_type = 'shop_order'
AND posts.post_status IN ( '" . $order_status . "' )
AND posts.post_date > '" . date( "Y-m-d 00:00:00", strtotime( '-1 days' ) ) . "' 
AND posts.post_date < '" . date( "Y-m-d 23:59:59", strtotime( '-1 days' ) ) . "'
" );
		set_transient( WOO_ST_PREFIX . '_sales_yesterday', $sales_yesterday, HOUR_IN_SECONDS );
	}

	// Get totals for today
	if( false === ( $sales_today = get_transient( WOO_ST_PREFIX . '_sales_today' ) ) ) {
		$sales_today = $wpdb->get_var( "SELECT SUM(meta.meta_value) AS total_sales FROM {$wpdb->posts} AS posts
LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
WHERE meta.meta_key = '_order_total'
AND posts.post_type = 'shop_order'
AND posts.post_status IN ( '" . $order_status . "' )
AND posts.post_date > '" . date( "Y-m-d 00:00:00", current_time( 'timestamp' ) ) . "' 
AND posts.post_date < '" . date( "Y-m-d 23:59:59", current_time( 'timestamp' ) ) . "'
" );
		set_transient( WOO_ST_PREFIX . '_sales_today', $sales_today, HOUR_IN_SECONDS );
	}

	$template = 'dashboard_sales_summary.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_permanent_delete_link( $actions, $post ) {

	// Check that the User can manage_woocommerce
	if( !current_user_can( apply_filters( 'woo_st_permanent_delete_capability', 'manage_woocommerce' ) ) ) {
		return $actions;
	}

	// Limit to the Product CPT screen
	if( $post->post_type != 'product' ) {
		return $actions;
	}

	// Do not show for the Trash screen
	$post_status = ( isset( $_REQUEST['post_status'] ) ? $_REQUEST['post_status'] : false );
	if( !empty( $post_status ) ) {
		if( $post_status == 'trash' )
			return $actions;
	}

	$actions['permanent_delete'] = '<span class="delete"><a href="' . wp_nonce_url( admin_url( 'edit.php?post_type=product&ids=' . $post->ID . '&action=permanent_delete_product' ), 'woo_st-permanent_delete_' . $post->ID ) . '" title="' . esc_attr__( 'Permanently delete this product', 'woocommerce-store-toolkit' ) . '" rel="permalink">' .  __( 'Delete Permanently', 'woocommerce' ) . '</a></span>';

	return $actions;

}
add_filter( 'post_row_actions', 'woo_st_permanent_delete_link', 10, 2 );
add_filter( 'page_row_actions', 'woo_st_permanent_delete_link', 10, 2 );

function woo_st_permanent_delete_product_action() {

	if ( empty( $_REQUEST['ids'] ) ) {
		wp_die( __( 'No product to permanently delete has been supplied!', 'woocommerce-store-toolkit' ) );
	}

	// Get the original page
	$id = isset( $_REQUEST['ids'] ) ? absint( $_REQUEST['ids'] ) : '';

	check_admin_referer( 'woo_st-permanent_delete_' . $id );

	// Delete the Post
	$deleted = 0;
	if ( !empty( $id ) ) {
		wp_delete_post( $id, true );
		$deleted++;
		$post_type = 'product';
		$url = add_query_arg( array( 'post_type' => $post_type, 'action' => null, '_wpnonce' => null, 'ids' => $id, 'deleted' => $deleted ), 'edit.php' );
		wp_redirect( $url );
		exit();
	} else {
		wp_die( __( 'Permanently delete Product failed, could not find original product:', 'woocommerce' ) . ' ' . $id );
	}

}
add_action( 'admin_action_permanent_delete_product', 'woo_st_permanent_delete_product_action' );

function woo_st_permanent_delete_product_bulk_admin_footer() {

	global $post_type;

	// Check that the User can manage_woocommerce
	if( !current_user_can( apply_filters( 'woo_st_permanent_delete_capability', 'manage_woocommerce' ) ) ) {
		return;
	}

	$post_status = ( isset( $_REQUEST['post_status'] ) ? $_REQUEST['post_status'] : false );
	if( $post_type == 'product' && ( $post_status <> 'trash' ) ) { ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('<option>').val('permanent_delete').text('<?php _e( 'Delete Permanently' )?>').appendTo("select[name='action']");
		jQuery('<option>').val('permanent_delete').text('<?php _e( 'Delete Permanently' )?>').appendTo("select[name='action2']");
	});
</script>
<?php
	}

}
add_action( 'admin_footer-edit.php', 'woo_st_permanent_delete_product_bulk_admin_footer' );

function woo_st_permanent_delete_product_bulk_action() {

	$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
	$action = $wp_list_table->current_action();

	switch( $action ) {

		case 'permanent_delete':

			check_admin_referer( 'bulk-posts' );

			if ( empty( $_REQUEST['post'] ) ) {
				wp_die( __( 'No products to permanently delete have been supplied!', 'woocommerce-store-toolkit' ) );
			}

			$post_ids = ( isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : '' );

			$deleted = 0;
			foreach( $post_ids as $post_id ) {
				wp_delete_post( $post_id, true );
				$deleted++;
			}
			$post_type = 'product';
			$url = add_query_arg( array( 'post_type' => $post_type, 'action' => null, '_wpnonce' => null, 'deleted' => $deleted, 'ids' => join( ',', $post_ids ) ), 'edit.php' );
			wp_redirect( $url );
			exit();
			break;

		default:
			return;
			break;

	}

}
add_action( 'load-edit.php', 'woo_st_permanent_delete_product_bulk_action' );

function woo_st_restrict_manage_posts() {

	global $typenow;

	if( !current_user_can( 'manage_options' ) )
		return;

	if( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ) ) ) {
		woo_st_shop_order_filters();
	}

}
add_action( 'restrict_manage_posts', 'woo_st_restrict_manage_posts' );

function woo_st_shop_order_filters() {

	if( apply_filters( 'woo_st_shop_order_billing_country_filter', true ) )
		woo_st_shop_order_billing_country_filter();
	if( apply_filters( 'woo_st_shop_order_shipping_country_filter', true ) )
		woo_st_shop_order_shipping_country_filter();
	if( apply_filters( 'woo_st_shop_order_payment_gateway_filter', true ) )
		woo_st_shop_order_payment_gateway_filter();

}

function woo_st_shop_order_billing_country_filter() {

	$wc_countries = ( class_exists( 'WC_Countries' ) ? new WC_Countries() : false );
	$countries = ( method_exists( $wc_countries, 'get_countries' ) ? $wc_countries->get_countries() : false );
	if( empty( $countries ) )
		return;

	$selected = ( isset( $_GET['_customer_billing_country'] ) ? sanitize_text_field( $_GET['_customer_billing_country'] ) : false );
?>
<select name="_customer_billing_country">
	<option value=""><?php _e( 'All billing countries', 'woocommerce-store-toolkit' ); ?></option>
<?php foreach( $countries as $prefix => $country ) { ?>
	<option value="<?php echo $prefix; ?>"<?php selected( $prefix, $selected ); ?>><?php echo $country; ?></option>
<?php } ?>
</select>
<?php

}

function woo_st_shop_order_shipping_country_filter() {

	$wc_countries = ( class_exists( 'WC_Countries' ) ? new WC_Countries() : false );
	$countries = ( method_exists( $wc_countries, 'get_countries' ) ? $wc_countries->get_countries() : false );
	if( empty( $countries ) )
		return;

	$selected = ( isset( $_GET['_customer_shipping_country'] ) ? sanitize_text_field( $_GET['_customer_shipping_country'] ) : false );
?>
<select name="_customer_shipping_country">
	<option value=""><?php _e( 'All shipping countries', 'woocommerce-store-toolkit' ); ?></option>
<?php foreach( $countries as $prefix => $country ) { ?>
	<option value="<?php echo $prefix; ?>"<?php selected( $prefix, $selected ); ?>><?php echo $country; ?></option>
<?php } ?>
</select>
<?php

}

function woo_st_shop_order_payment_gateway_filter() {

	$payment_gateways = woo_st_get_payment_gateways();
	if( empty( $payment_gateways ) )
		return;

	$selected = ( isset( $_GET['_customer_payment_method'] ) ? sanitize_text_field( $_GET['_customer_payment_method'] ) : false );
?>
<select name="_customer_payment_method">
	<option value=""><?php _e( 'All payment methods', 'woocommerce-store-toolkit' ); ?></option>
<?php foreach( $payment_gateways as $payment_gateway ) { ?>
	<option value="<?php echo $payment_gateway->id; ?>"<?php selected( $payment_gateway->id, $selected ); ?>><?php echo ucfirst( woo_st_format_payment_gateway( $payment_gateway->id ) ); ?></option>
<?php } ?>
</select>
<?php

}

function woo_st_request_query( $vars ) {

	global $typenow;

	if( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ) ) ) {
		// Billing country
		if( isset( $_GET['_customer_billing_country'] ) && $_GET['_customer_billing_country'] != '' ) {
			$vars['meta_query'] = array(
				array(
					'key' => '_billing_country',
					'value' => sanitize_text_field( $_GET['_customer_billing_country'] ),
					'compare' => '='
				)
			);
		}

		// Shipping country
		if( isset( $_GET['_customer_shipping_country'] ) && $_GET['_customer_shipping_country'] != '' ) {
			$vars['meta_query'] = array(
				array(
					'key' => '_shipping_country',
					'value' => sanitize_text_field( $_GET['_customer_shipping_country'] ),
					'compare' => '='
				)
			);
		}

		// Payment method
		if( isset( $_GET['_customer_payment_method'] ) && $_GET['_customer_payment_method'] != '' ) {
			$vars['meta_query'] = array(
				array(
					'key' => '_payment_method',
					'value' => sanitize_text_field( $_GET['_customer_payment_method'] ),
					'compare' => '='
				)
			);
		}
	}

	return $vars;

}
add_filter( 'request', 'woo_st_request_query' );

function woo_st_get_custom_post_type_ids( $post_type = false ) {
	
	if( empty( $post_type ) )
		return;

	$posts_per_page = apply_filters( 'woo_st_get_custom_post_type_ids_posts_per_page', 10 );
	$args = array(
		'post_type' => $post_type,
		'post_status' => 'any',
		'posts_per_page' => $posts_per_page,
		'fields' => 'ids',
		'orderby' => 'rand'
	);
	$post_ids = new WP_Query( $args );
	if( !empty( $post_ids->posts ) ) {
		return $post_ids->posts;
	}

}

function woo_st_get_user_orders( $user_id = 0, $args = array(), $return = 'ids' ) {

	if( empty( $user_id ) )
		return;

	$defaults = array(
		'numberposts' => -1,
		'meta_key'    => '_customer_user',
		'meta_value'  => $user_id,
		'post_type'   => ( function_exists( 'wc_get_order_types' ) ? wc_get_order_types() : false ),
		'post_status' => ( function_exists( 'wc_get_order_statuses' ) ? array_keys( wc_get_order_statuses() ) : false ),
		'fields' => 'ids'
	);
	$args = wp_parse_args( $args, $defaults );
	$order_ids = new WP_Query( $args );
	if( $return == 'found_posts' )
		return $order_ids->found_posts;
	$orders = ( !empty( $order_ids->posts ) ? $order_ids->posts : false );

	return $orders;

}

function woo_st_get_order_refunds( $order_id = 0, $args = array() ) {

	if( empty( $order_id ) )
		return;

	$post_type = 'shop_order_refund';
	$defaults = array(
		'numberposts' => -1,
		'post_type'   => $post_type,
		'post_status' => ( function_exists( 'wc_get_order_statuses' ) ? array_keys( wc_get_order_statuses() ) : false ),
		'post_parent' => $order_id
	);
	$args = wp_parse_args( $args, $defaults );

	$refunds = get_posts( $args );
	if( !empty( $refunds ) ) {
		foreach( $refunds as $key => $refund ) {
			$refunds[$key]->meta = get_post_custom( $refund->ID );
		}
	}
	return $refunds;

}

function woo_st_get_payment_gateways() {

	global $woocommerce;

	$output = false;

	// Test that payment gateways exist with WooCommerce 1.6 compatibility
	if( version_compare( $woocommerce->version, '2.0.0', '<' ) ) {
		if( $woocommerce->payment_gateways )
			$output = $woocommerce->payment_gateways->payment_gateways;
	} else {
		if( $woocommerce->payment_gateways() )
			$output = $woocommerce->payment_gateways()->payment_gateways();
	}
	// Add Other to list of payment gateways
	$output['other'] = (object)array(
		'id' => 'other',
		'title' => __( 'Other', 'woocommerce-store-toolkit' ),
		'method_title' => __( 'Other', 'woocommerce-store-toolkit' )
	);
	return $output;

}

function woo_st_format_payment_gateway( $payment_id = '' ) {

	$output = $payment_id;
	if( empty( $payment_id ) )
		$output = __( 'N/A', 'woocommerce-store-toolkit' );
	return $output;

}

function woo_st_admin_footer_text( $footer_text = '' ) {

	if( !current_user_can( 'manage_options' ) )
		return $footer_text;

	if( apply_filters( 'woo_st_admin_footer_text', true ) )
		$footer_text .= ' | ' . __( 'Stopwatch', 'woocommerce-store-toolkit' ) . ': ' . timer_stop( 0, 3 ) . ' ' . __( 'seconds', 'woocommerce-store-toolkit' );
	return $footer_text;

}

// HTML active class for the currently selected tab on the Store Toolkit screen
function woo_st_admin_active_tab( $tab_name = null, $tab = null ) {

	if( isset( $_GET['tab'] ) && !$tab )
		$tab = $_GET['tab'];
	else
		$tab = 'overview';

	$output = '';
	if( isset( $tab_name ) && $tab_name ) {
		if( $tab_name == $tab )
			$output = ' nav-tab-active';
	}
	echo $output;

}

// HTML template for each tab on the Store Toolkit screen
function woo_st_tab_template( $tab = '' ) {

	if( !$tab )
		$tab = 'overview';

	switch( $tab ) {

		case 'nuke':

			// Check if a previous nuke failed mid-drop
			$in_progress = woo_st_get_option( 'in_progress', false );
			if( !empty( $in_progress ) ) {
				$message = sprintf( __( 'It looks like a previous nuke failed to clear that dataset, this is common in large catalogues and is likely due to WordPress hitting a memory limit or server timeout. Don\'t stress, <a href="%s">retry %s nuke?</a>', 'woocommerce-store-toolkit' ), esc_url( add_query_arg( array( 'action' => 'nuke', 'dataset' => $in_progress ) ) ), ucfirst( $in_progress ) );
				woo_st_admin_notice_html( $message, 'error' );
				delete_option( WOO_ST_PREFIX . '_in_progress' );
			}

			$products = woo_st_return_count( 'product' );
			$images = woo_st_return_count( 'product_image' );
			$tags = woo_st_return_count( 'product_tag' );
			$categories = woo_st_return_count( 'product_category' );
			if( !empty( $categories ) ) {
				$term_taxonomy = 'product_cat';
				$args = array(
					'hide_empty' => 0
				);
				$categories_data = get_terms( $term_taxonomy, $args );
			}
			$orders = woo_st_return_count( 'order' );
			if( !empty( $orders ) ) {
				$order_statuses = woo_st_get_order_statuses();
				$orders_date = false;
				$date_format = 'd/m/Y';
				$orders_date_from = woo_st_get_order_first_date( $date_format );
				$orders_date_to = date( $date_format );
			}
			$tax_rates = woo_st_return_count( 'tax_rate' );
			$download_permissions = woo_st_return_count( 'download_permission' );
			$coupons = woo_st_return_count( 'coupon' );
			$shipping_classes = woo_st_return_count( 'shipping_class' );
			$woocommerce_logs = woo_st_return_count( 'woocommerce_log' );
			$attributes = woo_st_return_count( 'attribute' );

			$brands = woo_st_return_count( 'product_brand' );
			$vendors = woo_st_return_count( 'product_vendor' );
			$store_exports_csv = woo_st_return_count( 'store_export_csv' );
			$store_exports_tsv = woo_st_return_count( 'store_export_tsv' );
			$store_exports_xls = woo_st_return_count( 'store_export_xls' );
			$store_exports_xlsx = woo_st_return_count( 'store_export_xlsx' );
			$store_exports_xml = woo_st_return_count( 'store_export_xml' );
			$store_exports_rss = woo_st_return_count( 'store_export_rss' );
			$credit_cards = woo_st_return_count( 'credit_card' );
			$google_product_feed = woo_st_return_count( 'google_product_feed' );

			$posts = woo_st_return_count( 'post' );
			$post_categories = woo_st_return_count( 'post_category' );
			$post_tags = woo_st_return_count( 'post_tag' );
			$links = woo_st_return_count( 'link' );
			$comments = woo_st_return_count( 'comment' );
			$media_images = woo_st_return_count( 'media_image' );

			$show_table = false;
			if( $products || $images || $tags || $categories || $orders || $store_exports_csv || $credit_cards || $attributes )
				$show_table = true;
			break;

		case 'post_types':
			$args = array();
			$output = 'objects';
			$post_types = get_post_types( $args, $output );
			$post_counts = array();
			$post_ids = array();
			if( !empty( $post_types ) ) {
				foreach( $post_types as $key => $post_type ) {
					$count_posts = wp_count_posts( $key );
					$post_counts[$key] = array_sum( (array)$count_posts );
					$post_ids[$key] = woo_st_get_custom_post_type_ids( $key );
				}
			}
			break;

		case 'tools':
			$autocomplete_order = get_option( WOO_ST_PREFIX . '_autocomplete_order', 0 );
			$unlock_variations = get_option( WOO_ST_PREFIX . '_unlock_variations', 0 );
			break;

		case 'settings':
			$troubleshooting_url = '';
			$enable_cron = woo_st_get_option( 'enable_cron', 0 );
			$secret_key = woo_st_get_option( 'secret_key', '' );
			break;

	}
	if( $tab ) {
		if( file_exists( WOO_ST_PATH . 'templates/admin/tabs-' . $tab . '.php' ) ) {
			include_once( WOO_ST_PATH . 'templates/admin/tabs-' . $tab . '.php' );
		} else {
			$message = sprintf( __( 'We couldn\'t load the export template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), 'tabs-' . $tab . '.php', WOO_CD_PATH . 'templates/admin/...' );
			woo_st_admin_notice_html( $message, 'error' );
			ob_start(); ?>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php
			ob_end_flush();
		}
	}

}

function woo_st_add_data_meta_boxes( $post_type, $post = '' ) {

	if( $post->post_status == 'auto-draft' )
		return;

	// Product
	$post_type = 'product';
	if( apply_filters( 'woo_st_product_data_meta_box', true ) )
		add_meta_box( 'woo-product-post_data', __( 'Product Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_product_data_meta_box', $post_type, 'normal', 'default' );
	$post_type = 'product_variation';
	if( apply_filters( 'woo_st_product_data_meta_box', true ) )
		add_meta_box( 'woo-product-post_data', __( 'Product Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_product_data_meta_box', $post_type, 'normal', 'default' );

	// Order
	$post_type = 'shop_order';
	if( apply_filters( 'woo_st_order_data_meta_box', true ) )
		add_meta_box( 'woo-order-post_data', __( 'Order Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_order_data_meta_box', $post_type, 'normal', 'default' );
	if( apply_filters( 'woo_st_order_items_data_meta_box', true ) )
		add_meta_box( 'woo-order-post_item', __( 'Order Items Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_order_items_data_meta_box', $post_type, 'normal', 'default' );
	if( apply_filters( 'woo_st_order_refunds_data_meta_box', true ) )
		add_meta_box( 'woo-order-post_refund', __( 'Refunds Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_order_refunds_data_meta_box', $post_type, 'normal', 'default' );
	
	if( apply_filters( 'woo_st_order_related_orders_meta_box', false ) )
		add_meta_box( 'woo-order-related_orders', __( 'Related Orders', 'woocommerce-store-toolkit' ), 'woo_st_order_related_orders_meta_box', $post_type, 'side', 'default' );

	// Coupon
	$post_type = 'shop_coupon';
	if( apply_filters( 'woo_st_coupon_data_meta_box', true ) )
		add_meta_box( 'woo-coupon-post_data', __( 'Coupon Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_coupon_data_meta_box', $post_type, 'normal', 'default' );

	// Attachment
	$post_type = 'attachment';
	if( apply_filters( 'woo_st_attachment_data_meta_box', true ) )
		add_meta_box( 'attachment-post_data', __( 'Attachment Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_attachment_data_meta_box', $post_type, 'normal', 'default' );

	// 3rd party

	// WooCommerce Subscriptions - http://www.woothemes.com/products/woocommerce-subscriptions/
	$post_type = 'shop_subscription';
	if( post_type_exists( $post_type ) ) {
		if( apply_filters( 'woo_st_order_data_meta_box', true ) )
			add_meta_box( 'woo-order-post_data', __( 'Subscription Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_order_data_meta_box', $post_type, 'normal', 'default' );
	}

	// WooCommerce - Store Exporter Deluxe - https://www.visser.com.au/plugins/store-exporter-deluxe/
	$post_type = 'scheduled_export';
	if( post_type_exists( $post_type ) ) {
		if( apply_filters( 'woo_st_scheduled_export_data_meta_box', true ) )
			add_meta_box( 'woo-scheduled_export-post_data', __( 'Scheduled Export Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_scheduled_export_data_meta_box', $post_type, 'normal', 'default' );
	}

	// WooCommerce - Store Exporter Deluxe - https://www.visser.com.au/plugins/store-exporter-deluxe/
	$post_type = 'export_template';
	if( apply_filters( 'woo_st_export_template_data_meta_box', true ) )
		add_meta_box( 'woo-coupon-post_data', __( 'Export Template Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_export_template_data_meta_box', $post_type, 'normal', 'default' );

	// WooCommerce Events - http://www.woocommerceevents.com/
	if( class_exists( 'WooCommerce_Events' ) ) {
		$post_type = 'event_magic_tickets';
		if( apply_filters( 'woo_st_event_data_meta_box', true ) )
			add_meta_box( 'woo-event-post_data', __( 'Event Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_event_data_meta_box', $post_type, 'normal', 'default' );
	}

	// WooCommerce Bookings - http://www.woothemes.com/products/woocommerce-bookings/
	if( class_exists( 'WC_Bookings' ) ) {
		$post_type = 'wc_booking';
		if( apply_filters( 'woo_st_booking_data_meta_box', true ) )
			add_meta_box( 'woo-booking-post_data', __( 'Booking Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_booking_data_meta_box', $post_type, 'normal', 'default' );
	}

	// WooCommerce Memberships - http://www.woothemes.com/products/woocommerce-memberships/
	if( function_exists( 'init_woocommerce_memberships' ) ) {
		$post_type = 'wc_user_membership';
		if( apply_filters( 'woo_st_user_membership_data_meta_box', true ) )
			add_meta_box( 'woo-user_membership-post_data', __( 'User Membership Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_user_membership_data_meta_box', $post_type, 'normal', 'low' );
		$post_type = 'wc_membership_plan';
		if( apply_filters( 'woo_st_membership_plan_data_meta_box', true ) )
			add_meta_box( 'woo-membership_plan-post_data', __( 'Membership Plan Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_membership_plan_data_meta_box', $post_type, 'normal', 'low' );
		// These guys think they are special...
		add_filter( 'wc_memberships_allowed_meta_box_ids', 'woo_st_extend_wc_memberships_allowed_meta_box_ids' );
	}

	// WooCommerce Appointments - http://www.bizzthemes.com/plugins/woocommerce-appointments/
	if( class_exists( 'WC_Appointments' ) ) {
		$post_type = 'wc_appointment';
		if( apply_filters( 'woo_st_appointment_data_meta_box', true ) )
			add_meta_box( 'woo-appointment-post_data', __( 'Appointment Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_generic_data_meta_box', $post_type, 'normal', 'low' );
	}

	// Advanced Custom Fields - http://www.advancedcustomfields.com
	if( class_exists( 'acf' ) ) {
		$acf_version = ( defined( 'ACF_VERSION' ) ? ACF_VERSION : false );
		if( version_compare( $acf_version, '5.6', '>=' ) )
			$post_type = 'acf-field-group';
		else
			$post_type = 'acf';
		if( apply_filters( 'woo_st_acf_data_meta_box', true ) )
			add_meta_box( 'woo-acf-post_data', __( 'ACF Post Meta', 'woocommerce-store-toolkit' ), 'woo_st_generic_data_meta_box', $post_type, 'normal', 'low' );
	}

}

// WooCommerce Memberships - http://www.woothemes.com/products/woocommerce-memberships/
function woo_st_extend_wc_memberships_allowed_meta_box_ids( $meta_boxes ) {

	$meta_boxes[] = 'woo-user_membership-post_data';
	$meta_boxes[] = 'woo-membership_plan-post_data';
	return $meta_boxes;

}

function woo_st_product_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'product_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_order_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'order_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_order_items_data_meta_box() {

	global $post, $wpdb;

	$order_items_sql = $wpdb->prepare( "SELECT `order_item_id` as id, `order_item_name` as name, `order_item_type` as type FROM `" . $wpdb->prefix . "woocommerce_order_items` WHERE `order_id` = %d", $post->ID );
	if( $order_items = $wpdb->get_results( $order_items_sql ) ) {
		foreach( $order_items as $key => $order_item ) {
			$order_itemmeta_sql = $wpdb->prepare( "SELECT `meta_key`, `meta_value` FROM `" . $wpdb->prefix . "woocommerce_order_itemmeta` AS order_itemmeta WHERE `order_item_id` = %d ORDER BY `order_itemmeta`.`meta_key` ASC", $order_item->id );
			$order_items[$key]->meta = $wpdb->get_results( $order_itemmeta_sql );
		}
	}

	$template = 'order_item_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_order_refunds_data_meta_box() {

	global $post;

	$refunds = woo_st_get_order_refunds( $post->ID );

	$template = 'order_refund_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_order_related_orders_meta_box() {

	global $post;

	$post_id = ( $post->ID ? $post->ID : false );
	$orders = array();
	$user_id = get_post_meta( $post_id, '_customer_user', true );
	$matching = false;
	if( !empty( $user_id ) ) {
		$matching = 'user_id';
		// Check if a User has been linked to this Order
		$args = array(
			'fields' => 'ids'
		);
		$orders = woo_st_get_user_orders( $user_id, $args );
	} else {
		$matching = 'billing_email';
		// Fallback to the Billing e-mail address
		$billing_email = get_post_meta( $post_id, '_billing_email', true );
		if( !empty( $billing_email ) ) {
			$post_type = 'shop_order';
			$args = array(
				'post_type' => $post_type,
				'fields' => 'ids'
			);
			$woocommerce_version = woo_get_woo_version();
			// Check if this is a pre-WooCommerce 2.2 instance
			if( version_compare( $woocommerce_version, '2.2' ) >= 0 )
				$args['post_status'] = ( function_exists( 'wc_get_order_statuses' ) ? apply_filters( 'woo_st_order_post_status', array_keys( wc_get_order_statuses() ) ) : 'any' );
			else
				$args['post_status'] = apply_filters( 'woo_st_order_post_status', 'publish' );
			$args['meta_query'][] = array(
				'key' => '_billing_email',
				'value' => $billing_email
			);
			$order_ids = new WP_Query( $args );
			$orders = ( !empty( $order_ids->posts ) ? $order_ids->posts : false );
		}
	}

	// Remove this Order from the list
	if( !empty( $orders ) ) {
		$needle = array_search( $post_id, $orders );
		if( $needle !== false )
			unset( $orders[$needle] );
	}

	$template = 'order_related_orders.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_coupon_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'coupon_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_export_template_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'export_template_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_category_data_meta_box( $term = '', $taxonomy = '' ) {

	$term_taxonomy = 'product_cat';
	$term_meta = get_term_meta( $term->term_id );

	// We support up to 5 levels deep; can be extended further as needed

	// Term
	$category_heirachy = $term->name;
	$category_depth = 1;
	if( !empty( $term->parent ) ) {
		// Term > Term
		$parent = get_term( $term->parent );
		if( !is_wp_error( $parent ) ) {
			$category_depth++;
			$category_heirachy = $parent->name . ' &raquo; ' . $category_heirachy;
			// Term > Term > Term
			$parent = get_term( $parent->parent );
			if( !is_wp_error( $parent ) ) {
				$category_depth++;
				$category_heirachy = $parent->name . ' &raquo; ' . $category_heirachy;
				// Term > Term > Term > Term
				$parent = get_term( $parent->parent );
				if( !is_wp_error( $parent ) ) {
					$category_depth++;
					$category_heirachy = $parent->name . ' &raquo; ' . $category_heirachy;
					// Term > Term > Term > Term > Term
					$parent = get_term( $parent->parent );
					if( !is_wp_error( $parent ) ) {
						$category_heirachy = $parent->name . ' &raquo; ' . $category_heirachy;
					}
				}
			}
		}
	}

	$template = 'category_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_tag_data_meta_box( $term = '', $taxonomy = '' ) {

	$term_taxonomy = 'product_tag';
	$term_meta = get_term_meta( $term->term_id );

	$template = 'tag_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_brand_data_meta_box( $term = '', $taxonomy = '' ) {

	$term_taxonomy = 'product_brand';
	$term_meta = get_term_meta( $term->term_id );

	$template = 'brand_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_product_vendor_data_meta_box( $term = '', $taxonomy = '' ) {

	$term_taxonomy = 'yith_shop_vendor';
	$term_meta = get_term_meta( $term->term_id );

	$template = 'product_vendor_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_user_orders( $user ) {

	if( !current_user_can( 'manage_woocommerce' ) )
		return;

	$user_id = $user->data->ID;
	$posts_per_page = apply_filters( 'woo_st_user_orders_posts_per_page', 10 );
	$args = array(
		'numberposts' => $posts_per_page
	);
	$total_orders = woo_st_get_user_orders( $user_id, $args, 'found_posts' );
	$paged = ( isset( $_GET['paged'] ) ? $_GET['paged'] : 1 );
	if( !empty( $paged ) )
		$args['paged'] = $paged;
	$max_page = absint( $total_orders / $posts_per_page );
	$orders = ( !empty( $total_orders ) ? woo_st_get_user_orders( $user_id, $args ) : false );

	$template = 'user_orders.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}


function woo_st_user_data_meta_box( $user = '' ) {

	$user_id = $user->data->ID;
	$user_meta = get_user_meta( $user_id );

	$template = 'user_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_scheduled_export_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'scheduled_export_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_event_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'event_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_booking_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'booking_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_user_membership_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'user_membership_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_generic_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'order_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_membership_plan_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'membership_plan_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_attachment_data_meta_box() {

	global $post;

	$post_meta = get_post_custom( $post->ID );

	$template = 'attachment_data.php';
	if( file_exists( WOO_ST_PATH . 'templates/admin/' . $template ) ) {

		include_once( WOO_ST_PATH . 'templates/admin/' . $template );

	} else {

		$message = sprintf( __( 'We couldn\'t load the template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), $template, WOO_ST_PATH . 'includes/admin/...' );
?>
<p><strong><?php echo $message; ?></strong></p>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php

	}

}

function woo_st_admin_order_column_headers( $columns ) {

	// Check if another Plugin has registered this column
	if( !isset( $columns['user'] ) ) {
		$pos = array_search( 'order_total', array_keys( $columns ) );
		$columns = array_merge(
			array_slice( $columns, 0, $pos ),
			array( 'user' => __( 'User ID', 'woocommerce-store-toolkit' ) ),
			array_slice( $columns, $pos )
		);
	}
	return $columns;

}

function woo_st_admin_order_column_content( $column ) {

	global $post;

	if( $column == 'user' ) {
		$user_id = get_post_meta( $post->ID, '_customer_user', true );
		if( !empty( $user_id ) ) {
			echo '<a href="' . get_edit_user_link( $user_id ) . '">';
			echo sprintf( '#%d', $user_id );
			echo '</a>';
		} else {
			echo '-';
		}

		// Allow Plugin/Theme authors to add their own content within this column
		do_action( 'woo_st_admin_order_user_column_content', $post->ID );

	}

}

function woo_st_add_user_column( $columns ) {

	if( !current_user_can( 'manage_woocommerce' ) )
		return $columns;

	$last_column = array_slice( $columns, -1, 1, true );
	array_pop( $columns );
	$columns['woocommerce_orders'] = __( 'Orders', 'woocommerce-store-toolkit' );
	$columns += $last_column;
	return $columns;

}

function woo_st_user_column_values( $value, $column_name, $user_id ) {

	if( $column_name <> 'woocommerce_orders' )
		return $value;

	$value = '0';
	$args = array(
		'fields' => 'ids'
	);
	$orders = woo_st_get_user_orders( $user_id, $args );
	if( !empty( $orders ) )
		$value = count( $orders );

	return $value;

}

function woo_st_woocommerce_register_post_type() {

	// So we can view the Edit Product screen for individual Variations
	$unlock_variations = get_option( WOO_ST_PREFIX . '_unlock_variations', 0 );
	if( !empty( $unlock_variations ) ) {
		add_filter( 'woocommerce_register_post_type_product_variation', 'woo_st_admin_unlock_variations_screen', 10, 1 );
	}

}
add_action( 'woocommerce_register_post_type', 'woo_st_woocommerce_register_post_type' );

function woo_st_extend_woocommerce_system_status_report() {

	global $_wp_additional_image_sizes;

	$image_sizes = get_intermediate_image_sizes();
	ob_start(); ?>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2" data-export-label="Templates"><?php _e( 'Image Sizes', 'woocommerce-store-toolkit' ); ?><?php echo wc_help_tip( __( 'This section shows all available WordPress Image Sizes.', 'woocommerce-exporter' ) ); ?></th>
		</tr>
	</thead>
	<tbody>
<?php if( !empty( $image_sizes ) ) { ?>
	<?php foreach( $image_sizes as $image_size ) { ?>
		<tr>
			<td><?php echo $image_size; ?></td>
			<td>
		<?php if( isset( $_wp_additional_image_sizes[$image_size] ) ) { ?>
				<?php echo print_r( $_wp_additional_image_sizes[$image_size], true ); ?>
		<?php } else { ?>
<?php
	// Check for default WordPress Image Sizes
	$size_info = array();
	switch( $image_size ) {

		case 'thumbnail':
			$size_info = array(
				'width' => get_option( 'thumbnail_size_w' ),
				'height' => get_option( 'thumbnail_size_h' ),
				'crop' => get_option( 'thumbnail_crop' )
			);
			break;

		case 'medium':
			$size_info = array(
				'width' => get_option( 'medium_size_w' ),
				'height' => get_option( 'medium_size_h' )
			);
			break;

		case 'medium_large':
			$size_info = array(
				'width' => get_option( 'medium_large_size_w' ),
				'height' => get_option( 'medium_large_size_h' )
			);

		case 'large':
			$size_info = array(
				'width' => get_option( 'large_size_w' ),
				'height' => get_option( 'large_size_h' )
			);
			break;

	}
?>
				<?php echo ( !empty( $size_info ) ? print_r( $size_info, true ) : '-' ); ?>
		<?php } ?>
			</td>
		</tr>
	<?php } ?>
<?php } else { ?>
		<tr>
			<td colspan="2"><?php _e( 'No Image Sizes were available.', 'woocommerce-store-toolkit' ); ?></td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php
	ob_end_flush();

}
add_action( 'woocommerce_system_status_report', 'woo_st_extend_woocommerce_system_status_report' );
?>