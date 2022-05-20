var popupNotification;
var chartVariables = {};
var chartLabels = {};

jQuery( 'document' ).ready( ( $ ) => {

    popupNotification = jQuery("#popup-notification").kendoNotification({
        appendTo: "#appendto",
        // autoHideAfter: 50000
    }).data("kendoNotification");
    

    // Add cahnge event for inputs
    $( ".time-span-group-input" ).on( 'change', function() {
        
        let dataRow  = parseInt( $( this ).attr( 'data-row' ) );
        let dataType =  $( this ).attr( 'data-type' ) ;
        
        
        /* Clear all of fields after this element */
        for ( let i = dataRow + 1; i <= 5 ; i++ ) {
            let value = $( this )
                .closest( '.time-span-group-input-container' )
                .find( "input[data-row=" + ( i ) + "]" ).val();
                
            if ( value.trim() !== "" ) {
                $( this )
                .closest( '.time-span-group-input-container' )
                .find( "input[data-row=" + ( i ) + "]" ).val("");
            
                $( this )
                    .closest( '.time-span-grid-item-wrapper' )
                    .find( "[data-first=" + i + "]" )
                    .text("");
                $( this )
                    .closest( '.time-span-grid-item-wrapper' )
                    .find( "[data-last=" + i + "]" )
                    .text("");
            }
            
        }
        
        // Value of the before field
        let beforeVal = $( this )
            .closest( '.time-span-group-input-container' )
            .find( "input[data-row="+( dataRow - 1 )+"]" ).val();
        
        // If the before value is blank then clear current element
        if ( beforeVal.trim() === "" ) {
            $( this ).val( "" );
            
            $( this )
                .parent()
                .parent()
                .prepend()
                .prepend( '<label style="bottom: 26px;position: absolute;color: red;z-index: 1000;right: 0px;font-size: 12px;">' + validate_message.blank_message + '</label>')
                .find('label')
                .fadeOut(3000);
            $( this ).val("");
        }

        // If the curent value less than the before value then clear current element
        if ( parseFloat( this.value ) <= parseFloat( beforeVal ) ) {

            $( this )
                .parent()
                .parent()
                .prepend()
                .prepend( '<label style="bottom: 26px;position: absolute;color: red;z-index: 1000;right: 0px;font-size: 12px;">' + validate_message.compare_message + '</label>')
                .find('label')
                .fadeOut(3000);
            
            $( this ).val( "" );

        } else {

            $( this ).closest( '.time-span-grid-item-wrapper' ).find( "[data-last=" + dataRow + "]" ).text( this.value );
            $( this ).closest( '.time-span-grid-item-wrapper' ).find( "[data-first=" + ( dataRow + 1 ) + "]" ).text( this.value );
        
        }
        
        
        // If the last item is filled then call getRFMValues function
        if ( $( this ).closest( '.time-span-grid-item-wrapper' ).find( "[data-last=5]" ).text().trim() !== "" ) {
            getRFMValues( dataType );
        }
        
        // Enable save button 
        enableSaveButtonRFM( jQuery( this ).closest( '.time-span-grid-root' ) );
    });
});


/**
 * Calculate grid item amount
 * 
 * @since 1.0.0
 * @param   Object      element     The DOM element of grid items container
 * @param   string      type        The type of RFM
 * @deprecated 1.0.0
 */ 
function calcualteGridItemAmount( element, type ) {
    
    let time_spans = jQuery( element )
        .closest( '.time-span-grid-item-wrapper' )
        .find( '.time-span-period-name' );

    let first, last;
    
    _.each( time_spans, ( item ) => {
        // console.log(item);    
        if( jQuery( item ).hasClass( 'time-span-period-first' ) ) {
            first = jQuery( item ).text();
            if( first.includes("First") || first.includes("ابتدا") ){
                first = 0;
            }
        } else {
            last = parseInt( jQuery( item ).text().toString() );
        }
        if ( _.isNumber( last ) ) {
            // console.log(_.isNumber( last ), last);
            let total = 0;
            _.each( chartLabels[ type ], ( inner_item, index ) => {
                index = parseInt( index );
                first = parseInt( first );
                console.log( index > first && index < last, first, index, last  );
                if ( index > first && index < last ) {
                    total += parseInt( chartVariables[ type ].config.data.datasets[0].data[ inner_item ] );
                }
            } );
            
            jQuery( item ).closest( '.time-span-statistics-cart-wrapper' ).find( ".time-span-number-of-customers" ).text( total );
            
            last = "";
        }
    });
}

