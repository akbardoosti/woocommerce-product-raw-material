<style>
    
</style>

<span id="popupNotification"></span>
<fieldset class="radio-button-container" style="direction:ltr;display: flex;flex-flow: row-reverse;">
    
    <div style="margin-left: 10px;">
       <input type="radio" name="import_method" id="radio1" class="radio" value="file" checked />
       <label for="radio1">آپلود فایل اکسل </label>
    </div>
    <div>
       <input type="radio" name="import_method" id="radio2" class="radio" value="form" />
       <label for="radio2">وارد کردن مواد اولیه </label>
    </div>
    
</fieldset>
<div class="custom-file-upload">
    
    <input type="file" id="excelFiles" name="excelFiles[]" multiple />
</div>
<form id="product-details-form" action="config.php?func_name=save_info">
	<div class="input-group mb-3">
	  	<div class="input-group-prepend">
	    	<button type="button" onclick="add_product_html()" class="btn-add btn btn-outline-secondary btn-circle btn-success" type="button">
                <span class="dashicons dashicons-plus"></span>
	    		<?php _e('افزودن ماده اولیه', 'product-details');//Add product ?>
	    	</button>
            
	  	</div>
	  	<div class="product-container" id="product_container" >
		  	<div data-row="1" class="row" style="display: flex;">
		  	    <label>1</label>
		  	    <div class="col-xs-6">
		  	        <input id="form[productName][1]" name="form[productName][1]" placeholder="<?php _e('نام ماده اولیه', 'product-details');//Product name ?>" type="text" class="form-control" aria-label="" aria-describedby="basic-addon1">
		  	    </div>
		  		<div class="col-xs-6">
		  		    <input id="form[productPrice][1]" name="form[productPrice][1]" placeholder="<?php _e('قیمت ماده اولیه', 'product-details');//Product price ?>" type="text" class="form-control"  aria-label="" aria-describedby="basic-addon2">
		  		</div>
		  		<label></label>
		  	</div>
	  	</div>
	</div>
	<div>
		<button type="button" onclick="save_info()" class="btn-save-info">
			<?php _e('ذخیره اطلاعات مواد اولیه', 'product-details');//Save products information ?> 
		</button>
	</div>
</form>


<div style="margin:auto 10px;">
	<div class="header" style="margin-top: 21px;display:block;text-align: center;background-color: #10128b;padding: 12px;color: #fff;">
	    <h3 style="color: #fff;margin:0">
			<?php _e('لیست مواد اولیه', 'product-details');//Products list ?>
	    </h3>
	</div>
	<div id="jsGrid"></div>
</div>

<br />


<div style="margin:auto 10px;">
	<div class="header" style="margin-top: 21px;display:block;text-align: center;background-color: #10128b;padding: 12px;color: #fff;">
	    <h3 style="color: #fff;margin:0">
			<?php _e('لیست دسته بندی ها', 'product-details');//Products list ?>
	    </h3>
	</div>
	<div id="category-list">
	    
	</div>
</div>

<br />
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
	  	    </div>
	  	</div>
    </p>
</div>
<div style="margin:auto 10px;">
	<div class="header" style="margin-top: 21px;display:block;text-align: center;background-color: #10128b;padding: 12px;color: #fff;">
	    <h3 style="color: #fff;margin:0">
			<?php _e('لیست محصولات', 'product-details');//Products list ?>
	    </h3>
	</div>
	<div id="product-list">
	    
	</div>

</div>
<div style="display: flex;  justify-content: center; direction: rtl;margin-top: 10px;">
    <div id="pagination_container" dir="rtl"></div>
</div>

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

<div id="ohsnap"></div>


<?php 
require plugin_dir_path( __FILE__ ) . 'public/js/js_functions_list.php';

require plugin_dir_path( __FILE__ ) . 'public/js/generate-kendo-list-js.php';

require(plugin_dir_path( __FILE__ ) .'public/js/init_form-js.php');
?>