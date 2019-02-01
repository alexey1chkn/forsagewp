<ul class="subsubsub">
	<li><a href="#cron-nuke"><?php _e( 'CRON Nuke', 'woocommerce-store-toolkit' ); ?></a></li>
</ul>
<!-- .subsubsub -->
<br class="clear" />

<form method="post">
	<table class="form-table">
		<tbody>

			<tr id="cron-exports">
				<td colspan="2" style="padding:0;">
					<hr />
					<h3><div class="dashicons dashicons-clock"></div>&nbsp;<?php _e( 'CRON Nuke', 'woocommerce-store-toolkit' ); ?></h3>
<?php if( $enable_cron == 1 ) { ?>
					<p style="font-size:0.8em;"><div class="dashicons dashicons-yes"></div>&nbsp;<strong><?php _e( 'CRON Nuke is enabled', 'woocommerce-store-toolkit' ); ?></strong></p>
<?php } ?>
					<p class="description"><?php printf( __( 'Store Toolkit supports nuking via a command line request, to do this you need to prepare a specific URL and pass it the following required inline parameters. For sample CRON requests and supported arguments consult our <a href="%s" target="_blank">online documentation</a>.', 'woocommerce-store-toolkit' ), $troubleshooting_url ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="enable_cron"><?php _e( 'Enable CRON', 'woocommerce-store-toolkit' ); ?></label></th>
				<td>
					<select id="enable_cron" name="enable_cron">
						<option value="1"<?php selected( $enable_cron, 1 ); ?>><?php _e( 'Yes', 'woocommerce-store-toolkit' ); ?></option>
						<option value="0"<?php selected( $enable_cron, 0 ); ?>><?php _e( 'No', 'woocommerce-store-toolkit' ); ?></option>
					</select>
					<p class="description"><?php _e( 'Enabling CRON allows developers to schedule automated nukes and connect with Store Toolkit remotely.', 'woocommerce-store-toolkit' ); ?></p>
				</td>
			</tr>
			<tr>
				<th>
					<label for="secret_key"><?php _e( 'CRON secret key', 'woocommerce-store-toolkit' ); ?></label>
				</th>
				<td>
					<input name="secret_key" type="text" id="secret_key" value="<?php echo esc_attr( $secret_key ); ?>" class="large-text code" />
					<p class="description"><?php _e( 'This secret key (can be left empty to allow unrestricted access) limits access to authorised developers who provide a matching key when working with Store Toolkit.', 'woocommerce-store-toolkit' ); ?></p>
				</td>
			</tr>

		</tbody>
	</table>
	<!-- .form-table -->
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'woocommerce-store-toolkit' ); ?>" />
	</p>
	<input type="hidden" name="action" value="save-settings" />
	<?php wp_nonce_field( 'woo_st_save_settings' ); ?>
</form>