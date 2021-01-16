<?php

class especialidad extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'especi_id',
                                    'code'  => 'especi_key',
                                    'name'  => 'especi_nombre',
                                    'descripcion' => '',
                                    'state' => 'especi_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'especialidades';
    protected $_PREF_TABLE= 'ESPEMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }

    public function get_list($params){
        

        $query = array();
        $sql=" SELECT * FROM  rh.especialidades ";


        if($params['nombre'] != ''){
            
            $sql.=" WHERE especi_nombre like ? ";
            $query[] = '%'.$params['nombre'].'%';   
        }
        
        $sql.= " ORDER BY especi_nombre  ";


        return  $this->_CI->db->query($sql, $query)->result_array();

    }
     
    
    
}
 