<?php 



/**
 * Product details plugin.
 *
 * @package   PDT_Aggregator
 * @author    Akbar Doosti
 * @license   GPL-2.0+
 * @link      https://wpx93.ir
 * @copyright CONF_Plugin_Copyright
 */

class PDT_Aggregator {
	
	/**
	 * The instance of this class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      RFM_Aggregator    $instance    The instance of this class.
	 */
	private static $instance;

	/**
	 * The instance of RFM Class 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      RFM    $recency    The instance of RFM Class .
	 */
	private $recency;

	/**
	 * The instance of RFM Class 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      RFM    $recency    The instance of RFM Class 
	 */
	private $frequency;

	/**
	 * The instance of RFM Class 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      RFM    $recency    The instance of RFM Class 
	 */
	private $monetary;

	/**
	 * The Purchase amount index list
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $purchase_amount_index    The Purchase amount index list.
	 */
	private $purchase_amount_index;

	/**
	 * The Purchase number index list
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $purchase_number_index    The Purchase number index list.
	 */
	private $purchase_number_index;

	/**
	 * The MySQL table name of this class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $table_name    The MySQL table name of this class.
	 */
	private $table_name;
	
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->load_dependencies();

		$this->table_name = "rfm_settings";


		$this->purchase_amount_index = array(
			'invoice' 				=> __( 'Average invoice amount', 'rfm' ),
			// 'gross_sale' 			=> __( 'Gross sale', 'rfm' ),
			'net_sales' 			=> __( 'Net sales', 'rfm' ),
			'gross_sales_num' 		=> __( 'Gross sales number', 'rfm' ),
			// 'gross_product_weight' 	=> __( 'Gross product weight', 'rfm' ),
			// 'net_product_weight' 	=> __( 'Net product weight', 'rfm' ),
			'net_invoice_payment' 	=> __( 'Net invoice payment amount', 'rfm' ),
		);

		$this->purchase_number_index = array(
			'invoices' 			=> __( 'Number of invoices', 'rfm' ),
			'good_numbers_avg' 	=> __( 'Average number of goods per invoice', 'rfm' ),
			// 'row_numbers_avg' 	=> __( 'Average number of rows per invoice', 'rfm' ),
			// 'reg_net_numbers' 	=> __( 'Net number of invoices registered', 'rfm' ),
		);

		$this->recency = new RFM( 
			'recency', 
			__( 'Recently bought', 'rfm' ),
			__( 'Customer freshness', 'rfm' ),
			'Recency (R)',
			__( "Day", "rfm" )
		);
		$this->frequency = new RFM( 
			'frequency', 
			__( 'The number of purchases', 'rfm' ),
			__( 'Customer orders', 'rfm' ),
			'Frequency (F)',
			__( "Num", "rfm" )
		);
		
