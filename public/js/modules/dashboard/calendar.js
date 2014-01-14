$(function() {

        const DEFAULT_DATEPICKER_FORMAT = "DD d 'de' MM yy";
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
        
        function calcularNoches(startDate, endDate) {
            return 1 + Math.floor((endDate.getTime() - startDate.getTime()) / 86400000);
        }
        
        function calcularNochesCambiandoFechas(startDate, endDate, precio) {
            $('#startDate, #endDate').bind('change', function(){
                var cantNoches = calcularNoches(startDate.datepicker('getDate'), endDate.datepicker('getDate'));
                setReservationNightAndAmount(cantNoches, precio);
            });
        }
        
        function setReservationNightAndAmount(cantNoches, precio) {
            var $numeroNoches = $('.numero_noches'),
            $montoNoches = $('#monto'), 
            $montoServicios = $('#resumen_servicios_monto'),
            $monto_final = $('#resumen_monto_final');
            
            $montoServicios.html(0);
            $montoNoches.html(0)
            
            $numeroNoches.html(cantNoches + ' Noches');
            $montoNoches.html(precio * cantNoches);
            
            var cuenta = parseInt($montoNoches.html()) + parseInt($montoServicios.html());
            $monto_final.html(cuenta);
            var montoNoches = parseInt($montoNoches.html());
            var montoServicios = parseInt($montoServicios.html());
            var saldoResultado = montoNoches + montoServicios;

            $("#descuento").bind('blur', function() {
                cuenta = parseInt(saldoResultado) - parseInt($(this).val());
                $monto_final.html(cuenta);
            });
        }
        
        function setReservationDateAndResort(start, end, resort) {
            var $resort = $("#resort"), 
            $startDate = $('#startDate'), 
            $endDate = $('#endDate'),
            $unidad = $('#unidad');
            $startDate.datepicker('setDate', start);
            $endDate.datepicker('setDate', end);
            var cantNoches = calcularNoches($startDate.datepicker('getDate'), $endDate.datepicker('getDate'));
            
            /* seteamos las fechas*/
            
            $unidad.html(resort.unidad);
         
            $resort.find("option[data-id='" + resort.id + "']").attr('selected', 'selected');
            $resort.multiselect("uncheckAll")
            $resort.multiselect("widget").find(':checkbox[value="' + resort.id + '"]').each(function(){
                this.click();
            });
            /* setea los precios y cantidad de noches */
            setReservationNightAndAmount(cantNoches, resort.precio_noche);
            /* si cambia alguna fecha comprueba y setea los precios de vuenta */
            calcularNochesCambiandoFechas($startDate, $endDate, resort.precio_noche);
            
            $resort.multiselect('refresh');
        }
        
        /**
         * Crea el modal de agregar reserva 
        **/
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
        
        /**
         * Crea el modal de confirmacion
         **/
        var createConfirmationDialog = function(mensage){
            var dialog = '<div id="dialog-confirm" title="">';
            dialog += '<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>' + mensage + '</div>';
            $(dialog).appendTo('body');
        }
        
        /**
         * crea el popover de la reserva
         **/
        var showTooltip = function ($this, event, element) {
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

                var handleStatus = '';
                if (event.status == 'Pendiente Trad.') {
                    handleStatus = '<a class="popover-confirmar" href="javascript:confirmarProcess(' + event.xid + ')">Confirmar</a>'
                } else if (event.status == 'Confirmada') {
                    handleStatus =  '<a class="popover-check-inn" href="javascript:checkInnProcess(' + event.xid + ')">Check INN</a>'
                } else if (event.status == 'Check inn') {
                    handleStatus =  '<a class="popover-check-out" href="javascript:checkOutProcess(' + event.xid + ')">Check OUT</a>'
                }             
                tooltip = '<ul class="nav nav-tabs" id="myTab">';
                tooltip += '<li class="active"><a href="#inicio' + event.xid + '">Inicio</a></li>';
                tooltip += '<li><a href="#pasajeros' + event.xid + '">Pasajeros</a></li>';
                tooltip += '<li><a href="#email' + event.xid + '">eMail</a></li>';
                tooltip += '<li><a href="#consumos' + event.xid + '">Consumo</a></li>';
                tooltip += '<li><a href="#facturar' + event.xid + '">Resumen</a></li>';
                tooltip += '</ul>';

                tooltip += '<div class="tab-content">';
                
                tooltip += '<div class="tab-pane active inicio" id="inicio' + event.xid + '">';
                tooltip += '<div class="divarius-popover">';
                tooltip += '<div class="row-fechas"><ul>';
                tooltip += '<li><b class="field">Check Inn: </b><br>' + $.fullCalendar.formatDate(reservation.start, "ddd dd 'de' MMM", Lang.Fullcalendar) + '</li>';
                tooltip += '<li><b class="field">Check Out: </b><br>' + $.fullCalendar.formatDate(reservation.end, "ddd dd 'de' MMM", Lang.Fullcalendar) + '</li>';
                tooltip += '<li><b class="field">Noches: </b><br>' + event.noches + '</li>';
                tooltip += '<li><b class="field">Pax: </b><br>' + event.pax + '</li></ul></div>';
                tooltip += '<div class="row-datos"><ul><li><b class="field">eMail: </b>' + event.email + '</li></ul></li></div>';
                tooltip += '<div class="row-datos"><ul><li><b class="field">Telefono:</b> ' + event.telefono + '</li></ul></div>';
                tooltip += '<div class="row-descripcion"><ul>';
                tooltip += '<li><b class="field">Observacions: </b>' + event.description + '</li></ul></div>';              
                tooltip += '</div>';
                tooltip += '<div id="controls"><span>' + handleStatus + '</span><ul><li><a href="' + settings.base_url + 'reservas/edit/' + event.xid + '" id="popover-mas-info" class="popover-options">Mas Info</a></li><li>';
                tooltip += '<a class="popover-options popover-anular" data-res="' + event._id + '" href="javascript:removeReservation(' + event.xid + ')" class="delete" rel="' + event._id + '" title="delete" id="ev-' + event.xid + '">';
                tooltip += 'Anular</a></li></ul></div>';
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
                tooltip += '<a id="popover-cuenta" href="javascript:facturar(' + event.xid + ')">Cuenta</a>';
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

                var elementPosition = parseInt(element.offset().left) + parseInt(element.width());
                var windowWidth = $(window).width();
                var position = 'right';

                result = parseInt(windowWidth) - parseInt(elementPosition);
                if (result < 450) {
                    position = 'left';
                }

                $this.popover({
                    title: function() {
                        return '<h3 class="popover-title" style="background: '+ event.color +'">' + event.nombre_apellido  + '<span class="divarius-res-status">' + event.status + '</span></h3>';
                    },
                    trigger : 'click',
                    content : tooltip,
                    animation : false,
                    placement : position
                });
        }
        
        /**
         * Agrega tooltip a las habitaciones
         */
        var showResourceTooltip = function() {
            $('.resort-name').tooltip({
            items: "[data-img], [title], [data-precio], [data-estadia-minima], [data-descripcion]",
            content: function() {
                var element = $(this),
                title = element.attr("title"),
                tooltipMessage = '';

                tooltipMessage = '<div class="tooltipContainer">';
                tooltipMessage += '<p style="font-size=14px; font-weight: bold; color: #333; margin-bottom: 0;">' + title + '</p>';
                
                if (element.is('[data-descripcion]')) {
                    tooltipMessage += '<p style="font-size=14px;color: #777; margin: 0;">' + element.data('descripcion') + '</p>';
                }
                if (element.is('[data-precio]')) {
                    tooltipMessage += '<p style="font-size=14px; font-weight: bold; color: #333; margin-bottom: 0;  margin-top: 5px;"><span style="color: #777; font-weight: normal;">Precio Noche:</span> $ ' + element.data('precio') + '</p>';
                }
                if ( element.is('[data-estadia-minima]') ) {
                    tooltipMessage += '<p style="font-size=14px; font-weight: bold; color: #333; margin-top: 0;"><span style="color: #777; font-weight: normal;">Estadia Minima:</span> ' + element.data('estadia-minima') + ' Noches</p>';
                }
                if (element.is('[data-img]')) {
                    var imgSource = element.data('img'),
                    imgUrl = imgSource.split(',');
                    tooltipMessage += '<ul style="margin: 0;>';
                    $.each(imgUrl, function(index, value) {
                        tooltipMessage += '<li style="display: inline; list-style: none; margin-left: 5px;"><img class="tooltipImg" src="' + settings.base_url + 'assets/uploads/gallery/' + value + '" width="70px" height="70px" /></li>';
                    });
                    tooltipMessage += '</ul>';
                }
                tooltipMessage += '</div>';
                return tooltipMessage;
            }
            });
        };
        
        /**
         * Trae los pasajeros de una reserva
         */
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
        
        /**
         * Trae los consumos de una reserva
         **/
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
        
        /**
         * Accion cuando arrojas o expandis una reserva
         */
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

        /**
         * Muestra el modal de confirmacion
         */
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
        
        /**
         * Checkea la disponibilidad
         */
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

        /**
         * Trae los pasajeros y Consumos de una reserva
         */
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
        };
        
        /**
         * Mueve el elemento 15px adelante para simunal el ingreso del pasajero
         */
        var moveElementToRightPosition = function(element) {
            element.css({'left' : parseInt(element.position().left) + parseInt(15)});
        };
        
        /**
         * Agregar tooltips
         */
        var validateCheckInnProcess = function(event, element) {
            var hoy = new Date();
            var dia = hoy.getDate();
            var mes = hoy.getMonth();
            var anio= hoy.getFullYear();
            var today = new Date(anio, mes, dia);
            if((event.end >= today && event.start <= today || today == event.end)) {
                if (event.status != 'Check inn' && event.status != 'Check out') {
                    $(element).append('<div class="warning-pending-status ' + event.status +  ' " title="Por favor verifique el check-inn de esta reserva"></div>');
                    $(element).tooltip({                        
                        content : '<b>Atencion: </b>Esta reserva deberia tener el check-inn realizado. '
                            + 'De otro modo no podrá operar algunos servicios de Divarius.',
                        animation : false
                    });
                }
            }
        };

        $calendar.fullCalendar({
		theme: true,
                header: {
                    left: 'calendarHeader',
                    center: 'prev, next, today, resourceNextWeeks, resourceMonth',
                    right: 'title'
                },
                slotEventOverlap : false,
                editable: true,
                defaultView: 'resourceMonth',
                monthNames: 		Lang.Fullcalendar.monthNames,
                monthNamesShort: 	['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                dayNames: 		Lang.Fullcalendar.dayNames,
                dayNamesShort: 		['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
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
                        resourceNextWeeks: 'd/M <p>ddd</p>'
                },
                numberOfWeeks: 3,
                weekPrefix: Lang.Fullcalendar.buttonText.week,
                firstDay: 0,
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
                    var thisEvent = event, 
                    mensaje = '<p>NECESITAMOS TU ATENCION</p>';
                    mensaje += '<p>Estas a punto de modificar las fechas de una reserva.</p>';
                    mensaje += '<p><span style="font-size: 13px; font-family: Open sans; background: none repeat scroll 0 0 #008000; color: #FFFFFF; display: inline-block; margin-top: 2px; padding: 5px;">' + $.fullCalendar.formatDate(event.start, "dddd dd 'de' MMM", Lang.Fullcalendar) + '.';
                    mensaje += ' — ' + $.fullCalendar.formatDate(event.end, "dddd dd 'de' MMM", Lang.Fullcalendar) + '.</span></p>';
                    createConfirmationDialog(mensaje);
                    showUpConfirmationDialog(thisEvent);
                },
                eventAfterRender : function(event, element, view) {
                    moveElementToRightPosition(element);
                    validateCheckInnProcess(event, element);
                    showTooltip(element, event, element);
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
                resourceRender: function(resource, element, view) {
                    showResourceTooltip();
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
