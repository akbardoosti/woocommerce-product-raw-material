function category_list_kendo(data) {
		var grid = jQuery("#category-list").data("kendoGrid");
        if (typeof grid != 'undefined' )
            jQuery('#category-list').kendoGrid('destroy').empty();
		var gridDataSource = new kendo.data.DataSource({
			data: data,
			//   transport:{
			//     update:''  
			//   },
			batch: true,
			schema: {
				model: {
					fields: {
						term_id: {
							type: "number"
						},
						name: {
							type: "string",
							editable: false,
							nullable: false
						},
						profit: {
							type: "number",
							editable: true,
							nullable: false
						}
					}
				}
			},
			pageSize: 10,
			sort: {
				field: "OrderDate",
				dir: "desc"
			},

		});

		jQuery("#category-list").kendoGrid({
			dataSource: gridDataSource,
			//   groupable: true,

			pageable: {
				numeric: false,
				previousNext: false
			},
			//   group:{ field: "title", dir: "asc"},
			selectable: "multiple",

			dataBound: function(e) {

				dataView = this.dataSource.view();
				for (var i = 0; i < dataView.length; i++) {
					if (dataView[i].type == 'main') {
						var uid = dataView[i].uid;
						jQuery("#category-list tbody").find("tr[data-uid=" + uid + "]").addClass("alarm"); //alarm's in my style and we call uid for each row
						jQuery("#category-list tbody").find("tr[data-uid=" + uid + "] .k-checkbox").remove();
						jQuery("#category-list tbody").find("tr[data-uid=" + uid + "] .k-button").remove();
					}
				}
			},

			//   height: 400,
			pageable: true,
			sortable: true,
			filterable: true,
			editable: true,
			//   editable: "inline",
			persistSelection: true,
			columns: [{
				field: "term_id",
				title: "شناسه",
				hidden: true,
				editable: false,
			}, {
				field: "isChanged",

				hidden: true,
				editable: false,
			}, {
				field: "name",
				title: "عنوان",

				editable: false,
				// template: "<span style='color:#= getFreightColor(Freight) #'>#= Freight #</span>"
			}, {
				field: "profit",
				headerTemplate: '<?php _e('سود(%)',
                        'product-details');?><button onclick=\'clearAllProfit(event)\'><span class="dashicons dashicons-trash"></span></button>',
				// editor: null,
				// editable: true,
				nullable: false,
			}, {
				command:[
					{
						name: "update_product",
						text: 'ویرایش',
						click: function (e) {
							var grid = jQuery("#category-list").data("kendoGrid"),
							data = grid.dataItem(jQuery(e.target).closest("tr"));
							
							if ( data.dirty ) {
								update_category_profit({
									term_id: data.term_id,
									profit: data.profit,
								});
							} else {
								popupNotification.show('مقداری تغییر نکرده است',"error");
							}
						},
						icon: "edit"
					}
				] ,
				title: " ",
				width: "120px"
			}]
		});
		//#=dirtyField(data,"Discontinued")#
		jQuery("#category-list .k-grid-content").on("click", "input.chkbx", function(e) {
			// alert();
			var grid = jQuery("#category-list").data("kendoGrid"),
				dataItem = grid.dataItem(jQuery(e.target).closest("tr"));
			if (typeof dataItem != 'undefined') {
				// dataItem.set("isSuggestedPrice", this.checked);
				// if (this.checked)
				//     dataItem.set("isChanged", 'suggested');
				// else
				//     dataItem.set("isChanged", '');
			}
		});

		jQuery("#category-list .k-grid-content").on("click", ".k-checkbox", function(e) {

			var checked = this.checked,
				row = jQuery(this).closest("tr"),
				grid = jQuery("#category-list").data("kendoGrid");

			let dataItem = grid.dataItem(row);

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


	function  material_list_kendo(data) {
		var grid = jQuery("#jsGrid").data("kendoGrid");
		if (typeof grid != 'undefined' ) {
			jQuery('#jsGrid').kendoGrid('destroy').empty();
		}
		var gridDataSource = new kendo.data.DataSource({
			data: data,
			//   transport:{
			//     update:''  
			//   },
			batch: true,
			schema: {
				model: {
					fields: {
						ID: {
							type: "number"
						},
						NAME: {
							type: "string",
							editable: false,
							nullable: false
						},
						PRICE: {
							type: "number",
							editable: true,
							nullable: false
						}
					}
				}
			},
			pageSize: 10,
			sort: {
				field: "OrderDate",
				dir: "desc"
			},

		});

		jQuery("#jsGrid").kendoGrid({
			dataSource: gridDataSource,
			//   groupable: true,

			pageable: {
				numeric: false,
				previousNext: false
			},
			//   group:{ field: "title", dir: "asc"},
			selectable: "multiple",

			dataBound: function(e) {

				dataView = this.dataSource.view();
				for (var i = 0; i < dataView.length; i++) {
					if (dataView[i].type == 'main') {
						var uid = dataView[i].uid;
						jQuery("#jsGrid tbody").find("tr[data-uid=" + uid + "]").addClass("alarm"); //alarm's in my style and we call uid for each row
						jQuery("#jsGrid tbody").find("tr[data-uid=" + uid + "] .k-checkbox").remove();
						jQuery("#jsGrid tbody").find("tr[data-uid=" + uid + "] .k-button").remove();
					}
				}
			},

			//   height: 400,
			pageable: true,
			sortable: true,
			filterable: true,
			editable: true,
			//   editable: "inline",
			persistSelection: true,
			columns: [{
				field: "ID",
				title: "شناسه",
				hidden: true,
				editable: false,
			}, {
				field: "isChanged",

				hidden: true,
				editable: false,
			}, {
				field: "NAME",
				title: "عنوان",

				editable: false,
				// template: "<span style='color:#= getFreightColor(Freight) #'>#= Freight #</span>"
			}, {
				field: "PRICE",
				headerTemplate: '<?= __('قیمت'."({$currencies[$currency_code]})",
                        'product-details');//Price?><button onclick=\'clearAllMaterialPrice(event)\'><span class="dashicons dashicons-trash"></span></button>',
				// editor: null,
				// editable: true,
				template: function(item) {
                	if (item.PRICE != null)
	                    return addComma(item.PRICE.toString());
	                else
	                    return '';
	            },
				nullable: false,
			}, {
				command: [
					{
						name: "update_product",
						text: 'ویرایش',
						click: function( e ) {
							
							var grid = jQuery("#jsGrid").data("kendoGrid"),
							dataItem = grid.dataItem(jQuery(e.target).closest("tr"));

							if ( dataItem.dirty ) {

								update_product({
									ID: dataItem.ID,
									PRICE: dataItem.PRICE,
									NAME: dataItem.NAME,
								});

							} else {
								popupNotification.show('مقداری تغییر نکرده است',"error");
							}
						},
						icon: "edit"
					},
					{
						name: "delete_product",
						text: 'حذف',
						click: function( e ) {
						// 	 kendo.confirm("Confirm text")
						//         .done(function(){
						// /* The result can be observed in the DevTools(F12) console of the browser. */
						//             console.log("User accepted");
						//         })
						//         .fail(function(){
						// /* The result can be observed in the DevTools(F12) console of the browser. */
						//             console.log("User rejected");
						//         });
							var grid = jQuery("#jsGrid").data("kendoGrid"),
							dataItem = grid.dataItem(jQuery(e.target).closest("tr"));
							delete_product(dataItem.ID);
						},
						icon: "delete"
					}
				],
				title: " ",
				width: "180px"
			}]
		});
		//#=dirtyField(data,"Discontinued")#
		jQuery("#jsGrid .k-grid-content").on("click", "input.chkbx", function(e) {
			// alert();
			var grid = jQuery("#jsGrid").data("kendoGrid"),
				dataItem = grid.dataItem(jQuery(e.target).closest("tr"));
			if (typeof dataItem != 'undefined') {
				// dataItem.set("isSuggestedPrice", this.checked);
				// if (this.checked)
				//     dataItem.set("isChanged", 'suggested');
				// else
				//     dataItem.set("isChanged", '');
			}
		});

		jQuery("#jsGrid .k-grid-content").on("click", ".k-checkbox", function(e) {

			var checked = this.checked,
				row = jQuery(this).closest("tr"),
				grid = jQuery("#jsGrid").data("kendoGrid");

			let dataItem = grid.dataItem(row);

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




function product_list_kendo(data) {	
	var grid = jQuery("#product-list").data("kendoGrid");
	if (typeof grid != 'undefined' )
	    jQuery('#product-list').kendoGrid('destroy').empty();

    var gridDataSource = new kendo.data.DataSource({
        data: data,
        //   transport:{
        //     update:''  
        //   },
        batch: true,
        schema: {
            model: {
                fields: {
                    product_id: {
                        type: "number"
                    },
                    title: {
                        type: "string",
                        editable: false,
                        nullable: false
                    },
                    shipping_cost: {
                        type: "string",
                        editable: true,
                        nullable: false
                    },
                    wage_cost: {
                        type: "string",
                        editable: true,
                        nullable: false
                    },
                    other_costs: {
                        type: "number",
                        editable: true,
                    },
                }
            }
        },
        pageSize: 10,
        sort: {
            field: "OrderDate",
            dir: "desc"
        },

    });

    jQuery("#product-list").kendoGrid({
        dataSource: gridDataSource,
        //   groupable: true,
        
        pageable: {
            numeric: false,
            previousNext: false
        },
        //   group:{ field: "title", dir: "asc"},
        selectable: "multiple",

        dataBound: function(e) {
            
            dataView = this.dataSource.view();
            for (var i = 0; i < dataView.length; i++) {
                if (dataView[i].type == 'main') {
                    var uid = dataView[i].uid;
                    jQuery("#product-list tbody").find("tr[data-uid=" + uid + "]").addClass("alarm"); //alarm's in my style and we call uid for each row
                    jQuery("#product-list tbody").find("tr[data-uid=" + uid + "] .k-checkbox").remove();
                    jQuery("#product-list tbody").find("tr[data-uid=" + uid + "] .k-button").remove();
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
            field: "product_id",
            title: "شناسه",
            hidden: true,
            editable: false,
        }, {
            field: "isChanged",

            hidden: true,
            editable: false,
        }, {
            field: "title",
            title: "عنوان",

            editable: false,
            // template: "<span style='color:#= getFreightColor(Freight) #'>#= Freight #</span>"
        }, {
            field: "shipping_cost",
            headerTemplate: '<?php _e('هزینه ارسال ('.$currency_name.')', 'product-details');//Price?><button onclick=\'clearAllShippingCost(event)\'><span class="dashicons dashicons-trash"></span></button>',
            // editor: null,
            // editable: true,
            nullable: false,
            template: function(item) {
                if (item.shipping_cost != null)
                    return addComma(item.shipping_cost.toString());
                else
                    return '';
            }
        }, {
            field: "wage_cost",
            headerTemplate: '<?php _e('هزینه دستمزد('.$currency_name.')', 'product-details');//Price?><button onclick=\'clearAllWageCost(event)\'><span class="dashicons dashicons-trash"></span></button>',
            // editable: true,
            template: function(item) {
                if (item.wage_cost != null)
                    return addComma(item.wage_cost.toString());
                else
                    return '';
            }
        }, {
            field: "other_costs",
            headerTemplate: '<?php _e('سایر هزینه ها  ('.$currency_name.')', 'product-details');//Price?> <button onclick=\'clearAllOtherCost(event)\'><span class="dashicons dashicons-trash"></span></button>',
            template: function(item) {
                if (item.other_costs != null)
                    return addComma(item.other_costs.toString());
                else
                    return '';
            }
            // editable: true,

            // template: '<input type="checkbox" #= isSuggestedPrice ? \'checked="checked"\' : "" # class="chkbx k-checkbox k-checkbox-md k-rounded-md" />',
            // attributes: {
            //     class: "k-text-center"
            // },

        }, {
            command: [
				{
					name: "update_product",
					text: 'به روزرسانی',
					click: function ( e ) {
						var grid = jQuery("#product-list").data("kendoGrid"),
						data = grid.dataItem(jQuery(e.target).closest("tr"));
						if ( data.dirty ) {
							
							save_shipping_price( {
								product_id: data.product_id,
								wage_cost: data.wage_cost,
								shipping_cost: data.shipping_cost,
								other_costs: data.other_costs,
							});
							
						} else {
							popupNotification.show('مقداری تغییر نکرده است',"error");
						}
						
					},
					icon: "edit"
				}
			],
            title: " ",
            width: "180px"
        }]
    });
    //#=dirtyField(data,"Discontinued")#
    jQuery("#product-list .k-grid-content").on("click", "input.chkbx", function(e) {
        // alert();
        var grid = jQuery("#product-list").data("kendoGrid"),
            dataItem = grid.dataItem(jQuery(e.target).closest("tr"));
        if (typeof dataItem != 'undefined') {
            // dataItem.set("isSuggestedPrice", this.checked);
            // if (this.checked)
            //     dataItem.set("isChanged", 'suggested');
            // else
            //     dataItem.set("isChanged", '');
        }
    });

    jQuery("#product-list .k-grid-content").on("click", ".k-checkbox", function(e) {

        var checked = this.checked,
            row = jQuery(this).closest("tr"),
            grid = jQuery("#product-list").data("kendoGrid");

        let dataItem = grid.dataItem(row);

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