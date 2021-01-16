<?php
  
class empleadoafectacion extends Table{
     
    
    protected $_FIELDS=array(   
                                    'id'    => 'empre_id',
                                    'code'  => 'empre_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'empre_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'empleado_presupuestal';
    protected $_PREF_TABLE= 'EMPLEADOPRESU'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
    
    
    public function get($empleado_id , $ano_eje = '')
    {
        $params = array($empleado_id, $ano_eje);
        
        $sql = "SELECT  ep.*, ( tarea.sec_func || '-' || tarea.tarea_nro || ' ' || tarea.tarea_nombre ) as tarea,
                        ( tr.fuente_financ || '-' || tr.tipo_recurso || ' ' || ff.nombre ) as fuente    
                            
                 FROM  planillas.empleado_presupuestal ep 
                 LEFT JOIN  sag.tarea ON ep.tarea_id = tarea.tarea_id
                 LEFT JOIN  pip.fuente_financ ff ON ep.fuente_id = ff.fuente_financ AND ep.ano_eje = ff.ano_eje  
                 LEFT JOIN  pip.tipo_recurso  tr  ON tr.tipo_recurso = ep.tipo_recurso AND tr.fuente_financ = ep.fuente_id  AND ep.ano_eje = tr.ano_eje  
                 WHERE ep.empre_estado = 1 AND ep.indiv_id = ? AND ep.ano_eje = ? 
                 LIMIT 1 ";
        
        $rs =  $this->_CI->db->query($sql, $params)->result_array();
        return $rs[0];
    }
    

    public function registrar($params, $t_r = false ){


          $this->_CI->db->trans_begin();


          $values = array();   


          $indiv_id     = (trim($params['indiv_id']) != '') ?  trim($params['indiv_id']) : '0';
          $tarea_id     = (trim($params['tarea_id']) != '') ?  trim($params['tarea_id']) : '0';
          $fuente_id    =  trim($params['fuente_id']) ;
          $tipo_recurso =  trim($params['tipo_recurso']);

          $ano_eje = trim($params['ano_eje']);

          $sql = "SELECT * FROM planillas.empleado_presupuestal WHERE empre_estado = 1 AND
                                                                       indiv_id = ? AND 
                                                                       tarea_id = ? AND 
                                                                       fuente_id = ? AND 
                                                                       tipo_recurso = ? AND
                                                                       ano_eje = ? ";
          
          $rs = $this->_CI->db->query($sql, array($indiv_id, $tarea_id, $fuente_id,$tipo_recurso, $ano_eje))->result_array();

          if( trim($rs[0]['empre_id'])  == '' )
          {  
               $values = array();
          
               $sql = " UPDATE  planillas.empleado_presupuestal 
                        SET empre_estado = 0 
                        WHERE  indiv_id = ? AND ano_eje = ? 
                     "; 

               $this->_CI->db->query($sql, array( $indiv_id,  $ano_eje ));     

               list($empre_id, $empre_key) = parent::registrar( array('indiv_id' => $indiv_id, 'tarea_id' => $tarea_id, 'fuente_id' => $fuente_id, 'tipo_recurso' => $tipo_recurso, 'ano_eje' => $ano_eje) , true);

               $sql = "   UPDATE planillas.planilla_empleados
                          SET empre_id = ?, tarea_id = ?, fuente_id = ?, tipo_recurso = ?
                          WHERE indiv_id = ? AND 
                                plaemp_id IN ( 

                                      SELECT plaemp.plaemp_id 
                                      FROM planillas.planillas pla 
                                      INNER JOIN planillas.planilla_movimiento mov ON pla.pla_id = mov.pla_id AND plamo_estado = 1 AND plaes_id = ".ESTADOPLANILLA_ELABORADA." 
                                      INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?
                                      WHERE pla.pla_afectacion_presu = ".PLANILLA_AFECTACION_ESPECIFICADA_X_DETALLE." AND pla.ano_eje = ?
                                  ) 
                      ";

               $this->_CI->db->query($sql, array($empre_id, $tarea_id, $fuente_id, $tipo_recurso, $indiv_id, $indiv_id, $ano_eje ));


               $sql = "   UPDATE planillas.planilla_empleado_concepto
                          SET tarea_id = ?, fuente_id = ?, tipo_recurso = ?
                          WHERE plaemp_id IN ( 

                                      SELECT plaemp.plaemp_id 
                                      FROM planillas.planillas pla 
                                      INNER JOIN planillas.planilla_movimiento mov ON pla.pla_id = mov.pla_id AND plamo_estado = 1 AND plaes_id = ".ESTADOPLANILLA_ELABORADA." 
                                      INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?
                                      WHERE pla.pla_afectacion_presu = ".PLANILLA_AFECTACION_ESPECIFICADA_X_DETALLE."  AND pla.ano_eje = ?
                                  ) 
                      ";

               $this->_CI->db->query($sql, array($tarea_id, $fuente_id, $tipo_recurso, $indiv_id, $ano_eje ));

 

               if($this->_CI->db->trans_status() === FALSE) 
               {
                   $this->_CI->db->trans_rollback();
                   return false;
                   
               }else{
                       
                   $this->_CI->db->trans_commit();
                   return true;
               } 

          }
          else{
        
              return true;
          }




    }
    
}