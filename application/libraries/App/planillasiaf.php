<?php

class planillasiaf extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'plasiaf_id',
                                    'code'  => 'plasiaf_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'plasiaf_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planilla_siaf';
    protected $_PREF_TABLE= 'PLANILLA'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

    public function registrar($params,$b)
    {


        $sql =" UPDATE planillas.planilla_siaf SET plasiaf_estado = 0
                WHERE pla_id = ? AND fuente_id = ? AND  tipo_recurso = ?
              ";

        $rs = $this->_CI->db->query($sql, array($params['pla_id'], $params['fuente_id'], $params['tipo_recurso']));

        $sql= " SELECT plaemp_id 
                FROM planillas.planilla_empleados plaemp 
                WHERE plaemp.pla_id = ? AND plaemp_estado = 1 AND fuente_id = ? AND  tipo_recurso = ? ";

        $rs = $this->_CI->db->query($sql, array($params['pla_id'], $params['fuente_id'], $params['tipo_recurso']))->result_array();

        if(sizeof($rs) > 0)
        {   
            return parent::registrar($params, $b);
        }
        else
        {
            return ($b) ? array('1','1') : true;
        }

    }

}