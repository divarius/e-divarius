<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class reservation_manager {

    function __construct(){

    }

    //registers a user with the given data
    public function add_reservation($dbdata)
    {
        $CI =& get_instance();
        //data prep
        $dbdata=array(
            'nombre_apellido' => $dbdata['nombre_apellido'],
            'dni' => $dbdata['dni'],
            'email' => $dbdata['email'],
            'telefono' => $dbdata['telefono'],
            'start' => $dbdata['start'],
            'end' => $dbdata['end'],
            'observaciones' => $dbdata['observaciones'],
            'cant_adultos' => $dbdata['cant_adultos'],
            'cant_menores' => $dbdata['cant_menores'],
            'forma_pago_reserva' => $dbdata['forma_pago_reserva'],
            'monto_reserva' => $dbdata['monto_reserva'],
            'forma_pago_final' => $dbdata['forma_pago_final'],
            'monto_final' => $dbdata['monto_final'],
            'id_resort' => $dbdata['id_resort'],
            'id_servicio' => $dbdata['id_servicio'],
            'status' => 'Reserva Pendiente de Pago medios tradicionales'
        );
        return $CI->reservation_model->add_reservation($dbdata);
    }
    
    public function delete_reservation($reservationId)
    {
        $CI =& get_instance();
        //data prep
        $dbdata = array(
            'id' => $reservationId
        );
        return $CI->reservation_model->delete_reservation($dbdata);
    }
    
    public function update_reservation($reservation)
    {
        $CI =& get_instance();
        //data prep
        $dbdata = array(
            'start' => $reservation['start'],
            'end' => $reservation['end'],
            'id_resort' => $reservation['id_resort'],
        );
        return  $CI->reservation_model->update_reservation($reservation['id'], $dbdata);
    }
    
    /**
     * Borra pasajeros
     * @param type $paxId
     * @return type 
     */
    public function remove_pax($paxId)
    {
        $CI =& get_instance();
        //data prep
        $dbdata = array(
            'id' => $paxId
        );
        return $CI->reservation_model->remove_pax($dbdata);
    }
    
    /**
     * Agrega pasajeros
     */
    public function add_pax($nombre, $dni, $reserva)
    {
        $CI =& get_instance();
        //data prep
        $dbdata = array(
            'nombre' => $nombre,
            'dni' => $dni,
            'id_reservation' => $reserva,
        );
        return $CI->reservation_model->add_pax($dbdata);
    }
    
    /**
     * Borra consumo
     * @param type $consumoId
     * @return type 
     */
    public function remove_consumo($consumoId)
    {
        $CI =& get_instance();
        //data prep
        $dbdata = array(
            'id' => $consumoId
        );
        return $CI->reservation_model->remove_consumo($dbdata);
    }
    
    /**
     * Agrega pasajeros
     */
    public function add_consumo($nombre, $costo, $reserva)
    {
        $CI =& get_instance();
        //data prep
        $dbdata = array(
            'nombre' => $nombre,
            'costo' => $costo,
            'id_reservation' => $reserva,
        );
        return $CI->reservation_model->add_consumo($dbdata);
    }
    
    /**
     * Agrega pasajeros
     */
    public function statusProcess($reservation, $status)
    {
        $CI =& get_instance();
        //data prep
        $dbdata = array(
            'status' => $status,
        );
        return  $CI->reservation_model->update_reservation($reservation, $dbdata);
    }
    
    /**
     * Agrega pasajeros
     */
    public function sendEmail($email, $reservationId, $asunto, $contenido)
    {
        $CI =& get_instance();
        //data prep
        $dbdata = array(
            'email' => $email,
            'reservationId' => $reservationId,
            'asunto' => $asunto,
            'contenido' => $contenido
        );
        return  $CI->reservation_model->sendEmail($dbdata);
    }
}