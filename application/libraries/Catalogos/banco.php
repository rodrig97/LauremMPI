<?php

 
class banco extends Table{
     
   protected $_FIELDS=array(   
                                    'id'    => 'ebanco_id',
                                    'code'  => 'ebanco_key',
                                    'name'  => 'ebanco_nombre',
                                    'descripcion' => '',
                                    'state' => 'ebanco_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'entidades_bancarias';
    protected $_PREF_TABLE= 'BANCOMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    

    public function  get_list(){


         $sql =" SELECT  * FROM public.entidades_bancarias WHERE ebanco_estado = 1 AND ebanco_remuneracion = 1 ORDER BY ebanco_nombre";
         $rs = $this->_CI->db->query($sql, array())->result_array();

         return $rs;
    }
    
    
}
