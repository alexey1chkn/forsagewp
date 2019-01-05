<?php

	/**
	 * Activator for the GA cache
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 09.09.2017, Webcraftic
	 * @see Factory409_Activator
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	class WGA_Activation extends Wbcr_Factory409_Activator {

		/**
		 * Runs activation actions.
		 *
		 * @since 1.0.0
		 */
		public function activate()
		{
			// -------------
			// Caching google analytics on a schedule
			// -------------

			$ga_cache = WGA_Plugin::app()->getPopulateOption('ga_cache');

			if( $ga_cache ) {
				wp_clear_scheduled_hook('wbcr_clearfy_update_local_ga');

				if( !wp_next_scheduled('wbcr_clearfy_update_local_ga') ) {
					$ga_caos_remove_wp_cron = WGA_Plugin::app()->getPopulateOption('ga_caos_remove_wp_cron');

					if( !$ga_caos_remove_wp_cron ) {
						wp_schedule_event(time(), 'daily', 'wbcr_clearfy_update_local_ga');
					}
				}
			}
		}

		/**
		 * Runs activation actions.
		 *
		 * @since 1.0.0
		 */
		public function deactivate()
		{
			//WGA_Plugin::app()->updatePopulateOption('ga_cache', 0);

			if( wp_next_scheduled('wbcr_clearfy_update_local_ga') ) {
				wp_clear_scheduled_hook('wbcr_clearfy_update_local_ga');
			}
		}
	}
