<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
?>

<form method="post" id="woobe_filter_form">
    <div class="col-lg-4">
        <ul class="woobe_filter_form_texts">
            <li><?php woobe_filter_draw_text(); ?></li>            
        </ul>
    </div>

    <div class="col-lg-4">
        <ul class="woobe_filter_form_texts">            
            <li><?php woobe_filter_draw_prices(); ?></li>
            <li><?php woobe_filter_draw_other(); ?></li>
        </ul>
    </div>

    <div class="col-lg-4">
        <ul>
            <li><?php woobe_filter_draw_taxonomies(); ?></li>
        </ul>
    </div>
    <div style="clear: both;"></div>
</form>

<div style="clear: both;"></div>
<br />
<hr />
<a href="#" class="button button-primary button-large" id="woobe_filter_products_btn"><?php _e('Filter', 'woo-bulk-editor') ?></a>
<a href="#" class="button button-primary button-large woobe_filter_reset_btn1" style="display: none;"><?php _e('Reset', 'woo-bulk-editor') ?></a>

<div style="clear: both;"></div>
<br />
<a href="https://bulk-editor.com/document/filters/" style="height: 21px; line-height: normal;" target="_blank" class="button button-primary"><span class="icon-book"></span>&nbsp;<?php _e('Documentation', 'woo-bulk-editor') ?></a>
<br />



<!-------------------------------------------------------------->

<?php

function woobe_filter_draw_taxonomies() {
    //get all products taxonomies
    $taxonomy_objects = get_object_taxonomies('product', 'objects');
    unset($taxonomy_objects['product_type']);
    unset($taxonomy_objects['product_visibility']);
    unset($taxonomy_objects['product_shipping_class']);

    //***

    if (!empty($taxonomy_objects)) {
        foreach ($taxonomy_objects as $t) {
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
//            if($t->name== "product_visibility"){
//                $t->label=__('Catalog visibility', 'woo-bulk-editor');
//                foreach ($terms as $k => $term) {
//                    if ($term->slug == 'exclude-from-catalog') {
//                        $term->name= __('Exclude from catalog', 'woo-bulk-editor');
//                    }elseif ($term->slug == 'exclude-from-search') {
//                        $term->name= __('Exclude from search', 'woo-bulk-editor');
//                    }else{
//                        unset($terms[$k]);
//                    }
//
//                }
//            }
            
            ?>
            <div class='filter-unit-wrap' style="overflow: visible;">

                <table style="width: 100%;">
                    <tr>
                        <td style="width: 100%;">
                            <select style="width: 100%; min-width: 200px;" class="chosen-select woobe_filter_select" multiple="" id="woobe_filter_taxonomies_<?php echo $t->name ?>" name="woobe_filter[taxonomies][<?php echo $t->name ?>][]" data-placeholder="<?php echo $t->label ?>">
                                <?php if (!empty($terms)): ?>
                                    <?php foreach ($terms as $tt) : ?>
                                        <option value="<?php echo $tt->term_id ?>"><?php echo $tt->name ?></option>
                                        <?php draw_child_filter_terms($tt->term_id, $terms_by_parents, 1) ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>
                        <td>
                            <div class='select-wrap' style="display: inline-block">
                                <select name="woobe_filter[taxonomies_operators][<?php echo $t->name ?>]">
                                    <option value="IN">OR</option>
                                    <option value="AND">AND</option>
                                    <option value="NOT IN">NOT IN</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="clear: both;"></div>
            <?php
        }
    }
}

//service
function draw_child_filter_terms($term_id, $terms_by_parents, $level) {
    ?>
    <?php if (isset($terms_by_parents[$term_id]) AND ! empty($terms_by_parents[$term_id])): ?>
        <?php
        foreach ($terms_by_parents[$term_id] as $tt) :
            ?>
            <option style="padding-left: <?php echo 5 * $level ?>px" value="<?php echo $tt->term_id ?>"><?php echo $tt->name ?></option>
            <?php draw_child_filter_terms($tt->term_id, $terms_by_parents, $level + 1); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php
}

