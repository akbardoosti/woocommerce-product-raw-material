<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    rfm-plugin
 * @subpackage rfm-plugin/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    rfm-plugin
 * @subpackage rfm-plugin/includes
 * @author     Akbar Doosti <dousti1371@gmail.com>
 */
class RFM_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		require_once( plugin_dir_path( dirname( __FILE__ ) ) . '/includes/classes/class-time-period-setting.php' );
		require_once( plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-rfm-plugin-controller.php' );
		require_once( plugin_dir_path( dirname( __FILE__ ) ) . '/includes/classes/class-rfm-basic-analysis-settings-aggregator.php' );

		// die(var_dump(class_exists('RFM_Basic_Analysis_Settings')));
		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		if( strpos( $_SERVER['REQUEST_URI'], $this->plugin_name ) ) {
		// die(plugin_dir_url( dirname(__FILE__) ));

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rfm-plugin-admin.css', array(), $this->version, 'all' );

			wp_enqueue_style( 'rfm-style-css', plugin_dir_url( __FILE__ ) . '/css/style.css' );
	        wp_enqueue_style( 'iranyekan', plugin_dir_url( __FILE__ ) . '/css/iranyekan.css' );
	        
			wp_enqueue_style( 'rfm-multi-select-css', plugin_dir_url( __FILE__ ) . '/css/multi-select.css' );

	        wp_enqueue_style( 'rfm_kendo_common_min_css', plugin_dir_url( dirname(__FILE__) ) . '/public/kendo/kendo.common.min.css' );
	        wp_enqueue_style( 'rfm_kendo_default_mobile_min_css', plugin_dir_url( dirname(__FILE__) ) . '/public/kendo/kendo.default.mobile.min.css' );
	        wp_enqueue_style( 'rfm_kendo_rtl_min_css', plugin_dir_url( dirname(__FILE__) ) .'/public/kendo/kendo.rtl.min.css' );

	        /*
	 $args = array(
                'tax_query'     => array(
                    array(
                        'taxonomy'  => 'product_cat', // required
                        'terms'     => array( '257', '2283', '1473', '1527')
                    ),
                ),
                'post_type'     => 'product',
                'posts_per_page' => '-1'
            );
            // $query = new WP_Query( array( 'cat' => array(  257, '2283', '1473', '1527' ) ) );
            $query = new WP_Query( array( 'cat' => '257,2283,1473,1527' ) );
            // $query = new WP_Query( $args );
            // while ( $query->have_posts() ) {
            //     $query->the_post();
            //     echo '<li>' . get_the_title() . '</li>';
            // }
            
            die(var_dump($query->found_posts));
	        */

	    }

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if( strpos( $_SERVER['REQUEST_URI'], $this->plugin_name ) ) {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rfm-plugin-admin.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( 'rfm-multi-select-min', plugin_dir_url( __FILE__ ) . '/js/multi-select.js' );
			wp_enqueue_script( 'rfm-chart', plugin_dir_url( __FILE__ ) . '/js/chart.js' );
	        wp_enqueue_script( 'rfm-underscore', plugin_dir_url( dirname(__FILE__) ) .'/public/js/underscore-umd-min.js' );

	        wp_enqueue_script( 'rfm-plugin-common-js', plugin_dir_url( __FILE__ ) . '/js/rfm-plugin-common.js' );
	        
	        wp_enqueue_script( 'rfm-plugin-src-js', plugin_dir_url( __FILE__ ) . '/js/src.js' );

	        wp_localize_script( 'rfm-plugin-src-js', 'translated_data', array(
	            'custom_course_input_error' => __( 'The number entered can not be less than 90', 'rfm' ),
	            'save_time_period_setting_success' => __( 'Interval settings saved successfully', 'rfm' ),
	            'save_rfm_setting_success' => __( 'Customer segmentation (RFM) settings saved successfully', 'rfm' ),
	            'save_basic_analysis_setting_success' => __( 'Baseline analysis settings saved successfully', 'rfm' ),
	            'error_message' => __( 'There was a problem with storage', 'rfm' ),
	        ) );
	        
	        wp_enqueue_script( 'rfm-plugin-cahrt-data-js', plugin_dir_url( __FILE__ ) . '/js/rfm-plugin-cahrt-data.js' );
	        wp_localize_script( 'rfm-plugin-src-js', 'chart_ajax_obj', array(
	            'ajax_url' => admin_url( 'admin-ajax.php' ),
				'rec_nonce' => wp_create_nonce( 'recency_chart' ),
				'freq_nonce' => wp_create_nonce( 'frequency_chart' ),
				'monetary_nonce' => wp_create_nonce( 'monetary_chart' ),
            ) );
            
            wp_localize_script( 'rfm-plugin-src-js', 'chart_message', array(
	            'recency' => __( "Recency chart" , 'rfm' ),
				'frequency' => __( "Frequency chart" , 'rfm' ),
				'monetary' => __( "Monetary chart" , 'rfm' ),
            ) );
            
            wp_localize_script( 'rfm-plugin-src-js', 'validate_message', array(
	            'blank_message' => __( "Fill in the previous field" , 'rfm' ),
				'compare_message' => __( "Enter a larger amount" , 'rfm' ),
            ) );
            
	        wp_localize_script('rfm-plugin-src-js', 'my_ajax_obj', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'rfm_plugin_ajax' ),
	        ) );

	        wp_enqueue_script( 'rfm_kendo_all_js', plugin_dir_url( dirname(__FILE__) ) .'/public/kendo/kendo.all.min.js' );
	        wp_enqueue_script( 'rfm_kendo_messages_fa_IR_js', plugin_dir_url( dirname(__FILE__) ) .'/public/kendo/kendo.messages.fa-IR.min.js' );

	        wp_enqueue_script( 'rfm-basic-analysis-setting_js', plugin_dir_url( __FILE__ ) . '/js/rfm-basic-analysis-setting.js' );

	        wp_localize_script( 'rfm-basic-analysis-setting_js', 'analysis_setting_message', array(
				'placeHolder' => __( "Choose one of the options" , 'rfm' ),
				'noResult' 	  => __( "No results found in this list.", 'rfm' ),
				"noData" 	  => __( "No item", "rfm" ),
	        ) );
	    }
	}

	/**
	 * Add This plugin to admin menu
	 * @since 1.0.0
	 */
	public function admin_menu_page() {

		add_menu_page(
	        __( 'Data analysis', 'rfm' ),//Page title, Product details
	        __( 'Data analysis', 'rfm' ),//Menu title, Product details
	        'manage_options',
	        __FILE__,
	        array( 'RFM_Plugin_Controller', 'render_page' ),
	        'dashicons-chart-line',
	        20
	    );
	    add_submenu_page( 
			__FILE__, 
			__( 'Time period settings', 'rfm' ), 
			__( 'Time period settings', 'rfm' ), 
			'manage_options', 
			__FILE__.'/time-period-setting', 
			array( 'Time_Period_Setting', 'render_page')
		);

		add_submenu_page( 
			__FILE__, 
			__( 'Basic analysis settings', 'rfm' ), 
			__( 'Basic analysis settings', 'rfm' ), 
			'manage_options', 
			__FILE__.'/basic-analysis-setting', 
			array( 'RFM_Basic_Analysis_Settings_Aggregator', 'render_page')
		);
	}
}
