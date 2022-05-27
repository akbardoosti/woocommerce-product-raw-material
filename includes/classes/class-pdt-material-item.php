<?php

class PDT_Material_Item {

    private $table_name;
    private static $instance;

    public function __construct( $data = [] ){
        $this->load_dependencies();
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
    
    public function load_dependencies() {
        // Load database class tools
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tools/class-pd-database.php';
    }
}