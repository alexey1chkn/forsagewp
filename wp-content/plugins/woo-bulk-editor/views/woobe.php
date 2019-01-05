<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
?>

<div class="woobe-admin-preloader">
    <div class="cssload-loader">
        <div class="cssload-inner cssload-one"></div>
        <div class="cssload-inner cssload-two"></div>
        <div class="cssload-inner cssload-three"></div>
    </div>
</div>


<!----------------------------- Filters ------------------------------------->

<?php echo WOOBE_HELPER::render_html(WOOBE_PATH . 'views/parts/top_panel.php'); ?>

<!----------------------------- Filters end ------------------------------------->

<div class="wrap nosubsub" style="margin-top: 0;">

    <?php if (isset($_GET['settings_saved'])): ?>
        <div id="message" class="updated"><p><strong><?php _e("Your settings have been saved.", 'woo-bulk-editor') ?></strong></p></div>
    <?php endif; ?>

    <section class="woobe-section">
        <h3 style="margin-top: 0;  margin-bottom: 31px;"><?php printf('WOOBE - WooCommerce Bulk Editor Professional v.%s', WOOBE_VERSION) ?></h3>
        <input type="hidden" name="woobe_settings" value="" />

        <?php if (version_compare(WOOCOMMERCE_VERSION, WOOBE_MIN_WOOCOMMERCE_VERSION, '<')): ?>

            <div id="message" class="error fade"><p><strong><?php _e("ATTENTION! Your version of the woocommerce plugin is too obsolete. There is no warranty of normal working with the plugin!!", 'woo-bulk-editor') ?></strong></p></div>

        <?php endif; ?>

        <svg class="hidden">
        <defs>
        <path id="tabshape" d="M80,60C34,53.5,64.417,0,0,0v60H80z"/>
        </defs>
        </svg>


        <div id="tabs" class="woobe-tabs woobe-tabs-style-shape">

            <nav>
                <ul>
                    <li class="tab-current">
                        <a href="#tabs-products" onclick="return woobe_init_js_intab('tabs-products')">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Products Editor", 'woo-bulk-editor') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#tabs-settings" onclick="return woobe_init_js_intab('tabs-settings')">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Settings", 'woo-bulk-editor') ?></span>
                        </a>
                    </li>

                    <?php do_action('woobe_ext_panel_tabs'); //including extensions scripts        ?>

                </ul>
            </nav>

            <div class="content-wrap">

                <section id="tabs-products" class="content-current" style="/*overflow-x: scroll;*/">

                    <?php
                    $table_labels = array();
                    $edit_views = array();
                    $edit_sanitize = array();
                    $fields_types = array();
                    if (!empty($active_fields)) {
                        foreach ($active_fields as $key => $f) {
                            $title = $f['title'];
                            if (isset($f['title_static']) AND $f['title_static']) {
                                $title = $settings_fields[$key]['title'];
                            }
                            $table_labels[] = array('title' => $title, 'desc' => isset($f['desc']) ? $f['desc'] : '');
                            $edit_views[] = $f['edit_view'];
                            $edit_sanitize[] = isset($f['sanitize']) ? $f['sanitize'] : 'no';
                        }
                    }
                    $fk = $settings_fields_keys;


