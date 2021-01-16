<?php

class actividad  extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => 'acti_id',
                                    'code'  => 'acti_key',
                                    'name'  => 'acti_nombre',
                                    'descripcion' => '',
                                    'state' => 'acti_estado'
                            );
    
    protected $_SCHEMA = 'sisplae';
    protected $_TABLE = 'actividades';
    protected $_PREF_TABLE= 'ACTI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    
    public function get_actividad($id){
         
         // retorna todo la info enrelacion a una actividad, 
         $sql = 'SELECT nivs.pes_id,nivs.pes_nombre,nivs.pes_key, nivs2.pes_nombre as obj_general, tareas.tarea_id,tareas.tarea_key,
                        tareas.tarea_codigo, tareas.tarea_nombre,  acti.*,actfi.*,unmed_nombre, 
                    
                       (Select count(actac.actacc_id) from sisplae.actividad_acciones actac where actac.actacc_estado = 1 AND actac.acti_id = acti.acti_id   ) as numero_acciones,
                       (Select count(actbs.actbs_id) from sisplae.actividad_items actbs where  actbs.actbs_estado = 1 AND actbs.acti_id = acti.acti_id   ) as numero_items
                      
              
                 FROM sisplae.actividades acti LEFT JOIN sisplae.actividad_fisico actfi 
                 ON actfi.acti_id = acti.acti_id 
                 LEFT JOIN sisplae.unidades_medida unmed ON actfi.unmed_id = unmed.unmed_id
                 LEFT JOIN sisplae.tarea_objetivos taobs ON acti.taob_id = taobs.taob_id
                 LEFT JOIN sisplae.pei_estructura nivs ON taobs.pes_id = nivs.pes_id
                 LEFT JOIN sisplae.pei_estructura nivs2 ON nivs.pes_parent = nivs2.pes_id
                 LEFT JOIN sisplae.tareas tareas ON  taobs.tarea_id = tareas.tarea_id
                 WHERE acti.acti_id = ? AND actfi.actfi_estado = 1 
                 LIMIT 1';
       //  echo $sql;
         $q = $this->_CI->db->query($sql,array($id))->result_array();
         return $q[0];
         
         
         
    }
    
    public function registrar($taob = '', $nombre = '', $descripcion = '', $unmed = '', $tipo = '', $system = false, $meses = array() ){
        
       
         $this->_CI->db->trans_begin();
        
        list($id_nact,$key_nact) = $this->add(array('acti_nombre' => $nombre, 'acti_tipo' => $tipo, 'taob_id' => $taob), true);
        $sql = 'SELECT pes_id FROM  sisplae.tarea_objetivos WHERE taob_id = ? ';  // seleccionamos el id del objetivo esp de la tarea_objetivo
        $q =  $this->_CI->db->query($sql,array($taob_id))->result_array();
        $id_objetivo = $q[0]['pes_id'];
        
        $this->_CI->db->where('acti_id',$id_nact);
        $this->_CI->db->update('sisplae.actividades',array('pes_id' => $id_objetivo));
        
        
        $sql = "INSERT INTO sisplae.actividad_fisico(acti_id,_taob_id,unmed_id,
                                                     actfi_mes1,actfi_mes2,actfi_mes3,actfi_mes4,actfi_mes5,actfi_mes6,
                                                     actfi_mes7,actfi_mes8,actfi_mes9,actfi_mes10,actfi_mes11,actfi_mes12)
                                                    
                            VALUES(?,?,?,
                                   ?,?,?,?,?,?,
                                   ?,?,?,?,?,?)";
        
         $params = array( $id_nact,$taob,$unmed );
         
         foreach($meses as $mesv) $params[] = $mesv;
          
         $q =  $this->_CI->db->query($sql,$params);
         $id=$this->_CI->db->insert_id();
       
         $code=$id.'ACTFI';
         $code=md5($code);
         $this->_CI->db->where('actfi_id', $id);
         $this->_CI->db->update('sisplae.actividad_fisico', array( 'actfi_key' => $code )); //Generate code
         
        
        if ($this->_CI->db->trans_status() === FALSE)
        {
            $this->_CI->db->trans_rollback();
            return false;
        }
        else
        {
            $this->_CI->db->trans_commit();
            return true;
        } 
         
    }
    
    
    public function __actualizar($id = '',$nombre = '', $descripcion = '', $unmed = '', $tipo = '', $system = false, $meses = array()){
        
        
    }
    
    public function actualizar($acti_id = '',$actfi = '',$n_nombre = '',$n_unmed = '',$meses = array(), $tipo = 1 ){
        
         // recuperar codigo de acti, y actividad fisico
        
         // actualiza estado de la actividad
          $this->_CI->db->trans_begin();
          
         $sql = 'SELECT actfi_id FROM sisplae.actividad_fisico WHERE actfi_key = ? LIMIT 1';
         $actfi_id_t=$this->_CI->db->query($sql,array($actfi))->result_array();
         $actfi_id= $actfi_id_t[0]['actfi_id'];
              
         $sql = 'SELECT taob_id,acti_rela FROM sisplae.actividades WHERE acti_id = ? LIMIT 1';
         $reg_t=$this->_CI->db->query($sql,array($acti_id))->result_array();
         $taob_id= $reg_t[0]['taob_id'];
         $acti_rela = $reg_t[0]['acti_rela'];
         $acti_rela = (trim($acti_rela) != '0') ? $acti_rela : $acti_id;
       
         $sql = 'UPDATE sisplae.actividades SET acti_estado = 2 WHERE acti_id = ? ';
         $this->_CI->db->query($sql,array($acti_id));
         
         $sql = 'UPDATE sisplae.actividad_fisico SET actfi_estado = 2 WHERE actfi_id = ? ';
         $this->_CI->db->query($sql,array($actfi_id));
         
         
         $sql = 'INSERT INTO sisplae.actividades(acti_nombre,acti_tipo,taob_id,acti_rela) VALUES(?,?,?,?)';
         $this->_CI->db->query($sql,array($n_nombre,$tipo,$taob_id,$acti_rela));
         $acti_id=$this->_CI->db->insert_id();
         
         $code=$acti_id.'acti';
         $code1=md5($code);
         $this->_CI->db->where('acti_id',$acti_id);
         $this->_CI->db->update( 'sisplae.actividades', array(  'acti_key' => $code1 )); //Generate code
          
         
         $sql = 'SELECT actfi_rela FROM sisplae.actividad_fisico WHERE actfi_id = ? LIMIT 1';
         $actfi_rela=$this->_CI->db->query($sql,array($actfi_id))->result_array();
         $actfi_rela= $actfi_rela[0]['actfi_rela'];
         $actfi_rela = (trim($actfi_rela) != '0') ? $actfi_rela : $actfi_id;
         
         
         $sql = 'INSERT INTO sisplae.actividad_fisico(acti_id,unmed_id,actfi_rela,
                                                      actfi_mes1,actfi_mes2,actfi_mes3,actfi_mes4,actfi_mes5,actfi_mes6,
                                                      actfi_mes7,actfi_mes8,actfi_mes9,actfi_mes10,actfi_mes11,actfi_mes12)
                                                      
                                                      VALUES(?,?,?,
                                                             ?,?,?,?,?,?,
                                                             ?,?,?,?,?,?)';
         
         
      //   $this->_CI->db->query($sql,array($n_nombre,$tipo,$taob_id,$acti_id));
          
         $params = array( $acti_id,$n_unmed,$actfi_rela );
         
         foreach($meses as $mesv) $params[] = trim($mesv);
         
      //   var_dump($params);
         
         $q =  $this->_CI->db->query($sql,$params);
         $id=$this->_CI->db->insert_id();
         
         $code=$id.'ACTFI';
         $code=md5($code);
         $this->_CI->db->where('actfi_id', $id);
         $this->_CI->db->update('sisplae.actividad_fisico', array( 'actfi_key' => $code )); //Generate code
         
        if ($this->_CI->db->trans_status() === FALSE)
        {
            $this->_CI->db->trans_rollback();
            return false;
        }
        else
        {
            $this->_CI->db->trans_commit();
            return true;
        } 

         
    }
    
    
    public function eliminar($id = '',$motivo = ''){
        
        //Comprobar si tiene acciones y registro de bienes, updata state, actividad a 0 y fisico a 0
          $this->_CI->db->trans_begin();
          
          $affect = array();
          
            $sql = 'UPDATE sisplae.actividades SET acti_estado = 0 WHERE acti_id = ?';
            $this->_CI->db->query($sql,array($id));
            $affect['actividades']=$this->_CI->db->affected_rows();
            
            $sql = 'UPDATE sisplae.actividad_fisico SET actfi_estado = 0 WHERE actfi_estado = 1 AND acti_id = ?';
            $this->_CI->db->query($sql,array($id));
            $affect['fisico']=$this->_CI->db->affected_rows();
            
            $sql = 'UPDATE sisplae.actividad_acciones SET  actacc_estado = 0 WHERE acti_id = ? ';
            $this->_CI->db->query($sql,array($id));
            $affect['acciones']=$this->_CI->db->affected_rows();
            
            $sql = 'UPDATE sisplae.actividad_items SET actbs_estado = 0 WHERE acti_id = ? ';
            $this->_CI->db->query($sql,array($id));
            $affect['items']=$this->_CI->db->affected_rows();
            
            
            if ($this->_CI->db->trans_status() === FALSE)
            {
                $this->_CI->db->trans_rollback();
                return false;
            }
            else
            {
                $this->_CI->db->trans_commit();
                return array(true,$affect);
            } 
          
    }
    
    
    public function get_by_tareaobjetivo($id_param, $tipo = 1){
        
        //echo 'TAREA: '.$id_param;
      
        if($tipo==1 || $tipo==2 ){
           
             
            $sql = ' SELECT taobs.taob_id,taobs.taob_key,nivs.pes_id,pes_nombre,pes_key, actis.acti_id,actis.acti_tipo,actfis.unmed_id,unmed.unmed_key,
            
				(Select count(acti_id) FROM sisplae.actividades actis2
                                                       LEFT JOIN sisplae.tarea_objetivos taob2 ON actis2.taob_id = taob2.taob_id 
				  WHERE actis2.acti_estado = 1 AND actis2.taob_id = taob2.taob_id AND taob2.taob_id = taobs.taob_id   ) as n_actividades,
                                  
                       (Select count(actac.actacc_id) from sisplae.actividad_acciones actac where actac.actacc_estado = 1 AND actac.acti_id = actis.acti_id   ) as numero_acciones,
                       (Select count(actbs.actbs_id) from sisplae.actividad_items actbs where  actbs.actbs_estado = 1 AND actbs.acti_id = actis.acti_id   ) as numero_items
                      

		 ,acti_nombre,acti_key,actfi_key, unmed_nombre,actfis.actfi_id, 
		  actfi_mes1,actfi_mes2,actfi_mes3,actfi_mes4,actfi_mes5,actfi_mes6
		 ,actfi_mes7,actfi_mes8,actfi_mes9,actfi_mes10,actfi_mes11,actfi_mes12 

		FROM sisplae.actividades actis 
                LEFT JOIN sisplae.actividad_fisico actfis  ON  actis.acti_id = actfis.acti_id 
                LEFT JOIN sisplae.tarea_objetivos taobs ON actis.taob_id = taobs.taob_id
                LEFT JOIN sisplae.pei_estructura nivs ON taobs.pes_id = nivs.pes_id
	        LEFT JOIN sisplae.unidades_medida unmed ON actfis.unmed_id = unmed.unmed_id 
						 
                WHERE     taobs.taob_estado = 1 AND
			    taobs.taob_id = ?   AND actis.acti_estado = 1 AND actis.acti_generate = 0  AND actfis.actfi_estado = 1 
                
                ORDER BY taobs.taob_id asc,nivs.pes_id, actis.acti_rela asc, actis.acti_id desc     
                     
                ';
            
            $params= array($id_param);
            
        }
        else if($tipo==3){

                $sql = '

                          Select rela_taobs.pes_id,rela_taobs.pes_nombre,rela_taobs.pes_key
                                 ,rela_taobs.taob_id, rela_taobs.taob_key,rela_actis.acti_id,rela_actis.acti_tipo,rela_actis.unmed_id,rela_actis.unmed_key,rela_actis.n_actividades,rela_actis.numero_acciones,rela_actis.numero_items
                                 ,acti_nombre,acti_key,actfi_key, unmed_nombre,rela_actis.actfi_id 
                                 ,actfi_mes1,actfi_mes2,actfi_mes3,actfi_mes4,actfi_mes5,actfi_mes6
                                 ,actfi_mes7,actfi_mes8,actfi_mes9,actfi_mes10,actfi_mes11,actfi_mes12 

                          FROM(
                                     SELECT pes.pes_id,pes.pes_nombre,pes.pes_key,taobs.taob_id,taobs.taob_key  
                                     FROM  sisplae.tarea_objetivos taobs LEFT JOIN sisplae.pei_estructura pes ON taobs.pes_id = pes.pes_id
                                     WHERE taob_estado = 1 AND taobs.tarea_id = ?
                            ) rela_taobs 

                            LEFT JOIN (

                                     SELECT actis.taob_id,actis.acti_id,actis.acti_tipo,actfis.unmed_id,unmed.unmed_key,actfis.actfi_id,

                                                    (Select count(acti_id) FROM sisplae.actividades actis2
                                                                           LEFT JOIN sisplae.tarea_objetivos taob2 ON actis2.taob_id = taob2.taob_id 
                                                      WHERE actis2.acti_estado = 1 AND actis2.taob_id = taob2.taob_id AND taob2.taob_id = taobs.taob_id   ) as n_actividades,

                                           (Select count(actac.actacc_id) from sisplae.actividad_acciones actac where actac.actacc_estado = 1 AND actac.acti_id = actis.acti_id   ) as numero_acciones,
                                           (Select count(actbs.actbs_id) from sisplae.actividad_items actbs where  actbs.actbs_estado = 1 AND actbs.acti_id = actis.acti_id   ) as numero_items


                                     ,acti_nombre,acti_key,actfi_key, unmed_nombre, 
                                      actfi_mes1,actfi_mes2,actfi_mes3,actfi_mes4,actfi_mes5,actfi_mes6
                                     ,actfi_mes7,actfi_mes8,actfi_mes9,actfi_mes10,actfi_mes11,actfi_mes12 

                                    FROM sisplae.actividades actis 
                                    LEFT JOIN sisplae.actividad_fisico actfis  ON  actis.acti_id = actfis.acti_id 
                                    LEFT JOIN sisplae.tarea_objetivos taobs ON actis.taob_id = taobs.taob_id
                                    LEFT JOIN sisplae.unidades_medida unmed ON actfis.unmed_id = unmed.unmed_id 

                                    WHERE     taobs.taob_estado = 1 AND
                                              taobs.tarea_id = ?  AND actis.acti_estado = 1 AND actis.acti_generate = 0  AND actfis.actfi_estado = 1 





                            ) rela_actis ON rela_taobs.taob_id = rela_actis.taob_id
                             ORDER BY   pes_id,acti_id
                        ';
                
                $params= array($id_param,$id_param);
        }
      
       // echo $sql;
        //if($order == '1') $sql.='  '; 
      //  echo $sql;
        $q =  $this->_CI->db->query($sql,$params)->result_array();
       
        return $q;
        
        
    }
    
    public function trasladar_objetivo($acti,$new_taob){
        
        $sql = 'UPDATE sisplae.actividades SET taob_id = ? WHERE acti_id = ? ';
         
        $q = $this->_CI->db->query($sql, array( $new_taob, $acti) );
          
         return ($this->_CI->db->affected_rows()== 1) ? true : false;
        
    }
    
} 


?>
