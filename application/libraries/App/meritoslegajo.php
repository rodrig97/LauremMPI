<?php

class meritoslegajo  extends Table
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
        m.imeritosid
        ,m.ipersid
        ,m.ctipomerito
        ,m.cdocumentotipo
        ,m.cdocumentonro
        ,m.cdocumentofecha
        ,m.cmotivo
        ,m.documento
        ,m.nombarch

        FROM
        ficha.meritos as m
        
        WHERE
        m.ipersid ='" . $ipersid . "'
        and m.bhabilitado = 1
        ORDER BY m.imeritosid ASC

        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function listximeritos($imeritosid)
    {
            $data =  (
                "
                SELECT
                 m.imeritosid
                ,m.ipersid
                ,m.ctipomerito
                ,m.cdocumentotipo
                ,m.cdocumentonro
                ,m.cdocumentofecha
                ,m.cmotivo
                ,m.documento
                ,m.nombarch

                FROM
                ficha.meritos as m
                
                WHERE
                m.imeritosid ='" . $imeritosid . "'
                and m.bhabilitado = 1
                ORDER BY m.imeritosid ASC
                ");
            
                $rs =  $this->_CI->db->query($data)->result_array();
                return $rs;
        }
}
