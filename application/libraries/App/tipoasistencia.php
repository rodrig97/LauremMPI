<?php

 

class tipoasistencia extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => 'hoae_id',
                                    'code'  => 'hoae_key',
                                    'name'  => 'hoae_nombre',
                                    'descripcion' => 'hoae_descripcion',
                                    'state' => 'hoae_estado'
                            );
    
    protected $_SCHEMA     = 'planillas';
    protected $_TABLE      = 'hoa_estados';
    protected $_PREF_TABLE = 'hojaest'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

    public function get_list(){

        $sql =" SELECT * FROM  planillas.hoa_estados WHERE hoae_estado =  1 AND hoae_id != 0 ORDER BY hoae_id  ";

        $rs = $this->_CI->db->query($sql)->result_array();

        return $rs;


    }
    

}