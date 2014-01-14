<?php $this->load->view('resort_manager/header');?>
<div class="container">
    <div class="row">
        <div class="span12">
            <div class="row-fluid">
                <div class="span2">
                    <?php echo dashMenu(); ?>
                </div>
                <div class="span2">
                    <div>
                        <a href="<?php echo site_url('habitaciones/add') ?>">Nuevo</a>
                    </div>
                </div>
                <div class="span8"></div>
                <br><br>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span8">
            <table id="back-habitaciones" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>
                            Identificación
                        </th>
                        <th>
                            Categoría
                        </th>
                        <th>
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody>
                        <?php 
                        foreach($resorts as $categoria => $grupResort)
                        {
                                    foreach($grupResort as $resort) {
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="item-content"><?php echo $resort['name'] . ' ' . $resort['descripcion']; ?>
                                                    <div class="edit-group">
                                                        <a class="edit" href="<?php echo site_url('habitaciones/editar/') . '/' . $resort['resortId']; ?>"></a>
                                                        <a class="delete" href="<?php echo site_url('habitaciones/eliminar/') . '/' . $resort['resortId']; ?>"></a>
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $categoria ?>
                                            </td>
                                            <td>
                                                <div class="roundedThree">
                                                    <input type="checkbox" <?php echo ($resort['resortactive']) ? 'checked=checked' : '';  ?> value="None" id="rounded<?php echo $resort['resortId']; ?>" name="check" />
                                                    <label for="rounded<?php echo $resort['resortId']; ?>"></label>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?><?php
                        }
                        ?>
                </tbody>
            </table>
            <?php echo $this->pagination->create_links(); ?>    
        </div>
    </div>
</div>
<?php $this->load->view('resort_manager/footer');?>