//*****************************


function woobe_filter_draw_text() {

    $behavior_options = array(
        'like' => __('LIKE', 'woo-bulk-editor'),
        'exact' => __('EXACT', 'woo-bulk-editor'),
        'not' => __('NOT', 'woo-bulk-editor'),
        'begin' => __('BEGIN', 'woo-bulk-editor'),
        'end' => __('END', 'woo-bulk-editor'),
    );


    $filter_keys = array(
        'post__in' => array(
            'placeholder' => __('ID(s). Use comma or/and minus for range', 'woo-bulk-editor'),
            'behavior_options' => array('exact' => __('EXACT', 'woo-bulk-editor'))
        ),
        'post_title' => array(
            'placeholder' => __('Product title ...', 'woo-bulk-editor'),
            'behavior_options' => $behavior_options
        ),
        'post_content' => array(
            'placeholder' => __('Product content ...', 'woo-bulk-editor'),
            'behavior_options' => $behavior_options
        ),
        'post_excerpt' => array(
            'placeholder' => __('Product excerpt ...', 'woo-bulk-editor'),
            'behavior_options' => $behavior_options
        ),
        'post_name' => array(
            'placeholder' => __('Product slug ...', 'woo-bulk-editor'),
            'behavior_options' => $behavior_options
        ),
        'sku' => array(
            'placeholder' => __('SKU, use comma for several ...', 'woo-bulk-editor'),
            'behavior_options' => $behavior_options
        ),
    );


    $filter_keys = apply_filters('woobe_filter_text', $filter_keys);
    ?>

    <?php foreach ($filter_keys as $key => $item) : ?>
        <div class='filter-unit-wrap'>
            <div class="col-lg-10">
                <div style="padding-right: 2px;">
                    <input type="text" placeholder="<?php echo $item['placeholder'] ?>" name="woobe_filter[<?php echo $key ?>][value]" value="" />
                </div>
            </div>
            <div class="col-lg-2">

                <select name="woobe_filter[<?php echo $key ?>][behavior]">
                    <?php foreach ($item['behavior_options'] as $key => $title) : ?>
                        <option value="<?php echo $key ?>"><?php echo $title ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div style="clear: both;"></div>
        </div>

    <?php endforeach; ?>

    <?php
}

function woobe_filter_draw_prices() {

    $filter_keys = array(
        'regular_price' => array(
            'placeholder_from' => __('regular price from', 'woo-bulk-editor'),
            'placeholder_to' => __('regular price to', 'woo-bulk-editor'),
        ),
        'sale_price' => array(
            'placeholder_from' => __('sale price from', 'woo-bulk-editor'),
            'placeholder_to' => __('sale price to', 'woo-bulk-editor'),
        ),
        'stock_quantity' => array(
            'placeholder_from' => __('stock quantity from', 'woo-bulk-editor'),
            'placeholder_to' => __('stock quantity to', 'woo-bulk-editor'),
        ),
        'width' => array(
            'placeholder_from' => __('width from', 'woo-bulk-editor'),
            'placeholder_to' => __('width to', 'woo-bulk-editor'),
        ),
        'height' => array(
            'placeholder_from' => __('height from', 'woo-bulk-editor'),
            'placeholder_to' => __('height to', 'woo-bulk-editor'),
        ),
        'length' => array(
            'placeholder_from' => __('length from', 'woo-bulk-editor'),
            'placeholder_to' => __('length to', 'woo-bulk-editor'),
        ),
        'weight' => array(
            'placeholder_from' => __('weight from', 'woo-bulk-editor'),
            'placeholder_to' => __('weight to', 'woo-bulk-editor'),
        ),
    );


    $filter_keys = apply_filters('woobe_filter_numbers', $filter_keys);
    ?>
    <div class='filter-unit-wrap filter-unit-wrap-numbers'>
        <?php foreach ($filter_keys as $key => $item) : ?>
            <div class="col-lg-6">
                <input type="number" name="woobe_filter[<?php echo $key ?>][from]" min="0" placeholder="<?php echo $item['placeholder_from'] ?>" value="" /><br />
            </div>
            <div class="col-lg-6">
                <input type="number" name="woobe_filter[<?php echo $key ?>][to]" min="0" placeholder="<?php echo $item['placeholder_to'] ?>" value="" />
            </div>

            <div style="height: 4px; clear: both;"></div>
        <?php endforeach; ?>

        <div style="clear: both;"></div>
    </div>

    <?php
}

