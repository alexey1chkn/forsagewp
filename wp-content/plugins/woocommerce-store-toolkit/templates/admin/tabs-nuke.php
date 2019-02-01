<ul class="subsubsub">
	<li><a href="#empty-woocommerce-tables"><?php _e( 'Empty WooCommerce Tables', 'woocommerce-store-toolkit' ); ?></a> |</li>
	<li><a href="#empty-product-by-category"><?php _e( 'Delete Products by Product Category', 'woocommerce-store-toolkit' ); ?></a> |</li>
	<li><a href="#delete-sales-by-sale-status"><?php _e( 'Delete Orders by Order Status', 'woocommerce-store-toolkit' ); ?></a> |</li>
	<li><a href="#delete-sales-by-date"><?php _e( 'Delete Orders by Order Date', 'woocommerce-store-toolkit' ); ?></a> |</li>
	<li><a href="#empty-wordpress-tables"><?php _e( 'Empty WordPress Tables', 'woocommerce-store-toolkit' ); ?></a></li>
</ul>
<br class="clear" />
<h3><?php _e( 'Nuke WooCommerce', 'woocommerce-store-toolkit' ); ?></h3>
<p><?php _e( 'Select the WooCommerce tables you wish to empty then click Remove to permanently remove WooCommerce generated details from your WordPress database.', 'woocommerce-store-toolkit' ); ?></p>
<form method="post" id="postform">
	<div id="poststuff">

		<div id="empty-woocommerce-tables" class="postbox">
			<h3 class="hndle"><?php _e( 'Empty WooCommerce Tables', 'woocommerce-store-toolkit' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Permanently remove WooCommerce details.', 'woocommerce-store-toolkit' ); ?></p>
				<p><a href="javascript:void(0)" id="woocommerce-checkall"><?php _e( 'Check All', 'woocommerce-store-toolkit' ); ?></a> | <a href="javascript:void(0)" id="woocommerce-uncheckall"><?php _e( 'Uncheck All', 'woocommerce-store-toolkit' ); ?></a></p>
				<fieldset>
					<table class="form-table">

						<tr>
							<th>
								<label for="products"><?php _e( 'Products', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="products" name="woo_st_products"<?php echo disabled( $products, 0 ); ?> /> (<?php echo $products; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="product_categories"><?php _e( 'Product Categories', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="product_categories" name="woo_st_product_categories"<?php echo disabled( $categories, 0 ); ?> /> (<?php echo $categories; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="product_tags"><?php _e( 'Product Tags', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="product_tags" name="woo_st_product_tags"<?php echo disabled( $tags, 0 ); ?> /> (<?php echo $tags; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="product_images"><?php _e( 'Product Images', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="product_images" name="woo_st_product_images"<?php echo disabled( $images, 0 ); ?> /> (<?php echo $images; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="attributes"><?php _e( 'Product Attributes', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="attributes" name="woo_st_attributes"<?php echo disabled( $attributes, 0 ); ?> /> (<?php echo $attributes; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="orders"><?php _e( 'Orders', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="orders" name="woo_st_orders"<?php echo disabled( $orders, 0 ); ?> /> (<?php echo $orders; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="tax_rates"><?php _e( 'Tax Rates', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="tax_rates" name="woo_st_tax_rates"<?php echo disabled( $tax_rates, 0 ); ?> /> (<?php echo $tax_rates; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="download_permissions"><abbr title="<?php _e( 'Product Download Permissions', 'woocommerce-store-toolkit' ); ?>"><?php _e( 'Download Permissions', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="download_permissions" name="woo_st_download_permissions"<?php echo disabled( $download_permissions, 0 ); ?> /> (<?php echo $download_permissions; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="coupons"><?php _e( 'Coupons', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="coupons" name="woo_st_coupons"<?php echo disabled( $coupons, 0 ); ?> /> (<?php echo $coupons; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="shipping_classes"><?php _e( 'Shipping Classes', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="shipping_classes" name="woo_st_shipping_classes"<?php echo disabled( $shipping_classes, 0 ); ?> /> (<?php echo $shipping_classes; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="woocommerce_logs"><?php _e( 'WooCommerce Logs', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="woocommerce_logs" name="woo_st_woocommerce_logs"<?php echo disabled( $woocommerce_logs, 0 ); ?> /> (<?php echo $woocommerce_logs; ?>)
							</td>
						</tr>

<?php if( isset( $credit_cards ) ) { ?>
						<tr>
							<th>
								<label for="creditcards"><?php _e( 'Credit Cards', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="creditcards" name="woo_st_creditcards"<?php echo disabled( $credit_cards, 0 ); ?> /> (<?php echo $credit_cards; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.visser.com.au/woocommerce/plugins/offline-credit-card-processing/" target="_blank">' . __( 'Offline Credit Card Processing', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $store_exports_csv ) ) { ?>
						<tr>
							<th>
								<label for="storeexportscsv"><?php _e( 'Store Exports (CSV)', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="storeexportscsv" name="woo_st_storeexportscsv"<?php echo disabled( $store_exports_csv, 0 ); ?> /> (<?php echo $store_exports_csv; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.visser.com.au/woocommerce/plugins/store-exporter-deluxe/" target="_blank">' . __( 'Store Exporter Deluxe', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $store_exports_tsv ) ) { ?>
						<tr>
							<th>
								<label for="storeexportstsv"><?php _e( 'Store Exports (TSV)', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="storeexportstsv" name="woo_st_storeexportstsv"<?php echo disabled( $store_exports_tsv, 0 ); ?> /> (<?php echo $store_exports_tsv; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.visser.com.au/woocommerce/plugins/store-exporter-deluxe/" target="_blank">' . __( 'Store Exporter Deluxe', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $store_exports_xls ) ) { ?>
						<tr>
							<th>
								<label for="storeexportsxls"><?php _e( 'Store Exports (XLS)', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="storeexportsxls" name="woo_st_storeexportsxls"<?php echo disabled( $store_exports_xls, 0 ); ?> /> (<?php echo $store_exports_xls; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.visser.com.au/woocommerce/plugins/store-exporter-deluxe/" target="_blank">' . __( 'Store Exporter Deluxe', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $store_exports_rss ) ) { ?>
						<tr>
							<th>
								<label for="storeexportsrss"><?php _e( 'Store Exports (RSS)', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="storeexportsrss" name="woo_st_storeexportsrss"<?php echo disabled( $store_exports_rss, 0 ); ?> /> (<?php echo $store_exports_rss; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.visser.com.au/woocommerce/plugins/store-exporter-deluxe/" target="_blank">' . __( 'Store Exporter Deluxe', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $store_exports_xlsx ) ) { ?>
						<tr>
							<th>
								<label for="storeexportsxlsx"><?php _e( 'Store Exports (XLSX)', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="storeexportsxlsx" name="woo_st_storeexportsxlsx"<?php echo disabled( $store_exports_xlsx, 0 ); ?> /> (<?php echo $store_exports_xlsx; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.visser.com.au/woocommerce/plugins/store-exporter-deluxe/" target="_blank">' . __( 'Store Exporter Deluxe', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $store_exports_xml ) ) { ?>
						<tr>
							<th>
								<label for="storeexportsxml"><?php _e( 'Store Exports (XML)', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="storeexportsxml" name="woo_st_storeexportsxml"<?php echo disabled( $store_exports_xml, 0 ); ?> /> (<?php echo $store_exports_xml; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.visser.com.au/woocommerce/plugins/store-exporter-deluxe/" target="_blank">' . __( 'Store Exporter Deluxe', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $brands ) ) { ?>
						<tr>
							<th>
								<label for="product_brands"><?php _e( 'Product Brands', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="product_brands" name="woo_st_product_brands"<?php echo disabled( $brands, 0 ); ?> /> (<?php echo $brands; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.woothemes.com/products/brands/" target="_blank">' . __( 'WooCommerce Brands', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $vendors ) ) { ?>
						<tr>
							<th>
								<label for="product_vendors"><?php _e( 'Product Vendors', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="product_vendors" name="woo_st_product_vendors"<?php echo disabled( $vendors, 0 ); ?> /> (<?php echo $vendors; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.woothemes.com/products/product-vendors/" target="_blank">' . __( 'Product Vendors', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
<?php if( isset( $google_product_feed ) ) { ?>
						<tr>
							<th>
								<label for="google_product_feed"><?php _e( 'Advanced Google Product Feed', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="google_product_feed" name="woo_st_google_product_feed"<?php echo disabled( $google_product_feed, 0 ); ?> /> (<?php echo $google_product_feed; ?>)
								<span class="description"><?php echo sprintf( __( 'via %s', 'woocommerce-store-toolkit' ), '<a href="http://www.leewillis.co.uk/wordpress-plugins/" target="_blank">' . __( 'Advanced Google Product Feed', 'woocommerce-store-toolkit' ) . '</a>' ); ?></span>
							</td>
						</tr>

<?php } ?>
					</table>
				</fieldset>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'woocommerce-store-toolkit' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

		<div id="empty-product-by-category" class="postbox">
			<h3 class="hndle"><?php _e( 'Delete Products by Product Category', 'woocommerce-store-toolkit' ); ?></h3>
			<div class="inside">
<?php if( $categories ) { ?>
				<p class="description"><?php _e( 'Remove Products from specific Product Categories by selecting the Product Categories below, then click Remove to permanently remove those Products.', 'woocommerce-store-toolkit' ); ?></p>
				<ul>
	<?php foreach( $categories_data as $category_single ) { ?>
					<li>
						<label>
							<input type="checkbox" name="woo_st_products_category[<?php echo $category_single->term_id; ?>]" value="<?php echo $category_single->term_id; ?>"<?php if( $category_single->count == 0 ) { ?> disabled="disabled"<?php } ?> />
							<?php echo $category_single->name; ?> (<?php echo $category_single->count; ?>)
						</label>
					</li>
	<?php } ?>
				</ul>
				<p class="submit">
					<input type="button" id="empty-product-by-category-remove" value="<?php _e( 'Remove', 'woocommerce-store-toolkit' ); ?>" class="button-primary confirm-button" data-confirm="<?php _e( 'This will permanently remove Products associated to the selected Product Categories. Are you sure you want to proceed?', 'woocommerce-store-toolkit' ); ?>" />
				</p>
<?php } else { ?>
				<p><?php _e( 'No Categories have been created.', 'woocommerce-store-toolkit' ); ?></p>
<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

		<div id="delete-sales-by-sale-status" class="postbox">
			<h3 class="hndle"><?php _e( 'Delete Orders by Order Status', 'woocommerce-store-toolkit' ); ?></h3>
			<div class="inside">
<?php if( $orders ) { ?>
				<p class="description"><?php _e( 'Remove Orders based on the Order Status by selecting the the Order Status below, then click Remove to permanently remove those Orders.', 'woocommerce-store-toolkit' ); ?></p>
				<ul>
<?php if( $order_statuses ) { ?>
	<?php foreach( $order_statuses as $order_status ) { ?>
					<li>
						<label>
							<input type="checkbox" name="woo_st_orders_status[<?php echo $order_status->term_id; ?>]" value="<?php echo $order_status->term_id; ?>"<?php if( $order_status->count == 0 ) { ?> disabled="disabled"<?php } ?> />
							<?php echo woo_st_convert_sale_status( $order_status->name ); ?> (<?php echo $order_status->count; ?>)
						</label>
					</li>
	<?php } ?>
<?php } else { ?>
				<li><?php _e( 'No Order Status\'s were found.', 'woocommerce-store-toolkit' ); ?></li>
<?php } ?>
				</ul>
				<p class="submit">
					<input type="button" id="delete-sales-by-sale-status-remove" value="<?php _e( 'Remove', 'woocommerce-store-toolkit' ); ?>" class="button-primary confirm-button" data-confirm="<?php _e( 'This will permanently remove Orders matching the selected Order Status. Are you sure you want to proceed?', 'woocommerce-store-toolkit' ); ?>" />
				</p>
<?php } else { ?>
				<p><?php _e( 'No Orders were found.', 'woocommerce-store-toolkit' ); ?></p>
<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

		<div id="delete-sales-by-date" class="postbox">
			<h3 class="hndle"><?php _e( 'Delete Orders by Order Date', 'woocommerce-store-toolkit' ); ?></h3>
			<div class="inside">
<?php if( $orders ) { ?>
				<p class="description"><?php _e( 'Remove Orders based on the Order Date by selecting the required date filter, then click Remove to permanently remove those Orders.', 'woocommerce-store-toolkit' ); ?></p>
				<ul>
					<li>
						<label><input type="radio" name="woo_st_orders_date" value=""<?php checked( $orders_date, false ); ?>/><?php _e( 'All dates', 'woocommerce-store-toolkit' ); ?></label>
					</li>
					<li>
						<label><input type="radio" name="woo_st_orders_date" value="today"<?php checked( $orders_date, 'today' ); ?> /><?php _e( 'Today', 'woocommerce-store-toolkit' ); ?></label>
					</li>
					<li>
						<label><input type="radio" name="woo_st_orders_date" value="current_month"<?php checked( $orders_date, 'current_month' ); ?> /><?php _e( 'This month', 'woocommerce-store-toolkit' ); ?></label>
					</li>
					<li>
						<label><input type="radio" name="woo_st_orders_date" value="manual"<?php checked( $orders_date, 'manual' ); ?> /><?php _e( 'Fixed date', 'woocommerce-store-toolkit' ); ?></label><br />
						<div style="margin-top:0.2em;">
							<input type="text" size="10" maxlength="10" id="orders_date_from" name="woo_st_orders_date_from" value="<?php echo $orders_date_from; ?>" class="text code datepicker order_export" />
							 to 
							<input type="text" size="10" maxlength="10" id="orders_date_to" name="woo_st_orders_date_to" value="<?php echo $orders_date_to; ?>" class="text code datepicker order_export" />
						</div>
					</li>
				</ul>
				<p class="submit">
					<input type="button" id="delete-sales-by-date-remove" value="<?php _e( 'Remove', 'woocommerce-store-toolkit' ); ?>" class="button-primary confirm-button" data-confirm="<?php _e( 'This will permanently remove Orders within the selected Order date range. Are you sure you want to proceed?', 'woocommerce-store-toolkit' ); ?>" />
				</p>
<?php } else { ?>
				<p><?php _e( 'No Orders were found.', 'woocommerce-store-toolkit' ); ?></p>
<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

		<div id="empty-wordpress-tables" class="postbox">
			<h3 class="hndle"><?php _e( 'Empty WordPress Tables', 'woocommerce-store-toolkit' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Permanently remove WordPress details.', 'woocommerce-store-toolkit' ); ?></p>
				<p><a href="javascript:void(0)" id="wordpress-checkall"><?php _e( 'Check All', 'woocommerce-store-toolkit' ); ?></a> | <a href="javascript:void(0)" id="wordpress-uncheckall"><?php _e( 'Uncheck All', 'woocommerce-store-toolkit' ); ?></a></p>
				<fieldset>
					<table class="form-table">

						<tr>
							<th>
								<label for="posts"><?php _e( 'Posts', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="posts" name="woo_st_posts"<?php echo disabled( $posts, 0 ); ?> /> (<?php echo $posts; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="post_categories"><?php _e( 'Post Categories', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="post_categories" name="woo_st_post_categories"<?php echo disabled( $post_categories, 0 ); ?> /> (<?php echo $post_categories; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="post_tags"><?php _e( 'Post Tags', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="post_tags" name="woo_st_post_tags"<?php echo disabled( $post_tags, 0 ); ?> /> (<?php echo $post_tags; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="links"><?php _e( 'Links', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="links" name="woo_st_links"<?php echo disabled( $links, 0 ); ?> /> (<?php echo $links; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="comments"><?php _e( 'Comments', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="links" name="woo_st_comments"<?php echo disabled( $comments, 0 ); ?> /> (<?php echo $comments; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="comments"><?php _e( 'Media: Images', 'woocommerce-store-toolkit' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="links" name="woo_st_media_images"<?php echo disabled( $media_images, 0 ); ?> /> (<?php echo $media_images; ?>)
							</td>
						</tr>

					</table>
				</fieldset>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'woocommerce-store-toolkit' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

	</div>
	<!-- #poststuff -->

	<input type="hidden" name="action" value="nuke" />
	<?php wp_nonce_field( 'nuke', 'woo_st_nuke' ); ?>
</form>