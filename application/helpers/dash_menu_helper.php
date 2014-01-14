<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    function dashMenu()
    {
        $dashboardUrl = site_url('dashboard');
        $reservationUrl = site_url('reservas');
        $resortUri = site_url('habitaciones');
        $listasPreciosUri = site_url('lista-de-precio');
        $categoriasUri = site_url('categorias');
        $serviciosUri = site_url('servicios');
        $formaDePagoUri = site_url('forma-de-pago');
        $informesUri = site_url('informes');
        $configUri = site_url('configuracion');
        
        $html = <<<HTML
               <div class="menu-container">
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Menu</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li><a href="{$dashboardUrl}">Dashboard</a></li>
                                <li><a href="{$reservationUrl}">Reservas</a></li>
                                <li><a href="{$resortUri}">Habitaciones</a></li>
                                <li><a href="{$listasPreciosUri}">Precios</a></li>
                                <li><a href="{$categoriasUri}">Categorias</a></li>
                                <li><a href="{$serviciosUri}">Servicios</a></li>
                                <li><a href="{$formaDePagoUri}">Forma de Pago</a></li>
                                <!--<li><a href="{$informesUri}">Informes</a></li>
                                <li><a href="{$configUri}">Configuracion</a></li>-->
                            </ul>
                        </div>
                    </div>
HTML;
        return $html;                           
    }
?>
