<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOBE;
?>


<div class="notice notice-warning">
    <p>
        <?php printf(__('Bulk editing will be applied to: %s', 'woo-bulk-editor'), '<span class="woobe_action_will_be_applied_to">' . __('all the products on the site', 'woo-bulk-editor') . '</span>') ?>
    </p>
</div>

<form method="post" id="woobe_bulk_form">

    <div style="display: none;" id="woobe_show_variations_mode">
        <div class="notice notice-warning">
            <p>
                <?php _e('Bulk operations will be applied to the variations of the products, and their parent-products will be ignored!', 'woo-bulk-editor') ?>
            </p>
        </div>


        <?php $combination_attributes = wc_get_attribute_taxonomies(); ?>

        <?php if (!empty($combination_attributes)): ?>
            <hr />

            <select id="woobe_bulk_combination_attributes" multiple="" class="chosen-select" style="width: 350px;" data-placeholder="<?php _e('select combination of attributes', 'woo-bulk-editor') ?>">
                <?php foreach ($combination_attributes as $a) : ?>
                    <option value="pa_<?php echo $a->attribute_name ?>"><?php echo $a->attribute_label ?></option>
                <?php endforeach; ?>
            </select>&nbsp;<a href="#" id="woobe_bulk_add_combination_to_apply" class="button button-primary button"><?php _e('Add attributes combination to apply on', 'woo-bulk-editor') ?></a><br />

            <ul id="woobe_bulk_to_var_combinations_apply"></ul>

            <small style="font-style: italic;"><?php _e('Select combination(s) of attributes if you need it by your logic, another way changes will be applied to all variations of all/filtered/selected variable products. Order of the terms in combination(s) has no matter.', 'woo-bulk-editor') ?></small><br />

            <br />
            <hr />
            <br />

        <?php else: ?>

            <strong><?php
                printf(__('No attributes created, you can do it %s', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                            'href' => admin_url('edit.php?post_type=product&page=product_attributes'),
                            'title' => __('here', 'woo-bulk-editor')
                )));
                ?></strong>

        <?php endif; ?>

    </div>



    <div class="woobe-tabs woobe-tabs-style-shape">
        <nav>
            <ul>
                <li class="tab-current">
                    <a href="#woobe-bulk-basic" onclick="return woobe_init_js_intab('tabs-woobe-bulk-basic')"><?php _e('Basic', 'woo-bulk-editor') ?></a>
                </li>
                <li>
                    <a href="#woobe-bulk-additional" onclick="return woobe_init_js_intab('tabs-woobe-bulk-additional')"><?php _e('Additional', 'woo-bulk-editor') ?></a>
                </li>
            </ul>
        </nav>
        <div class="content-wrap">
            <section id="woobe-bulk-basic" class="content-current">

                <div class="col-lg-12">
                    <ul class="woobe_filter_form_texts">
                        <li>
                            <small style="font-style: italic;"><?php _e('text', 'woo-bulk-editor') ?></small><br />
                            <?php woobe_bulk_draw_text(apply_filters('woobe_bulk_text', $text_keys)); ?>
                        </li>
                    </ul>
                </div>



                <br />
                <hr style="clear: both;" />

                <div class="col-lg-12">
                    <ul class="woobe_filter_form_texts">
                        <li>
                            <small style="font-style: italic;"><?php _e('numeric', 'woo-bulk-editor') ?></small><br />
                            <?php woobe_bulk_draw_nums(apply_filters('woobe_bulk_number', $num_keys)); ?>
                        </li>
                    </ul>
                </div>

                <br />
                <hr style="clear: both;" />

                <div class="col-lg-12">
                    <ul class="woobe_filter_form_texts">
                        <li>
                            <small style="font-style: italic;"><?php _e('statuses and types', 'woo-bulk-editor') ?></small><br />
                            <?php woobe_bulk_draw_other(apply_filters('woobe_bulk_other', $other_keys)); ?>
                        </li>
                    </ul>
                </div>

                <br />
                <hr style="clear: both;" />

                <div class="col-lg-12">
                    <ul class="woobe_filter_form_texts">
                        <li>
                            <small style="font-style: italic;"><?php _e('taxonomies', 'woo-bulk-editor') ?></small><br />
                            <?php woobe_bulk_draw_taxonomies(); ?>
                        </li>
                    </ul>
                </div>

                <div style="clear: both;"></div>

            </section>

            <section id="woobe-bulk-additional">
                <div class="col-lg-12">
                    <ul class="woobe_filter_form_texts">
                        <li><?php woobe_bulk_draw_add1($settings_fields); ?></li>
                    </ul>
                </div>

                <br />
                <hr style="clear: both;" />
            </section>
        </div>
    </div>



