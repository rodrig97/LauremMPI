<?php

class exportacion extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => '',
                                    'code'  => '',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => ''
                            );
    
    protected $_SCHEMA = '';
    protected $_TABLE = '';
    protected $_PREF_TABLE= ''; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }


    public function getReportes($tipo = 0){


        $sql =" SELECT  * FROM planillas.reportes WHERE reptip_id = ? order by rep_id";
        $rs =   $this->_CI->db->query($sql , array($tipo))->result_array(); 
        return $rs;
    }
    
}