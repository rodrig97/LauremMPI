<?php
 
class planillamovimiento extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'plamo_id',
                                    'code'  => 'plamo_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'plamo_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planilla_movimiento';
    protected $_PREF_TABLE= 'PLANILLAMOVI'; 
    
    
   // private $_PLANILLA_ESTADOS = array('ANULADA' => '0','ELABORADA' => '1','PROCESADA' => '2', 'FINALIZADA' => '3' );
    
    public function __construct(){
          
        parent::__construct();
          
    }
    
    public function registrar($values = array(), $return_id = false){
         
        if(trim($values['pla_id']) != ''){
              $sql = " UPDATE planillas.planilla_movimiento SET plamo_estado = 0 WHERE pla_id = ? ";
              $this->_CI->db->query($sql,array($values['pla_id']));
        }
        return   parent::registrar($values, $return_id);
    }
     
}