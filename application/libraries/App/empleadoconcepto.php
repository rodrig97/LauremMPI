<?php
  
class empleadoconcepto extends Table{
     
    
    protected $_FIELDS=array(   
                                    'id'    => 'empcon_id',
                                    'code'  => 'empcon_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'empcon_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'empleado_concepto';
    protected $_PREF_TABLE= 'EMPCONCEPTO'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }
    
    
    public function get($empcon_id)
    {

      $sql =" SELECT * FROM planillas.empleado_concepto WHERE empcon_id = ?  LIMIT 1";
      $rs = $this->_CI->db->query($sql, array($empcon_id))->result_array();

      return $rs[0];

    } 

    public function get_list($empleado_id, $tipo_id = '0', $plati_id = '0')
    {
         
        $params = array($empleado_id);
        
        $sql = "SELECT empcon.indiv_id as persona_id, 
                      concs.*,empcon.empcon_key,
                      concs.conc_nombre as concepto, 
                      cpc.id_clasificador as partida_id, 
                      cpc.copc_id as afectacion_id, 
                      gr.gvc_nombre as grupo,
                      ecb.*,  
                      (  ind.indiv_appaterno ||' '|| ind.indiv_apmaterno || ' ' ||  ind.indiv_nombres || ' ('|| ind.indiv_dni || ')' ) as beneficiario    
                      
                      FROM planillas.empleado_concepto empcon  
                      LEFT JOIN planillas.conceptos concs ON empcon.conc_id = concs.conc_id AND concs.conc_estado = 1
                      LEFT JOIN planillas.grupos_vc gr ON gr.gvc_id = concs.gvc_id 
                      LEFT JOIN planillas.conceptos_presu_cont cpc ON concs.conc_id = cpc.conc_id AND copc_estado = 1 
                      LEFT JOIN planillas.empleado_concepto_beneficiario ecb ON ecb.empcon_id = empcon.empcon_id AND ecb_estado = 1
                      LEFT JOIN public.individuo ind ON ecb.indiv_id_b = ind.indiv_id 
                      
                WHERE empcon.empcon_estado = 1 AND empcon.indiv_id = ? ";
        

        if($tipo_id!= '0'){

            $sql.= " AND concs.conc_tipo = ?  ";
            $params[] = $tipo_id;  
 
        }                  

       if($plati_id != '0'){

            $sql.= " AND concs.plati_id = ?  ";
            $params[] = $plati_id;  
 
        }                  
 

        $sql.=" ORDER BY concs.gvc_id, concs.conc_nombre ";

        return $this->_CI->db->query($sql, $params)->result_array();
        
    }

