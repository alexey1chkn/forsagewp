<?php
	/**
	 * This class configures the google analytics cache
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 2017 Webraftic Ltd
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	class WGA_ConfigGACache extends Wbcr_FactoryClearfy206_Configurate {


		public function registerActionsAndFilters()
		{

			if( $this->getPopulateOption('ga_cache') ) {
				add_filter('cron_schedules', array($this, 'cronAdditions'));

				// Load update script to schedule in wp_cron.
				add_action('wbcr_ga_update_local_script', array($this, 'updateLocalGoogleAnaliticScript'));

				if( !is_admin() ) {
					$this->addGoogleAnaliticsScript();
				}
			}
		}


		public function cronAdditions($schedules)
		{
			$schedules['weekly'] = array(
				'interval' => DAY_IN_SECONDS * 7,
				'display' => __('Once Weekly'),
			);

			$schedules['twicemonthly'] = array(
				'interval' => DAY_IN_SECONDS * 14,
				'display' => __('Twice Monthly'),
			);

			$schedules['monthly'] = array(
				'interval' => DAY_IN_SECONDS * 30,
				'display' => __('Once Monthly'),
			);

			return $schedules;
		}

		public function updateLocalGoogleAnaliticScript()
		{
			include(WGA_PLUGIN_DIR . '/includes/update-local-ga.php');
		}

		private function addGoogleAnaliticsScript()
		{
			$ga_tracking_id = $this->getPopulateOption('ga_tracking_id');

			if( !empty($ga_tracking_id) ) {
				$local_ga_file = WGA_PLUGIN_DIR . '/cache/local-ga.js';
				// If file is not created yet, create now!
				if( !file_exists($local_ga_file) ) {
					ob_start();
					do_action('wbcr_ga_update_local_script');
					ob_end_clean();
				}

				$ga_script_position = $this->getPopulateOption('ga_script_position', 'footer');
				$ga_enqueue_order = $this->getPopulateOption('ga_enqueue_order', 0);

				switch( $ga_script_position ) {
					case 'header':
						add_action('wp_head', array($this, 'printGoogleAnalitics'), $ga_enqueue_order);
						break;
					default:
						add_action('wp_footer', array($this, 'printGoogleAnalitics'), $ga_enqueue_order);
				}
			}
		}

		/**
		 * Generate tracking code and add to header/footer (default is header).
		 */
		public function printGoogleAnalitics()
		{
			$ga_tracking_id = $this->getPopulateOption('ga_tracking_id');
			$ga_track_admin = $this->getPopulateOption('ga_track_admin');

			// If user is admin we don't want to render the tracking code, when option is disabled.
			if( empty($ga_tracking_id) || (current_user_can('manage_options') && (!$ga_track_admin)) ) {
				return;
			}

			$ga_adjusted_bounce_rate = $this->getPopulateOption('ga_adjusted_bounce_rate', 0);
			$ga_anonymize_ip = $this->getPopulateOption('ga_anonymize_ip', false);
			$ga_caos_disable_display_features = $this->getPopulateOption('ga_caos_disable_display_features', false);

			echo "<!-- Google Analytics Local by " . $this->plugin->getPluginTitle() . " -->" . PHP_EOL;

			echo "<script>" . PHP_EOL;
			echo "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','" . WGA_PLUGIN_URL . "/cache/local-ga.js','ga');" . PHP_EOL;

			echo "ga('create', '" . $ga_tracking_id . "', 'auto');" . PHP_EOL;

			echo $ga_caos_disable_display_features
				? "ga('set', 'displayFeaturesTask', null);" . PHP_EOL
				: '';

			echo $ga_anonymize_ip
				? "ga('set', 'anonymizeIp', true);" . PHP_EOL
				: '';

			echo "ga('send', 'pageview');";

			echo $ga_adjusted_bounce_rate
				? PHP_EOL . 'setTimeout("ga(' . "'send','event','adjusted bounce rate','" . $ga_adjusted_bounce_rate . " seconds')" . '"' . ',' . $ga_adjusted_bounce_rate * 1000 . ');'
				: '';

			echo PHP_EOL . '</script>' . PHP_EOL;

			echo "<!-- end Google Analytics Local by " . $this->plugin->getPluginTitle() . " -->" . PHP_EOL;
		}
	}