//***

                    if (empty($edit_views)) {
                        echo '<strong style="color: red;">' . __('Select some columns in tab "Settings"', 'woo-bulk-editor') . '</strong><br /><br />';
                    }

                    $table_labels[] = array('title' => __('Actions', 'woo-bulk-editor'), 'desc' => '');
                    echo WOOBE_HELPER::render_html(WOOBE_PATH . 'views/parts/advanced-table.php', array(
                        'table_data' => array(
                            'editable' => implode(',', $editable),
                            'default-sort-by' => $default_sortby_col_num,
                            'sort' => $default_sort,
                            'no-order' => implode(',', $no_order),
                            'per-page' => $per_page,
                            'additional' => '',
                            'start-page' => 0,
                            'fields' => implode(',', $fk),
                            'edit_views' => (!empty($edit_views) ? implode(',', $edit_views) : ''),
                            'edit_sanitize' => (!empty($edit_sanitize) ? implode(',', $edit_sanitize) : ''),
                        ),
                        'table_labels' => $table_labels
                    ));
                    ?>


                    <?php if (!empty($tax_keys)): ?>
                        <div id="taxonomies_popup" style="display: none;">

                            <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%;">
                                <div class="woobe-modal-inner">
                                    <div class="woobe-modal-inner-header">
                                        <h3 class="woobe-modal-title" style="font-size: 17px;">&nbsp;</h3>
                                        <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close1"></a>
                                    </div>
                                    <div class="woobe-modal-inner-content">
                                        <div class="woobe-form-element-container">
                                            <div class="woobe-name-description">
                                                <strong><?php echo __('Quick search', 'woo-bulk-editor') ?></strong>
                                                <span><?php echo __('Quick terms search by its name', 'woo-bulk-editor') ?></span>
                                            </div>
                                            <div class="woobe-form-element">
                                                <input type="text" class="woobe_popup_option" id="term_quick_search" value="" /><br />
                                                <a href="#" class="woobe_create_new_term"><?php _e('create new term', 'woo-bulk-editor') ?></a>&nbsp;|&nbsp;
                                                <input type="checkbox" id="taxonomies_popup_list_checked_only" value="0" /><label for="taxonomies_popup_list_checked_only"><?php _e('selected only', 'woo-bulk-editor') ?></label>
                                            </div>
                                        </div>

                                        <div class="woobe-form-element-container">
                                            <ul id="taxonomies_popup_list"></ul>
                                        </div>
                                    </div>
                                    <div class="woobe-modal-inner-footer">
                                        <a href="javascript:void(0)" class="woobe-modal-close1 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                                        <a href="javascript:void(0)" class="woobe-modal-save1 button button-primary button-large button-large-1"><?php echo __('Apply', 'woo-bulk-editor') ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

                        </div>
                    <?php endif; ?>

                    <?php if ($is_popupeditor): ?>
                        <div id="popupeditor_popup" style="display: none;">
                            <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%;">
                                <div class="woobe-modal-inner">
                                    <div class="woobe-modal-inner-header">
                                        <h3 class="woobe-modal-title">&nbsp;</h3>
                                        <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close2"></a>
                                    </div>
                                    <div class="woobe-modal-inner-content">
                                        <div class="woobe-form-element-container">
                                            <div id="woobe-modal-content-popupeditor">
                                                <div class="woobe-form-element-container" style="padding: 0;">
                                                    <?php
                                                    wp_editor('', 'popupeditor', array(
                                                        'editor_height' => 325
                                                    ));
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="woobe-modal-inner-footer">
                                        <a href="javascript:void(0)" class="woobe-modal-close2 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                                        <a href="javascript:void(0)" class="woobe-modal-save2 button button-primary button-large button-large-1"><?php echo __('Apply', 'woo-bulk-editor') ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

                        </div>
                    <?php endif; ?>

                    <?php if ($is_downloads): ?>
                        <div id="downloads_popup_editor" style="display: none;">
                            <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%;">
                                <div class="woobe-modal-inner">
                                    <div class="woobe-modal-inner-header">
                                        <h3 class="woobe-modal-title">&nbsp;</h3>
                                        <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close3"></a>
                                    </div>
                                    <div class="woobe-modal-inner-content">
                                        <div class="woobe-form-element-container">
                                            <div id="woobe-modal-content-popupeditor">
                                                <div class="woobe-form-element-container" style="padding: 0;">
                                                    <a href="#" class="woobe-button woobe_insert_download_file" data-place="top"><?php echo __('Add File', 'woo-bulk-editor') ?></a><br />

                                                    <br />
                                                    <div id="woobe_downloads_bulk_operations">
                                                        <div class="col-lg-12">

                                                            <select id="woobe_downloads_operations">
                                                                <option value="new"><?php _e('Replace all downloads by the selected ones', 'woo-bulk-editor') ?></option>
                                                                <option value="add"><?php _e('Add selected downloads to the already existed ones', 'woo-bulk-editor') ?></option>
                                                                <option value="delete"><?php _e('Remove selected downloads', 'woo-bulk-editor') ?></option>
                                                            </select>


                                                        </div>

                                                        <i><?php _e('For [Remove selected downloads] - enter links to downloads which should be removed.', 'woo-bulk-editor') ?></i><br />

                                                        <div style="clear: both;"></div>
                                                    </div>

                                                    <form method="post" action="" id="products_downloads_form"></form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="woobe-modal-inner-footer">
                                        <a href="javascript:void(0)" class="woobe-modal-close3 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                                        <a href="javascript:void(0)" class="woobe-modal-save3 button button-primary button-large button-large-1"><?php echo __('Apply', 'woo-bulk-editor') ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

                        </div>


                        <div style="display: none" id="woobe_download_file_tpl">
                            <li class="woobe_options_li">
                                <table style="width: 100%;">
                                    <tr>
                                        <td class="sort" width="1%"><div style="margin: -4px 3px 0 0; line-height: 0;"><a href="#" class="help_tip woobe_drag_and_drope" title="<?php echo __('drag and drop', 'woo-bulk-editor') ?>"><img style="vertical-align: middle;" src="<?php echo WOOBE_ASSETS_LINK ?>images/move.png" alt="<?php echo __('move', 'woo-bulk-editor') ?>" /></a></div></td>
                                        <td class="file_name">
                                            <input type="text" class="input_text" placeholder="<?php esc_attr_e('File name', 'woo-bulk-editor'); ?>" name="_wc_file_names[]" value="__TITLE__" />
                                            <input type="hidden" name="_wc_file_hashes[]" value="__HASH__" />
                                        </td>
                                        <td class="file_url"><input type="text" class="input_text woobe_down_file_url" placeholder="http://product-link/" name="_wc_file_urls[]" value="__FILE_URL__" /></td>
                                        <td class="file_url_choose" width="1%"><a href="#" class="woobe-button woobe_upload_file_button" data-choose="<?php esc_attr_e('Choose file', 'woo-bulk-editor'); ?>" data-update="<?php esc_attr_e('Insert file URL', 'woo-bulk-editor'); ?>"><?php echo str_replace(' ', '&nbsp;', __('Choose file', 'woo-bulk-editor')); ?></a></td>
                                        <td width="1%"><a href="#" class="woobe_down_file_delete woobe-button">X</a></td>
                                    </tr>
                                </table>
                            </li>
                        </div>

                    <?php endif; ?>

                    <?php if ($is_gallery): ?>
                        <div id="gallery_popup_editor" style="display: none;">
                            <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%;">
                                <div class="woobe-modal-inner">
                                    <div class="woobe-modal-inner-header">
                                        <h3 class="woobe-modal-title">&nbsp;</h3>
                                        <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close4"></a>
                                    </div>
                                    <div class="woobe-modal-inner-content">
                                        <div class="woobe-form-element-container">
                                            <div id="woobe-modal-content-popupeditor">
                                                <div class="woobe-form-element-container" style="padding: 0;">
                                                    <a href="#" class="woobe-button woobe_insert_gall_file" data-place="top"><?php echo __('Add Image', 'woo-bulk-editor') ?></a><br />
                                                    <br />
                                                    <div id="woobe_gallery_bulk_operations">
                                                        <div class="col-lg-12">

                                                            <select id="woobe_gall_operations">
                                                                <option value="new"><?php _e('Replace all products images by the selected ones', 'woo-bulk-editor') ?></option>
                                                                <option value="add"><?php _e('Add selected images to the already existed ones', 'woo-bulk-editor') ?></option>
                                                                <option value="delete"><?php _e('Delete selected images from the products', 'woo-bulk-editor') ?></option>
                                                                <option value="delete_forever"><?php _e('Delete selected images from the products, also delete them from the media library forever', 'woo-bulk-editor') ?></option>
                                                            </select>


                                                        </div>

                                                        <div style="clear: both;"></div>
                                                    </div>


                                                    <form method="post" action="" id="products_gallery_form"></form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="woobe-modal-inner-footer">
                                        <a href="javascript:void(0)" class="woobe-modal-close4 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                                        <a href="javascript:void(0)" class="woobe-modal-save4 button button-primary button-large button-large-1"><?php echo __('Apply', 'woo-bulk-editor') ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

                        </div>

                        <div style="display: none;" id="woobe_gallery_li_tpl">
                            <li>
                                <img src="__IMG_URL__" alt="" class="woobe_gal_img_block" />
                                <a href="#" class="woobe_gall_file_delete"><img src="<?php echo WOOBE_ASSETS_LINK . 'images/delete2.png' ?>" alt="" /></a>
                                <input type="hidden" name="woobe_gallery_images[]" value="__ATTACHMENT_ID__" />
                            </li>
                        </div>

                    <?php endif; ?>

                    <?php if ($is_upsells): ?>
                        <div id="upsells_popup_editor" style="display: none;">
                            <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%;">
                                <div class="woobe-modal-inner">
                                    <div class="woobe-modal-inner-header">
                                        <h3 class="woobe-modal-title">&nbsp;</h3>
                                        <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close5"></a>
                                    </div>
                                    <div class="woobe-modal-inner-content">

                                        <div class="woobe-form-element-container">
                                            <div class="woobe-name-description">
                                                <strong><?php echo __('Search', 'woo-bulk-editor') ?></strong>
                                                <span><?php echo __('Search products by its name', 'woo-bulk-editor') ?></span>
                                            </div>
                                            <div class="woobe-form-element">
                                                <div class="products_search_container">
                                                    <input type="text" class="woobe_popup_option" id="upsells_products_search" value="" />
                                                    <div class="cssload-container" style="display: none;">
                                                        <div class="cssload-whirlpool"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="woobe-form-element-container">


                                            <br />
                                            <div id="woobe_upsells_bulk_operations">
                                                <div class="col-lg-12">

                                                    <select id="woobe_upsells_operations">
                                                        <option value="new"><?php _e('Replace all products by the selected ones', 'woo-bulk-editor') ?></option>
                                                        <option value="add"><?php _e('Add selected products to the already existed ones', 'woo-bulk-editor') ?></option>
                                                        <option value="delete"><?php _e('Remove selected products', 'woo-bulk-editor') ?></option>
                                                    </select>


                                                </div>

                                                <div style="clear: both;"></div>
                                            </div>


                                            <form method="post" action="" id="products_upsells_form"></form>
                                        </div>
                                    </div>
                                    <div class="woobe-modal-inner-footer">
                                        <a href="javascript:void(0)" class="woobe-modal-close5 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                                        <a href="javascript:void(0)" class="woobe-modal-save5 button button-primary button-large button-large-1"><?php echo __('Apply', 'woo-bulk-editor') ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

                        </div>


                    <?php endif; ?>

                    <?php if ($is_cross_sells): ?>
                        <div id="cross_sells_popup_editor" style="display: none;">
                            <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%;">
                                <div class="woobe-modal-inner">
                                    <div class="woobe-modal-inner-header">
                                        <h3 class="woobe-modal-title">&nbsp;</h3>
                                        <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close6"></a>
                                    </div>
                                    <div class="woobe-modal-inner-content">

                                        <div class="woobe-form-element-container">
                                            <div class="woobe-name-description">
                                                <strong><?php echo __('Search', 'woo-bulk-editor') ?></strong>
                                                <span><?php echo __('Search products by its name', 'woo-bulk-editor') ?></span>
                                            </div>
                                            <div class="woobe-form-element">
                                                <div class="products_search_container">
                                                    <input type="text" class="woobe_popup_option" id="cross_sells_products_search" value="" />
                                                    <div class="cssload-container" style="display: none;">
                                                        <div class="cssload-whirlpool"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="woobe-form-element-container">

                                            <br />
                                            <div id="woobe_crossels_bulk_operations">
                                                <div class="col-lg-12">

                                                    <select id="woobe_crossels_operations">
                                                        <option value="new"><?php _e('Replace all products by the selected ones', 'woo-bulk-editor') ?></option>
                                                        <option value="add"><?php _e('Add selected products to the already existed ones', 'woo-bulk-editor') ?></option>
                                                        <option value="delete"><?php _e('Remove selected products', 'woo-bulk-editor') ?></option>
                                                    </select>


                                                </div>

                                                <div style="clear: both;"></div>
                                            </div>

                                            <form method="post" action="" id="products_cross_sells_form"></form>
                                        </div>
                                    </div>
                                    <div class="woobe-modal-inner-footer">
                                        <a href="javascript:void(0)" class="woobe-modal-close6 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                                        <a href="javascript:void(0)" class="woobe-modal-save6 button button-primary button-large button-large-1"><?php echo __('Apply', 'woo-bulk-editor') ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

                        </div>

                    <?php endif; ?>

                    <?php $meta_popup_editor=TRUE;//for bulk edit ?>
                    <?php if ($meta_popup_editor): ?>
                        <div id="meta_popup_editor" style="display: none;">
                            <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%;">
                                <div class="woobe-modal-inner">
                                    <div class="woobe-modal-inner-header">
                                        <h3 class="woobe-modal-title">&nbsp;</h3>
                                        <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close10"></a>
                                    </div>
                                    <div class="woobe-modal-inner-content">

                                        <div class="woobe-form-element-container">
                                            <div class="row" style="margin-bottom: 9px;">
                                                <div class="col-lg-10">
                                                    <h4 style="margin: 0;"><?php _e('REMEMBER: sequence of data maybe have sense (and maybe not), so be attentive! Do not mix in the same popup window data of array and object data!', 'woo-bulk-editor') ?></h4>
                                                </div>
                                                <div class="col-lg-2">
                                                    &nbsp;<a href="https://bulk-editor.com/document/bulk-edit-of-serialized-jsoned-woocommerce-products-meta-data/" style="height: 21px; line-height: normal;" target="_blank" class="button button-primary"><span class="icon-book"></span>&nbsp;<?php echo __('Documentation', 'woo-bulk-editor') ?></a>
                                                </div>
                                            </div>
                                            
                                            <div style="clear: both;"></div>

                                            <a href="#" class="woobe-button meta_popup_editor_insert_new" data-place="top"><?php echo __('Prepend array key/value', 'woo-bulk-editor') ?></a>&nbsp;
                                            <a href="#" class="woobe-button meta_popup_editor_insert_new_o" data-place="top"><?php echo __('Prepend object set', 'woo-bulk-editor') ?></a><br />

                                            <form method="post" action="" id="meta_popup_editor_form"></form>

                                            <a href="#" class="woobe-button meta_popup_editor_insert_new" data-place="bottom"><?php echo __('Append array key/value', 'woo-bulk-editor') ?></a>&nbsp;
                                            <a href="#" class="woobe-button meta_popup_editor_insert_new_o" data-place="bottom"><?php echo __('Append object set', 'woo-bulk-editor') ?></a><br />

                                        </div>
                                    </div>
                                    <div class="woobe-modal-inner-footer">
                                        <a href="javascript:void(0)" class="woobe-modal-close10 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                                        <a href="javascript:void(0)" class="woobe-modal-save10 button button-primary button-large button-large-1"><?php echo __('Apply', 'woo-bulk-editor') ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

                        </div>


                        <div style="display: none; padding: 7px;" id="meta_popup_editor_li">
                            <li class="woobe_options_li">
                                <a href="#" class="help_tip woobe_drag_and_drope" style="top: -13px; left: 0;" title="<?php echo __('drag and drop', 'woo-bulk-editor') ?>"><img src="<?php echo WOOBE_ASSETS_LINK ?>images/move.png" alt="<?php echo __('move', 'woo-bulk-editor') ?>" /></a>
                                <small><?php _e('key', 'woo-bulk-editor') ?>:</small><input type="text" value="__KEY__" class="meta_popup_editor_li_key" name="keys[]" /><br />
                                <small><?php _e('value', 'woo-bulk-editor') ?>:</small><input type="text" value="__VALUE__" class="meta_popup_editor_li_value" name="values[]" />
                                <a href="#" class="woobe_prod_delete" style="top: 0; right: 0;"><img src="<?php echo WOOBE_ASSETS_LINK . 'images/delete2.png' ?>" alt="" /></a>
                                __CHILD_LIST__
                            </li>
                        </div>

                        <div style="display: none; padding: 7px;" id="meta_popup_editor_li_o">
                            <li class="woobe_options_li">
                                <a href="#" class="help_tip woobe_drag_and_drope" style="top: -13px; left: 0;" title="<?php echo __('drag and drop', 'woo-bulk-editor') ?>"><img src="<?php echo WOOBE_ASSETS_LINK ?>images/move.png" alt="<?php echo __('move', 'woo-bulk-editor') ?>" /></a>
                                <small><?php _e('key', 'woo-bulk-editor') ?>:</small><input type="text" value="__KEY__" class="meta_popup_editor_li_key" name="keys[]" /><br />
                                <a href="#" class="woobe_prod_delete" style="top: 0; right: 0;"><img src="<?php echo WOOBE_ASSETS_LINK . 'images/delete2.png' ?>" alt="" /></a>
                                __CHILD_LIST__
                            </li>
                        </div>

                        <div style="display: none; padding: 7px;" id="meta_popup_editor_li_object">
                            <li class="woobe_options_li">
                                <small><?php _e('key', 'woo-bulk-editor') ?>:</small><br /><input type="text" value="__KEY__" class="meta_popup_editor_li_key meta_popup_editor_li_key2" name="keys2[]" /><br />
                                <small><?php _e('value', 'woo-bulk-editor') ?>:</small><br /><textarea style="height: 60px;" class="meta_popup_editor_li_value meta_popup_editor_li_value2" name="values2[]">__VALUE__</textarea>
                                <a href="#" class="woobe_prod_delete" style="top: 0; right: 0;"><img src="<?php echo WOOBE_ASSETS_LINK . 'images/delete2.png' ?>" alt="" /></a>
                            </li>
                        </div>

                    <?php endif; ?>



                    <?php if ($is_grouped): ?>
                        <div id="grouped_popup_editor" style="display: none;">
                            <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%;">
                                <div class="woobe-modal-inner">
                                    <div class="woobe-modal-inner-header">
                                        <h3 class="woobe-modal-title">&nbsp;</h3>
                                        <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close7"></a>
                                    </div>
                                    <div class="woobe-modal-inner-content">

                                        <div class="woobe-form-element-container">
                                            <div class="woobe-name-description">
                                                <strong><?php echo __('Search', 'woo-bulk-editor') ?></strong>
                                                <span><?php echo __('Search products by its name', 'woo-bulk-editor') ?></span>
                                            </div>
                                            <div class="woobe-form-element">
                                                <div class="products_search_container">
                                                    <input type="text" class="woobe_popup_option" id="grouped_products_search" value="" />
                                                    <div class="cssload-container" style="display: none;">
                                                        <div class="cssload-whirlpool"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="woobe-form-element-container">

                                            <br />
                                            <div id="woobe_grouped_bulk_operations">
                                                <div class="col-lg-12">

                                                    <select id="woobe_grouped_operations">
                                                        <option value="new"><?php _e('Replace all products by the selected ones', 'woo-bulk-editor') ?></option>
                                                        <option value="add"><?php _e('Add selected products to the already existed ones', 'woo-bulk-editor') ?></option>
                                                        <option value="delete"><?php _e('Remove selected products', 'woo-bulk-editor') ?></option>
                                                    </select>


                                                </div>

                                                <div style="clear: both;"></div>
                                            </div>


                                            <form method="post" action="" id="products_grouped_form"></form>
                                        </div>
                                    </div>
                                    <div class="woobe-modal-inner-footer">
                                        <a href="javascript:void(0)" class="woobe-modal-close7 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                                        <a href="javascript:void(0)" class="woobe-modal-save7 button button-primary button-large button-large-1"><?php echo __('Apply', 'woo-bulk-editor') ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

                        </div>

                    <?php endif; ?>

                    <?php if ($is_upsells OR $is_cross_sells OR $is_grouped): ?>
                        <div style="display: none;" id="woobe_product_li_tpl">
                            <li class="woobe_options_li">
                                <a href="#" class="help_tip woobe_drag_and_drope" title="<?php echo __('drag and drop', 'woo-bulk-editor') ?>"><img src="<?php echo WOOBE_ASSETS_LINK ?>images/move.png" alt="<?php echo __('move', 'woo-bulk-editor') ?>" /></a>
                                <img src="__IMG_URL__" alt="" class="woobe_gal_img_block" />&nbsp;
                                <a href="__PERMALINK__" target="_blank"><label>__TITLE__</label></a>
                                <a href="#" class="woobe_prod_delete"><img src="<?php echo WOOBE_ASSETS_LINK . 'images/delete2.png' ?>" alt="" /></a>
                                <input type="hidden" name="woobe_prod_ids[]" value="__ID__" />
                            </li>
                        </div>
                    <?php endif; ?>

                    <div class="row" style="clear: both;">
                        <div class="col-lg-3">
                            <a href="https://bulk-editor.com/document/woocommerce-products-editor/" style="height: 21px; line-height: normal;" target="_blank" class="button button-primary"><span class="icon-book"></span>&nbsp;<?php _e('Documentation', 'woo-bulk-editor') ?></a><br />
                        </div>

                        <div class="col-lg-9" style="text-align: right;">
                            <small>* <i><?php echo __('Note: if horizontal scroll disappeared when it must be visible, click on tab Products Editor to make it visible', 'woo-bulk-editor') ?></i></small><br />
                        </div>

                    </div>
                    <br />


                </section>

                <section id="tabs-settings">
                    <form id="mainform" method="post" action="">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%; vertical-align: top;">
                                    <?php
                                    $fields_all = $settings_fields;
                                    $fields_all_checked = array();
                                    $fields_all_unchecked = array();

                                    foreach ($fields_all as $key => $f) {
                                        if (intval($f['show']) === 1) {
                                            $fields_all_checked[$key] = $f;
                                        } else {
                                            $fields_all_unchecked[$key] = $f;
                                        }
                                    }
                                    ?>
                                    <h4><?php printf(__('Columns settings%s, columns enabled %s', 'woo-bulk-editor'), ($show_notes ? '' : ' ' . (count($fields_all) - 1)), count($fields_all_checked) - 1) ?></h4>
                                    <ul class="woobe_fields">
                                        <li class="unsortable">
                                            <input type="text" value="" style="width: 100%;" placeholder="<?php _e('columns finder ...', 'woo-bulk-editor') ?>" id="woobe_columns_finder" /><br />
                                        </li>
                                        <?php
