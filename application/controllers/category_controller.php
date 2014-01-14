<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class category_controller extends CI_Controller {

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
	public function manager_category()
	{
            if (!$this->session->userdata('logged_in')) {
                    //user is already logged in
                    redirect('ingresar');
            } else {
                try{
                    $crud = new grocery_CRUD();
                    $crud->set_theme('twitter-bootstrap');
                    $crud->set_table('categoria_habitaciones');
                    $crud->where('id_establecimiento', $this->_guestProfile['guestData']['id_establecimiento']);
                    $crud->set_subject('Categoria');
                    /**/
                    $crud->callback_after_insert(array($this, 'setIdEstablecimiento'));
                    
                    $crud->fields('nombre');
                    $crud->columns('nombre');
                    
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
            $categoria = array(
                "id_establecimiento" => $this->_guestProfile['guestData']['id_establecimiento']
            );
            return $this->db->update('categoria_habitaciones', $categoria, array('id' => $primary_key));
        }
}