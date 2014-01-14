</div>
    <footer>
        <figure><img src="<?=base_url()?>public/img/footer_divarius.png"></figure>
    </footer>

    <!-- JQUERY -->
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
   
    <!-- config -->
    <script>
        var $calendar = $('#calendar');
        var popover = '.popover';
        var settings = {
            base_url : '<?= base_url() ?>',
            category : '<?php echo (isset($_GET['categoria'])) ? $_GET['categoria'] : '' ?>',
            resouces : '<?= base_url() ?>resort/getresorts'
        }
        if (settings.category) {
            settings.resouces = settings.resouces + '?category=' + settings.category;
        } 
    </script>
    <?php
        // Define JS
        $js = array(
            array('bootstrap/bootstrap.min.js'),
            array('jquery/jquery.validate.min.js'),
            array('jquery/jquery-ui-1.10.3.custom.js'),
            array('jquery/jquery.ui.datepicker-es.js'),
            array('jquery/chosen.jquery.min.js'),
            array('jquery/jquery.nicescroll.min.js'),          
            array('fullcalendar/fullcalendar.js'),
            array('fullcalendar/dateformat.js'),
            array('fullcalendar/langES.js'),
            array('jquery/jquery.multiselect.min.js'),
            array('jquery/jquery.multiselect.filter.js'),
            array('jquery/jquery.multiselect.es.js'),
            array('modules/dashboard/calendar.js'),
            array('modules/dashboard/popover.js'),
            array('modules/dashboard/dashboard.js'),
            array('base.js')
        );
        // create group
        $this->carabiner->group('grupobase', array('js' => $js));           
        $this->carabiner->display('grupobase');
    ?>
        <script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/localization/messages_es.js' type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                    //* show all elements & remove preloader
                    setTimeout('$("html").removeClass("js")',1000);
            });
        </script>
</body>
</html>