var createAnularDialog = function(mensage){
    var mensage = "ELIMINAR RESERVACION";
    mensage = "Esta deacuerdo en eliminar la reservacion?";
    var dialog = '<div id="dialog-confirm" title="">';
    dialog += '<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><p>' + mensage + '</p></div>';
    $(dialog).appendTo('body');
}

var removeReservation = function(eventxId) {
    createAnularDialog();
    var eventxId = eventxId;
    var eventId = $('#ev-' + eventxId).data('res');
    $(popover).remove();
    var confirmationDialog = $('#dialog-confirm');
    confirmationDialog.dialog({
        resizable: false,
        height:140,
        modal: true,
        hiddenTitle: true,
        dialogClass : 'divarius-confirmationModal',
        buttons: {
            "Eliminar" : {
                text : 'Eliminar',
                'class':'btn_Confirmar',
                click : function() {
                    if(eventId && eventxId) {
                        // opslaan in db
                        var dataString = 'reservation_id=' + eventxId + '&action=delete';
                        $.ajax({
                            type:"POST",
                            url: "reservation/delete",
                            data: dataString,
                            success:function(html){
                                $calendar.fullCalendar('removeEvents', eventId);
                                $calendar.fullCalendar('refetchEvents');
                                $(popover).remove();
                            }
                        });
                        $(this).dialog( "close" );
                        confirmationDialog.remove();
                    }
                }
            },
            "cancelar" : {
                text : 'Cancelar',
                'class':'btn_Cancelar',
                click : function() {
                    $calendar.fullCalendar('refetchEvents');
                    $calendar.fullCalendar('unselect');
                    $(this).dialog( "close" );
                    confirmationDialog.remove();
                }
            }
        }
    });    
}

var removePax = function(paxId) {
    var row = $('#pax-'+ paxId);
    $.ajax({
        type: "POST",
        url: settings.base_url + "reservation/remove-pax",
        data: '&action=remove' + '&pax_id=' + paxId,
        dataType: 'json',
        success:function(result) {
            if(result){
                row.remove();
            }
        }
    });
} 

var addPax = function(reservation) {
    var contenedorPasajeros = $('#pasajeros' + reservation + ' .tab-pasajeros ul.pax-result'),
    nombre = $('#pasajeros' + reservation + ' .newPax').find('input.nombre').val(),
    dni = $('#pasajeros' + reservation + ' .newPax').find('input.dni').val(),
    reserva = reservation;
    $.ajax({
        type: "POST",
        url: settings.base_url + 'reservation/add-pax',
        data: '&action=add' + '&nombre=' + nombre + '&dni=' + dni + '&reserva=' + reserva,
        dataType: 'json',
        success:function(result) {
            if (result) {
                var newRow = '<li class="pasajeros lista" id="pax-' + result + '"><p class="field-pasajeros pasajero">' + nombre + '</p>';
                newRow += '<p class="field-pasajeros dni">' + dni + '</p>';
                newRow += '<a data-pax-id="' + result + '" class="quitar-pasajero" href="javascript:removePax(' + result + ')">quitar</a></li>';
                contenedorPasajeros.prepend(newRow);
            }
        }
    });
}


var removeConsumo = function(consumoId) {
    var row = $('#consumo-'+ consumoId);
    $.ajax({
        type: "POST",
        url: settings.base_url + "reservation/remove-consumo",
        data: '&action=remove' + '&consumo_id=' + consumoId,
        dataType: 'json',
        success:function(result) {
            if(result){
                row.remove();
            }
        }
    });
} 

var addConsumo = function(reservation) {
    var contenedorConsumos = $('#consumos' + reservation + ' .tab-consumos ul.consumos-result'),
    nombre = $('#consumos' + reservation + ' .newConsumo').find('input.nombre').val(),
    costo = $('#consumos' + reservation + ' .newConsumo').find('input.costo').val(),
    reserva = reservation;
    $.ajax({
        type: "POST",
        url: settings.base_url + 'reservation/add-consumo',
        data: '&action=add' + '&nombre=' + nombre + '&costo=' + costo + '&reserva=' + reserva,
        dataType: 'json',
        success:function(result) {
            if (result) {
                var newRow = '<li id="consumo-' + result + '"><p class="field-consumo nombre">' + nombre + '</p>';
                newRow += '<p class="field-consumo costo">$ ' + costo + '</p>';
                newRow += '<a data-consumo-id="' + result + '" class="quitar-consumo" href="javascript:removeConsumo(' + result + ')">quitar</a></li>';
                contenedorConsumos.prepend(newRow);
            }
        }
    });
}

var checkInnProcess = function(reservation) {
    $.ajax({
        type: "POST",
        url: settings.base_url + "reservation/check-inn",
        data: '&action=checkinn' + '&reservationId=' + reservation,
        dataType: 'json',
        success:function(result) {
            $calendar.fullCalendar('refetchEvents');
            $(popover).remove();
        }
    });
}

var confirmarProcess = function(reservation) {
    $.ajax({
        type: "POST",
        url: settings.base_url + "reservation/confirmarProcess",
        data: '&action=confirmar' + '&reservationId=' + reservation,
        dataType: 'json',
        success:function(result) {
            $calendar.fullCalendar('refetchEvents');
            $(popover).remove();
        }
    });
}

var checkOutProcess = function(reservation) {
    $.ajax({
        type: "POST",
        url: settings.base_url + "reservation/check-out",
        data: '&action=checkout' + '&reservationId=' + reservation,
        dataType: 'json',
        success:function(result) {
            $calendar.fullCalendar('refetchEvents');
            $(popover).remove();
        }
    });
}

var sendEmailTo = function(email, reservationId) {
    var asunto = $('#email' + reservationId + ' #asunto').val();
    var contenido = $('#email' + reservationId + ' #contenido').val();
    $.ajax({
        type: "POST",
        url: settings.base_url + "reservation/send-email",
        data: 'action=send' + '&reservationId=' + reservationId + '&email=' + email + '&asunto=' + asunto + '&contenido=' + contenido,
        dataType: 'json',
        success:function(result) {
            $calendar.fullCalendar('refetchEvents');
            $(popover).remove();
        }
    });
}

var facturar = function(reservationId) {
    $('#resumenForm').dialog({   
        draggable: false,
        hiddenTitle: true,
        showButtonsOnTop: true,
        dialogClass : 'divarius-resumenModal',
        closeOnEscape : true,
        buttons: {
            "guardar" : {
                text:'Guardar',
                'class':'btn_Guardar',
                click : function() {
                    $(this).dialog( "close" ); 
                }
            },
            "cancelar" : {
                text : 'Cancelar',
                'class':'btn_Cancelar',
                click : function() {
                    $(this).dialog( "close" ); 
                }
            }
        }
    });
}