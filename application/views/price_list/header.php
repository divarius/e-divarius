<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Divarius</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Jquery UI -->
    <link href='<?=base_url()?>public/css/jquery-ui/jquery.multiselect.css' rel="stylesheet" type="text/css"/>
    <link href='<?=base_url()?>public/css/jquery-ui/jquery.multiselect.filter.css' rel="stylesheet" type="text/css"/>

  
    <!-- themacalendario -->
    <link href='<?=base_url()?>public/css/modules/dashboard/dashboard.css' rel="stylesheet" type="text/css"/>
    <link href='<?=base_url()?>public/css/modules/dashboard/confirmationDialog.css' rel="stylesheet" type="text/css"/>
    
    <!-- Base -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <?php
        $css = array(
            array('jquery-ui/jquery-ui-1.10.3.custom.min.css'),
            array('jquery-ui/jquery.multiselect.css'),
            array('jquery-ui/jquery.multiselect.filter.css'),
            array('bootstrap/bootstrap.css'),
            array('modules/dashboard/dashboard.css'),
            array('base.css'),
            array('jquery.chosen/chosen.css')
        );
        // create group
        $this->carabiner->group('grupoDashboard', array('css' => $css));           
        $this->carabiner->display('grupoDashboard');
    ?> 
</head>
<body id="internal" class="dashboard">
    <?php echo globalNavigation() ?>
        <div id="lista-precios" class="container">