<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
?>


<div id="woobe_tools_panel">

    <div style="position: relative">

        <a href="#" class="button button-secondary woobe_tools_panel_full_width_btn icon-resize-horizontal-1" title="<?php _e('Set full width', 'woo-bulk-editor') ?>"></a>
        <a href="#" class="button button-secondary woobe_tools_panel_profile_btn" title="<?php _e('Columns profiles', 'woo-bulk-editor') ?>"></a>


        <?php do_action('woobe_tools_panel_buttons') ?>


        <a href="#" class="button button-secondary woobe_tools_panel_newprod_btn" title="<?php _e('New Product', 'woo-bulk-editor') ?>"></a>



        <a href="#" class="button button-primary woobe_tools_panel_duplicate_btn" title="<?php _e('Duplicate selected product(s). ATTENTION: duplication of variations of the products is locked!', 'woo-bulk-editor') ?>" style="display: none;"></a>
        <a href="#" class="button button-primary woobe_tools_panel_delete_btn" title="<?php _e('Delete selected product(s)', 'woo-bulk-editor') ?>" style="display: none;"></a>

        <a href="#" class="button button-primary woobe_tools_panel_uncheck_all" title="<?php _e('Uncheck all selected products', 'woo-bulk-editor') ?>" style="display: none;"></a>
        <a href="#" class="button button-secondary woobe_filter_reset_btn2" title="<?php _e('Reset filters', 'woo-bulk-editor') ?>" style="display: none;"></a>




        &nbsp;<span>
            <?php echo WOOBE_HELPER::draw_advanced_switcher(0, 'woobe_show_variations', '', array('true' => __('variations', 'woo-bulk-editor'), 'false' => __('variations', 'woo-bulk-editor')), array('true' => 1, 'false' => 0), 'js_check_woobe_show_variations', 'woobe_show_variations'); ?>
            <?php echo WOOBE_HELPER::draw_tooltip(__('Bulk editing of the parent products will be ignored! Enabling this mode hide not relevant bulk edit operations for the products [variations]! Before activation of this mode, for more convenient editing of [variations] recommend make filtering for all variable products. Binded operation also will be applied only to the products variations!', 'woo-bulk-editor')) ?>
        </span>&nbsp;

        <span><a href="#" id="woobe_select_all_vars" class="button" style="display: none;"><?php _e('select all variations', 'woo-bulk-editor') ?></a></span>


        <?php do_action('woobe_tools_panel_buttons_end') ?>

        <div style="display: none;">
            <a href="#" id="woobe_scroll_right" class="button" title="<?php _e('Scroll right', 'woo-bulk-editor') ?>" style="display: none;"></a>
            <a href="#" id="woobe_scroll_left" class="button" title="<?php _e('Scroll left', 'woo-bulk-editor') ?>" style="display: none;"></a>
        </div>

    </div>
</div>


<table id="advanced-table" data-editable="<?php echo $table_data['editable'] ?>" data-default-sort-by="<?php echo $table_data['default-sort-by'] ?>" data-sort="<?php echo $table_data['sort'] ?>" data-no-order="<?php echo $table_data['no-order'] ?>" data-additional='' data-start-page="<?php echo $table_data['start-page'] ?>" data-per-page="<?php echo $table_data['per-page'] ?>" data-fields="<?php echo $table_data['fields'] ?>" data-edit-views="<?php echo $table_data['edit_views'] ?>" data-edit-sanitize="<?php echo $table_data['edit_sanitize'] ?>" class="display table dt-responsive table-striped table-bordered nowrap">
    <thead>
        <tr>
            <?php foreach ($table_labels as $c => $label) : ?>
                <th id="woobe_col_<?php echo $c ?>"><?php echo trim($label['title']) ?><?php echo (!empty($label['desc']) AND $c > 0 ? WOOBE_HELPER::draw_tooltip($label['desc']) : '') ?></th>
                <?php endforeach; ?>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <?php foreach ($table_labels as $label) : ?>
                <th><?php echo trim($label['title']) ?></th>
            <?php endforeach; ?>
        </tr>
    </tfoot>
    <tbody></tbody>
</table>


