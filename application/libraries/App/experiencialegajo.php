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

    public function store($ipersid,$ccargos_desempenados,$dfechainicio,$dfechatermino,$lugar_laburo)
    {
        $value1 = 1;
        $sql = "INSERT INTO ficha.exp_laboral(ipersid,ccargos_desempenados,dfechainicio,dfechatermino,lugar_laburo,iestado,bhabilitado ) 
        VALUES('".$ipersid."','".$ccargos_desempenados."','".$dfechainicio."','".$dfechatermino."','".$lugar_laburo."','".$value1."','".$value1."') ";
         $this->_CI->db->query($sql);
        $rs =  $this->_CI->db->insert_id();
        return $rs;
    }

    public function update($iexp_laboralid,$ccargos_desempenados,$dfechainicio,$dfechatermino,$lugar_laburo)
    {
        $sql = "UPDATE ficha.exp_laboral
        SET
        ccargos_desempenados = '" .$ccargos_desempenados. "'
        ,dfechainicio = '" .$dfechainicio. "'
        ,dfechatermino= '" .$dfechatermino. "'
        ,lugar_laburo= '" .$lugar_laburo. "'
       
        WHERE iexp_laboralid = '" .$iexp_laboralid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $iexp_laboralid;
        return $rs;
    }
    public function delete($iexp_laboralid)
    {
        $sql = "DELETE FROM ficha.exp_laboral 
        WHERE iexp_laboralid = '" .$iexp_laboralid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $iexp_laboralid;
        return $rs;
    }
  
    
}
