<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class resort_controller extends CI_Controller {

    
    private $_guestProfile;
        
	function __construct()
    {
        parent::__construct();
        $this->load->library('carabiner');
        $this->load->library('pagination');
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
        $this->load->library('image_CRUD');
        $this->_guestProfile = $this->session->userdata('logged_in');
	}
        
        /**
         *   ABM categorias
	*/
	public function manager_habitacion()
	{
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                try{
                    $crud = new grocery_CRUD();
                    $crud->set_theme('habitaciones');
                    $crud->set_table('resorts');
                    $crud->where('resorts.id_establecimiento', $this->_guestProfile['guestData']['id_establecimiento']);
                    $crud->set_subject('habitacion');
                    $crud->required_fields('name', 'id_categoria');     
                    $crud->columns('name', 'id_categoria', 'resortactive');
                    
                    $crud->display_as('name', 'Nombre');
                    $crud->display_as('id_categoria', 'Categoria');
                    $crud->display_as('cant_estandar_adultos', 'Cant. Estandar Adul.');
                    $crud->display_as('cant_estandar_menores', 'Cant. Estandar Meno.');
                    $crud->display_as('cant_maxima_adultos', 'Cant. Max. Adul.');
                    $crud->display_as('cant_maxima_menores', 'Cant. Max. Meno.');
                    $crud->display_as('resortactive', 'Estado');
                    
                    $crud->set_relation(
                        'id_categoria',
                        'categoria_habitaciones',
                        'nombre',
                            array(
                                'categoria_habitaciones.id_establecimiento' => $this->_guestProfile['guestData']['id_establecimiento']
                            )
                    );
					
                    $crud->callback_field('name',array($this, 'field_nombre_callback'));
                    $crud->callback_field('descripcion',array($this, 'field_descripcion_callback'));
                    $crud->callback_field('observaciones',array($this, 'field_observaciones_callback'));
					
                    $crud->unset_texteditor(array('descripcion', 'full_text', 'observaciones', 'full_text'));
                    $crud->callback_column('resortactive', array($this, '_callback_estado'));
                    
                    $crud->add_action('Fotos', '', '', '', array($this, 'addPhotosAction'));
                    
                    /**/
                    $crud->callback_after_insert(array($this, 'setIdEstablecimiento'));
                    
                    $output = $crud->render();
                    $this->output($output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}               
            }
        }
        
        public function output($output = null)
	{
            $this->load->view('default_layout/default_abm.php',$output);
	}
        
        /**
         * Setea el id de 
         * @param type $post_array
         * @param type $primary_key
         * @return type 
         */
        function setIdEstablecimiento($post_array, $primary_key)
        {
            $habitacion = array(
                "id_establecimiento" => $this->_guestProfile['guestData']['id_establecimiento']
            );
            return $this->db->update('resorts', $habitacion, array('id' => $primary_key));
        }
        
        public function _callback_estado($value, $row)
        {
            $estado = '<p style="color:red; text-align:center;">Deshabilitada</p>';
            if ($value == 1) {
                $estado = '<p style="color:green; text-align:center;">Habilitada</p>';
            }
            return $estado;
        }
		
        function field_nombre_callback($value = '', $primary_key = null)
        {
            return '<input type="text" maxlength="250" value="'.$value.'" name="name" id="field-name" placeholder="Número, Identificación o Nombre">';
        }
        
        function field_descripcion_callback($value = '', $primary_key = null)
        {
            return '<textarea name="descripcion" id="field-descripcion" placeholder="Descripción">'.$value.'</textarea>';
        }
        
        function field_observaciones_callback($value = '', $primary_key = null)
        {
            return '<textarea name="observaciones" id="field-observaciones"  placeholder="Especificaciones especiales">'.$value.'</textarea>';
        }
        
        /**
         * Obtiene las Habitaciones/Cabañas/etc 
         */
        public function getResorts()
        {
            if (!$this->session->userdata('logged_in')) {
                //uesr is already logged in
                redirect('login');
            } else {
                $category = '';
                if (isset($_GET['category'])) {
                    $category = $this->input->get('category');
                }
                $results = $this->resort_model->get_resorts_from_place($this->_guestProfile['guestData']['id_establecimiento'], $category);
                echo json_encode($results);
            }
        }
        
        /**
         * Obtiene las reservas
         */
        public function getReservations()
        {
            if (!$this->session->userdata('logged_in')) {
                redirect('login');
            } else {
                $results = $this->reservation_model->get_reservations();
                echo json_encode($this->buildReservations($results));
            }
        }
        
        /**
         * buildea las reservas
         */
        public function buildReservations($reservations)
        {
            $results = array();
            foreach($reservations as $reservation)
            {
                $results[] = array(
                    'title' => $reservation['nombre_apellido'],
                    'start' => $reservation['start'],
                    'end' => $reservation['end'],
                    'allDay' => true,
                    'resource' => array($reservation['id_resort']),
                    'color' => $this->builStatusColor($reservation['status']),
                    'borderColor' => '#FFF',
                    'email' => $reservation['email'],
                    'textColor' => '#000000',
                    'dni' => $reservation['dni'],
                    'telefono' => $reservation['telefono'],
                    'description' => $reservation['observaciones'],
                    'nombre_apellido' => $reservation['nombre_apellido'],
                    'xid' => $reservation['id'],
                    'status' => $this->buildStatusPrefix($reservation['status']),
                    'pax' => $reservation['cant_adultos'] + $reservation['cant_menores'] ,
                    'consumos' => $this->getConsumosFromReservation($reservation['id'])
                );
            }
            return $results;
        }
        
        /**
         * Devuelve los pasajeros de una reservacion
         */
        public function getPaxFromReservation($reservationId)
        {
            return $this->reservation_model->getPaxFromReservation($reservationId);
        }
        
        /**
         * Devuelve los consumos extras de una reservacion en particular
         */
        public function getConsumosFromReservation($reservationId)
        {
            return $this->reservation_model->getConsumosFromReservation($reservationId);
        }
        
        public function getPaxAnsConsumos()
        {
            if (!$this->session->userdata('logged_in')) {
                redirect('login');
            } else {
                if($_POST['action']) {
                    $reservationId = $this->input->post('reservationId');
                    echo json_encode(array(
                        'pax' => $this->getPaxFromReservation($reservationId),
                        'consumos' => $this->getConsumosFromReservation($reservationId)
                    ));
                }
            }
        }
        
        /**
         * Build de los colores de status de esas reservas
         * @param type $reservationStatus
         * @return type 
         */
        public function builStatusColor($reservationStatus) 
        {
            $statusColor = array(
                'Habitacion no disponible' => '#CCCCCC',
                'Reserva Web Pendiente de Pago'  => '#BBA8F2',
                'Reserva Pendiente de Pago medios tradicionales' => '#ECBE91',
                'Reserva Confirmada' => '#AEDF7D',
                'Checkinn' => '#7193E1',
                'Checkout' => '#888888'
            );
            return($statusColor[$reservationStatus]);
        }
        
        /**
         * Build de los colores de status de esas reservas
         * @param type $reservationStatus
         * @return type 
         */
        public function buildStatusPrefix($reservationStatus) 
        {
            $statusColor = array(
                'Habitacion no disponible' => 'No disponible',
                'Reserva Web Pendiente de Pago'  => 'Pendiente web',
                'Reserva Pendiente de Pago medios tradicionales' => 'Pendiente Trad.',
                'Reserva Confirmada' => 'Confirmada',
                'Checkinn' => 'Check inn',
                'Checkout' => 'Check out'
            );
            return($statusColor[$reservationStatus]);
        }
        
        /**
         * Checkea la disponibilidad de las habitaciones
         */
        public function checkAvailability()
        {
            if (isset($_POST['action'])) { 
                $start = $this->input->post('start');
                $end = $this->input->post('end');
                $idEstablecimiento = $this->_guestProfile['guestData']['id_establecimiento'];
                $result = $this->resort_model->checkAvailability($start, $end, $idEstablecimiento);
                echo json_encode($result);
            }
        }
        
        function addPhotosAction($primary_key , $row)
        {
            return site_url('galeria/add/' . $row->id);
        }
        
        public function addGallery()
        {
            $image_crud = new image_CRUD();
	
            $image_crud->set_primary_key_field('id');
            $image_crud->set_url_field('url');
            $image_crud->set_title_field('titulo');
            $image_crud->set_table('galeria_x_resort')
                ->set_relation_field('resort_id')
                ->set_ordering_field('priority')
                ->set_image_path('assets/uploads/gallery');

            $output = $image_crud->render();

            $this->_gallery_output($output);
        }
        
        public function _gallery_output($output = null)
	{
            $this->load->view('resort_manager/resort_gallery.php', $output);
	}
}