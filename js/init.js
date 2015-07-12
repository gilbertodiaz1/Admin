(function($) {
    $(function() {
        $('.button-collapse').sideNav();
    });
})
(jQuery);

$(document).ready(function() {
    $('select').material_select();
    $('.slider').slider();
    $('ul.tabs').tabs();
    $('.materialboxed').materialbox();
    var from_$input = $('#checkin').pickadate(),
        from_picker = from_$input.pickadate('picker')

    var to_$input = $('#checkout').pickadate(),
        to_picker = to_$input.pickadate('picker')


    // Check if there’s a “from” or “to” date to start with.
    if (from_picker.get('value')) {
        to_picker.set('min', from_picker.get('select'))
    }
    if (to_picker.get('value')) {
        from_picker.set('max', to_picker.get('select'))
    }

    // When something is selected, update the “from” and “to” limits.
    from_picker.on('set', function(event) {
        if (event.select) {
            to_picker.set('min', from_picker.get('select'))
        } else if ('clear' in event) {
            to_picker.set('min', false)
        }
    })
    to_picker.on('set', function(event) {
        if (event.select) {
            from_picker.set('max', to_picker.get('select'))
        } else if ('clear' in event) {
            from_picker.set('max', false)
        }
    })
});

$('#checkin').pickadate({
    today: '',
    closeOnSelect: false,
    closeOnClear: false,
    format: 'dd/mm/yyyy',
    formatSubmit: 'dd/mm/yyyy',
    selectMonths: true,
    selectYears: false,
    selectYears: 1,
    min: true
});

$('#checkout').pickadate({
    closeOnSelect: false,
    closeOnClear: false,
    format: 'dd/mm/yyyy',
    formatSubmit: 'dd/mm/yyyy',
    selectMonths: true,
    selectYears: false,
    selectYears: 1,
    min: true
});