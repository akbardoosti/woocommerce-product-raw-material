

function triggerCollapse(element) {
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

    jQuery(element).find('.time-span-content-body').slideToggle(500);
}

jQuery('.time-span-body').click(function(){   
    alert();
    triggerCollapse(jQuery(this).parent()[0]);
});

jQuery('.time-span-content-body').hide();

jQuery('.time-span-form-control-item-label').on('click', function(e) {
    // Find all radio buttons after this element and uncheck all of them
    let radios = jQuery(this).parent().parent().find('.time-span-form-control-item-radio-container');
    _.each(radios, (item) => {
        jQuery(item).removeClass('checked');
        jQuery(item).find('.time-span-form-control-item-radio-icon-checked').hide();
    });

    // Check this element and add checked styles to the element
    jQuery(this).find('.time-span-form-control-item-radio-container').addClass('checked');
    jQuery(this).
    find('.time-span-form-control-item-radio-icon-checked').
    show().
    removeClass('hide');

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

// const myChart = new Chart(
//     document.getElementById('myChart'),
//     config
// );

// const monetaryChart = new Chart(
//     document.getElementById('monetaryChart'),
//     config
// );

// const frequencyChart = new Chart(
//     document.getElementById('frequencyChart'),
//     config
// )