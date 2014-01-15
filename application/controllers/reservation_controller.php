<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class reservation_controller extends CI_Controller {

	private $_guestProfile;
        
	public function __construct() {
            parent::__construct();
            $this->load->library('carabiner');
            $carabiner_config = array(
                'script_dir' => 'public/js/', 
                'style_dir'  => 'public/css/',
                'cache_dir'  => 'public/cache/',
                'base_uri'   => base_url(),
                'combine'    => TRUE,
                'dev'        => TRUE
            );
            $this->carabiner->config($carabiner_config);
            $this->load->library('grocery_CRUD');
            $this->_guestProfile = $this->session->userdata('logged_in');
        }
        
        /**
         *   ABM categorias
	*/
	public function manager_reservation()
	{
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                try{
                    $resorts = $this->resort_model->getResortsIds($this->_guestProfile['guestData']['id_establecimiento']);
                    $crud = new grocery_CRUD();
                    $crud->set_theme('twitter-bootstrap');
                    $crud->set_table('reservations');
                    foreach($resorts as $resort) {
                        $crud->or_where('id_resort', $resort);
                    }
                    $crud->set_relation(
                        'id_resort',
                        'resorts',
                        'name',
                            array(
                                'resorts.id_establecimiento' => $this->_guestProfile['guestData']['id_establecimiento']
                            )
                    );
                    $crud->field_type('id_servicio','multiselect', $this->service_model->get_services_for_reservation($this->_guestProfile['guestData']['id_establecimiento']));
                    $crud->display_as('nombre_apellido', 'Nombre y Apellido');
                    $crud->display_as('start', 'Check inn');
                    $crud->display_as('end', 'Check out');
                    $crud->display_as('email', 'eMail');
                    $crud->display_as('id_resort', 'Habitacion');
                    $crud->display_as('status', 'Estado');
                    $crud->display_as('id_servicio', 'Servicios Adicionales');
                    $crud->fields(
                        'nombre_apellido', 
                        'dni', 
                        'email', 
                        'telefono', 
                        'cant_adultos', 
                        'cant_menores', 
                        'forma_pago_reserva', 
                        'monto_reserva',
                        'forma_pago_final',
                        'monto_final',
                        'status',
                        'observaciones',
                        'id_servicio'
                    );
                    
                    $crud->columns('id_resort', 'nombre_apellido', 'email', 'start', 'end', 'status');
                    
                    $crud->callback_column('status', array($this, '_callback_status'));
                    $crud->set_subject('Reserva');
                    $crud->unset_export();
                    $output = $crud->render();
                    $this->output($output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}               
            }
        }
        
        public function _callback_status($value, $row)
        {
            $statusColor = array(
                'Habitacion no disponible' => '#CCCCCC',
                'Reserva Web Pendiente de Pago'  => '#BBA8F2',
                'Reserva Pendiente de Pago medios tradicionales' => '#ECBE91',
                'Reserva Confirmada' => '#AEDF7D',
                'Checkinn' => '#7193E1',
                'Checkout' => '#888888'
            );
            return '<p style="text-align: center; background:' . $statusColor[$value] . '">' . $value . '</p>';
        }
        
        
        
        public function output($output = null)
	{
            $this->load->view('default_layout/default_abm.php',$output);
	}
        
        
	/**
         *   displays the registration form and process the requests
	*/
	public function add_reservation()
	{
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('login');
            } else {
                
                if ($this->input->post()) {
                    //init
                    $data['nombre'] = '';
                    $data['dni'] = '';
                    $data['email'] = '';
                    $data['nombre_apellido'] = '';
                    $data['telefono'] = '';
                    $data['start'] = '';
                    $data['end'] = '';
                    $data['observaciones'] = '';
                    $data['id_resort'] = '';
                    $data['id_servicio'] = '';
                    $data['status'] = '';
                    $data['cant_adultos'] = '';
                    $data['cant_menores'] = '';
                    $data['forma_pago_reserva'] = '';
                    $data['monto_reserva'] = '';
                    $data['forma_pago_final'] = '';
                    $data['monto_final'] = '';
                    
                    //load rules
                    $rules = $this->config->item('reservation_rules');
                    //default msg
                    
                    $data['msg'] = $this->lang->line('um_form_msg');
                    
                    
                    if (isset($_POST['action'])) { 
                        $dbdata = $this->builReservation();
                        $this->form_validation->set_rules($rules);//check with the rules                       
                        if ($this->form_validation->run() === FALSE) {
                            echo json_encode($data['msg']=$this->lang->line('um_form_error'));
                        } else {
                            //validation passed
                            $dbdata = $this->builReservation($this->input->post());
                            foreach($this->input->post('resort') as $resortReservation){
                                $dbdata['id_resort'] = $resortReservation;
                                $this->reservation_manager->add_reservation($dbdata);
                            }
                            echo json_encode($this->input->post('resort'));
                        }
                    } else {
                        echo json_encode('Error');
                    }
                }
            }
        }
        
        public function builReservation()
        {
            return array(
                'dni' => $this->input->post('dni'),
                'email' => $this->input->post('email'),
                'nombre_apellido' => $this->input->post('nombreyapellido'),
                'telefono' => $this->input->post('telefono'),
                'start' => $this->input->post('start'),
                'end' => $this->input->post('end'),
                'observaciones' => $this->input->post('descripcion'),
                'cant_adultos' => $this->input->post('cant_adultos'),
                'cant_menores' => $this->input->post('cant_menores'),
                'id_servicio' => @implode(',', $this->input->post('services')),
                'forma_pago_reserva' => $this->input->post('forma_pago_reserva'),
                'monto_reserva' => $this->input->post('monto_reserva'),
                'forma_pago_final' => $this->input->post('forma_pago_final'),
                'monto_final' => $this->input->post('monto_final'),
                'status' => 'Reserva Pendiente de Pago medios tradicionales'
            );
        }
        
        public function delete_reservation()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $this->reservation_manager->delete_reservation($this->input->post('reservation_id'));
                }
            }
        }
        
        public function update_reservation()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $this->reservation_manager->update_reservation($this->input->post());
                }
            }
        }
        
        public function remove_pax()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $result = $this->reservation_manager->remove_pax($this->input->post('pax_id'));
                    echo json_encode($result);
                }
            }
        }
        
        public function add_pax()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $nombre = $this->input->post('nombre');
                    $dni = $this->input->post('dni');
                    $reserva = $this->input->post('reserva');
                    $result = $this->reservation_manager->add_pax($nombre, $dni, $reserva);
                    echo json_encode($result);
                }
            }
        }
        
        public function remove_consumo()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $result = $this->reservation_manager->remove_consumo($this->input->post('consumo_id'));
                    echo json_encode($result);
                }
            }
        }
        
        public function add_consumo()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $nombre = $this->input->post('nombre');
                    $costo = $this->input->post('costo');
                    $reserva = $this->input->post('reserva');
                    $result = $this->reservation_manager->add_consumo($nombre, $costo, $reserva);
                    echo json_encode($result);
                }
            }
        }
        
        public function checkInnProcess()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $reservationId = $this->input->post('reservationId');
                    $result = $this->reservation_manager->statusProcess($reservationId, 'Checkinn');
                    echo json_encode($result);
                }
            }
        }
        
        public function checkOutProcess()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $reservationId = $this->input->post('reservationId');
                    $result = $this->reservation_manager->statusProcess($reservationId, 'Checkout');
                    echo json_encode($result);
                }
            }
        }
        
        public function confirmarProcess()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                if (isset($_POST['action'])) {                   
                    $reservationId = $this->input->post('reservationId');
                    $result = $this->reservation_manager->statusProcess($reservationId, 'Reserva Confirmada');
                    echo json_encode($result);
                }
            }
        }
        
        public function sendEmail()
        {
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    return json_encode(array('error'));
            } else {
                if (isset($_POST['action'])) {
                    $reservationId = $this->input->post('reservationId');
                    $email = $this->input->post('email');
                    $asunto = $this->input->post('asunto');
                    $contenido = $this->input->post('contenido');
                    $result = $this->reservation_manager->sendEmail($email, $reservationId, $asunto, $contenido);
                    echo json_encode($result);
                }
            }
        }
}