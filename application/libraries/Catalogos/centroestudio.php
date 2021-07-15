<?php

class centroestudio extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'cees_id',
                                    'code'  => 'cees_key',
                                    'name'  => 'cees_nombre',
                                    'descripcion' => '',
                                    'state' => 'cees_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'centro_estudio';
    protected $_PREF_TABLE= 'centroestudiompi'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }

    public function get_list($params){

        $sql = " SELECT * FROM  rh.centro_estudio WHERE cees_estado = 1 ";
        $query = array();
        
        if($params['nombre'] != ''){
            $sql .= " AND cees_nombre like ? ";
            $query[]  = '%'.$params['nombre'].'%';
        }

        if($params['tipo_estudio'] != ''){
            $sql .= " AND  ( tiest_id = ? OR tiest_id = 0 ) ";
            $query[]  = $params['tipo_estudio'];
        }

        $sql .= " ORDER BY cees_nombre";

        return  $this->_CI->db->query($sql, $query)->result_array();

    }
    
    
    
}



?>
