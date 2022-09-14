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

    public function listxidemeritosid($idemeritosid)
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

    public function store($ipersid,$sancion,$cdocumentoresolucion,$cdocumentonro,$cfecha_ini,$cfecha_fin)
    {
        $value1 = 1;
        $sql = "INSERT INTO ficha.demeritos(ipersid,sancion,cdocumentoresolucion,cdocumentonro,cfecha_ini,cfecha_fin,iestado,bhabilitado ) 
        VALUES('".$ipersid."','".$sancion."','".$cdocumentoresolucion."','".$cdocumentonro."','".$cfecha_ini."','".$cfecha_fin."','".$value1."','".$value1."') ";
         $this->_CI->db->query($sql);
        $rs =  $this->_CI->db->insert_id();
        return $rs;
    }

    public function update($idemeritosid,$sancion,$cdocumentoresolucion,$cdocumentonro,$cfecha_ini,$cfecha_fin)
    {
        $sql = "UPDATE ficha.demeritos
        SET
        sancion = '" .$sancion. "'
        ,cdocumentoresolucion = '" .$cdocumentoresolucion. "'
        ,cdocumentonro= '" .$cdocumentonro. "'
        ,cfecha_ini= '" .$cfecha_ini. "'
        ,cfecha_fin = '" .$cfecha_fin. "'
        WHERE idemeritosid = '" .$idemeritosid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $idemeritosid;
        return $rs;
    }
    public function delete($idemeritosid)
    {
        $sql = "DELETE FROM ficha.demeritos 
        WHERE idemeritosid = '" .$idemeritosid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $idemeritosid;
        return $rs;
    }

    
}
