<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class importacionxls extends CI_Controller {
    
    public $usuario;
    
    public function __construct()
    {
    
        parent::__construct();
        
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
        
        $this->load->library(array('App/xlsimportacion'));  
    
    }

    public function configuracion()
    {
    
        $this->load->library(array('App/tipoplanilla'));

        $modos = $this->xlsimportacion->get_modos_importacion();

        $platis = $this->tipoplanilla->get_all();

        $this->load->view('planillas/v_importacionxls_configuracion', array('modos' => $modos, 'platis' => $platis));

    }

    public function panel()
    {   

        $this->load->library('App/tipoplanilla');

        
        $datos     = $this->input->post();
         
        $modo_info = $this->xlsimportacion->get_modo_importacion($datos['modo_importacion']);
  
 
        if( $datos['modo_importacion'] == XLS_IMPORTACION_PERSONALZIADA )
        {     
        
           if(sizeof($datos['tiposplanilla']) > 0 )
           {

               $restringir = implode(",", $datos['tiposplanilla']);

               $rs = $this->tipoplanilla->get_by_ids($datos['tiposplanilla']);
                
               $tipos_planilla = array();

               foreach ($rs as $t) $tipos_planilla[] = trim($t['plati_nombre']);

               $tipos_permitidos = implode(',',$tipos_planilla);
 
           }
           else
           {
                
               $restringir = array();
               $tipos_permitidos = '';

           }


        }

        
        $config = array(   
                            'modo'             => $datos['modo_importacion'],
                            'by'               => $datos['by'],
                            'restringir'       => $restringir,
                            'vincular'         => $datos['vincular'],
                            'tipos_permitidos' => $tipos_permitidos
                       );
 

        $params = array(    
                           'modo_importacion'   => $modo_info['xim_nombre'], 
                           'config_importacion' => $config
                       );


        $this->load->view('planillas/p_panel_importacionxls', $params); 

    }

    public function explorar()
    { 

        $this->load->library(array('App/variable'));

        $key = $this->input->post('view');
        $file_id  = $this->xlsimportacion ->get_id($key);
        
        $modo = $this->input->post('modo');
        $modo_config = $this->xlsimportacion->get_modo_importacion($modo);

       
        $function_set_params = trim($modo_config['xim_function_set_parametros']);
            

        $datos = $this->input->post();


        if($datos['vincular'] == 'vincular') 
        {
            $datos['vincular'] = true;
        }
        else
        {
            $datos['vincular'] = false;
        }


        $tiposplanilla = explode(',' , $datos['restringir']);

        foreach($tiposplanilla as $k => $v)
        {
            $tiposplanilla[$k] = trim($v);
        }

        if(sizeof($tiposplanilla) == 0) $tiposplanilla = array(0);

            $config_personalizado = array(
                                            array(

                                                'by'            => $datos['by'],
                                                'modo'          => $datos['modo'],
                                                'tiposplanilla' => $tiposplanilla,
                                                'vincular'      => $datos['vincular']
             
                                            )
                                    );


            call_user_func_array( array($this->xlsimportacion, $function_set_params ), 
                                                               $config_personalizado );
 


        list($ok, $html_table, $log, $data) = $this->xlsimportacion->explorar($file_id);


        if($ok)
        {
                 
            if(sizeof($this->xlsimportacion->_plati_id) == 1)
            {
                    
                $variables_destino = $this->variable->get_list(array(

                                                        'tipoplanilla' => ($this->xlsimportacion->_plati_id[0])

                                              ));

            }
            else
            {

                $variables_destino = array();
    
            }
            

            $params = array(
                            
                            'columnas_variables' => $data['columnas_variables'],
                            'planillas'          => $data['planillas'],
                            'trabajadores'       => $data['trabajadores'],
                            'variables_destino'  => $variables_destino,
                            'numeregistros'      => $data['registros'],
                            'view'               => $key

                     );
 

            $html_result = $this->load->view('planillas/v_xls_import', $params, true);     
        }
        else
        {

            $params = array('log' => $log);

            $html_result = $this->load->view('planillas/v_xls_detalle_observaciones', $params, true);     


        }
 

        echo json_encode( array(
                                'html_table'        => $html_table, 
                                'html_result'       => $html_result,
                                'numeregistros'     => ( ($data['registros'] * 1) - 1) 
                                ));
 
    }


    public function importar()
    {


        $key                 = $this->input->post('view');
        $file_id             = $this->xlsimportacion ->get_id($key);
        
        $modo                = $this->input->post('modo');
        $modo_config         = $this->xlsimportacion->get_modo_importacion($modo);
        
        $function_set_params = trim($modo_config['xim_function_set_parametros']);
        
        $datos               = $this->input->post();

        $punto_inicio        = $datos['pi'];
 

        if($datos['vincular'] == 'vincular') 
        {
            $datos['vincular'] = true;
        }
        else
        {
            $datos['vincular'] = false;
        }


        $tiposplanilla = explode(',' , $datos['restringir']);

        foreach($tiposplanilla as $k => $v)
        {
            $tiposplanilla[$k] = trim($v);
        }

        if(sizeof($tiposplanilla) == 0) $tiposplanilla = array(0);

            $config_personalizado = array(
                                            array(

                                                'by'            => $datos['by'],
                                                'modo'          => $datos['modo'],
                                                'tiposplanilla' => $tiposplanilla,
                                                'vincular'      => $datos['vincular']
             
                                            )
                                    );

       call_user_func_array( array($this->xlsimportacion, $function_set_params ), 
                                                          $config_personalizado );
 

       if( ( $punto_inicio * 1) == 1)
       {
           list($ok, $html_table, $log, $data) = $this->xlsimportacion->explorar($file_id);
           $numero_registros = 1;
       }
       else
       {
           $ok =true;
           $numero_registros = NUMERO_REGISTROS_PARTE_IMPORTACION;
       }

 
       if($ok)
       {
           
            $params = array(
                            
                            'columnas'  => $datos['columna'],
                            'variables' => $datos['variable'],
                            'obervacion' => $datos['obervacion'],
                            'punto_inicio' => $punto_inicio,
                            'numero_registros' => $numero_registros
                      );

       

            list($ok, $resumen) = $this->xlsimportacion->importar($file_id, $params);
             
            $html_result = $this->load->view('planillas/v_xls_import_result', $resumen, true );
 
            echo json_encode( 
                                array(
                                        'result'        => '1',
                                        'html_result'   => $html_result,
                                        'punto_limite'  => $resumen['punto_limite'],
                                        'resumen'       => $resumen['totales_columnas'],
                                        'total_rows'    => $resumen['total_rows']
                                     )
                            );


       }
       else
       {
            
           echo json_encode( 
                               array(
                                       'result' => '0',
                                       'html_result'   => 'Algo no salio bien durante la importación'
                                    )
                           ); 
       }


        

    }


    public function importar_trabajadores()
    {


        $key                 = $this->input->post('view');
        $file_id             = $this->xlsimportacion ->get_id($key);
        
        $modo                = $this->input->post('modo');
        $modo_config         = $this->xlsimportacion->get_modo_importacion($modo);
        
        $function_set_params = trim($modo_config['xim_function_set_parametros']);
        
        $datos               = $this->input->post();
  
       list($ok, $html_table, $log, $data) = $this->xlsimportacion->explorar_trabajadores($file_id);

    
       if($ok)
       {
           
           
            list($ok, $resumen) = $this->xlsimportacion->importar_trabajadores($file_id, $params);
                
    
            $html_result = $this->load->view('planillas/v_xls_import_result', $resumen, true );

    
            echo json_encode( 
                                array(
                                        'result' => '1',
                                        'html_result'   => $html_result
                                     )
                            );


       }
       else
       {
            
           echo json_encode( 
                               array(
                                       'result' => '0',
                                       'html_result'   => 'Algo no salio bien durante la importación'
                                    )
                           ); 
       }


        

    }



    public function panel_importar_trabajadores()
    {

        $params = array();

        $this->load->view('planillas/p_panel_importacionxls_trabajadores', $params); 
    }


    public function explorar_importacion_trabajadores()
    {

        $this->load->library(array('App/variable'));

        $key = $this->input->post('view');
        $file_id  = $this->xlsimportacion ->get_id($key);
  
        list($ok, $html_table, $log, $data) = $this->xlsimportacion->explorar_trabajadores($file_id);


        if($ok)
        {
                 
            if(sizeof($this->xlsimportacion->_plati_id) == 1)
            {
                    
                $variables_destino = $this->variable->get_list(array(

                                                        'tipoplanilla' => ($this->xlsimportacion->_plati_id[0])

                                              ));

            }
            else
            {

                $variables_destino = array();
        
            }
            

            $params = array(
                            
                            'columnas_variables' => $data['columnas_variables'],
                            'planillas'          => $data['planillas'],
                            'trabajadores'       => $data['trabajadores'],
                            'variables_destino'  => $variables_destino,
                            'numeregistros'      => $data['registros'],
                            'view'               => $key

                     );
        

            $html_result = $this->load->view('planillas/v_xls_import_trabajadores', $params, true);     
        }
        else
        {

            $params = array('log' => $log);

            $html_result = $this->load->view('planillas/v_xls_detalle_observaciones', $params, true);     


        }
        

        echo json_encode( array(
                                'html_table'        => $html_table, 
                                'html_result'       => $html_result  
                                ));

    }
 

}