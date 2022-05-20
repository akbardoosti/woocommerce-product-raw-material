<?php
/**
 * CONF Plugin Name.
 *
 * @package   RFM_Plugin_AJAX
 * @author    Akbar Doosti
 * @license   GPL-2.0+
 * @link      https://www.linkedin.com/in/akbar-doosti/
 * @copyright CONF_Plugin_Copyright
 */

/**
 *-----------------------------------------
 * Do not delete this line
 * Added for security reasons: http://codex.wordpress.org/Theme_Development#Template_Files
 *-----------------------------------------
 */
defined('ABSPATH') or die("Direct access to the script does not allowed");
/*-----------------------------------------*/

/**
 * Handle AJAX calls
 */
class RFM_Plugin_AJAX
{

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Initialize the class
     *
     * @since     1.0.0
     */
    public function __construct() {
        // Load all libraries we need
        $this->load_dependencies();
        
        // Backend AJAX calls
        if ( is_admin() ) {
        // if (current_user_can('manage_options')) {
            add_action('wp_ajax_admin_backend_call', array($this, 'ajax_backend_call'));
            
            add_action( 'wp_ajax_save_time_period_setting', array( 'Time_Period_Setting', 'save_time_period_setting' ) );
            add_action( 'wp_ajax_get_time_period_setting', array( 'Time_Period_Setting', 'get_time_period_setting' ) );

            add_action( 'wp_ajax_save_rfm_setting', array( 'RFM_Aggregator', 'save_rfm_setting' ) );
            add_action( 'wp_ajax_get_rfm_setting', array( 'RFM_Aggregator', 'get_rfm_setting' ) );
            
            add_action( 'wp_ajax_get_chart_data', array( 'RFM', 'get_chart_data' ) );
            
            add_action( 'wp_ajax_get_rfm_values', array( 'RFM', 'get_rfm_values' ) );

            add_action( 'wp_ajax_save_basic_analysis_setting', array( 'RFM_Basic_Analysis_Settings_Aggregator', 'save_basic_analysis_setting' ) );
            add_action( 'wp_ajax_get_basic_analysis_setting', array( 'RFM_Basic_Analysis_Settings_Aggregator', 'get_basic_analysis_setting' ) );
        }


        // Frontend AJAX calls
        add_action('wp_ajax_admin_frontend_call', array($this, 'ajax_frontend_call'));
        add_action('wp_ajax_nopriv_frontend_call', array($this, 'ajax_frontend_call'));

    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Handle AJAX: Backend Example
     *
     * @since    1.0.0
     */
    public function ajax_backend_call()
    {
        die('fd');
        // Security check
        check_ajax_referer('referer_id', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);

        die();
    }

    /**
     * Handle AJAX: Frontend Example
     *
     * @since    1.0.0
     */
    public function ajax_frontend_call()
    {

        // Security check
        check_ajax_referer('referer_id', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);

        die();
    }

    /**
     * Load all libraries we need
     *
     * @since    1.0.0
     */
    public function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-time-period-setting.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-rfm-aggregator.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-rfm.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-rfm-basic-analysis-settings-aggregator.php';
    }
}
