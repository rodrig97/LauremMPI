<?php
 
class estadistica extends Table{
    
    protected $_FIELDS=array();
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojaasistencia';
    protected $_PREF_TABLE= 'hojaasistencia'; 
     
    public function __construct()
    {
          
        parent::__construct();
          
    }

}