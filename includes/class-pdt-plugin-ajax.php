<?php
/**
 * CONF Plugin Name.
 *
 * @package   PDT_Plugin_AJAX
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
class PDT_Plugin_AJAX
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
            add_action( 'wp_ajax_save_product_info', array( 'PDT_Material', 'save_product_info'));
            
            add_action( 'wp_ajax_get_all_products', array( 'PDT_Material', 'get_products' ) );

            add_action( 'wp_ajax_get_all_categories', array( 'PDT_Category_Profit', 'get_categories' ) );

            add_action( 'wp_ajax_update_category_profit', array( 'PDT_Category_Profit', 'update' ) );
            add_action( 'wp_ajax_delete_product', array( 'PDT_Material', 'delete' ) );
            
            add_action( 'wp_ajax_update_product', array( 'PDT_Material', 'update' ) );
            
            add_action( 'wp_ajax_get_shipping_price_list', array( 'PDT_Shipping', 'get_price_list' ) );

            add_action( 'wp_ajax_save_shipping_price', array( 'PDT_Shipping', 'save_shipping' ) );
            add_action( 'wp_ajax_woocommerce_products', array( 'PDT_WC_Product', 'get_woocommerce_products' ) );

            add_action( 'wp_ajax_update_woocommerce_product', array( 'PDT_WC_Product', 'update_woocommerce_product' ) );
            add_action( 'wp_ajax_clear_all_shipping_cost', array( 'PDT_Shipping', 'clear_all_shipping_cost' ) );
            add_action( 'wp_ajax_clear_all_wage_cost', array( 'PDT_Shipping', 'clear_all_wage_cost' ) );
            add_action( 'wp_ajax_clear_all_other_cost', array( 'PDT_Shipping', 'clear_all_other_cost' ) );
            add_action( 'wp_ajax_clear_all_material_price', array( 'PDT_Material', 'clear_all_material_price' ) );
            add_action( 'wp_ajax_clear_all_profit', array( 'PDT_Category_Profit', 'clear_all_profit' ) );
            add_action( 'wp_ajax_clear_check_item_price', array( 'PDT_Material', 'check_item_price' ) );
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
        
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "class-pdt-material.php";
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "class-pdt-material-item.php";
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "class-pdt-category-profit.php";
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "class-pdt-shipping.php";
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "class-pdt-wc-prodcut.php";
    
    }
}
