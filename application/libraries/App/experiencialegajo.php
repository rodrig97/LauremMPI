<?php

class experiencialegajo  extends Table
{
   
    protected $_FIELDS = array(
        'id'          => 'indiv_id',
        'code'        => 'indiv_key',
        'name'        => 'indiv_nombres',
        'descripcion' => '',
        'state'       => 'indiv_estado'
    );

    protected $_SCHEMA     = 'public';
    protected $_TABLE      = 'individuo';
    protected $_PREF_TABLE = 'PERSMPI';


    public function __construct()
    {

        parent::__construct();
    }

  
    public function get_ipersid($ipersid)
    {

        $sql = "
        Select 
        el.iexp_laboralid
        ,el.ipersid
        ,el.ipaisid
        ,el.idptoid
        ,el.iprovid
        ,el.idistid
        ,el.ccargos_desempenados
        ,el.dfechainicio
        ,el.dfechatermino
        ,el.lugar_laburo
        ,el.documento
        ,el.nombarch

        FROM
        ficha.exp_laboral as el
        WHERE
        el.ipersid ='" . $ipersid . "'
        and el.bhabilitado = 1
        ORDER BY el.iexp_laboralid ASC
      

        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function listxiexpLaboral($iexp_laboralid)
    {
            $data = (
                "
                SELECT
                el.iexp_laboralid
                ,el.ipersid
                ,el.ipaisid
                ,el.idptoid
                ,el.iprovid
                ,el.idistid
                ,el.ccargos_desempenados
                ,el.dfechainicio
                ,el.dfechatermino
                ,el.lugar_laburo
                ,el.documento
                ,el.nombarch

                FROM
                ficha.exp_laboral as el
                WHERE
                el.iexp_laboralid ='" . $iexp_laboralid . "'
                and el.bhabilitado = 1
                ORDER BY el.iexp_laboralid ASC
                "
            );
            $rs =  $this->_CI->db->query($data)->result_array();
        return $rs;
    }
  
    
}
