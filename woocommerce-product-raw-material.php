<?php
/**
 * 
 * @package           PDT_Plugin
 * @author            Akbar Doosti
 * @copyright         2022 VIP Shop flowers
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce product raw material
 * Plugin URI:        https://wordpress.org/plugins/tags/repository/
 * Description:       This plugin designed for add raw material to Woocommerce products
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Akbar Doosti
 * Author URI:        https://wpx93.ir
 * Text Domain:       pdt-raw-material
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 * 
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PDT_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pdt-plugin-activator.php
 */
function activate_pdt_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdt-plugin-activator.php';
	PDT_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pdt-plugin-deactivator.php
 */
function deactivate_pdt_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdt-plugin-deactivator.php';
	PDT_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pdt_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_pdt_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pdt-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pdt_plugin() {

	$plugin = new PDT_Plugin();
	$plugin->run();

}
run_pdt_plugin();
