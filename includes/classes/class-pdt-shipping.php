<?php
    
class PDT_Shipping {
    
    private static $instance;
    private $table_name;
    private $shipping_cost;
    private $other_costs;
    private $wage_cost;
    private $product_id;
		
    public function __construct($args=[]) {
        // add_action('')
        
        $this->table_name = 'pd_cost_of_sales';
        
        $this->shipping_cost = isset( $args[ 'shipping_cost' ] ) ? $args[ 'shipping_cost' ] : "";
        $this->other_costs = isset( $args[ 'other_costs' ] ) ? $args[ 'other_costs' ] : "";
        $this->wage_cost = isset( $args[ 'wage_cost' ] ) ? $args[ 'wage_cost' ] : "";
        $this->product_id = isset( $args[ 'product_id' ] ) ? $args[ 'product_id' ] : "";

        $this->load_dependencies();
    }
    
    public static function get_instance() {
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function setup_actions() {
        // 
        //Main plugin hooks
        register_activation_hook( DIR_PATH, array( $this, 'activate' ) );
        register_deactivation_hook( DIR_PATH, array( $this, 'deactivate' ) );
        
    }
    public function activate() {
        
        $this->create_table();
    }
    
    public function deactivate() {
        $this->delete_table();
    }
    
    public function uninstall_plugin() {
        
    }
        
    public function create_table() {
        global $wpdb, $table_prefix;
        $result = true;
        if( ! PDT_Databse::is_table_exist( $table_prefix . $this->table_name ) ) {
            
            $sql .= "CREATE TABLE ".$table_prefix.$this->table_name." (";
            $sql .= 	"ID bigint(20) AUTO_INCREMENT NOT NULL,";
            $sql .=     "product_id bigint(20) NOT NULL,";
            $sql .=     "shipping_cost varchar(20) NOT NULL,";
            $sql .=     "other_costs varchar(20) NOT NULL,";
            $sql .=     "wage_cost varchar(20) NOT NULL,";
            $sql .=     "created varchar(10) NOT NULL,";
            $sql .= 	"PRIMARY KEY(ID),";
            $sql .= 	"CONSTRAINT product_id UNIQUE (product_id)";
            $sql .= ");";
            
            $result = $wpdb->query( $wpdb->prepare( $sql ) );

        }
        
        return $result;
        
    }
        
    public function delete_table() {
        global $wpdb, $table_prefix;
        $result = true;
        
        $drop_sql  = "DROP TABLE IF EXISTS {$table_prefix}{$this->table_name};";
    
        $result = $wpdb->query(
            $wpdb->prepare($drop_sql)
        );
        
        return $result;
    }
        
    public function save_info() {
        global $wpdb, $table_prefix;

        $result = $wpdb->insert(
            
            $table_prefix . $this->table_name,
            array(
                'shipping_cost' => $this->shipping_cost,
                'other_costs'   => $this->other_costs,
                'wage_cost'     => $this->wage_cost,
                'product_id'    => $this->product_id,
                'created'       => time()
            )
            
        );
        
        if( ! $result ) {
            $result = $wpdb->update(
                
                $table_prefix . $this->table_name,
                array(
                    'shipping_cost' => $this->shipping_cost,
                    'other_costs' => $this->other_costs,
                    'wage_cost' => $this->wage_cost
                ),
                array(
                    'product_id' => $this->product_id,
                )
                
            );
        }

        return $result;
    }
        
    public function get_row( $id ) {
        global $wpdb, $table_prefix;
        $sql = "SELECT shipping_cost, other_costs, wage_cost FROM {$table_prefix}{$this->table_name} WHERE ". 
                "product_id = '$id'";
        $result = $wpdb->get_row(
            $wpdb->prepare(
                $sql
            )
        );
            
        return $result;
    }
        
    public function get_sum_of_price( $id ) {
        global $wpdb, $table_prefix;

        $sql = "SELECT SUM(shipping_cost + other_costs + wage_cost) FROM {$table_prefix}{$this->table_name} WHERE ". 
                "product_id = '$id'";
        $result = $wpdb->get_var(
            $wpdb->prepare(
                $sql
            )
        );
            
        return $result;
    }
        
    public function get_shipping_list($args) {
        $args = array(
            'status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'limit' => $args['num_per_page'],
            'page' => $args['page_number'],
            'return' => 'ids',
            'type' => $args[ 'type' ],
            'paginate' => true
        );
        
        $products = wc_get_products( $args );
        
        $product_list = [];
        foreach ( $products as $key => $rows ) {
            foreach ( $rows as $row ) {
                $product = wc_get_product( $row );
                $prices  = $this->get_row( $row );
                $shipping_cost = $other_costs = $wage_cost ="";
                
                if(!empty($prices)){
                    $shipping_cost = $prices->shipping_cost;
                    $other_costs = $prices->other_costs;
                    $wage_cost = $prices->wage_cost;
                }
                $product_list[] = array(
                    'product_id'    => $row,
                    'title'         => $product->get_title(),
                    'shipping_cost' => $shipping_cost | "",
                    'wage_cost'     => $wage_cost | "",
                    'other_costs'   => $other_costs | "",
                );
                // die(json_encode($product_list));
            }
        }
        return array(
            'product_list' => $product_list,
            'num_of_pages' => $products->max_num_pages
        );
        
        // $sql = "SELECT * FROM {$table_prefix}{$this->table_name} ";
        // $result = $wpdb->get_var(
        //     $wpdb->prepare(
        //         $sql
        //     )
        // );
            
        // return $result;
    }


    public function clear_shipping_cost() {
        
        global $wpdb, $table_prefix;

        $sql = "UPDATE ".
        $table_prefix.
        $this->table_name.
        " SET `shipping_cost`= '' WHERE 1";
        // die($sql);
        return $wpdb->query(
            $wpdb->prepare( $sql )
        );

    }


    public function clear_wage_cost() {
        global $wpdb, $table_prefix;
        
        $sql = "UPDATE ".
        $table_prefix.
        $this->table_name.
        " SET `wage_cost`= '' WHERE 1";
        // die($sql);
        return $wpdb->query(
            $wpdb->prepare( $sql )
        );
    }

    public function clear_all_wage_cost() {
        // Security check
        check_ajax_referer('referer_id', 'nonce');

        $response = 'OK';
        $result = $this->clear_wage_cost();
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);

        die();
    }

