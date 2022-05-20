<?php 
	
/**
 * 
 */
class PDT_Databse 
{
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

		$sql = "CREATE VIEW " . $view_name . " AS SELECT
    `wp`.`ID` AS `order_id`,
    `wp`.`post_date` AS `post_date`,
    `wopl`.`product_id`,
    `wopl`.`customer_id`,
    `wopl`.`product_qty`,
    `wopl`.`product_net_revenue`,
    `wopl`.`product_gross_revenue`,
    `wopl`.`tax_amount`,
    `wopl`.`shipping_amount`,
    `wopl`.`coupon_amount`,
    `wopl`.`shipping_tax_amount`,
    `wtr`.`term_taxonomy_id`,
    `wtt`.`term_id`,
    `pm`.`meta_value` AS email,
    (product_net_revenue+coupon_amount+shipping_amount) AS order_total,
    wp.post_status
FROM
    `wp_wc_order_product_lookup` AS wopl
    LEFT JOIN `khabesta_sqldb`.`wp_posts` AS `wp` ON wp.ID = wopl.order_id
    LEFT JOIN (
        SELECT
            *
        FROM
            wp_postmeta
        WHERE
            meta_key = '_billing_email'
    ) AS pm ON wp.ID = pm.post_id
    LEFT JOIN `wp_term_relationships` AS wtr ON wtr.`object_id` = wopl.product_id
    LEFT JOIN `wp_term_taxonomy` AS wtt ON wtr.term_taxonomy_id = wtt.term_taxonomy_id
WHERE
    wp.post_type = 'shop_order'
    AND (
        post_status = 'wc-processing'
        OR post_status = 'wc-completed'
        OR post_status = 'wc-refunded'
    )
GROUP BY
    wopl.order_item_id  
ORDER BY `order_id` ASC";
	

	$sql = "SELECT
    wp.ID order_id,
    wp.post_date,
    wp.post_author,
    pm.meta_value AS email,
    net_sale_info.order_item_id,
    net_sale_info.order_item_name,
    SPLIT_STR(item_details.meta_value,',',1) AS item_gross_price,
    SPLIT_STR(item_details.meta_value,',',2) AS item_net_price,
    city_info.meta_value AS `city`,
    state_info.meta_value AS `state`,
    -- net_payment.meta_value AS item_net_price,
    shipping_detail.meta_value AS shipping_amount,
    item_quantity.meta_value AS qty,
    -- shipping_info.shipping_amount,
    product_cat.term_id,
    order_product.product_id,
    customer_info.meta_value AS user_id
FROM
    wp_posts AS wp
    LEFT JOIN(
        SELECT
            `wp_postmeta`.`post_id` AS `post_id`,
            `wp_postmeta`.`meta_value` AS `meta_value`
        FROM
            `wp_postmeta`
        WHERE
            (
                `wp_postmeta`.`meta_key` = '_billing_email'
            )
    ) AS pm ON `wp`.`ID` = `pm`.`post_id`
    LEFT JOIN (
        SELECT
            `wp_postmeta`.`post_id` AS `post_id`,
            `wp_postmeta`.`meta_value` AS `meta_value`
        FROM
            `wp_postmeta`
        WHERE
            (
                `wp_postmeta`.`meta_key` = '_customer_user'
            )
    ) AS customer_info ON `wp`.`ID` = `customer_info`.`post_id`
    LEFT JOIN (
        SELECT
            `wp_postmeta`.`post_id` AS `post_id`,
            `wp_postmeta`.`meta_value` AS `meta_value`
        FROM
            `wp_postmeta`
        WHERE
            (
                `wp_postmeta`.`meta_key` = '_billing_state' 
            )
    ) AS state_info ON `wp`.`ID` = `state_info`.`post_id`
    LEFT JOIN (
        SELECT
            `wp_postmeta`.`post_id` AS `post_id`,
            `wp_postmeta`.`meta_value` AS `meta_value`
        FROM
            `wp_postmeta`
        WHERE
            (
                `wp_postmeta`.`meta_key` = '_billing_city'
            )
    ) AS city_info ON `wp`.`ID` = `city_info`.`post_id`
    LEFT JOIN (
        SELECT
            *
        FROM
            wp_woocommerce_order_items
        WHERE
            order_item_type = 'line_item'
    ) AS net_sale_info ON wp.ID = net_sale_info.order_id
    LEFT JOIN (
        SELECT
            order_item_id,
            GROUP_CONCAT(meta_value) AS meta_value
        FROM
            `wp_woocommerce_order_itemmeta`
        WHERE
            `meta_key` LIKE '_line_subtotal' OR `meta_key` LIKE '_line_total'
       GROUP BY order_item_id
       ORDER BY meta_key
    ) AS item_details ON net_sale_info.order_item_id = item_details.order_item_id
    LEFT JOIN (
        SELECT order_item_id, meta_value FROM `wp_woocommerce_order_itemmeta` WHERE `meta_key` LIKE '_qty'
    ) AS item_quantity ON item_quantity.order_item_id = item_details.order_item_id
    LEFT JOIN (
        SELECT
            order_item_id,
            meta_value
        FROM
            `wp_woocommerce_order_itemmeta`
        WHERE
            `meta_key` LIKE '_line_total'
    ) AS net_payment ON net_sale_info.order_item_id = net_payment.order_item_id
    LEFT JOIN (
        SELECT
            meta_value AS product_id,
            order_item_id
        FROM
            `wp_woocommerce_order_itemmeta`
        WHERE
            `meta_key` LIKE '_product_id'
    ) AS order_product ON net_sale_info.order_item_id = order_product.order_item_id
    LEFT JOIN (
        SELECT
            object_id AS product_id,
            GROUP_CONCAT(term_id) AS term_id
        FROM
            `wp_term_relationships` AS wtr
            LEFT JOIN wp_term_taxonomy AS wtt ON wtr.`term_taxonomy_id` = wtt.term_taxonomy_id
        WHERE
            wtt.taxonomy = 'product_cat' AND wtt.parent != 0
        GROUP BY
            product_id
    ) AS product_cat ON product_cat.product_id = order_product.product_id
    LEFT JOIN (
        SELECT
            *
        FROM
            wp_woocommerce_order_items
        WHERE
            order_item_type = 'shipping'
    ) AS shipping_info ON wp.ID = shipping_info.order_id
    LEFT JOIN (
        SELECT
            order_item_id,
            meta_value
        FROM
            `wp_woocommerce_order_itemmeta`
        WHERE
            `meta_key` LIKE 'cost'
    ) AS shipping_detail ON shipping_info.order_item_id = shipping_detail.order_item_id
WHERE
    wp.post_type = 'shop_order'
    AND(
        (`wp`.`post_status` = 'wc-processing')
        OR(`wp`.`post_status` = 'wc-completed')
        OR(`wp`.`post_status` = 'wc-refunded')
    ) 
ORDER BY
    wp.ID
    -- AND order_product.product_id IN (
    --     SELECT
    --         object_id AS product_id
    --     FROM
    --         `wp_term_relationships` AS wtr
    --         LEFT JOIN wp_term_taxonomy AS wtt ON wtr.`term_taxonomy_id` = wtt.term_taxonomy_id
    --     WHERE
    --         wtt.taxonomy = 'product_cat' 
    --         -- AND 
    --         -- term_id
    -- )";
	// die($sql);
		return $wpdb->query(
			$wpdb->prepare( $sql )
		);

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