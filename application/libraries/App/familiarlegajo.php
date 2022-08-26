<?php

class familiarlegajo  extends Table
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
        cf.icarga_familiarid
        ,cf.ipersid
        ,cf.cape_nom_conyug
        ,cf.ccel_conyug
        ,cf.cape_nom_hijos
        ,cf.cfechanac_hijos
        ,cf.ape_nom_padre
        ,cf.cel_padre
        ,cf.ape_nom_madre
        ,cf.cel_madre
        ,cf.documento
        ,cf.nombarch

        FROM
        ficha.carga_familiar as cf
        
        WHERE
        cf.ipersid = $ipersid 
        and cf.bhabilitado = 1
        ORDER BY cf.icarga_familiarid ASC

        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function listxicargaFam($icarga_familiarid)
    {
            $data = (
                "
                SELECT
                 cf.icarga_familiarid
                ,cf.ipersid
                ,cf.cape_nom_conyug
                ,cf.ccel_conyug
                ,cf.cape_nom_hijos
                ,cf.cfechanac_hijos
                ,cf.ape_nom_padre
                ,cf.cel_padre
                ,cf.ape_nom_madre
                ,cf.cel_madre
                ,cf.documento
                ,cf.nombarch

                FROM
                ficha.carga_familiar as cf
                
                WHERE
                cf.icarga_familiarid ='" . $icarga_familiarid . "'
                and cf.bhabilitado = 1
                ORDER BY cf.icarga_familiarid ASC
                ");
                $rs =  $this->_CI->db->query($data)->result_array();
                return $rs;
    }
    
}
