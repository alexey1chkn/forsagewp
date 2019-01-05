<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOBE;
?>
<h4 style="margin-bottom: 2px;"><?php _e('History', 'woo-bulk-editor') ?> - <a href="https://bulk-editor.com/document/history/" style="height: 21px; line-height: normal;" target="_blank" class="button button-primary"><span class="icon-book"></span>&nbsp;<?php _e('Documentation', 'woo-bulk-editor') ?></a></h4>
<small style="font-style: italic;"><?php _e('Works for edit-operations and not work with delete-operations! Also does not work with all operations which are presented in "Variations Advanced Bulk Operations"', 'woo-bulk-editor') ?></small><br />

<?php if ($WOOBE->show_notes) : ?>
    <span style="color: red;"><?php _e('In FREE version of the plugin it is possible to roll back 2 last operations.', 'woo-bulk-editor') ?></span><br />
<?php endif; ?>

<br />
<div class="col-lg-6">
    <select style="width: 250px;" id="woobe_history_show_types">
        <option value="0"><?php _e('all', 'woo-bulk-editor') ?></option>
        <option value="1"><?php _e('solo operations', 'woo-bulk-editor') ?></option>
        <option value="2"><?php _e('bulk operations', 'woo-bulk-editor') ?></option>
    </select>
</div>
<div class="col-lg-6" style="text-align: right;">
    <a href="javascript: woobe_history_clear();void(0);" class="page-title-action"><?php _e('Clear the History', 'woo-bulk-editor') ?></a>
</div>
<div style="clear: both;"></div>

<div id="woobe_history_list_container"></div>

