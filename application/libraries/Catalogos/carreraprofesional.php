<?php

class carreraprofesional extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'carpro_id',
                                    'code'  => 'carpro_key',
                                    'name'  => 'carpro_nombre',
                                    'descripcion' => '',
                                    'state' => 'carpro_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'carreras_profesionales';
    protected $_PREF_TABLE= 'CARRERA'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }

    public function get_list($params){
        
        $sql = " SELECT DISTINCT c.* FROM  rh.carreras_profesionales c LEFT JOIN rh.centro_estudio_carreras ce on c.carpro_id = ce.carpro_id WHERE carpro_estado = 1 ";

        $query = array();

        if($params['nombre'] != ''){ 
            $sql .= " AND carpro_nombre like ? ";
            $query[]  = '%'.$params['nombre'].'%';
        }

        if($params['centro_estudios'] != ''){ 
            $sql .= " AND cees_id = ? ";
            $query[]  = $params['centro_estudios'];
        }

        $sql .= " ORDER BY carpro_nombre";
        return  $this->_CI->db->query($sql, $query)->result_array();

    }
     
    
    
}
 