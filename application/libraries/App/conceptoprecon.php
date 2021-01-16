<?php
 
class conceptoprecon extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'copc_id',
                                    'code'  => 'copc_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'copc_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'conceptos_presu_cont';
    protected $_PREF_TABLE= 'CONPRECO'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
    
    
    public function registrar($values){
        
         $concepto_id = $values['conc_id'];
         $partida_id = $values['id_clasificador'];
         $cuentadebe = $values['cuentadebe_id'];
         $cuentahaber = $values['cuentahaber_id'];
         
         $anio_id = $values['ano_eje'];
         $sql =  " SELECT copc_id 
                   FROM planillas.conceptos_presu_cont 
                   WHERE  ano_eje = ? AND 
                          id_clasificador = ? AND  conc_id = ?  AND cuentadebe_id = ? AND cuentahaber_id = ? AND  copc_estado = 1 ";

         $rs = $this->_CI->db->query($sql, array($anio_id, $partida_id, $concepto_id, $cuentadebe, $cuentahaber))->result_array();
        
         if($rs[0]['copc_id'] == ''){
         
             $sql = " UPDATE planillas.conceptos_presu_cont SET copc_estado = 0 WHERE  conc_id = ? ";
             $this->_CI->db->query($sql,$concepto_id);
            return parent::registrar($values);
         }
         else{
             return true;
         }
         
    }


    public function desactivar($conc_id){

        $sql = " UPDATE planillas.conceptos_presu_cont SET copc_estado = 0 WHERE conc_id = ?   ";        
        $ok = $this->_CI->db->query($sql, array($conc_id));

        return ($ok) ?  true : false;

    }
     
}