    public function get_all_variables($empleado_id, $plati_id)
    {

         /*
          ESTA FUNCION RETORNA UN RECORDSET CON TODAS LAS VARIABLES 
          PERSONALIZABLES QUE LE PERTENECEN AL USUARIO POR TODOS LOS CONCEPTOS 
         */
 
         $sql = " SELECT  rs_u.var_id,
                           vars.*, 
                           ev.empvar_id,
                           COALESCE(ev.empvar_value,0) as valor_establecido,

                            COALESCE((
                               CASE WHEN ev.empvar_id is null THEN
                                  vari_valordefecto
                               ELSE  
                                  empvar_value
                               END   
                            ),0) as valor,  
 
                            gr.gvc_nombre as grupo  

                            FROM 

                          ( SELECT (vars_union.var_id) 

                            FROM (      /* OBTENIENDO LAS VARIABLES (op1) DE LAS ECUACIONES DE LOS CONCEPTOS */
                                   ( SELECT (coops_operando1) as var_id 
                                     FROM planillas.conceptos_ops ops 
                                     WHERE (ops.coops_operando1_t = 1)  
                                           AND ops.conc_id  IN 
                                                               ( SELECT conc_union.conc_id  /* BUSCAMOS LOS CONCEPTOS POR DEFECTO DE LA PLANILLA Y LOS QUE YA TIENE EL EMPLEADO */
                                                                 FROM (
                                                                        ( SELECT conc_id 
                                                                          FROM planillas.conceptos 
                                                                          WHERE conc_estado = 1 AND conc_esxdefecto = 1 AND plati_id = ? )
                                                                 
                                                                         UNION ( SELECT ec.conc_id 
                                                                                 FROM planillas.empleado_concepto ec
                                                                                 INNER JOIN planillas.conceptos concs ON ec.conc_id = concs.conc_id
                                                                                 WHERE empcon_estado = 1 AND indiv_id = ?  AND plati_id = ? )
                                                                           ) as conc_union  /* FIN UNION 1*/   
                                                                      ) 
                                                                ) /* FIN IN 1*/ 
                                                                
                                                                UNION (  /* OBTENIENDO LAS VARIABLES (op2) DE LAS ECUACIONES DE LOS CONCEPTOS */
                                                                            SELECT   (coops_operando2) as var_id 
                                                                            FROM planillas.conceptos_ops ops 
                                                                            WHERE (ops.coops_operando2_t = 1) 
                                                                                  AND ops.conc_id IN 
                                                                                
                                                                                  (SELECT conc_union.conc_id  /* BUSCAMOS LOS CONCEPTOS POR DEFECTO DE LA PLANILLA Y LOS QUE YA TIENE EL EMPLEADO */
                                                                                      FROM ( (SELECT conc_id FROM planillas.conceptos WHERE conc_estado = 1 AND conc_esxdefecto = 1 AND plati_id = ?)
                                                                                          UNION ( SELECT ec.conc_id FROM planillas.empleado_concepto ec
                                                                                                  INNER JOIN planillas.conceptos concs ON ec.conc_id = concs.conc_id
                                                                                                  WHERE empcon_estado = 1 AND indiv_id = ?  AND plati_id = ? )
                                                                                      ) as conc_union /* FIN UNION 2*/        
                                                                                  ) /* FIN IN 2 */

                                                                          ) 
                                                                 UNION (

                                                                      SELECT vari_id 
                                                                      FROM planillas.variables vari 
                                                                      WHERE vari_estado = 1 AND plati_id = ? AND vari_conc_afecto_cuarta_quinta = 1
                                                                                                                                    
                                                                 )

                                                                  ) as vars_union ) as rs_u 
                        INNER JOIN planillas.variables vars ON rs_u.var_id = vars.vari_id AND (vars.vari_personalizable = ".VARIABLE_PERSONALIZABLE_GESTIONDATOS." OR vars.vari_personalizable = ".VARIABLE_PERSONALIZABLE_AMBOS." )
                        LEFT JOIN planillas.grupos_vc gr ON vars.gvc_id = gr.gvc_id
                        LEFT JOIN planillas.empleado_variable ev ON rs_u.var_id = ev.vari_id AND ev.empvar_estado =  1 AND ev.indiv_id  = ? 

                        WHERE vars.vari_estado = 1
                        ORDER BY vars.gvc_id,vars.vari_nombre
                    ";

       return $this->_CI->db->query($sql, array($plati_id, $empleado_id, $plati_id, $plati_id, $empleado_id, $plati_id, $plati_id, $empleado_id))->result_array();
    }
    


