<?php

class PDT_Material_Item {

    private $table_name;
    private static $instance;

    public function __construct( $data = [] ){
        $this->load_dependencies();
    }

    
    public function load_dependencies() {
        // Load database class tools
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tools/class-pd-database.php';
    }
}