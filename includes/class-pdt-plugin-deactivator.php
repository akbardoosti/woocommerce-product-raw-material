<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    PDT_Plugin
 * @subpackage pdt-plugin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    pdt-plugin
 * @subpackage pdt-plugin/includes
 * @author     Akbar Doosti <dousti1371@gmail.com>
 */
class PDT_Plugin_Deactivator {

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
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/class-pdt-database.php';
        PDT_Databse::get_instance()->delete_view();

		/**
		 * The class responsible for deactivate all of classes in this plugin.
		 */
        // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdt-plugin-controller.php';
        // PDT_Plugin_Controller::get_instance()->deactivate();
	}

}
