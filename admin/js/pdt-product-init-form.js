let counter = 1;
/**
 * Upload file into server
 */
function upload_file() {
    jQuery( '#loading-container' ).show();
    file_data = jQuery("#excelFiles").prop('files')[0];
    form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('action', 'uploaad_excel_file');
    form_data.append('security', blog.security);

    jQuery.ajax({
        url: blog.ajaxurl,
        type: 'POST',
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
            jQuery( '#loading-container' ).hide();
            if ( response.success ) {
                jQuery ( "#excelFiles" ).val( '' );
                
                popupNotification.show('فایل با موفقیت بارگذاری شد', "success");    
                get_all_products();
            } else {
                popupNotification.show( 'لطفا یک فایل اکسل انتخاب کنید.', 'error' );
            }
        }
    });
}
(function($) {

          // Browser supports HTML5 multiple file?
          var multipleSupport = typeof $('<input/>')[0].multiple !== 'undefined',
              isIE = /msie/i.test( navigator.userAgent );

          $.fn.customFile = function() {

            return this.each(function() {

              var $file = $(this).addClass('custom-file-upload-hidden'), // the original file input
                  $wrap = $('<div class="file-upload-wrapper">'),
                  $input = $('<input type="text" class="file-upload-input" />'),
                  // Button that will be used in non-IE browsers
                  $button = $('<button type="button" class="file-upload-button">انتخاب فایل</button>'),
                  $download_button = $('<a href="<?php echo MY_PLUGIN_ADDRESS . 'data/raw_materials.xlsx' ?>" type="button" class="file-download-button" target="_blank" download>دانلود فایل نمونه</a>'),
                  $button_upload = $('<button type="button" class="file-upload-btn">بارگذاری</button>'),
                  // Hack for IE
                  $label = $('<label class="file-upload-button" for="'+ $file[0].id +'">انتخاب فایل</label>');

              // Hide by shifting to the left so we
              // can still trigger events
              $file.css({
                position: 'absolute',
                left: '-9999px'
              });

              $wrap.insertAfter( $file )
                .append( $file, $input, ( isIE ? $label : $button ) )
                .append( $button_upload )
                .append( $download_button );

              // Prevent focus
              $file.attr('tabIndex', -1);
              $button.attr('tabIndex', -1);
              $button_upload.attr('tabIndex', -1);

              $button.click(function () {
                $file.focus().click(); // Open dialog
              });

              $button_upload.click(function () {
                upload_file();
              });

              $file.change(function() {

                var files = [], fileArr, filename;

                // If multiple is supported then extract
                // all filenames from the file array
                if ( multipleSupport ) {
                  fileArr = $file[0].files;
                  for ( var i = 0, len = fileArr.length; i < len; i++ ) {
                    files.push( fileArr[i].name );
                  }
                  filename = files.join(', ');

                // If not supported then just take the value
                // and remove the path to just show the filename
                } else {
                  filename = $file.val().split('\\').pop();
                }

                $input.val( filename ) // Set the value
                  .attr('title', filename) // Show filename in title tootlip
                  .focus(); // Regain focus

              });

              $input.on({
                blur: function() { $file.trigger('blur'); },
                keydown: function( e ) {
                  if ( e.which === 13 ) { // Enter
                    if ( !isIE ) { $file.trigger('click'); }
                  } else if ( e.which === 8 || e.which === 46 ) { // Backspace & Del
                    // On some browsers the value is read-only
                    // with this trick we remove the old input and add
                    // a clean clone with all the original events attached
                    $file.replaceWith( $file = $file.clone( true ) );
                    $file.trigger('change');
                    $input.val('');
                  } else if ( e.which === 9 ){ // TAB
                    return;
                  } else { // All other keys
                    return false;
                  }
                }
              });

            });

          };

          // Old browser fallback
          if ( !multipleSupport ) {
            $( document ).on('change', 'input.customfile', function() {

              var $this = $(this),
                  // Create a unique ID so we
                  // can attach the label to the input
                  uniqId = 'customfile_'+ (new Date()).getTime(),
                  $wrap = $this.parent(),

                  // Filter empty input
                  $inputs = $wrap.siblings().find('.file-upload-input')
                    .filter(function(){ return !this.value }),

                  $file = $('<input type="file" id="'+ uniqId +'" name="'+ $this.attr('name') +'"/>');

              // 1ms timeout so it runs after all other events
              // that modify the value have triggered
              setTimeout(function() {
                // Add a new input
                if ( $this.val() ) {
                  // Check for empty fields to prevent
                  // creating new inputs when changing files
                  if ( !$inputs.length ) {
                    $wrap.after( $file );
                    $file.customFile();
                  }
                // Remove and reorganize inputs
                } else {
                  $inputs.parent().remove();
                  // Move the input so it's always last on the list
                  $wrap.appendTo( $wrap.parent() );
                  $wrap.find('input').focus();
                }
              }, 1);

            });
          }

}(jQuery));

    jQuery('input[type=file]').customFile();

    jQuery(document).ready(function($) {
        
        var selector = document.getElementById("form[productName][1]");

        
        jQuery("#form\\[productPrice\\]\\[1\\]").on('input', (e)=>{
            e.target.value = e.target.value.replace(/[^0-9\.]+/g, '');
            e.target.value = addComma(e.target.value);
        });

        jQuery( '#loading-container' ).show();
        jQuery.when( get_all_products() ).then( () => {
            jQuery.when( get_all_categories() ).then( () => {
                jQuery.when( get_shipping_price_list({
                    product_type: jQuery('#product_type').val(),
                    num_per_page: 10,
                    page_number: 1
                }) ).then( () => {
                    jQuery( '#loading-container' ).hide();
                }).fail();
            }).fail();
        }).fail();

        jQuery( '#product_type' ).change(function() {
            get_shipping_price_list({
                product_type: jQuery('#product_type').val(),
                num_per_page: 10,
                page_number: 1
            });
        });

        jQuery( '.custom-file-upload' ).show();
        jQuery( '#product-details-form' ).hide();
        jQuery( 'input[name="import_method"]' ).change( function( e ) {
            if ( e.target.value == 'file' ) {
                jQuery( '.custom-file-upload' ).show();
                jQuery( '#product-details-form' ).hide();
            } else {
                jQuery( '#product-details-form' ).show();
                jQuery( '.custom-file-upload' ).hide();
            }
        });
    });
    

    //Add comma to numbers every three digits
    function addComma(str){ 
        return str.split(',').join('').replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }

    //add product input in html form
    function add_product_html(){
        let html_content = `<div data-row="${++counter}" class="row" style="display: flex;">
        <label>${counter}</label>
        <div class="col-xs-6">
        <input id="form[productName][${counter}]" name="form[productName][${counter}]" placeholder="<?php _e('نام ماده اولیه', 'product-details');//Product name ?>" type="text" class="form-control" aria-label="" aria-describedby="basic-addon${counter}"></div>
        <div class="col-xs-6"><input id="form[productPrice][${counter}]" name="form[productPrice][${counter}]" placeholder="<?php _e('قیمت ماده اولیه', 'product-details');//Product price ?>" type="text" class="form-control"  aria-label="" aria-describedby="basic-addon${counter}"></div>
        <button type="button" onclick="remove_product_html(${counter})" class="btn-remove btn btn-outline-secondary btn-circle btn-success" type="button">
        <span class="dashicons dashicons-trash"></span>
        </button></div>`;
        
        jQuery("#product_container").append(html_content);
        
        jQuery(`#form\\[productPrice\\]\\[${counter}\\]`).on('input', (e)=>{
            e.target.value = e.target.value.replace(/[^0-9\.]+/g, '');
            e.target.value = addComma(e.target.value);
        });
    }
    
    //remove product input row in html form
    function remove_product_html(row_id){
        
        if(row_id < counter){
            
            popupNotification.show('<?php _e('از ردیف های میانی نمیتوانید حذف کنید', 'product-deta'); ?>',
            "error");           
            return;
        }
        counter--;
        jQuery(`[data-row=${row_id}]`).remove();
    }

    //Get all data 
    function get_formatting_data(data){
        let return_data = [];
        let obj = {};
        let flag = true;
        for (var i = 0; i < data.length; i++) {
            if(data[i]['value'].trim() == "")
                flag = false;
            if ((/productName/gm).test(data[i]['name'])) {
                obj.product_name = data[i]['value'];
                
            }else{
                obj.product_price = data[i]['value'];
                return_data.push(obj);
                obj = {};
            }
        }
        
        if(!flag)
            return false;
            
        return return_data;
    }
    //Save product info 
    function save_info(){
        let formData = jQuery('#product-details-form').serializeArray();
        formData = get_formatting_data(formData);
        
        if(formData == false)
        {
            
            popupNotification.show('<?php  _e('پرکردن همه فیلدها ضروری است', 'product-detaita'); ?>',
            "error");
            return;
        }
        
        let action = 'save_product_info';
        let data = {formData, action};
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post('<?= MY_PLUGIN_ADDRESS ?>includes/config.php', data, function(response) {
            let i = 2;
            popupNotification.show('<?php   _e('عملیات ذخیره سازی با موفقیت انجام شد', 'product-detail'); ?>',
            "success");
            while(i <= counter){
                jQuery(`[data-row=${i}]`).remove();//Delete rows greater than 1
                i++;
            }
            counter = 1;//Reset counter
    
            jQuery(`#form\\[productPrice\\]\\[1\\]`).val('');
            jQuery(`#form\\[productName\\]\\[1\\]`).val('');
            get_all_products();
        });
    }
    
    
    
    
    
    
    //Load all products
    function get_all_products (){
        
        let diff = jQuery.Deferred();
        jQuery.ajax({
            url: '<?= MY_PLUGIN_ADDRESS ?>includes/config.php',
            type:'post',
            data: {
                action: 'get_all_products'
            },
            async: true,
            success: function(data){
                
                diff.resolve();
                material_list_kendo( JSON.parse( data ) );
                
            }
        });
        
        return diff.promise();
    }
    
    

    
    // Load all categories
    function get_all_categories (){
        let diff = jQuery.Deferred();
        
        jQuery.ajax({
            url: '<?= MY_PLUGIN_ADDRESS ?>includes/config.php',
            type:'post',
            data: {
                action: 'get_all_categories'
            },
            dataType: 'json',
            async: true,
            success: function(data){
                diff.resolve();
               category_list_kendo( _.toArray( data ) );
                
            }
        });
        
        return diff.promise();
    }
    
    
    
    function get_shipping_price_list( options ) {
        let diff = jQuery.Deferred();
        
        jQuery( '#loading-container' ).show();
        
        jQuery.ajax({
            url: '<?= MY_PLUGIN_ADDRESS ?>includes/config.php',
            type:'post',
            data: {
                action: 'get_shipping_price_list',
                type: options.product_type,
                num_per_page: options.num_per_page,
                page_number: options.page_number
            },
            dataType: 'json',
            async: true,
            success: function(data){

                diff.resolve();
                jQuery( '#loading-container' ).hide();
                generatePagination (
                    parseInt(data['num_of_pages']), 
                    options.num_per_page,
                    // parseInt(jQuery("#per_page_number").val() != ""?jQuery("#per_page_number").val():10),
                    options.page_number
                );
                product_list_kendo( _.toArray( data.product_list ) );

            }
        });
        
        return diff.promise();
    }
    

    function generatePagination (numofpages, per_page, cur_page){
        jQuery("#pagination_container").pagination({
            items: numofpages * per_page,
            itemsOnPage: per_page,
            prevText: "&laquo;",
            nextText: "&raquo;",
            currentPage: cur_page,
            onPageClick: function (pageNumber) {
                get_shipping_price_list({
                    product_type: jQuery('#product_type').val(),
                    num_per_page: per_page,
                    page_number: pageNumber,
                });
            },
        });
    }
