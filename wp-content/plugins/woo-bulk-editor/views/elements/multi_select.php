<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//$val is terms ids here
?>

<div class="woobe_multi_select_cell">
    <div class="woobe_multi_select_cell_list"><?php echo WOOBE_HELPER::draw_attribute_list_btn($active_fields[$field_key]['select_options'], $val, $field_key, $post) ?></div>
    <div class="woobe_multi_select_cell_dropdown" style="display: none;">
        <?php
        echo WOOBE_HELPER::draw_select(array(
            'field' => $field_key,
            'product_id' => $product_id,
            'class' => 'woobe_data_select chosen-select',
            //'options' => $this->settings->active_fields[$field_key]['select_options'],
            'options' => array(),
            'selected' => $val,
                //'onmouseover' => 'woobe_multi_select_onmouseover(this)',
                //'onchange' => 'woobe_act_select(this)'
                ), true);
        ?>
        <br />
        <br />

        <div style="float: right;">
            <a href="#" class="page-title-action woobe_multi_select_cell_cancel"><?php _e('cancel', 'woo-bulk-editor') ?></a>
        </div>


        <div style="float: left;">
            <a href="#" class="page-title-action woobe_multi_select_cell_save"><?php _e('save', 'woo-bulk-editor') ?></a>
        </div>


        <div style="float: left;">
            <a href="#" class="page-title-action woobe_multi_select_cell_new" data-tax-key="<?php echo $field_key ?>"><?php _e('new', 'woo-bulk-editor') ?></a>
        </div>


        <div style="clear: both;"></div>

    </div>
</div>