//***
//lets show selected columns on the top
                                        $columns_colors = array();
                                        foreach (array($fields_all_checked, $fields_all_unchecked) as $counter => $ff):

                                            if ($counter > 0 AND ! empty($fields_all_unchecked)):
                                                ?>
                                                <li class="woobe_options_li">
                                                    <a href="#" id="show_all_columns"><?php _e('Show all columns', 'woo-bulk-editor') ?></a>
                                                </li>
                                                <?php
                                            endif;
                                            if (!empty($ff)):
                                                foreach ($ff as $key => $f) :
                                                    if (!$f['direct']) {
                                                        continue;
                                                    }
                                                    ?>
                                                    <?php if (!empty($f['title'])): ?>
                                                        <li class="woobe_options_li <?php if (isset($f['move'])): ?>unsortable<?php endif; ?>" <?php if ($counter > 0): ?>style="display: none;"<?php endif; ?>>

                                                            <div class="col-lg-6">
                                                                <div style="height: 7px;"></div>

                                                                <?php if (!isset($f['move'])): ?>
                                                                    <a href="#" class="help_tip woobe_drag_and_drope2" title="<?php echo __('drag and drop', 'woo-bulk-editor') ?><?php echo ($show_notes ? ' - ' . __('premium version', 'woo-bulk-editor') : '') ?>"><img src="<?php echo WOOBE_ASSETS_LINK ?>images/move.png" alt="<?php echo __('move', 'woo-bulk-editor') ?>" /></a>
                                                                <?php endif; ?>

                                                                <?php if ($f['field_type'] !== 'none'): ?>
                                                                    <?php if (isset($f['title_static']) AND $f['title_static']): ?>
                                                                        <input type="text" name="woobe_options[fields][<?php echo $key ?>][title]" value="<?php echo $settings_fields[$key]['title'] ?>" readonly="" class="woobe_column_li_option" /><br />
                                                                    <?php else: ?>
                                                                        <input type="text" name="woobe_options[fields][<?php echo $key ?>][title]" value="<?php echo $f['title'] ?>" class="woobe_column_li_option" /><br />
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php echo $f['desc'] ?><br />
                                                                    <input type="hidden" name="woobe_options[fields][<?php echo $key ?>][title]" value="" /><br />
                                                                    <div style="height: 10px;"></div>
                                                                <?php endif; ?>

                                                                <br />

                                                                <?php if (!in_array($key, array('__checker', 'ID')) AND $current_user_role == 'administrator'): ?>
                                                                <input type="checkbox" value="1" <?php checked($f['shop_manager_visibility']) ?> class="shop_manager_visibility" disabled="" data-key="<?php echo $key ?>" id="shop_manager_visibility_<?php echo $key ?>" />&nbsp;<label><?php _e('visible for the shop manager', 'woo-bulk-editor') ?>
                                                                        <?php if ($show_notes): ?><br /><small class="woobe-free-version">(<?php _e('premium version', 'woo-bulk-editor') ?>)</small><?php endif; ?></label><br />
                                                                    <input type="hidden" name="woobe_options[fields][<?php echo $key ?>][shop_manager_visibility]" value="<?php echo $f['shop_manager_visibility'] ?>" />
                                                                <?php endif; ?>
                                                            </div>


                                                            <div class="col-lg-3">

                                                                <?php
                                                                $col_color = '';
                                                                $txt_color = '';

                                                                if (isset($options['fields'][$key]['col_color'])) {
                                                                    $col_color = $options['fields'][$key]['col_color'];
                                                                }

                                                                if (isset($options['fields'][$key]['txt_color'])) {
                                                                    $txt_color = $options['fields'][$key]['txt_color'];
                                                                }

                                                                $columns_colors[$key] = array(
                                                                    'col_color' => $col_color,
                                                                    'txt_color' => $txt_color
                                                                );
                                                                ?>
                                                                <div class="woobe_column_color_pickers">
                                                                    <input type="text" name="woobe_options[fields][<?php echo $key ?>][col_color]" value="<?php echo $col_color ?>" class="woobe-color-picker" />
                                                                    <input type="text" name="woobe_options[fields][<?php echo $key ?>][txt_color]" value="<?php echo $txt_color ?>" class="woobe-color-picker" />
                                                                </div>


                                                            </div>

                                                            <?php if (isset($f['move'])): ?>
                                                                <!-------------------- always visible and not switchable ----------------------------->
                                                                <input type="hidden" value="1" name="woobe_options[fields][<?php echo $key ?>][show]" />
                                                            <?php else: ?>
                                                                <div class="col-lg-2" style="text-align: right;">

                                                                    <?php echo WOOBE_HELPER::draw_advanced_switcher(intval(isset($active_fields[$key])), $key, 'woobe_options[fields][' . $key . '][show]', array('true' => '', 'false' => ''), array('true' => 1, 'false' => 0), 'woobe_fshow_' . $key); ?>

                                                                </div>
                                                            <?php endif; ?>



                                                            <div class="col-lg-1" style="text-align: right;">
                                                                <?php if (isset($f['desc'])/* AND $f['field_type'] !== 'none' */): ?>
                                                                    <div class="woobe_options_li_desc">
                                                                        <br />
                                                                        <?php echo WOOBE_HELPER::draw_tooltip($f['desc']); ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>

                                                            <div style="clear: both;"></div>

                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>


                                        <?php endforeach; ?>


                                    </ul>


                                    <br />

                                    <input type="submit" class="button button-primary button-primary" value="<?php echo __('Save all settings', 'woo-bulk-editor') ?>" />


                                </td>
                                <td style="width: 45%; vertical-align: top; padding-left: 7px;">
                                    <h4><?php _e('General settings', 'woo-bulk-editor') ?> - <a href="https://bulk-editor.com/document/settings/" style="height: 21px; line-height: normal;" target="_blank" class="button button-primary"><span class="icon-book"></span>&nbsp;<?php _e('Documentation', 'woo-bulk-editor') ?></a></h4>

                                    <?php foreach ($total_settings as $k => $o) : ?>
                                        <div class="woobe-control-section">
                                            <h5><?php echo $o['title'] ?></h5>
                                            <div class="woobe-control-container">
                                                <div class="woobe-control">

                                                    <?php
                                                    switch ($o['type']) {
                                                        case 'select':
                                                            ?>
                                                            <div class="select-wrap">
                                                                <select name="woobe_options[options][<?php echo $k ?>]">
                                                                    <?php foreach ($o['select_options'] as $kk => $vv) : ?>
                                                                        <option <?php selected($kk == $o['value']) ?> value="<?php echo $kk ?>"><?php echo $vv['title'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <?php
                                                            break;

                                                        case 'number':
                                                            ?>
                                                    <input type="number" name="woobe_options[options][<?php echo $k ?>]" value="<?php echo $o['value'] ?>" readonly="" />
                                                            <?php if ($show_notes): ?><br /><small class="woobe-free-version">(<?php _e('premium version', 'woo-bulk-editor') ?>)</small><?php endif; ?>
                                                            <?php
                                                            break;


                                                        default:
                                                            //textinput
                                                            ?>
                                                            <input type="text" name="woobe_options[options][<?php echo $k ?>]" value="<?php echo $o['value'] ?>" />
                                                            <?php
                                                            break;
                                                    }
                                                    ?>

                                                </div>
                                                <div class="woobe-description">
                                                    <p class="description"><?php echo $o['desc'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>


                                    <br />

                                    <input type="submit" class="button button-primary button-primary" value="<?php echo __('Save all settings', 'woo-bulk-editor') ?>" />


                                </td>
                            </tr>
                        </table>

                    </form>
                </section>


                <!--------------------------------- taxonomies terms data ---------------------------------------------->
                <div style="display: none;">
                    <div id="taxonomies_popup_list_li_tpl">
                        <li data-search-value="__SEARCH_TXT__" class="quick_search_element __TOP_LI__">
                            <div class="quick_search_element_container">
                                <input type="checkbox" __CHECK__ name="woobe_tax_terms[]" value="__TERM_ID__" id="term___TERM_ID__">&nbsp;<label for="term___TERM_ID__">__LABEL__</label><br>
                            </div>
                            __CHILDS__
                        </li>
                    </div>
                </div>



                <script>
                    var taxonomies_terms = {};

<?php if (!empty($tax_keys)): ?>
    <?php foreach ($tax_keys as $tax_key) : ?>

                            taxonomies_terms['<?php echo $tax_key ?>'] =<?php echo json_encode(WOOBE_HELPER::get_taxonomies_terms_hierarchy($tax_key)) ?>;

    <?php endforeach; ?>
<?php endif; ?>



<?php if (!empty($attribute_keys)): ?>
    <?php foreach ($attribute_keys as $tax_key) : ?>

                            taxonomies_terms['<?php echo $tax_key ?>'] =<?php echo json_encode(WOOBE_HELPER::get_taxonomies_terms_hierarchy($tax_key)) ?>;

    <?php endforeach; ?>
<?php endif; ?>


                    var woobe_active_fields =<?php echo json_encode($settings_fields_full) ?>;
                </script>



                <?php do_action('woobe_ext_panel_tabs_content'); //including extensions scripts        ?>


                <div style="clear: both;"></div>

            </div>

        </div>


    </section><!--/ .woobe-section-->
    <div class="made_by">
        <a href="https://pluginus.net/" target="_blank">Created by PluginUs.NET</a><br />
    </div>
    <div style="clear: both;"></div>

    <div id="woobe_buffer" style="display: none;"></div>

    <div id="woobe_html_buffer" class="woobe_info_popup" style="display: none;"></div>


    <!-------------------------------- advanced panel popups ------------------------------------------->

    <div id="woobe_tools_panel_profile_popup" style="display: none;">
        <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 15002; width: 80%; height: 320px;">
            <div class="woobe-modal-inner">
                <div class="woobe-modal-inner-header">
                    <h3 class="woobe-modal-title"><?php _e('Columns profile', 'woo-bulk-editor') ?></h3>
                    <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close8"></a>
                </div>
                <div class="woobe-modal-inner-content">

                    <div class="woobe-form-element-container">
                        <div class="woobe-name-description">
                            <strong><?php echo __('Columns profiles', 'woo-bulk-editor') ?></strong>
                            <span><?php echo __('Here you can load previously saved columns profile. After pressing on the load button, page reloading will start immediately!', 'woo-bulk-editor') ?></span>

                            <?php if ($show_notes): ?>
                                <span style="color: red;"><?php echo __('In FREE version of the plugin it is possible to create 1 profile.', 'woo-bulk-editor') ?></span>
                            <?php endif; ?>

                            <?php if (isset($current_profile['title'])): ?>
                                <span class="current_profile_disclaimer"><?php
                                    printf(__('Current profile is: %s %s', 'woo-bulk-editor'), $current_profile['title'], WOOBE_HELPER::draw_link(array(
                                                'href' => $current_profile['key'],
                                                'title' => WOOBE_HELPER::draw_image(WOOBE_ASSETS_LINK . 'images/delete.png', '', '', 15),
                                                'class' => 'woobe_delete_profile',
                                                'title_attr' => __('remove current columns profile', 'woo-bulk-editor')
                                    )))
                                    ?></span>
                            <?php endif; ?>

                        </div>
                        <div class="woobe-form-element">
                            <div class="products_search_container">
                                <select id="woobe_load_profile">
                                    <option value="0"><?php _e('Select profile to load', 'woo-bulk-editor') ?></option>
                                    <?php foreach ($profiles as $pkey => $pvalue) : ?>
                                        <option <?php selected((isset($current_profile['key']) ? $current_profile['key'] === trim($pkey) : false)) ?> value="<?php echo $pkey ?>"><?php echo $pvalue['title'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="cssload-container" style="display: none;">
                                    <div class="cssload-whirlpool"></div>
                                </div><br />

                                <div style="display: none;"  id="woobe_load_profile_actions">
                                    <a href="javascript:void(0)" class="button button-primary button" id="woobe_load_profile_btn"><?php _e('load', 'woo-bulk-editor') ?></a>&nbsp;
                                    <a href="#" class="button button-primary button woobe_delete_profile"><?php _e('remove', 'woo-bulk-editor') ?></a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="woobe-form-element-container">
                        <div class="woobe-name-description">
                            <strong><?php echo __('New Profile', 'woo-bulk-editor') ?></strong>
                            <span><?php echo __('Here you can type any title and save current columns set and their order. Type here any title and then press Save button OR press Enter button on your keyboard!', 'woo-bulk-editor') ?></span>
                        </div>
                        <div class="woobe-form-element">
                            <div class="products_search_container">
                                <input type="text" value="" id="woobe_new_profile" />
                            </div>
                        </div>
                    </div>

                    <!-- <div class="woobe-form-element-container"></div> -->
                </div>
                <div class="woobe-modal-inner-footer">
                    <a href="javascript:void(0)" class="button button-primary button-large button-large-1"  id="woobe_new_profile_btn"><?php echo __('Create', 'woo-bulk-editor') ?></a>
                    <a href="javascript:void(0)" class="woobe-modal-close8 button button-primary button-large button-large-2"><?php echo __('Close', 'woo-bulk-editor') ?></a>
                </div>
            </div>
        </div>

        <div class="woobe-modal-backdrop" style="z-index: 15001;"></div>

    </div>


    <div id="woobe_new_term_popup" style="display: none;">
        <div class="woobe-modal woobe-modal2 woobe-style" style="z-index: 16004 !important; width: 80%; height: 320px; overflow: visible;">
            <div class="woobe-modal-inner">
                <div class="woobe-modal-inner-header">
                    <h3 class="woobe-modal-title" style="font-size: 19px;"><?php printf(__('New term for [%s]', 'woo-bulk-editor'), '<span></span>') ?></h3>
                    <a href="javascript:void(0)" class="woobe-modal-close woobe-modal-close9"></a>
                </div>
                <div class="woobe-modal-inner-content" style="overflow: visible;">

                    <div class="woobe-form-element-container">
                        <div class="woobe-name-description">
                            <strong><?php echo __('New Term(s)', 'woo-bulk-editor') ?></strong>
                            <span><?php echo __('Here you can write title for the new term. Use comma to create some new tags on the same time! New terms with already existed names will not be created!', 'woo-bulk-editor') ?></span>
                        </div>
                        <div class="woobe-form-element">
                            <input type="text" value="" id="woobe_new_term_title" style="width: 100%;" />
                        </div>
                    </div>


                    <div class="woobe-form-element-container">
                        <div class="woobe-name-description">
                            <strong><?php echo __('Slug(s) of the new term', 'woo-bulk-editor') ?></strong>
                            <span><?php echo __('Here you can write slug for the the new term (optionally). Use comma for slug(s) when you create some on the same time terms, or leave slug field empty to create slug(s) automatically', 'woo-bulk-editor') ?></span>
                        </div>
                        <div class="woobe-form-element">
                            <input type="text" value="" id="woobe_new_term_slug" style="width: 100%;" />
                        </div>
                    </div>


                    <div class="woobe-form-element-container">
                        <div class="woobe-name-description">
                            <strong><?php echo __('Parent of the new term(s)', 'woo-bulk-editor') ?></strong>
                            <span><?php echo __('Here you can select parent for the the new term (optionally)', 'woo-bulk-editor') ?></span>
                        </div>
                        <div class="woobe-form-element">
                            <select id="woobe_new_term_parent"></select>
                        </div>
                    </div>

                </div>
                <div class="woobe-modal-inner-footer">
                    <a href="#" class="button button-primary button-large button-large-1" id="woobe_new_term_create"><?php echo __('Create', 'woo-bulk-editor') ?></a>
                    <a href="javascript:void(0)" class="woobe-modal-close9 button button-primary button-large button-large-2"><?php echo __('Cancel', 'woo-bulk-editor') ?></a>
                </div>
            </div>
        </div>

        <div class="woobe-modal-backdrop" style="z-index: 16003;"></div>

    </div>

    <?php do_action('woobe_page_end') ?>


    <div class="external-scroll_wrapper">
        <div class="external-scroll_x">
            <div class="scroll-element_outer">
                <div class="scroll-element_size"></div>
                <div class="scroll-element_track"></div>
                <div class="scroll-bar"></div>
            </div>
        </div>
    </div>

</div>



<?php if ($show_notes): ?>
    <hr />

    <table style="width: 100%;">
        <tr>

            <td style="width: 33%;">
                <h3 style="color: tomato;"><?php _e("UPGRADE TO FULL VERSION", 'woo-bulk-editor') ?>:</h3>
                <a href="https://bulk-editor.com/a/buy" target="_blank"><img src="<?php echo WOOBE_LINK ?>assets/images/woobe_banner.png" width="300" alt="<?php _e("WOOBE - WooCommerce Bulk Editor Professional", 'woo-bulk-editor'); ?>" /></a>
            </td>

            <td style="width: 33%;">
                <h3><?php _e("WOOCS - WooCommerce Currency Switcher", 'woo-bulk-editor') ?>:</h3>
                <a href="http://codecanyon.net/item/woocommerce-currency-switcher/8085217?ref=realmag777" target="_blank"><img src="<?php echo WOOBE_LINK ?>assets/images/woocs_banner.png" alt="<?php _e("WooCommerce Currency Switche", 'woo-bulk-editor'); ?>" /></a>
            </td>

            <td style="width: 33%;">
                <h3><?php _e("WOOF - WooCommerce Products Filter", 'woo-bulk-editor') ?>:</h3>
                <a href="http://codecanyon.net/item/woof-woocommerce-products-filter/11498469?ref=realmag777" target="_blank"><img src="<?php echo WOOBE_LINK ?>assets/images/woof_banner.png" alt="<?php _e("WOOF - WooCommerce Products Filter", 'woo-bulk-editor'); ?>" /></a>
            </td>

        </tr>
    </table>
<?php endif; ?>



<?php if (!empty($columns_colors)): ?>

    <style type="text/css">

        <?php foreach ($columns_colors as $key => $colors) : ?>

            <?php if (!empty($colors['col_color'])): ?>
                td[data-field="<?php echo $key ?>"] {
                    background-color: <?php echo $colors['col_color'] ?>;
                }
            <?php endif; ?>


            <?php if (!empty($colors['txt_color'])): ?>
                td[data-field="<?php echo $key ?>"] {
                    color: <?php echo $colors['txt_color'] ?>;
                }

                td[data-field="<?php echo $key ?>"] select,
                td[data-field="<?php echo $key ?>"] li.search-choice span,
                td[data-field="<?php echo $key ?>"] li.woobe_li_tag,
                td[data-field="<?php echo $key ?>"] .woobe-button,
                td[data-field="<?php echo $key ?>"] input.woobe_calendar,
                td[data-field="<?php echo $key ?>"] .woobe_btn_gal_block{
                    color: <?php echo $colors['txt_color'] ?> !important;
                }
            <?php endif; ?>

        <?php endforeach; ?>

    </style>

    <?php
endif;
?>