/**
 * Return the category list of each type
 * 
 * @since 1.0.0
 * @param   string  type    
 * @return  array           The category list of each type
 */ 
function calcualteCustomerCategory( type ) {
    
    // let types = [ 'recency', 'frequency', 'monetary' ];
    let first = "", last = "", result = [];
    
    // _.each( types, ( item ) => {
        let fieldsets = jQuery( 'input[data-type="' + type + '"]' );
        
        _.each( fieldsets, ( in_item ) => {
            
            if ( first.trim() === "" ) {
                first = jQuery( in_item ).val();
            } else {
                last = jQuery( in_item ).val();
                result.push( { first: first, last: last } );
                first = last;
            }

        });
    // });
    
    return result;
}

/**
 * Retrive chart data
 * 
 * @since 1.0.0
 * @param   string  type     type of RFM
 * @return  Promise          Retrive chart data 
 */
function getChartData( type ) {
    // jQuery( '#loading-container' ).show();
    
    let deffer = jQuery.Deferred();
    
    let formData = _.toArray( jQuery( '#rfm-aggregator-setting' ).serializeArray() );
    formData = getFormattingData( formData );
    
    let nonces = {
        recency:   chart_ajax_obj.rec_nonce,
        frequency: chart_ajax_obj.freq_nonce,
        monetary:  chart_ajax_obj.monetary_nonce,
    };
    
    let ids = {
        recency:    "recencyChart",
        frequency:  "frequencyChart",
        monetary:   "monetaryChart",
    };
    
    let chartMessage = {
        recency:    chart_message.recency,
        frequency:  chart_message.frequency,
        monetary:   chart_message.monetary,
    };
    
    jQuery.ajax({
        url: chart_ajax_obj.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            type: type,
            action: "get_chart_data",
            _ajax_nonce: nonces[ type ],
            formData: formData
        },
        success: function( result ) {
            deffer.resolve();
            if ( result.success ) {
                
                let labels = result.data.labels, tempLabels = {};
                
                for ( let i = 0 ; i < labels.length ; i++ ) {
                    
                    if ( labels[ i ] === null ) {
                        tempLabels[ 0 ] = i;
                    } else {
                        let index = labels[ i ].split( '-' )[0];
                        tempLabels[ index ] = i;
                    }
                    
                }
                
                chartLabels[ type ]= tempLabels;
                
                const data = {
                    labels: result.data.labels,
                    datasets: [{
                      label: chartMessage[type],
                      backgroundColor: 'rgb(255, 152, 0)',
                      borderColor: 'rgb(255, 152, 0)',
                      data: result.data.data,
                    }]
                };
            
                const config = {
                    type: 'bar',
                    data: data,
                    options: {}
                };
                
                // If chart exist then destroy it
                if ( typeof chartVariables[ type ] !== undefined && chartVariables[ type ] != null ) {
                    chartVariables[ type ].destroy();
                }
                chartVariables[ type ] = new Chart(
                    document.getElementById( ids[type] ),
                    config
                );
                
            }
        }
    });
    
    return deffer.promise();
}



/**
 * Retrive RFM setting
 * 
 * @since 1.0.0
 * @return  Promise          Retrive RFM setting
 */
