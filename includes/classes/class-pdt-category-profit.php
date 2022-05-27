<?php
    
    class PDT_Category_Profit {
        
        private static $instance;
        private $table_name;
        private $wpdb;
		private $table_prefix;
		private $term_id;
		private $profit;
		
        public function __construct($args=[]) {
            // add_action('')
            require_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
        	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        	global $table_prefix;
        	
        	$this->table_prefix = $table_prefix;
        	$this->wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
        	$this->table_name = 'pd_category_profit';
        	
        	$this->term_id = isset( $args[ 'term_id' ] ) ? $args[ 'term_id' ] : "";
        	$this->profit  = isset( $args[ 'profit' ] ) ? $args[ 'profit' ] : "";

            // Load database class tools
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tools/class-pd-database.php';
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
            
            
            $result = true;
            if( ! PD_Databse::is_table_exist( $this->table_prefix.$this->table_name ) ){
    			
    			$sql .= "CREATE TABLE ".$this->table_prefix.$this->table_name." (";
    			$sql .= 	"ID bigint(20) AUTO_INCREMENT NOT NULL,";
    			$sql .= 	"term_id bigint(20) NOT NULL,";
    			$sql .=     "profit varchar(10) NOT NULL,";
    			$sql .= 	"PRIMARY KEY(ID),";
    			$sql .= 	"CONSTRAINT term_id UNIQUE (term_id)";
    			$sql .= ");";
    			
    		    $result = $this->wpdb->query($this->wpdb->prepare($sql));
            }
            
            return $result;
            
        }
        
        public function delete_table() {
            
            $drop_sql  = "DROP TABLE IF EXISTS {$this->table_prefix}{$this->table_name};";
		
		    $result = $this->wpdb->query(
		        $this->wpdb->prepare($drop_sql)
		    );
            
			return $result;
        }
        
        public function save_info() {
            
            $result = $this->wpdb->insert(
                
		        $this->table_prefix.$this->table_name,
		        array(
		            'term_id' => $this->term_id,
		            'profit' => $this->profit
		        )
		        
		    );
		    
		    if( ! $result ) {
		        $result = $this->wpdb->update(
                    
    		        $this->table_prefix . $this->table_name,
    		        array(
    		            'term_id' => $this->term_id,
    		            'profit' => $this->profit
    		        ),
    		        array(
    		            'term_id' => $this->term_id,
                    )
    		        
    		    );
		    }
    		    
    		return $result;
        }
        
        public function get_profit($term_id) {
            
            $result = $this->wpdb->get_var(
                $this->wpdb->prepare(
                    "SELECT profit FROM {$this->table_prefix}{$this->table_name} WHERE term_id = '{$term_id}'"
                )
            );
    		    
    		return $result;
        }

        public function clear_profit() {
            $sql = "UPDATE ".
            $this->table_prefix.
            $this->table_name.
            " SET `profit`= '' WHERE 1";
            // die($sql);
            return $this->wpdb->query(
                $this->wpdb->prepare( $sql )
            );
        }

        /**
     * 
     * Get all of the category profit list that is called in the `Product` class and `init_form()` function : JS function is get_all_categories()
     * 
    */
        public function get_categories() {
            // Security check
            check_ajax_referer('referer_id', 'nonce');

            $response = 'OK';
            // Send response in JSON format
            // wp_send_json( $response );
            // wp_send_json_error();
            wp_send_json_success($response);
            
            $categories = get_tags(array( 'taxonomy' => 'product_cat' ));
            $output = [];
            $category_profit = CategoryProfit::get_instance();
            
            foreach( $categories as $row ) {
                $output[] = array(
                    'term_id' => $row->term_id,
                    'name' => $row->name,
                    'profit' => $category_profit -> get_profit( $row->term_id ),
                );
            }
            
            die(json_encode($output));
        }
        

        /**
     * 
     * Get all of the category profit list that is called in the `Product` class and `init_form()` function : JS function is get_all_categories()
     * 
    */
        public function update() {
            // Security check
            check_ajax_referer('referer_id', 'nonce');

            $response = 'OK';
            // Send response in JSON format
            // wp_send_json( $response );
            // wp_send_json_error();
            wp_send_json_success($response);
            $category = $_POST['category'];
        
            $category_profit = new CategoryProfit( [
                'term_id' => $category[ 'term_id' ],
                'profit' => $category[ 'profit' ]
            ] );
            
            $result = $category_profit->save_info();
            
            die(json_encode($result));
        }


        public function clear_all_profit() {
            // Security check
            check_ajax_referer('referer_id', 'nonce');

            $response = 'OK';
            // Send response in JSON format
            // wp_send_json( $response );
            // wp_send_json_error();
            wp_send_json_success($response);
            $result = CategoryProfit::get_instance()->clear_profit();
        die( json_encode( $result ) );
        }
    }