var popupNotification = jQuery("#popupNotification").kendoNotification({
        appendTo: "#appendto",
        // autoHideAfter: 50000
    }).data("kendoNotification");
	let counter = 1;
    var selector = document.getElementById("form[productName][1]");
    /**
     * number 
     */ 
    var num_of_pages = '';// jQuery('#per_page_number').val()?jQuery('#per_page_number').val():10;
    //Add comma to numbers every three digits
    function addComma(str){ 
        return str.split(',').join('').replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
    jQuery("#form\\[productPrice\\]\\[1\\]").on('input', (e)=>{
        e.target.value = e.target.value.replace(/[^0-9\.]+/g, '');
        e.target.value = addComma(e.target.value);
    });


	
	/**
	 * Description: Get all woocommerce products created via AJAX
	 */ 
	function get_woocommerce_products (options){
	    jQuery("#loading-container").show();
	    jQuery.ajax({
	        url: '<?= MY_PLUGIN_ADDRESS ?>includes/config.php',
	        type:'post',
	        data: {
	            action: 'woocommerce_products',
	            product_type: options.product_type,
	            product_status: options.product_status,
	            num_per_page: options.num_per_page,
	            page_number: options.page_number
	        },
	        async: true,
	        success: function(data){
	            jQuery("#loading-container").hide();
	            // jsGrid.locale("fr");
	            var result = JSON.parse(data);
	            
	            var editor; // use a global for the submit and return data rendering in the examples
                
                function myDisplayer(some) {
                  alert( some);
                }
                 var grid = jQuery("#jsGrid").data("kendoGrid");
                 if (typeof grid != 'undefined' )
                    jQuery('#jsGrid').kendoGrid('destroy').empty();
                    
                let myPromise = new Promise(function(myResolve, myReject) {
                    myResolve(result['data']);
                });
                
                myPromise.then(
                  function(value) {
                        generateKendoGrid(value);
                        generatePagination (
                            parseInt(result['num_of_pages']), 
                            options.num_per_page,
                            // parseInt(jQuery("#per_page_number").val() != ""?jQuery("#per_page_number").val():10),
                            options.page_number
                        );
                  },
                  function(error) {myDisplayer(error);}
                );
	        }
	    });
	    function generateKendoGrid(data){
	        var gridDataSource = new kendo.data.DataSource({
              data: data,
            //   transport:{
            //     update:''  
            //   },
            batch: true,
              schema: {
                model: {
                  fields: {
                    id: { type: "number" },
                    title: { type: "string", editable: false, nullable: false  },
                    old_price: { type: "string",editable: false, nullable: false },
                    suggested_price: { type: "string", editable: false, nullable: false },
                    regular_price: { type: "number" },
                    checkbox: { type: "bool" },
                    isSuggestedPrice: {type: "boolean"}
                  }
                }
              },
              pageSize: 20,
              sort: {
                field: "OrderDate",
                dir: "desc"
              },
              
                change:function(e) {
                   
                  if (e.action == "itemchange" && e.field == "regular_price")
                  {
                    // alert("Product Name Changed");
                    var editItemModelId = e.items[0].id;
                    var grid = jQuery("#jsGrid").data("kendoGrid");
                    var dataItem = grid.dataSource.get(editItemModelId);
                    
                    dataItem.set("isChanged", 'regular_price');
                    // dataItem.set("old_price", true);
                  }
                }
                
            });
            
             const copyToClipboard = str => {
                const el = document.createElement('textarea');
                el.value = str;
                el.setAttribute('readonly', '');
                el.style.position = 'absolute';
                el.style.left = '-9999px';
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            };
    
            const bindCopyToGrid = (delimiter) => {
                jQuery("#jsGrid").on("copy", function (e) {
                    const selected = myGrid.select();
                    var selectedRowsData = "";
                    for (let i = 0; i < selected.length; i++) {
                        var newRowData = '';
                        myGrid.columns.filter(x => x.hidden == undefined || 
                                              x.hidden == false).forEach((filter) => {
                            newRowData = newRowData == '' ? 
                            myGrid.dataItem(selected[i])[filter.field] : newRowData + 
                            delimiter + myGrid.dataItem(selected[i])[filter.field]
                        });
                                         
                        if (selectedRowsData.indexOf(newRowData) == -1)
                            selectedRowsData = selectedRowsData == "" ? 
                            selectedRowsData + newRowData : selectedRowsData + 
                            "\r\n" + newRowData;                   
                    }
                  
                    copyToClipboard(selectedRowsData);
                });
            }
    
            const embedMultiSelectGrid = () => {
                jQuery('#jsGrid tr[role="row"]').off('click');
                jQuery('#jsGrid tr[role="row"]').on("click", function () {
                    jQuery("#jsGrid").off("copy");
                    var sel = getSelection().toString();
                    if (!sel) {
                        var el = jQuery("#jsGrid"),
                            grid = el.data("kendoGrid"),
                            row = el.find("tbody>tr[data-uid=" + this.dataset.uid + "]");
    
                        if (row.length > 0) {
                            if (row.hasClass('k-state-selected')) {
                                row.removeClass('k-state-selected');
                            }
                            else {
                                grid.select(row);
                            }
                        }
    
                        bindCopyToGrid(",");
                    }
                });
            };
             

            jQuery("#jsGrid").kendoGrid({
              dataSource: gridDataSource,
            //   groupable: true,
              scrollable: {
                virtual: true
              },
                pageable: {
                    numeric: false,
                    previousNext: false
                },
            //   group:{ field: "title", dir: "asc"},
              selectable: "multiple",

              dataBound: function(e) {
                //embedMultiSelectGrid();
                // grid.unbind("dataBound");
                dataView = this.dataSource.view();
                for (var i = 0; i < dataView.length; i++) {
                    if (dataView[i].type=='main') {
                        var uid = dataView[i].uid;
                        jQuery("#jsGrid tbody").find("tr[data-uid=" + uid + "]").addClass("alarm");  //alarm's in my style and we call uid for each row
                        jQuery("#jsGrid tbody").find("tr[data-uid=" + uid + "] .k-checkbox").remove();
                        jQuery("#jsGrid tbody").find("tr[data-uid=" + uid + "] .k-button").remove();
                    }
                }
              },
              
            //   height: 400,
              pageable: false,
              sortable: true,
              filterable: true,
              editable: true,
            //   editable: "inline",
              persistSelection: true,
              columns: [{ 
                      field:'checkbox',
                      selectable: true, 
                      width: "50px",
                      attributes: {class: "select-checkbox"}
                },{
                field:"id",
                title: "شناسه",
                hidden: true,
                editable: false,
              },{
                field:"isChanged",
                
                hidden: true,
                editable: false,
              }
              , {
                field: "title",
                title: "عنوان",
                
                editable: false,
                // template: "<span style='color:#= getFreightColor(Freight) #'>#= Freight #</span>"
              }, {
                field: "old_price",
                title: "قیمت قبلی",
                editor: null,
                editable: false,
                editable: false, nullable: false,
                template:function(item){
                    
                    if(item.old_price != null)
                        return addComma(item.old_price.toString());
                    else
                        return '';
                }
              }, {
                field: "suggested_price",
                title: "قیمت پیشنهادی",
                editable: false,
                template:function(item){
                    if(item.suggested_price != null)
                        return addComma(item.suggested_price.toString());
                    else
                        return '';
                }
              },{ 
                  field: "isSuggestedPrice", 
                  title: "قیمت پیشنهادی جایگزین شود؟", 
                   
                  template: '<input type="checkbox" #= isSuggestedPrice ? \'checked="checked"\' : "" # class="chkbx k-checkbox k-checkbox-md k-rounded-md" />', 
                   attributes: {class: "k-text-center"} ,
                
              },{
                field: "regular_price",
                title: "قیمت فعلی",
                template:function(item){
                    if(item.regular_price != null)
                        return addComma(item.regular_price.toString());
                    else
                        return '';
                },
                
              },
              { command: { text: "به روزرسانی", click: update_product }, title: " ", width: "180px" }
              ]
            });
            //#=dirtyField(data,"Discontinued")#
            jQuery("#jsGrid .k-grid-content").on("click", "input.chkbx", function(e) {
                // alert();
                var grid = jQuery("#jsGrid").data("kendoGrid"),
                    dataItem = grid.dataItem(jQuery(e.target).closest("tr"));
                if(typeof dataItem != 'undefined'){
                    dataItem.set("isSuggestedPrice", this.checked);
                    if(this.checked)
                        dataItem.set("isChanged", 'suggested');
                    else
                        dataItem.set("isChanged", '');
                }
            });
            
            
	    }
	    jQuery("#jsGrid .k-grid-content").on("click", ".k-checkbox", function(e) {
            
            var checked = this.checked,
            row = jQuery(this).closest("tr"),
            grid = jQuery("#jsGrid").data("kendoGrid");
            
            let  dataItem = grid.dataItem(row);
    
            // checkedIds[dataItem.id] = checked;
            if (checked) {
                
                //-select the row
                row.addClass("k-state-selected");
                } else {
                //-remove selection
                row.removeClass("k-state-selected");
            }
            
        });
	}
    function checkItemPrice( product_id ) {
        
        let deffer = jQuery.Deferred();

        jQuery.ajax({
            url: '<?= MY_PLUGIN_ADDRESS ?>includes/config.php',
            type:'post',
            data: {
                action: 'check_item_price',
                product_id: product_id
            },
            dataType:'json',
            success: function( data ) {
                if ( ! data.success ) {
                    
                    _.each( data.data, function( item, index ){
                        popupNotification.show(
                            "قیمت ماده مصرفی  " + item + " وارد نشده است",
                            "error"
                        );
                    });

                    deffer.reject();
                } else {
                    deffer.resolve();
                }
            }
        });

        return deffer.promise();
    }
	function update_product(e){
	    e.preventDefault();
	    
        var dataItem = this.dataItem(jQuery(e.currentTarget).closest("tr"));

        let send_data = [];
        if(typeof dataItem.isChanged != 'undefined' && dataItem.isChanged.trim() != ""){
            let obj = {
                'product_id': dataItem.id,
                'type': dataItem.type,
                '_o_price': dataItem.regular_price,
                '_sug_price': dataItem.suggested_price
            };
            if(dataItem.isChanged == 'suggested') {

                 /* If `isSuggestedPrice` selected `suggessted price` Will be replaced */
                obj._price = dataItem.isSuggestedPrice ? dataItem.suggested_price : dataItem.regular_price;

                if(obj._price.trim() == "")
                {
                    popupNotification.show('<?php _e('این محصول قیمت پیشنهادی ندارد', 'product-details')?>', "error");
                    return;
                }

                jQuery.
                    when( checkItemPrice( dataItem.id ) ).
                    done(function(res){
                        send_data.push(obj);
                        update_woocommerce_product( 
                            jQuery( "#product_type" ).val(), 
                            'array', 
                            send_data 
                        );
                    }).fail(function(rej){

                    });

            } else {
                obj._price= dataItem.regular_price;/* If `isSuggestedPrice` was not selected `suggessted price` Will be replaced */
                send_data.push(obj);
                update_woocommerce_product(jQuery("#product_type").val(), 'array', send_data);
            }
                
            
        }else{
            popupNotification.show('<?php _e('هیچ مقداری تغییر نکرده است', 'product-details')?>', "error");
        }
	}
	function generatePagination (numofpages, per_page, cur_page){
	    jQuery("#pagination_container").pagination({
            items: numofpages*per_page,
            itemsOnPage: per_page,
            prevText: "&laquo;",
            nextText: "&raquo;",
            currentPage: cur_page,
            onPageClick: function (pageNumber) {
                get_woocommerce_products({
        		    product_type: jQuery('#product_type').val(),
                    product_status: jQuery('#product_status').val(),
                    num_per_page: per_page,
                    page_number: pageNumber,
        		});
            },
        });
	}
	
	
	/**
	 * Description: Get all woocommerce products when `product type` or `product status` or `per page number` or `product number` field has changed
	 */ 
	jQuery('#product_type,#product_status,#per_page_number,#page_number').change((event)=>{
	    get_woocommerce_products({
		    product_type: jQuery('#product_type').val(),
            product_status: jQuery('#product_status').val(),
            num_per_page: 20, //jQuery('#per_page_number').val(),
            page_number: jQuery('#page_number').val()
		});
	});

	get_woocommerce_products({
	    product_type: jQuery('#product_type').val(),
        product_status: jQuery('#product_status').val(),
        num_per_page: 20,// jQuery('#per_page_number').val(),
        page_number: jQuery('#page_number').val()
	});
	
	jQuery('#product_selection').on('change',function(e){
	   check_product_selection(e.target.checked);
	});
// 	jQuery("#how_calc_price-continaer").hide();
	jQuery("#increase_rate_input_container").hide();
    jQuery("#value_type-continaer").hide();
    jQuery("#new_price_container").hide();
    jQuery("#loading-container").hide();
    
    jQuery("#new_price_value").on('input',(e)=>{
        if(e.target.value.trim() != "")
            jQuery("#group_work_button").show();
        else
            jQuery("#group_work_button").hide();
    });
    
    jQuery('#Increase_rate, input[type=radio][name=how_calc_price]').change(function() {
        check_increase_price_option();
    });
    function check_increase_price_option(){
        let value_type = jQuery('input[type=radio][name=value_type]').val();
        let Increase_rate = jQuery('#Increase_rate').val();
        if(value_type.trim() != "" && Increase_rate.trim() != "")
            jQuery("#group_work_button").show();
        else
            jQuery("#group_work_button").hide();
    }
    
    jQuery('input[type=radio][name=how_calc_price]').change(function() {
        if(this.value == 'increase_price'){
            jQuery("#increase_rate_container").show();
            jQuery("#value_type-continaer").show();
            
        }else{
            jQuery("#increase_rate_container").hide();
            jQuery("#value_type-continaer").hide();
            jQuery('input[type=radio][name=value_type]').prop('checked', false);
            jQuery('#Increase_rate').val("");
        }
        if(this.value == 'suggested-price'){
            jQuery('#group_work_button').show();
        }
        else
            jQuery('#group_work_button').hide();
    });
    jQuery('#how_calc_price').change(function(){
        // alert(jQuery(this).val());
        if(jQuery(this).val() == 'increase_price'){
            jQuery("#increase_rate_container").show();
            jQuery("#value_type-continaer").show();
        }else{
            jQuery("#increase_rate_container").hide();
            jQuery("#value_type-continaer").hide();
            jQuery('input[type=radio][name=value_type]').prop('checked', false);
            jQuery('#Increase_rate').val("");
        }
        if(jQuery(this).val() == 'suggested-price'){
            jQuery('#group_work_button').show();
        }
        else
            jQuery('#group_work_button').hide();
        
        if(jQuery(this).val() == 'new-price')    
            jQuery("#new_price_container").show();
        else{
            jQuery("#new_price_container").hide();
            jQuery("#new_price_value").val("");
        }
            
        if(jQuery(this).val() == 'increase_price'){
            jQuery("#increase_rate_input_container").show();
            jQuery("#value_type-continaer").show();
        }    
        else{
            jQuery("#increase_rate_input_container").hide();
            jQuery("#value_type-continaer").hide();
        }    
            
            
            
    })


	function check_product_selection(val){
	    if(val){
	        jQuery("#how_calc_price-continaer").show();
	        jQuery("#save_changes_button").hide();
	   }else{
	        jQuery("#how_calc_price-continaer").hide();
	        jQuery("#group_work_button").hide();
	        jQuery("#save_changes_button").show();
	   }
	}
	
	function saveChanges(){
	   
	    
        // let product_selection = jQuery('#product_selection').is(":checked");
        
        let how_calc_price = jQuery('#how_calc_price').val();
        
        let product_type = '';
        // if(product_selection){
        var grid = jQuery("#jsGrid").data("kendoGrid");
       
        // update_woocommerce_product(product_type, 'array', send_data);
        var rows = grid.select();
        let send_data = [];
        rows.each(function(index, row) {
            var item = grid.dataItem(row);
            
            let obj = {};
            obj = {
                'product_id': item.id,
                'type': item.type,
                '_o_price': item.regular_price,
                '_sug_price': item.suggested_price
            }
            product_type = item.type;
            if(how_calc_price == 'suggested-price'){
                obj._price = item.suggested_price;
                if(item.suggested_price.trim() == ""){
                    popupNotification.show('<?php _e('یکی از محصولات قیمت پیشنهادی ندارد', 'product-details')?>', "error");

                    return;
                }
            }else if(how_calc_price == 'new-price'){
                
                obj._price = jQuery("#new_price_value").val();// item.regular_price;
            }else if(how_calc_price == 'increase_price'){
                let increase_rate = parseInt(jQuery("#Increase_rate").val());
                
                if(jQuery('input[type=radio][name=value_type]:checked').val() == "currency"){
                    obj._price = parseInt(item.regular_price)+increase_rate;
                }
                else if(increase_rate>100){
                    popupNotification.show('<?php _e('    میزان افزایش بیشتر از 100 نمیتواند باشد', 'product-details')?>', "error");
                
                    return;
                }
                else{
                    obj._price = parseFloat(parseInt(item.regular_price)+((parseInt(item.regular_price)*increase_rate)/100));
                }
            }
            send_data.push(obj);
          // selectedItem has EntityVersionId and the rest of your model
        });
        
        if( send_data.length == 0){
            popupNotification.show('<?php _e('لطفا حداقل یکی از محصولات رو انتخاب کنید', 'product-details')?>',
            "info");
            return;
        }
            
        update_woocommerce_product(product_type, 'array', send_data);
	}
	
	 
	 
	//Update product 
	function update_woocommerce_product(product_type, save_type,products){
	    jQuery("#loading-container").show();

	     jQuery.ajax({
	        url: '<?= MY_PLUGIN_ADDRESS ?>includes/config.php',
	        type:'post',
	        data: {
	            action: 'update_woocommerce_product',
	            products,
	            product_type,
	            save_type
	        },
	        dataType:'json',
	        async: true,
	        success: function(data){
	            jQuery("#loading-container").hide();
	           // kendo.alert("<?php //_e('محصول به روزرسانی شد', 'product-details');//The product was updated ?>");
	            popupNotification.show('<?php _e('محصول به روزرسانی شد', 'product-details')?>', "success");
				// alert("");
				get_woocommerce_products({
				    product_type: jQuery('#product_type').val(),
		            product_status: jQuery('#product_status').val(),
		            num_per_page: 20, //jQuery('#per_page_number').val(),
		            page_number: jQuery('#page_number').val()
				});
	        }
	    });
	}
	
	jQuery("#group_work_button").hide();