</form>

<div style="clear: both;"></div>
<br />
<div style="clear: both;"></div>
<div class="woobe_progress" style="display: none;">
    <div class="woobe_progress_in" id="woobe_bulk_progress">0%</div>
</div>
<br />
<hr />
<div class="woobe_delete_wraper">
    <input type="checkbox" disabled="" value='1'>
    <?php if ($WOOBE->show_notes): ?>
        <label><?php _e('Bulk deleting', 'woo-bulk-editor') ?> (<small class="woobe-free-version"><?php _e('premium version', 'woo-bulk-editor') ?></small>)</label>
    <?php else: ?>
        <label><?php _e('Bulk deleting', 'woo-bulk-editor') ?></label>
    <?php endif; ?>

    <br />
    <a href="#" disabled='disabled' class="button button-primary button-large" id="woobe_bulk_delete_products_btn" ><?php _e('Delete products!', 'woo-bulk-editor') ?></a>
</div>

<a href="#" class="button button-primary button-large" id="woobe_bulk_products_btn" style="display: none;"><?php _e('Do Bulk Edit', 'woo-bulk-editor') ?></a>
<a href="#" class="button button-primary woobe_bulk_terminate" title="<?php _e('terminate bulk operation', 'woo-bulk-editor') ?>" style="display: none;"><?php _e('terminate bulk operation', 'woo-bulk-editor') ?></a>

