<?php

class demeritoslegajo  extends Table
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
        d.idemeritosid
        ,d.ipersid
        ,d.sancion
        ,d.cdocumentoresolucion
        ,d.cdocumentonro
        ,d.cfecha_ini
        ,d.cfecha_fin
        ,d.documento
        ,d.nombarch

        FROM
        ficha.demeritos as d
        
        WHERE
        d.ipersid ='" .$ipersid . "'
        and d.bhabilitado = 1
        ORDER BY d.idemeritosid ASC
      

        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function listxidemeritos($idemeritosid)
    {
            $data =  (
                "
                SELECT
                 d.idemeritosid
                ,d.ipersid
                ,d.sancion
                ,d.cdocumentoresolucion
                ,d.cdocumentonro
                ,d.cfecha_ini
                ,d.cfecha_fin
                ,d.documento
                ,d.nombarch

                FROM
                ficha.demeritos as d
                
                WHERE
                d.idemeritosid ='" . $idemeritosid . "'
                and d.bhabilitado = 1
                ORDER BY d.idemeritosid ASC
                ");

                $rs =  $this->_CI->db->query($data)->result_array();
                return $rs;
    }
    
}
