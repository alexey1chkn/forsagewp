<?php
	/*
	Plugin Name: Webcraftic Cyr to Lat reloaded
	Plugin URI: https://wordpress.org/plugins/cyr-and-lat/
	Description: Converts Cyrillic characters in post and term slugs to Latin characters. Useful for creating human-readable URLs. Allows to use both of cyrillic and latin slugs.
	Author: Webcraftic
	Author URI: https://clearfy.pro
	Version: 1.1.1
	*/

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	class WCTL_Plugin {

		public function __construct()
		{
			// Уведомления о расширенной версии плагина с пользовательским интерфейсовм.
			// После скрытия больше не надоедают

			require_once dirname(__FILE__) . '/admin-notices.php';
			Admin_Notices::instance(__FILE__);

			// Ссылка в матаданных плагина, на расширенную версию

			add_filter('plugin_row_meta', array($this, 'setPluginMeta'), 10, 2);

			register_activation_hook(__FILE__, array($this, 'scheduleConversion'));

			add_filter('sanitize_title', array($this, 'sanitizeTitle'), 9);
			add_filter('sanitize_file_name', array($this, 'sanitizeTitle'));
		}

		/**
		 * Ссылка в метаданных плагина, на расширенную версию
		 *
		 * @param $links
		 * @param $file
		 * @return array
		 */
		public function setPluginMeta($links, $file)
		{
			if( in_array(get_locale(), array('ru_RU', 'bel', 'kk', 'uk', 'bg', 'bg_BG', 'ka_GE')) ) {
				$link_title = 'Получите расширенный плагин бесплатно';
				$url = 'https://ru.clearfy.pro/?utm_source=wordpress.org&utm_campaign=cyr-and-lat';
			} else {
				$link_title = 'Get ultimate plugin for free';
				$url = 'https://clearfy.pro/?utm_source=wordpress.org&utm_campaign=cyr-and-lat';
			}

			if( $file == plugin_basename(__FILE__) ) {
				$links[] = '<a href="' . $url . '" style="color: #FF5722;font-weight: bold;" target="_blank">' . $link_title . '</a>';
			}

			return $links;
		}

		/**
		 * @since 1.1.1
		 * @return void
		 */
		public function scheduleConversion()
		{
			add_action('shutdown', array($this, 'convertExistingSlugs'));
		}

		/**
		 * Выполняет транслитерацию для старых записей, рубрик, меток в которых slug уже создан,
		 * также в этом методе предусмотрена возможность отката и совместимость с плагином Cyrlitera.
		 *
		 * @since 1.1.1
		 * @return void
		 */
		public function convertExistingSlugs()
		{
			global $wpdb;

			$posts = $wpdb->get_results("SELECT ID, post_name FROM {$wpdb->posts} WHERE post_name REGEXP('[^_A-Za-z0-9\-]+') AND post_status IN ('publish', 'future', 'private')");

			foreach((array)$posts as $post) {
				$sanitized_name = $this->sanitizeTitle(urldecode($post->post_name));

				if( $post->post_name != $sanitized_name ) {
					add_post_meta($post->ID, 'wbcr_wp_old_slug', $post->post_name);

					$wpdb->update($wpdb->posts, array('post_name' => $sanitized_name), array('ID' => $post->ID), array('%s'), array('%d'));
				}
			}

			$terms = $wpdb->get_results("SELECT term_id, slug FROM {$wpdb->terms} WHERE slug REGEXP('[^_A-Za-z0-9\-]+') ");

			foreach((array)$terms as $term) {
				$sanitized_slug = $this->sanitizeTitle(urldecode($term->slug));

				if( $term->slug != $sanitized_slug ) {
					update_option('wbcr_wp_term_' . $term->term_id . '_old_slug', $term->slug, false);
					$wpdb->update($wpdb->terms, array('slug' => $sanitized_slug), array('term_id' => $term->term_id), array('%s'), array('%d'));
				}
			}
		}

		/**
		 * Фильтрует все вызовы экшенов sinitize_title и sanitize_file_name,
		 * возвращает преобразованную строку на латинице.
		 *
		 * @param string $title обработанный заголовок
		 * @return string
		 */
		public function sanitizeTitle($title)
		{
			global $wpdb;

			$origin_title = $title;

			$is_term = false;
			$backtrace = debug_backtrace();
			foreach($backtrace as $backtrace_entry) {
				if( $backtrace_entry['function'] == 'wp_insert_term' ) {
					$is_term = true;
					break;
				}
			}

			$term = $is_term
				? $wpdb->get_var($wpdb->prepare("SELECT slug FROM {$wpdb->terms} WHERE name = '%s'", $title))
				: '';

			if( empty($term) ) {
				$title = $this->transliterate($title);
			} else {
				$title = $term;
			}

			return apply_filters('wbcr_ctl_sanitize_title', $title, $origin_title);
		}

		/**
		 * Очищает от специальных символов и преобразует все символы строки в латинские.
		 *
		 * @since 1.1.1
		 * @param string $title
		 * @param bool $ignore_special_symbols
		 * @return string
		 */
		public function transliterate($title, $ignore_special_symbols = false)
		{
			$origin_title = $title;
			$iso9_table = $this->getSymbolsPack();

			$title = strtr($title, $iso9_table);

			if( function_exists('iconv') ) {
				$title = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title);
			}

			if( !$ignore_special_symbols ) {
				$title = preg_replace("/[^A-Za-z0-9'_\-\.]/", '-', $title);
				$title = preg_replace('/\-+/', '-', $title);
				$title = preg_replace('/^-+/', '', $title);
				$title = preg_replace('/-+$/', '', $title);
			}

			return apply_filters('wbcr_ctl_transliterate', $title, $origin_title, $iso9_table);
		}


		/**
		 * Метод возвращает базу символов в зависимости от установленной локали
		 *
		 * @since 1.1.1
		 * @return array
		 */
		public function getSymbolsPack()
		{
			$loc = get_locale();

			$ret = array(
				// russian
				'А' => 'A',
				'а' => 'a',
				'Б' => 'B',
				'б' => 'b',
				'В' => 'V',
				'в' => 'v',
				'Г' => 'G',
				'г' => 'g',
				'Д' => 'D',
				'д' => 'd',
				'Е' => 'E',
				'е' => 'e',
				'Ё' => 'Jo',
				'ё' => 'jo',
				'Ж' => 'Zh',
				'ж' => 'zh',
				'З' => 'Z',
				'з' => 'z',
				'И' => 'I',
				'и' => 'i',
				'Й' => 'J',
				'й' => 'j',
				'К' => 'K',
				'к' => 'k',
				'Л' => 'L',
				'л' => 'l',
				'М' => 'M',
				'м' => 'm',
				'Н' => 'N',
				'н' => 'n',
				'О' => 'O',
				'о' => 'o',
				'П' => 'P',
				'п' => 'p',
				'Р' => 'R',
				'р' => 'r',
				'С' => 'S',
				'с' => 's',
				'Т' => 'T',
				'т' => 't',
				'У' => 'U',
				'у' => 'u',
				'Ф' => 'F',
				'ф' => 'f',
				'Х' => 'H',
				'х' => 'h',
				'Ц' => 'C',
				'ц' => 'c',
				'Ч' => 'Ch',
				'ч' => 'ch',
				'Ш' => 'Sh',
				'ш' => 'sh',
				'Щ' => 'Shh',
				'щ' => 'shh',
				'Ъ' => '',
				'ъ' => '',
				'Ы' => 'Y',
				'ы' => 'y',
				'Ь' => '',
				'ь' => '',
				'Э' => 'Je',
				'э' => 'je',
				'Ю' => 'Ju',
				'ю' => 'ju',
				'Я' => 'Ja',
				'я' => 'ja',
				// global
				'Ґ' => 'G',
				'ґ' => 'g',
				'Є' => 'Ie',
				'є' => 'ie',
				'І' => 'I',
				'і' => 'i',
				'Ї' => 'I',
				'ї' => 'i',
				'Ї' => 'i',
				'ї' => 'i',
				'Ё' => 'Jo',
				'ё' => 'jo',
				'й' => 'i',
				'Й' => 'I'
			);

			// ukrainian
			if( $loc == 'uk' ) {
				$ret = array_merge($ret, array(
					'Г' => 'H',
					'г' => 'h',
					'И' => 'Y',
					'и' => 'y',
					'Х' => 'Kh',
					'х' => 'kh',
					'Ц' => 'Ts',
					'ц' => 'ts',
					'Щ' => 'Shch',
					'щ' => 'shch',
					'Ю' => 'Iu',
					'ю' => 'iu',
					'Я' => 'Ia',
					'я' => 'ia',

				));
				//bulgarian
			} elseif( $loc == 'bg' || $loc == 'bg_BG' ) {
				$ret = array_merge($ret, array(
					'Щ' => 'Sht',
					'щ' => 'sht',
					'Ъ' => 'a',
					'ъ' => 'a'
				));
			}

			// Georgian
			if( $loc == 'ka_GE' ) {
				$ret = array_merge($ret, array(
					'ა' => 'a',
					'ბ' => 'b',
					'გ' => 'g',
					'დ' => 'd',
					'ე' => 'e',
					'ვ' => 'v',
					'ზ' => 'z',
					'თ' => 'th',
					'ი' => 'i',
					'კ' => 'k',
					'ლ' => 'l',
					'მ' => 'm',
					'ნ' => 'n',
					'ო' => 'o',
					'პ' => 'p',
					'ჟ' => 'zh',
					'რ' => 'r',
					'ს' => 's',
					'ტ' => 't',
					'უ' => 'u',
					'ფ' => 'ph',
					'ქ' => 'q',
					'ღ' => 'gh',
					'ყ' => 'qh',
					'შ' => 'sh',
					'ჩ' => 'ch',
					'ც' => 'ts',
					'ძ' => 'dz',
					'წ' => 'ts',
					'ჭ' => 'tch',
					'ხ' => 'kh',
					'ჯ' => 'j',
					'ჰ' => 'h'
				));
			}

			// Greek
			if( $loc == 'el' ) {
				$ret = array_merge($ret, array(
					'α' => 'a',
					'β' => 'v',
					'γ' => 'g',
					'δ' => 'd',
					'ε' => 'e',
					'ζ' => 'z',
					'η' => 'h',
					'θ' => 'th',
					'ι' => 'i',
					'κ' => 'k',
					'λ' => 'l',
					'μ' => 'm',
					'ν' => 'n',
					'ξ' => 'x',
					'ο' => 'o',
					'π' => 'p',
					'ρ' => 'r',
					'σ' => 's',
					'ς' => 's',
					'τ' => 't',
					'υ' => 'u',
					'φ' => 'f',
					'χ' => 'ch',
					'ψ' => 'ps',
					'ω' => 'o',
					'Α' => 'A',
					'Β' => 'V',
					'Γ' => 'G',
					'Δ' => 'D',
					'Ε' => 'E',
					'Ζ' => 'Z',
					'Η' => 'H',
					'Θ' => 'TH',
					'Ι' => 'I',
					'Κ' => 'K',
					'Λ' => 'L',
					'Μ' => 'M',
					'Ν' => 'N',
					'Ξ' => 'X',
					'Ο' => 'O',
					'Π' => 'P',
					'Ρ' => 'R',
					'Σ' => 'S',
					'Τ' => 'T',
					'Υ' => 'U',
					'Φ' => 'F',
					'Χ' => 'CH',
					'Ψ' => 'PS',
					'Ω' => 'O',
					'ά' => 'a',
					'έ' => 'e',
					'ή' => 'h',
					'ί' => 'i',
					'ό' => 'o',
					'ύ' => 'u',
					'ώ' => 'o',
					'Ά' => 'A',
					'Έ' => 'E',
					'Ή' => 'H',
					'Ί' => 'I',
					'Ό' => 'O',
					'Ύ' => 'U',
					'Ώ' => 'O',
					'ϊ' => 'i',
					'ΐ' => 'i',
					'ΰ' => 'u',
					'ϋ' => 'u',
					'Ϊ' => 'I',
					'Ϋ' => 'U'
				));
			}

			return apply_filters('wbcr_ctl_default_symbols_pack', $ret);
		}
	}

	new WCTL_Plugin();
?>
