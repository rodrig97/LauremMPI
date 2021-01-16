<?php

class individuosunat extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'indsu_id',
                                    'code'  => 'indsu_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'indsu_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'individuo_sunat';
    protected $_PREF_TABLE= 'insu'; 
    
    
    public function __construct()
    {
        parent::__construct();
    }


    public function get( $indiv_id = 0 )
    {

        $sql = " SELECT * FROM planillas.individuo_sunat WHERE indsu_estado = 1 AND indiv_id = ?  LIMIT 1";
        list($rs) = $this->_CI->db->query($sql, array( $indiv_id ))->result_array(); 
        return $rs;
    }   


    public function registrar($indiv_id, $values = array())
    {

       $datos_actuales = $this->get($indiv_id);

       if($datos_actuales['indsu_id'] != '')
       {

           $cambios_encontrados =  false;

           foreach($values as $key => $value)
           { 

                if( trim($datos_actuales[$key]) != '')
                {
                     if(  trim($value)  != trim($datos_actuales[$key]) )
                     {  
                         $cambios_encontrados = true;
                     }   
                }
           }    

        }
        else
        {
            $cambios_encontrados = true;
        }

       $ok = true;

       if($cambios_encontrados)
       {

             // Actualizar el registro a cero
            $values['indiv_id'] = $indiv_id; 
            $sql =" UPDATE planillas.individuo_sunat 
                    SET indsu_estado = 0
                    WHERE indiv_id = ? ";
           
            $this->_CI->db->query($sql, array($indiv_id));
 
            $ok =  parent::registrar($values, false);
 
       }

       return $ok;
    }


}