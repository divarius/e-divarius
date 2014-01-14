<div id="reservationForm" class="hidden col_form">
    <form id="buildReservationForm">
    <div class="row modal_header">
        <div class="span4">
            <h1>Nueva Reserva</h1>
        </div>
        <div class="span_center">
            <a class="btn_pendiente" href="#"></a>
            <span><small class="txt_orange">PENDIENTE</small><!--254213--></span>
        </div>
    </div>
    <div class="row">
        <div  class="modal_left_col">
        <div class="row top_info">
                <div class="span3"><small>Fecha Desde</small><input id="startDate" class="datepicker" name="startDate" type="text" required /></div>
                <div class="span3"><small>Fecha Hasta</small><input id="endDate" class="datepicker" name="endDate" type="text" required /></div>
                <div class="span2" style="text-align: center;"><small>Habitación</small><h2 id="unidad"></h2></div>
                <div class="span2"><small>Estadía Total</small><h2 class="numero_noches"></h2></div>
                <div class="span2"><small>Disponibilidad</small><h2><span id="numero_disponibilidad"></span>&nbsp;Hab.</h2></div>
        </div>
        <div style="margin-bottom: 15px;">
            <div class="span10">
                <div class="control-group">
                    <div class="controls">
                    <select name="resort[]" multiple id="resort" style="width:750px" required>
                    <?php 
                        foreach($resorts as $categoria => $grupResort)
                        {
                            ?><optgroup label="<?php echo $categoria; ?>"><?php
                            foreach($grupResort as $resort) {
                            ?><option data-id="<?php echo $resort['resortId']; ?>" value="<?php echo $resort['resortId']; ?>"><?php echo $resort['name'] . ' - ' . $resort['descripcion']; ?></option><?php
                            }
                            ?></optgroup><?php
                        }
                    ?>
                    </select>
                    </div>
                </div>
            </div>
        </div>
        <section>
            <div class="span5"><h2 class="datos">Datos del Pasajero</h2></div>
            <div class="span5"  style="padding-top:10px;">
                <div class="row-fluid">
                    <div class="span4 offset2">
                        <div class="row-fluid">
                            <div class="span6"><label class="pull-right">Adultos</label></div>
                            <div class="span6 controls">
                                <div class="pull-right">
                                    <select id="cant_adultos" name="cant_adultos" data-placeholder="" class="chosen-select" style="width:140px;" required>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span4 offset1">
                        <div class="row-fluid">
                            <div class="span6"><label class="pull-right">Menores</label></div>
                            <div class="span6 controls">
                                <div class="pull-right">
                                    <select id="cant_menores" name="cant_menores" data-placeholder="" class="chosen-select" style="width:140px;" required>
                                        <option>0</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="group-data">
        <div class="span5">
                <div class="control-group">
                    <div class="controls">
                        <input id="nombreyapellido" tabindex="0" placeholder="Nombre y Apellido" name="nombreyapellido" type="text" value="" required />
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input id="telefono" placeholder="Telefono de Contacto" name="telefono" type="text" value="" class="number" required />
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <input id="email" placeholder="Email" name="email" type="text" value="" required class="email"/>
                    </div>    
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input id="dni" placeholder="DNI" name="dni" type="text" value="" />
                    </div>
                </div>
        </div>
        <div class="span5">
            <div class="control-group">
                <div class="controls">
                    <select name="services[]" data-placeholder="Servicios Adicionales" multiple style="width:349px" id="servicios-adicionales" class="multiple-ui-selector-base">
                        <?php 
                            foreach($services as $service)
                            {
                                ?><option value="<?php echo $service['id']; ?>"><?php echo $service['nombre']. ' - ' . $service['descripcion'];?></option><?php
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <textarea id="descripcion" placeholder="Notas / Observaciones" name="descripcion" type="textarea" style="height:128px; width: 343px;"></textarea>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="span5 wrap_items"><h2>Resumen de Cuenta</h2>
            <div>
                <span class="numero_noches"></span> 
                <samp class="monto servicios">$<span id="monto" name="monto" class="input_monto"></span></samp>
            </div>
            <div>
                <span class="txt">Servicios Adicionales</span>
                <samp class="monto servicios">$<span id="resumen_servicios_monto" name="servicios_monto" class="input_monto"></span></samp>
            </div>
            <div>
                <span class="txt">Descuento</span> 
                <samp class="descuento monto servicios">$<input id="descuento" name="descuento" class="input_monto" value="0"></samp>
            </div>
            <div>
                <span class="txt">Monto Final</span> 
                <samp class="monto servicios">$<span id="resumen_monto_final" class="input_monto"></span></samp>
            </div>
        </div>
        <div class="span5 wrap_items">
            <h2 class="formapago">Forma de Pago 
                <span class="cuenta">
                <!--<div class="roundedThree">
                    <input type="checkbox" <?php echo ($resort['resortactive']) ? 'checked=checked' : '';  ?> value="None" id="rounded<?php echo $resort['resortId']; ?>" name="check" />
                    <label for="rounded<?php echo $resort['resortId']; ?>"></label>
                    <p class="checkbox-title">Cuenta Corriente</p>
                </div>-->
            </h2> 
            <div class="row-fluid formapagowrap">
                <div class="span6">
                    <div class="row-fluid">
                        <div class="span12"> <label class="control-label" for="habitaciones">Reserva</label></div>
                    </div>
                    <div class="row-fluid">
                        <div class="span9 controls">
                            <select name="forma_pago_reserva" data-placeholder="Sel. Medio" class="chosen-select" style="width:340px;">
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 controls">
                            <input id="monto_reserva" placeholder="Monto"  name="monto_reserva" type="text"/>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 controls">
                            <!--<div class="roundedThree">
                                <input type="checkbox" <?php echo ($resort['resortactive']) ? 'checked=checked' : '';  ?> value="None" id="rounded<?php echo $resort['resortId']; ?>" name="check" />
                                <label for="rounded<?php echo $resort['resortId']; ?>"></label>
                                <p class="checkbox-title">Pagado</p>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="row-fluid">
                        <div class="span12"><label class="control-label" for="habitaciones">Final</label></div>
                    </div>
                    <div class="row-fluid">
                        <div class="span9 controls">
                            <select name="forma_pago_final" data-placeholder="Sel. Medio" class="chosen-select" style="width:340px;">
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 controls">
                            <input id="monto_final" placeholder="Monto"  name="monto_final" type="text"/>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 controls">
                            <!--<div class="roundedThree">
                                <input type="checkbox" <?php echo ($resort['resortactive']) ? 'checked=checked' : '';  ?> value="None" id="rounded<?php echo $resort['resortId']; ?>" name="check" />
                                <label for="rounded<?php echo $resort['resortId']; ?>"></label>
                                <p class="checkbox-title">Pagado</p>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input name="action" value="action" type="hidden" />
    </form>
    </div>
    </div> 
    <div id="ajax-loading" class="hide">
        <div class="loading">Cargando, actualizando cambios...</div>        
    </div>    
</div> 