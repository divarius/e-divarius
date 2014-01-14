<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Divarius</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Full Calendar -->
    <!-- Base -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <?php
        $css = array(
            array('jquery-ui/jquery-ui-1.10.3.custom.min.css'),
            array('jquery-ui/jquery.multiselect.css'),
            array('jquery-ui/jquery.multiselect.filter.css'),
            array('fullcalendar/fullcalendar.css'),
            array('fullcalendar/fullcalendar.print.css', 'print'),
            array('fullcalendar/theme.css'),
            array('bootstrap/bootstrap.css'),
            array('modules/dashboard/dashboard.css'),
            array('modules/dashboard/popover.css'),
            array('modules/dashboard/confirmationDialog.css'),
            array('base.css'),
            array('jquery.chosen/chosen.css')
        );
        // create group
        $this->carabiner->group('grupoDashboard', array('css' => $css));           
        $this->carabiner->display('grupoDashboard');
    ?>
    <script>
        //* hide all elements & show preloader
        document.documentElement.className += 'js';
    </script>
</head>
<body id="internal" class="dashboard">
    <div id="loading_layer" style="display:none"><img src="<?=base_url()?>public/img/ajax_loader.gif" alt="" /></div>
    <?php echo globalNavigation() ?>
        <div id="resevas" class="container">