<?php

class meritoslegajo  extends Table
{

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

    public function listximeritosid($imeritosid)
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

    public function store($ipersid,$ctipomerito,$cdocumentotipo,$cdocumentonro,$cdocumentofecha,$cmotivo)
    {
        $value1 = 1;
        $sql = "INSERT INTO ficha.meritos(ipersid,ctipomerito,cdocumentotipo,cdocumentonro,cdocumentofecha,cmotivo,iestado,bhabilitado ) 
        VALUES('".$ipersid."','".$ctipomerito."','".$cdocumentotipo."','".$cdocumentonro."','".$cdocumentofecha."','".$cmotivo."','".$value1."','".$value1."') ";
         $this->_CI->db->query($sql);
        $rs =  $this->_CI->db->insert_id();
        return $rs;
    }

    public function update($imeritosid,$ctipomerito,$cdocumentotipo,$cdocumentonro,$cdocumentofecha,$cmotivo)
    {
        $sql = "UPDATE ficha.meritos
        SET
        ctipomerito = '" .$ctipomerito. "'
        ,cdocumentotipo = '" .$cdocumentotipo. "'
        ,cdocumentonro= '" .$cdocumentonro. "'
        ,cdocumentofecha= '" .$cdocumentofecha. "'
        ,cmotivo = '" .$cmotivo. "'
        WHERE imeritosid = '" .$imeritosid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $imeritosid;
        return $rs;
    }
    public function delete($imeritosid)
    {
        $sql = "DELETE FROM ficha.meritos 
        WHERE imeritosid = '" .$imeritosid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $imeritosid;
        return $rs;
    }


}
