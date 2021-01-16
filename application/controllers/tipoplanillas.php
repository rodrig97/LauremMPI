<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class tipoplanillas extends CI_Controller {
    
    public $usuario;
    
    public function __construct(){
        
        parent::__construct();
         
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
        
        $this->load->library(array('App/persona','App/planilla','App/tipoplanilla'));
    }

    public function gestionar()
    {
        $this->load->view('planillas/p_planillatipo_gestionar');
    }
    
    public function nuevo()
    {

        
        
    }   

    public function registrar()
    {

    }

    public function modificar()
    {

    }

    public function actualizar()
    {

    }

    public function desactivar()
    {

    }

    public function eliminar()
    {

    }

    public function registro($modo = '')
    {
  
        header("Content-Type: application/json");
       
        $rs = $this->tipoplanilla->get_list();

        $response = array();
        $c = 1;

        foreach($rs as $reg)
        {
            $data['id']   =   trim($reg['plati_key']);
            $data['col1'] =  $c;
            $data['col2'] =  trim($reg['plati_nombre']);
            $data['col3'] =  ( trim($reg['plati_tipoempleado']) == '' ? '-----' : trim($reg['plati_tipoempleado']) );
            $data['col4'] =  ( trim($reg['plati_abrev']) == '' ? '-----' : trim($reg['plati_abrev']) );
            $data['col5'] =  ( (trim($reg['plati_tiene_categoria']) == '1') ? 'Si' : 'No');
            $data['col6'] =  ( (trim($reg['plati_activo']) == '1') ? 'Si' : 'No');
            $data['col7'] =  trim($reg['total_empleados']); 

            $data['activo_asistencia'] = ($reg['asistencia'] == '1') ? 'Si' : 'No';
        
            $response[] = $data;
            $c++;
        }

        echo json_encode($response) ;

    }


    public function view()
    {   

        $view = $this->input->post('view');

        $view_id = $this->tipoplanilla->get_id($view);

        $info = $this->tipoplanilla->view($view_id);
 
        $this->load->view('planillas/v_tipoplanilla_view', array('info' => $info));

    }


    public function get_categorias()
    {

        $datos = $this->input->get();

        $plati_id = $this->tipoplanilla->get_id($datos['view']);

        $rs = $this->tipoplanilla->get_categorias($plati_id);

        $response = array();
        $c = 1;

        foreach($rs as $reg)
        {
            $data['id']   =   trim($reg['platica_key']);
            $data['col1'] =  $c;
            $data['col2'] =  trim($reg['platica_nombre']);
            $data['col3'] =  ( trim($reg['platica_descripcion']) == '' ? '-----' : trim($reg['platica_descripcion']) );
             

            $response[] = $data;
            $c++;
        }

        echo json_encode($response) ;

    }

    public function nueva_categoria()
    {

        $data = $this->input->post();


        $plati_id = $this->tipoplanilla->get_id($data['view']);

        $info = $this->tipoplanilla->view($plati_id);

        $this->load->view('planillas/v_tipoplanilla_categoria_nueva', array('view' => $data['view'], 'info' => $info ) );
    }


    public function registrar_categoria()
    {
            

        $data = $this->input->post();
 

        $params = array('nombre' => trim($data['nombre']),
                        'nombrecompleto' => trim($data['nombrecompleto']),
                        'descripcion' => trim($data['descripcion']),
                        'nombre' => trim($data['nombre']),
                        'plati' => trim($data['view']) );


        $rs = $this->tipoplanilla->registrar_categoria($params);


        $response =  array(
            
             'result' =>   ($rs) ? '1' : '0',
             'mensaje'  =>  ($rs) ? 'Categoria registrada satisfactoriamente' : $mensaje_error,
             'data' => array()
        );
        
        echo json_encode($response);

    }

    public function configuracion_sunat()
    {   

        $this->load->library(array('App/planillatiposunat', 'App/individuosunat' , 'App/persona'));

        $data = $this->input->post();

        $view_key = $data['view'];

        if($data['modo'] != 'trabajador' &&  $data['modo'] != 'tipoplanilla') die();


        if($data['modo'] == 'tipoplanilla')
        {

            $view_id = $this->tipoplanilla->get_id($view_key);
            $view_info = $this->tipoplanilla->get($view_id);            
            $parametros_sunat = $this->planillatiposunat->get($view_info['plati_id']);
 
        }
        else
        {
            $view_id = $this->persona->get_id($view_key);
            $view_info = $this->persona->get_info($view_id);
            $parametros_sunat = $this->individuosunat->get($view_info['indiv_id']);
        }

     //   var_dump($parametros_sunat);

        $this->load->library(array('App/sunatcatalogos'));      

        $tabla_ocupaciones = (INSTITUCION_PUBLICA == '1' ? 'ocupacion_sector_publico' : 'ocupacion_sectorprivado' );

        $catalogos = array(

                    'tipotrabajador' => $this->sunatcatalogos->get('tipotrabajador'),
                    'regimenlaboral' => $this->sunatcatalogos->get('regimenlaboral'),
                    'categoria_ocupacional' => $this->sunatcatalogos->get('categoria_ocupacional'),
                    'niveleducativo' => $this->sunatcatalogos->get('niveleducativo'),
                    'ocupaciones' => $this->sunatcatalogos->get($tabla_ocupaciones),
                    'tipo_contrato' => $this->sunatcatalogos->get('tipo_contrato'),
                    'tipopago' => $this->sunatcatalogos->get('tipopago'),
                    'periocidad_remuneracion' => $this->sunatcatalogos->get('periocidad_remuneracion'),
                    'regimendesalud' => $this->sunatcatalogos->get('regimendesalud'),
                    'establecimientos' => $this->sunatcatalogos->get('establecimientos')  

            );



        $this->load->view('planillas/v_tipoplanilla_sunat_configuracion',   array( 'modo' => $data['modo'], 
                                                                                   'catalogos' => $catalogos, 
                                                                                   'view_info' => $view_info, 
                                                                                   'parametros_sunat' => $parametros_sunat));
    
    }

    public function actualizar_parametros_sunat()
    {   
        $this->load->library(array('App/planillatiposunat', 'App/individuosunat'));

        $data = $this->input->post();

        foreach ($data as $key => $value)
        {   
           $data[$key] = (is_array($value == FALSE) ? trim($value) : $value );
        }

        $key = trim($data['view']); 

        if($data['modoformulario'] == 'tipoplanilla')
        {
            $plati_id = $this->tipoplanilla->get_id($key);
        }
        else
        {
            $indiv_id = $this->persona->get_id($key);
        }

        $jornadamaxima = '0';
        $jornadaatipica = '0';
        $jornadanocturno = '0';

        if(is_array($data['jornadalaboral']) ==  TRUE )
        {   
            foreach ($data['jornadalaboral'] as $key => $value)
            {
                 if($value=='jormax') $jornadamaxima = '1';
                 if($value=='joratip') $jornadaatipica = '1';
                 if($value=='jorhornoc') $jornadanocturno = '1';    
            }
        }

        $values = array(

            'tipotrabajador'        => $data['tipotrabajador'],
            'regimenlaboral'        => $data['regimenlaboral'],
            'catagoriaocupacional'  => $data['categoriaocupacional'],
            'niveleducativo'        => $data['niveleducativo'],
            'ocupacion'             => $data['ocupacion'],
            'tipocontrato'          => $data['tipocontrato'],
            'tipopago'              => $data['tipopago'],
            'periocidadpago'        => $data['periocidadpago'],
            'establecimiento_ruc'   => RUC_INSTITUCION,
            'establecimiento'       => $data['establecimiento'],
            'jornadamaxima'         => $jornadamaxima,
            'jornadaatipica'        => $jornadaatipica,
            'jornadanocturno'       => $jornadanocturno,
            'situacionespecial'     => $data['situacionespecial'],
            'rembasica'             => $data['rembasica'],
            'sindicalizado'         => $data['sindicalizado'],
            'discapacitado'         => $data['discapacitado'],
            'sctr'                  => $data['sctr'],
            'regimensalud'          => $data['regimensalud']

            );
    

        
        if($data['modoformulario'] == 'tipoplanilla')
        {
            $rs =  $this->planillatiposunat->registrar($plati_id, $values);
        }
        else
        {   
            $values['ruc'] = $data['ruc']; 

            $rs = $this->individuosunat->registrar($indiv_id, $values); 
        }
      
      $response =  array(
          
           'result' =>   ($rs) ? '1' : '0',
           'mensaje'  =>  ($rs) ? 'Datos actualizados correctamente' : $mensaje_error,
           'data' => array()
      );
      
      echo json_encode($response);


    }
}