<hr />
<h4><?php _e('Notes', 'woo-bulk-editor') ?>:</h4>
<ul>
    <?php if ($WOOBE->show_notes) : ?>
        <li style="color: red;">* <?php _e('In red containers wrapped fields which are not possible modify in bulk in FREE version of the plugin!', 'woo-bulk-editor') ?><br /></li>
    <?php endif; ?>

    <li>* <?php _e('In the case of an aborted bulk-operation you can roll back changes in the tab History', 'woo-bulk-editor') ?><br /></li>
    <li>* <?php
        printf(__('Time by time (one time per week for example) - make the backup of your site database. For example by %s', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(
                        array(
                            'href' => 'https://wordpress.org/plugins/wp-migrate-db/',
                            'title' => __('this plugin', 'woo-bulk-editor'),
                            'target' => '_blank'
                        )
        ))
        ?><br /></li>
    <li>

        <a href="https://bulk-editor.com/document/woocommerce-products-bulk-edit/" style="height: 21px; line-height: normal;" target="_blank" class="button button-primary"><span class="icon-book"></span>&nbsp;<?php _e('Documentation', 'woo-bulk-editor') ?></a>

    </li>
</ul><br />
<br />

<!-------------------------------------------------------------------->
<?php

function woobe_bulk_draw_text($bulk_fields) {
    global $WOOBE;
    $fields = $WOOBE->settings->get_fields();
    ?>
    <?php foreach ($bulk_fields as $field_key => $field) : ?>

        <?php
        if ($WOOBE->settings->current_user_role != 'administrator') {
            if (intval($WOOBE->settings->get_shop_manager_visibility()[$field_key]) === 0) {
                continue;
            }
        }
        ?>

        <?php if ($fields[$field_key]['edit_view'] == 'meta_popup_editor'): ?>
            <div class="col-lg-4">
                <div class='filter-unit-wrap <?php echo $fields[$field_key]['css_classes'] ?> <?php if (!$fields[$field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                    <div class="col-lg-1">
                        <div style="height: 4px;"></div>
                        <?php if ($fields[$field_key]['direct']): ?>
                            <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $field_key ?>]" class="bulk_checker" data-field-key="<?php echo $field_key ?>" data-title="<?php echo $fields[$field_key]['title'] ?>" value="1" /><br />
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-11">

                        <div>
                            <?php echo WOOBE_HELPER::draw_meta_popup_editor_btn($field_key, 0, $fields[$field_key]['title']); ?>
                        </div>

                        <input type="hidden" name="woobe_bulk[<?php echo $field_key ?>][value]" value="" />
                        <input type="hidden" name="woobe_bulk[<?php echo $field_key ?>][behavior]" value="new" />
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-lg-4">
                <div class='filter-unit-wrap <?php echo $field['css_classes'] ?> <?php if (!$fields[$field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                    <div class="col-lg-1">
                        <div style="height: 4px;"></div>

                        <?php if ($fields[$field_key]['direct']): ?>
                            <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $field_key ?>]" class="bulk_checker" data-field-key="<?php echo $field_key ?>" data-title="<?php echo $field['title'] ?>" value="1" /><br />
                        <?php endif; ?>

                    </div>
                    <div class="col-lg-8">
                        <input type="text" class="woobe_bulk_value" disabled="" placeholder="<?php echo $field['title'] ?>" name="woobe_bulk[<?php echo $field_key ?>][value]" value="" />
                    </div>
                    <div class="col-lg-1">
                        <select class="woobe_bulk_add_special_key" disabled="">
                            <option value="-1"><?php _e('variable', 'woo-bulk-editor') ?></option>
                            <option value="{TITLE}"><?php _e('TITLE', 'woo-bulk-editor') ?></option>
                            <option value="{ID}">ID</option>
                            <option value="{SKU}"><?php _e('SKU', 'woo-bulk-editor') ?></option>
                            <option value="{MENU_ORDER}"><?php _e('MENU ORDER', 'woo-bulk-editor') ?></option>
                            <option value="{PARENT_ID}"><?php _e('PARENT ID', 'woo-bulk-editor') ?></option>
                            <option value="{PARENT_TITLE}"><?php _e('PARENT TITLE', 'woo-bulk-editor') ?></option>
                            <option value="{PARENT_SKU}"><?php _e('PARENT SKU', 'woo-bulk-editor') ?></option>
                            <option value="{REGULAR_PRICE}"><?php _e('REGULAR PRICE', 'woo-bulk-editor') ?></option>
                            <option value="{SALE_PRICE}"><?php _e('SALE PRICE', 'woo-bulk-editor') ?></option>

                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select name="woobe_bulk[<?php echo $field_key ?>][behavior]" disabled="" class="woobe_bulk_value_signs" data-key="<?php echo $field_key ?>">
                            <option value="append"><?php _e('append', 'woo-bulk-editor') ?></option>
                            <option value="prepend"><?php _e('prepend', 'woo-bulk-editor') ?></option>
                            <option value="new"><?php _e('new', 'woo-bulk-editor') ?></option>
                            <option value="replace"><?php _e('replace', 'woo-bulk-editor') ?></option>
                        </select>
                    </div>
                    <div style="clear: both;"></div>

                    <div class='filter-unit-wrap woobe_bulk_replace_to_<?php echo $field_key ?>' style="display: none;">

                        <div class="col-lg-2">
                            <select name="woobe_bulk[<?php echo $field_key ?>][case]" disabled="">
                                <option value="same"><?php _e('same case', 'woo-bulk-editor') ?></option>
                                <option value="ignore"><?php _e('ignore case', 'woo-bulk-editor') ?></option>
                            </select>
                        </div>

                        <div class="col-lg-9">
                            <input type="text" class="woobe_bulk_value" disabled="" placeholder="<?php _e('replace to text', 'woo-bulk-editor') ?>" name="woobe_bulk[<?php echo $field_key ?>][replace_to]" value="" />
                        </div>


                        <div class="col-lg-1">
                            <select class="woobe_bulk_add_special_key" disabled="">
                                <option value="-1"><?php _e('variable', 'woo-bulk-editor') ?></option>
                                <option value="{TITLE}"><?php _e('TITLE', 'woo-bulk-editor') ?></option>
                                <option value="{ID}">ID</option>
                                <option value="{SKU}"><?php _e('SKU', 'woo-bulk-editor') ?></option>
                                <option value="{MENU_ORDER}"><?php _e('MENU ORDER', 'woo-bulk-editor') ?></option>
                                <option value="{PARENT_ID}"><?php _e('PARENT ID', 'woo-bulk-editor') ?></option>
                                <option value="{PARENT_TITLE}"><?php _e('PARENT TITLE', 'woo-bulk-editor') ?></option>
                                <option value="{PARENT_SKU}"><?php _e('PARENT SKU', 'woo-bulk-editor') ?></option>
                                <option value="{REGULAR_PRICE}"><?php _e('REGULAR PRICE', 'woo-bulk-editor') ?></option>
                                <option value="{SALE_PRICE}"><?php _e('SALE PRICE', 'woo-bulk-editor') ?></option>

                            </select>
                        </div>



                        <div style="clear: both;"></div>
                    </div>

                </div>
            </div>
        <?php endif; ?>



    <?php endforeach; ?>
    <div style="clear: both;"></div>
    <?php
}

