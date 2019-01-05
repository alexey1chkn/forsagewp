<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOBE;
?>

<!------------------ filter sets profiles popup --------------------------->
<div id="woobe_fprofile_popup" style="display: none;">
    <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%; height: 320px;">
        <div class="woobe-modal-inner">
            <div class="woobe-modal-inner-header">
                <h3 class="woobe-modal-title"><?php _e('Filters profiles', 'woo-bulk-editor') ?></h3>
                <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close-fprofile"></a>
            </div>
            <div class="woobe-modal-inner-content">

                <div class="woobe-form-element-container">
                    <div class="woobe-name-description">
                        <strong><?php echo __('Profiles', 'woo-bulk-editor') ?></strong>
                        <span><?php echo __('Here you can load previously saved filters profile. After pressing on the load button, products table data reloading will start immediately!', 'woo-bulk-editor') ?></span>

                        <?php if ($WOOBE->show_notes): ?>
                            <span style="color: red;"><?php echo __('In FREE version of the plugin it is possible to create 1 profile.', 'woo-bulk-editor') ?></span>
                        <?php endif; ?>

                        <ul id="woobe_loaded_fprofile_data_info"></ul>

                    </div>
                    <div class="woobe-form-element">

                        <select id="woobe_load_fprofile">
                            <option value="0"><?php _e('Select filter profile to load', 'woo-bulk-editor') ?></option>
                            <?php if (!empty($fprofiles)): ?>
                                <?php foreach ($fprofiles as $pkey => $pvalue) : ?>
                                    <option value="<?php echo $pkey ?>"><?php echo $pvalue['title'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>


                        <div style="display: none;" id="woobe_load_fprofile_actions">
                            <a href="javascript:void(0)" class="button button-primary button" id="woobe_load_fprofile_btn"><?php _e('load', 'woo-bulk-editor') ?></a>&nbsp;
                            <a href="#" class="button button-primary button woobe_delete_fprofile"><?php _e('remove', 'woo-bulk-editor') ?></a>
                        </div>

                    </div>
                </div>



                <div class="woobe-form-element-container woobe-new-fprofile-inputs">
                    <div class="woobe-name-description">
                        <strong><?php echo __('New Filter Profile', 'woo-bulk-editor') ?></strong>
                        <span><?php echo __('Here you can type any title and save current filters set. Type here any title and then press Save button OR press Enter button on your keyboard!', 'woo-bulk-editor') ?></span>
                    </div>
                    <div class="woobe-form-element">
                        <div class="products_search_container">
                            <input type="text" value="" id="woobe_new_fprofile" />
                        </div>
                    </div>
                </div>


                <div class="woobe-form-element-container woobe-new-fprofile-attention">

                    <div class="notice notice-info">
                        <p>
                            <?php _e('You can save filter profile only when you applying filters to the products.', 'woo-bulk-editor') ?>    
                        </p>
                    </div>

                </div>

            </div>
            <div class="woobe-modal-inner-footer">
                <a href="javascript:void(0)" class="button button-primary button-large button-large-1"  id="woobe_new_fprofile_btn"><?php echo __('Save', 'woo-bulk-editor') ?></a>
                <a href="javascript:void(0)" class="woobe-modal-close-fprofile button button-primary button-large button-large-2"><?php echo __('Close', 'woo-bulk-editor') ?></a>
            </div>
        </div>
    </div>

    <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

</div>
