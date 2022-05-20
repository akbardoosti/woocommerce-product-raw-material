
jQuery( document ).ready( function() {
    initializePluginRFM();
});

/**
 * Enable button (2022-04-12)
 *
 * Remove disabled-button class from the button (2022-04-12)
 * 
 * @alias    enableSaveButtonRFM
 *
 *
 * @fires   click
 * @fires   .time-span-form-control-item-radio#click
 * @listens event:click
 *
 * @param {Object} element   Includes  .time-span-container HTML tag element
 */
function enableSaveButtonRFM( element ) {
    jQuery( element ).find( '.time-span-save-button' ).removeClass( 'disabled-button' );
}

/**
 * Trigger .time-span-content-body HTML tag (2022-04-12)
 *
 * Fires when .time-span-body element is clicked (2022-04-12)
 * 
 * @alias    triggerCollapse
 *
 *
 * @fires   click
 * @fires   .time-span-body#click
 * @listens event:eventName
 * @listens className~event:eventName
 *
 * @param {Object} element   Includes  .time-span-container HTML tag element
 */
function triggerCollapseRFM(element) {
    if (jQuery(element).find('.time-span-collapse-button').hasClass('rotate-180')) {
        jQuery(element).find('.time-span-collapse-button').removeClass('rotate-180');
    } else {
        jQuery(element).find('.time-span-collapse-button').addClass('rotate-180');
    }

    if (jQuery(element).find('.time-span-body').hasClass('expand-header')) {
        jQuery(element).find('.time-span-body').removeClass('expand-header');
    } else {
        jQuery(element).find('.time-span-body').addClass('expand-header');
    }

    jQuery(element).find('.time-span-content-body').slideToggle('fast');
}


/**
 * Initialize rfm-plugin form
 *
 * When windows is loaded initializePluginRFM() function will be executed
 *
 * @alias    initializePluginRFM
 */
function initializePluginRFM(){
    // Each of items container will be hidden 
    jQuery('.time-span-content-body').hide();

    // clear custom course input
    jQuery( '#custom-course-input-container' )
        .hide()
        .find('input')
            .val("");   
}


/**
 * Set radio button
 * 
 * Designed to change the values of radio fields
 * @since 1.0.0
 */ 
function setRadioButton( name, value ) {
    if ( name.trim() == "" || name == null || typeof name === undefined ||
         value.trim() == "" || value == null || typeof value === undefined
        ) {
        return;
    }
    jQuery( "[name=" + name + "][value="+value+"]" )
        .prop( 'checked', true );
    jQuery( "[name=" + name + "][value="+value+"]" )
        .parent()
        .addClass( 'checked' );
    jQuery( "[name=" + name + "][value="+value+"]" )
        .parent()
            .find( '.time-span-form-control-item-radio-icon-checked' )
            .removeClass( 'hide' );
}

/**
 * Set checkbox field 
 * 
 * Designed to change the values of checkbox fields
 * @since 1.0.0
 */ 
function setCheckBox( name, value ) {
    
    if ( value == '1') {
        jQuery( "[name=" + name + "]" )
            .prop( 'checked', true );
        jQuery( "[name=" + name + "]" )
            .parent()
            .addClass( 'switch-checked' );
    }
        
}

/**
 * Set item box fields
 * 
 * Designed to change the values of item box fields
 * @since 1.0.0
 */ 
function setItemBox( value ) {
    if( value.trim() !== "") {
        jQuery( "[data=" + value + "]" ).addClass( 'box-item-checked' );
        jQuery( "[data=" + value + "]" ).find( 'svg' ).addClass( 'box-checked' );
    }
}


/**
 * Clear form
 * 
 * Designed to clear the values of form fields
 * @since 1.0.0
 */ 
function clearForm( container ) {
    
    _.each( container, ( item, index ) => {
        let time_span_form_controls = jQuery( item ).find( '.time-span-form-control-root' );
        
        // 
        _.each( time_span_form_controls, ( in_item, in_index ) => {
            let spans = jQuery( in_item ).find( '.time-span-form-control-item-radio-container' );
            _.each( spans, ( s_item, s_index ) => {
                jQuery( s_item ).removeClass( 'checked' );
                jQuery( s_item ).find( 'input' ).prop( 'checked', false );
                jQuery( s_item ).find( '.time-span-form-control-item-radio-icon-checked' ).hide();
            });
        });
        
        jQuery( item )
            .find( '#custom-course-input-container' )
            .hide()
            .find('input')
                .val(""); 
                
        triggerCollapseRFM(item);
        
        jQuery( item ).find( '.time-span-save-button' ).addClass( 'disabled-button' );    
    });
}


/**
 * Format data
 * 
 * To format the field values received through the serializeArray function
 * @since 1.0.0
 * @return {object} formatted data
 */ 
function getFormattingData(data) {
    let return_data = {};

    _.each( data, ( item ) => {
        return_data[ item.name ] = item.value;
    } );
    
    return return_data;
}


/**
 * Retrive box item fields value
 * @since 1.0.0
 * @return string value of box item value
 */ 
function getTimeSpanBoxItemValue() {

    let tags = jQuery( '.time-span-box-item' ), result;
    
    _.each( tags, ( item ) => {
        
        let hasClass = jQuery( item )
                        .find( '.time-span-box-inner-item' )
                        .hasClass( 'box-item-checked' );
        
        if ( hasClass ) {
            result = jQuery( item )
                        .find( '.time-span-box-inner-item' )
                        .attr( 'data' );
        }
    });

    return result;
}



function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}