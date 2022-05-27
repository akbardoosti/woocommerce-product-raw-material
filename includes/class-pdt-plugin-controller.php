<?php


/**
 * PDT_Plugin.
 *
 * @package   PDT_Plugin_Controller
 * @author    Akbar Doosti
 * @license   GPL-2.0+
 * @link      https://www.linkedin.com/in/akbar-doosti/
 * @copyright CONF_Plugin_Copyright
 */

class PDT_Plugin_Controller {
	
	/**
	 * The instance of this class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      PDT_Plugin_Controller    $instance    The instance of this class.
	 */
	private static $instance;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// 
		$this->load_dependencies();

	}

	/**
	 * Return an instance of PDT_Plugin_Controller
	 * @since 1.0.0
	 * @access   public
	 */ 
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
        }
        return self::$instance;	
	}

	/**
	 * Run when the plugin is activated
	 * 
	 * @since 1.0.0
	 * @access   public
	 */ 
	public function activate() {
		Time_Period_Setting::get_instance()->activate();
		PDT_Aggregator::get_instance()->activate();
		PDT_Basic_Analysis_Settings_Aggregator::get_instance()->activate();
	}

	/**
	 * Run when the plugin is deactivated
	 * @since 1.0.0
	 * @access   public
	 */
	public function deactivate() {
		Time_Period_Setting::get_instance()->deactivate();
		PDT_Aggregator::get_instance()->deactivate();
		PDT_Basic_Analysis_Settings_Aggregator::get_instance()->deactivate();
		
	}

	

	/**
	 * Create shortcode for this Class
	 * @since 1.0.0
	 * @access   public
	 */ 
	public function shortcode() {

	}

	/**
	 * Renders HTML tags
	 * @since 1.0.0
	 * @access   public
	 */ 
	public function render_page() {
		
		Time_Period_Setting::get_instance()->render_page();

		PDT_Basic_Analysis_Settings_Aggregator::get_instance()->render_page();

		PDT_Aggregator::get_instance()->render_page();
	
	}

	/**
	 * Load dependencies of this class
	 * @since 1.0.0
	 * @access   private
	 */ 
	private function load_dependencies() {
		require_once( 'classes/class-time-period-setting.php' );
		require_once( 'classes/class-rfm-aggregator.php' );
		require_once( 'classes/class-rfm-basic-analysis-settings-aggregator.php' );
	}
}