function woobe_filter_draw_other() {
    $fields = woobe_get_fields();
    ?>
    <div class='filter-unit-wrap'>
        <div class="col-lg-6" style="margin-bottom: 2px;">
            <div style="padding-right: 1px">
                <?php
                $wc_get_product_types = wc_get_product_types();
                $product_types = array();
                $product_types[-1] = __('Product type', 'woo-bulk-editor');
                foreach ($wc_get_product_types as $key => $t) {
                    $product_types[$key] = trim(str_replace('product', '', $t));
                }

                echo WOOBE_HELPER::draw_select(array(
                    'options' => $product_types,
                    'field' => 'product_type',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[product_type]'
                ));
                ?>
            </div>
        </div>
        <div class="col-lg-6" style="margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <?php
                echo WOOBE_HELPER::draw_select(array(
                    'options' => array(-1 => __('Product status', 'woo-bulk-editor')) + $fields['post_status']['select_options'],
                    'field' => 'post_status',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[post_status]'
                ));
                ?>
            </div>
        </div>

        <div class="col-lg-6" style="margin-bottom: 2px;">
            <div style="padding-right: 1px">
                <?php
                $opt = array('' => __('Stock status', 'woo-bulk-editor'));
                $opt = array_merge($opt, wc_get_product_stock_status_options());

                echo WOOBE_HELPER::draw_select(array(
                    'options' => $opt,
                    'field' => 'stock_status',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[stock_status]'
                ));
                ?>
            </div>
        </div>

        <div class="col-lg-6" style="margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <?php
                echo WOOBE_HELPER::draw_select(array(
                    'options' => array(
                        -1 => __('Featured', 'woo-bulk-editor'),
                        1 => __('Is Featured', 'woo-bulk-editor'), //true
                        2 => __('Not Featured', 'woo-bulk-editor'), //false
                    ),
                    'field' => 'featured',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[featured]'
                ));
                ?>
            </div>
        </div>


        <div class="col-lg-6" style="margin-bottom: 2px;">
            <div style="padding-right: 1px">
                <?php
                echo WOOBE_HELPER::draw_select(array(
                    'options' => array(
                        '' => __('Downloadable', 'woo-bulk-editor'),
                        'yes' => __('Yes', 'woo-bulk-editor'), //true
                        'no' => __('No', 'woo-bulk-editor'), //false
                    ),
                    'field' => 'downloadable',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[downloadable]'
                ));
                ?>
            </div>
        </div>

        <div class="col-lg-6" style="margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <?php
                echo WOOBE_HELPER::draw_select(array(
                    'options' => array(
                        '' => __('Sold individually', 'woo-bulk-editor'),
                        'yes' => __('Yes', 'woo-bulk-editor'), //true
                        'no' => __('No', 'woo-bulk-editor'), //false
                    ),
                    'field' => 'sold_individually',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[sold_individually]'
                ));
                ?>
            </div>
        </div>
        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-right: 1px">
                <?php
                echo WOOBE_HELPER::draw_calendar(0, __('Sale price from', 'woo-bulk-editor'), 'date_on_sale_from', '', 'woobe_filter[date_on_sale_from]', true);
                ?>
            </div>
        </div>
        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <?php
                echo WOOBE_HELPER::draw_calendar(0, __('Sale price to', 'woo-bulk-editor'), 'date_on_sale_to', '', 'woobe_filter[date_on_sale_to]', true);
                ?>
            </div>
        </div>


        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-right: 1px">
                <?php
                echo WOOBE_HELPER::draw_calendar('woobe_filter_post_date_from', __('Post date from', 'woo-bulk-editor'), 'post_date_from', '', 'woobe_filter[post_date_from]', true);
                ?>
            </div>
        </div>
        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <?php
                echo WOOBE_HELPER::draw_calendar('woobe_filter_post_date_to', __('Post date to', 'woo-bulk-editor'), 'post_date_to', '', 'woobe_filter[post_date_to]', true);
                ?>
            </div>
        </div>


        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <input type="number" name="woobe_filter[menu_order_from]" min="0" placeholder="<?php _e('Menu order from', 'woo-bulk-editor') ?>" value="" /><br />
            </div>
        </div>

        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <input type="number" name="woobe_filter[menu_order_to]" min="0" placeholder="<?php _e('Menu order to', 'woo-bulk-editor') ?>" value="" />
            </div>
        </div>


        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <?php
                echo WOOBE_HELPER::draw_select(array(
                    'options' => array('-1' => __('Backorders', 'woo-bulk-editor')) + $fields['backorders']['select_options'],
                    'field' => 'backorders',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[backorders]'
                ));
                ?>
            </div>
        </div>
        
        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <?php
                $users = array();
                if ($users === array()) {
                    $uu = get_users();
                    $users = array();
                    if (!empty($uu)) {
                        foreach ($uu as $u) {
                            $users[$u->data->ID] = $u->data->display_name;
                        }
                    }
                }
                echo WOOBE_HELPER::draw_select(array(
                    'options' => array('-1' => __('By author', 'woo-bulk-editor'))+$users ,//+ $fields['author']['select_options'],
                    'field' => 'post_author',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[post_author]'
                ));
                ?>
            </div>
        </div>
        <div class="col-lg-6" style="position: relative; margin-bottom: 2px;">
            <div style="padding-left: 1px">
                <?php
                $visibility = array(
                    'visible'=>__('Shop and search results', 'woo-bulk-editor'),
                    'shop_only'=>__('Shop only', 'woo-bulk-editor'),
                    'search_only'=>__('Search results only', 'woo-bulk-editor'),
                    'hidden'=>__('Hidden', 'woo-bulk-editor'),
                );

                echo WOOBE_HELPER::draw_select(array(
                    'options' => array('-1' => __('Catalog visibility', 'woo-bulk-editor'))+$visibility  ,
                    'field' => 'product_visibility',
                    'product_id' => 0,
                    'class' => 'woobe_filter_select',
                    'name' => 'woobe_filter[product_visibility]'
                ));
                ?>
            </div>
        </div>

        <?php
        $filter_keys = apply_filters('woobe_filter_other', array());
        if (!empty($filter_keys)) {
            $padding = 'right';
            foreach ($filter_keys as $key => $item) {
                ?>
                <div class="col-lg-6" style="margin-bottom: 2px;">
                    <div style="padding-<?php echo $padding ?>: 1px">
                        <?php
                        echo WOOBE_HELPER::draw_select(array(
                            'options' => array(
                                '' => $item['title'],
                                '1' => __('Yes', 'woo-bulk-editor'), //true
                                'zero' => __('No', 'woo-bulk-editor'), //false
                            ),
                            'field' => $key,
                            'product_id' => 0,
                            'class' => 'woobe_filter_select',
                            'name' => 'woobe_filter[' . $key . ']'
                        ));
                        ?>
                    </div>
                </div>
                <?php
                if ($padding == 'right') {
                    $padding = 'left';
                } else {
                    $padding = 'right';
                }
            }
        }
        ?>
        <div style="clear: both;"></div>
    </div>

    <?php
}
