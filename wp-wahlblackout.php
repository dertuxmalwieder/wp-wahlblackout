<?php
/*
Plugin Name: WP-WahlBlackout
Plugin URI: https://tuxproject.de/projects/wp-wahlblackout
Description: Schaltet das Blog am Tag der nächsten Wahl von 8 bis 18 Uhr ab; ein Widget mit dem Countdown (in Tagen) ist enthalten.
Version: 20191001
Author: tux.
Author URI: https://tuxproject.de/blog
License: WTFPL

Yep, versioning scheme is yyyymm##, no reference to a day.
Counting builds makes more sense to me.
*/

// ------------------------
// General SQL field defaults
add_option('wahlblackout_verfehlung','Merkel');
add_option('wahlblackout_wahltyp','Bundestagswahl');
add_option('wahlblackout_regierung','Bundesregierung');
add_option('wahlblackout_datum','24.09.2017');
add_option('wahlblackout_datum_rein',strtotime('24-09-2017 08:00 AM'));
add_option('wahlblackout_datum_raus',strtotime('24-09-2017 06:00 PM'));

// ------------------------
// Shortcode
function wp_wahlblackout_shortcode() {
    return ($wahlcountdown > 1 ? "In ".$wahlcountdown." Tagen" : "Morgen") . " habt ihr die Möglichkeit, die " . get_option("wahlblackout_regierung") . " wegen " . get_option("wahlblackout_verfehlung") . " abzuwählen.";
}

// ------------------------
// Set up the ACP
function wp_wahlblackout_admin() {  
    include('wp-wahlblackout-admin.php');  
}  
  
function wp_wahlblackout_admin_action() {
    add_options_page("WP-WahlBlackout", "WP-WahlBlackout", 'manage_options', plugin_basename(__FILE__), "wp_wahlblackout_admin");  
}

// ------------------------
// Sidebar widget
class WahlBlackout_Widget extends WP_Widget 
{
    public function __construct() 
    {
        parent::__construct(
            'wp_wahlblackout_widget',
            'WP-WahlBlackout-Widget',
            array(
                'description' => 'Zeigt die verbleibenden Tage bis zur nächsten Wahl an.'
            )
        );
    }

    public function form($instance) 
    {
        $defaults = array(
            'title' => ''
        );
        $instance = wp_parse_args((array)$instance, $defaults);

        $title = $instance['title'];
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Titel:'; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
<?php
    }

    public function update($new_instance, $old_instance) 
    {
        $instance = array();
        
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    public function widget($args, $instance) 
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        echo $before_widget;
        
        if (!empty($title))
        {
            echo $before_title . $title . $after_title;
        }

        $wahldatum = date_create(get_option("wahlblackout_datum"));
        $heute = date("d.m.Y");

        $wahlcountdown = date_diff($heute, $wahldatum)->format("%a");

        echo wp_wahlblackout_shortcode();
        
        echo $after_widget;
    }
}

function wp_wahlblackout_widget_register() {
    register_widget('WahlBlackout_Widget');
}

// ------------------------
// Add meta links
function wp_wahlblackout_plugin_actions( $links, $file ) {
    static $plugin;
    if (!$plugin) $plugin = plugin_basename(__FILE__);
 
    // create additional plug-in row
    if ($file == $plugin) {
        $links[] = '<strong><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=der_tuxman%40arcor%2ede&item_name=Donation%20for%20the%20WahlBlackout%20plugin&no_shipping=1&return=http%3a%2f%2ftuxproject%2ede%2fthx4donation%2f&cn=Note%20for%20me&tax=0&currency_code=EUR&bn=PP%2dDonationsBF&charset=UTF%2d8">Spenden</a></strong>';
    }
    return $links;
}
global $wp_version;
add_filter((version_compare($wp_version, '2.8alpha', '>') ? 'plugin_row_meta' : 'plugin_action_links'), 'wp_wahlblackout_plugin_actions', 10, 2);

// ------------------------
// Caching help:
function wp_wahlblackout_clearcache() {
    // Super Cache Plugin
    if ( function_exists( 'wp_cache_clear_cache' ) ) {
        wp_cache_clear_cache();
    }

    // Hyper Cache Plugin
    if ( function_exists( 'hyper_clean' ) ) {
        hyper_clean();
    }

    // W3 Total Cache Plugin
    if ( function_exists( 'w3tc_pgcache_flush' ) ) {
        w3tc_pgcache_flush();
    }
}

// ------------------------
// Do something useful:
add_action('admin_menu', 'wp_wahlblackout_admin_action'); // add our admin page
add_action('widgets_init', 'wp_wahlblackout_widget_register'); // add our widget
add_shortcode("wahlblackout", "wp_wahlblackout_shortcode"); // add our shortcode

// ------------------------
// WP blog replacements:
function wp_wahlblackout_init() {
    if (current_time('timestamp') >= get_option('wahlblackout_datum_rein') && current_time('timestamp') <= get_option('wahlblackout_datum_raus')) {
        if (!get_option('wahlblackout_cache_leer')) {
            wp_wahlblackout_clearcache();
            add_option('wahlblackout_cache_leer','1');
        }

        if (is_admin()) return;

        ob_start();

        // Own blackout.php? Else use ours :-)
        if ( file_exists( WP_CONTENT_DIR . '/blackout.php' ) )
            include( WP_CONTENT_DIR . '/blackout.php' );
        else
            include('blackout.php');

        ob_flush();
        exit();
    }
    elseif (current_time('timestamp') >= get_option('wahlblackout_datum_raus')) {
        wp_wahlblackout_clearcache();
        delete_option('wahlblackout_cache_leer');
    }
}

add_action('init', 'wp_wahlblackout_init');
?>