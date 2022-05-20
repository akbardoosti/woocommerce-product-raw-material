<?php
    require_once("class-category-profit.php");
    require_once("class-shipping.php");
    
    class PDT_Product{
        private $name;
        private $price;
        
        private $table_prefix;
        private $table_name = "pd_product_details";
        private $items_table_name = "pd_product_items";
        private $id;
        private static $instance;
       
        public function __construct($data=[]){
            $this->name = isset($data['product_name'])?$data['product_name']:"";
            $this->price = isset($data['product_price'])?str_replace(",","",$data['product_price']):"";
            require_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
            require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
            global $table_prefix;
        
            $this->table_prefix = $table_prefix;
            $this->wpdb = new wpdb(DB_USER,DB_PASSWORD,DB_NAME,DB_HOST);

            // Load database class tools
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tools/class-pd-database.php';

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
        
        /* Member functions */
        public function setPrice($par){
            $this->price = $par;
        }
        public function getPrice(){
            return number_format($this->price);
        }
        public function setName($par){
            $this->name = $par;
        }
        public function getName(){
            return $this->title;
        }
        public function setId($par){
            $this->id = $par;
        }
        public function getId(){
            return $this->id;
        }
        public function setWpdb($str){
            $this->wpdb = $str;
        }
        public function getWpdb(){
            return $this->wpdb;
        }
        public function setTablePrefix($str){
            $this->table_prefix = $str;
        }
        public function getTablePrefix(){
            return $this->table_prefix;
        }

        public function init_form(){
          //  echo plugin_dir_path( __FILE__ ) .'/../views/Product/init_form.php<br/>';
          //  echo MY_PLUGIN_ADDRESS.'views/Product/init_form.php';
          //  require_once();
            $currencies    = get_woocommerce_currencies();
            $currency_code = get_woocommerce_currency();
            require_once(plugin_dir_path( __FILE__ ) .'/../views/Product/init_form.php');
            
        }
        
        /**
         * 
         * Delete product from product list
         */
         public  function delete_product($id){
             $result = $this->wpdb->delete( $this->table_prefix.$this->table_name, array( 'ID' => $id ) );
             return $result;
         }
        /**
        *   This function created for Create table 
        */
        public function create_table(){
            global $wpdb, $table_prefix;
            // wp_die($this->table_name) ;
            // wp_die( var_dump(PD_Databse::is_table_exist( $table_prefix.$this->table_name )) );
            if( ! PD_Databse::is_table_exist( $table_prefix.$this->table_name ) ) {
                $sql .= "CREATE TABLE " . $table_prefix . $this->table_name . "(";
                $sql .=     "ID int(10) AUTO_INCREMENT NOT NULL,";
                $sql .=     "NAME VARCHAR(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,";
                $sql .=     "PRICE VARCHAR(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,";
                $sql .=     "PRIMARY KEY(ID)";
                $sql .= ");";
                
                $result = $wpdb -> query($wpdb->prepare($sql));
            }

            return $result;
        }
        
        /**
         * This function create save information table
         */ 
        public  function create_save_information_table(){
            global $wpdb, $table_prefix;
            
            
            if( ! PD_Databse::is_table_exist( $table_prefix . $this->items_table_name ) ) {

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
        /**
        *   This function created for Delete table
        */
        public  function delete_table(){
            global $wpdb, $table_prefix ;
                
            $drop_sql  = "DROP TABLE IF EXISTS {$table_prefix}pd_product_details;";
            
            $result = $wpdb -> query($wpdb->prepare($drop_sql));
            return $result;
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
        *   This function save Product info to DataBase
        */
        public function save_info(){
            
            // $sql = "INSERT INTO TABLE ".$table_prefix."PRODUCT_DETAILS (NAME, PRICE) VALUES(";
            // $sql .= $data['product_name'] . "," . $data['product_price'] . ")";

            $result = $this->wpdb->insert( 
                $this->table_prefix."pd_product_details", 
                array( 
                    'NAME' => $this->name, 
                    'PRICE' => $this->price, 
                )
            );
            
            
            return $result;
        }
        
        /**
         * This function created for save product items
         **/
        public function save_items($post_id, $product_id, $number){
            $result = $this->wpdb->insert(
                $this->table_prefix.'pd_product_items',
                array(
                    'post_id' => $post_id,
                    'product_id' => $product_id,
                    'product_number' => $number
                )
            );
            return $result;
        }
        /**
         * This function created for save variable product items
         **/
        public function save_variable_items($variable_id, $product_id, $number){
            $result = $this->wpdb->insert(
                $this->table_prefix.'pd_product_items',
                array(
                    'variation_id' => $variable_id,
                    'product_id' => $product_id,
                    'product_number' => $number
                )
            );
            return $result;
        }
        /**
         * 
         * 
        */ 
        public function save_product_array($products){
            
        }
        
        /**
         * 
         * Retrieve all product list
         * 
         */ 
        public static function get_all_products(){
            global $wpdb , $table_prefix;
            
            $sql = "SELECT ID, NAME, FORMAT(PRICE,0) PRICE FROM {$table_prefix}pd_product_details";
            $results = $wpdb->get_results( $sql );
            return $results;
        }
        
        /**
         * Update the product
         */ 
        public function update_product(){
            return $this->wpdb->update( 
                $this->table_prefix.$this->table_name, 
                array(
                    'PRICE' => $this->price,
                    'NAME' => $this->name
                ), 
                array(
                    'ID' => $this->id
                )
            );
        }
        
        /**
         *
         * Get product dropdown list in HTML
         * 
         */
         public static function get_product_dropdown($type, $id=null){
            $currencies    = get_woocommerce_currencies();
            $currency_code = get_woocommerce_currency();
            global $wpdb, $table_prefix;
            
            require(plugin_dir_path( __FILE__ ) .'/../views/Product/get_product_dropdown.php');
        }
         
         /**
          * This function create for Product items
          */
         public function clear_items($type, $post_id){
            if($type == 'simple'):
                return $this->wpdb->delete( 
                    $this->table_prefix . $this->items_table_name, 
                    array( 
                        'post_id' => $post_id 
                    ) 
                );
            elseif($type == 'variable'):
                return $this->wpdb->delete( 
                    $this->table_prefix . $this->items_table_name, 
                    array( 
                        'variation_id' => $post_id 
                    ) 
                );
            endif;
         }
         
         /**
          * 
          * Description: This function get total of items that inserted for products
          * 
          */ 
         public function get_item_price($type, $id, $parent_id=null){
             
             $category = get_the_terms( 
                 isset( $parent_id )? intval($parent_id) : intval($id), 
                 'product_cat' 
            );
            foreach ($category as $term) {
                $product_cat_id = $term->term_id;
                break;
            }
                
            $cat_profit = CategoryProfit::get_instance()->get_profit( $product_cat_id );
            $shipping   = PD_Shipping::get_instance()->get_row( isset( $parent_id )? intval($parent_id) : intval($id) );
           
            
                
            if($type == 'simple'):
                /* If the type of product is simple, execute this code */
                $sql = "SELECT SUM(wpdet.PRICE*wpitms.product_number) total_price FROM {$this->table_prefix}pd_product_items AS wpitms 
    LEFT JOIN  `{$this->table_prefix}pd_product_details` wpdet ON wpitms.product_id = wpdet.`ID`
    WHERE wpitms.`post_id` = '$id'";
    
            else:
                
                /* If the type of product is variable, execute this code */
                $sql = "SELECT SUM(wpdet.PRICE*wpitms.product_number) total_price FROM {$this->table_prefix}pd_product_items AS wpitms 
    LEFT JOIN  `{$this->table_prefix}pd_product_details` wpdet ON wpitms.product_id = wpdet.`ID`
    WHERE wpitms.`variation_id` = '$id'";
    
            endif;
            
             $total = $this->wpdb->get_var($sql);
             
             if ( isset( $shipping->other_costs ) && isset( $shipping->wage_cost ) ) {
                $total += intval($shipping->other_costs) + intval($shipping->wage_cost);
             }
             
             return $total + ( ( $total * $cat_profit ) / 100 );
         }

         public function clear_price() {
            $sql = "UPDATE ".
            $this->table_prefix.
            $this->table_name.
            " SET `PRICE`= '' WHERE 1";
            // die($sql);
            return $this->wpdb->query(
                $this->wpdb->prepare( $sql )
            );
         }

         public function get_blank_price_items( $product_id ) {
            
            $sql = "SELECT wpd.* FROM `wp_pd_product_items` AS wpi LEFT JOIN wp_pd_product_details AS wpd".
            " ON wpi.product_id = wpd.ID WHERE wpi.variation_id = '$product_id' OR wpi.post_id = '$product_id'".
            " AND ( wpd.PRICE IS NULL OR wpd.PRICE = '' )";

            $result = $this->wpdb->get_results(
                $this->wpdb->prepare( 
                    $sql
                )
            );
// die(json_encode(['success'=>false, $result ] ) );
            return $result;
         }

         /**
            * 
            * Get all of the product items that is called in the `Product` class and `init_form()` function : JS function is save_info()
            * 
            */
         public function save_product_info() {
            $formData = $_POST['formData'];
            foreach($formData as $row):
                $product = new Product($row);
                $result = $product->save_info();
            endforeach;
            
            die(json_encode($result));
         }

         /**
     * 
     * Get all of the product items that is called in the `Product` class and `init_form()` function : JS function is get_all_products()
     * 
    */
        public function get_products() {
            $products = $this->get_all_products();
            die(json_encode($products));
        }

        /**
     * 
     * Delete one of the product items that is called in the `Product` class and `init_form()` function : JS function is delete_product(item_id)
     * 
    */
        public function delete() {
            $product = new Product();
            $result = $product->delete_product($_POST['product_id']);
            die(json_encode($result));
        }

        /**
     * 
     * Update one of the product items that is called in the `Product` class and `init_form()` function : JS function is update_product(product)
     * 
    */
        public function update() {
            $product = new Product();
            $update_product = $_POST['product'];
            $product -> setName($update_product['NAME']);
            $product -> setPrice(str_replace(',','',$update_product['PRICE']));
            $product -> setId($update_product['ID']);
            
            $result = $product->update_product();
            die(json_encode($result));
        }


        /**
     * 
     * Get all of the woocommerce products that is called in the `CreatedProduct` Class and `RenderPage()` Function : JS function is get_woocommerce_products()
     * 
     */ 
        public function get_woocommerce_products() {
            $type        = $_POST['product_type'];
        $status      = $_POST['product_status'];
        $limit       = $_POST['num_per_page'];
        $page        = $_POST['page_number'];
        
        
        /* Create instance primary substance from Product class*/
        $primary_substance = Product::GetInstance(); 
        $args = array(
            'status' => $status,
            'orderby' => 'date',
            'order' => 'DESC',
            'limit' => $limit,
            'page'  => $page,
            'return' => 'ids',
            'type' => $type,
            'paginate' => true
        );
        
        $products = wc_get_products( $args );
        $result = [];
        if($type == 'simple'):
            foreach($products as $rows):
                if(!is_array($rows))
                    continue;
                foreach($rows as $row):
                    $product = wc_get_product( $row );
                    $shipping_price = PD_Shipping::get_instance()->get_row( $row )->shipping_cost;
                    
                    $created_product = new CreatedProduct([
                        'type' => $type ,
                        'pro_id' => $row
                    ]);
                    $price = $created_product->get_price();
                    
                    $is_suggested_price = ( $primary_substance->get_item_price( 'simple', $row ) + $shipping_price ) == $product -> get_regular_price();
                    $suggested_price = $primary_substance->get_item_price('simple', $row);
                    if( ! empty( $suggested_price ) ) {
                        $suggested_price += $shipping_price;
                    } else {
                        $suggested_price = "";
                    }
                    
                    
                    $result[] =[
                        'id'=>$row,
                        'title' => $product->get_title(),
                        'regular_price' => $product -> get_regular_price(),
                        'sale_price'=>$product->get_sale_price(),
                        'old_price'=>isset($price)?$price->old_price:"",
                        'type'=>$product->get_type(),
                        'suggested_price' => $suggested_price,
                        'isSuggestedPrice' => $is_suggested_price
                    ];
                    unset($created_product);
                    // 
                endforeach;
            endforeach;
        else:
            foreach($products as $rows):
                
                if(!is_array($rows))
                    continue;
                foreach($rows as $item):
                    $product = wc_get_product( $item );
                    $shipping_price = PD_Shipping::get_instance()->get_row( $row )->shipping_cost;
                    
                    $result[] =[
                        'id'=>$item,
                        'title' => $product->get_title(),
                        'regular_price' => $product -> get_regular_price(),
                        'sale_price'=>$product->get_sale_price(),
                        'pa_size' => $product->get_attribute('pa_size'),
                        'type' => 'main'
                    ];
                    $variations = $product->get_available_variations();
                    foreach ($variations as $row) :
                        
                        $variation = new WC_Product_Variation( $row['variation_id'] );
                        $created_product = new CreatedProduct([
                            'type' => $type ,
                            'pro_id' =>  $row['variation_id'] 
                        ]);
                        $price = $created_product->get_price();
                        
                        /** Get attribute of variable product */
                        $attributes = $variation->get_attributes();
                        $variation_names = [];
                        $term_slug = 'foo-bar';
                        $taxonomies = get_taxonomies();
                        foreach ( $attributes as $key => $value) {
                            $variation_key =  end(explode('-', $key));
                           
                            if(empty($variation->get_attribute($variation_key)))
                                $variation_names[] = $value;
                            else
                                $variation_names[] =  $variation->get_attribute($variation_key);
                        }
                        $attribute = implode(',', $variation_names);
                        
                        $is_suggested_price = ($primary_substance->get_item_price('variable', $row['variation_id'], $item) + $shipping_price) == $variation -> get_regular_price();
                        $suggested_price = $primary_substance->get_item_price( 'variable', $row['variation_id'], $item );
                        if( ! empty( $suggested_price ) ) {
                            $suggested_price += $shipping_price;
                        } else {
                            $suggested_price = "";
                        }
                        
                        $result[] =[
                            'id'=>$variation->variation_id,
                            'title' => $variation->get_title().'('.$attribute.')',
                            'regular_price' => $variation -> get_regular_price(),
                            'sale_price'=>$variation->get_sale_price(),
                            'type'=>'variable',
                            'old_price'=>isset($price->old_price)?$price->old_price:"",
                            'suggested_price' =>  $suggested_price ,
                            'isSuggestedPrice' => $is_suggested_price,
                            'pa_size' => $variation->get_attribute('pa_size')
                        ];
                    endforeach;
                endforeach;
            endforeach;
        endif;
        die(json_encode([
            'data'=>$result, 
            'num_of_pages'=>$products->max_num_pages
        ]));
        }

        public function clear_all_material_price() {
            $result = Product::GetInstance()->clear_price();
        die( json_encode( $result ) );
        }

        public function check_item_price() {
            $product_id = $_POST[ 'product_id' ];

        $result = Product::GetInstance()->get_blank_price_items( $product_id );
// die(json_encode(['success'=>false, $result]));
        if ( ! empty( $result ) ) {
            // code...
            $response[ 'success' ] = false;
            $response[ 'data' ] = array_column( $result, 'NAME' );
        } else {
            $response[ 'success' ] = true;
        }

        die( json_encode( $response ) );
        }

    }