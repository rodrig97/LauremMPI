<?php

class anioeje extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'ano_id',
                                    'code'  => '',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'ano_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'ano_eje';
    protected $_PREF_TABLE= 'anioxx'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

    public function get_list($params = array())
    {

        $sql = " SELECT * 
                 FROM public.ano_eje anio 
                 WHERE ano_estado = '1'  
                 ORDER BY ano_eje desc ";

        return $this->_CI->db->query($sql, array($params['usuario']) )->result_array();
    }
 

}