		$currencies    = get_woocommerce_currencies();
        $currency_code = get_woocommerce_currency();
		$this->monetary = new RFM( 
			'monetary', 
			__( 'Purchase amount', 'rfm' ),
			__( 'Customer revenue', 'rfm'),
			'Monetary (M)',
			$currencies[ $currency_code ]
		);
	}

	/**
	 * Return an instance of Plugin_Controller
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
	 * @since 1.0.0
	 * @access   public
	 */ 
	public function activate() {
		$this->create_mysql_table();
	}

	/**
	 * Run when the plugin is deactivated
	 * @since 1.0.0
	 * @access   public
	 */
	public function deactivate() {
		$this->delete_mysql_table();
	}

	/**
	 * Get $analysis_time_period attribute
	 * @since 1.0.0
	 * @return array $this->analysis_time_period.
	 * @access   public
	 */ 
	public function get_analysis_time_period() {
		return $this->analysis_time_period;
	}

	/**
	 * Set $analysis_time_period attribute
	 * @param array $param Analysis time period list
	 * @since 1.0.0
	 * @access   public
	 */ 
	public function set_analysis_time_period( $param ) {
		$this->analysis_time_period = $param;
	}

	/**
	 * Create time period setting MySQL table
	 * @since 1.0.0
	 * @access   private
	 */ 
	private function create_mysql_table() {
		global $wpdb, $table_prefix;

		if ( ! RFM_Databse::is_table_exist( $table_prefix.$this->table_name ) ) {

			$sql  = "CREATE TABLE ".$table_prefix.$this->table_name." (";
			$sql .= 	"ID bigint(20) AUTO_INCREMENT NOT NULL,";
			$sql .=     "purchase_amount_index varchar(20) NOT NULL,";
			$sql .=     "purchase_number_index varchar(20) NOT NULL,";
			$sql .=     "delete_junk_files tinyint(1) NOT NULL,";
			$sql .=     "customer_category varchar(10) NOT NULL,";
			$sql .=     "recency_cat varchar(255) NOT NULL,";
			$sql .=     "frequency_cat varchar(255) NOT NULL,";
			$sql .=     "monetary_cat varchar(255) NOT NULL,";
			$sql .=     "created varchar(10) NOT NULL,";
			$sql .= 	"PRIMARY KEY(ID)";
			$sql .= ");";

		} else {

			$sql = "ALTER TABLE ". $table_prefix.$this->table_name ." ADD purchase_amount_index varchar(20) NOT NULL AFTER id,";
			$sql .= " ADD purchase_number_index varchar(20) NOT NULL  AFTER id,";
			$sql .= " ADD delete_junk_files tinyint(1) NOT NULL  AFTER id,";
			$sql .= " ADD customer_category varchar(10) NOT NULL AFTER id,";
			$sql .= " ADD recency_cat varchar(255) NOT NULL AFTER id,";
			$sql .= " ADD frequency_cat varchar(255) NOT NULL AFTER id,";
			$sql .= " ADD monetary_cat varchar(255) NOT NULL AFTER id,";

		}

		$wpdb->query(
			$wpdb->prepare( $sql )
		);
	}

	/**
	 * Save information to MySQL table
	 * 
	 * @since 1.0.0
	 * @access   public
	 * @param 	array 	$args 
	 */ 
	public function save( $args ) {

		global $wpdb, $table_prefix;
		
		if ( $wpdb->get_var( "SELECT COUNT(*) FROM {$table_prefix}{$this->table_name}" ) > 0 ) {

	    	$result = $wpdb->update( 
		        $table_prefix.$this->table_name, 
		        array(
		            'purchase_amount_index' => $args['purchase_amount_index'],
        			'purchase_number_index' => $args['purchase_number_index'],
        			'delete_junk_files'     => $args['delete_junk_files'],
        			'customer_category'     => $args['customer_category'],
        			'recency_cat'           => $args['recency_cat'],
                    'frequency_cat'         => $args['frequency_cat'],
                    'monetary_cat'          => $args['monetary_cat'],
                    'created'               => time(),
		        ), 
		        array(
		            'ID' => 1
		        )
		    );

		} else {

		    $data = array(
            	'purchase_amount_index' => $args[ 'purchase_amount_index' ],
    			'purchase_number_index' => $args[ 'purchase_number_index' ],
    			'delete_junk_files' 	=> $args[ 'delete_junk_files' ],
    			'customer_category' 	=> $args[ 'customer_category' ],
    			'recency_cat'           => $args[ 'recency_cat' ],
                'frequency_cat'         => $args[ 'frequency_cat' ],
                'monetary_cat'          => $args[ 'monetary_cat' ],
                'created' => time(),
            );
        	
        	try{

        		$result = $wpdb->insert(
    		        $table_prefix . $this->table_name,
    		        $data
    		    );

        	} catch( Exception $ex ) {

        		die( $ex->getMessge() );

        	}

		}

		return $result;
	}

	/**
	 * Get one row of the records
	 * 
	 * @since 1.0.0
	 * @access   public
	 * @return 	array  one row of the records
	 */ 
	public function get_row() {
		
		if ( ! RFM_Cache::get_instance()->get_cache( 'rfm_settings_data' ) ) {
			global $wpdb, $table_prefix;

			$sql = "SELECT `purchase_amount_index`, `purchase_number_index`, `delete_junk_files`,".
				" `customer_category`, `recency_cat`, `frequency_cat`, `monetary_cat`".
				" FROM {$table_prefix}{$this->table_name}";

			$result = $wpdb->get_row(
				$wpdb->prepare( $sql )
			);
			RFM_Cache::get_instance()->set_cache( 'rfm_settings_data', $result, 900 );
		} else {
			$result = RFM_Cache::get_instance()->get_cache( 'rfm_settings_data' );
		}

		return $result;
	} 

	/**
	 * Save interval settings information via AJAX
	 * 
	 * @since 1.0.0
	 * @access   public
	 */
	public function save_rfm_setting() {
        
        check_ajax_referer('rfm_plugin_ajax'); 
        $recency_cat = $_POST[ 'category' ][ 'recency' ];
        $frequency_cat = $_POST[ 'category' ][ 'frequency' ];
        $monetary_cat = $_POST[ 'category' ][ 'monetary' ];
        
        // die(print_r($recency_cat));
        RFM_Aggregator::get_instance()->save([
            'purchase_amount_index' => isset( $_POST['purchase_amount_index'] ) ? $_POST['purchase_amount_index'] : "",
            'purchase_number_index' => isset( $_POST['purchase_number_index'] ) ? $_POST['purchase_number_index'] : "",
            'delete_junk_files' => isset( $_POST['delete_data'] ) && $_POST['delete_data'] == 'on' ? 1 : 0,
            'customer_category' => isset( $_POST['customer_category'] ) ? $_POST['customer_category'] : "",
            'recency_cat' => isset( $recency_cat ) ? json_encode( $recency_cat ) : "",
            'frequency_cat' => isset( $frequency_cat ) ? json_encode( $frequency_cat ) : "",
            'monetary_cat' => isset( $monetary_cat ) ? json_encode( $monetary_cat ) : "",
        ]);

        RFM_Cache::get_instance()->clear_rfm_cache( array(
        	'chart_query',
        	'rfm_setting'
        ));
        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success( $response );

        die();
    }

	/**
	 * Get interval settings information via AJAX
	 * @since 1.0.0
	 * @access   public
	 */
	public function get_rfm_setting() {
        
        check_ajax_referer('rfm_plugin_ajax'); 

        $result = RFM_Aggregator::get_instance()->get_row();

		if ( empty( $result ) ) {
			$response = 'Error';
			wp_send_json_error( $response  );
		}
		
        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success( $result );
        die();
    }

	/**
	 * Delete time period setting MySQL table
	 * @since 1.0.0
	 * @access   public
	 */ 
	public function delete_mysql_table() {
		global $wpdb, $table_prefix;
		

		$drop_sql  = "DROP TABLE IF EXISTS {$table_prefix}{$this->table_name};";
    			
		$wpdb->query(
			$wpdb->prepare( $drop_sql )
		);
	}

	/**
	 * Render HTML tags
	 * @since 1.0.0
	 * @access   public
	 */ 
	public function render_page() {
		require_once( plugin_dir_path( dirname( __FILE__ ) ) . '/partials/rfm-aggregator-render-page.php' );
	}


	public function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/classes/class-rfm.php';

		require_once( plugin_dir_path( dirname( __FILE__ ) ) . '/classes/class-rfm-database.php' );

		require_once( plugin_dir_path( dirname( __FILE__ ) ) . '/classes/class-rfm-cache.php' );

	}
}

 ?>