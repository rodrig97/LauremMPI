<?php
 
class individuo extends Table{
     
   protected $_FIELDS=array(   
                                    'id'    => 'indiv_id',
                                    'code'  => 'indiv_key',
                                    'name'  => 'indiv_nombre',
                                    'descripcion' => '',
                                    'state' => 'indiv_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'individuo';
    protected $_PREF_TABLE= 'individuo'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
     
}