function getRFMAggregatorSetting() {
    // jQuery( '#loading-container' ).show();
    let deffer = jQuery .Deferred();
    
    jQuery.ajax({
        url: my_ajax_obj.ajax_url,
        type: 'post',
        data:{
            _ajax_nonce: my_ajax_obj.nonce,
            action: "get_rfm_setting"
        },
        success: function( result ) {
            deffer.resolve();
            if ( result.success ) {
                let data = result.data;
                
                setRadioButton( 'purchase_amount_index', data[ 'purchase_amount_index' ] );
                setRadioButton( 'purchase_number_index', data[ 'purchase_number_index' ] );
                setCheckBox( 'delete_data', data[ 'delete_junk_files' ] );
                setItemBox( data[ 'customer_category' ] );
                
                let recency_cat   = isJson( data[ 'recency_cat' ] ) ? JSON.parse( data[ 'recency_cat' ] ) : [],
                    frequency_cat = isJson( data[ 'frequency_cat' ] ) ? JSON.parse( data[ 'frequency_cat' ] ) : [],
                    monetary_cat  = isJson( data[ 'monetary_cat' ] ) ? JSON.parse( data[ 'monetary_cat' ] ) : [];
                
                _.each( recency_cat, ( item, index ) => {
                    jQuery( 'input[data-row="' + index + '"][data-type="recency"]' ).val( item ).trigger( 'change' );
                });
                
                _.each( frequency_cat, ( item, index ) => {
                    jQuery( 'input[data-row="' + index + '"][data-type="frequency"]' ).val( item ).trigger( 'change' );
                });
                
                _.each( monetary_cat, ( item, index ) => {
                    jQuery( 'input[data-row="' + index + '"][data-type="monetary"]' ).val( item ).trigger( 'change' );
                });
            }
        }
    });
    
    return deffer.promise();
}

/*
    Calling of functions to load form data
*/
jQuery.when( getRFMAggregatorSetting() ).done( function() {
    jQuery.when( getChartData( 'recency' ) ).done( function() {
        jQuery.when( getChartData( 'frequency' ) ).done( function() {
            jQuery.when( getChartData( 'monetary' ) ).done( function() {
                jQuery( '#loading-container' ).hide();
            });
        });
    });
});

/**
 * Retrive RFM values
 * 
 * @since 1.0.0
 * @param   string    type  type of RFM
 * @return  Promise         RFM values
 */
function getRFMValues( type ) {
    
    // jQuery( '#loading-container' ).show();
    let nonces = {
        recency:   chart_ajax_obj.rec_nonce,
        frequency: chart_ajax_obj.freq_nonce,
        monetary:  chart_ajax_obj.monetary_nonce,
    };
    
    let categories = calcualteCustomerCategory( type );
    
    jQuery.ajax({
        url: chart_ajax_obj.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
            type: type,
            action: "get_rfm_values",
            _ajax_nonce: nonces[ type ],
            categories: categories
        },
        success: function( result ) {
            jQuery( '#loading-container' ).hide();
            if ( result.success ) {
                let data = result.data;
                _.each( data, ( item, index ) => {
                    jQuery( 'span[data-type=' + type + '][customer-numbers=' + ( index + 1 )  + ']' ).text( item );
                    // console.log('span[data-type=' + type + '][customer-numbers=' + ( index + 1 )  + ']', item);
                })
            }
        }
    });
}


/**
 * Save RFM settings in DataBase
 * 
 * @since 1.0.0
 */ 
function saveRFMAggregatorSetting() {

    let formData = _.toArray( jQuery( '#rfm-aggregator-setting' ).serializeArray() );
    formData = getFormattingData( formData );
    formData.customer_category = getTimeSpanBoxItemValue();

    /* global ajaxurl:true */
    jQuery.ajax({
        url: my_ajax_obj.ajax_url,
        type: 'post',
        data:{
            ...formData, // Copy formData object into data
            _ajax_nonce: my_ajax_obj.nonce,
            action: "save_rfm_setting"
        },
        success: function( data ) {

            if ( data.success ) {
                
                popupNotification.show(
                    translated_data.save_rfm_setting_success, 
                    "success"
                );
            } else {
                popupNotification.show(
                    translated_data.error_message, 
                    "error"
                );
            }

        },
    });
}
