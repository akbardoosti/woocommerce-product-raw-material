
var analysisListSelect = {};

jQuery( 'document' ).ready( function() {
    
    

    jQuery.when( getRFMbasicAnalysisSetting() ).done( function( res ) {
        let categories = res.base_categories_analysis, 
            products   = res.base_products_analysis,
            areas      = res.base_areas_analysis;
        
        _.each( categories, ( item, index ) => {
            jQuery( '#categories-analysis-list option[value=\'' + item.value + '\']' ).attr( 'selected', 'selected' );
        });

        _.each( products, ( item, index ) => {
            jQuery( '#products-analysis-list option[value=\'' + item.value + '\']' ).attr( 'selected', 'selected' );
        });

        try{
            _.each( areas, ( item, index ) => {
                jQuery( '#areas-analysis-list option[value=\'' + item.value + '\']' ).attr( 'selected', 'selected' );
            });    
        } catch ( error ) {  }
        
        analysisListSelect[ 'categories' ]  = createMultiSelect( '#categories-analysis-list' );
        analysisListSelect[ 'products' ]    = createMultiSelect( '#products-analysis-list' );
        analysisListSelect[ 'areas' ]       = createMultiSelect( '#areas-analysis-list' );

    }).fail( function( rej ) {

    });
});

function createMultiSelect( id ) {

    const multiSelect = new IconicMultiSelect({
        customCss:   true,
        noData:      analysis_setting_message.noData,
        noResults:   analysis_setting_message.noResult,
        placeholder: analysis_setting_message.placeHolder,
        select:      id,
    });

    multiSelect.init();

    multiSelect.subscribe(function (event) {
        console.log(event);
    });

    return multiSelect;

}

function saveRFMbasicAnalysisSetting () {
    // Show loading 
    // jQuery( '#loading-container' ).show();
    
    // Get form data
    let formData = {};//_.toArray( jQuery( '#basic-analysis-setting-form' ).serializeArray() );
    // Organizing formData
    formData[ 'categories-analysis-list' ] = analysisListSelect[ 'categories' ]._selectedOptions;
    formData[ 'products-analysis-list' ] = analysisListSelect[ 'products' ]._selectedOptions;
    formData[ 'areas-analysis-list' ] = analysisListSelect[ 'areas' ]._selectedOptions;
    formData[ 'categories_options' ] = jQuery( '[name=categories_options]' ).val();
    formData[ 'products_options' ] = jQuery( '[name=products_options]' ).val();
    formData[ 'areas_options' ] = jQuery( '[name=areas_options]' ).val();
    
    
    // console.log(analysisListSelect);
    
    /* global ajaxurl:true */
    jQuery.ajax({
        url: my_ajax_obj.ajax_url,
        type: 'post',
        data:{
            ...formData, // Copy formData object into data
            _ajax_nonce: my_ajax_obj.nonce,
            action: "save_basic_analysis_setting"
        },
        success: function( data ) {
            
            // Hide loading 
            jQuery( '#loading-container' ).hide();
            if ( data.success ) {
                popupNotification.show(
                    translated_data.save_basic_analysis_setting_success, 
                    "success"
                );
            }
            
        },
    })
}

function getRFMbasicAnalysisSetting() {

    let deffer = jQuery.Deferred();

    jQuery.ajax({
        url: my_ajax_obj.ajax_url,
        type: 'post',
        data: {
            _ajax_nonce: my_ajax_obj.nonce,
            action: "get_basic_analysis_setting"
        },
        success: function( data ) {

            jQuery( '[name=categories_options] option[value=' + data.data[ 'base_categories_options' ] + ']' ).attr('selected','selected');
            jQuery( '[name=products_options] option[value=' + data.data[ 'base_products_options' ] + ']' ).attr('selected','selected');
            jQuery( '[name=areas_options] option[value=' + data.data[ 'base_areas_options' ] + ']' ).attr('selected','selected');
            
            deffer.resolve( data.data );
            // Hide loading 
            jQuery( '#loading-container' ).hide();
            // if ( data.success ) {
            //     popupNotification.show(
            //         translated_data.save_time_period_setting_success, 
            //         "success"
            //     );
            // }
            
        },
    });

    return deffer.promise();
}

