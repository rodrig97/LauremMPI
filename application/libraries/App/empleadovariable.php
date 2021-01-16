<?php
 

class empleadovariable extends Table{
     
    
    protected $_FIELDS=array(   
                                    'id'    => 'empvar_id',
                                    'code'  => 'empvar_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'empvar_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'empleado_variable';
    protected $_PREF_TABLE= 'EMPVARIABLE'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
    
    
    public function get_list($empleado_id)
    {
         
        $params = array($empleado_id);
        
        $sql = "SELECT empvar.indiv_id as persona_id, vars.* 
                         FROM planillas.empleado_variable empvar 
                         LEFT JOIN planillas.variables vars ON empvar.vari_id = vars.vari_id 
                         WHERE empvar.empvar_estado = 1 AND empvar.indiv_id = ? ";
        
        return $this->_CI->db->query($sql, $params)->result_array();
        
    }
    
    

    public function registrar($data, $t = false)
    {
 
        //  $indiv_id, $vari_id, $valor
        
        $indiv_id = ($data['indiv_id'] == '') ? '0' : $data['indiv_id'];
        $vari_id  = ($data['vari_id'] == '') ? '0' : $data['vari_id'];
        $valor    =  ($data['empvar_value'] == '') ? '0' : $data['empvar_value'];

    
        $sql =" SELECT * FROM planillas.empleado_variable WHERE indiv_id = ?  AND vari_id = ?  AND empvar_estado = 1 ";
        $rs = $this->_CI->db->query($sql, array($indiv_id,$vari_id))->result_array();
    
        //var_dump($sql,$indiv_id, $vari_id, $valor);
        if( trim($rs[0]['empvar_id']) != '' ){
            
            $values =  array('empvar_value' => $valor);  

        //    if($data['empvar_displayprint'] != '' && $data['empvar_displayprint'] != '0' ) $values['empvar_displayprint'] = $opcion_impresion;

          //  var_dump($rs[0]['empvar_id']);  
            return parent::actualizar($rs[0]['empvar_id'],$values,false);
        }    
        else{
 
            if($data['empvar_displayprint'] == ''){
                 $sql = " SELECT vari_displayprint FROM planillas.variables WHERE vari_id = ? ";   
                 $rs  = $this->_CI->db->query($sql, array($vari_id))->result_array(); 
                 $opcion_impresion = $rs[0]['vari_displayprint'];
            }
            else{
                $opcion_impresion = $data['empvar_displayprint'];
            }


            $values = array('vari_id' => $vari_id, 'indiv_id' => $indiv_id, 'empvar_value' => $valor, 'empvar_displayprint' => $opcion_impresion  ); //'empvar_displayprint' => $opcion_impresion
            return parent::registrar($values);
        }

    }        


}