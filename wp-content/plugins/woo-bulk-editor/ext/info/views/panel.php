<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOBE;
?>
<h4><?php _e('Help', 'woo-bulk-editor') ?></h4>


<b>
    <?php
    printf(__('The plugin has %s and "%s" list. Also you can watch %s to understand all features of the plugin.', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                'href' => 'https://bulk-editor.com/documentation/',
                'title' => __('documentation', 'woo-bulk-editor'),
                'target' => '_blank'
            )), WOOBE_HELPER::draw_link(array(
                'href' => 'https://bulk-editor.com/how-to-list/',
                'title' => __('How to', 'woo-bulk-editor'),
                'target' => '_blank'
            )), WOOBE_HELPER::draw_link(array(
                'href' => 'https://bulk-editor.com/video-tutorials/',
                'title' => __('videos', 'woo-bulk-editor'),
                'target' => '_blank'
    )));
    ?>&nbsp;
    <?php if ($WOOBE->show_notes) : ?>
        <div style="height: 9px;"></div>
        <span style="color: red;"><?php
            printf(__('Current version of the plugin is FREE. See the difference between FREE and PREMIUM versions %s', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                        'href' => 'https://bulk-editor.com/downloads/',
                        'title' => __('here', 'woo-bulk-editor'),
                        'target' => '_blank'
            )));
            ?></span>
    <?php endif; ?>
</b>

<hr />

<h4><?php _e('Some little hints', 'woo-bulk-editor') ?>:</h4>

<ol>
    <li><?php _e('If to click on an empty space of the black wp-admin bar, it will get you back to the top of the page', 'woo-bulk-editor') ?></li>


    <li><?php
        printf(__('Can I %s?', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                    'href' => 'https://bulk-editor.com/howto/can-i-select-products-and-add-15-to-their-regular-price/',
                    'title' => __('select products and add 15% to their regular price', 'woo-bulk-editor'),
                    'target' => '_blank'
        )))
        ?>
    </li>

    <li><?php
        printf(__('If your shop is on the Russian language you should install %s for the correct working of WOOBE with Cyrillic', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                    'href' => 'https://ru.wordpress.org/plugins/cyr3lat/',
                    'title' => __('this plugin', 'woo-bulk-editor'),
                    'target' => '_blank'
        )))
        ?>
    </li>


    <li><?php
        printf(__('How to set the same value for some products on the same time - %s', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                    'href' => 'https://bulk-editor.com/howto/how-to-set-the-same-value-for-some-products-on-the-same-time/',
                    'title' => __('binded editing', 'woo-bulk-editor'),
                    'target' => '_blank'
        )))
        ?>
    </li>

    <li>
        <?php _e('Remember! "Sale price" can not be greater than "Regular price", never! So if "Regular price" is 0 - not possible to set "Sale price"!', 'woo-bulk-editor') ?>
    </li>

    <li>
        <?php _e('Search by products slugs, which are in non-latin symbols does not work because in the data base they are keeps in the encoded format!', 'woo-bulk-editor') ?>
    </li>


    <li>
        <?php _e('Click ID of the product in the products table to see it on the site front.', 'woo-bulk-editor') ?>
    </li>


    <li>
        <?php _e('Use Enter keyboard button in the Products Editor for moving to the next products row cell with saving of changes while edit textinputs. Use arrow Up or arrow Down keyboard buttons in the Products Editor for moving to the next/previous products row without saving of changes.', 'woo-bulk-editor') ?>
    </li>

    <li>
        <?php _e('To select range of products using checkboxes - select first product, press SHIFT key on your PC keyboard and click last product.', 'woo-bulk-editor') ?>
    </li>

    <li><?php
        printf(__('If you have any ideas, you can suggest them on %s', 'woo-bulk-editor'), WOOBE_HELPER::draw_link(array(
                    'href' => 'https://bulk-editor.com/forum/',
                    'title' => __('the plugin forum', 'woo-bulk-editor'),
                    'target' => '_blank'
        )))
        ?>
    </li>
</ol>


<hr />

<h4><?php _e('Requirements', 'woo-bulk-editor') ?>:</h4>
<ul>
    <li><?php _e('Recommended RAM', 'woo-bulk-editor') ?>: 256 MB</li>
    <li><?php _e('Minimal PHP version is', 'woo-bulk-editor') ?>: PHP 5.4</li>
    <li><?php _e('Recommended PHP version', 'woo-bulk-editor') ?>: 7.xx</li>
</ul>

<hr />
<h4><?php _e('Some useful plugins for your e-shop', 'woo-bulk-editor') ?></h4>


<div class="col-lg-12">
    <a href="https://products-filter.com/" title="WOOF - WooCommerce Products Filter" target="_blank">
        <img width="150" src="<?php echo WOOBE_LINK ?>assets/images/woof_banner.png" alt="WOOF - WooCommerce Products Filter" />
    </a>

    <a href="https://currency-switcher.com/" title="WOOCS - WooCommerce Currency Switcher" target="_blank">
        <img width="150" src="<?php echo WOOBE_LINK ?>assets/images/woocs_banner.png" alt="WOOCS - WooCommerce Currency Switcher" />
    </a>

    <!-- <a href="https://alidropship.com/plugin/?via=4352" title="AliDropship is the best solution for drop shipping" target="_blank">
        <img width="150" src="<?php echo WOOBE_LINK ?>assets/images/drop-ship.jpg" alt="AliDropship is the best solution for drop shipping" />
    </a> -->
</div>

<div style="clear: both;"></div>


