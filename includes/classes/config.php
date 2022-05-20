<?php 
	require_once "Product.php";
	require_once "class-category-profit.php";
	require_once("class-shipping.php");
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
	/**
	 * 
	 * Get all of the product items that is called in the `Product` class and `init_form()` function : JS function is save_info()
	 * 
	*/
	if ($_POST['action'] == 'save_product_info') {
	    // class-pdt-product.php => save_product_info
	}
	
	/**
	 * 
	 * Get all of the product items that is called in the `Product` class and `init_form()` function : JS function is get_all_products()
	 * 
	*/
	if ($_POST['action'] == 'get_all_products') {
        //  class-pdt-product.php => get_products
        
	}
	
	/**
	 * 
	 * Get all of the category profit list that is called in the `Product` class and `init_form()` function : JS function is get_all_categories()
	 * 
	*/
	if ($_POST['action'] == 'get_all_categories') {
        // pdt-category-profit.php => get_categories()
	}
	
	/**
	 * 
	 * Get all of the category profit list that is called in the `Product` class and `init_form()` function : JS function is get_all_categories()
	 * 
	*/
	if ($_POST['action'] == 'update_category_profit') {
        // pdt-category-profit.php => update()
	}
	
	/**
	 * 
	 * Delete one of the product items that is called in the `Product` class and `init_form()` function : JS function is delete_product(item_id)
	 * 
	*/
	if($_POST['action'] == 'delete_product'){
        //  class-pdt-product.php => delete
	    
	}
	
	/**
	 * 
	 * Update one of the product items that is called in the `Product` class and `init_form()` function : JS function is update_product(product)
	 * 
	*/
	if($_POST['action'] == 'update_product'){
	    // pdt-product.php => update()
	}
	
	/**
	 * 
	 * Update one of the product items that is called in the `Product` class and `init_form()` function : JS function is update_product(product)
	 * 
	*/
	if($_POST['action'] == 'get_shipping_price_list'){
	    // pdt-shipping.php => get_price_list()
	}
	
	/**
	 * 
	 * Update one of the product items that is called in the `Product` class and `init_form()` function : JS function is update_product(product)
	 * 
	*/
	if($_POST['action'] == 'save_shipping_price'){
        
        // pdt-shipping.php => save_shipping()
	   
	}
	
	/**
	 * 
	 * Get all of the woocommerce products that is called in the `CreatedProduct` Class and `RenderPage()` Function : JS function is get_woocommerce_products()
	 * 
	 */ 
	 if($_POST['action'] == 'woocommerce_products'){
	    //lass-pdt-wc-prodcut.php => get_woocommerce_products()
        
	 }
	 
	 /**
	 * 
	 * Get update one item of the woocommerce products that is called in the `CreatedProduct` Class and `RenderPage()` Function : JS function is update_woocommerce_product()
	 * 
	 */ 
	 if($_POST['action'] == 'update_woocommerce_product'){
	     // class-pdt-wc-prodcut.php => update_woocommerce_product
        
	 }

    if( 'clear_all_shipping_cost' === $_POST['action'] ) {
        // pdt-shipping.php => clear_all_shipping_cost()
    }
    if( 'clear_all_wage_cost' === $_POST['action'] ) {

        // pdt-shipping.php => clear_all_wage_cost()
       
    }
    if( 'clear_all_other_cost' === $_POST['action'] ) {
        // pdt-shipping.php => clear_all_other_cost()
        
    }
    if( 'clear_all_material_price' === $_POST['action'] ) {
        // class-pdt-product.php => clear_all_material_price()
    }
    if( 'clear_all_profit' === $_POST['action'] ) {
        // class-pdt-category-profit=>clear_all_profit()
    }

    if( 'check_item_price' === $_POST['action'] ) {
        // class-pdt-product.php => check_item_price()
        
    }

?>