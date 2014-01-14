$(function() {
    $('.chosen-select').chosen({
        allow_single_deselect:true,
        width : '75%'
    });
    $('.multiple-ui-selector-base').multiselect();
    $('body, .ui-multiselect-checkboxes').niceScroll({
        cursorcolor : '#9ECCDA',
        cursorwidth : '12px',
        autohidemode : false,
        cursorborderradius : '0'
    });
    $('#resort').multiselect({
        selectedText: "# de # Habitaciones a seleccionado"
    }).multiselectfilter({
        label: 'Buscar Habitación :',
        width: '300'
    });
    
    $(".categorias-container .chosen-select").chosen().on('change', function(evt, params) {
        window.location.href = settings.base_url + 'dashboard?categoria=' + params.selected;
    });
});

