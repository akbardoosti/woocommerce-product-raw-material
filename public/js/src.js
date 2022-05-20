
jQuery( 'document' ).ready( () => {

    var popupNotification = jQuery("#popup-notification").kendoNotification({
        appendTo: "#appendto",
        // autoHideAfter: 50000
    }).data("kendoNotification");

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

        //
        jQuery( '#custom-course-input-container' )
            .hide()
            .find('input')
                .val("");   
    }
    initializePluginRFM();

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
        console.log(element);
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

    jQuery('.time-span-body').click(function(){   
        triggerCollapseRFM(jQuery(this).parent()[0]);
    });

    

    jQuery( '.time-span-form-control-item-label' ).on( 'click' , function( e ) {
        // Find all radio buttons after this element and uncheck all of them
        let radios = jQuery( this ).parent().parent().find( '.time-span-form-control-item-radio-container' );

        _.each(radios, (item) => {
            jQuery( item ).removeClass( 'checked' );
            jQuery( item ).find( '.time-span-form-control-item-radio-icon-checked' ).hide();
            jQuery( item ).find( 'input[type="radio"]' ).removeAttr( 'checked' );
        });

        // Check this element and add checked styles to the element
        jQuery( this )
            .find('.time-span-form-control-item-radio-container')
            .addClass('checked');
        jQuery( this )
            .find( 'input[type="radio"]' )
            .prop( 'checked', true );

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

    jQuery( '#custom-course-input-container input').change( ( e ) => {
        
        if ( e.target.value < 90 ) {
            popupNotification.show(
                translated_data.custom_course_input_error, 
                "error"
            );
            e.target.value = "";
        }
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

    jQuery ( ".time-span-form-control-item-radio" ).click( function () {
        enableSaveButtonRFM( jQuery( this ).closest( '.time-span-grid-root' ) );
    });

    jQuery('.time-span-content-body').on('change', (e) => {
        jQuery(this).find('.time-span-save-button').removeClass('disabled-button');
    });

    jQuery('input[type="checkbox"]').change(function() {

        if ( this.checked ) {
            jQuery(this).parent().addClass('switch-checked');
        } else {
            jQuery(this).parents().removeClass('switch-checked');
        }    

    });

    jQuery( '.time-span-group-input' ).focus( function() {
        jQuery( this ).parent().addClass( 'focused-border' );
    });
    jQuery( '.time-span-group-input' ).blur( function() {
        jQuery( this ).parent().removeClass( 'focused-border' );
    });

    const labels = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'Auguest',
        'September',
        'October',
        'November',
        'December'
    ];

    const data = {
        labels: labels,
        datasets: [{
          label: 'مجموعه داده های من',
          backgroundColor: 'rgb(255, 152, 0)',
          borderColor: 'rgb(255, 152, 0)',
          data: [80, 10, 5, 2, 20, 30, 45, 5, 23, 65, 10, 5],
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {}
    };

    const myChart = new Chart(
        document.getElementById('recencyChart'),
        config
    );

    const monetaryChart = new Chart(
        document.getElementById('monetaryChart'),
        config
    );

    const frequencyChart = new Chart(
        document.getElementById('frequencyChart'),
        config
    )
});