<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class resort_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->helper('text');
    }

    function add_resort($dbdata)
    {
        return $this->db->insert('resorts', $dbdata);
    }
    
    public function get_resorts_from_place($idEstablecimiento, $category = '')
    {
        /* Precios de los listados */
        $activeList = $this->price_model->getActiveList($idEstablecimiento);
        $inCourseList = $this->price_model->getInCourseList($idEstablecimiento);
        $listPrices = ($inCourseList) ? $inCourseList : $activeList;
        $prices = @$this->price_model->priceLookUp($listPrices[0]['id']);
                
        $this->db->select(HABITACIONES . '.*');
        $where = 'id_establecimiento = ' . $idEstablecimiento;
        $where .= ' and resortactive = 1 ';
        $where .= ($category != '') ?  ' and id_categoria= ' . $category : '';
        $this->db->where($where);
        
        $unidades = $this->db->get(HABITACIONES)->result_array();
        $unidades = $this->buildPricesxUnidad($unidades, $prices);
        
        //echo $this->db->last_query();
        return $this->buildResort($unidades);
    }
    
    public function buildPricesxUnidad($unidades, $prices)
    {
        $unidadesConPrecios = array();
        foreach($unidades as $unidad) {
            $unidad['precio_noche'] = (isset($prices[0]['precio_noche'])) ? $prices[0]['precio_noche'] : 'Sin Precio asignado';
            $unidad['estadia_minima'] = (isset($prices[0]['estadia_minima'])) ? $prices[0]['estadia_minima'] : 'Sin estadia minima asignada';
            $unidadesConPrecios[] = $unidad;
        }
        return $unidadesConPrecios;
    }
    
    
    /* Devuelve los resorts con fotos y precios */
    public function buildResort($resorts)
    {
        $i = 0;
        foreach($resorts as $resort) {
            /* Galeria de fotos */
            $galImg = array();
            $this->db->select('url');
            $this->db->where('id_resort = ' . $resort['id']);
            $handleImg = $this->db->get(HABITACIONES_GALERIA)->result_array();

            foreach($handleImg as $imgUrl) {
                $galImg[] = $imgUrl['url'];
            }           
            $name = (mb_strlen($resort['name'], 'UTF-8') >= 20) ? mb_substr($resort['name'], 0, 19, 'UTF-8') . '..' :  $resort['name']; 
            $skeleton = '<p class="resort-name">';
            $skeleton .= '<a data-estadia-minima="' . $resort['estadia_minima'] . '" data-precio="' . $resort['precio_noche'] . '"data-toggle="tooltip" data-descripcion="' . $resort['descripcion'] . '" title="' . $resort['name'] . '"';
            $skeleton .= (!empty($galImg)) ? ' data-img="' . implode(',', $galImg) . '"' : ''; 
            $skeleton .= '>' . $name . '</a></p>'; 
            $resorts[$i]['name'] = $skeleton;
            $resorts[$i]['unidad'] = $resort['name'];
            $i ++;
        }
        return $resorts;
    }
    
    public function get_resorts($data)
    {
        $this->db->select(HABITACIONES.'.id as resortId, ' . HABITACIONES.'.*,' . HABITACIONES_CATEGORIA . '.*');
        $this->db->where(HABITACIONES.'.id_establecimiento = ' . $data["id_establecimiento"]);
        $this->db->join(HABITACIONES_CATEGORIA, HABITACIONES_CATEGORIA . '.id =' . HABITACIONES . '.id_categoria');
        $result = $this->db->get(HABITACIONES)->result_array();
        $grupos = array();
        foreach($result as $habitacion) {
            if (!array_key_exists($habitacion['nombre'], $grupos)) {
                $grupos[$habitacion['nombre']][] = $habitacion;
            } else if (array_key_exists($habitacion['nombre'], $grupos)) {
                $grupos[$habitacion['nombre']][] = $habitacion;
            } else if ($habitacion['nombre'] == '') {
                $grupos['Otros'][] = $habitacion;
            }
        }
        return $grupos;
    }
    
    public function get_resorts_prices($data)
    {
        $this->db->select(HABITACIONES.'.id as resortId, ' . HABITACIONES.'.*,' . HABITACIONES_CATEGORIA . '.*,' . PRECIOS_DE_HABITACIONES  . '.*');
        $this->db->where(HABITACIONES.'.id_establecimiento = ' . $data["id_establecimiento"] . ' AND '. PRECIOS_DE_HABITACIONES . '.id_lista = ' . $data['id_lista']);
        $this->db->join(HABITACIONES_CATEGORIA, HABITACIONES_CATEGORIA . '.id =' . HABITACIONES . '.id_categoria');
        $this->db->join(PRECIOS_DE_HABITACIONES, PRECIOS_DE_HABITACIONES . '.id_resort =' . HABITACIONES . '.id');
        $result = $this->db->get(HABITACIONES)->result_array();
        $grupos = array();
        foreach($result as $habitacion) {
            if (!array_key_exists($habitacion['nombre'], $grupos)) {
                $grupos[$habitacion['nombre']][] = $habitacion;
            } else if (array_key_exists($habitacion['nombre'], $grupos)) {
                $grupos[$habitacion['nombre']][] = $habitacion;
            } else if ($habitacion['nombre'] == '') {
                $grupos['Otros'][] = $habitacion;
            }
        }
        return $grupos;
    }
    
    public function checkAvailability($start, $end, $idEstablecimiento)
    {
        $this->db->select('id_resort'); 
        $this->db->join(HABITACIONES, HABITACIONES . '.id = ' . RESERVAS . '.id_resort');
        $this->db->where('start BETWEEN "' . $start . '" AND "' . $end . '" OR end BETWEEN "' . $start . '" AND "' . $end . '" OR "' . $start . '" BETWEEN start AND end');
        $this->db->where('id_establecimiento = ' . $idEstablecimiento);
        $this->db->group_by('id_resort'); 
        $noDisponibles = $this->db->get(RESERVAS)->result_array();
        $username = $this->user_manager->this_user_name();
        $data = $this->um_users_model->get_user_details($username);
        
        $this->db->select('count(*) as cantidad');
        $this->db->where('id_establecimiento = ' . $data['id_establecimiento']);
        $disponibles = $this->db->get(HABITACIONES)->result_array();
        
        $disponibles[0]['cantidad'] = $disponibles[0]['cantidad'] - count($noDisponibles);
        $result = array(
            'noDisponibles' => $noDisponibles,
            'disponibles' => $disponibles
        );
        return $result;
    }
    
    public function getCategorias()
    {
        $this->db->select('*');
        $this->db->where('id_establecimiento = ' . $data["id_establecimiento"]);
        $query = $this->db->get('resorts');
        $query = $query->result_array();
        return $query;
    }
    
    public function getResortsIds($id_establecimiento)
    {
        $arResortsIds = array();
        $this->db->select(HABITACIONES.'.id as resortId');
        $this->db->where(HABITACIONES.'.id_establecimiento = ' . $id_establecimiento);
        $resorts = $this->db->get(HABITACIONES)->result_array();
        foreach ($resorts as $resort) {
            $arResortsIds[] = $resort['resortId'];
        }
        return $arResortsIds;
    }
    
    public function record_count($data)
    {
         $this->db->like('id_establecimiento', $data["id_establecimiento"]);
         $this->db->from(HABITACIONES);
         return  $this->db->count_all_results();
    }
    
    /**
     * 
     * @return type
     */
    public function getResortsByCategory($idEstablecimiento, $idCategory)
    {
        $this->db->select('*');
        $this->db->where('id_establecimiento = ' . $idEstablecimiento . ' AND id_categoria = ' . $idCategory);
        $query = $this->db->get('resorts')->result_array();
        return $query;
    }
}
?>