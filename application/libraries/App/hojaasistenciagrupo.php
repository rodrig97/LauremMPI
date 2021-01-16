<?php

 
class hojaasistenciagrupo extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => 'hoagru_id',
                                    'code'  => 'hoagru_key',
                                    'name'  => 'hoagru_nombre',
                                    'descripcion' => '',
                                    'state' => 'hoagru_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojaasistencia_grupos';
    protected $_PREF_TABLE= 'hojagruposxx'; 
    
    
    public function __construct(){
           parent::__construct();
    }

    public function get_list()
    {

        $sql = "  SELECT * 
                  FROM planillas.hojaasistencia_grupos 
                  WHERE hoagru_estado = 1  ORDER BY hoagru_nombre ";
        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs;

    }
 
}