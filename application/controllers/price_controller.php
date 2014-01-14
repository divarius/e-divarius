<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class price_controller extends CI_Controller {

    private $_guestProfile;

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            //user is already logged in
            redirect('ingresar');
        } else {
            $this->load->library('carabiner');
            $carabiner_config = array(
                'script_dir' => 'public/js/',
                'style_dir' => 'public/css/',
                'cache_dir' => 'public/cache/',
                'base_uri' => base_url(),
                'combine' => TRUE,
                'dev' => TRUE
            );
            $this->carabiner->config($carabiner_config);
            $this->load->library('grocery_CRUD');
            $this->_guestProfile = $this->session->userdata('logged_in');
        }
    }

    /**
     *   ABM categorias
     */
    public function manager_price() {
        if (!$this->session->userdata('logged_in')) {
            //user is already logged in
            redirect('ingresar');
        } else {
            try {
                $crud = new grocery_CRUD();
                $crud->set_theme('twitter-bootstrap');
                $crud->set_table('listas_de_precios');
                $crud->where('id_establecimiento', $this->_guestProfile['guestData']['id_establecimiento']);
                $crud->set_subject('Precios');
                /**/
                $crud->callback_after_insert(array($this, 'setIdEstablecimiento'));

                $crud->fields('nombre', 'fecha_desde', 'fecha_hasta', 'tipo');
                $crud->columns('nombre', 'Fecha de Aplicacion', 'estado', 'tipo');
                $crud->callback_column('Fecha de Aplicacion', array($this, '_callback_fecha_aplicacion'));
                $crud->callback_column('estado', array($this, '_callback_estado'));
                $crud->display_as('name', 'Lista de Precios');

                $crud->unset_edit();
                $crud->unset_delete();

                $crud->add_action('Editar Precios', '', '', '', array($this, 'addPriceAction'));
                $crud->add_action('Configurar', '', '', '', array($this, 'configPriceAction'));
                //$crud->add_action('Eliminar', '', '', '', array($this, 'deletePriceAction'));

                $output = $crud->render();
                $this->output($output);
            } catch (Exception $e) {
                show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
            }
        }
    }

    public function output($output = null) {
        $this->load->view('default_layout/default_abm.php', $output);
    }

    function setIdEstablecimiento($post_array, $primary_key) {
        $lista_de_precio = array(
            "id_establecimiento" => $this->_guestProfile['guestData']['id_establecimiento']
        );
        return $this->db->update('listas_de_precios', $lista_de_precio, array('id' => $primary_key));
    }

    public function _callback_fecha_aplicacion($value, $row) {
        $result = '';
        if ($row->fecha_desde == '' && $row->fecha_hasta == '') {
            $result = '<p style="color:#000; text-align:center;">SIN COTA DE TIEMPO</p>';
        } else {
            $today = new DateTime();
            $startDate = new DateTime($row->fecha_desde);
            $endDate = new DateTime($row->fecha_hasta);
            $interval = $today->diff($endDate);
            if ($today <= $endDate) {
                $result = '<p style="color:#000; text-align:center;">Del ';
                $result .= $startDate->format('d/m/Y') . ' al ' . $endDate->format('d/m/Y');
                $result .= '</p>';
            } else {
                $result = '<p style="color:red; text-align:center;">Del ';
                $result .= $startDate->format('d/m/Y') . ' al ' . $endDate->format('d/m/Y');
                $result .= '</p>';
            }
        }
        return $result;
    }

    public function _callback_estado($value, $row) {
        $result = '';
        $update = '';
        if ($row->fecha_desde == '' && $row->fecha_hasta == '') {
            $result = '<p style="color:green; text-align:center;">ACTIVA</p>';
            $update = 'ACTIVA';
        } else {
            $today = new DateTime();
            $startDate = new DateTime($row->fecha_desde);
            $endDate = new DateTime($row->fecha_hasta);
            $interval = $today->diff($endDate);
            if ($today < $startDate) {
                $result = '<p style="color:#000; text-align:center;">FUERA DE FECHA</p>';
                $update = 'FUERA DE FECHA';
            } else if ($today >= $startDate && $today <= $endDate) {
                $result = '<p style="color:green; text-align:center;">EN CURSO</p>';
                $update = 'EN CURSO';
            } else if ($today > $endDate) {
                $result = '<p style="color:red; text-align:center;">EXPIRADA</p>';
                $update = 'EXPIRADA';
            }
        }
        $lista_de_precio = array(
            "estado" => $update
        );
        $this->db->update('listas_de_precios', $lista_de_precio, array('id' => $row->id));
        return $result;
    }

    public function addPriceAction($primary_key, $row) {
        if ($row->tipo == 'Precios Unificados') {
            return site_url('precios/unificados/' . $row->id);
        } else if ($row->tipo == 'Precios Individuales por Unidad') {
            return site_url('precios/individuales/' . $row->id);
        } else if ($row->tipo == 'Precios por Categoria') {
            return site_url('precios/precio-por-categoria/' . $row->id);
        } else {
            return site_url('precios/unificados/' . $row->id);
        }
    }

    public function configPriceAction($primary_key, $row) {
        return site_url('lista-de-precio/edit/' . $row->id);
    }

    public function unit_prices() {
        //init
        $listaId = $this->uri->segment(3);
        $IdEstablecimiento = $this->_guestProfile['guestData']['id_establecimiento'];
        $data['lista'] = $this->price_model->listLookUp($IdEstablecimiento, $listaId);

        $isPriceUpdated = $this->price_model->priceLookUp($listaId);
        $data['precio_noche'] = (isset($isPriceUpdated[0]['precio_noche'])) ? $isPriceUpdated[0]["precio_noche"] : '';
        $data['estadia_minima'] = (isset($isPriceUpdated[0]['estadia_minima'])) ? $isPriceUpdated[0]["estadia_minima"] : '';
        //load rules
        $rules = $this->config->item('price_rules');
        if (isset($_POST['guardar'])) {
            $data['precio_noche'] = $this->input->post('precio_noche');
            $data['estadia_minima'] = $this->input->post('estadia_minima');

            $this->form_validation->set_rules($rules); //check with the rules
            if ($this->form_validation->run() === FALSE) {
                //validation failed
                $data['msg'] = $this->lang->line('um_form_error');
                $this->load->view('price_list/precios-unificados', $data);
            } else {
                //validation passed
                $dbdata = array(
                    'precio_noche' => $this->input->post('precio_noche'),
                    'estadia_minima' => $this->input->post('estadia_minima'),
                    'id_lista' => $data['lista']['id']
                );
                if (empty($isPriceUpdated)) {
                    $this->price_manager->add_unit_resort($dbdata, $IdEstablecimiento);
                } else {
                    $this->price_manager->update_unit_resort($dbdata, $IdEstablecimiento);
                }

                //render the view
                $data['msg'] = 'Los Precios se guardarion correctamente';
                redirect('lista-de-precio/success/' . $data['lista']['id']);
            }
        } else {
            //render the view
            $this->load->view('price_list/precios-unificados', $data);
        }
    }

    /**
     * Controllador de precios individuales
     */
    public function individual_prices() {
        //init
        $listaId = $this->uri->segment(3);
        $precio_x_resort = $this->price_model->resort_prices_by_list($listaId);
        if (count($precio_x_resort) > 0) {
            $resorts = getResortsPrices($listaId);
        } else {
            $resorts = getResorts($listaId);
        }
        $data['resorts'] = $resorts;

        $IdEstablecimiento = $this->_guestProfile['guestData']['id_establecimiento'];
        $data['lista'] = $this->price_model->listLookUp($IdEstablecimiento, $listaId);
        $data['listaId'] = $listaId;

        $array_datos = array();
        $contador = 0;
        if (isset($_POST['guardar'])) {

            foreach ($_POST as $campo => $valor) {
                $campo_array = explode('_', $campo);

                if ($campo_array[0] == 'precio') {
                    $contador++;
                    $resortId = $campo_array[1];
                    $array_datos[$contador]['resortId'] = $resortId;
                    $array_datos[$contador]['precio'] = $valor;
                }

                if ($campo_array[0] == 'estadia-minima') {
                    $array_datos[$contador]['estadia'] = $valor;
                }
            }

            foreach ($array_datos as $key => $array_values) {
                $isPriceUpdated = $this->price_model->priceByResortLookUp($listaId, $array_values['resortId']);

                //validation passed
                $dbdata = array(
                    'precio_noche' => $array_values['precio'],
                    'estadia_minima' => $array_values['estadia'],
                    'id_lista' => $data['lista']['id'],
                    'id_resort' => $array_values['resortId']
                );

                if (empty($isPriceUpdated)) {
                    $this->price_manager->add_unit_resort_individual($dbdata);
                } else {
                    $this->price_manager->update_unit_resort_individual($dbdata);
                }
            }

            //render the view
            $data['msg'] = 'Los Precios se guardarion correctamente';
            redirect('lista-de-precio/success/' . $data['lista']['id']);
        } else {
            //render the view
            $this->load->view('price_list/precios-individuales', $data);
        }
    }

    /**
     * Controllador de precios individuales
     */
    public function category_prices() {
        //init
        $listaId = $this->uri->segment(3);
        $precio_x_resort = $this->price_model->resort_prices_by_list($listaId);

        if (count($precio_x_resort) > 0) {
            $resorts = getResortsPrices($listaId);
        } else {
            $resorts = getResorts($listaId);
        }

        $data['resorts'] = $resorts;

        $IdEstablecimiento = $this->_guestProfile['guestData']['id_establecimiento'];
        $data['lista'] = $this->price_model->listLookUp($IdEstablecimiento, $listaId);
        $data['listaId'] = $listaId;

        $array_datos = array();
        $contador = 0;
        $contador_resorts = 0;
        $resorts_category = array();

        if (isset($_POST['guardar'])) {

            foreach ($_POST as $campo => $valor) {
                $campo_array = explode('_', $campo);

                if ($campo_array[0] == 'precio') {
                    $contador++;
                    $categoryId = $campo_array[1];

                    //Obtiene los resorts por categoria
                    $resorts_category = $this->resort_model->getResortsByCategory($IdEstablecimiento, $categoryId);
                    $contador_resorts = $contador;

                    foreach ($resorts_category as $clave => $valores) {
                        $array_datos[$contador_resorts]['resortId'] = $valores['id'];
                        $array_datos[$contador_resorts]['precio'] = $valor;
                        $array_datos[$contador_resorts]['id_lista'] = $listaId;
                        $contador_resorts++;
                    }
                }

                if ($campo_array[0] == 'estadia-minima') {
                    $contador_resorts = $contador;
                    $resorts_category = $this->resort_model->getResortsByCategory($IdEstablecimiento, $categoryId);
                    foreach ($resorts_category as $clave => $valores) {
                        $array_datos[$contador_resorts]['estadia'] = $valor;
                        $contador_resorts++;
                    }
                    $contador = $contador_resorts - 1;
                }
            }

            foreach ($array_datos as $key => $array_values) {
                $isPriceUpdated = $this->price_model->priceByResortLookUp($listaId, $array_values['resortId']);

                //validation passed
                $dbdata = array(
                    'precio_noche' => $array_values['precio'],
                    'estadia_minima' => $array_values['estadia'],
                    'id_lista' => $data['lista']['id'],
                    'id_resort' => $array_values['resortId']
                );

                if (empty($isPriceUpdated)) {
                    $this->price_manager->add_unit_resort_individual($dbdata);
                } else {
                    $this->price_manager->update_unit_resort_individual($dbdata);
                }
            }

            //render the view
            $data['msg'] = 'Los Precios se guardarion correctamente';
            redirect('lista-de-precio/success/' . $data['lista']['id']);
        } else {
            //render the view
            $this->load->view('price_list/precio-por-categoria', $data);
        }
    }

}
