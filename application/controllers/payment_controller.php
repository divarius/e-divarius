<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class payment_controller extends CI_Controller {

        private $_guestProfile;
        
	public function __construct() {
            parent::__construct();
            $this->load->library('carabiner');
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
         *   ABM payment
	*/
	public function manager_payment()
	{
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                try{
                    $crud = new grocery_CRUD();
                    $crud->set_theme('twitter-bootstrap');
                    $crud->set_table('formas_de_pago');
                    $crud->where('id_establecimiento', $this->_guestProfile['guestData']['id_establecimiento']);
                    $crud->set_subject('Forma de Pago');
                    
                    /**/
                    $crud->callback_after_insert(array($this, 'setIdEstablecimiento'));
                    
                    $crud->fields('forma_de_pago');
                    $crud->columns('forma_de_pago');
                    
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
        
        function setIdEstablecimiento($post_array, $primary_key)
        {
            $payment = array(
                "id_establecimiento" => $this->_guestProfile['guestData']['id_establecimiento']
            );
            return $this->db->update('formas_de_pago', $payment, array('id' => $primary_key));
        }
}