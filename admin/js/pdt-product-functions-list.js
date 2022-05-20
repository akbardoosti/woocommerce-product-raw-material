var popupNotification = jQuery("#popupNotification").kendoNotification({
        appendTo: "#appendto",
        // autoHideAfter: 50000
    }).data("kendoNotification");
	//Update product 
	function update_product(product){
		let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	    jQuery.ajax({
	        url: plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'update_product',
	            product
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
	           	if( data ) {
				 	popupNotification.show('ماده اولیه به روزرسانی شد',"success");
	           	} else {
	           		popupNotification.show('مشکلی پیش آمد',"error");
	           	}
	        }
	    });
	}

	//Delete product 
	function delete_product(item_id){
		let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	     jQuery.ajax({
	        url:  plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'delete_product',
	            product_id: item_id
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
	        	if ( data ) {
		            popupNotification.show( 'ماده اولیه مورد نظر حذف شدن', "success" );
		            get_all_products();
	        	} else {
	           		popupNotification.show('مشکلی پیش آمد',"error");
	        	}
	        }
	    });
	}

	// Save shipping price
	function save_shipping_price( row ) {
		let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	    jQuery.ajax({
	        url: plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'save_shipping_price',
	            
	            product_id: row['product_id'],
	            wage_cost: row['wage_cost'],
	            shipping_cost: row['shipping_cost'],
	            other_costs: row['other_costs'],
	        },
	        dataType: 'json',
	        async: true,
	        success: function(data){
	            if( data ) {
    	            popupNotification.show('به روزرسانی انجام شد',"success");
	            } else {
	           		popupNotification.show('مشکلی پیش آمد',"error");
	        	}
	        }
	    });
	}

	// Update category 
	function update_category_profit(category){
		let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	     jQuery.ajax({
	        url: plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'update_category_profit',
	            category
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
				if( data ) {
					popupNotification.show('به روزرسانی انجام شد', "success");
				} else {
	           		popupNotification.show('مشکلی پیش آمد',"error");
	        	}
	        }
	    });
	}

	function clearAllShippingCost(e) {
		e.stopPropagation();
		let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	    jQuery.ajax({
	        url: plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'clear_all_shipping_cost',
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
				if( data ) {
					showSuccessMessage();
					get_shipping_price_list({
				    	product_type: jQuery('#product_type').val(),
			        	num_per_page: 10,
						page_number: 1
				    });
				} else {
	           		showErrorMessage();
	        	}
	        }
	    });
	}
	function clearAllWageCost(e) {
		e.stopPropagation();

		let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	    jQuery.ajax({
	        url: plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'clear_all_wage_cost',
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
				if( data ) {
					showSuccessMessage();
					get_shipping_price_list({
				    	product_type: jQuery('#product_type').val(),
			        	num_per_page: 10,
						page_number: 1
				    });
				} else {
	           		showErrorMessage();
	        	}
	        }
	    });
	}
    function clearAllOtherCost(e) {
    	e.stopPropagation();
    	let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	    jQuery.ajax({
	        url: plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'clear_all_other_cost',
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
				if( data ) {
					showSuccessMessage();
					get_shipping_price_list({
				    	product_type: jQuery('#product_type').val(),
			        	num_per_page: 10,
						page_number: 1
				    });
				} else {
	           		showErrorMessage();
	        	}
	        }
	    });
    }
	function clearAllMaterialPrice(e) {
		e.stopPropagation();
		jQuery( '#loading-container' ).show();

		let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	    jQuery.ajax({
	        url: plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'clear_all_material_price',
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
				jQuery( '#loading-container' ).hide();
				if( data ) {
					get_all_products();
					showSuccessMessage();
				} else {
	           		showErrorMessage();
	        	}
	        }
	    });
	}
	function clearAllProfit(e) {
		e.stopPropagation();
		jQuery( '#loading-container' ).show();

		let plugin_address = '<?= MY_PLUGIN_ADDRESS ?>';
	    jQuery.ajax({
	        url: plugin_address + 'includes/config.php',
	        type:'post',
	        data: {
	            action: 'clear_all_profit',
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
				jQuery( '#loading-container' ).hide();
				if( data ) {
					get_all_categories();

					showSuccessMessage();
				} else {
	           		showErrorMessage();
	        	}
	        }
	    });
	}

	function showSuccessMessage() {
		popupNotification.show('داده های ذخیره شده پاک شدند', "success");
	}

	function showErrorMessage() {
		popupNotification.show('مشکلی پیش آمد',"error");
	}