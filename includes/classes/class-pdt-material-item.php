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

    /**
     * This function delete save information table
     */ 
    public  function delete_save_information_table(){
        global $wpdb, $table_prefix ;
            
        $drop_sql  = "DROP TABLE IF EXISTS {$table_prefix}pd_product_items;";
        
        $result = $wpdb -> query($wpdb->prepare($drop_sql));
        return $result;
    }

    /**
     * This function create save information table
     */ 
    public  function create_save_information_table(){
        global $wpdb, $table_prefix;
        
        
        if( ! PDT_Databse::is_table_exist( $table_prefix . $this->items_table_name ) ) {

            $sql .= "CREATE TABLE " . $table_prefix . $this->items_table_name ." (";
            $sql .=     "ID bigint(20) AUTO_INCREMENT NOT NULL,";
            $sql .=     "post_id bigint(20) NOT NULL,";
            $sql .=     "product_id bigint(20) NOT NULL,";
            $sql .=     "variation_id bigint(20) NOT NULL,";
            $sql .=     "product_number bigint(20) NOT NULL,";
            $sql .=     "PRIMARY KEY(ID)";
            $sql .= ");";
            
            $result = $wpdb -> query($wpdb->prepare($sql));

        }
        
        return $result;
    }

    public function load_dependencies() {
        // Load database class tools
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tools/class-pd-database.php';
    }
}