<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function statusBar()
{
    $html = <<<HTML
               <ul class="res-estados">
                    <li><span class="seleccion"></span>Seleccion</li>
                    <li><span class="libre"></span>Libre</li>
                    <li><span class="no-disponible"></span>No Disponible</li>
                    <li><span class="reserva-web"></span>Reserva Web</li>
                    <li><span class="pendiente-pago"></span>Pendiente de Pago</li>
                    <li><span class="confirmado-ocupado"></span>Confirmado/Ocupado</li>
                </ul> 
HTML;
    return $html;
}

?>