function woobe_bulk_draw_nums($filter_keys) {
    global $WOOBE;
    //print_r($filter_keys);exit;
    ?>
    <?php foreach ($filter_keys as $field_key => $field) : ?>

        <?php
        if ($WOOBE->settings->current_user_role != 'administrator') {
            if (intval($WOOBE->settings->get_shop_manager_visibility()[$field_key]) === 0) {
                continue;
            }
        }
        ?>

        <div class="col-lg-4">
            <div class='filter-unit-wrap <?php echo $field['css_classes'] ?> <?php if (!$field['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($field['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $field_key ?>]" class="bulk_checker" data-field-key="<?php echo $field_key ?>" data-title="<?php echo $field['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-7">
                    <input type="number" class="woobe_bulk_value" disabled="" placeholder="<?php echo $field['title'] ?>" name="woobe_bulk[<?php echo $field_key ?>][value]" value="" />
                </div>
                <div class="col-lg-4">
                    <select name="woobe_bulk[<?php echo $field_key ?>][behavior]" disabled="">
                        <?php foreach ($field['options'] as $key => $title) : ?>
                            <option value="<?php echo $key ?>"><?php echo $title ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endforeach; ?>
    <div style="clear: both;"></div>
    <br />
    <div class="col-lg-4">
        <?php WOOBE_HELPER::draw_rounding_drop_down() ?>
    </div>

    <div class="col-lg-1">
        <?php
        WOOBE_HELPER::draw_tooltip(sprintf(__('Select how to round float values fractions in the numeric fields. Works for prices and numeric meta fields. Read more %s.', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                            'title' => __('here', 'woo-bulk-editor'),
                            'href' => 'https://bulk-editor.com/document/woocommerce-price-rounding/',
                            'target' => '_blank'
        ))))
        ?>
    </div>

    <div style="clear: both;"></div>
    <?php
}

function woobe_bulk_draw_taxonomies() {
    global $WOOBE;
    //get all products taxonomies
    $taxonomy_objects = get_object_taxonomies('product', 'objects');
    unset($taxonomy_objects['product_type']);
    unset($taxonomy_objects['product_visibility']);
    unset($taxonomy_objects['product_shipping_class']);

    //***

    if (!empty($taxonomy_objects)) {
        foreach ($taxonomy_objects as $t) {

            if ($WOOBE->settings->current_user_role != 'administrator') {
                if (intval($WOOBE->settings->get_shop_manager_visibility()[$t->name]) === 0) {
                    continue;
                }
            }

            //***

            if (substr($t->name, 0, 3) === 'pa_') {
                //continue; //without attributes
            }

            $terms_by_parents = array();

            $terms = get_terms(array(
                'taxonomy' => $t->name,
                'hide_empty' => false
            ));

            if (!empty($terms)) {
                foreach ($terms as $k => $term) {
                    if ($term->parent > 0) {
                        $terms_by_parents[$term->parent][] = $term;
                        unset($terms[$k]);
                    }
                }
            }
            ?>
            <div class="col-lg-4">
                <div class='filter-unit-wrap not-for-variations <?php if (!$WOOBE->settings->get_fields()[$t->name]['direct']): ?>woobe-direct-field<?php endif; ?>' style="overflow: visible;">

                    <div class="col-lg-1">
                        <div style="height: 4px;"></div>
                        <?php if ($WOOBE->settings->get_fields()[$t->name]['direct']): ?>
                            <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $t->name ?>]" class="bulk_checker" data-field-key="<?php echo $t->name ?>" data-title="<?php echo $t->label ?>" value="1" /><br />
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-9">
                        <select class="chosen-select woobe_filter_select" disabled="" multiple="" id="woobe_bulk_taxonomies_<?php echo $t->name ?>" name="woobe_bulk[<?php echo $t->name ?>][value][]" data-placeholder="<?php echo $t->label ?>">
                            <?php if (!empty($terms)): ?>
                                <?php foreach ($terms as $tt) : ?>
                                    <option value="<?php echo $tt->term_id ?>"><?php echo $tt->name ?></option>
                                    <?php draw_child_filter_terms($tt->term_id, $terms_by_parents, 1) ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <div class='select-wrap' style="display: inline-block">
                            <select name="woobe_bulk[<?php echo $t->name ?>][behavior]" disabled="">
                                <option value="append"><?php _e('append', 'woo-bulk-editor') ?></option>
                                <option value="replace"><?php _e('replace', 'woo-bulk-editor') ?></option>
                                <option value="remove"><?php _e('remove', 'woo-bulk-editor') ?></option>
                            </select>
                        </div>
                    </div>

                    <div style="clear: both;"></div>
                </div>
            </div>
            <?php
        }
        ?>
        <div style="clear: both;"></div>
        <?php
    }
}

