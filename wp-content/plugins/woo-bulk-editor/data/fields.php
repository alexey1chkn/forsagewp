<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function woobe_get_fields() {
    static $shipping_class = array();

    if ($shipping_class === array()) {
        $shipping_class[-1] = __('No shipping class', 'woo-bulk-editor');
        $terms = get_terms(array(
            'taxonomy' => 'product_shipping_class',
            'hide_empty' => false,
        ));

        if (!empty($terms)) {
            foreach ($terms as $term) {
                $shipping_class[intval($term->term_id)] = $term->name;
            }
        }
    }

    //***

    $wc_get_product_types = wc_get_product_types();
    foreach ($wc_get_product_types as $key => $t) {
        $wc_get_product_types[$key] = trim(str_replace('product', '', $t));
    }

    //***

    static $users = array();

    if ($users === array()) {
        $uu = get_users();
        $users = array();
        if (!empty($uu)) {
            foreach ($uu as $u) {
                $users[$u->data->ID] = $u->data->display_name;
            }
        }
    }
    //***

    return apply_filters('woobe_extend_fields', array(
        '__checker' => array(
            'show' => 1, //this is special checkbox only for functionality
            'title' => WOOBE_HELPER::draw_checkbox(array('class' => 'all_products_checker')),
            'desc' => __('Checkboxes for the products selection. Use SHIFT button on your keyboard to select multiple rows.', 'woo-bulk-editor'),
            'field_type' => 'none',
            'type' => 'number',
            'editable' => FALSE,
            'edit_view' => 'checkbox',
            'order' => FALSE,
            'move' => FALSE,
            'direct' => TRUE,
            'shop_manager_visibility' => 1
        ),
        'ID' => array(
            'show' => 1, //1 - enabled here by default
            'title' => 'ID',
            'field_type' => 'field',
            'type' => 'number',
            'editable' => FALSE,
            'edit_view' => 'textinput',
            'order' => TRUE,
            'move' => FALSE,
            'direct' => TRUE,
            'shop_manager_visibility' => 1
        ),
        '_thumbnail_id' => array(
            'show' => 1,
            'title' => __('Thumbnail', 'woo-bulk-editor'),
            'field_type' => 'meta',
            'type' => 'number',
            'editable' => true,
            'edit_view' => 'thumbnail',
            'order' => FALSE,
            'direct' => TRUE,
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'gallery' => array(
            'show' => 0,
            'title' => __('Gallery', 'woo-bulk-editor'),
            'desc' => __('Select any images which relate to the product', 'woo-bulk-editor'),
            'field_type' => 'gallery',
            'type' => 'textinput',
            'editable' => true,
            'edit_view' => 'gallery_popup_editor',
            'order' => FALSE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'post_title' => array(
            'show' => 1,
            'title' => __('Title', 'woo-bulk-editor'),
            'field_type' => 'field',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'order' => TRUE,
            'direct' => TRUE,
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        //'allow_product_types' => array('simple', 'external', 'variable', 'grouped')
        ),
        'post_content' => array(
            'show' => 1,
            'title' => __('Description', 'woo-bulk-editor'),
            'field_type' => 'field',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'popupeditor',
            'order' => FALSE,
            'direct' => TRUE,
            'shop_manager_visibility' => 1
        ),
        'post_excerpt' => array(
            'show' => 1,
            'title' => __('Short Desc.', 'woo-bulk-editor'),
            'field_type' => 'field',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'popupeditor',
            'order' => FALSE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'post_name' => array(
            'show' => 0,
            'title' => __('Slug', 'woo-bulk-editor'),
            'field_type' => 'field',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'urldecode',
            'order' => FALSE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'product_type' => array(
            'show' => 1,
            'title' => __('Type', 'woo-bulk-editor'),
            'field_type' => 'taxonomy',
            'taxonomy' => 'product_type',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'select',
            'select_options' => $wc_get_product_types,
            'order' => FALSE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'shop_manager_visibility' => 1
        ),
        'post_status' => array(
            'show' => 1,
            'title' => __('Status', 'woo-bulk-editor'),
            'field_type' => 'field',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'select',
            'select_options' => get_post_statuses(),
            'order' => FALSE,
            'direct' => TRUE,
            'shop_manager_visibility' => 1
        //'prohibit_product_types' => array('variation'),
        //'css_classes' => 'not-for-variations'
        ),
        'catalog_visibility' => array(
            'show' => 0,
            'title' => __('Catalog Visibility', 'woo-bulk-editor'),
            'desc' => __('This setting determines which shop pages products will be listed on', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'select',
            'select_options' => wc_get_product_visibility_options(),
            'order' => FALSE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'featured' => array(
            'show' => 0,
            'title' => __('Featured', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'switcher',
            'select_options' => array(
                'yes' => __('Yes', 'woo-bulk-editor'), //true
                'no' => __('No', 'woo-bulk-editor'), //false
            ),
            'order' => FALSE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'regular_price' => array(
            'show' => 1,
            'title' => __('Regular price', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_regular_price',
            'type' => 'number',
            'sanitize' => 'floatval',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'order' => TRUE,
            'direct' => TRUE,
            'allow_product_types' => array('external', 'simple', 'variation'),
            'shop_manager_visibility' => 1
        ),
        'sale_price' => array(
            'show' => 1,
            'title' => __('Sale price', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_sale_price',
            'type' => 'number',
            'sanitize' => 'floatval',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'order' => TRUE,
            'direct' => TRUE,
            'allow_product_types' => array('external', 'simple', 'variation'),
            'shop_manager_visibility' => 1
        ),
        'date_on_sale_from' => array(
            'show' => 0,
            'title' => __('Sale time from', 'woo-bulk-editor'),
            'desc' => __('The sale will end at the beginning of the set date', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_sale_price_dates_from',
            'type' => 'timestamp', //timestamp, unix
            'set_day_end' => FALSE, //false: 00:00:00, true: 23:59:59 - disabled in the code
            'editable' => TRUE,
            'edit_view' => 'calendar',
            'order' => TRUE,
            'direct' => FALSE,
            'allow_product_types' => array('external', 'simple', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'date_on_sale_to' => array(
            'show' => 0,
            'title' => __('Sale time to', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_sale_price_dates_to',
            'type' => 'timestamp', //timestamp, unix
            'set_day_end' => FALSE, //sale will end at the beginning of the set date - disabled in the code
            'editable' => TRUE,
            'edit_view' => 'calendar',
            'order' => TRUE,
            'direct' => FALSE,
            'allow_product_types' => array('external', 'simple', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'downloadable' => array(
            'show' => 0,
            'title' => __('Downloadable', 'woo-bulk-editor'),
            'desc' => __('Downloadable products give access to a file upon purchase.', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_downloadable',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'switcher',
            'select_options' => array(
                'yes' => __('Yes', 'woo-bulk-editor'), //true
                'no' => __('No', 'woo-bulk-editor'), //false
            ),
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'download_files' => array(
            'show' => 0,
            'title' => __('Down.Files', 'woo-bulk-editor'),
            'desc' => __('Downloadable files. Attention: maybe should be enabled Downloadable by logic!', 'woo-bulk-editor'),
            'field_type' => 'downloads',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'downloads_popup_editor',
            'order' => FALSE,
            'direct' => TRUE,
            'allow_product_types' => array('simple', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'download_limit' => array(
            'show' => 0,
            'title' => __('Download limit', 'woo-bulk-editor'),
            'desc' => __('Leave blank for unlimited re-downloads.  Attention: maybe should be enabled Downloadable by logic!', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'intval',
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variation'),
            'shop_manager_visibility' => 1
        ),
        'download_expiry' => array(
            'show' => 0,
            'title' => __('Download expiry', 'woo-bulk-editor'),
            'desc' => __('Enter the number of days before a download link expires, or leave blank. Attention: maybe should be enabled Downloadable by logic!', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'intval',
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variation'),
            'shop_manager_visibility' => 1
        ),
        'tax_status' => array(
            'show' => 0,
            'title' => __('Tax status', 'woo-bulk-editor'),
            'desc' => __('Define whether or not the entire product is taxable, or just the cost of shipping it.', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => FALSE,
            'edit_view' => 'select',
            'select_options' => array(//there is no special func to get this array (30-11-2017)
                'taxable' => __('Taxable', 'woocommerce'),
                'shipping' => __('Shipping only', 'woocommerce'),
                'none' => __('None', 'woocommerce'),
            ),
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'external', 'variable'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'tax_class' => array(
            'show' => 0,
            'title' => __('Tax class', 'woo-bulk-editor'),
            'desc' => __('Choose a tax class for this product. Tax classes are used to apply different tax rates specific to certain types of product.', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'select',
            'select_options' => wc_get_product_tax_class_options(),
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'external', 'variable', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'sku' => array(
            'show' => 1,
            'title' => __('SKU', 'woo-bulk-editor'),
            'desc' => __('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_sku',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'order' => TRUE,
            'direct' => TRUE,
            'shop_manager_visibility' => 1
        ),
        'manage_stock' => array(
            'show' => 1,
            'title' => __('Manage stock', 'woo-bulk-editor'),
            'desc' => __('Enable stock management at product level. ATTENTION: if to set count of products in Stock quantity, Manage stock option automatically set as TRUE!', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_manage_stock',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'switcher',
            'select_options' => array(
                'yes' => __('Yes', 'woo-bulk-editor'), //true
                'no' => __('No', 'woo-bulk-editor'), //false
            ),
            'order' => true,
            'direct' => TRUE,
            'allow_product_types' => array('simple', 'variable', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'stock_quantity' => array(
            'show' => 1,
            'title' => __('Stock quantity', 'woo-bulk-editor'),
            'desc' => __('Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level. ATTENTION: if to set count of products, Manage stock and Stock status options automatically set as TRUE!', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_stock',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'intval',
            'order' => TRUE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variable', 'variation'),
            'shop_manager_visibility' => 1
        ),
        'stock_status' => array(
            'show' => 1,
            'title' => __('Stock status', 'woo-bulk-editor'),
            'desc' => __('Controls whether or not the product is listed as in stock or out of stock on the frontend. Count products in stock can not be zero while activating!', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_stock_status',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'select',
            'select_options' => wc_get_product_stock_status_options(),
            'order' => TRUE,
            'direct' => TRUE,
            'allow_product_types' => array('simple', 'grouped', 'variation'),
            'shop_manager_visibility' => 1
        ),
        'backorders' => array(
            'show' => 0,
            'title' => __('Allow backorders', 'woo-bulk-editor'),
            'desc' => __('If managing stock, this controls whether or not backorders are allowed. If enabled, stock quantity can go below 0. Does Not work if the product Manage stock option is not activated!', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'select',
            'select_options' => wc_get_product_backorder_options(),
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'sold_individually' => array(
            'show' => 0,
            'title' => __('Sold individually', 'woo-bulk-editor'),
            'desc' => __('Enable this to only allow one of this item to be bought in a single order', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_sold_individually',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'switcher',
            'select_options' => array(
                'yes' => __('Yes', 'woo-bulk-editor'), //true
                'no' => __('No', 'woo-bulk-editor'), //false
            ),
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variable'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'weight' => array(
            'show' => 0,
            'title' => __('Weight', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_weight',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'floatval',
            'order' => TRUE,
            'direct' => TRUE,
            'allow_product_types' => array('simple', 'variable', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'length' => array(
            'show' => 0,
            'title' => __('Length', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_length',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'floatval',
            'order' => TRUE,
            'direct' => TRUE,
            'allow_product_types' => array('simple', 'variable', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'width' => array(
            'show' => 0,
            'title' => __('Width', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_width',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'floatval',
            'order' => TRUE,
            'direct' => TRUE,
            'allow_product_types' => array('simple', 'variable', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'height' => array(
            'show' => 0,
            'title' => __('Height', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_height',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'floatval',
            'order' => TRUE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variable', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'product_shipping_class' => array(
            'show' => 0,
            'title' => __('Shipping class', 'woo-bulk-editor'),
            'desc' => __('Shipping classes are used by certain shipping methods to group similar products.', 'woo-bulk-editor'),
            'field_type' => 'taxonomy',
            'taxonomy' => 'product_shipping_class',
            'type' => 'array',
            'editable' => TRUE,
            'edit_view' => 'select',
            'select_options' => $shipping_class,
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variable'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'upsell_ids' => array(
            'show' => 0,
            'title' => __('Upsells', 'woo-bulk-editor'),
            'desc' => __('Upsells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'woo-bulk-editor'),
            'field_type' => 'upsells',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'upsells_popup_editor',
            'order' => FALSE,
            'direct' => TRUE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'cross_sell_ids' => array(
            'show' => 0,
            'title' => __('Cross-sells', 'woo-bulk-editor'),
            'desc' => __('Cross-sells are products which you promote in the cart, based on the current product.', 'woo-bulk-editor'),
            'field_type' => 'cross_sells',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'cross_sells_popup_editor',
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variable'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'grouped_ids' => array(
            'show' => 0,
            'title' => __('Grouped products', 'woo-bulk-editor'),
            'desc' => __('This lets you choose which products are part of this group.', 'woo-bulk-editor'),
            'field_type' => 'grouped',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'grouped_popup_editor',
            'order' => FALSE,
            'direct' => TRUE,
            'allow_product_types' => array('grouped'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'purchase_note' => array(
            'show' => 0,
            'title' => __('Purchase note', 'woo-bulk-editor'),
            'desc' => __('Enter an optional note to send the customer after purchase.', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'popupeditor',
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variable'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'menu_order' => array(
            'show' => 0,
            'title' => __('Menu order', 'woo-bulk-editor'),
            'desc' => __('Custom ordering position.', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'number',
            'sanitize' => 'intval',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'order' => TRUE,
            'direct' => TRUE,
            'shop_manager_visibility' => 1
        //'prohibit_product_types' => array('variation'),
        //'css_classes' => 'not-for-variations'
        ),
        'reviews_allowed' => array(
            'show' => 0,
            'title' => __('Reviews allowed', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'switcher',
            'select_options' => array(
                'yes' => __('Yes', 'woo-bulk-editor'), //true
                'no' => __('No', 'woo-bulk-editor'), //false
            ),
            'order' => FALSE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'product_url' => array(
            'show' => 0,
            'title' => __('Product url', 'woo-bulk-editor'),
            'desc' => __('External/Affiliate product: External URL to the product', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'url',
            'sanitize' => 'esc_url',
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('external'), //for another product types will be not possible to edit value in this column
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'button_text' => array(
            'show' => 0,
            'title' => __('Aff.Button text', 'woo-bulk-editor'),
            'desc' => __('External/Affiliate product: This text will be shown on the button linking to the external product', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('external'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'virtual' => array(
            'show' => 0,
            'title' => __('Virtual', 'woo-bulk-editor'),
            'desc' => __('Virtual products are intangible and are not shipped.', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'switcher',
            'select_options' => array(
                'yes' => __('Yes', 'woo-bulk-editor'), //true
                'no' => __('No', 'woo-bulk-editor'), //false
            ),
            'order' => FALSE,
            'direct' => FALSE,
            'allow_product_types' => array('simple', 'variation'),
            'shop_manager_visibility' => 1,
            'css_classes' => '',
        ),
        'post_author' => array(
            'show' => 0,
            'title' => __('Author', 'woo-bulk-editor'),
            'field_type' => 'field',
            'type' => 'string',
            'editable' => TRUE,
            'edit_view' => 'select',
            'select_options' => $users,
            'order' => FALSE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'total_sales' => array(
            'show' => 0,
            'title' => __('Total sales', 'woo-bulk-editor'),
            'desc' => __('Total count of the product sales', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => 'total_sales',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'intval',
            'order' => TRUE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'review_count' => array(
            'show' => 0,
            'title' => __('Review count', 'woo-bulk-editor'),
            'desc' => __('How many times the products been reviewed by customers', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_wc_review_count',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'intval',
            'order' => TRUE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'average_rating' => array(
            'show' => 0,
            'title' => __('Average rating', 'woo-bulk-editor'),
            'desc' => __('Average rating of the product', 'woo-bulk-editor'),
            'field_type' => 'prop',
            'meta_key' => '_wc_average_rating',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'floatval',
            'order' => TRUE,
            'direct' => FALSE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        /*
          'rating_counts' => array(
          'show' => 0,
          'title' => __('Rating counts', 'woo-bulk-editor'),
          'desc' => __('Rating counts of the product. Attention: This is keeps in the DataBase as array! Use comma, for example: 5,5,3,4', 'woo-bulk-editor'),
          'field_type' => 'prop',
          'type' => 'string',
          'editable' => TRUE,
          'direct'=>TRUE,
          'edit_view' => 'textinput',
          'sanitize' => 'array',
          'order' => FALSE
          ),
         */
        'post_date' => array(
            'show' => 0,
            'title' => __('Date Published', 'woo-bulk-editor'),
            'field_type' => 'field',
            'type' => 'timestamp', //timestamp, unix
            'set_day_end' => FALSE, //false: 00:00:00, true: 23:59:59
            'editable' => TRUE,
            'edit_view' => 'calendar',
            'order' => TRUE,
            'direct' => TRUE,
            'prohibit_product_types' => array('variation'),
            'css_classes' => 'not-for-variations',
            'shop_manager_visibility' => 1
        ),
        'post_parent' => array(
            'show' => 0,
            'title' => __('Parent', 'woo-bulk-editor'),
            'field_type' => 'field',
            'type' => 'number',
            'editable' => TRUE,
            'edit_view' => 'textinput',
            'sanitize' => 'intval',
            'order' => TRUE,
            'direct' => TRUE,
            'allow_product_types' => array('variation'),
            'shop_manager_visibility' => 1
        )
    ));
}
