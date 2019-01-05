<?php
/*
  Plugin Name: WOOBE - WooCommerce Bulk Editor
  Plugin URI: https://bulk-editor.com/
  Description: WOOBE - WooCommerce Bulk Editor. Tools for managing and bulk edit WooCommerce Products data in the reliable and flexible way! Be professionals with managing data of your e-shop!
  Requires at least: WP 4.1.0
  Tested up to: WP 4.9.8
  Author: realmag777
  Author URI: https://pluginus.net/
  Version: 1.0.3
  Requires PHP: 5.4
  Tags: woocommerce,woocommerce bulk edit,bulk edit,bulk,products editor
  Text Domain: woo-bulk-editor
  Domain Path: /languages
  WC requires at least: 3.3.1
  WC tested up to: 3.5.1
  Forum URI: https://bulk-editor.com/forum/
 */

//update_option('woobe_options_' . get_current_user_id(), ''); //absolute reset of the plugin settings - be care
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//***

define('WOOBE_PATH', plugin_dir_path(__FILE__));
define('WOOBE_LINK', plugin_dir_url(__FILE__));
define('WOOBE_ASSETS_LINK', WOOBE_LINK . 'assets/');
define('WOOBE_DATA_PATH', WOOBE_PATH . 'data/');
define('WOOBE_PLUGIN_NAME', plugin_basename(__FILE__));
define('WOOBE_VERSION', '1.0.3');
define('WOOBE_MIN_WOOCOMMERCE_VERSION', '3.3.1');

//libs
include WOOBE_PATH . 'lib/storage.php';

//data
include_once WOOBE_DATA_PATH . 'fields.php';
include_once WOOBE_DATA_PATH . 'settings.php';

//classes
include WOOBE_PATH . 'classes/helper.php';
include WOOBE_PATH . 'classes/models/profiles.php';
include WOOBE_PATH . 'classes/models/settings.php';
include WOOBE_PATH . 'classes/models/products.php';
include WOOBE_PATH . 'classes/ext.php';

//12-11-2018
final class WOOBE {

