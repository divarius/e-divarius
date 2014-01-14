<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function globalNavigation()
{
    $userName = renderName();
    $homeUri = site_url('/');
    $logoutUri = site_url('salir');
    
    
    $html = <<<HTML
    <header>
    <div class="row-fluid">
        <div class="span5 logo"><a href="{$homeUri}"></a></div>
		<div class="span7">
                <div id="globalNavidation" class="pull-right">
                        <ul>
                                <!--<li class="global-li">
                                        <div class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><strong>Alertas</strong></a>
                                                <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-closer">
					<li class="nav-header">
						<i class="icon-warning-sign"></i>
						8 Notifications
					</li>
					<li>
						<a href="#">
							<div class="clearfix">
								<span class="pull-left">
									Followers
								</span>
								<span class="pull-right badge badge-info">+11</span>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							Ver todas las notificaciones
							<i class="icon-arrow-right"></i>
						</a>
					</li>
				</ul>
                                        </div>
                                </li>
                                <li class="global-li">
                                        <div class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><strong>Notificaciones</strong></a>
                                                <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-closer">
					<li class="nav-header">
						<i class="icon-warning-sign"></i>
						8 Notifications
					</li>
					<li>
						<a href="#">
							<div class="clearfix">
								<span class="pull-left">
									Followers
								</span>
								<span class="pull-right badge badge-info">+11</span>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							Ver todas las notificaciones
							<i class="icon-arrow-right"></i>
						</a>
					</li>
				</ul>
                                        </div>
                                </li>-->
                                <li class="global-li">
                                        <div class="dropdown">
                                                <a class="print" href="#">
                                                </a>
                                        </div>
                                </li>
                                <li class="global-li">
                                        <div class="dropdown">
                                                <a class="refresh" href="#">
                                                </a>
                                        </div>
                                </li>
                                <li class="global-li">
                                        <div class="dropdown">
                                                <a class="ayuda" href="#">
                                                </a>
                                        </div>
                                </li>
                                <li class="global-li last">
                                        <div class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><strong>{$userName}</strong></a>
                                                <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-closer" role="menu" aria-labelledby="dLabel">
                                                <li>
                                                    <a href="{$logoutUri}"><i class="icon-off"></i> Salir</a>
                                                </li>
                                                </ul>
                                        </div>
                                </li>
                        </ul>
                </div> 
        	</div>
	</div>
</header>
HTML;
    return $html;
}

?>