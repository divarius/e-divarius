<?php $this->load->view('price_list/header'); ?>
<form id="buildReservationForm" method="post" action="">
    <div id="abmTop" class="row">
        <div class="span12">
            <div class="row-fluid">
                <div class="span6">
                    <h2 class="span12">Lista: <?php echo $lista['nombre'] ?></h2>
                    <div class="row-fluid">
                        <div class="span6">Desde <?php echo $lista['fecha_desde']; ?></div>
                        <div class="span6">Hasta <?php echo $lista['fecha_hasta']; ?></div>  
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
                <hr>
                <?php foreach ($resorts as $categoria => $resort_categoria): ?>
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span2"><span><?= $categoria ?></span></div>
                                <div class="span6">
                                    <input type="text" placeholder="Precio($)" class="input-mini" name="precio_<?=$resort_categoria[0]['id_categoria']?>" value="<?php echo (isset($resort_categoria[0]['precio_noche'])?$resort_categoria[0]['precio_noche']:'') ?>" />
                                    <input type="text" placeholder="Estadia Minima" class="input-mini" name="estadia-minima_<?=$resort_categoria[0]['id_categoria']?>" value="<?php echo (isset($resort_categoria[0]['estadia_minima'])?$resort_categoria[0]['estadia_minima']:'') ?>" />
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <input type="hidden" name="listaId" value="<?= $listaId ?>">
</form>
<?php $this->load->view('price_list/footer'); ?>