<?php

class planillaestado extends Table
{
    
    protected $_FIELDS=array(   
                                    'id'    => 'plaes_id',
                                    'code'  => 'plaes_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'plaes_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planilla_estados';
    protected $_PREF_TABLE= 'PLANIESTADO'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
    
    public function get_list()
    {
        
        $sql = "SELECT * FROM planillas.planilla_estados WHERE  plaes_estado =  1 AND plaes_id > 0 ORDER BY plaes_orden";
        return $this->_CI->db->query($sql)->result_array();
        
    }
     
}