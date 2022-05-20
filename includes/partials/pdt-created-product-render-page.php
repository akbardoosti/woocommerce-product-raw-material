
<?php require(plugin_dir_path( __FILE__ ) .'public/css/RenderPage-css.php');?>

<form>
    <div class="input-group mb-3">
        <p class="stock_status_field hide_if_variable hide_if_external hide_if_grouped form-field _stock_status_field">
            <div class="product-container" id="product_container" >
                
			  	<div data-row="1" class="row" style="display: flex;">
			  	    
			  	    <div class="col-xs-6">
			  	        <label>نوع محصول</label>
			  	        <select name="productType" class="short" id="product_type">
			  	            <!--<option value="">انتخاب کنید ...</option>-->
                            <option value="simple">ساده</option>
                            <option value="variable">متغیر</option>
                        </select>
			  	    </div>
			  		<div class="col-xs-6">
			  		    <label>وضعیت</label>
			  		    <select name="productStatus" class="short" id="product_status">
			  		        <option value="">انتخاب کنید ...</option>
                            <option value="publish">منتشر شده</option>
                            <option value="draft">پیش نویس</option>
                            <option value="trash">حذف شده</option>
                        </select>
			  		</div>
			  		
			  	</div>
			  	<hr/>
			  
			  	<!--<div data-row="1" class="row" style="display:flex;">-->
			  	<!--    <div class="checkbox" style="margin-top:20px">-->
			  		   
      <!--          		<input id="product_selection" type="checkbox">-->
      <!--                  <label for="product_selection">	اعمال روی چند محصول</label> -->
			  	<!--	</div>-->
			  	<!--</div>-->
			  	
			  	<div id="how_calc_price-continaer" style="margin-right: 20px; margin-top: 10px;display:flex">
		  		    <div>
		  		        <label style="width: auto;display: block;font-weight: 900;margin-bottom: 2px;">&nbsp; </label>
    		  		    <select id="how_calc_price"  name="how_calc_price">
    		  		        <option value="">کارهای دسته جمعی</option>
    		  		        <option value="suggested-price">قیمت پیشنهادی اعمال شود</option>
    		  		        <option  value="new-price"> قیمت جدید اعمال  شود</option>
    		  		        <option value="increase_price">افزایش قیمت</option>
    		  		    </select>
		  		    </div>
		  		    
		  		    <div>
		  		        <div  id="new_price_container" style="float:right;">
    			  		    <label style="font-weight: 900;width:100%;display: block;margin-bottom: 2px;">مبلغ(<?php echo $currencies[$currency_code]; ?>) </label>
    			  		    <input type="number" min = '1' id="new_price_value" name="new_price" placeholder="لطفا مبلغ مورد نظر را وارد کنید" />
    			  		</div>
		  		    </div>
		  		    
			  		<div class="row" style="">
    			  	    <div  id="increase_rate_input_container" style="float:right;">
    			  		    <label style="width: auto;display: block;font-weight: 900;margin-bottom: 2px;">میزان افزایش</label>
    			  		    <input type="number" min = '1' id="Increase_rate" name="Increase_rate" placeholder="لطفا مبلغ مورد نظر را وارد کنید" />
    			  		</div>
    			  		<div id="value_type-continaer" style="float:right;">
    			  		    <label style="width:auto;display:block;"> </label>
    			  		    <div style="margin-top: 23px;">
                              <label for="currency_name" class="l-radio">
                                <input type="radio" id="currency_name" value="currency" name="value_type">
                                <span><?= $currencies[ $currency_code ] ?></span>
                              </label>
                              <label for="percent-option" class="l-radio">
                                <input type="radio" id="percent-option" value="percent" name="value_type">
                                <span>درصد</span>
                              </label>
                            </div>
    			  		</div>
    			  	</div>
		  		</div>
			  	
		  	</div>
	  	</p>
    </div>
    <button type="button" id="group_work_button" onclick="saveChanges()" style="height: 12px;margin-top: 17px;margin-right:22px;" class="button action">اجرا</button>
    
        <!--<button id="save_changes_button" type="button" onclick="saveChanges()" style="width:200px;height: 12px;" class="button action">ذخیره تغییرات</button>-->
    <div style="position: absolute;
    top: 0;
    bottom: 0;
    display: flex;
    left: 0;
    right: 0;
    vertical-align: middle;
    background-color: #1414149c;
    align-items:center;
    justify-content: center;
    z-index: 999
    " id="loading-container">
        <div class="lds-facebook"><div></div><div></div><div></div></div>
    </div>
    
</form>

<span id="popupNotification"></span>
<div id="jsGrid" style="margin:20px;">
    
</div>
<div style="display: flex;  justify-content: center;">
    <div id="pagination_container" dir="rtl"></div>
</div>


<?php require(plugin_dir_path( __FILE__ ) .'public/js/RenderPage-js.php');?>