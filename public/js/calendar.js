$(function() {

        const DEFAULT_DATEPICKER_FORMAT = "DD d 'de' MM";
        const DEFAULT_FULLCALENDAR_FORMAT = 'dd/MM/yyyy';
        const DEFAULT_FULLCALENDAR_FORMAT_INVERSE = 'yyyy-MM-dd';
        const DEFAULT_RESERVATION_FORM = '#reservationForm';
        
        $('.datepicker').datepicker({
            dateFormat : DEFAULT_DATEPICKER_FORMAT,
            minDate : '+0d',
            numberOfMonths : 2
        });
      
        function cleanUpReservationFormData() {
            $(DEFAULT_RESERVATION_FORM + ' input').val('');
        }
        
        function setReservationDateAndResort(start, end, resort) {
            var $resort = $("#resort"),
            $nNoches = $('.numero_noches'),
            $startDate = $('#startDate'),
            $endDate= $('#endDate');
            
            $startDate.datepicker('setDate', start);
            $endDate.datepicker('setDate', end);
            var d1 = $startDate.datepicker('getDate'),
            d2 = $endDate.datepicker('getDate'),
            diff = 1;
            
            $resort.find("option[data-id='" + resort.id + "']").attr('selected', 'selected');
            $resort.multiselect("uncheckAll")
            $resort.multiselect("widget").find(':checkbox[value="' + resort.id + '"]').each(function(){
                this.click();
            });
            if (d1 && d2) {
                $nNoches.html(diff + Math.floor((d2.getTime() - d1.getTime()) / 86400000) + ' Noches');
            }
            $resort.multiselect('refresh');
        }
        
	function getReservation() {
            $(popover).remove();
            $(DEFAULT_RESERVATION_FORM).dialog({   
                draggable: false,
                hiddenTitle: true,
                showButtonsOnTop: true,
                dialogClass : 'divarius-reservationModal',
                closeOnEscape : true,
                buttons: {
                    "guardar" : {
                        text:'Guardar',
                        'class':'btn_Guardar',
                        click : function() {
                            var validator = $('#buildReservationForm');
                            if (validator.valid()) {
                                var startDate = '&start=' + $.fullCalendar.formatDate($('#startDate').datepicker('getDate'), DEFAULT_FULLCALENDAR_FORMAT_INVERSE);
                                var endDate = '&end=' + $.fullCalendar.formatDate($('#endDate').datepicker('getDate'), DEFAULT_FULLCALENDAR_FORMAT_INVERSE);
                                $.ajax({
                                    type: "POST",
                                    url: settings.base_url + "reservation/add",
                                    data: $('#buildReservationForm').serialize() + startDate + endDate,
                                    dataType: 'json',
                                    cache: 'false',
                                    beforeSend: function(){
                                        $("#ajax-loading").fadeIn('fast');
                                    },
                                    afterSend: function(){
                                        $("#ajax-loading").fadeOut('fast');
                                    },
                                    success:function(result) {
                                        $("#ajax-loading").fadeOut('fast');
                                        $calendar.fullCalendar('refetchEvents');
                                        $calendar.fullCalendar('unselect');
                                        
                                    }
                                });
                                $(this).dialog( "close" ); 
                            } 

                        }
                    },
                    "cancelar" : {
                        text : 'Cancelar',
                        'class':'btn_Cancelar',
                        click : function() {
                            var validator = $('#buildReservationForm').validate();
                            validator.resetForm();
                            $(this).dialog( "close" ); 
                        }
                    },
                    "eliminar" : {
                        text : 'Eliminar',
                        'class':'btn_Eliminar',
                        click : function() {
                            $(this).dialog( "close" ); 
                        }
                    }
                }
            });
        }
        
        var createConfirmationDialog = function(mensage){
            var dialog = '<div id="dialog-confirm" title="">';
            dialog += '<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>' + mensage + '</div>';
            $(dialog).appendTo('body');
        }
        
        var showTooltip = function ($this, event) {
            
                var reservation = new Object();
                reservation.start = new Date(event.start);
                if (event.end) {
                    reservation.end = new Date(event.end);
                } else {
                    reservation.end = new Date(event.start);
                }
                reservation.end.setDate(reservation.end.getDate() + 1);
                if (reservation.start && reservation.end) {
                    event.noches = Math.floor((1 + reservation.end.getTime() - reservation.start.getTime()) / 86400000);
                }
                tooltip = '<ul class="nav nav-tabs" id="myTab">';
                tooltip += '<li class="active"><a href="#inicio' + event.xid + '">Inicio</a></li>';
                tooltip += '<li><a href="#pasajeros' + event.xid + '">Pasajeros</a></li>';
                tooltip += '<li><a href="#email' + event.xid + '">eMail</a></li>';
                tooltip += '<li><a href="#consumos' + event.xid + '">Consumo</a></li>';
                tooltip += '<li><a href="#facturar' + event.xid + '">Resumen</a></li>';
                tooltip += '</ul>';

                tooltip += '<div class="tab-content">';
                
                tooltip += '<div class="tab-pane active" id="inicio' + event.xid + '">';
                tooltip += '<div class="divarius-popover">';
                tooltip += '<div class="row-fechas"><ul>';
                tooltip += '<li><b class="field">Check Inn: </b><br>' + $.fullCalendar.formatDate(reservation.start, "ddd dd 'de' MMM", Lang.Fullcalendar) + '</li>';
                tooltip += '<li><b class="field">Check Out: </b><br>' + $.fullCalendar.formatDate(reservation.end, "ddd dd 'de' MMM", Lang.Fullcalendar) + '</li>';
                tooltip += '<li><b class="field">Noches: </b><br>' + event.noches + '</li>';
                tooltip += '<li><b class="field">Pax: </b><br>' + event.pax.length + '</li></ul></div>';
                tooltip += '<div class="row-descripcion"><ul>';
                tooltip += '<li><b class="field">Descripcion: </b>' + event.description + '</li></ul></div>';
                tooltip += '<div class="row-datos"><ul><li><b class="field">eMail: </b>' + event.email + '</li>';
                tooltip += '<li><b class="field">Telefono:</b> ' + event.telefono + '</li></ul></div>';
                tooltip += '<div id="controls"><span><a id="popover-check-inn" href="javascript:checkInnProcess(' + event.xid + ')">Check INN</a></span><ul><li><a id="popover-mas-info" class="popover-options">Mas Info</a></li><li>';
                tooltip += '<a class="popover-options popover-anular" data-res="' + event._id + '" href="javascript:removeReservation(' + event.xid + ')" class="delete" rel="' + event._id + '" title="delete" id="ev-' + event.xid + '">';
                tooltip += 'Anular</a></li></ul></div>';
                tooltip += '</div>';              
                tooltip += '</div>';
                
                tooltip += '<div class="tab-pane" id="pasajeros' + event.xid + '">';
                tooltip += '<div class="tab-pasajeros">';
                tooltip += '<ul><li class="newPax"><input type="text" placeholder="Nombre" class="field-pasajeros nombre" id="field-pasajeros" name="nombre[]" />';
                tooltip += '<input type="text" placeholder="Dni" class="field-pasajeros dni" id="field-pasajeros" name="dni[]" />';
                tooltip += '<a class="agregar-pasajero" href="javascript:addPax(' + event.xid + ')">agregar</a></li></ul>';
                tooltip += '<ul class="pax-result"></ul></div>';
                tooltip += '</div>';
                
                
                tooltip += '<div class="tab-pane" id="consumos' + event.xid + '">';
                tooltip += '<div class="tab-consumos">';
                tooltip += '<ul><li class="newConsumo"><input type="text" placeholder="Nombre" class="field-consumo nombre" />';
                tooltip += '<input type="text" placeholder="Costo" class="field-consumo costo" name="costo[]" />';
                tooltip += '<a class="agregar-consumo" href="javascript:addConsumo(' + event.xid + ')">agregar</a></li></ul>';
                tooltip += '<ul class="consumos-result"></ul></div>';
                tooltip += '</div>';
                
                               
                tooltip += '<div class="tab-pane" id="email' + event.xid + '">';
                tooltip += '<div class="tab-email">';
                tooltip += '<input type="text" placeholder="Asunto" class="field-email" id="asunto" name="asunto" />';
                tooltip += '<textarea type="text" placeholder="Contenido" class="field-email" id="contenido" name="contenido" /></textarea>';
                tooltip += '<a id="enviar-email" href="javascript:sendEmailTo(\'';  
                tooltip += event.email;
                tooltip += '\',' + event.xid + ')">Enviar</a>';
                tooltip += '</div>';
                tooltip += '</div>';
                
                tooltip += '<div class="tab-pane" id="facturar' + event.xid + '">';
                tooltip += '<div class="tab-resumen">';
                tooltip += '<div class="consumos-top">';
                tooltip += '<b>¿Qué operación deseas realizar?</b>';
                tooltip += '</div>';
                tooltip += '<div class="consumos-left">';
                tooltip += '<p>- Resumen y Check Out.<br>';
                tooltip += '- Transferir Saldos.<br>';
                tooltip += '- Asignar Créditos.</p>';
                tooltip += '<a id="popover-check-inn" href="javascript:facturar(' + event.xid + ')">Cuenta</a>';
                tooltip += '</div>';
                tooltip += '<div class="consumos-right">';
                tooltip += '<p>- Imprimir, Exportar o simplemente ver el Resumen de la Cuenta. </p>';
                tooltip += '<a id="popover-mas-info" href="javascript:facturar(' + event.xid + ')">Resumen</a>';
                tooltip += '</div>';
                tooltip += '</div>';
                tooltip += '</div>';
                tooltip += '</div>';
                tooltip += '<script>';
                tooltip += '$(".tab-content").niceScroll({';
                tooltip += "cursorcolor : '#9ECCDA',";
                tooltip += "cursorwidth : '7px',";
                tooltip += "autohidemode : false,";
                tooltip += "cursorborderradius : '0'";
                tooltip += "});";
                tooltip += '</script>';
                          
                $this.popover({
                    title: function() {
                        return '<h3 class="popover-title" style="background: '+ event.color +'">' + event.nombre_apellido  + '<span class="divarius-res-status">' + event.status + '</span></h3>';
                    },
                    trigger : 'click',
                    content : tooltip,
                    animation : false 
                });
        }
        
        var getPaxByReservation = function(event){
            $('#pasajeros' + event.xid + ' ul.pax-result').html('');
            var reservationPax = '';
            $.each(event.pax, function(index, value) {
                reservationPax += '<li class="pasajeros lista" id="pax-' + value.id + '"><p class="field-pasajeros pasajero">' + value.nombre + '</p>';
                reservationPax += '<p class="field-pasajeros dni">' + value.dni + '</p>';
                reservationPax += '<a data-pax-id="' + value.id + '" class="quitar-pasajero" href="javascript:removePax(' + value.id + ')">quitar</a></li>';
            });
            $('#pasajeros' + event.xid + ' ul.pax-result').append(reservationPax);
        };
        
        var getConsumoByReservation = function(event){
            $('#consumos' + event.xid + ' ul.consumos-result').html('');
            var reservationConsumo = '';
                $.each(event.consumo, function(index, value) {
                    reservationConsumo += '<li id="consumo-' + value.id + '"><p class="field-consumo nombre">' + value.nombre + '</p>';
                    reservationConsumo += '<p class="field-consumo costo">$ ' + value.costo + '</p>';
                    reservationConsumo += '<a data-consumo-id="' + value.id + '" class="quitar-consumo" href="javascript:removeConsumo(' + value.id + ')">quitar</a></li>';
                });
                $('#consumos' + event.xid + ' ul.consumos-result').append(reservationConsumo);
        };
        
        var resizeOrDrop = function(event) {
            var startDate = '&start=' + $.fullCalendar.formatDate(event._start, DEFAULT_FULLCALENDAR_FORMAT_INVERSE);
            var endDate = '&end=' + $.fullCalendar.formatDate(event._end, DEFAULT_FULLCALENDAR_FORMAT_INVERSE);
            var resort = '&id_resort=' + event.resource;
            $.ajax({
                type: "POST",
                url: settings.base_url + "reservation/update",
                data: 'id=' + event.xid + startDate + endDate + resort + '&action=update',
                dataType: 'json',
                success:function(result) {
                    $calendar.fullCalendar('refetchEvents');
                },
                error: function (xhr, status) { 
                   //alert('Unknown error ' + status);
                }   
                
            });
            $(popover).remove();
        }
            
        var showUpConfirmationDialog = function(Event) {
            var confirmationDialog = $('#dialog-confirm');
            confirmationDialog.dialog({
                resizable: false,
                height:140,
                modal: true,
                hiddenTitle: true,
                dialogClass : 'divarius-confirmationModal',
                buttons: {
                    "confirmar" : {
                        text : 'Confirmar',
                        'class':'btn_Confirmar',
                        click : function() {
                            resizeOrDrop(Event);
                            $(this).dialog( "close" );
                            confirmationDialog.remove();
                        }
                    },
                    "cancelar" : {
                        text : 'Cancelar',
                        'class':'btn_Cancelar',
                        click : function() {
                            $calendar.fullCalendar('refetchEvents');
                            $( this ).dialog( "close" );
                            confirmationDialog.remove();
                        }
                    }
                }
            });    
        } 
        
        var checkAvailability = function(start, end) {
            $('#resort option').removeAttr('disabled');
            $('#resort').multiselect('refresh');
            var startDate = 'start=' + $.fullCalendar.formatDate(start, DEFAULT_FULLCALENDAR_FORMAT_INVERSE);
            var endDate = '&end=' + $.fullCalendar.formatDate(end, DEFAULT_FULLCALENDAR_FORMAT_INVERSE);
            var ndisponibilidad = $('#numero_disponibilidad');
            $.ajax({
                type: "POST",
                url: settings.base_url + "resort/check-availability",
                data: startDate + endDate + '&action=update',
                dataType: 'json',
                success:function(result) {
                    $.each(result.noDisponibles, function(index, value) {
                        $('#resort option[data-id="' + value.id_resort + '"]').attr("disabled", "disabled");
                    });
                    $('#resort').multiselect('refresh');
                    ndisponibilidad.html(result.disponibles[0].cantidad);
                }
            });
        }

        var getPaxAnsConsumos = function(event) {
            var event = event;
            $.ajax({
                type: "POST",
                url: settings.base_url + "resort/getPaxAnsConsumos",
                data: 'reservationId=' + event.xid + '&action=get',
                dataType: 'json',
                success:function(result) {
                    event.consumo = result.consumos;
                    event.pax = result.pax;
                }
            });
            return event;
        }


        //right: 'prev, next, today, resourceWeek, resourceNextWeeks, resourceMonth'
        $calendar.fullCalendar({
		theme: true,
                header: {
                    left: 'calendarHeader',
                    center: 'prev, next, today, resourceWeek, resourceMonth',
                    right: 'title'
                },
                slotEventOverlap : false,
                editable: true,
                defaultView: 'resourceMonth',
                monthNames: 		Lang.Fullcalendar.monthNames,
                monthNamesShort: 	['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                dayNames: 		Lang.Fullcalendar.dayNames,
                dayNamesShort: 		['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                titleFormat: {
                    month: 'MMMM yyyy',
                    // week: "MMM d[ yyyy]{ '&#8212;'[ MMM] d yyyy}",
                    week:"'Semaine du' dd [yyyy] {'au' [MMM] dd MMM yyyy}",
                    day: 'MMM dddd dd yyyy'
                },
                buttonText: {
                        prev: '&nbsp;&#9668;&nbsp;',
                        next: '&nbsp;&#9658;&nbsp;',
                        prevYear: 		'&nbsp;&lt;&lt;&nbsp;',
                        nextYear: 		'&nbsp;&gt;&gt;&nbsp;',
                        today: 			Lang.Fullcalendar.buttonText.today,
                        month: 			Lang.Fullcalendar.buttonText.month,
                        week: 			Lang.Fullcalendar.buttonText.week,
                        day: 			Lang.Fullcalendar.buttonText.day,
                        resourceWeek:           Lang.resource.resourceWeek,
                        resourceNextWeeks:      Lang.resource.resourceNextWeeks,
                        resourceMonth:          Lang.resource.resourceMonth
                },
                columnFormat: {
                        month: 'ddd',
                        week: 'ddd d-M',
                        day: 'dddd d-M',
                        resourceDay: 'H:mm',
                        resourceMonth: 'd <p>ddd</p>',
                        resourceWeek: 'd-M <p>ddd</p>',
                        resourceNextWeeks: 'd-M <p>ddd</p>'
                },
                numberOfWeeks: 3,
                weekPrefix: Lang.Fullcalendar.buttonText.week,
                firstDay: 1, 	
                selectable: true,
                minTime: 8,
                maxTime: 16,
                selectHelper: true,
                eventBorderColor : '#FFF',
                resources: settings.resouces,
                events: settings.base_url + 'resort/getreservations',
                select: function(start, end, allDay, jsEvent, view, resource) {   
                    cleanUpReservationFormData();
                    checkAvailability(start, end);
                    setReservationDateAndResort(start, end, resource);
                    getReservation();
                },
                eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
                    console.log(event)
                    var thisEvent = event, 
                    mensaje = '<p>NECESITAMOS TU ATENCION</p>';
                    mensaje += '<p>Estas a punto de modificar las fechas de una reserva.</p>';
                    mensaje += '<p><span style="font-size: 13px; font-family: Open sans; background: none repeat scroll 0 0 #008000; color: #FFFFFF; display: inline-block; margin-top: 2px; padding: 5px;">' + $.fullCalendar.formatDate(event.start, "dddd dd 'de' MMM", Lang.Fullcalendar) + '.';
                    mensaje += ' — ' + $.fullCalendar.formatDate(event.end, "dddd dd 'de' MMM", Lang.Fullcalendar) + '.</span></p>';
                    createConfirmationDialog(mensaje);
                    showUpConfirmationDialog(thisEvent);
                },
                eventAfterRender : function(event, element, view) {
                    showTooltip(element, event);
                },
                eventClick : function(event, jsEvent, view) {
                },
                eventResize : function(event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view) { 
                    var thisEvent = event, 
                    mensaje = '<p>NECESITAMOS TU ATENCION</p>';
                    mensaje += '<p>Estas a punto de modificar las fechas de una reserva.</p>';
                    mensaje += '<p><span style="font-size: 13px; font-family: Open sans; background: none repeat scroll 0 0 #008000; color: #FFFFFF; display: inline-block; margin-top: 2px; padding: 5px;">' + $.fullCalendar.formatDate(event.start, "dddd dd 'de' MMM", Lang.Fullcalendar) + '</span>';
                    mensaje += '<span> — </span><span style="font-size: 13px; font-family: Open sans; background: none repeat scroll 0 0 #008000; color: #FFFFFF; display: inline-block; margin-top: 2px; padding: 5px;">' + $.fullCalendar.formatDate(event.end, "dddd dd 'de' MMM", Lang.Fullcalendar) + '</span></p>';
                    createConfirmationDialog(mensaje);
                    showUpConfirmationDialog(thisEvent);
                },
                windowResize: function(view) {
                    $calendar.fullCalendar('option', 'height', $(window).height() - 40);
                },
                eventMouseover: function(event, jsEvent) {
                },
                eventMouseout: function(calEvent, jsEvent) {
                    $(this).css('z-index', 8);
                    var $tabs = $('#myTab a');
                    $tabs.click(function (e) {
                        e.preventDefault();
                        $(this).tab('show');
                        var event = getPaxAnsConsumos(calEvent);
                        getPaxByReservation(event);
                        getConsumoByReservation(event);
                    });
                }
        });
});
