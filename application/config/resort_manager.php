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
$config['resortactive']=true;


/*
this array defines the rules for the registration form
you may add your own. but it should match the database and the model
*/
$config['resort_rules']= array(
        array(
            'field' => 'name',
            'label' => 'Nombre',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'descripcion',
            'label' => 'Descripcion',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'category',
            'label' => 'Categoria',
            'rules' => 'trim|required'
        )
);

