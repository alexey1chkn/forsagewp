<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<br />
<!-- <div class="woobe_gallery_popup_operations">
    <div class="col-lg-9">
        <input type="checkbox" value="0" id="woobe_remove_gall_attach" />&nbsp;<label for="woobe_remove_gall_attach"><?php _e('Remove images also from medialibrary if detached of the product', 'woo-bulk-editor') ?></label>
    </div>
    <div class="col-lg-3" style="text-align: right;">
        <a href="#" class="button button-primary woobe_gall_file_delete_all" title="<?php _e('Remove all images', 'woo-bulk-editor') ?>"><?php _e('Clean', 'woo-bulk-editor') ?></a>
    </div>
    <div style="clear: both;"></div>
</div> -->


<ul class="woobe_fields_tmp">

    <?php if (!empty($images)): ?>
        <?php
        foreach ($images as $attachment_id) :
            $img = wp_get_attachment_image_src($attachment_id);
            ?>
            <li>
                <img src="<?php echo $img[0] ?>" alt="" class="woobe_gal_img_block" />
                <a href="#" class="woobe_gall_file_delete" title="<?php _e('Detach image of the product', 'woo-bulk-editor') ?>"><img src="<?php echo WOOBE_ASSETS_LINK . 'images/delete2.png' ?>" alt="" /></a>
                <input type="hidden" name="woobe_gallery_images[]" value="<?php echo intval($attachment_id); ?>" />
            </li>
        <?php endforeach; ?>
    <?php endif; ?>

</ul>


