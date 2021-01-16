<?php

class variable extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'vari_id',
                                    'code'  => 'vari_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'vari_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'variables';
    protected $_PREF_TABLE= 'VARIABLEX'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
    
    
    public function get($vari_id)
    {
        
         $params =  array($vari_id);
         
         $sql = " SELECT   vars.vari_key as key,
                           vars.*, 
                           tipla.plati_nombre as tipo_planilla,
                           oprint.opim_nombre as impresion,
                            ( CASE WHEN gr.gvc_nombre is null THEN 
                             '------- '
                                ELSE  
                                     gr.gvc_nombre
                                END   ) as grupo_nombre,

                            optper.vape_nombre as personalizable,
                            uni.vau_nombre as unidad,
                            uni.vau_abrev as unidad_abrev  
                       
                            FROM   planillas.variables vars 
                            LEFT JOIN planillas.planilla_tipo tipla ON vars.plati_id = tipla.plati_id
                            LEFT JOIN planillas.grupos_vc gr ON vars.gvc_id = gr.gvc_id 
                            LEFT JOIN planillas.opcion_impresion oprint ON vars.vari_displayprint = oprint.opim_id
                            LEFT JOIN planillas.variable_personalizable optper ON vars.vari_personalizable = optper.vape_id
                            LEFT JOIN planillas.variable_unidad uni ON vars.vau_id = uni.vau_id
                            WHERE vars.vari_id = ?  ";
         
         $rs = $this->_CI->db->query($sql, $params)->result_array();
         
         return $rs[0];
    }

    
    public function get_list($params = array())
    {
    
        $q = array();
        
        $sql = "SELECT vars.vari_key as key,vars.*, 
                       tipla.plati_nombre as tipo_planilla,
                       oprint.opim_nombre as impresion, 
                       ( CASE WHEN gr.gvc_nombre is null THEN 
                             '------- '
                        ELSE  
                             gr.gvc_nombre
                        END   ) as grupo_nombre,
                        
                        optper.vape_nombre as personalizable,
                        uni.vau_nombre as unidad,
                        uni.vau_abrev as unidad_abrev   

                FROM   planillas.variables vars 
                INNER JOIN planillas.planilla_tipo tipla ON vars.plati_id = tipla.plati_id AND tipla.plati_estado = 1
                LEFT JOIN planillas.grupos_vc gr ON vars.gvc_id = gr.gvc_id 
                LEFT JOIN planillas.opcion_impresion oprint ON vars.vari_displayprint = oprint.opim_id
                LEFT JOIN planillas.variable_personalizable optper ON vars.vari_personalizable = optper.vape_id
                LEFT JOIN planillas.variable_unidad uni ON vars.vau_id = uni.vau_id  
                
                WHERE vars.vari_estado = 1 
               ";
        
        if($params['tipoplanilla'] != '')
        {
            $sql.=" AND vars.plati_id = ?";
            $q[] = $params['tipoplanilla'];
        }
        
        if($params['nombre'] != '')
        {
            $sql.=" AND vars.vari_nombre like '%".$params['nombre']."%'";
        }
 
        if($params['grupo'] != '')
        {
            $sql.=" AND vars.gvc_id = ?";
            $q[] = $params['grupo'];
        }
        
        
        if( trim($params['afecto_cuarta_quinta']) != '' && trim($params['afecto_cuarta_quinta']) != '0'){

            $sql.=" AND vars.vari_conc_afecto_cuarta_quinta = ?";
            $q[] = $params['afecto_cuarta_quinta'];
        }

        if($params['personalizable'] === true)
        {
            $sql.=" AND vars.vari_personalizable != ".VARIABLE_PERSONALIZABLE_NO;
        }

        if($params['personalizable'] === false)
        {
            $sql.=" AND vars.vari_personalizable = ".VARIABLE_PERSONALIZABLE_NO;
        }
        

        $sql .="  ORDER BY  tipo_planilla, vars.gvc_id,  vars.vari_nombre   ";
        
       return $this->_CI->db->query($sql,$q)->result_array();
        
    }
    
    public function get_aplicables($tipo_planilla, $ops = array()){
        
        $params = array($tipo_planilla);
        
        $sql = " SELECT * FROM planillas.variables varis
                 WHERE varis.vari_estado = 1 AND plati_id = ? ";
        
        if($ops['predeterminado'] != ''){
             
            $sql.= " AND vari_esxdefecto = ? ";
            $params[] = ($ops['predeterminado']) ? '1' : '0'; 
                
        }
        
        return $this->_CI->db->query($sql,$params)->result_array();
        
    }
      
    public function get_operando_conceptos($vari_id){
         
        
        $rs = array();
        
    /*    $sql  =" SELECT  distinct(conc.conc_id) as concepto_id, 
                        conc_nombre, conc_nombrecorto, tipla.plati_nombre, conc.conc_key as concepto_key 
                        FROM planillas.conceptos_ops ops  
                        INNER JOIN planillas.conceptos conc ON ops.conc_id = conc.conc_id AND conc.conc_estado = 1
                        INNER JOIN planillas.planilla_tipo tipla ON tipla.plati_id = conc.plati_id  
                        WHERE coops_estado = 1  AND ( ( coops_operando1 = ? AND coops_operando1_t = 1  ) OR ( coops_operando2 = ? AND coops_operando2_t = 1  ) ) ";

        */

         $sql  =" SELECT  distinct(conc.conc_id) as concepto_id, 
                                 conc_nombre, 
                                 conc_nombrecorto, 
                                 plati.plati_nombre, 
                                 conc.conc_ecuacion_id,
                                 conc.conc_key as concepto_key 
                         
                         FROM planillas.conceptos_ops ops  
                         INNER JOIN planillas.conceptos conc ON ops.conc_id = conc.conc_id AND ops.coops_ecuacion_id = conc.conc_ecuacion_id
                         LEFT JOIN planillas.planilla_tipo plati ON plati.plati_id = conc.plati_id  
                        
                         WHERE  ops.coops_estado = 1 AND 
                                conc.conc_estado = 1 AND  
                                ( ( coops_operando1 = ? AND coops_operando1_t = ".TIPOOPERANDO_VARIABLE."  ) OR ( coops_operando2 = ? AND coops_operando2_t = ".TIPOOPERANDO_VARIABLE."  ) )";
                

        $rs =  $this->_CI->db->query($sql,array($vari_id,$vari_id))->result_array();
        
        return $rs;
        
    }  


    public function actualizar_valor_planilla($pla_id, $vari_id, $valor){

       $sql ="UPDATE planillas.planilla_empleado_variable plaev
              SET plaev_valor = ?, plaev_valor_bk =  plaev_valor 
              WHERE plaev.plaev_estado = 1 AND plaev.vari_id = ? 
                    AND plaemp_id in (
                       SELECT plaemp_id FROM planillas.planilla_empleados plaemp WHERE pla_id = ? AND plaemp_estado = 1
                    ) 
            ";

       return ($this->_CI->db->query($sql, array($valor,$vari_id,$pla_id))) ? true : false;     
    }
    

    public function delete($vari_id){

        
         $this->_CI->load->library(array('App/conceptooperacion'));

         $this->_CI->db->trans_begin();
 
         $concs_relacionados = $this->get_operando_conceptos($vari_id);
 
         foreach($concs_relacionados as $conc){

            $conc_id = $conc['concepto_id'];

            $ecuacion_id = $this->_CI->conceptooperacion->get_next_ecuacion_id($conc_id);
            $ecuacion_id_anterior = $ecuacion_id - 1;

            $sql = "INSERT INTO planillas.conceptos_ops 
                         (coops_enlace, coops_operando1, coops_operando1_t, coops_operador, 
                          coops_operando2, coops_operando2_t, coops_grupo, conc_id, 
                          coops_orden, coops_estado, coops_ecuacion_id ) 
                     (SELECT   coops_enlace, coops_operando1, coops_operando1_t, coops_operador, coops_operando2, 
                               coops_operando2_t, coops_grupo, conc_id, coops_orden, coops_estado, ?
                      FROM planillas.conceptos_ops 
                       WHERE conc_id = ? AND coops_estado = 1  ORDER BY coops_id)"; 

             $this->_CI->db->query($sql, array($ecuacion_id, $conc_id));
 
             $sql = "UPDATE planillas.conceptos_ops SET coops_estado = 0 WHERE conc_id =  ? AND coops_ecuacion_id = ? "; 
             $this->_CI->db->query($sql, array($conc_id, $ecuacion_id_anterior));

             $sql = "UPDATE planillas.conceptos_ops SET coops_key = md5(coops_id || 'ecuacoop') WHERE conc_id =  ? AND coops_ecuacion_id = ? "; 
             $this->_CI->db->query($sql, array($conc_id, $ecuacion_id));

             $sql = "UPDATE planillas.conceptos_ops SET coops_operando1 = 0, coops_operando1_t = 3 
                      WHERE (coops_operando1_t = 1 AND coops_operando1 = ? )  AND coops_estado  = 1 AND conc_id =  ? AND coops_ecuacion_id = ? "; 

             $this->_CI->db->query($sql, array( $vari_id, $conc_id, $ecuacion_id));

              $sql = "UPDATE planillas.conceptos_ops SET coops_operando2 = 0, coops_operando2_t = 3 
                      WHERE (coops_operando2_t = 1 AND coops_operando2 = ? ) AND coops_estado  = 1 AND conc_id =  ? AND coops_ecuacion_id = ? "; 

             $this->_CI->db->query($sql, array( $vari_id, $conc_id, $ecuacion_id));


         } 


         $sql =" UPDATE planillas.empleado_variable SET empvar_estado = 0, empvar_fecmod = now() WHERE vari_id = ? ";
         $this->_CI->db->query($sql, array($vari_id));

         $sql= " UPDATE planillas.variables SET vari_estado = 0 WHERE vari_id = ? ";
         $this->_CI->db->query($sql, array($vari_id));


         if ($this->_CI->db->trans_status() === FALSE)
         {
            $this->_CI->db->trans_rollback();
            $ok= false;
         }
         else
         {
            $this->_CI->db->trans_commit();
            $ok = true;
         }    
  

          return $ok; 
    }


    public function get_list_personalizable()
    {
        $sql = "SELECT * FROM planillas.variable_personalizable WHERE vape_estado = 1 ORDER BY vape_id ";
        return $this->_CI->db->query($sql, array())->result_array();
    } 

    public function get_list_unidades()
    {
        $sql = "SELECT * FROM planillas.variable_unidad WHERE vau_estado = 1 ORDER BY vau_id ";
        return $this->_CI->db->query($sql, array())->result_array();
    }

    public function get_tabla_datos($params)
    {
        
        $sql = "  SELECT * FROM planillas.variables_tabla_datos 
                  WHERE vtd_estado = 1 AND plati_id = ? 
                  ORDER BY vtd_nombre ";

        $rs = $this->_CI->db->query($sql, array($params['plati_id']))->result_array();

        return $rs;
    }


}