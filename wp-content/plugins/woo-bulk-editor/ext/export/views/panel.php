
<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
?>

<div class="notice notice-warning">
    <p>
        <?php printf(__('Export will be applied to: %s', 'woo-bulk-editor'), '<span class="woobe_action_will_be_applied_to">' . __('all the products on the site', 'woo-bulk-editor') . '</span>') ?>
    </p>
</div>

<div class="notice notice-info">
    <p>
        <?php _e('<b>Note:</b> you can change columns set and then set their order in the tab Settings, then save it as columns profile which in future will help you with exporting of the products data format quickly without necessary each time set columns order and their set!', 'woo-bulk-editor') ?>    
    </p>
</div>
<br />
<div class="col-lg-6">
    <a href="javascript: woobe_export_to_csv();void(0);" class="button button-primary button-large woobe_export_products_btn"><span class="icon-export"></span>&nbsp;<?php _e('Export to CSV', 'woo-bulk-editor') ?></a>
    <!-- &nbsp;<a href="javascript: woobe_export_to_excel();void(0);" class="button button-primary button-large woobe_export_products_btn"><?php _e('Export to Excel', 'woo-bulk-editor') ?></a><br /> -->
    <a href="<?php echo $download_link ?>" target="_blank" class="button button-primary button-large woobe_export_products_btn_down" style="display: none; color: greenyellow;"><span class="icon-download"></span>&nbsp;<?php _e('download', 'woo-bulk-editor') ?>&nbsp;<span class="icon-download"></span></a>
    <a href="javascript: woobe_export_to_csv_cancel();void(0);" class="button button-primary button-large woobe_export_products_btn_cancel" style="display: none;"><span class="icon-cancel-circled-3"></span>&nbsp;<?php _e('cancel export', 'woo-bulk-editor') ?></a>
</div>

<div class="col-lg-6" style="text-align: right;">
    <?php
    echo WOOBE_HELPER::draw_link(array(
        'href' => admin_url('edit.php?post_type=product&page=product_importer'),
        'title' => '<span class="icon-upload"></span>&nbsp;' . __('Import from CSV', 'woo-bulk-editor'),
        'target' => '_blank',
        'class' => 'button button-primary button-large'
    ));
    ?><br />
</div>
<div style="clear: both;"></div>
<br />

<ul>
    <?php if (array_key_exists('download_files', $active_fields)): ?>
        <li>
            <div class="col-lg-4">
                <input type="number" value="5" placeholder="<?php _e('max downloads per product', 'woo-bulk-editor') ?>" id="woobe_export_download_files_count" style="width: 120px !important;" />&nbsp;
                <?php echo WOOBE_HELPER::draw_tooltip(__('Set here maximal possible count of downloads per product. Not possible to automate counting of this value because this data is serialized in the data base!', 'woo-bulk-editor')) ?>
            </div>
            <div style="clear: both;"></div>
        </li>
    <?php endif; ?>

    <li>
        <select id="woobe_export_delimiter">
            <option value=",">,</option>
            <option value=";">;</option>
            <option value="|">|</option>
            <option value="^">^</option>
            <option value="~">~</option>
        </select>&nbsp;<?php echo WOOBE_HELPER::draw_tooltip(__('Select CSV data delimiter. ATTENTION: if you going to import data back using native woocommerce importer - delimiter should be comma or import of the data will not be possible!', 'woo-bulk-editor')) ?>
    </li>
</ul>


<ul>
    <li>
        <div class="col-lg-12">

            <div class="woobe_progress woobe_progress_export" style="display: none;">
                <div class="woobe_progress_in" id="woobe_export_progress">0%</div>
            </div>

        </div>
        <div style="clear: both;"></div>
    </li>

</ul>



<div style="clear: both;"></div>
<br />
<a href="https://bulk-editor.com/document/woocommerce-products-export/" style="height: 21px; line-height: normal;" target="_blank" class="button button-primary"><span class="icon-book"></span>&nbsp;<?php _e('Documentation', 'woo-bulk-editor') ?></a>
<br />
