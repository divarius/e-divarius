<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class category_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    /**
     *  Agrega una Categoria
     */
    function add_category($dbdata)
    {
        return $this->db->insert(HABITACIONES_CATEGORIA, $dbdata);
    }
    
    /**
     * Elimina una Categoria
     * @param type $dbdata
     * @return type 
     */
    function delete_category($dbdata)
    {
        return $this->db->delete(HABITACIONES_CATEGORIA, $dbdata);
    }
    
    /**
     * Devuelve las Categorias
     * @return type 
     */
    public function get_categorys($idEstablecimiento)
    {
        $this->db->select('*');
        $this->db->where('id_establecimiento = ' .$idEstablecimiento);
        return $this->db->get(HABITACIONES_CATEGORIA)->result_array();
    }
    
    /**
     * Devuelve una Categoria
     * @return type 
     */
    public function getCategoryById($id)
    {
        $this->db->select('*');
        $this->db->where('id = ' . $id);
        $query = $this->db->get(HABITACIONES_CATEGORIA)->result_array();
        return $query[0];
    }
    
    /**
     * Actualiza la categoria
     * @param type $id
     * @param type $dbdata
     * @return type 
     */
    function update_category($id, $dbdata)
    {
        return $this->db->update(HABITACIONES_CATEGORIA, $dbdata, array('id' => $id));
    }
    
}
?>