<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function calendarNavigation($idEstablecimiento)
{
    $selectedItem = isset($_GET['categoria']) ? $_GET['categoria'] : null;
    $categoryList = getHtmlCategorys($idEstablecimiento, $selectedItem);
    $menu = dashMenu();
    $html = <<<HTML
               <div id="calendarHeader">
                <div class="row-fluid">
                    {$menu}
                    <div class="buscador-container">
                        <input type="text" name="search" placeholder="Buscar Pasajero o Reserva"/>
                        <button id="buscar-container" type="button">Buscar</button>
                    </div>
                    <div class="categorias-container">
                        <div class="controls"> 
                            {$categoryList}
                        </div>
                        <div id="control-items">
                            
                        </div>
                    </div>
                    <!--<div class="organizar-container">
                        <div class="controls"> 
                            <select style="width: 120px;" class="chosen-select" data-placeholder="Ordenar" name="orden">
                                <option value="">Ordenar</option>
                            </select>
                        </div>
                        <div id="control-items">
                            
                        </div>
                    </div>-->
                </div>
            </div> 
HTML;
    $script = <<<SCRIPT
               $('.dropdown-toggle').dropdown();
SCRIPT;
    return $html;
}

?>