function woobe_bulk_draw_other($filter_keys) {
    global $WOOBE;
    ?>
    <?php foreach ($filter_keys as $field_key => $field) : ?>

        <?php
        if ($WOOBE->settings->current_user_role != 'administrator') {
            if (intval($WOOBE->settings->get_shop_manager_visibility()[$field_key]) === 0) {
                continue;
            }
        }
        ?>

        <div class="col-lg-4">
            <div class='filter-unit-wrap <?php echo $field['css_classes'] ?> <?php if (!$field['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($field['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $field_key ?>]" class="bulk_checker" data-field-key="<?php echo $field_key ?>" data-title="<?php echo $field['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>

                <div class="col-lg-11">
                    <?php
                    $opt = array(-1 => sprintf(__('Set: %s', 'woo-bulk-editor'), $field['title']));
                    $opt = array_merge($opt, $field['options']);

                    echo WOOBE_HELPER::draw_select(array(
                        'options' => $opt,
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select',
                        'name' => 'woobe_bulk[' . $field_key . '][value]',
                        'disabled' => TRUE
                    ));
                    ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div style="clear: both;"></div>
    <?php
}

/* * ****************************************** */

function woobe_bulk_draw_add1($fields) {
    global $WOOBE;

    //***

    $options1 = array(
        'new' => __('set new', 'woo-bulk-editor'),
        'invalue' => __('increase by value', 'woo-bulk-editor'),
        'devalue' => __('decrease by value', 'woo-bulk-editor')
    );

    //***

    $current_field_key = '_thumbnail_id';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$fields[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($fields[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-4">
                    <img src="" alt="" width="30" id="woobe_bulk_select_thumb" />
                    <input type="hidden" class="woobe_bulk_value" name="woobe_bulk[<?php echo $current_field_key ?>][value]" value="" />
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                </div>
                <div class="col-lg-7">
                    <a href="#" id="woobe_bulk_select_thumb_btn" class="button button-primary button"><?php _e('select thumbnail', 'woo-bulk-editor') ?></a>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>


    <?php
    $current_field_key = 'date_on_sale_from';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$fields[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($fields[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <div style="position: relative;">
                        <?php
                        echo WOOBE_HELPER::draw_calendar($current_field_key, $fields[$current_field_key]['title'], $current_field_key, '', 'woobe_bulk[' . $current_field_key . '][value]', true);
                        ?>
                        <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $current_field_key = 'date_on_sale_to';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$fields[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($fields[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <div style="position: relative;">
                        <?php
                        echo WOOBE_HELPER::draw_calendar($current_field_key, $fields[$current_field_key]['title'], $current_field_key, '', 'woobe_bulk[' . $current_field_key . '][value]', true);
                        ?>
                        <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php
    $current_field_key = 'post_date';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$fields[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($fields[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <div style="position: relative;">
                        <?php
                        echo WOOBE_HELPER::draw_calendar($current_field_key, $fields[$current_field_key]['title'], $current_field_key, '', 'woobe_bulk[' . $current_field_key . '][value]', true);
                        ?>
                        <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="clear"></div>
    <br />
    <hr style="clear: both;">
    <br />

    <?php $filter_keys2 = array('weight', 'length', 'width', 'height'); ?>

    <?php foreach ($filter_keys2 as $current_field_key) : ?>

        <?php
        if ($WOOBE->settings->current_user_role != 'administrator') {
            if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
                continue;
            }
        }
        ?>

        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-7">
                    <input type="number" class="woobe_bulk_value" disabled="" placeholder="<?php echo $fields[$current_field_key]['title'] ?>" name="woobe_bulk[<?php echo $current_field_key ?>][value]" value="" />
                </div>
                <div class="col-lg-4">

                    <select name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" disabled="">
                        <?php foreach ($options1 as $key => $title) : ?>
                            <option value="<?php echo $key ?>"><?php echo $title ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div style="clear: both;"></div>
            </div>
        </div>

    <?php endforeach; ?>


    <div class="clear"></div>
    <br />
    <hr style="clear: both;">
    <br />

    <?php
    $current_field_key = 'manage_stock';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <?php
                    echo WOOBE_HELPER::draw_select(array(
                        'disabled' => 1,
                        'options' => array(
                            'yes' => __('Manage stock: Yes', 'woo-bulk-editor'), //true
                            'no' => __('Manage stock: No', 'woo-bulk-editor'), //false
                        ),
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select',
                        'name' => 'woobe_bulk[' . $current_field_key . '][value]'
                    ));
                    ?>
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $current_field_key = 'virtual';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <?php
                    echo WOOBE_HELPER::draw_select(array(
                        'disabled' => 1,
                        'options' => array(
                            'yes' => __('Virtual: yes', 'woo-bulk-editor'), //true
                            'no' => __('Virtual: no', 'woo-bulk-editor'), //false
                        ),
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select',
                        'name' => 'woobe_bulk[' . $current_field_key . '][value]'
                    ));
                    ?>
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $current_field_key = 'downloadable';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <?php
                    echo WOOBE_HELPER::draw_select(array(
                        'disabled' => 1,
                        'options' => array(
                            'yes' => __('Downloadable: yes', 'woo-bulk-editor'), //true
                            'no' => __('Downloadable: no', 'woo-bulk-editor'), //false
                        ),
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select',
                        'name' => 'woobe_bulk[' . $current_field_key . '][value]'
                    ));
                    ?>
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $current_field_key = 'purchase_note';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <input type="text" disabled="" placeholder="<?php echo $fields[$current_field_key]['title'] ?>" class="woobe_bulk_value" name="woobe_bulk[<?php echo $current_field_key ?>][value]" value="" />
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php
    $current_field_key = 'product_shipping_class';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <?php
                    echo WOOBE_HELPER::draw_select(array(
                        'disabled' => 1,
                        'options' => $fields[$current_field_key]['select_options'],
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select',
                        'name' => 'woobe_bulk[' . $current_field_key . '][value]'
                    ));
                    ?>
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $current_field_key = 'sold_individually';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <?php
                    echo WOOBE_HELPER::draw_select(array(
                        'disabled' => 1,
                        'options' => array(
                            'yes' => __('Sold individ.: Yes', 'woo-bulk-editor'), //true
                            'no' => __('Sold individ.: No', 'woo-bulk-editor'), //false
                        ),
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select',
                        'name' => 'woobe_bulk[' . $current_field_key . '][value]'
                    ));
                    ?>
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>


    <?php
    $current_field_key = 'backorders';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <?php
                    echo WOOBE_HELPER::draw_select(array(
                        'disabled' => 1,
                        'options' => $fields[$current_field_key]['select_options'],
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select',
                        'name' => 'woobe_bulk[' . $current_field_key . '][value]'
                    ));
                    ?>
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>


    <?php
    $current_field_key = 'tax_class';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">
                    <?php
                    echo WOOBE_HELPER::draw_select(array(
                        'disabled' => 1,
                        'options' => $fields[$current_field_key]['select_options'],
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select',
                        'name' => 'woobe_bulk[' . $current_field_key . '][value]'
                    ));
                    ?>
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>


    <?php
    $current_field_key = 'gallery';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field AND in_array($current_field_key, $WOOBE->settings->get_fields_keys())): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">

                    <div>
                        <?php echo WOOBE_HELPER::draw_gallery_popup_editor_btn($current_field_key, 0, array()); ?>
                    </div>

                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][value]" value="" />
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>


    <?php
    $current_field_key = 'download_files';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field AND in_array($current_field_key, $WOOBE->settings->get_fields_keys())): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">

                    <div>
                        <?php echo WOOBE_HELPER::draw_downloads_popup_editor_btn($current_field_key, 0, 0); ?>
                    </div>

                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][value]" value="" />
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $current_field_key = 'cross_sell_ids';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field AND in_array($current_field_key, $WOOBE->settings->get_fields_keys())): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">

                    <div>
                        <?php echo WOOBE_HELPER::draw_cross_sells_popup_editor_btn($current_field_key, 0, array()); ?>
                    </div>

                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][value]" value="" />
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $current_field_key = 'upsell_ids';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field AND in_array($current_field_key, $WOOBE->settings->get_fields_keys())): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">

                    <div>
                        <?php echo WOOBE_HELPER::draw_upsells_popup_editor_btn($current_field_key, 0, array()); ?>
                    </div>

                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][value]" value="" />
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $current_field_key = 'grouped_ids';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    ?>

    <?php if ($show_field AND in_array($current_field_key, $WOOBE->settings->get_fields_keys())): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11">

                    <div>
                        <?php echo WOOBE_HELPER::draw_grouped_popup_editor_btn($current_field_key, 0, array()); ?>
                    </div>

                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][value]" value="" />
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>
    <?php
    $current_field_key = 'post_author';
    $show_field = true;
    if ($WOOBE->settings->current_user_role != 'administrator') {
        if (intval($WOOBE->settings->get_shop_manager_visibility()[$current_field_key]) === 0) {
            $show_field = false;
        }
    }
    $opt_auth = array();
    $users = get_users(array('fields' => array('ID', 'display_name')));
    foreach ($users as $user) {
        $opt_auth[$user->ID] = $user->display_name;
    }
    ?>
    <?php if ($show_field): ?>
        <div class="col-lg-3">
            <div class='filter-unit-wrap woobe_post_author_edit <?php echo $fields[$current_field_key]['css_classes'] ?> <?php if (!$WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>woobe-direct-field<?php endif; ?>'>
                <div class="col-lg-1">
                    <div style="height: 4px;"></div>
                    <?php if ($WOOBE->settings->get_fields()[$current_field_key]['direct']): ?>
                        <input type="checkbox" title='<?php _e('select it to use', 'woo-bulk-editor') ?>' name="woobe_bulk[is][<?php echo $current_field_key ?>]" class="bulk_checker" data-field-key="<?php echo $current_field_key ?>" data-title="<?php echo $fields[$current_field_key]['title'] ?>" value="1" /><br />
                    <?php endif; ?>
                </div>
                <div class="col-lg-11 ">
                    <?php
                    echo WOOBE_HELPER::draw_select(array(
                        'disabled' => 1,
                        'options' => $opt_auth,
                        'field' => '',
                        'product_id' => 0,
                        'class' => 'woobe_filter_select chosen-select',
                        'name' => 'woobe_bulk[' . $current_field_key . '][value]'
                    ));
                    ?>
                    <i style="font-size: 8px;"><?php echo $fields[$current_field_key]['title'] ?></i>
                    <input type="hidden" name="woobe_bulk[<?php echo $current_field_key ?>][behavior]" value="new" />
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    <?php endif; ?>

    <div style="clear: both;"></div>
    <?php
}
