<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
//echo date('d-m-Y H:i:s',$val);
//global $WOOBE;
?>

<input type="text" onmouseover="woobe_init_calendar(this)" data-title="<?php echo str_replace('"', '', strip_tags($product_title)) ?>" data-val-id="calendar_<?php echo $field_key ?>_<?php echo $product_id ?>" value="<?php if ($val) echo date('d/m/Y', $val) ?>" class="woobe_calendar" placeholder="<?php echo ($print_placeholder ? $product_title : '') ?>" />
<input type="hidden" data-key="<?php echo $field_key ?>" data-product-id="<?php echo $product_id ?>" id="calendar_<?php echo $field_key ?>_<?php echo $product_id ?>" value="<?php echo $val ?>" name="<?php echo $name ?>" />
<a href="javascript: void(0);" class="woobe_calendar_cell_clear"><?php echo __('clear', 'woo-bulk-editor') ?></a>
