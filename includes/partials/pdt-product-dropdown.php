<?php
if($type == 'simple'):
?>
 <p class="stock_status_field form-field ">
<?php else: ?>
 <p class="form-row form-row-full  form-field ">
<?php endif; ?>   
<label for="">مواد مصرفی در محصول</label>
    <!-- <span class="woocommerce-help-tip"></span>	 -->
    
    <button onclick=<?= $type=='simple'? 'add_dropdown_item()':"add_dropdown_item_{$id}()"; ?> type="button" style="border:none;background-color:#04d156;cursor:pointer;" title="افزودن آیتم">
        <span class="dashicons dashicons-plus"></span>
    </button>
    <div id=<?= $type=='simple'?"product_item_dropdown_container":"variable_item_dropdown_container_{$id}"?>>
        <?php
            /*
             * Get the pre-saved items for this product
            */
            $post_id = get_the_ID();
            if($type == 'simple'):
                /* If the type of product is simple, execute this code */
                $sql = "SELECT wpdet.*, wpitms.product_number as num FROM {$table_prefix}pd_product_items AS wpitms 
                LEFT JOIN  `{$table_prefix}pd_product_details` wpdet ON wpitms.product_id = wpdet.`ID`
                WHERE wpitms.`post_id` = '$post_id'";
            else:
                /* If the type of product is variable, execute this code */
                $sql = "SELECT wpdet.*, wpitms.product_number as num FROM {$table_prefix}pd_product_items AS wpitms 
                LEFT JOIN  `{$table_prefix}pd_product_details` wpdet ON wpitms.product_id = wpdet.`ID`
                WHERE wpitms.`variation_id` = '$id'";
            endif;
            
            $items = $wpdb->get_results($sql, ARRAY_A );
            /* End of getting the items*/
            
            /* Load all items saved in product details */
            $select_options = $wpdb -> get_results("SELECT * FROM `{$table_prefix}pd_product_details`", ARRAY_A );
            
            $row_counter = 1;
            foreach($items as $item):
                if($type == 'simple'):?>
                    <div id="single_item_container_product_dropdown_<?= $row_counter ?>" style="display:flex;margin-top:<?= $row_counter>1?'5px':'0'; ?>">
                <?php else: ?>
                    <div id="single_item_container_variable_dropdown_<?= $id.'_'.$row_counter ?>" style="display:flex;margin-top:<?= $row_counter>1?'5px':'0'; ?>">
                <?php endif; ?>

                <span style="width: 13px;margin-right: 12px;"><?= $row_counter ?></span>
                <!--  -->
                <?php if($type == 'simple'):?>
                    <select class="product_item  short"  name="product_item[<?= $row_counter ?>][name]" placeholder="انتخاب کنید" 
                data-row=<?= $row_counter ?> id="#product_dropdown_<?= $row_counter ?>">
                <?php else: ?>
                    <select class="variable_item_<?= $id ?>  short"  name="variable_item[<?= $id ?>][<?= $row_counter ?>][name]" placeholder="انتخاب کنید" 
                data-row=<?= $row_counter ?> id="#variable_dropdown_<?= $id.'_'.$row_counter ?>">
                <?php endif; ?>
                        <?php 
                            foreach($select_options as $row):

                                if($row['ID'] == $item['ID'])
                                    echo "<option selected='selected' value={$row['ID']}>{$row['NAME']}</option>";
                                else
                                    echo "<option  value={$row['ID']}>{$row['NAME']}</option>";
                            
                            endforeach;
                        ?>
                    </select>

                <?php if($type == 'simple'):?>
                    <input class="num_of_product" value="<?= $item['num'] ?>" id="num_of_product_dropdown_<?php echo $row_counter; ?>" name="product_item[<?php echo $row_counter; ?>][num]" placeholder="تعداد" type="number" min="1" max="200" style="max-width:50px;margin-right: 3px;"/>
                <?php else: ?>
                    <input class="num_of_product_<?= $id ?>" value="<?= $item['num'] ?>" id="num_of_variable_dropdown_<?php echo $id.'_'.$row_counter; ?>" name="variable_item[<?= $id ?>][<?php echo $row_counter; ?>][num]" placeholder="تعداد" type="number" style="max-width:50px;margin-right: 3px;" min="1" max="200"/>
                <?php endif; ?>

                <?php if($type == 'simple'):?>
                    <span data-price=<?= $item['PRICE'] ?> data-row=<?= $row_counter ?> class="item_price item_price_product_dropdown_<?php echo $row_counter; ?>" style="margin-top: 7px; margin-right: 4px;min-width:10px;">
                        
                    </span>
                <?php else: ?>
                    <span data-price=<?= $item['PRICE'] ?> data-row=<?= $row_counter ?> class="item_price_<?= $id ?> item_price_variable_dropdown_<?php echo $id.'_'.$row_counter; ?>" style="margin-top: 7px; margin-right: 4px;min-width:10px;">
                        
                    </span>
                <?php endif; ?>

                <?php if($type == 'simple'):?>
                    <button type="button" onclick="remove_product_item(<?php echo $row_counter?>)" style="border:none; color:red;background:transparent;cursor:pointer;">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                <?php else: ?>
                    <button type="button" onclick="remove_product_item_<?= $id ?>(<?php echo $row_counter?>)" style="border:none; color:red;background:transparent;cursor:pointer;">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                <?php endif; ?>
            </div>
        <?php
            $row_counter ++;
            endforeach;
        ?>
    </div>
