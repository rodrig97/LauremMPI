<?php

class planillaempleadovariable extends Table{
     
    
    protected $_FIELDS=array(   
                                    'id'    => 'plaev_id',
                                    'code'  => 'plaev_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'plaev_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planilla_empleado_variable';
    protected $_PREF_TABLE= 'PLANILLAEV'; 
    
    
    public function __construct()
    {
       parent::__construct();
    }
    
    public function actualizar_valor($id, $valor, $is_codigo = false, $procedencia = PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO, $acumulado = false )
    {
         
        $extra =  ($acumulado == true) ? " plaev_valor + " : "";     

        $sql = " UPDATE planillas.planilla_empleado_variable  SET plaev_valor = ".$extra." ?, plaev_procedencia = ? WHERE  ";
        
        $sql.= ($is_codigo) ? " plaev_key = ? " : " plaev_id = ? ";
            
        $valor = round($valor,2);

 
        $this->_CI->db->query($sql, array( $valor , $procedencia, $id) );
        
        $ok = ( $this->_CI->db->affected_rows() > 0 ) ? true : false;

      
        return $ok;
        
    }


    public function set_valor($plaemp_id, $vari_id, $valor)
    {

       
            
        $sql =" UPDATE planillas.planilla_empleado_variable SET plaev_valor = ?, plaev_procedencia = ?    WHERE plaemp_id = ? AND vari_id = ?  ";
        
    
        $this->_CI->db->query($sql, array( round($valor,2), PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO, $plaemp_id, $vari_id));
 

        return ($this->_CI->db->affected_rows()>0) ? true : false;

    } 
    

    public function registrar($values, $return_id = false, $static_data = array(), $acumulado = false  )
    {   

        $values['plaev_valor'] = round($values['plaev_valor'], 2);
 
 
        $sql = " SELECT * FROM planillas.planilla_empleado_variable WHERE plaemp_id = ? AND vari_id = ? AND plaev_estado = 1 ";
        $rs  = $this->_CI->db->query($sql, array($values['plaemp_id'], $values['vari_id'] ) )->result_array();
 

        if( sizeof($rs) == 0 )
        { 

            if($static_data['vtd_id'] != '0' && $static_data['vtd_id'] != '' )
            {   
                  
                 $vtd_id    =  ($static_data['vtd_id'] != '' )  ? $static_data['vtd_id'] : '0';  
                 $yvalue    =  $static_data['y_values'][ trim($static_data['y_value_key']) ];
                 
                 if($yvalue == '') $yvalue = '0';

                 if($yvalue != '0')
                 {
                
                     $sql = "   SELECT vsv_value 
                                FROM   planillas.variables_staticvalues 
                                WHERE  vsv_estado = 1 AND  vari_id = ?  AND vtd_id = ? AND  y_value = ?  
                                LIMIT  1 ";
                
                     $rs = $this->_CI->db->query($sql, array( $values['vari_id'], $vtd_id, $yvalue ) )->result_array();
                
                     $values['plaev_valor'] = $rs[0]['vsv_value']; 
                }
            } 
 
            if($values['plaev_valor'] == '') $values['plaev_valor'] = 0;
             
            return parent::registrar($values, $return_id);
        }
        else
        {        

            
            $reg = $rs[0];
            
             if($values['plaev_valor'] == '') $values['plaev_valor'] = 0;
            
            $values['plaev_procedencia'] = ($values['plaev_procedencia']=='') ? '0' : $values['plaev_procedencia'];
 
            $this->actualizar_valor( $reg['plaev_id'],  $values['plaev_valor'], false,  $values['plaev_procedencia'], $acumulado  );
 

            if($return_id)
            {
                     
                return array($reg['plaev_id'], $reg['plaev_key']);
            }
            else
            {
                return true;
            }

        }

    }


    public function registro_directo($values, $return_id = false, $static_data = array()  )
    {

        if( trim($static_data['vtd_id']) != '0' && trim($static_data['vtd_id']) != '' )
        {   
                 
                 $vtd_id    =  ($static_data['vtd_id'] != '' )  ? $static_data['vtd_id'] : '0';  
                 $yvalue    =  $static_data['y_values'][ trim($static_data['y_value_key']) ];
                 
                if($yvalue == '') $yvalue = '0';

                if($yvalue != '0')
                {
     
                 $sql = "   SELECT vsv_value 
                            FROM   planillas.variables_staticvalues 
                            WHERE  vsv_estado = 1 AND  vari_id = ?  AND vtd_id = ? AND  y_value = ?  
                            LIMIT  1 ";
            
                 $rs = $this->_CI->db->query($sql, array( $values['vari_id'], $vtd_id, $yvalue ) )->result_array();
            
                 $values['plaev_valor'] = $rs[0]['vsv_value']; 

                }
        } 

        if($values['plaev_valor'] == '') $values['plaev_valor'] = 0;

        return parent::registrar($values, $return_id);
    }


}