<?php $this->load->view('price_list/header');?>
    <?php 
        if (isset($msg)) {
            echo $msg;
        }
    ?>
    <form id="buildReservationForm" method="post" action="">
        <div id="abmTop" class="row">
            <div class="span12">
                <div class="row-fluid">
                    <div class="span6">
                        <h2 class="span12">Lista: <?php echo $lista['nombre']?></h2>
                        <div class="row-fluid">
                            <?php 
                                if ($lista['fecha_desde'] === null && $lista['fecha_hasta'] === null) {
                                ?>
                                <div class="span12 txt_green">Precio sin cota de tiempo</div>
                                <?php
                            } else {
                                ?>
                                <div class="span6 txt_green">Desde <?php echo $lista['fecha_desde']; ?></div>
                                <div class="span6 txt_green">Hasta <?php echo $lista['fecha_hasta']; ?></div>  
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="span6 content-gral-nav">
                        <div class="pull-right">
                            <input value="Guardar" name="guardar"  class="midium-btn blue-btn submit-form" type="submit"/>
                            <a href="<?php echo site_url('lista-de-precio'); ?>" class="midium-btn grey-btn return-to-list">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <div class="row">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="span2"><span>Valor por Noche</span></div>
                            <div class="span6">
                                <input type="text" placeholder="$" class="money" name="precio_noche" value="<?php echo $precio_noche ?>"/>
                                <?php echo form_error('precio_noche'); ?>
                            </div>
                            <div class="span4"><span>Este valor se tomará como único para todas las unidades habitacionales.</span></div>
                            
                        </div>
                    </div>
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="span2"><span>Estadía Mínima</span></div>
                            <div class="span6">
                                <input type="text" placeholder="Dias" class="money" name="estadia_minima" value="<?php echo $estadia_minima ?>" />
                                <?php echo form_error('estadia_minima'); ?>
                            </div>
                            <div class="span4"><span>Si se deja en blanco la reserva no tendrá mínimo requerido.</span></div>
                            
                        </div>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $this->load->view('price_list/footer');?>