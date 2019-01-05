<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function woobe_get_total_settings($data) {
    return array(
        'per_page' => array(
            'title' => __('Default products count per page', 'woo-bulk-editor'),
            'desc' => __('How many rows of products show per page in tab Products. Max possible value is 100!', 'woo-bulk-editor'),
            'value' => '',
            'type' => 'number'
        ),
        'default_sort_by' => array(
            'title' => __('Default sort by', 'woo-bulk-editor'),
            'desc' => __('Select column by which products sorting is going after plugin page loaded', 'woo-bulk-editor'),
            'value' => '',
            'type' => 'select',
            'select_options' => $data['default_sort_by']
        ),
        'default_sort' => array(
            'title' => __('Default sort', 'woo-bulk-editor'),
            'desc' => __('Select sort direction for Default sort by column above', 'woo-bulk-editor'),
            'value' => '',
            'type' => 'select',
            'select_options' => array(
                'desc' => array('title' => 'DESC'),
                'asc' => array('title' => 'ASC')
            )
        ),
        'show_admin_bar_menu_btn' => array(
            'title' => __('Show button in admin bar', 'woo-bulk-editor'),
            'desc' => __('Show Bulk Editor button in admin bar for quick access to the products editor', 'woo-bulk-editor'),
            'value' => '',
            'type' => 'select',
            'select_options' => array(
                1 => array('title' => __('Yes', 'woo-bulk-editor')),
                0 => array('title' => __('No', 'woo-bulk-editor')),
            )
        ),
        'show_thumbnail_preview' => array(
            'title' => __('Show thumbnail preview', 'woo-bulk-editor'),
            'desc' => __('Show bigger thumbnail preview on mouse over', 'woo-bulk-editor'),
            'value' => '',
            'type' => 'select',
            'select_options' => array(
                1 => array('title' => __('Yes', 'woo-bulk-editor')),
                0 => array('title' => __('No', 'woo-bulk-editor')),
            )
        ),
        'load_switchers' => array(
            'title' => __('Load beauty switchers', 'woo-bulk-editor'),
            'desc' => __('Load beauty switchers instead of checkboxes in the products table.', 'woo-bulk-editor'),
            'value' => '',
            'type' => 'select',
            'select_options' => array(
                1 => array('title' => __('Yes', 'woo-bulk-editor')),
                0 => array('title' => __('No', 'woo-bulk-editor')),
            )
        ),
        'autocomplete_max_elem_count' => array(
            'title' => __('Autocomplete max count', 'woo-bulk-editor'),
            'desc' => __('How many products display in the autocomplete drop-downs. Uses in up-sells, cross-sells and grouped popups.', 'woo-bulk-editor'),
            'value' => '',
            'type' => 'number'
        ),
    );
}
