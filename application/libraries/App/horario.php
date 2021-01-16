<?php

class horario extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'hor_id',
                                    'code'  => 'hor_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'hor_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojaasistencia_horarios';
    protected $_PREF_TABLE= 'hxorariox'; 
    
    protected $sql_base =  "";
     
    public function __construct()
    {          
        parent::__construct();
       
    }

    public function get_list()
    {
        $sql = " SELECT *  FROM planillas.hojaasistencia_horarios WHERE hor_estado = 1 ORDER BY hor_id ";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs;
    }

    public function get($hor_id)
    {
       $sql = " SELECT * FROM  planillas.hojaasistencia_horarios WHERE hor_id = ? ";
       $rs = $this->_CI->db->query($sql, array($hor_id))->result_array();

       return $rs[0];
    }

}