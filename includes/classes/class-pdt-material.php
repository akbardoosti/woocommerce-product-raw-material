<?php
    
    
class PDT_Material{
    private $name;
    private $price;
    
    private $table_prefix;
    private $table_name ;
    private $id;
    private static $instance;
    
    public function __construct($data=[]){

        $this->name = isset($data['product_name'])?$data['product_name']:"";
        $this->price = isset($data['product_price'])?str_replace(",","",$data['product_price']):"";
        $this->table_name = "pd_product_details";
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
        global $wpdb , $table_prefix;
        $result = $wpdb->delete( $table_prefix.$this->table_name, array( 'ID' => $id ) );
        return $result;
    }
    /**
    *   This function created for Create table 
    */
    public function create_table(){
        global $wpdb, $table_prefix;
        // wp_die($this->table_name) ;
        if( ! PDT_Databse::is_table_exist( $table_prefix.$this->table_name ) ) {
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
    *   This function created for Delete table
    */
    public  function delete_table(){
        global $wpdb, $table_prefix ;
            
        $drop_sql  = "DROP TABLE IF EXISTS {$table_prefix}pd_product_details;";
        
        $result = $wpdb -> query($wpdb->prepare($drop_sql));
        return $result;
    }
        
        
    /**
    *   This function save Product info to DataBase
    */
    public function save_info(){
        global $wpdb , $table_prefix;
        // $sql = "INSERT INTO TABLE ".$table_prefix."PRODUCT_DETAILS (NAME, PRICE) VALUES(";
        // $sql .= $data['product_name'] . "," . $data['product_price'] . ")";

        $result = $wpdb->insert( 
            $table_prefix."pd_product_details", 
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
        global $wpdb , $table_prefix;
        $result = $wpdb->insert(
            $table_prefix.'pd_product_items',
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
        global $wpdb , $table_prefix;
        $result = $wpdb->insert(
            $table_prefix.'pd_product_items',
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
        global $wpdb , $table_prefix;
        return $wpdb->update( 
            $table_prefix.$this->table_name, 
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
        global $wpdb , $table_prefix;
        if($type == 'simple'):
            return $wpdb->delete( 
                $table_prefix . $this->items_table_name, 
                array( 
                    'post_id' => $post_id 
                ) 
            );
        elseif($type == 'variable'):
            return $wpdb->delete( 
                $table_prefix . $this->items_table_name, 
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
        global $wpdb , $table_prefix;
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
            $sql = "SELECT SUM(wpdet.PRICE*wpitms.product_number) total_price FROM {$table_prefix}pd_product_items AS wpitms 
LEFT JOIN  `{$table_prefix}pd_product_details` wpdet ON wpitms.product_id = wpdet.`ID`
WHERE wpitms.`post_id` = '$id'";
    
        else:
            
            /* If the type of product is variable, execute this code */
            $sql = "SELECT SUM(wpdet.PRICE*wpitms.product_number) total_price FROM {$table_prefix}pd_product_items AS wpitms 
LEFT JOIN  `{$table_prefix}pd_product_details` wpdet ON wpitms.product_id = wpdet.`ID`
WHERE wpitms.`variation_id` = '$id'";

        endif;
            
        $total = $wpdb->get_var($sql);
        
        if ( isset( $shipping->other_costs ) && isset( $shipping->wage_cost ) ) {
        $total += intval($shipping->other_costs) + intval($shipping->wage_cost);
        }
             
        return $total + ( ( $total * $cat_profit ) / 100 );
    }

    public function clear_price() {
        $sql = "UPDATE ".
        $table_prefix.
        $this->table_name.
        " SET `PRICE`= '' WHERE 1";
        // die($sql);
        return $wpdb->query(
            $wpdb->prepare( $sql )
        );
    }

    public function get_blank_price_items( $product_id ) {
        global $wpdb , $table_prefix;

        $sql = "SELECT wpd.* FROM `wp_pd_product_items` AS wpi LEFT JOIN wp_pd_product_details AS wpd".
        " ON wpi.product_id = wpd.ID WHERE wpi.variation_id = '$product_id' OR wpi.post_id = '$product_id'".
        " AND ( wpd.PRICE IS NULL OR wpd.PRICE = '' )";

        $result = $wpdb->get_results(
            $wpdb->prepare( 
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
        // Security check
        check_ajax_referer('save_product_info', 'nonce');

        $response = 'OK';
        $formData = $_POST['formData'];
        foreach ( $formData as $row ):
            $product = new PDT_Material( $row );
            $result = $product->save_info();
        endforeach;
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success( $response );
        
        
        die();
    }

    /**
     * 
     * Get all of the product items that is called in the `Product` class and `init_form()` function : JS function is get_all_products()
     * 
    */
    public function get_products() {
        // Security check
        check_ajax_referer('get_products', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        $products = $this->get_all_products();
        wp_send_json_success($products);
        die();
        
    }

    /**
     * 
     * Delete one of the product items that is called in the `Product` class and `init_form()` function : JS function is delete_product(item_id)
     * 
    */
    public function delete() {
        // Security check
        check_ajax_referer('delete_product', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);
        $product = new PDT_Material();
        $result = $product->delete_product($_POST['product_id']);
        die(json_encode($result));
    }

        /**
     * 
     * Update one of the product items that is called in the `Product` class and `init_form()` function : JS function is update_product(product)
     * 
    */
    public function update() {
        // Security check
        check_ajax_referer('update_product', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);
        $product = new PDT_Material();
        $update_product = $_POST['product'];
        $product -> setName($update_product['NAME']);
        $product -> setPrice(str_replace(',','',$update_product['PRICE']));
        $product -> setId($update_product['ID']);
        
        $result = $product->update_product();
        die(json_encode($result));
    }


    public function clear_all_material_price() {
        // Security check
        check_ajax_referer( 'clear_all_material_price', 'nonce' );
        $result = $this->clear_price();

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);
        die();
    }

    public function check_item_price() {
        // Security check
        check_ajax_referer( 'check_item_price', 'nonce' );

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        
        $product_id = $_POST[ 'product_id' ];

        $result = $this->get_blank_price_items( $product_id );
        if ( ! empty( $result ) ) {
            wp_send_json_error( array_column( $result, 'NAME' ) );
        } else {
            wp_send_json_success( $response );
        }

        die();
    }


    public function load_dependencies() {
        // Load database class tools
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tools/class-pdt-database.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/classes/class-category-profit.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . "includes/classes/class-shipping.php";
    }

}