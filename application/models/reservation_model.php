<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class reservation_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    /**
     *  Agrega una reserva a una habitacion
     */
    function add_reservation($dbdata)
    {
        return $this->db->insert(RESERVAS, $dbdata);
    }
    
    /**
     * Elimina una reserva
     * @param type $dbdata
     * @return type 
     */
    function delete_reservation($dbdata)
    {
        return $this->db->delete(RESERVAS, $dbdata);
    }
    
    /**
     * Devuelve las reservas
     * @return type 
     */
    public function get_reservations()
    {
        $this->db->select('*');
        $query = $this->db->get(RESERVAS)->result_array();
        return $query;
    }
    
    /**
     * Devuelve las reservas
     * @return type 
     */
    public function getReservationById($id)
    {
        $this->db->select('*');
        $this->db->where('id = ' . $id);
        $query = $this->db->get(RESERVAS)->result_array();
        return $query[0];
    }
    
    /**
     * Actualiza la reserva
     * @param type $id
     * @param type $dbdata
     * @return type 
     */
    function update_reservation($id, $dbdata)
    {
        return $this->db->update(RESERVAS, $dbdata, array('id' => $id));
    }
    
    
    /**
     * Devuelve los pasajeros de una reserva en particular
     * @param type $reservationId
     * @return type 
     */
    public function getPaxFromReservation($reservationId)
    {
        $this->db->select('*');
        $this->db->where('id_reservation =' . $reservationId);
        return  $this->db->get(PASAJEROS_X_RESERVA)->result_array();
    }
    
    /**
     * Devuelve los pasajeros de una reserva en particular
     * @param type $reservationId
     * @return type 
     */
    public function getConsumosFromReservation($reservationId)
    {
        $this->db->select('*');
        $this->db->where('id_reservation =' . $reservationId);
        return  $this->db->get(CONSUMOS_X_RESERVA)->result_array();
    }
    
    
    /**
     * Elimina un pasajero de la reserva
     * @param type $dbdata
     * @return type 
     */
    function remove_pax($dbdata)
    {
        return $this->db->delete(PASAJEROS_X_RESERVA, $dbdata);
    }
    
    /**
     *  Agrega una pasajeros a una reserva
     */
    function add_pax($dbdata)
    {
        if ($this->db->insert(PASAJEROS_X_RESERVA, $dbdata)) {
            return $this->db->insert_id();
        }
    }
    
    /**
     * Elimina un pasajero de la reserva
     * @param type $dbdata
     * @return type 
     */
    function remove_consumo($dbdata)
    {
        return $this->db->delete(CONSUMOS_X_RESERVA, $dbdata);
    }
    
    /**
     *  Agrega una pasajeros a una reserva
     */
    function add_consumo($dbdata)
    {
        if ($this->db->insert(CONSUMOS_X_RESERVA, $dbdata)) {
            return $this->db->insert_id();
        }
    }
    
    function sendEmail($dbdata) 
    {
        $url = base_url() . 'assets/templates/divarius_envio_from_dashboard.html';
        $html = file_get_contents($url);
        $html = str_replace('{asunto}', $dbdata['asunto'], $html);
        $html = str_replace('{contenido}', $dbdata['contenido'], $html);
        $reservationData = $this->getReservationById($dbdata['reservationId']);
        $reservationHtml = $this->buildReservationDataToEmail($reservationData);       
        $html = str_replace('{reservationData}', $reservationHtml, $html);

        
        $this->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailtype'] = 'html';
        $config['Return-Path'] = '/';

        $this->email->initialize($config);
        $this->email->from('info@divarius.com.ar', 'Divarius');
        $this->email->to($dbdata['email']);
        $this->email->subject($dbdata['asunto']);
        $this->email->message($html);
        $result = $this->email->send();
        return $result; 
    }
    
    /**
     * 
     */
    function buildReservationDataToEmail($reservationData)
    {
        $html = <<<HTML
            <p>Datos de la Reserva</p>
            <p>Nombre y Apellido: {$reservationData['nombre_apellido']}</p>
            <p>Telefono: {$reservationData['telefono']}</p>
            <p>Fecha de Ingreso: {$reservationData['start']}</p>
            <p>Fecha de Salida: {$reservationData['end']}</p>
            <p>Cantidad de Adultos: {$reservationData['cant_adultos']}</p>
            <p>Cantidad de Menores: {$reservationData['cant_menores']}</p>
HTML;
        return $html;
    }
}
?>