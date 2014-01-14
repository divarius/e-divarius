<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Price_manager {

    function __construct(){

    }

    //graba los precios unificados
    public function add_unit_resort($dbdata, $idEstablecimiento)
    {
        $CI =& get_instance();
        $resorts = $CI->resort_model->get_resorts_from_place($idEstablecimiento);
        foreach ($resorts as $resort) {
            $dbdata['id_resort'] = $resort['id'];
            
            $CI->price_model->add_unit_price($dbdata);
        }
    }
    
    //graba los precios unificados
    public function update_unit_resort($dbdata, $idEstablecimiento)
    {
        $CI =& get_instance();
        $resorts = $CI->resort_model->get_resorts_from_place($idEstablecimiento);
        foreach ($resorts as $resort) {
            $dbdata['id_resort'] = $resort['id'];
            $CI->price_model->update_unit_price($dbdata);
        }
    }
    
    
    public function add_unit_resort_individual($dbdata)
    {
        $CI =& get_instance();
        $CI->price_model->add_unit_price($dbdata);
        
    }
    
    //graba los precios unificados
    public function update_unit_resort_individual($dbdata)
    {
        $CI =& get_instance();
        $CI->price_model->update_unit_price($dbdata);
        
    }
}