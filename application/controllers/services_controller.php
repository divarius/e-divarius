<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class services_controller extends CI_Controller {

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
         *   ABM Servicios
	*/
	public function manager_service()
	{
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                try{
                    $crud = new grocery_CRUD();
                    $crud->set_theme('twitter-bootstrap');
                    $crud->set_table('servicio_x_establecimiento');
                    $crud->where('id_establecimiento', $this->_guestProfile['guestData']['id_establecimiento']);
                    $crud->fields('nombre', 'precio', 'descripcion');
                    $crud->required_fields('nombre', 'precio');
                    $crud->columns('nombre', 'precio');
                    $crud->set_subject('Servicio');
                    
                    /**/
                    $crud->callback_after_insert(array($this, 'setIdEstablecimiento'));
                    $crud->unset_export();
                    $output = $crud->render();
                    $this->output($output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}               
            }
        }
        
        /**
         * Injecta la view
         * @param type $output 
         */
        public function output($output = null)
	{
            $this->load->view('default_layout/default_abm.php', $output);
	}
        
        /**
         * Setea el id de 
         * @param type $post_array
         * @param type $primary_key
         * @return type 
         */
        function setIdEstablecimiento($post_array, $primary_key)
        {
            $servicio = array(
                "id_establecimiento" => $this->_guestProfile['guestData']['id_establecimiento']
            );
            return $this->db->update('servicio_x_establecimiento', $servicio, array('id' => $primary_key));
        }

}