</p>


<script type="text/javascript">


    var select_options = [];
    
    <?php if($type == 'simple'):?>
        var dropdown_container = <?= $row_counter ?>;
    <?php else: 
        echo "var dropdown_container_{$id} =  {$row_counter};";
     endif;?>
    var currency_name = "<?php echo $currencies[ $currency_code ]; ?>";
    
    /* Load all data into select_options variable */
    jQuery.ajax({
        type:'post',
        url:"<?= MY_PLUGIN_ADDRESS ?>includes/config.php",
        dataType:'json',
        data:{
            action: 'get_all_products'
        },
        success: function(data){
            select_options = data;
            
            /* Checks if this product already has items and increase one item to dropdown_container variable */
            if(dropdown_container == 1){
                dropdown_container++;
                /* Load first dropdown */
                add_to_dropdown('product_dropdown_1',1);
            }
        }
    });
    
    <?php if($type == 'simple'):?>
        /** These codes generate js script for simple product */

        /* Add product item */
        function add_dropdown_item(){
            add_to_dropdown('product_dropdown_'+dropdown_container, dropdown_container++);
        }

        /* Add product dropdown html */
        function add_to_dropdown(id, row){
            let dropdown = `<div id="single_item_container_${id}" style="display:flex;margin-top:${row>1?'5px':'0'}">
                <span style="width: 13px;margin-right: 12px;">${row}</span><select class="product_item select short" name="product_item[${row}][name]" placeholder="انتخاب کنید" data-row=${row} id="#${id}">`;
            dropdown += `<option  value="">انتخاب کنید ...</option>`;
            select_options.map((item, index)=>{
            dropdown += `<option  value="${item.ID}">${item.NAME}</option>`;
            });
            dropdown += `</select><input class="num_of_product" id="num_of_${id}" min = "1" max = "200" name="product_item[${row}][num]" placeholder="تعداد" type="number" style="max-width:50px;margin-right: 3px;"/><span data-row=${row} class="item_price item_price_${id}" style="margin-top: 7px; margin-right: 4px;min-width:10px;">
                </span>
                <button type="button" onclick="remove_product_item(${row})" style="border:none; color:red;background:transparent;cursor:pointer;"><span class="dashicons dashicons-trash"></span></button>
            </div>`;
            
            jQuery("#product_item_dropdown_container").append(dropdown);
        
            /* These codes add change event to dropdown item fields */
            jQuery('.product_item').change((e)=>{
                let price = get_item_price(e.target.value);
                
                // jQuery(`.item_price_product_dropdown_${e.target.dataset.row}`).text(price.toString()+`(${currency_name})`);
                jQuery(`.item_price_product_dropdown_${e.target.dataset.row}`).attr('data-price',price.replace(/,/gm,''));

                
                calculate_product_price();
            });
            
            /* These codes add change event to number of item fields */
            jQuery('.num_of_product').change((e)=>{
                calculate_product_price();
            });
        }

        /* These codes add change event to dropdown item fields */
        jQuery('.product_item').change((e)=>{
            let price = get_item_price(e.target.value);
            
            // jQuery(`.item_price_product_dropdown_${e.target.dataset.row}`).text(price.toString()+`(${currency_name})`);
            jQuery(`.item_price_product_dropdown_${e.target.dataset.row}`).attr('data-price',price.replace(/,/gm,''));

            calculate_product_price();
        });

        /* These codes add change event to number of item fields*/
        jQuery('.num_of_product').change((e)=>{
            calculate_product_price();
        });
        function remove_product_item(id){
            if(id<dropdown_container-1){
                alert('امکان حذف از ردیف های میانی نیست');
                return;
            }
            dropdown_container--;
            jQuery(`#single_item_container_product_dropdown_${id}`).remove();
            calculate_product_price();
        }

        /** Calculate sum of  product items price for simple products */
        function calculate_product_price(){
            let all_products = jQuery('.item_price');
            let total = 0;
            for(let i=0 ; i < all_products.length ; i++){
                let num = jQuery(`#num_of_product_dropdown_${all_products[i].dataset.row}`).val();
                num = isNaN(num) ? 1 : num;
                
                if(!(all_products[i].dataset.price == null || typeof all_products[i].dataset.price == 'undefined'))
                    total += parseFloat(all_products[i].dataset.price.toString().replace(/,/gm,'')) * num  ;
            }
        
            jQuery('#_suggestion_price').val(addComma(total.toString()));
        }
        /** End of generating */
    <?php else: 
        /** These codes generate js script for variable product */
        echo "/* Add product item */
        function add_dropdown_item_{$id}(){
     
            add_to_dropdown_{$id}('variable_dropdown_{$id}_'+dropdown_container_{$id}, dropdown_container_{$id}++);
        }";

        echo "/* Add product dropdown html */
        function add_to_dropdown_{$id}(id, row){
            let dropdown = `<div id='single_item_container_`+id + `' style='display:flex;margin-top:`+(row>1?'5px':'0')+`'>`;
            dropdown += `<span style='width: 13px;margin-right: 12px;'>`+row +`</span><select class='variable_item_{$id} select short' name='variable_item[{$id}][`+row +`][name]' placeholder='انتخاب کنید' data-row=`+row + ` id='#`+id +`'>`;
            dropdown += `<option  value=''>انتخاب کنید ...</option>`;
            select_options.map((item, index)=>{
               dropdown += `<option  value='`+item.ID+`'>`+item.NAME+`</option>`;
            });
            dropdown += `</select><input class='num_of_product_{$id}' min = '1' max = '200' id='num_of_`+id+`' name='variable_item[{$id}][`+row+`][num]' placeholder='تعداد' type='number' style='max-width:50px;margin-right: 3px;'/><span data-row=`+row+` class='item_price_{$id} item_price_`+id+`' style='margin-top: 7px; margin-right: 4px;min-width:10px;'>
                </span>
                <button type='button' onclick='remove_product_item_{$id}(`+row+`)' style='border:none; color:red;background:transparent;cursor:pointer;'><span class='dashicons dashicons-trash'></span></button>
            </div>`;
            
            jQuery('#variable_item_dropdown_container_{$id}').append(dropdown);
           
            /* These codes add change event to dropdown item fields */
            jQuery('.variable_item_{$id}').change((e)=>{
                let price = get_item_price(e.target.value);
                
                //jQuery(`.item_price_variable_dropdown_{$id}_`+e.target.dataset.row).text(price.toString()+`(`+currency_name+`)`);
                jQuery(`.item_price_variable_dropdown_{$id}_`+e.target.dataset.row).attr('data-price',price.replace(/,/gm,''));

                
                calculate_product_price_{$id}();
            });
            
            /* These codes add change event to number of item fields */
            jQuery('.num_of_product_{$id}').change((e)=>{
                calculate_product_price_{$id}();
            });
        }";
        echo "jQuery('.variable_item_{$id}').change((e)=>{
            let price = get_item_price(e.target.value);
            
            // jQuery(`.item_price_product_dropdown_`+e.target.dataset.row).text(price.toString()+`(`+currency_name+`)`);
            jQuery(`.item_price_product_dropdown_`+e.target.dataset.row).attr('data-price',price.replace(/,/gm,''));
            
            calculate_product_price_{$id}();
        });";
        echo "/* These codes add change event to number of item fields*/
        jQuery('.num_of_product_{$id}').change((e)=>{
            calculate_product_price_{$id}();
        });";
        echo "function remove_product_item_{$id}(id){";
        echo "if(id<dropdown_container_{$id}-1){";
        echo "alert('امکان حذف از ردیف های میانی نیست');";
        echo "return;}";
        echo "dropdown_container_{$id}--;";
        echo "jQuery(`#single_item_container_variable_dropdown_{$id}_`+id).remove();";
        echo "calculate_product_price_{$id}();";
        echo "}";

        echo "function calculate_product_price_{$id}(){
            let all_products = jQuery('.item_price_{$id}');
            let total = 0;
            for(let i=0 ; i < all_products.length ; i++){
                
                let num = jQuery(`#num_of_variable_dropdown_{$id}_`+all_products[i].dataset.row).val();
                num = isNaN(num) ? 1 : parseInt(num);
                
                if(!(all_products[i].dataset.price == null || typeof all_products[i].dataset.price == 'undefined'))
                    total += parseFloat(all_products[i].dataset.price.toString().replace(/,/gm,'')) * num  ;
            }
        
            jQuery('#_suggestion_price_{$id}').val(addComma(total.toString()));
        }";
        /** End of generating */
        endif; ?>
    
    /* Get item price */
    function get_item_price(id){
        for(let i = 0; i < select_options.length ; i++){
            if(select_options[i].ID == id)
                return select_options[i].PRICE;
        }
        return false;
    }
    
    /* Add comma to numbers every three digits */
    function addComma(str){ 
        return str.split(',').join('').replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
    
</script>