    public $storage = NULL;
    public $settings = NULL;
    public $products = NULL;
    public $profiles = NULL;
    private $ext = array('filters', 'bulk', 'export', 'meta', 'history', 'calculator', 'info', 'fprofiles', 'bulkoperations');
    public $show_notes = true;

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    public function init() {

        if (!is_admin()) {
            return;
        }

        if (!class_exists('WooCommerce')) {
            return;
        }

        //no one operation is possible if user is not a products administrator!!
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        //WOOCS compatibility
        if (class_exists('WOOCS')) {
            global $WOOCS;
            $WOOCS->reset_currency();
            remove_filter('woocommerce_product_get_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 2);
            remove_filter('woocommerce_product_variation_get_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 2);
            remove_filter('woocommerce_product_variation_get_regular_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 2);
            remove_filter('woocommerce_product_variation_get_sale_price', array($WOOCS, 'raw_sale_price_filter'), 9999, 2);
            remove_filter('woocommerce_product_get_regular_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 2);
            remove_filter('woocommerce_product_get_sale_price', array($WOOCS, 'raw_woocommerce_price_sale'), 9999, 2);
            remove_filter('woocommerce_get_variation_regular_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 4);
            remove_filter('woocommerce_get_variation_sale_price', array($WOOCS, 'raw_woocommerce_price'), 9999, 4);
            remove_filter('woocommerce_variation_prices', array($WOOCS, 'woocommerce_variation_prices'), 9999, 3);
        }

//***

        add_filter('plugin_action_links_' . WOOBE_PLUGIN_NAME, array($this, 'plugin_action_links'), 50);

//***

        if (isset($_GET['page']) AND $_GET['page'] == 'woobe') {
            add_action('admin_notices', function() {
                $user_id = get_current_user_id();
                if (!get_user_meta($user_id, 'woobe_notice_dismissed')) {
                    echo '<div class="notice notice-warning"><p>' . sprintf(__('If you not familiar with the plugin, firstly %s please', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                                'title' => __('visit this page', 'woo-bulk-editor'),
                                'href' => 'https://bulk-editor.com/document/woocommerce-products-editor/',
                                'target' => '_blank'
                    ))) . '</p><a href="edit.php?post_type=product&page=woobe&woobe-notice-dismissed=1" class="notice-dismiss"></a></div>';
                }
            });
            add_action('admin_init', function() {
                $user_id = get_current_user_id();
                if (isset($_GET['woobe-notice-dismissed'])) {
                    add_user_meta($user_id, 'woobe_notice_dismissed', 'true', true);
                }
            });
        }

        //side bar menu
        add_action('admin_menu', function() {
            add_submenu_page('edit.php?post_type=product', __('Bulk Editor', 'woo-bulk-editor'), __('Bulk Editor', 'woo-bulk-editor'), 'manage_woocommerce', 'woobe', function() {
                $this->print_plugin_options();
            });
        }, 99);

        add_action('admin_bar_menu', function($wp_admin_bar) {
            $opt = get_option('woobe_options_' . get_current_user_id()); //not beauty but we need it here
            if (isset($opt['options']['show_admin_bar_menu_btn']) AND intval($opt['options']['show_admin_bar_menu_btn']) === 1) {
                $args = array(
                    'id' => 'woobe-btn',
                    'title' => __('Bulk Editor', 'woo-bulk-editor'),
                    'href' => admin_url('edit.php?post_type=product&page=woobe'),
                    'meta' => array(
                        'class' => 'wp-admin-bar-woobe-btn',
                        'title' => 'WOOBE - WooCommerce Bulk Editor'
                    )
                );
                $wp_admin_bar->add_node($args);
            }
            unset($opt);
        }, 250);


//do not init hooks and all other parts of the plugins as we not need it on all site pages
        if (!$this->is_should_init()) {
            return;
        }

//***
//include extensions and their hooks
        if (!empty($this->ext)) {
            foreach ($this->ext as $ext_slug) {
                include WOOBE_PATH . 'ext' . DIRECTORY_SEPARATOR . $ext_slug . DIRECTORY_SEPARATOR . $ext_slug . '.php';
                $class_name = 'WOOBE_' . strtoupper($ext_slug);
                $this->$ext_slug = new $class_name();
            }
        }

//woobe_ext - include extensions from wp-content folder
        $woobe_more_ext_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'woobe_ext';
        if (file_exists($woobe_more_ext_path)) {
            $dir = new DirectoryIterator($woobe_more_ext_path);
            foreach ($dir as $fileinfo) {
                if ($fileinfo->isDir() AND ! $fileinfo->isDot()) {
                    $ext_slug = trim($fileinfo->getFilename());
                    include $woobe_more_ext_path . DIRECTORY_SEPARATOR . $ext_slug . DIRECTORY_SEPARATOR . $ext_slug . '.php';
                    $class_name = 'WOOBE_' . strtoupper($ext_slug);
                    $this->$ext_slug = new $class_name();
                    $this->ext[] = $ext_slug;
                }
            }
        }


//***
//init variables and hooks of the extensions will be applied, for example hook woobe_extend_fields
        $this->storage = new WOOBE_STORAGE();
        $this->settings = new WOOBE_SETTINGS();
        $this->profiles = new WOOBE_PROFILES($this->settings);
        $this->products = new WOOBE_PRODUCTS($this->settings, $this->storage);


        if (!empty($this->ext)) {
            foreach ($this->ext as $ext_slug) {
//we do it to allow ext hooks works everywhere (in the application and all its extensions)
                $this->$ext_slug->init_vars($this->storage, $this->profiles, $this->settings, $this->products);
            }
        }


//***

        load_plugin_textdomain('woo-bulk-editor', false, 'woo-bulk-editor/languages');


//ajax
        add_action('wp_ajax_woobe_get_products', array($this, 'woobe_get_products'), 1);
        add_action('wp_ajax_woobe_update_page_field', array($this, 'woobe_update_page_field'), 1);
        add_action('wp_ajax_woobe_redraw_table_row', array($this, 'woobe_redraw_table_row'), 1);
        add_action('wp_ajax_woobe_get_post_field', array($this, 'get_post_field'), 1);
        add_action('wp_ajax_woobe_get_downloads', array($this, 'get_downloads'), 1);
        add_action('wp_ajax_woobe_get_gallery', array($this, 'woobe_get_gallery'), 1);
        add_action('wp_ajax_woobe_get_upsells', array($this, 'woobe_get_upsells'), 1);
        add_action('wp_ajax_woobe_get_cross_sells', array($this, 'woobe_get_cross_sells'), 1);
        add_action('wp_ajax_woobe_get_grouped', array($this, 'woobe_get_grouped'), 1);

        add_action('wp_ajax_woobe_create_new_product', array($this, 'woobe_create_new_product'), 1);
        add_action('wp_ajax_woobe_duplicate_products', array($this, 'woobe_duplicate_products'), 1);
        add_action('wp_ajax_woobe_delete_products', array($this, 'woobe_delete_products'), 1);


        add_action('wp_ajax_woobe_create_new_term', array($this, 'woobe_create_new_term'), 1);



//***
        add_action('wp_ajax_woobe_title_autocomplete', array($this, 'woobe_title_autocomplete'));
        add_action('wp_ajax_woobe_save_options', array($this, 'woobe_save_options'), 1);



//***
        add_post_type_support('product', 'author');
    }

    /**
     * Show action links on the plugins page screen
     */
    public function plugin_action_links($links) {

        return array_merge(array(
            '<a href="' . admin_url('edit.php?post_type=product&page=woobe') . '">' . __('Products Editor', 'woo-bulk-editor') . '</a>',
            '<a target="_blank" href="' . esc_url('https://bulk-editor.com/') . '"><span class="icon-book"></span>&nbsp;' . __('Documentation', 'woo-bulk-editor') . '</a>'
                ), $links);
    }

    public function admin_enqueue_scripts() {
        if (isset($_GET['page']) AND $_GET['page'] == 'woobe') {
            ?>
            <script>
                var lang = {
                    move: "<?php echo __('move', 'woo-bulk-editor') ?>",
                    search: "<?php echo __('Search', 'woo-bulk-editor') ?>",
                    rest_failed: "<?php echo __('Failed', 'woo-bulk-editor') ?>",
                    error: "<?php echo __('Error', 'woo-bulk-editor') ?>",
                    delete: "<?php echo __('delete', 'woo-bulk-editor') ?>",
                    ignore: "<?php echo __('ignore', 'woo-bulk-editor') ?>",
                    no_deletable: "<?php echo __('This is not deletable!', 'woo-bulk-editor') ?>",
                    no_items: "<?php echo __('no items', 'woo-bulk-editor') ?>",
                    none: "<?php echo __('none', 'woo-bulk-editor') ?>",
                    no_data: "<?php echo __('no data', 'woo-bulk-editor') ?>",
                    loading: "<?php echo __('Loading', 'woo-bulk-editor') ?> ...",
                    loaded: "<?php echo __('Loaded', 'woo-bulk-editor') ?>.",
                    saved: "<?php echo __('Saved', 'woo-bulk-editor') ?>.",
                    saving: "<?php echo __('Saving', 'woo-bulk-editor') ?> ...",
                    apply: "<?php echo __('Apply', 'woo-bulk-editor') ?>",
                    cancel: "<?php echo __('Cancel', 'woo-bulk-editor') ?>",
                    canceled: "<?php echo __('Canceled', 'woo-bulk-editor') ?>",
                    sure: "<?php echo __('Sure?', 'woo-bulk-editor') ?>",
                    creating: "<?php echo __('Creating', 'woo-bulk-editor') ?> ...",
                    created: "<?php echo __('Created!', 'woo-bulk-editor') ?>",
                    duplicating: "<?php echo __('Duplicating', 'woo-bulk-editor') ?> ...",
                    duplicated: "<?php echo __('Duplicated!', 'woo-bulk-editor') ?>",
                    deleting: "<?php echo __('Deleting', 'woo-bulk-editor') ?> ...",
                    deleted: "<?php echo __('Deleted!', 'woo-bulk-editor') ?>",
                    reseting: "<?php echo __('Reseting', 'woo-bulk-editor') ?> ...",
                    reseted: "<?php echo __('Reseted!', 'woo-bulk-editor') ?>",
                    upload_image: "<?php echo __('Upload image', 'woo-bulk-editor') ?>",
                    upload_images: "<?php echo __('Upload images', 'woo-bulk-editor') ?>",
                    upload_file: "<?php echo __('Upload file', 'woo-bulk-editor') ?>",
                    fill_up_data: "<?php echo __('Fill up the data please!', 'woo-bulk-editor') ?>",
                    enter_duplicate_count: "<?php echo __('Enter how many time duplicate selected product(s)!', 'woo-bulk-editor') ?>",
                    enter_new_count: "<?php echo __('Enter how many new product(s) to create!', 'woo-bulk-editor') ?>",
                    search_input_placeholder: "<?php echo __('Text search by title or SKU', 'woo-bulk-editor') ?>",
                    show_panel: "<?php _e('Show: Filters/Bulk Edit/Export', 'woo-bulk-editor') ?>",
                    close_panel: "<?php _e('Hide: Filters/Bulk Edit/Export', 'woo-bulk-editor') ?>",
                    per_page: "<?php _e('Per page', 'woo-bulk-editor') ?>: ",
                    color_picker_col: "<?php _e('Select background color', 'woo-bulk-editor') ?>",
                    color_picker_txt: "<?php _e('Select text color', 'woo-bulk-editor') ?>",
                    sEmptyTable: "<?php _e('No data available in the table', 'woo-bulk-editor') ?>",
                    sInfo: "<?php _e('Showing _START_ to _END_ of _TOTAL_ entries', 'woo-bulk-editor') ?>",
                    sInfoEmpty: "<?php _e('Showing 0 to 0 of 0 entries', 'woo-bulk-editor') ?>",
                    sInfoFiltered: "<?php _e('(filtered from _MAX_ total entries)', 'woo-bulk-editor') ?>",
                    sLoadingRecords: "<?php _e('Loading...', 'woo-bulk-editor') ?>",
                    sProcessing: "<?php _e('Processing...', 'woo-bulk-editor') ?>",
                    sZeroRecords: "<?php _e('No matching records found', 'woo-bulk-editor') ?>",
                    sFirst: "<?php _e('First', 'woo-bulk-editor') ?>",
                    sLast: "<?php _e('Last', 'woo-bulk-editor') ?>",
                    sNext: "<?php _e('Next', 'woo-bulk-editor') ?>",
                    sPrevious: "<?php _e('Previous', 'woo-bulk-editor') ?>",
                    action_state_1: "<?php _e('all the products on the site', 'woo-bulk-editor') ?>",
                    action_state_2: "<?php _e('the filtered products. To remove the products filtering press reset button on the tools panel below', 'woo-bulk-editor') ?>",
                    action_state_31: "<?php _e('the selected products (variations)', 'woo-bulk-editor') ?>",
                    action_state_32: "<?php _e('You can reset selection of the products by its reset button on the panel of the editor OR uncheck them manually!', 'woo-bulk-editor') ?>",
                    term_maybe_exist: "<?php _e('Maybe term(s) with such name(s) already exists!', 'woo-bulk-editor') ?>",
                    free_ver_profiles: "<?php _e('In FREE version of the plugin you can create one profile only!', 'woo-bulk-editor') ?>",
                    append_sub_item: "<?php _e('append sub item', 'woo-bulk-editor') ?>",
                    is_deactivated_in_free: "<?php _e('This field is deactivated in FREE version for bulk edit!', 'woo-bulk-editor') ?>"
                };

                var woobe_settings = {
                    show_thumbnail_preview: <?php echo intval($this->settings->show_thumbnail_preview) ?>,
                    load_switchers: <?php echo intval($this->settings->load_switchers) ?>,
                    autocomplete_max_elem_count: <?php echo intval($this->settings->autocomplete_max_elem_count) ?>
                };

                var woobe_assets_link = "<?php echo WOOBE_ASSETS_LINK ?>";
                var spinner = woobe_assets_link + "/images/spinner.gif";

                //***

            <?php
            if (class_exists('SitePress')) {
                add_filter('woobe_current_language', function() {
//WPML compatibility
//because if it will be selected 'all' language - will be shown default one
                    return ICL_LANGUAGE_CODE;
                });
            }
            ?>

                var woobe_lang = '<?php echo apply_filters('woobe_current_language', '') ?>';//for translating compatibilities



            </script>

            <?php
            wp_enqueue_style('open_sans_font', 'https://fonts.googleapis.com/css?family=Open+Sans');
            wp_enqueue_style('woobe-bootstrap-grid', WOOBE_ASSETS_LINK . 'css/bootstrap-grid.css');
            wp_enqueue_style('woobe', WOOBE_ASSETS_LINK . 'css/woobe.css');
            wp_enqueue_style('woobe_scrollbar', WOOBE_ASSETS_LINK . 'css/jquery.scrollbar.css');
            wp_enqueue_style('woobe_fontello', WOOBE_ASSETS_LINK . 'css/fontello.css');

//***

            wp_enqueue_media();
            wp_enqueue_script('media-upload');
            wp_enqueue_style('thickbox');
            wp_enqueue_script('thickbox');

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');

            wp_enqueue_script('woobe_modernizr', WOOBE_ASSETS_LINK . 'js/modernizr.js');

            wp_enqueue_style('woobe_datatables', WOOBE_ASSETS_LINK . 'css/tables.css');
            wp_enqueue_script('woobe_datatables_net', WOOBE_ASSETS_LINK . 'js/jquery.dataTables.js', array('jquery'));
            wp_enqueue_script('woobe_data_tables', WOOBE_ASSETS_LINK . 'js/data-tables.js', array('woobe_datatables_net'));
            wp_enqueue_script('woobe_jquery_growl', WOOBE_ASSETS_LINK . 'js/jquery.growl.js', array('jquery'));

            wp_enqueue_style('woobe_switchery', WOOBE_ASSETS_LINK . 'js/switchery/switchery.min.css');
            wp_enqueue_script('woobe_switchery', WOOBE_ASSETS_LINK . 'js/switchery/switchery.min.js', array('jquery'));

//wp_enqueue_style('woobe_powertip', WOOBE_ASSETS_LINK . 'js/jquery.powertip/css/jquery.powertip.min.css');
//wp_enqueue_script('woobe_powertip', WOOBE_ASSETS_LINK . 'js/jquery.powertip/jquery.powertip.min.js', array('jquery'));

            wp_enqueue_style('woobe_chosen', WOOBE_ASSETS_LINK . 'js/chosen/chosen.min.css');
            wp_enqueue_script('woobe_chosen', WOOBE_ASSETS_LINK . 'js/chosen/chosen.jquery.min.js', array('jquery'));

            wp_enqueue_style('woobe_autocomplete', WOOBE_ASSETS_LINK . 'js/easy-autocomplete/easy-autocomplete.min.css');
            wp_enqueue_style('woobe_autocomplete_theme', WOOBE_ASSETS_LINK . 'js/easy-autocomplete/easy-autocomplete.themes.min.css');
            wp_enqueue_script('woobe_autocomplete', WOOBE_ASSETS_LINK . 'js/easy-autocomplete/jquery.easy-autocomplete.min.js', array('jquery'));

            wp_enqueue_style('woobe_datetimepicker', WOOBE_ASSETS_LINK . 'js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css');
            wp_enqueue_script('woobe_datetimepicker_moment', WOOBE_ASSETS_LINK . 'js/datepicker/moment-with-locales.min.js', array('jquery'));
            wp_enqueue_script('woobe_datetimepicker', WOOBE_ASSETS_LINK . 'js/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js', array('jquery'));

            wp_enqueue_script('woobe_placeholder_label', WOOBE_ASSETS_LINK . 'js/jquery.placeholder.label.min.js', array('jquery'));
            wp_enqueue_script('woobe_tooltip', WOOBE_ASSETS_LINK . 'js/tooltip.js', array('jquery'));

            wp_enqueue_script('woobe_tabs', WOOBE_ASSETS_LINK . 'js/tabs.js');
            wp_enqueue_script('woobe_scrollbar', WOOBE_ASSETS_LINK . 'js/jquery.scrollbar.min.js');
//wp_enqueue_script('woobe_popups', WOOBE_ASSETS_LINK . 'js/popups.js');
//***
            wp_enqueue_script('woobe', WOOBE_ASSETS_LINK . 'js/woobe.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'woobe_tabs'));
            do_action('woobe_ext_scripts'); //including extensions scripts
        }
    }

    public function print_plugin_options() {
        $args = array();
        $args['options'] = $this->settings->get_options();
        $args['total_settings'] = $this->settings->get_total_settings();
        $args['tax_keys'] = array();
        $args['attribute_keys'] = array();

        $args['is_popupeditor'] = FALSE;
        $args['is_downloads'] = FALSE;
        $args['is_gallery'] = FALSE;
        $args['is_upsells'] = FALSE;
        $args['is_cross_sells'] = FALSE;
        $args['is_grouped'] = FALSE;

        $args['meta_popup_editor'] = FALSE;

//to generate terms in popup taxonomies data
        if (!empty($this->settings->active_fields)) {
            foreach ($this->settings->active_fields as $k => $f) {
                if ($f['field_type'] == 'taxonomy' AND $f['edit_view'] == 'popup') {
                    $args['tax_keys'][] = $f['taxonomy'];
                }

                if ($f['field_type'] == 'attribute') {
                    $args['attribute_keys'][] = $k;
                }

//***

                if ($f['edit_view'] == 'popupeditor') {
                    $args['is_popupeditor'] = TRUE;
                }

                if ($f['edit_view'] == 'downloads_popup_editor') {
                    $args['is_downloads'] = TRUE;
                }

                if ($f['edit_view'] == 'gallery_popup_editor') {
                    $args['is_gallery'] = TRUE;
                }

                if ($f['edit_view'] == 'upsells_popup_editor') {
                    $args['is_upsells'] = TRUE;
                }

                if ($f['edit_view'] == 'cross_sells_popup_editor') {
                    $args['is_cross_sells'] = TRUE;
                }

                if ($f['edit_view'] == 'grouped_popup_editor') {
                    $args['is_grouped'] = TRUE;
                }

                if ($f['edit_view'] == 'meta_popup_editor') {
                    $args['meta_popup_editor'] = TRUE;
                }
            }
        }

        //***

        $args['active_fields'] = $this->settings->active_fields;
        $args['settings_fields'] = $this->settings->get_fields();
        $args['settings_fields_full'] = $this->settings->get_fields(false);
        $args['settings_fields_keys'] = $this->settings->get_fields_keys();
        $args['editable'] = $this->settings->editable;
        $args['default_sortby_col_num'] = $this->settings->get_default_sortby_col_num();
        $args['default_sort'] = $this->settings->default_sort;
        $args['no_order'] = $this->settings->no_order;
        $args['per_page'] = $this->settings->per_page;
        $args['show_notes'] = $this->show_notes;
        $args['current_user_role'] = $this->settings->current_user_role;
        $args['profiles'] = $this->profiles->get();

        //***

        echo WOOBE_HELPER::render_html(WOOBE_PATH . 'views/woobe.php', apply_filters('woobe_print_plugin_options', $args));
    }

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//ajax
    public function woobe_get_products($args = array(), $return = false) {

        if (!current_user_can('manage_woocommerce')) {
            return;
        }

//***

        $res = array();
        $res['draw'] = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
        $res['data'] = array();
        $fileds_keys = $this->settings->get_fields_keys();

        if (empty($args)) {
//for ajax only
            $args = array(
                'lang' => sanitize_key($_REQUEST['lang']),
                'per_page' => intval($_REQUEST['length']),
                'offset' => intval($_REQUEST['start']),
                'order_by' => $fileds_keys[intval($_REQUEST['order'][0]['column'])],
                'order' => sanitize_key($_REQUEST['order'][0]['dir']),
                'search' => $_REQUEST['search']['value']
            );
        }

        $products = $this->products->gets($args);

        $res['recordsFiltered'] = $res['recordsTotal'] = $products->found_posts;
        if ($products->found_posts > 0) {
            $products_types = array();
            $products_titles = array();
            foreach ($products->posts as $p) {
                $product_type = WC_Data_Store::load('product')->get_product_type($p->ID);
                $res['data'][] = $this->__pack_row($p, $product_type);
                $products_types[$p->ID] = $product_type;
                $products_titles[$p->ID] = str_replace('"', "", str_replace("'", "", $p->post_title));

//get variations if exists and requested
                if ($product_type == 'variable' AND ( isset($_REQUEST['woobe_show_variations']) AND intval($_REQUEST['woobe_show_variations']) > 0)) {
                    $variations = $this->products->gets(array('get_variations' => $p->ID));
                    if ($variations->found_posts > 0) {
                        foreach ($variations->posts as $var) {
                            $res['data'][] = $this->__pack_row($var, 'variation');
                            $products_types[$var->ID] = 'variation';
                            $products_titles[$var->ID] = str_replace('"', "", str_replace("'", "", $var->post_title));

//***

                            if ($this->settings->add_vars_to_var_title) {
                                //$attributes = implode(',', $this->products->get_product($var->ID)->get_variation_attributes());
                                //$products_titles[$var->ID] = $products_titles[$var->ID] . ' - ' . $attributes;
                                $products_titles[$var->ID] = $this->products->generate_product_title($this->products->get_product($var->ID));
                            }
                        }
                    }
                }

//***
//data for javascript functionality on the front
                $res['products_types'] = $products_types;
                $res['products_titles'] = $products_titles;
            }
        }

//***
//echo get_num_queries() . ' + ';exit;
        if (!$return) {
//if requested by ajax
            die(json_encode($res));
        }

        return $res;
    }

//service
    private function __pack_row($p, $product_type) {
        $row = array();
        $p = (array) $p;


        foreach ($this->settings->get_fields_keys() as $key) {
            $row[] = $this->wrap_field_val($p, $key, $product_type);
        }

        if ($product_type !== 'variation') {
//buttons: edit + view
            $row[] = WOOBE_HELPER::draw_link(array(
                        'title' => '&#xea0b;',
                        'href' => get_permalink($p['ID']),
                        'target' => '_blank',
                        'class' => 'button button-primary button-small',
                        'title_attr' => __('View the product on the site front', 'woo-bulk-editor')
                    )) . '&nbsp;' . WOOBE_HELPER::draw_link(array(
                        'title' => '&#xea25;',
                        'href' => get_admin_url() . 'post.php?post=' . $p['ID'] . '&action=edit',
                        'target' => '_blank',
                        'class' => 'button button-primary button-small',
                        'title_attr' => __('Editing of the product on its page', 'woo-bulk-editor')
            ));
        } else {
            $row[] = '';
        }

        return $row;
    }

//ajax
    public function woobe_update_page_field() {

        $product_id = intval($_REQUEST['product_id']);

        if ($product_id > 0 AND isset($_REQUEST['value'])) {

            if (is_array($_REQUEST['value'])) {
                $value = (array) $_REQUEST['value'];
            } else {
                $value = trim($_REQUEST['value']);
            }

            //$field_key = sanitize_key($_REQUEST['field']);//if sanitize not all meta keys works normally!!
            $field_key = trim($_REQUEST['field']);

//***
            //normalize calendar date
            if ($this->settings->active_fields[$field_key]['edit_view'] === 'calendar') {
                $value = $this->products->normalize_calendar_date($value, $field_key);
            }

//***
            //uploated to
            if (isset($_REQUEST['uploaded_to']) AND $_REQUEST['uploaded_to'] == 1 AND $field_key == '_thumbnail_id') {
                $id_th = intval($value);
                if ($id_th) {
                    $my_post = array();
                    $my_post['ID'] = $id_th;
                    $my_post['post_parent'] = $product_id;

                    wp_update_post(wp_slash($my_post));
                }
            }

            $value = $this->products->__string_replacer($value, $product_id);

            echo $this->products->update_page_field($product_id, $field_key, $value);
        }

        exit;
    }

//ajax
    public function woobe_redraw_table_row() {
        if (is_array($_REQUEST['value'])) {
            $value = (array) $_REQUEST['value'];
        } else {
            $value = trim($_REQUEST['value']);
        }

//***

        $product_id = intval($_REQUEST['product_id']);

        if (isset($_REQUEST['field']) AND ! empty($_REQUEST['field'])) {
            $field_key = sanitize_key($_REQUEST['field']);
            $this->products->update_page_field($product_id, $field_key, $value);
        }


//generate table row by $product_id
        $res = $this->woobe_get_products(array(
            'p' => $product_id,
            'post_type' => array('product', 'product_variation')
                ), true);


        echo(json_encode($res['data'][0]));
        exit;
    }

//ajax
    public function get_post_field() {
        echo $this->products->get_post_field(intval($_REQUEST['product_id']), sanitize_key($_REQUEST['field']), (isset($_REQUEST['post_parent']) ? $_REQUEST['post_parent'] : 0));
        exit;
    }

//ajax
    public function get_downloads() {

        $product_id = intval($_REQUEST['product_id']);

        if (!$product_id) {
            exit;
        }

        $product = $this->products->get_product($product_id);

        echo WOOBE_HELPER::render_html(WOOBE_PATH . 'views/parts/product-downloads.php', array(
            'downloadable_files' => $product->get_downloads('edit')
        ));


        exit;
    }

//ajax
    public function woobe_get_gallery() {

        $product_id = intval($_REQUEST['product_id']);

        if (!$product_id) {
            exit;
        }

        $product = $this->products->get_product($product_id);

        echo WOOBE_HELPER::render_html(WOOBE_PATH . 'views/parts/product-gallery.php', array(
            'images' => $product->get_gallery_image_ids('edit')
        ));


        exit;
    }

//ajax
    public function woobe_get_upsells() {

        $product_id = intval($_REQUEST['product_id']);

        if (!$product_id) {
            exit;
        }

        $product = $this->products->get_product($product_id);

        echo WOOBE_HELPER::render_html(WOOBE_PATH . 'views/parts/product-upsells.php', array(
            'products' => $product->get_upsell_ids('edit')
        ));


        exit;
    }

//ajax
    public function woobe_get_cross_sells() {

        $product_id = intval($_REQUEST['product_id']);

        if (!$product_id) {
            exit;
        }

        $product = $this->products->get_product($product_id);

        echo WOOBE_HELPER::render_html(WOOBE_PATH . 'views/parts/product-cross-sells.php', array(
            'products' => $product->get_cross_sell_ids('edit')
        ));


        exit;
    }

//ajax
    public function woobe_get_grouped() {

        $product_id = intval($_REQUEST['product_id']);

        if (!$product_id) {
            exit;
        }

        $product = $this->products->get_product($product_id);

        echo WOOBE_HELPER::render_html(WOOBE_PATH . 'views/parts/product-grouped.php', array(
            'products' => $product->get_children('edit')
        ));


        exit;
    }

//ajax
    public function woobe_save_options() {

        if (!current_user_can('manage_woocommerce')) {
            die('0');
        }

        $data = array();
        parse_str($_REQUEST['formdata'], $data);

        if (isset($data['woobe_options'])) {
            if (is_array($data['woobe_options'])) {
                $this->settings->update_options($data['woobe_options']);
            }

            //***
            //save shop manager fields visibility
            if ($this->settings->current_user_role == 'administrator') {
                $shop_manager_visibility = array();

                foreach ($data['woobe_options']['fields'] as $key => $v) {
                    if (isset($v['shop_manager_visibility'])) {
                        $shop_manager_visibility[$key] = intval($v['shop_manager_visibility']);
                    }
                }

                update_option('woobe_shop_manager_visibility', $shop_manager_visibility);
            }
        }

        exit;
    }

//ajax
    public function woobe_title_autocomplete() {
        $results = array();
        $results[] = array(
            "name" => __("Products not found!", 'woo-bulk-editor'),
            "id" => 0,
            "type" => "",
            "link" => "#",
            "icon" => WOOBE_ASSETS_LINK . 'images/not-found.jpg'
        );

//***

        if (!empty($_REQUEST['woobe_txt_search'])) {
            $args = array(
                'nopaging' => true,
                'post_type' => array('product', 'product_variation'),
                'post_status' => array_keys(get_post_statuses()),
                'order_by' => 'title',
                'order' => 'ASC',
                'per_page' => intval($_REQUEST['auto_res_count']) > 0 ? intval($_REQUEST['auto_res_count']) : 10,
                'max_num_pages' => intval($_REQUEST['auto_res_count']) > 0 ? intval($_REQUEST['auto_res_count']) : 10
            );

//***

            if (!empty($_REQUEST['exept_ids'])) {
                $exept_ids = array(); //which products exclude as they are on the list already
                parse_str($_REQUEST['exept_ids'], $exept_ids);
                $args['post__not_in'] = $exept_ids['woobe_prod_ids'];
            }

//***
            $st = $_REQUEST['woobe_txt_search'];
            $_REQUEST['woobe_txt_search'] = array();
            $_REQUEST['woobe_txt_search_behavior'] = array();
            $_REQUEST['woobe_txt_search']['post_title'] = $st;
            $_REQUEST['woobe_txt_search_behavior']['post_title'] = 'like';
            $this->products->suppress_filters = true;
            add_filter('posts_where', array($this->filters, 'posts_txt_where'), 101);
            $query = $this->products->gets($args);

//+++
//http://easyautocomplete.com/guide
            if ($query->have_posts()) {
                $results = array();
                foreach ($query->posts as $p) {
                    $data = array(
                        "name" => $p->post_title . ' (#' . $p->ID . ')',
                        "id" => $p->ID,
                        "type" => "product"
                    );
                    if (has_post_thumbnail($p->ID)) {
                        $img_src = wp_get_attachment_image_src(get_post_thumbnail_id($p->ID), 'thumbnail');
//$data['icon'] = woobe_aq_resize($img_src[0], 100, 100, true);
                        $data['icon'] = $img_src[0];
                    } else {
                        $data['icon'] = WOOBE_ASSETS_LINK . 'images/not-found.jpg';
                    }
                    $data['link'] = get_post_permalink($p->ID);
                    $results[] = $data;
                }
            }
        }


        die(json_encode($results));
    }

//ajax
    public function woobe_create_new_product() {

        if (!current_user_can('manage_woocommerce')) {
            die('0');
        }
//also: http://woocommerce.wp-a2z.org/oik_api/wc_api_productscreate_product/
        $wp_rest_request = new WP_REST_Request('POST');
        $wp_rest_request->set_body_params(array(
            'name' => __('New Product', 'woo-bulk-editor'),
            'description' => '',
            'status' => 'draft'
        ));
        $products_controller = new WC_REST_Products_Controller();

        $to_create = intval($_REQUEST['to_create']);
        while ($to_create) {
            $products_controller->create_item($wp_rest_request);
            $to_create--;
        }

//to see new products on the top after redrawing - do it by js
//$this->storage->set_val('woobe_products_order_by', 'ID');
//$this->storage->set_val('woobe_products_order', 'DESC');

        exit;
    }

//ajax
    public function woobe_duplicate_products() {

        if (!current_user_can('manage_woocommerce')) {
            die('0');
        }

        //test of non-interrupt system (if server error occurred)
        //$error = 'Always throw this error';
        //throw new Exception($error);

        if (!empty($_REQUEST['products_ids'])) {
            if (!class_exists('WC_Admin_Duplicate_Product', false)) {
                include_once (plugin_dir_path('woocommerce/includes/admin/class-wc-admin-duplicate-product.php'));
            }
            $duplicator = new WC_Admin_Duplicate_Product();
            //$cached_products = $this->storage->get_val('woobe_cached_products');

            foreach ($_REQUEST['products_ids'] as $product_id) {

                $product = $this->products->get_product($product_id);

                //when duplication do some copies of the same product - just idea
                /*
                  if (isset($cached_products[$product_id])) {
                  $product = $cached_products[$product_id];
                  } else {
                  $product = $this->products->get_product($product_id);
                  $cached_products[$product_id] = $product;
                  $this->storage->set_val('woobe_cached_products', $cached_products);
                  }
                 */
                //duplication of variation is locked
                if ($product->get_type() === 'variation') {
                    continue;
                }

                $duplicate = $duplicator->product_duplicate($product);
                $duplicate->set_slug($duplicate->get_title()/* . '-'.$duplicate->get_id() */);
                $duplicate->save();
                do_action('woocommerce_product_duplicate', $duplicate, $product);
//clean_post_cache($d->get_id());
            }

//wp_cache_flush();
        }

        die('done');
    }

//ajax
    public function woobe_delete_products() {

        if (!current_user_can('manage_woocommerce')) {
            die('0');
        }

        if (!empty($_REQUEST['products_ids'])) {
            foreach ($_REQUEST['products_ids'] as $product_id) {
//WC_API_Products::delete_product($product_id, FALSE);
//wc_delete_product_transients($product_id);
                $product = $this->products->get_product($product_id);
                $product->delete(false);
            }

//wp_cache_flush();
        }

        die('done');
    }

    public function wrap_field_val($post, $field_key, $product_type) {

        $res = NULL;

        $product = $this->products->get_product($post['ID']);
        $product_id = $product->get_id();

        if (isset($this->settings->active_fields[$field_key]['allow_product_types'])) {
            try {
                if (!in_array($product_type, $this->settings->active_fields[$field_key]['allow_product_types'])) {
                    return WOOBE_HELPER::draw_restricked();
                }
            } catch (Exception $e) {
//***
            }
        }

        if (isset($this->settings->active_fields[$field_key]['prohibit_product_types'])) {
            try {
                if (in_array($product_type, $this->settings->active_fields[$field_key]['prohibit_product_types'])) {
                    return WOOBE_HELPER::draw_restricked();
                }
            } catch (Exception $e) {
//***
            }
        }

//***
        $val = '';
        switch ($this->settings->active_fields[$field_key]['field_type']) {
            case 'meta':
            case 'prop':
            case 'attribute':
                //$val = get_post_meta($product_id, $field_key, true);
                $val = $this->products->get_post_field($product_id, $field_key);
                break;

            case 'taxonomy':
                $terms = $this->products->get_post_field($product_id, $field_key);

                $ids = array();
                $titles = array();

//***

                if (!empty($terms)) {
                    foreach ($terms as $t) {
                        $ids[] = $t->term_id;
                        $titles[] = $t->name;
                    }
                }

                if (!empty($ids)) {
                    $ids = array_map(function($v) {
                        return intval($v);
                    }, $ids);
                }

//***

                if ($this->settings->active_fields[$field_key]['type'] === 'array') {
                    $val = array(
                        'terms_ids' => $ids,
                        'terms_titles' => $titles
                    );

//for drop-down view
                    if ($this->settings->active_fields[$field_key]['edit_view'] == 'select') {
                        $val['selected'] = $val['terms_ids'];
                    }
                } else {
//string, for example: product_type
                    $val = $titles[0];
                }

                break;




            default:
                if (isset($post[$field_key])) {
                    $val = $post[$field_key];
//for variations
                    if ($field_key === 'post_title' AND $post['post_type'] === 'product_variation') {
                        if ($this->settings->add_vars_to_var_title) {
                            //$attributes = implode(',', $this->products->get_product($post['ID'])->get_variation_attributes());
                            //$val = $val . ' - ' . $attributes;
                            $val = $this->products->generate_product_title($product);
                        }
                    }
                }
                break;
        }

//***

        switch ($this->settings->active_fields[$field_key]['edit_view']) {
            case 'select':

                $select_options = $this->settings->active_fields[$field_key]['select_options'];

                //fix for product variations statuses
                if ($field_key === 'post_status') {
                    if ($product->is_type('variation')) {
                        unset($select_options['draft']);
                        unset($select_options['pending']);
                    }
                }

                //***

                $res = WOOBE_HELPER::draw_select(array(
                            'field' => $field_key,
                            'product_id' => $product_id,
                            'class' => 'woobe_data_select',
                            'options' => $select_options,
                            'selected' => (isset($val['selected']) ? $val['selected'] : $val),
                            'onchange' => 'woobe_act_select(this)'
                ));
                break;


            case 'multi_select':
                $res = WOOBE_HELPER::render_html(WOOBE_PATH . 'views/elements/multi_select.php', array(
                            'field_key' => $field_key,
                            'product_id' => $product_id,
                            'val' => $val,
                            'active_fields' => $this->settings->active_fields,
                            'post' => $post,
                ));
                break;

            case 'popup':
                $res = WOOBE_HELPER::draw_taxonomy_popup_btn($val, $field_key, $post);
                break;

            case 'popupeditor':
                $res = WOOBE_HELPER::draw_popup_editor_btn($val, $field_key, $post);
                break;

            case 'downloads_popup_editor':
                $res = WOOBE_HELPER::draw_downloads_popup_editor_btn($field_key, $product_id);
                break;

            case 'gallery_popup_editor':
                $res = WOOBE_HELPER::draw_gallery_popup_editor_btn($field_key, $product_id);
                break;

            case 'upsells_popup_editor':
                $res = WOOBE_HELPER::draw_upsells_popup_editor_btn($field_key, $product_id);
                break;

            case 'cross_sells_popup_editor':
                $res = WOOBE_HELPER::draw_cross_sells_popup_editor_btn($field_key, $product_id);
                break;

            case 'grouped_popup_editor':
                $res = WOOBE_HELPER::draw_grouped_popup_editor_btn($field_key, $product_id);
                break;

            case 'meta_popup_editor':
                $res = WOOBE_HELPER::draw_meta_popup_editor_btn($field_key, $product_id);
                break;

            case 'thumbnail':
                $thumbnail = wp_get_attachment_image_src($val, 'thumbnail');
                $full = wp_get_attachment_image_src($val, 'full');

                if (!empty($thumbnail)) {
                    $thumbnail = $thumbnail[0];
                    $full = $full[0];
                } else {
                    $thumbnail = WOOBE_ASSETS_LINK . 'images/not-found.jpg';
                    $full = WOOBE_ASSETS_LINK . 'images/not-found.jpg';
                }

                $onmouseover = '';
                if ($this->settings->show_thumbnail_preview) {
                    $onmouseover = 'onmouseover="woobe_init_image_preview(this)"';
                }

                $res = '<a href="' . $full . '" onclick="return woobe_act_thumbnail(this)" ' . $onmouseover . ' title="' . $post['post_title'] . '"><img src="' . $thumbnail . '" class="attachment-thumbnail size-thumbnail" alt="" /></a>';
                break;

            case 'switcher':
                $labels = array_values($this->settings->active_fields[$field_key]['select_options']);
                $values = array_keys($this->settings->active_fields[$field_key]['select_options']);
                $res = WOOBE_HELPER::draw_advanced_switcher(($val == $values[0] ? TRUE : FALSE), $product_id . '_' . $field_key, $field_key, array('true' => $labels[0], 'false' => $labels[1]), array('true' => $values[0], 'false' => $values[1]), 'yes');
                break;

            case 'calendar':
                if ($this->settings->active_fields[$field_key]['type'] === 'timestamp') {
                    $val = strtotime($val);
                }

                $post_title = $post['post_title'];

                if ($post['post_type'] === 'product_variation') {
                    if ($this->settings->add_vars_to_var_title) {
                        //$attributes = implode(',', $this->products->get_product($post['ID'])->get_variation_attributes());
                        //$post_title = $post_title . ' - ' . $attributes;
                        $post_title = $this->products->generate_product_title($this->products->get_product($product_id));
                    }
                }

                $res = WOOBE_HELPER::draw_calendar($product_id, $post_title . ' (' . $this->settings->active_fields[$field_key]['title'] . ')', $field_key, $val);
                break;

            case 'checkbox':
//using for products selection
                $res = WOOBE_HELPER::draw_checkbox(array(
                            'class' => 'woobe_product_check',
                            'data-product-id' => $product_id
                ));
                break;

            default:
//textinput
                $sanitize = '';
                if (isset($this->settings->active_fields[$field_key]['sanitize'])) {
                    $sanitize = $this->settings->active_fields[$field_key]['sanitize'];
                }

                $res = $this->products->__sanitize_answer_value($field_key, $sanitize, $val);

                break;
        }

        //***
        //lets show product ID as LINK to the product
        if ($field_key === 'ID') {
            $class = 'woobe-id-permalink';
            $title = '';

            if ($product->get_type() === 'variable') {
                $class .= ' woobe-id-permalink-var';
                $title = __('see the product on the site OR select products variations if they are shown', 'woo-bulk-editor');
            }

            $res = '<a href="' . get_permalink($res) . '" class="' . $class . '" title="' . $title . '" target="_blank">' . $res . '</a>';
        }

        //***
        /*
          $woobe_operation_time = get_option('woobe_operation_time', 0);
          if (!$woobe_operation_time) {
          update_option('woobe_operation_time', time());
          }
         */
        //***

        return $res;
    }

//ajax
    public function woobe_create_new_term() {

        $titles = $_REQUEST['titles'];
        $slugs = $_REQUEST['slugs'];
        $taxonomy = $_REQUEST['tax_key'];

        //***

        if (!empty($titles)) {

            if (substr_count($titles, ',') > 0) {
                $titles = explode(',', $titles);
            } else {
                $titles = array($titles);
            }


            if (substr_count($slugs, ',') > 0) {
                $slugs = explode(',', $slugs);
            } else {
                $slugs = array($slugs);
            }

            //***

            $terms_ids = array();
            foreach ($titles as $k => $t) {
                $t = trim($t);
                if (!term_exists($t, $taxonomy)) {
                    if (!empty($t)) {
                        $res = wp_insert_term($t, $taxonomy, array(
                            'parent' => intval($_REQUEST['parent']),
                            'slug' => (isset($slugs[$k]) ? trim($slugs[$k]) : '')
                        ));
                        $terms_ids[] = $res['term_id'];
                    } else {
                        unset($titles[$k]);
                    }
                }
            }

            //***

            echo json_encode(array(
                'titles' => array_reverse($titles),
                'terms_ids' => array_reverse($terms_ids),
                'terms' => WOOBE_HELPER::get_taxonomies_terms_hierarchy($taxonomy)
            ));
        }
        exit;
    }

//do not init functionality on all site pages as it not nessesary
    private function is_should_init() {
//do not onit it exept of one woobe page and its ajax requests
        $init = isset($_GET['page']) AND $_GET['page'] === 'woobe';

        if (defined('DOING_AJAX')) {
            if (strpos($_REQUEST['action'], 'woobe_') !== FALSE) {
                $init = true;
            }
        }

        return $init;
    }

}

//***

$WOOBE = new WOOBE();
$GLOBALS['WOOBE'] = $WOOBE;
add_action('init', array($WOOBE, 'init'), 9999);

