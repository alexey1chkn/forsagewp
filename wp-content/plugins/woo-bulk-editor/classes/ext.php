<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//https://bulk-editor.com/document/creating-an-extension/
abstract class WOOBE_EXT {

    protected $slug;
    protected $is = 'internal'; //external
    public $storage = NULL;
    public $profiles = NULL;
    public $settings = NULL;
    public $products = NULL;

    abstract public function __construct();

    public function get_ext_path() {
        return plugin_dir_path((new ReflectionClass(get_class($this)))->getFileName());
    }

    public function get_ext_link() {
        if ($this->is === 'internal') {
            return plugin_dir_url((new ReflectionClass(get_class($this)))->getFileName());
        } else {
            //external
            return '/' . basename(WP_CONTENT_DIR) . '/woobe_ext/' . $this->slug . '/';
        }
    }

    //we do it in index.php to allow ext hooks works everywhere
    public function init_vars(&$storage, &$profiles, &$settings, &$products) {
        $this->storage = $storage;
        $this->profiles = $profiles;
        $this->settings = $settings;
        $this->products = $products;
    }

    //generate tab
    public function add_tab($slug, $place, $label) {
        //woobe_ext_top_panel_tab or woobe_ext_panel_tab
        add_action('woobe_ext_' . $place . '_tabs', function() use($slug, $place, $label) {
            ?>
            <li>
                <a href="#tabs-<?php echo $slug ?>" onclick="return woobe_init_js_intab('tabs-<?php echo $slug ?>')">
                    <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                    <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                    <span><?php echo $label ?></span>
                </a>
            </li>
            <?php
        }, 1);

        //***

        add_action('woobe_ext_' . $place . '_tabs_content', function() use($slug, $place) {
            ?>
            <section id="tabs-<?php echo $slug ?>"><?php do_action('woobe_ext_' . $place . '_' . $slug); //including extensions views                    ?></section>
            <?php
        }, 1);
    }

}
