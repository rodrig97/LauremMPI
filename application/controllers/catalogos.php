<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
class catalogos extends CI_Controller {
    
    
    public $cargos;
    public $permisos;
    
    
    public function __construct()
    {
       
         parent::__construct();
   
         $this->load->library(array('users/user', 'App/tarea','App/anioeje', 'Catalogos/meta'));
     
    } 

    public function gestionar_metas()
    {   
          
         $anios = $this->anioeje->get_list( array() ) ;
 
         $this->load->view('catalogos/v_gestionar_metas', array('anios' => $anios));
   
    } 


    public function nueva_meta()
    {

        $data = $this->input->post();
        $params = array();
       
        if($data['option'] == 'modificar')
        {
            $tarea_id = $this->tarea->get_id( trim($data['view']));

            $tarea_info = $this->tarea->get_info($tarea_id); 
            $option = 'modificar';
        }
        else
        {
            $option = 'nueva';
        }

        $anios = $this->anioeje->get_list( array() ) ;
        
        $this->load->view('catalogos/v_nueva_meta', array('anios' => $anios, 'tarea_info' => $tarea_info, 'option' => $option ));
    }

    public function get_metas()
    { 

        $data = $this->input->get();

        $params = array('ano_eje' => $data['anio'], 'nombre' => strtoupper(trim($data['codigo_nombre'])) );

        $tareas = $this->tarea->get_list($params);
        
        $response = array();
        $c = 1;

        foreach ($tareas as $reg)
        {
            $data = array();

            $data['id']   = trim($reg['tarea_key']);
            $data['col1'] = $c;
            $data['col2'] = trim($reg['ano_eje']);
            $data['col3'] = trim($reg['tarea_codigo']);
            $data['col4'] = (trim($reg['tarea_nombre']) != '') ? trim($reg['tarea_nombre']) : '--------'; 
            $c++;
            $response[] = $data;
        }

        echo json_encode($response);

    }



    public function registrar_meta()
    {
 
        $data = $this->input->post();
        
        $values = array('sec_ejec' => SEC_EJEC,
                        'anio_eje' => $data['anio'],
                        'sec_func' => $data['codigo'],
                        'nombre' => strtoupper(trim($data['nombre']))
                     );

        if(is_numeric($data['codigo']) === FALSE || strlen($data['codigo']) > 4 )
        {
             $response =  array(
                  'result' =>   '0',
                  'mensaje'  => 'EL codigo especificado es invalido',
                  'data' => array('key' => '' )
             );

             echo json_encode($response);
             die();
        }

        $existe = $this->meta->existe($values);

        if($existe === FALSE)
        {   


             $this->meta->registrar($values);

             $values = array('sec_ejec' => SEC_EJEC,
                             'ano_eje' => $data['anio'],
                             'sec_func' => $data['codigo'],
                             'tarea_nombre' => strtoupper(trim($data['nombre'])),
                             'tarea_nro' => '1'
                          );


             $this->tarea->registrar($values);

             $response =  array(
                 
                  'result' =>   '1',
                  'mensaje'  => 'Meta presupuestal registrada satisfactoriamente',
                  'data' => array('key' => '' )
             );

        }
        else
        {
            $response =  array(
                
                 'result' =>   '0',
                 'mensaje'  => 'El codigo de meta ya esta registrado para ese año',
                 'data' => array('key' => '' )
            );
        }

        echo json_encode($response);
    }



    public function actualizar_meta()
    {
    
        $data = $this->input->post();   

        $tarea_id = $this->tarea->get_id( trim($data['view']));

        $tarea_info = $this->tarea->get_info($tarea_id); 


        $values = array('sec_ejec' => SEC_EJEC,
                        'anio_eje' => $data['anio'],
                        'sec_func' => $data['codigo'],
                        'nombre' => strtoupper(trim($data['nombre']))
                     );

        if(is_numeric($data['codigo']) === FALSE || strlen($data['codigo']) > 4 )
        {
             $response =  array(
                  'result' =>   '0',
                  'mensaje'  => 'EL codigo especificado es invalido',
                  'data' => array('key' => '' )
             );

             echo json_encode($response);
             die();
        }


        if( $data['codigo'] != $tarea_info['sec_func'] )
        {
            
            $existe = $this->meta->existe($values);
        }
        else
        {
            $existe = false;
        }


        if($existe === FALSE)
        {   
             
             
             $values = array('sec_func' => $data['codigo'],
                             'nombre' => strtoupper(trim($data['nombre'])),
                             'sec_func_actual' => $tarea_info['sec_func'],
                             'ano_eje' => $tarea_info['ano_eje'],
                             'tarea_id' => $tarea_info['tarea_id']
                          );


             $this->meta->actualizar($values);

             $response =  array(
                 
                  'result' =>   '1',
                  'mensaje'  => 'Meta presupuestal actualizada correctamente ',
                  'data' => array('key' => '' )
             );

        }
        else
        {
            $response =  array(
                
                 'result' =>   '0',
                 'mensaje'  => 'El codigo de meta ya esta registrado para ese año',
                 'data' => array('key' => '' )
            );
        }

        echo json_encode($response);
    }


    public function eliminar_meta()
    {

         $data = $this->input->post();   
         $tarea_id = $this->tarea->get_id( trim($data['view']));
        

    }

}