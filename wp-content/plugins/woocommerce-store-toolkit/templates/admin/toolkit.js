var $j = jQuery.noConflict();

$j(function () {

	// Date Picker element
	if( $j.isFunction($j.fn.datepicker) ) {
		$j('.datepicker').datepicker({
			dateFormat: 'dd/mm/yy'
		});
	}

	$j('#woocommerce-checkall').click(function () {
		$j('#empty-woocommerce-tables').find(':checkbox').attr('checked', true);
	});
	$j('#woocommerce-uncheckall').click(function () {
		$j('#empty-woocommerce-tables').find(':checkbox').attr('checked', false);
	});

	$j('#wordpress-checkall').click(function () {
		$j('#empty-wordpress-tables').find(':checkbox').attr('checked', true);
	});
	$j('#wordpress-uncheckall').click(function () {
		$j('#empty-wordpress-tables').find(':checkbox').attr('checked', false);
	});

	$j('#empty-product-by-category-remove').click(function () {
		var checked_items = $j('#empty-product-by-category input:checked').length;
		if( !checked_items ) {
			alert( 'At least one Product Category needs to be selected.' );
			e.stopImmediatePropagation();
			return;
		}
		$j('#product_categories').attr('checked', true);
	});
	$j('#delete-sales-by-sale-status-remove').click(function (e) {
		var checked_items = $j('#delete-sales-by-sale-status input:checked').length;
		if( !checked_items ) {
			alert( 'At least one Order Status needs to be selected.' );
			e.stopImmediatePropagation();
			return;
		}
		$j('#orders').attr('checked', true);
	});
	$j('#delete-sales-by-date-remove').click(function () {
		$j('#orders').attr('checked', true);
	});

	$j('.woocommerce_page_woo_st .confirm-button').click(function(e){
		var choice = confirm($j(this).attr('data-confirm'));
		if( choice )
			$j('.woocommerce_page_woo_st form#postform').trigger( "submit" );
		return;
	});

	$j('.woocommerce_page_woo_st form#postform').submit(function () {
		showProgress();
	});

	function showProgress() {
		window.scrollTo(0,0);
		
		document.getElementById('progress').style.display = 'block';
		document.getElementById('content').style.display = 'none';
		document.getElementById('support-donate_rate').style.display = 'none';
	}

});