<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

////////////////////////////////////////
//////USER MANAGER FOR CODEIGNITER//////
/////////////version 1.0.0//////////////
////////////////////////////////////////
///written by - Anuradha Jayathilaka
///email - me@anu3d.info
///web - www.anu3d.info
////////////////////////////////////////
//This code is free to use in any project.
//please leave this information if you're using this. thanks :)
////////////////////////////////////////


/*
settings for new users
*/


/*
this array defines the rules for the registration form
you may add your own. but it should match the database and the model
*/
$config['price_rules']= array(
        array(
            'field' => 'precio_noche',
            'label' => 'Valor por Noche',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'estadia_minima',
            'label' => 'Estadia Minima',
            'rules' => 'trim|required'
        )
);

