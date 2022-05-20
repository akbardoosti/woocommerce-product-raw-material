var popupNotification;

jQuery( 'document' ).ready( ( $ ) => {
    
    // Create Kendo notification object
    popupNotification = jQuery( "#popup-notification" ).kendoNotification({
        appendTo: "#appendto",
        // autoHideAfter: 50000
    }).data( "kendoNotification" );
    
    // Add .time-span-body tags click event
    jQuery( '.time-span-body' ).click( function() {   
        triggerCollapseRFM( jQuery( this ).parent()[ 0 ] );
    });
    
    // Add all radio buttons click event
    jQuery( '.time-span-form-control-item-label' ).on( 'click' , function( e ) {
        
        // Find all radio buttons after this element and uncheck all of them
        let radios = jQuery( this ).parent().parent().find( '.time-span-form-control-item-radio-container' );

        _.each(radios, (item) => {
            jQuery( item ).removeClass( 'checked' );
            jQuery( item ).find( '.time-span-form-control-item-radio-icon-checked' ).hide();
            jQuery( item ).find( 'input[type="radio"]' ).removeAttr( 'checked' );
        });

        // Checked styles to the element
        jQuery( this )
            .find('.time-span-form-control-item-radio-container')
            .addClass('checked');
            
        // Change this element to checked input
        jQuery( this )
            .find( 'input[type="radio"]' )
            .prop( 'checked', true );

        // Change to checked icon 
        jQuery( this )
            .find('.time-span-form-control-item-radio-icon-checked')
                .show()
            .removeClass('hide');
        
    });

    /* 
    Fires when custom course option is changed,
    This field generate in the includes/views/time-period-setting/render-page.php file
    */
    jQuery( 'input[type=radio][name=analysis_period]' ).click( ( e ) => {
        
        if ( e.currentTarget.value == 'custom-course' ) {
            jQuery( '#custom-course-input-container' ).show();
        } else {
            jQuery( '#custom-course-input-container' )
                .hide()
                .find('input')
                    .val("");
        }
        
    });

    // If length of custom course input less than 90 display error
    jQuery( '#custom-course-input-container input').change( ( e ) => {
        
        if ( e.target.value < 90 ) {
            popupNotification.show(
                translated_data.custom_course_input_error, 
                "error"
            );
            e.target.value = "";
        }
        
    });

    // Enable save button when radio button cahnged
    jQuery ( ".time-span-form-control-item-radio" ).click( function () {
        enableSaveButtonRFM( jQuery( this ).closest( '.time-span-grid-root' ) );
    });

    // 
    jQuery('.time-span-content-body').on('change', (e) => {
        jQuery( this ).find( '.time-span-save-button' ).removeClass( 'disabled-button' );
    });

    // Add checkbox style when checkbox switched
    jQuery( 'input[type="checkbox"]' ).change(function() {

        if ( this.checked ) {
            jQuery(this).parent().addClass('switch-checked');
        } else {
            jQuery(this).parents().removeClass('switch-checked');
        }    

    });

    // Add focused style for input element when focused
    jQuery( '.time-span-group-input' ).focus( function() {
        jQuery( this ).parent().addClass( 'focused-border' );
    });
    
    // Remove focused style for input element when blured
    jQuery( '.time-span-group-input' ).blur( function() {
        jQuery( this ).parent().removeClass( 'focused-border' );
    });
    
    // Add click event to scoring button 
    $( '.time-span-scoring-button-content' ).click( function() {
        
        $( this )
            .closest( '.time-span-scoring-item' )
            .find( '.time-span-scoring-collapse' )
                .slideToggle( 'fast' );

        // Get arrow icon of the scoring button 
        let collapseTag = $( this )
                                .closest('.time-span-scoring-button-root')
                                    .find( '.time-span-scoring-collapse-icon svg');
                                    
        // Check if the icon has rotate-180 class remove that or if  icon doesn't have rotate-180 class add that
        if ( ! $( collapseTag ).hasClass( 'rotate-180' ) ) {
            $( collapseTag ).addClass( 'rotate-180' );
        } else {
            $( collapseTag ).removeClass( 'rotate-180' );
        }
        
    });

    // Hide all of Scoring sections
    $( '.time-span-scoring-collapse' ).hide();

    // Add click event to box item 
    $( '.time-span-box-inner-item' ).on( 'click', function() {
        
        // Get all of time-span-box-item's 
        let box_items = $( this )
                        .closest( '.time-span-box-root' )
                            .find( '.time-span-box-item' );
        
        // Remove all of checked styles from items
        _.each( box_items, ( item ) => {

            $( item )
                .find( '.time-span-box-inner-item' )
                .removeClass( 'box-item-checked' )
                    .find( 'svg' )
                    .removeClass( 'box-checked' );
            
        });
    
        // Add Checked Style to this element
        $( this )
            .addClass( 'box-item-checked' )
                .find( 'svg' )
                .addClass( 'box-checked' );
        
    });
    
    // Add click event to cancel button 
    jQuery( '.time-span-cancel-button' ).on( 'click', function() {
        let time_span_container = jQuery( this ).closest( '.time-span-container' );
        
        // Clear all fields in the form
        clearForm( time_span_container );
    });

}); 

/**
 * Save time period setting
 * Designed to store interval settings
 * @since 1.0.0
 */ 
function saveTimePeriodSetting() {
    
    // Show loading 
    // jQuery( '#loading-container' ).show();
    
    // Get form data
    let formData = _.toArray( jQuery( '#time-period-setting-form' ).serializeArray() );
    // Organizing formData
    formData = getFormattingData( formData );
    
    /* global ajaxurl:true */
    jQuery.ajax({
        url: my_ajax_obj.ajax_url,
        type: 'post',
        data:{
            ...formData, // Copy formData object into data
            _ajax_nonce: my_ajax_obj.nonce,
            action: "save_time_period_setting"
        },
        success: function( data ) {
            
            // Hide loading 
            jQuery( '#loading-container' ).hide();
            if ( data.success ) {
                popupNotification.show(
                    translated_data.save_time_period_setting_success, 
                    "success"
                );
            }
            
        },
    })
}

/**
 * Get time period setting
 * Designed to load interval settings
 * @since 1.0.0
 */ 
function getTimePeriodSetting() {
    
    // Show loading 
    // jQuery( '#loading-container' ).show();
    
    jQuery.ajax({
        url: my_ajax_obj.ajax_url,
        type: 'post',
        data:{
            _ajax_nonce: my_ajax_obj.nonce,
            action: "get_time_period_setting"
        },
        success: function( result ) {
            
            // Hide loading 
            jQuery( '#loading-container' ).hide();
            
            if ( result.success ) {
                let data = result.data;
                
                // Set time period setting form
                jQuery( 'input[name=custom_course_time_span]' ).val( data[ 'custom_course_time_span' ] );
                setRadioButton( 'analysis_period', data[ 'analysis_time_period' ] );
                setRadioButton( 'comparison_period', data[ 'comparison_analysis_period' ] );
                if ( data[ 'analysis_time_period' ] === 'custom-course') {
                    jQuery( '#custom-course-input-container' ).show();
                }
            }
        }
    });
}

getTimePeriodSetting();


jQuery( '#loading-container' ).hide();