<?php

class distrito extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'distri_id',
                                    'code'  => 'distri_key',
                                    'name'  => 'distri_nombre',
                                    'descripcion' => '',
                                    'state' => 'distri_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'distrito';
    protected $_PREF_TABLE= 'DISTRIMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
      
    
    
}



?>
