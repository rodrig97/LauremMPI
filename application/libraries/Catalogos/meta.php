<?php

class meta extends Table{
    
       
     
    protected $_FIELDS=array(   
                                   /* 'id'    => 'codi',
                                    'code'  => 'depar_key',
                                    'name'  => 'deta',
                                    'descripcion' => '',
                                    'state' => 'depar_estado'*/
                            );
    
    protected $_SCHEMA = 'pip';
    protected $_TABLE = 'meta';
    protected $_PREF_TABLE= 'MEMPITA'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    public function get_list($anio_eje = '')
    {
          
        $sql = "SELECT ano_eje,sec_ejec,sec_func,nombre 
                FROM pip.meta 
                WHERE nombre != ''
                ";

        $values = array();

        if(trim($anio_eje) != '')
        {
            $sql.=" AND ano_eje = ? ";
            $values[] = $anio_eje;
        }

        $sql.= ' ORDER BY nombre ';
      
        $rs =  $this->_CI->db->query($sql, $values )->result_array();
      
        return $rs;
        
    }

    public function existe($params)
    {
        $sql  = "SELECT * FROM pip.meta WHERE meta.ano_eje = ? AND meta.sec_func = ?  ";

        $rs = $this->_CI->db->query($sql, array($params['anio_eje'], $params['sec_func'] ))->result_array();

        return (sizeof($rs) > 0) ? true :false;
    }
    

    public function registrar($params= array() )
    {

        $sql =" INSERT INTO pip.meta(sec_ejec, ano_eje, sec_func, nombre ) VALUES(?,?, ?,?)";

         $ok = $this->_CI->db->query($sql, array($params['sec_ejec'], $params['anio_eje'], $params['sec_func'], $params['nombre'] ));

         return ($ok) ? true : false;
    }
    
    

    public function actualizar($params= array() )
    {

        $sql= " UPDATE sag.tarea SET sec_func = ?, tarea_nombre = ? WHERE tarea_id = ? ";

        $ok = $this->_CI->db->query($sql, array($params['sec_func'], $params['nombre'], $params['tarea_id'] ));


        $sql= " UPDATE pip.meta SET sec_func = ?, nombre = ? WHERE sec_func = ? AND ano_eje = ?";

        $ok = $this->_CI->db->query($sql, array($params['sec_func'], $params['nombre'], $params['sec_func_actual'], $params['ano_eje'] ));
        
        return true;
    }
}



?>
