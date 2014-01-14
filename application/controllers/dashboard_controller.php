<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard_controller extends CI_Controller {

    private $_guestProfile;

    public function __construct() {
        parent::__construct();
        $this->_guestProfile = $this->session->userdata('logged_in');
    }
        
	public function index(){
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
        $this->load->view('dashboard/dashboard', array(
                'resorts' => getResorts(),
                'services' => $this->service_model->get_services($this->_guestProfile['guestData']['id_establecimiento']),
                'idEstablecimiento' => $this->_guestProfile['guestData']['id_establecimiento']
            )
        );
	}
}

?>