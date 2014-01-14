<?php $this->load->view('dashboard/header');?>
    <?php echo calendarNavigation($idEstablecimiento); ?>
    <div id='calendar'></div>
    <div class="row statusbarContent">
        <?php echo statusBar(); ?>
    </div>
    <?php $this->load->view('dashboard/reservationModal');?>
    <?php $this->load->view('dashboard/resumenModal');?>
<?php $this->load->view('dashboard/footer');?>
