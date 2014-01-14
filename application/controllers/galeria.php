<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class galeria extends CI_Controller {

    
        private $_guestProfile;
        
	function __construct()
        {
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
           
            $this->load->library('image_CRUD');
            $this->_guestProfile = $this->session->userdata('logged_in');
	}
        
        
        public function add()
        {
            $image_crud = new image_CRUD();
	
            $image_crud->set_primary_key_field('id');
            $image_crud->set_url_field('url');
            $image_crud->set_table('galeria_x_resort')
                ->set_relation_field('id_resort')
                ->set_ordering_field('priority')
                ->set_image_path('assets/uploads/gallery');

            $output = $image_crud->render();

            $this->_gallery_output($output);
        }
        
        public function _gallery_output($output = null)
	{
            $this->load->view('gallery/resort_gallery.php', $output);
	}
}