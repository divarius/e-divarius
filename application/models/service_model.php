<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class service_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    /*
    COMMON FUNCTIONS
    */
    function add_service($dbdata)
    {
        return $this->db->insert('servicio_x_establecimiento', $dbdata);
    }
    
    /**
     *
     * @param type $dbdata
     * @return type 
     */
    function delete_service($dbdata)
    {
        return $this->db->delete('servicio_x_establecimiento', $dbdata);
    }
    
    /**
     *
     * @return type 
     */
    public function get_services($idEstablecimiento)
    {
        $this->db->select('*');
        $this->db->where('id_establecimiento =' . $idEstablecimiento);        
        return $this->db->get('servicio_x_establecimiento')->result_array();
    }
    /**
     *
     * @return type 
     */
    public function get_services_for_reservation($idEstablecimiento)
    {
        $this->db->select('*');
        $this->db->where('id_establecimiento =' . $idEstablecimiento);        
        $services = $this->db->get('servicio_x_establecimiento')->result_array();
        $buildServices = array();
        if (!empty($services)) {
            foreach($services as $service) {
                $buildServices[$service['id']] = $service['nombre'];
            }
        }
        return $buildServices;
    }
}
?>