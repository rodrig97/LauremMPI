<?php

class variabletipoplanilla extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'varpla_id',
                                    'code'  => 'varpla_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'varpla_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'variables_tipoplanilla';
    protected $_PREF_TABLE= 'VARIABLEX'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
    
    public function get_list($tipo_planilla){
        
        $sql = "  
                 SELECT varpla_id, 
                        varis.vari_id as variable_id,
                        rela.plati_id as tipoplanilla_id, 
			platis.plati_nombre as tipoplanilla,
			varis.vari_nombre, varis.vari_nombrecorto 

		 FROM planillas.variables_tipoplanilla rela
                 LEFT JOIN planillas.variables varis ON rela.vari_id = varis.vari_id
		 LEFT JOIN planillas.planilla_tipo platis ON rela.plati_id = platis.plati_id 
           	
                 WHERE rela.varpla_estado = 1 AND rela.plati_id = ? 
                 
                 ORDER BY platis.plati_nombre, varis.vari_nombre
 
                ";
        
        return $this->_CI->db->query($sql, array($tipo_planilla))->result_array();
        
    }
    
     
}