<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RFM_Plugin
 * @subpackage rfm-plugin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    rfm-plugin
 * @subpackage rfm-plugin/includes
 * @author     Akbar Doosti <dousti1371@gmail.com>
 */
class RFM_Plugin_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

        /**
         * This only required if custom post type has rewrite!
         */
        flush_rewrite_rules();

        /**
         * Delete  order list view from Database
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/class-rfm-database.php';
        RFM_Databse::get_instance()->delete_view();

		/**
		 * The class responsible for deactivate all of classes in this plugin.
		 */
        // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rfm-plugin-controller.php';
        // RFM_Plugin_Controller::get_instance()->deactivate();
	}

}
