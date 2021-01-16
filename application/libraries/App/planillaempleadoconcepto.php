<?php
  

class planillaempleadoconcepto extends Table{
     
    
    protected $_FIELDS=array(   
                                    'id'    => 'plaec_id',
                                    'code'  => 'plaec_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'plaec_estado'
                            );
    
    protected $_SCHEMA     = 'planillas';
    protected $_TABLE      = 'planilla_empleado_concepto';
    protected $_PREF_TABLE = 'PLANILLAECC'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }

    
    public function registrar($values, $indiv_id = 0, $procedencia = 0, $static_data = array() )
    {
 

          $this->_CI->load->library('App/planillaempleadovariable');

          $sql = " SELECT plaec_id FROM planillas.planilla_empleado_concepto WHERE plaemp_id = ? AND conc_id = ? ";
          $rs  = $this->_CI->db->query($sql, array( $values['plaemp_id'], $values['conc_id'] ))->result_array();
          
          if($rs[0]['plaec_id'] != '')
          { 
              if($values['plaec_value'] != '' || $values['plaec_value_pre'] != '' ){

                  $t_values = array('plaec_value' => ($values['plaec_value'] == '' ? '0' : $values['plaec_value']),
                                    'plaec_value_pre' => ($values['plaec_value_pre'] == '' ? '0' : $values['plaec_value_pre'] ),
                                    'plaec_calculado' => ($values['plaec_calculado'] == '' ? '1' : $values['plaec_calculado'] ) 
                                   );

                  parent::actualizar($rs[0]['plaec_id'], $t_values , false );  
              }
              return true;
          }             

          $conc_id   = $values['conc_id'];
          $plaemp_id = $values['plaemp_id'];

           
          $sql =" SELECT * FROM planillas.conceptos WHERE conc_id = ? LIMIT 1";
          $t = $this->_CI->db->query($sql, array($conc_id))->result_array();
          $info_concepto = $t[0];

          
          $values['conc_afecto']      = $info_concepto['conc_afecto'];
          $values['conc_ecuacion_id'] = $info_concepto['conc_ecuacion_id'];
          $values['conc_tipo']        = $info_concepto['conc_tipo'];
          $values['gvc_id']           = $info_concepto['gvc_id'];


          $max = $info_concepto['conc_max_x_mes'] * 1;
          
          unset($values['conc_max_x_mes']);
          
           if( $max > 0)
           { 
               
               $no = $this->restric_validar_numero_permitido($max, $plaemp_id, $conc_id);

               if( $no == FALSE) return false;
           }    
              
          
          
          // Recuperando las variables del concepto
          $sql = "SELECT  *,
                          vars.vari_id as variable_id 
                          
                          FROM planillas.variables vars 
                          LEFT JOIN planillas.empleado_variable pe  ON pe.empvar_estado = 1 AND pe.vari_id = vars.vari_id AND pe.indiv_id = ?
                          LEFT JOIN planillas.planilla_empleado_variable plaev  ON plaev.plaev_estado = 1  AND plaev.vari_id  = vars.vari_id  AND plaev.plaemp_id =  ?
                          LEFT JOIN planillas.variables_tabla_datos vtd ON vars.vtd_id = vtd.vtd_id
                  WHERE  

                      vars.vari_id in ( SELECT (var_concepto.var_id) 
                                              FROM ( 
                                              (
                                                  SELECT   (coops_operando1) as var_id
                                                  FROM planillas.conceptos_ops ops 
                                                  WHERE ops.coops_estado = 1 AND (ops.coops_operando1_t = ".TIPOOPERANDO_VARIABLE.")  AND ops.conc_id = ? AND coops_ecuacion_id = ?
                                                  ORDER BY var_id
                                              )
                                              UNION (

                                                  SELECT   (coops_operando2) as var_id
                                                  FROM planillas.conceptos_ops ops 
                                                  WHERE ops.coops_estado = 1 AND (ops.coops_operando2_t = ".TIPOOPERANDO_VARIABLE.")  AND ops.conc_id = ? AND coops_ecuacion_id = ?  ) 
                                                  ORDER BY var_id  
                                              ) as var_concepto ) 

                      ORDER BY vars.vari_id 
                ";

          
              $this->_CI->db->trans_begin();
              
          
              $variables_del_concepto = $this->_CI->db->query($sql, array($indiv_id, $plaemp_id,$conc_id,$values['conc_ecuacion_id'], $conc_id, $values['conc_ecuacion_id'] ))->result_array();
          
              foreach($variables_del_concepto as $vari)
              {



                  if(trim($vari['plaev_id']) == '') // si no existe en la relacion planilla_empleado_variable
                  {

                      // SI existe comprobamos si existe en la relacion empleado variable, de ser asi se obtienen los datos por defecto

                      if(trim($vari['empvar_id']) != '')  // SI existe la relacion empleado_variable
                      {
                            $data = array( 'plaemp_id'          =>  $plaemp_id, 
                                           'vari_id'            =>  $vari['variable_id'],
                                           'plaev_valor'        =>  $vari['empvar_value'],
                                           'plaev_procedencia'  =>  PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO,
                                           'plaev_displayprint' =>  $vari['empvar_displayprint']); 

                           
                      }
                      else
                      {

                            $data = array( 'plaemp_id'          =>  $plaemp_id, 
                                           'vari_id'            =>  $vari['variable_id'],
                                           'plaev_valor'        =>  $vari['vari_valordefecto'],
                                           'plaev_procedencia'  =>  PROCENDENCIA_VARIABLE_VALOR_XDEFECTO,
                                           'plaev_displayprint' =>  $vari['vari_displayprint']);

                            
                      } 
 

                      $static_data['vtd_id']      = $vari['vtd_id'];
                      $static_data['y_value_key'] = $vari['y_value_key'];
  
  
                      $this->_CI->planillaempleadovariable->registrar($data, false, $static_data);

                  } 
          
              }


              // OBTENER LOS CONCEPTOS NO AFECTOS AL TRABAJADOR
          

               $sql = "SELECT concs.conc_id  FROM (

                         SELECT (conc_na.conc_id) as conc_id 
                                              FROM ( 
                                              (
                                                  SELECT   (coops_operando1) as conc_id
                                                  FROM planillas.conceptos_ops ops     
                                                  WHERE ops.coops_estado = 1 AND (ops.coops_operando1_t = ".TIPOOPERANDO_CONCEPTO.")  AND ops.conc_id = ? AND coops_ecuacion_id = ?
                                                  ORDER BY conc_id
                                              )
                                              UNION (

                                                  SELECT   (coops_operando2) as conc_id
                                                  FROM planillas.conceptos_ops ops 
                                                  WHERE ops.coops_estado = 1 AND (ops.coops_operando2_t = ".TIPOOPERANDO_CONCEPTO.")  AND ops.conc_id = ? AND coops_ecuacion_id = ?  ) 
                                                  ORDER BY conc_id           
                                              ) as conc_na

                          ORDER BY conc_id

                       ) as rs_no_afectos 
                       LEFT JOIN planillas.conceptos concs ON concs.conc_id  = rs_no_afectos.conc_id   
                       WHERE concs.conc_estado = 1 AND concs.conc_afecto = 0
                       ORDER BY conc_id
                      ";

              $conceptos_no_afectos = $this->_CI->db->query($sql, array($conc_id,$values['conc_ecuacion_id'], $conc_id, $values['conc_ecuacion_id']))->result_array();

              $concepto_parent = $values['conc_id'];

              foreach($conceptos_no_afectos as $conc){
                    

                    $values['conc_id'] = $conc['conc_id'];    
                    $this->registrar($values, $indiv_id, PROCENDENCIA_CONCEPTO_POR_ESTAR_RELACIONADO, $static_data ); 
              }
            
              $values['conc_id'] = $concepto_parent;


          
          if ($this->_CI->db->trans_status() === FALSE)
          {
              $this->_CI->db->trans_rollback();
              return false;
          }
          else
          {
              $this->_CI->db->trans_commit();
             return  parent::registrar($values);
             
          }    
      
      

    } 
    

    /*   
        @name: concepto::registro_directos
        @descripcion : COMPRUEBA SI EL TRABAJADOR TIENE O NO EL CONCEPTO, PERO NO REGISTRA LAS VARIABLES NI CONCEPTOS NO AFECTOS 
        RELACIONADOS QUE UTILIZA EL CONCEPTO
    */

    public function registro_directo( $values, $indiv_id = 0, $procedencia = 0, $static_data = array() )
    {

          $this->_CI->load->library('App/planillaempleadovariable');

          $sql = " SELECT plaec_id FROM planillas.planilla_empleado_concepto WHERE plaemp_id = ? AND conc_id = ? ";
          $rs  = $this->_CI->db->query($sql, array( $values['plaemp_id'], $values['conc_id'] ))->result_array();
          
          if($rs[0]['plaec_id'] != '') return true;            

          $conc_id   = $values['conc_id'];
          $plaemp_id = $values['plaemp_id'];
 

          $max = $values['conc_max_x_mes'] * 1;

          unset($values['conc_max_x_mes']);


          if( $max > 0)
          { 
              $no = $this->restric_validar_numero_permitido($max, $plaemp_id, $conc_id);
              if( $no == FALSE) return false;
          }    
              
         
/*          if ($this->_CI->db->trans_status() === FALSE)
          {
              $this->_CI->db->trans_rollback();
              return false;
          }
          else
          {
              $this->_CI->db->trans_commit();
             return  parent::registrar($values);
             
          }   */

          $rs = parent::registrar($values);
        
          return ($rs) ? true : false;
    }



    public function restric_validar_numero_permitido($max, $plaemp_id, $conc_id)
    { 
 
        $sql = " SELECT pla.*, plaemp.indiv_id  
                 FROM planillas.planilla_empleados plaemp
                 INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id
                 WHERE plaemp.plaemp_id = ? 
                 LIMIT 1 ";

        $rs = $this->_CI->db->query($sql, array($plaemp_id))->result_array();         
        

        $sql = "SELECT count(plaec_id) as cant
                FROM planillas.planilla_empleado_concepto pec
                INNER JOIN planillas.planilla_empleados pem ON pec.plaemp_id = pem.plaemp_id AND pem.plaemp_estado = 1
                INNER JOIN planillas.planillas pla ON pem.pla_id = pla.pla_id AND pla.pla_estado = 1 
                WHERE pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.plaec_value > 0 AND pla.pla_anio = ? 
                      AND pla.pla_mes = ? AND pem.indiv_id = ? AND pec.conc_id = ?  ";

        $rs = $this->_CI->db->query($sql, array($rs[0]['pla_anio'], $rs[0]['pla_mes'], $rs[0]['indiv_id'], $conc_id ))->result_array();

        $cant = $rs[0]['cant'] * 1;
 

        if( $cant < $max  )
        {
            return true;
        }
        else
        {
            return false;
        }

    }


    public function has_concepto($plaemp_id, $conc_id){


         $sql = " SELECT plaec_id FROM  planillas.planilla_empleado_concepto WHERE plaec_estado = 1  AND plaemp_id = ?  AND conc_id = ?  LIMIT 1";     
         $rs=  $this->_CI->db->query($sql, array($plaemp_id, $conc_id))->result_array;

        return (sizeof($rs) == 1) ? true : false;
    }
 

    public function quitar($conceptos, $plaemp_id)
    {


        $this->_CI->db->trans_begin();

        if(is_array($conceptos)){

             $p_c = '';    
             foreach($conceptos as $k => $c){

                 if($k>0) $p_c.=',';

                 $p_c.=$c;
             }

             $sql = " UPDATE  planillas.planilla_empleado_concepto
                      SET plaec_estado = 0 WHERE conc_id in (".$p_c.") AND plaemp_id = ? ";         

             $this->_CI->db->query($sql, array($plaemp_id)); 
        }
        else{

              $sql = " UPDATE  planillas.planilla_empleado_concepto
                      SET plaec_estado = 0 WHERE conc_id = ? AND plaemp_id = ? ";   
             $this->_CI->db->query($sql, array($conceptos, $plaemp_id)); 

        }


        if ($this->_CI->db->trans_status() === FALSE)
        {
            $this->_CI->db->trans_rollback();
            return false;
        }
        else
        {
            $this->_CI->db->trans_commit();
           return  true;
           
        }    

    }
    

    public function desvincular($conceptos, $plaemp_id)
    {

        $this->_CI->db->trans_begin();

        if(is_array($conceptos))
        {

             $p_c = '';    
             foreach($conceptos as $k => $c){

                 if($k>0) $p_c.=',';

                 $p_c.=$c;
             }

             $sql = " DELETE FROM planillas.planilla_empleado_concepto
                      WHERE conc_id in (".$p_c.") AND plaemp_id = ? ";         

             $this->_CI->db->query($sql, array($plaemp_id)); 
        }
        else
        {

              $sql = " DELETE FROM planillas.planilla_empleado_concepto
                       WHERE conc_id = ? AND plaemp_id = ? ";   
                       
             $this->_CI->db->query($sql, array($conceptos, $plaemp_id)); 

        }


        if ($this->_CI->db->trans_status() === FALSE)
        {
            $this->_CI->db->trans_rollback();
            return false;
        }
        else
        {
            $this->_CI->db->trans_commit();
           return  true;
           
        }    
    }

    
    public function activar(){

    }


    public function get_conc_id($plaec_id){

        $sql =" SELECT conc_id FROM planillas.planilla_empleado_concepto WHERE plaec_id = ? AND plaec_estado = 1 ";

        $rs = $this->_CI->db->query($sql, array($plaec_id))->result_array();
        return $rs[0]['conc_id'];
    }

    public function get_full_info($plaec_id){

        $sql = "SELECT  
                         
                        plaec.*, 
                        plaemp.indiv_id,
                        ( indiv.indiv_nombres || ' ' || indiv.indiv_appaterno || ' ' || indiv.indiv_nombres )  as trabajador,
                        concs.conc_nombre as concepto,
                        concs.conc_id,
                        concs.conc_max_x_mes,
                        planillas.pla_anio,
                        planillas.pla_id,
                        planillas.pla_mes,
                        plaec.plaec_value_pre as total,
                        plaec.plaec_value as total_modificado

                FROM planillas.planilla_empleado_concepto plaec 
                         INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id
                         INNER JOIN planillas.planillas ON plaemp.pla_id = planillas.pla_id
                         INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id
                         INNER JOIN public.individuo indiv ON plaemp.indiv_id = indiv.indiv_id

                WHERE 

                      plaec.plaec_id = ? 

                ";


        $rs = $this->_CI->db->query($sql, array($plaec_id))->result_array();   
        return $rs[0];         
    }



    public function actualizar_marcado($id,$data = array(), $is_code=true){



          if($data['plaec_marcado'] == '1')
          { 

              $plaec_id = ($is_code) ? $this->get_id($id) : $id;

              $info_concepto = $this->get_full_info($plaec_id);
              $max = $info_concepto['conc_max_x_mes'] * 1;
              
              if( $max > 0)
              { 

                  $plaemp_id = $info_concepto['plaemp_id'];
                  $conc_id = $info_concepto['conc_id'];
                 // var_dump($plaemp_id, $conc_id);

                  $sql = " SELECT pla.*, plaemp.indiv_id  
                           FROM planillas.planilla_empleados plaemp
                           INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id
                           WHERE plaemp.plaemp_id = ? 
                           LIMIT 1 ";

                  $rs = $this->_CI->db->query($sql, array($plaemp_id))->result_array();         
           

                  $sql = "SELECT count(plaec_id) as cant
                          FROM planillas.planilla_empleado_concepto pec
                          INNER JOIN planillas.planilla_empleados pem ON pec.plaemp_id = pem.plaemp_id AND pem.plaemp_estado = 1
                          INNER JOIN planillas.planillas pla ON pem.pla_id = pla.pla_id AND pla.pla_estado = 1 
                          WHERE pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pla.pla_mes = ? AND pem.indiv_id = ? AND pec.conc_id = ?  ";

                  $rs = $this->_CI->db->query($sql, array($rs[0]['pla_mes'], $rs[0]['indiv_id'], $conc_id ))->result_array();

                  $cant = $rs[0]['cant'] * 1;

                  if( $cant >= $max  ){

                      return false;
                  }

              }  

          }  


          return parent::actualizar($id,$data,$is_code);

    }



    public function get_movimientos($plaec_id){


       $sql = " SELECT * FROM planillas.planilla_empleado_concepto_mov mov 
                LEFT JOIN planillas.conceptos_min_max mm ON mov.cmm_id = mm.cmm_id 

                WHERE plaecm_estado = 1 AND plaec_id = ? ORDER BY plaecm_id
              ";

       $rs = $this->_CI->db->query($sql, array($plaec_id))->result_array();
       return $rs;
    }

    public function calculo_por_mes()
    {

        $sql = "  SELECT plaemp.indiv_id, plaec.conc_id, concs.conc_nombre, SUM(plaec_value) as monto  
                       FROM planillas.planilla_empleados plaemp
                       INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = ? AND pla.pla_mes = ? 
                       INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ?
                       INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec_calculado = 1 AND plaec_value > 0 AND plaec.conc_id IN (?)
                       INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id 
                       WHERE plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?

                       GROUP BY plaemp.indiv_id, plaec.conc_id, concs.conc_nombre
                     ";

        $rs = $this->_CI->db->query($sql, array($params['anio'], $params['mes'], ESTADO_PLANILLA_CERRADA, $in_conceptos, $params['indiv_id'] ))->result_array();
         
        return $rs;    

    } 


}