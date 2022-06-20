<?php

class PDT_Category_Profit
{

    /**
     * Instance of this object.
     * @access private 
     * @since 1.0.0
     */
    private static $instance;

    /**
     * Name of MySQL table.
     * @access private 
     * @since 1.0.0
     */
    private $table_name;

    /**
     * Value of profit.
     * @access private 
     * @since 1.0.0
     */
    private $wpdb;

    /**
     * Value of profit.
     * @access private 
     * @since 1.0.0
     */
    private $table_prefix;

    /**
     * Value of category id.
     * @access private 
     * @since 1.0.0
     */
    private $term_id;

    /**
     * Value of profit.
     * @access private 
     * @since 1.0.0
     */
    private $profit;

    /**
     * Initialize this object
     * @since 1.0.0
     */ 
    public function __construct($args = [])
    {
        
        $this->table_name = 'pd_category_profit';

        $this->term_id = isset($args['term_id']) ? $args['term_id'] : "";
        $this->profit = isset($args['profit']) ? $args['profit'] : "";

        // Load database class tools
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/tools/class-pd-database.php';
    }

    /**
     * Get instance of this object
     * @since 1.0.0
     */ 
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Setup action
     * @since 1.0.0
     * @return integer Profit value
     * 
     */ 
    public function setup_actions()
    {
        //
        //Main plugin hooks
        register_activation_hook(DIR_PATH, array($this, 'activate'));
        register_deactivation_hook(DIR_PATH, array($this, 'deactivate'));

    }

    /**
     * Get category profit from database
     * @since 1.0.0
     * @return integer Profit value
     * 
     */  
    public function activate()
    {

        $this->create_table();
    }

    public function deactivate()
    {
        $this->delete_table();
    }

    public function uninstall_plugin()
    {

    }

    /**
     * Create category profit table in database
     * @since 1.0.0
     * @return boolean 
     */  
    public function create_table()
    {
        global $wpdb, $table_prefix;

        $result = true;
        if (!PDT_Databse::is_table_exist($this->table_prefix . $this->table_name)) {

            $sql .= "CREATE TABLE " . $this->table_prefix . $this->table_name . " (";
            $sql .= "ID bigint(20) AUTO_INCREMENT NOT NULL,";
            $sql .= "term_id bigint(20) NOT NULL,";
            $sql .= "profit varchar(10) NOT NULL,";
            $sql .= "PRIMARY KEY(ID),";
            $sql .= "CONSTRAINT term_id UNIQUE (term_id)";
            $sql .= ");";

            $result = $wpdb->query( $wpdb->prepare( $sql ) );
        }

        return $result;

    }
    /**
     * Delete category profit table from database
     * @since 1.0.0
     * @return integer Profit value
     * 
     */  
    public function delete_table()
    {
        global $wpdb, $table_prefix;

        $drop_sql = "DROP TABLE IF EXISTS {$this->table_prefix}{$this->table_name};";

        $result = $wpdb->query(
            $wpdb->prepare( $drop_sql )
        );

        return $result;
    }

    /**
     * Save category profit information in database
     * @since 1.0.0
     * @return boolean
     * 
     */  
    public function save_info()
    {
        global $wpdb, $table_prefix;

        $result = $wpdb->insert(

            $this->table_prefix . $this->table_name,
            array(
                'term_id' => $this->term_id,
                'profit' => $this->profit,
            )

        );

        if (!$result) {
            $result = $wpdb->update(

                $table_prefix . $this->table_name,
                array(
                    'term_id' => $this->term_id,
                    'profit' => $this->profit,
                ),
                array(
                    'term_id' => $this->term_id,
                )

            );
        }

        return $result;
    }

    /**
     * Get category profit from database
     * @since 1.0.0
     * @return integer Profit value
     * 
     */  
    public function get_profit($term_id)
    {
        global $wpdb, $table_prefix;
        $result = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT profit FROM {$this->table_prefix}{$this->table_name} WHERE term_id = '{$term_id}'"
            )
        );

        return $result;
    }

    /**
     * Clear all category profit value from database
     * @since 1.0.0
     * @return boolean  result of query executing
     */
    public function clear_profit()
    {
        global $wpdb, $table_prefix;
        $sql = "UPDATE " .
        $table_prefix .
        $this->table_name .
            " SET `profit`= '' WHERE 1";
        // die($sql);
        return $wpdb->query(
            $wpdb->prepare($sql)
        );
    }

    /**
     *
     * Get all of the category profit list that is called in the `Product` class and `init_form()` function : JS function is get_all_categories()
     * @since 1.0.0
     * 
     */
    public function get_categories()
    {
        // Security check
        check_ajax_referer('referer_id', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        wp_send_json_success($response);

        $categories = get_tags(array('taxonomy' => 'product_cat'));
        $output = [];
        $category_profit = CategoryProfit::get_instance();

        foreach ($categories as $row) {
            $output[] = array(
                'term_id' => $row->term_id,
                'name' => $row->name,
                'profit' => $category_profit->get_profit($row->term_id),
            );
        }

        die(json_encode($output));
    }

    /**
     *
     * Get all of the category profit list that is called in the `Product` class and `init_form()` function : JS function is get_all_categories()
     *
     */
    public function update()
    {
        // Security check
        check_ajax_referer('referer_id', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        // wp_send_json_success($response);
        $category = $_POST['category'];

        $category_profit = new CategoryProfit([
            'term_id' => $category['term_id'],
            'profit' => $category['profit'],
        ]);

        $result = $category_profit->save_info();

        die(json_encode($result));
    }

    // Clear all profit value from Database table
    public function clear_all_profit()
    {
        // Security check
        check_ajax_referer('referer_id', 'nonce');

        $response = 'OK';
        // Send response in JSON format
        // wp_send_json( $response );
        // wp_send_json_error();
        // wp_send_json_success($response);
        $result = CategoryProfit::get_instance()->clear_profit();
        die(json_encode($result));
    }
}
