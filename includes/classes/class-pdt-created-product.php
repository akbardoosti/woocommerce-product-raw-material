<?php
    require_once("Product.php");
    
    class PDT_Created_Product{
        
        private static $instance;
        private $wpdb;
		private $table_prefix;
		private $table_name='woocommerce_product_archive';
		
		private $type;
		private $id;
		private $old_price;
        private $price;
        private $suggested_price;
        /**
         * Description: This function is constructor of CreatedProduct
         */ 
        public function __construct($data=null){
            require_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
        	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
        	global $table_prefix;
        
        	$this->table_prefix = $table_prefix;
        	$this->wpdb = new wpdb(DB_USER,DB_PASSWORD,DB_NAME,DB_HOST);
        	
        	if(isset($data)):
            	$this->type = isset($data['type']) ? $data['type']:"";
            	$this->old_price = isset($data['o_price']) ? $data['o_price']:"";
                $this->price = isset($data['n_price']) ? $data['n_price']:"";
                $this->suggested_price = isset($data['sug_price']) ? $data['sug_price']:"";
                $this->id = isset($data['pro_id']) ? $data['pro_id']:"";
            endif;

            // Load database class tools
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tools/class-pd-database.php';
        }
        
        /**
         * 
         * Description: Get instance of CreatedProduct
         * 
         */ 
        public static function GetInstance(){
            if(!isset(self::$instance)){
                self::$instance = new self();
            }
            return self::$instance;
        }
        
        /**
         * 
         * Description: Get table name 
         * 
         */ 
        public function getTableName(){
            return $this->table_prefix.$this->table_name;
        }
        
        /**
         * 
         * Description: This function created for generate Html form of Created products in woocommerce
         * 
         */ 
        public function RenderPage(){
            $currencies    = get_woocommerce_currencies();
            $currency_code = get_woocommerce_currency();
            require_once(plugin_dir_path( __FILE__ ) .'/../views/CreatedProduct/RenderPage.php');
        }
        
        public function save_info(){
            // return $this->old_price;
            if($this->type == 'simple'):
                $product = wc_get_product( $this->id );
                // return $product -> get_regular_price();
                $result = $this->wpdb->insert(
    		        $this->table_prefix.$this->table_name,
    		        array(
    		            'post_id' => $this->id,
    		            'old_price' => $product -> get_regular_price(), 
    		            'price' => $this->price,
    		            'suggested_price' => $this->suggested_price,
    		            'created_date' => time()
    		        )
    		    );
    		    if( $result ):
    		        update_post_meta($this->id, '_regular_price', (float)$this->price);
    		    endif;
		    elseif($this->type == 'variable'):
		        $variation = new WC_Product_Variation( $this->id );
		      
		        $result = $this->wpdb->insert(
    		        $this->table_prefix.$this->table_name,
    		        array(
    		            'variation_id' => $this->id,
    		            'old_price' => $variation -> get_regular_price(),
    		            'price' => $this->price,
    		            'suggested_price' => $this->suggested_price,
    		            'created_date' => time()
    		        )
    		    );
    		    
    		    if( $result ):
    		        update_post_meta($this->id, '_regular_price', (float)$this->price);
    		    endif;
    		endif;
    		
		    return $result;
        }
        
        /**
         * 
         * This function get price of Created Product
         * 
         */
        public function get_price(){
			global $wpdb, $table_prefix;

            if($this->type == 'simple'):
                $sql = "SELECT `old_price`, `price`, `suggested_price` FROM ".
                "`{$table_prefix}{$this->table_name}` WHERE `post_id` = '{$this->id}'".
                " AND `ID` IN (SELECT MAX(ID) FROM `{$table_prefix}{$this->table_name}` WHERE `post_id` = '{$this->id}')";
            elseif($this->type == 'variable'):
                $sql = "SELECT `old_price`, `price`, `suggested_price` FROM `{$table_prefix}{$this->table_name}` WHERE `variation_id` = '{$this->id}' AND `ID` IN (SELECT MAX(ID) FROM `{$table_prefix}{$this->table_name}` WHERE `variation_id` = '{$this->id}')";
                // $sql = "SELECT `old_price`, `price`, `suggested_price` FROM `wp_woocommerce_product_archive` WHERE `variation_id` = '{$this->id}' HAVING MAX(CAST(`created_date` AS SIGNED))";
            endif;
            
            $result = $wpdb->get_row($sql);
            
            return $result;
        }
        /**
         * 
         * Description: This function create $this->table_name table in mysql
         * 
         */ 
        public function create_table(){
			global $wpdb, $table_prefix;
            $result = true;
            
            if( ! PDT_Databse::is_table_exist( $this->table_prefix.$this->table_name ) ) {       
                
    			$sql .= "CREATE TABLE ".$table_prefix.$this->table_name." (";
    			$sql .= 	"ID bigint(20) AUTO_INCREMENT NOT NULL,";
    			$sql .= 	"post_id bigint(20) NOT NULL,";
    			$sql .=     "variation_id bigint(20) NOT NULL,";
    			$sql .= 	"old_price varchar(30) NOT NULL,";
    			$sql .= 	"price varchar(30) NOT NULL,";
    			$sql .= 	"suggested_price varchar(30) NOT NULL,";
    			$sql .= 	"created_date varchar(15) NOT NULL,";
    			$sql .= 	"PRIMARY KEY(ID)";
    			$sql .= ");";
    			
    		    $result = $wpdb->query( $wpdb->prepare( $sql ) );

            }
            
            return $result;
        }
        
        /**
		 * This function delete woocommerce_product_archive table
		 */ 
		public function delete_table(){
			global $wpdb, $table_prefix;
		    
			$drop_sql  = "DROP TABLE IF EXISTS {$table_prefix}{$this->table_name};";
			
			$result = $wpdb->query( $wpdb->prepare( $drop_sql ) );
            
			return $result;
		}
    }