    /**
     * Clear other_cost column in all of Shipping list in Database
     * @since 1.0.0
     * @return boolean The result of update
     */
    public function clear_other_cost() {
        global $wpdb, $table_prefix;
        $sql = "UPDATE ".
        $table_prefix.
        $this->table_name.
        " SET `other_costs`= '' WHERE 1";
        // die($sql);
        return $wpdb->query(
            $wpdb->prepare( $sql )
        );
    }

    public function clear_all_other_cost(){
        // Security check
        check_ajax_referer( 'clear_all_other_cost', 'nonce' );

        $response = 'OK';
        $result = $this->clear_other_cost();

        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success( $response );

        die();
    }

    /**
     * Get all of Shipping list
     * @since 1.0.0
     * @return json     Shipping list
    */
    public function get_price_list() {
        // Security check
        check_ajax_referer('get_price_list', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        $results =  $this->get_shipping_list(array(
            'type'          => $_POST['type'],
            'num_per_page'  => $_POST['num_per_page'],
            'page_number'   => $_POST['page_number'],
        ));
        
        wp_send_json_success($results);
        
        die();
    }

    /**
     * 
     * Update one of the product items that is called in the `Product` class and `init_form()` function : JS function is update_product(product)
     * 
    */
    public function save_shipping() {
        // Security check
        check_ajax_referer( 'save_shipping', 'nonce' );
        $shipping =  new PDT_Shipping( array(
            'shipping_cost' => $_POST['shipping_cost'], 
            'other_costs'   => $_POST['other_costs'], 
            'wage_cost'     => $_POST['wage_cost'], 
            'product_id'    => $_POST['product_id'], 
        ) );
        $result = $shipping->save_info();

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);
                
        die();
    }

    public function clear_all_shipping_cost() {
        // Security check
        check_ajax_referer('clear_all_shipping_cost', 'nonce');

        $response = 'OK';
        $result = $this->clear_shipping_cost();
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);
        

        die( json_encode( $result ) );
    }

    public function load_dependencies() {
        // Load database class tools
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tools/class-pd-database.php';
    }
    
}