    public function registrar($indiv_id, $conc_id )
    {
   
          $this->_CI->load->library(array('App/empleadovariable'));
 
          $this->_CI->db->trans_begin();

          $ok = false;
          
           // Deshabilitamos el concepto si lo tiene 
          $sql = " UPDATE planillas.empleado_concepto 
                   SET empcon_estado = 0, empcon_fecmod = now() 
                   WHERE indiv_id = ? AND conc_id = ? AND empcon_estado = 1 ";  

          $this->_CI->db->query($sql, array($indiv_id, $conc_id) );

          /*
          $sql =" SELECT * FROM planillas.conceptos WHERE conc_id = ? LIMIT 1 ";
          list($conc_info) = $this->_CI->db->query($sql, array($conc_id))->result_array();
 

           // BUSCANDO LAS VARIABLES Y VERIFICANDO SI EL TRABAJADOR YA LAS TIENE 

                   $sql = "  SELECT  *,vars.vari_id as variable_id 
                                
                                      FROM planillas.variables vars 
                                      LEFT JOIN planillas.empleado_variable pe  ON pe.empvar_estado = 1 AND pe.vari_id = vars.vari_id AND pe.indiv_id = ?
                                      
                              WHERE  

                                  vars.vari_id in ( SELECT (var_concepto.var_id) 
                                                          FROM ( 
                                                          (
                                                              SELECT   (coops_operando1) as var_id
                                                              FROM planillas.conceptos_ops ops
                                                              WHERE (ops.coops_operando1_t = 1)  AND ops.coops_ecuacion_id = ? 
                                                              AND ops.conc_id = ?
                                                          )
                                                           UNION (

                                                              SELECT   (coops_operando2) as var_id
                                                              FROM planillas.conceptos_ops ops
                                                              WHERE (ops.coops_operando2_t = 1)  AND ops.coops_ecuacion_id = ?  
                                                              AND ops.conc_id = ? ) 

                                                          ) as var_concepto ) ";
          
            
                   $variables_del_concepto = $this->_CI->db->query($sql, array($indiv_id, $conc_info['conc_ecuacion_id'], $conc_id, $conc_info['conc_ecuacion_id'], $conc_id ))->result_array();
                  
              
                   foreach($variables_del_concepto as $vari)
                   {

                         if( trim($vari['empvar_id']) == ''  || trim($vari['empvar_estado']) == '0' ){  // SI NO TIENE LA VARIABLE

                             $data = array(
                                            'indiv_id'            => $indiv_id,
                                            'vari_id'             => $vari['variable_id'],
                                            'empvar_value'        => $vari['vari_valordefecto'],
                                            'empvar_displayprint' => $vari['vari_displayprint']  

                                          );  

                           
                             $this->_CI->empleadovariable->registrar($data, false);

                         }

                   }
              */ 


                   // REGISTRAR EL CONCEPTO
                   $sql = " SELECT conc_displayprint FROM planillas.conceptos WHERE conc_id = ? ";   
                   $rs  = $this->_CI->db->query($sql, array($vari_id))->result_array(); 
                   
                   $opcion_impresion = $rs[0]['conc_displayprint'];

                   $data  = array(
                                  'indiv_id'            => $indiv_id,
                                  'conc_id'             => $conc_id,
                                  'empcon_displayprint' => $opcion_impresion ) ;

                   
                   parent::registrar($data,false);
                

                   if($this->_CI->db->trans_status() === FALSE) 
                   {  
                          $this->_CI->db->trans_rollback();
                          $ok = false;
                          
                   }else{
                              
                          $this->_CI->db->trans_commit();
                         $ok= true;
                   } 

              return $ok;
 
    }



    public function desvincular_concepto($empcon_id){


        $this->_CI->db->trans_begin();

        if( func_num_args() == 1)
        { 
            $sql = " UPDATE planillas.empleado_concepto SET empcon_estado = 0, empcon_fecmod = now() WHERE empcon_id = ? AND empcon_estado = 1   "; 
            $this->_CI->db->query($sql, array($empcon_id));
        }
        else if(func_num_args() == 2 ){

            $args= func_get_args();
        
            $sql = " UPDATE planillas.empleado_concepto 
                     SET  empcon_estado = 0, 
                          empcon_fecmod = now() 
                     WHERE empcon_estado = 1 AND indiv_id = ?  AND conc_id = ?   ";

             $this->_CI->db->query($sql, $args );        

        }
        else if(func_num_args() == 3)
        {

             $args= func_get_args();
          
             if($args[2] == true)
             { 
               $sql = " DELETE FROM  planillas.empleado_concepto  
                        WHERE  indiv_id = ?  AND conc_id = ?  ";

               $this->_CI->db->query($sql, $args );        
   
             }
        }
      
       if($this->_CI->db->trans_status() === FALSE) 
       {  
               $this->_CI->db->trans_rollback();
              return false;
                          
       }else{
                              
              $this->_CI->db->trans_commit();
              return true;
       } 
 
    }

    public function get_acceso_rapido($indiv_id = 0, $plati_id = 0)
    {
 
        $sql ="  SELECT * 
                 FROM planillas.conceptos conc
                 LEFT JOIN planillas.empleado_concepto empcon ON conc.conc_id = empcon.conc_id AND empcon_estado = 1 AND empcon.indiv_id = ?
                 WHERE conc.conc_estado = 1 AND conc.conc_accesorapido = 1 AND  conc.plati_id = ?  
               ";

        $rs = $this->_CI->db->query($sql, array( $indiv_id,$plati_id) )->result_array();
        
        return $rs;
    }
    
}