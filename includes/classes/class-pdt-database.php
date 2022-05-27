<?php 
	
/**
 * 
 */
class PDT_Databse {
	/**
	 * The instance of this class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      RFM_Databse    $instance    The instance of this class.
	 */
	private static $instance;

	private $view_name;

	function __construct()
	{
		$this->view_name = "vw_rfm_order_list9046";
		// code...
	}

	/**
	 * Return an instance of RFM_Databse
	 * @since 1.0.0
	 * @access   public
	 */ 
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
        }
        return self::$instance;	
	}

	public function get_view_name() {
		return $this->view_name;
	}

	public function set_view_name( $param ) {
		$this->view_name = $param;
	}

	/**
	 * Returns true if table exists in DataBase
	 * @since 1.0.0
	 */
	public static function is_table_exist( $table ) {

		global $wpdb, $table_prefix;

		$database = DB_NAME;
		$sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$database'".
		" AND table_name = '$table' LIMIT 1";

		$result = $wpdb->get_var(
			$wpdb->prepare( $sql )
		);
		
		return $result > 0;
	}

	/**
	 * Returns true if procedure exists in DataBase
	 * @since 1.0.0
	 */
	public static function is_proc_exist( $procedure ) {
		global $wpdb, $table_prefix;

		$database = DB_NAME;
		$sql = "SELECT COUNT(*) FROM information_schema.routines WHERE ROUTINE_SCHEMA = '$database'".
			" AND ROUTINE_NAME = '$procedure'";

		$result = $wpdb->get_var(
			$wpdb->prepare( $sql )
		);
		
		return $result > 0;
	}

	/**
	 * Returns true if procedure exists in DataBase
	 * @since 1.0.0
	 */
	public static function is_trigger_exist( $trigger ) {
		global $wpdb, $table_prefix;

		$database = DB_NAME;
		$sql = "SELECT * FROM information_schema.`TRIGGERS` WHERE `TRIGGER_SCHEMA` = '$database' AND `TRIGGER_NAME` = '$trigger'";

		$result = $wpdb->get_var(
			$wpdb->prepare( $sql )
		);
		
		return $result > 0;
	}

	public function create_view() {
		global $wpdb, $table_prefix;
		$view_name =  $this->view_name;
		// wp_die($view_name);

		
	}

	public function delete_view() {
		global $wpdb, $table_prefix;
		$view_name =  $this->view_name;
		return $wpdb->query(
			$wpdb->prepare( "DROP VIEW IF EXISTS $view_name;" )
		);
	}
}
?>