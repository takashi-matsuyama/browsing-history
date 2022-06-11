<?php
/**
 * Plugin Name: Browsing History
 * Plugin URI: https://wordpress.org/plugins/browsing-history
 * Description: Save user’s browsing history and list them.
 * Version: 1.3.1
 * Requires at least: 4.8
 * Requires PHP: 5.4.0
 * Author: Takashi Matsuyama
 * Author URI: https://profiles.wordpress.org/takashimatsuyama/
 * Text Domain: browsing-history
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

$this_plugin_info = get_file_data( __FILE__, array(
  'name' => 'Plugin Name',
  'version' => 'Version',
  'text_domain' => 'Text Domain',
  'minimum_php' => 'Requires PHP',
));

define( 'CCCBROWSINGHISTORY_PLUGIN_PATH', rtrim( plugin_dir_path( __FILE__ ), '/') );
define( 'CCCBROWSINGHISTORY_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/') );
define( 'CCCBROWSINGHISTORY_PLUGIN_SLUG', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
define( 'CCCBROWSINGHISTORY_PLUGIN_NAME', $this_plugin_info['name'] );
define( 'CCCBROWSINGHISTORY_PLUGIN_VERSION', $this_plugin_info['version'] );
define( 'CCCBROWSINGHISTORY_TEXT_DOMAIN', $this_plugin_info['text_domain'] );

load_plugin_textdomain( CCCBROWSINGHISTORY_TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages' );

/*** Require PHP Version Check ***/
if ( version_compare(phpversion(), $this_plugin_info['minimum_php'], '<') ) {
  $plugin_notice = sprintf( __('Oops, this plugin will soon require PHP %s or higher.', CCCBROWSINGHISTORY_TEXT_DOMAIN), $this_plugin_info['minimum_php'] );
  register_activation_hook(__FILE__, create_function('', "deactivate_plugins('".plugin_basename( __FILE__ )."'); wp_die('{$plugin_notice}');"));
}

if( ! class_exists( 'CCC_Browsing_History' ) ) {
  require( CCCBROWSINGHISTORY_PLUGIN_PATH.'/function.php' );
  /****** CCC_Browsing_History Initialize ******/
  function ccc_browsing_history_initialize() {
    global $ccc_browsing_history;
    /* Instantiate only once. */
    if( ! isset($ccc_browsing_history) ) {
      $ccc_browsing_history = new CCC_Browsing_History();
    }
    return $ccc_browsing_history;
  }
  /*** Instantiate ****/
  ccc_browsing_history_initialize();

  /*** How to use this Shortcode ***/
  /*
  * [ccc_browsing_history_list_results title="string" posts_per_page="int" class="string" style="string" post_type="string (string, string, string)"]
  */
  require( CCCBROWSINGHISTORY_PLUGIN_PATH .'/assets/shortcode-list.php' );

  /****** Uninstall ******/
  require( CCCBROWSINGHISTORY_PLUGIN_PATH .'/assets/uninstall.php' );
  register_uninstall_hook( __FILE__, array('CCC_Browsing_History_Uninstall', 'delete_usermeta') );
} else {
  $plugin_notice = __('Oops, PHP Class Name Conflict.', CCCBROWSINGHISTORY_TEXT_DOMAIN);
  wp_die($plugin_notice);
}
