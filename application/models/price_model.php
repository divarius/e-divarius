<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class price_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Devuelve las Categorias
     * @return type 
     */
    public function listLookUp($idEstablecimiento, $listaId) {
        $this->db->select('*');
        $this->db->where('id_establecimiento = "' . $idEstablecimiento . '" and ' . 'id = "' . $listaId . '"');
        $query = $this->db->get(LISTA_DE_PRECIOS)->result_array();
        return $query[0];
    }

    public function priceLookUp($listaId) {
        $this->db->select('precio_noche, estadia_minima');
        $this->db->where('id_lista = "' . $listaId . '"');
        $query = $this->db->get(PRECIOS_DE_HABITACIONES)->result_array();
        return $query;
    }

    public function priceByResortLookUp($listaId, $idResort) {
        $this->db->select('precio_noche, estadia_minima');
        $this->db->where('id_lista = ' . $listaId . ' AND id_resort = ' . $idResort);
        $query = $this->db->get(PRECIOS_DE_HABITACIONES);
        return $query->num_rows();
    }

    public function priceByCategoryLookUp($listaId, $idResort) {
        $this->db->select('precio_noche, estadia_minima');
        $this->db->where('id_lista = ' . $listaId . ' AND category = ' . $idResort);
        $query = $this->db->get(PRECIOS_DE_HABITACIONES);
        return $query->num_rows();
    }

    public function add_unit_price($dbdata) {
        $this->db->insert('precio_x_resort', $dbdata);
    }

    public function update_unit_price($dbdata) {
        $this->db->where('id_lista = "' . $dbdata['id_lista'] . '" and id_resort = "' . $dbdata['id_resort'] . '"');
        return $this->db->update('precio_x_resort', $dbdata);
    }

    public function getActiveList($idEstablecimiento) {
        $this->db->select('*');
        $this->db->where('id_establecimiento = "' . $idEstablecimiento . '" and ' . 'estado = "ACTIVA"');
        $query = $this->db->get(LISTA_DE_PRECIOS)->result_array();
        return $query;
    }

    public function getInCourseList($idEstablecimiento) {
        $this->db->select('*');
        $this->db->where('id_establecimiento = "' . $idEstablecimiento . '" and ' . 'estado = "EN CURSO"');
        $query = $this->db->get(LISTA_DE_PRECIOS)->result_array();
        return $query;
    }

    public function resort_prices_by_list($id) {
        $this->db->select('*');
        $this->db->where('id_lista = ' . $id);
        $query = $this->db->get(PRECIOS_DE_HABITACIONES)->result_array();
        return $query;
    }

}

?>