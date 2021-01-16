<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class variables extends CI_Controller {
    
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
        
         $this->load->library(array('App/variable'));
       // ,'Catalogos/provincia','Catalogos/distrito'
         
    }
    
    
    public function registrar()
    {
         
        $this->load->library(array('App/tipoplanilla','App/variabletipoplanilla','App/grupovc'));
        
        $this->db->trans_begin();
        
        $data = $this->input->post();
        
        foreach($data as $k =>  $d) $data[$k] = trim($data[$k]);
        
        if($data['grupo']== '' && $data['grupo_label'] != '')
        {

            $values = array('gvc_nombre' => trim($data['grupo_label'])); 
            list($grupo_id,$grupo_key) =  $this->grupovc->registrar($values,true);

        } 
        else
        {
          
            $grupo_id = $data['grupo'];
        }
      

        $values = array(
            
                  'vari_nombre'         => strtoupper(trim($data['nombre'])),  
                  'vari_tipovalor'      => $data['tipo'],
                  'vari_valordefecto'   => (trim($data['pordefecto']) != '') ? $data['pordefecto'] : '0',  
                  'vari_esxdefecto'     => (trim($data['predeterminado']) != '') ? $data['predeterminado'] : '0', 
                  'vari_displayprint'   => (trim($data['displayprint']) != '') ? $data['displayprint'] : '0',
                  'vari_nombrecorto'    => strtoupper(trim($data['nombrecorto'])),
                  'vari_descripcion'    => strtoupper(trim($data['descripcion'])),
                  'gvc_id'              => $grupo_id,
                  'vari_personalizable' => $data['personalizable'],
                  'vau_id'              => $data['unidad'],
                  'vari_orden'       => (trim($data['orden']) != '' && is_numeric($data['orden'])) ? $data['orden'] : '0',
                  'vari_remuneracion'     => (trim($data['cuentaremuneracion']) != '') ? $data['cuentaremuneracion'] : '0',
                  'vari_conc_afecto_cuarta_quinta' => (trim($data['afecto_a_cuarta_quinta']) != '') ? $data['afecto_a_cuarta_quinta'] : '0'  
              
        );


        $values['vari_planillon'] =  ( trim($data['enplanillon']) == '1' && trim($data['nombreplanillon']) != '' ) ? '1' : '0';

        $values['vari_planillon_nombre'] = ( trim($data['enplanillon']) == '1' && trim($data['nombreplanillon']) != '' ) ? trim($data['nombreplanillon']) : '';

        
        $for_pla = explode(',', $data['aplicable']);
        array_shift($for_pla);
         
        foreach( $for_pla as $pla)
        {
                
           $pt = $this->tipoplanilla->get_id($pla);
           $values['plati_id'] =  $pt; 
           list($id,$key) = $this->variable->registrar($values,true); 
        }
        
        
        $ok =false;
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
       
        }
        else
        {
            $this->db->trans_commit();
            $ok = true;
        }    
           
        
        $response =  array(
            
             'result' =>  ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Variable registrada correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $key, 'id' => $id )
        ); 
        
        echo json_encode($response);    
         
    }
    
    
    
    public function view(){
        
       $vari_key = trim($this->input->post('vari')); 
       $vari_id = $this->variable->get_id($vari_key);
       $vari_info = $this->variable->get( $vari_id );
      
       $operando_conceptos = $this->variable->get_operando_conceptos($vari_id);
      

       $vari_info['predeterminado'] =  ($vari_info['vari_esxdefecto'] == '1') ? 'SI' : 'NO';
        
       $vari_info['in_conceptos'] = $operando_conceptos;

       $this->load->view('planillas/v_variable_info', array('vari_info' => $vari_info));
       
    }
     
     public function nueva_variable(){
         
        $this->load->library(array('App/tipoplanilla','App/grupovc'));
          
        $tipos = $this->tipoplanilla->get_all(); 
        $grupos = $this->grupovc->get_list();
        
        $unidades = $this->variable->get_list_unidades();
        $personalizable_ops = $this->variable->get_list_personalizable(); 


        $this->load->view('planillas/v_variable_nueva', array(
                                                              'tipos_planilla'  => $tipos, 
                                                              'grupos'          => $grupos,
                                                              'unidades'        => $unidades,
                                                              'personalizables' => $personalizable_ops
                                                              ));
         
     }
     
     
     public function modificar_variable(){
         
        $this->load->library(array('App/tipoplanilla','App/grupovc'));
          
          $tipos              = $this->tipoplanilla->get_all(); 
          $grupos             = $this->grupovc->get_list(); 
          
          $vari_k             = $this->input->post('view');
          $vari_id            = $this->variable->get_id($vari_k);
          $variable_info      = $this->variable->get($vari_id);
          $operando_conceptos = $this->variable->get_operando_conceptos($vari_id);
          
          $unidades           = $this->variable->get_list_unidades();
          $personalizable_ops = $this->variable->get_list_personalizable(); 
  
          $tabla_datos = $this->variable->get_tabla_datos(array('plati_id' => $variable_info['plati_id']));


          $this->load->view('planillas/v_variable_modificar',array(
                                                                    'tipos_planilla'     => $tipos,
                                                                    'variable_info'      => $variable_info,
                                                                    'operando_conceptos' => $operando_conceptos,
                                                                    'grupos'             => $grupos,
                                                                    'unidades'           => $unidades,
                                                                    'personalizables'    => $personalizable_ops,
                                                                    'tabla_datos'        => $tabla_datos
                                                                   ) );
         
     }
      
     
     public function actualizar()
     {
           
        $this->load->library(array('App/tipoplanilla','App/variabletipoplanilla','App/grupovc'));
        
        $this->db->trans_begin();
        
        $data = $this->input->post();
        
        foreach($data as $k =>  $d) $data[$k] = trim($data[$k]);
        
        
        $vari_id = $data['view']; // vari_key
      
        if($data['grupo']== '' && $data['grupo_label'] != '')
        {

            $values = array('gvc_nombre' => trim($data['grupo_label'])); 

            list($grupo_id,$grupo_key) =  $this->grupovc->registrar($values,true);

        } 
        else
        {
            $grupo_id = $data['grupo'];
        }
  

        $values = array(
            
                  'vari_nombre'         => strtoupper(trim($data['nombre'])),  
                  'vari_tipovalor'      => $data['tipo'],
                  'vari_valordefecto'   => (trim($data['pordefecto']) != '') ? $data['pordefecto'] : '0',  
                  'vari_esxdefecto'     => (trim($data['predeterminado']) != '') ? $data['predeterminado'] : '0', 
                  'vari_displayprint'   => (trim($data['displayprint']) != '') ? $data['displayprint'] : '0',
                  'vari_nombrecorto'    => strtoupper(trim($data['nombrecorto'])),
                  'vari_descripcion'    => strtoupper(trim($data['descripcion'])),
                  'gvc_id'              => $grupo_id,
                  'vari_personalizable' => $data['personalizable'],
                  'vau_id'              => $data['unidad'],
                  'vari_orden'          => (trim($data['orden']) != '' && is_numeric($data['orden'])) ? $data['orden'] : '0',
                  'vari_remuneracion'     => (trim($data['cuentaremuneracion']) != '') ? $data['cuentaremuneracion'] : '0',
                  'vtd_id'               => (trim($data['tabladatos']) != '' && is_numeric($data['tabladatos'])) ? $data['tabladatos'] : '0',
                  'vari_conc_afecto_cuarta_quinta' => (trim($data['afecto_a_cuarta_quinta']) != '') ? $data['afecto_a_cuarta_quinta'] : '0'  
                
        );



        $values['vari_planillon'] =  ( trim($data['enplanillon']) == '1' && trim($data['nombreplanillon']) != '' ) ? '1' : '0';

        $values['vari_planillon_nombre'] = ( trim($data['enplanillon']) == '1' && trim($data['nombreplanillon']) != '' ) ? trim($data['nombreplanillon']) : '';
        
        
        $this->variable->actualizar($vari_id, $values, true);
   
        $ok =false;
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
       
        }
        else
        {
            $this->db->trans_commit();
            $ok = true;
        }    
           
        
        $response =  array(
            
             'result'  =>  ($ok)? '1' : '0',
             'mensaje' => ($ok)? ' Variable actualizada correctamente' : 'Ocurrio un error durante la operacion',
             'data'    => array('about' => $vari_id )
        ); 
        
        echo json_encode($response);    
         
         
     }
     
     
     public function provide($tipo)
     {
          
         $this->load->library(array('App/tipoplanilla'));
         
         header("Content-Type: application/json");
         
         if($tipo == 'main')
         {
          
            $start = 1;
            $end = 10;
            
            $params = array();
            
            $params['nombre'] = strtoupper(trim($this->input->get('nombre')));

            if(trim($this->input->get('tipoplanilla')) != '0' )   $params['tipoplanilla'] = $this->tipoplanilla->get_id(trim($this->input->get('tipoplanilla')));
            
            if(trim($this->input->get('grupo')) != '' && trim($this->input->get('grupo')) != '0' ) $params['grupo'] = trim($this->input->get('grupo'));

            if($this->input->get('personalizable')!='0')
            {
               if($this->input->get('personalizable')=='1')
               {
                 $params['personalizable'] = false;
               } 
               else
               {
                 $params['personalizable'] = true;
               }
            } 


            if($this->input->get('afecto_cuarta_quinta')!='2')
            {
               if($this->input->get('afecto_cuarta_quinta')=='1')
               {
                  $params['afecto_cuarta_quinta'] = '1';
               } 
               else
               {
                  $params['afecto_cuarta_quinta'] = '0';
               }
            } 

            $variables = $this->variable->get_list($params);

            $c = 1;

            $total = sizeof($variables);

            header("Content-Range: " . "items ".$start."-".$total."/".$total);     
            $data = array();
            $response = array();
            foreach($variables as $variable){
                  
                
                $data['id'] =   trim($variable['key']);
                $data['col1'] = $c;
                $data['col2'] = trim($variable['vari_nombre']); //trim($variable['vari_id']).' : '.
                $data['col3'] = trim($variable['tipo_planilla']);
                $data['col4'] = trim($variable['grupo_nombre']); // Tipo Numerico / Valores especificos
                $data['col5'] = (trim($variable['vari_personalizable'])!='0') ? 'Si' : 'No';  // Obligatorio
                $data['col6'] = trim($variable['vari_valordefecto']);  // Obligatorio
                 
                $response[] = $data;
                $c++;
            }

            echo json_encode($response) ;
         }
         
     }
     
     
      public function guardar_detalleplanilla()
      {
        
         $var = $this->input->post('var');
         $v = $this->input->post('v');
         
         $this->load->library(array('App/planillaempleadovariable'));
      
         $rs = $this->planillaempleadovariable->actualizar_valor($var,$v,true);
         
          $response =  array(

                 'result' =>  ($rs)? '1' : '0',
                 'mensaje'  => ($rs)? 'Valor actualizado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $p_k )
          );
        
          echo json_encode($response);
        
    }


     public function actualizar_detalle_planilla()
     {

        $this->load->library(array('App/planilla'));

        $planilla_id = $this->planilla->get_id(trim($this->input->post('planilla')));
        $vari_id = $this->variable->get_id(trim($this->input->post('variable')));   
        $valor = $this->input->post('valor');

        $ok = $this->variable->actualizar_valor_planilla($planilla_id, $vari_id, $valor);

        $response =  array(

                 'result'  =>  ($ok)? '1' : '0',
                 'mensaje' => ($ok)? 'valor de la Variable actualizado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array()
        );
       
          echo json_encode($response);

  
     }

     public function acceso_directo(){

      
     }


     public function eliminar()
     {

        $data = $this->input->post();
        $vari_id = $this->variable->get_id($data['view']);
 
        $ok = $this->variable->delete($vari_id);

         $response =  array(

               'result'  =>  ($ok)  ? '1' : '0',
               'mensaje' =>  ($ok)  ? 'Variable eliminada correctamente' : 'Ocurrio un error durante la operacion',
               'data'    => array()
        );
     
        echo json_encode($response);

     }




     public function tabla_gestionar_montos()
     { 
        
          $this->load->library(array('App/planillatipocategoria'));
          $tables  = $this->planillatipocategoria->get_tables();
          


          $this->load->view('planillas/v_tabla_montos', array('tables' => $tables));
     }


     public function ver_tabla_montos()
     { 
      
          $this->load->library(array('App/planillatipocategoria'));

          $vtd_id = $this->input->post('view');

          $table_montos = $this->planillatipocategoria->get_table_montos($vtd_id);
  
          $this->load->view('planillas/v_tabla_variables_gestionarmontos', array('table' => $table_montos));
     }


     public function tabla_actualizar_montos()
     {
         
         $this->load->library(array('App/planillatipocategoria'));

         $datos = $this->input->post('data');  
/*         $datos = explode('_',$datos);

         $vari_id = explode('-', $datos[0]);
         $vari_id = $vari_id[1]; 
         

         $data = array(

                     '1' => $datos[1], // Operario
                     '2' => $datos[2], // Oficial
                     '3' => $datos[3],  // Peon
          '8' => $datos[4],  // maestro
                     '9' => $datos[5],  // guardian
                     '10' => $datos[6]  // guardian

                 );
  */

          $datos_a = explode('|', $datos);

          array_shift($datos_a);

          $vtd_id = $this->input->post('view');

          foreach($datos_a as $v)
          {
              $params = array();


              list($params['vari_id'], $params['yvalue'], $params['value'] ) = explode('_', $v );
              
              if($params['value']=='') $params['value'] = 0;

              $ok = $this->planillatipocategoria->actualizar_montos($params, $vtd_id );
          }

    


           $response =  array(

                  'result'  =>  ($ok)? '1' : '0',
                  'mensaje' => ($ok)? ' Datos actualizados correctamente' : 'Ocurrio un error durante la operacion',
                  'data'    => array('key' => '' )
           );
        
           echo json_encode($response);
      

     }

     
    
}