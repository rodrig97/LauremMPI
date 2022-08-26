<?php

class estudioslegajo  extends Table
{

    public function __construct()
    {

        parent::__construct();
    }


    public function get_ipersid($ipersid)
    {

        $sql = "
        Select 
        pte.iperstipoestudid
        ,pte.itipoestudid
        ,pte.ipersid
        ,pte.ccentroestudios
        ,pte.ipaisid
        ,pte.idptoid
        ,pte.iprovid
        ,pte.idistid
        ,pte.dfechainicio
        ,pte.dfechatermino
        ,pte.ccolegiaturanro
        ,pte.clugar
        ,pte.cgrado_titulo
        ,pte.documento
        ,pte.nombarch

        FROM
        ficha.personas_tipo_estudios as pte
        WHERE
        pte.ipersid ='" . $ipersid . "'
        and pte.bHabilitado = 1
        ORDER BY pte.iperstipoestudid ASC
        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }



    public function store($ipersid,$ccentroestudios,$dfechainicio,$dfechatermino,$cgrado_titulo,$ccolegiaturanro)
    {
        $sql = "INSERT INTO ficha.personas_tipo_estudios(ipersid,dfechatermino,dfechainicio,iestado,bhabilitado,ccolegiaturanro,cgrado_titulo,ccentroestudios ) VALUES('".$ipersid."','".$dfechatermino."','".$dfechainicio."',1,1,'".$ccolegiaturanro."','".$cgrado_titulo."','".$ccentroestudios."') ";
         $this->_CI->db->query($sql);
        $rs =  $this->_CI->db->insert_id();
        return $rs;
       
    }

    public function listxiperstipoestudid($iperstipoestudid)
    {

        $sql = "
        Select 
        pte.iperstipoestudid
        ,pte.itipoestudid
        ,pte.ipersid
        ,pte.ccentroestudios
        ,pte.ipaisid
        ,pte.idptoid
        ,pte.iprovid
        ,pte.idistid
        ,pte.dfechainicio
        ,pte.dfechatermino
        ,pte.ccolegiaturanro
        ,pte.clugar
        ,pte.cgrado_titulo
        ,pte.documento
        ,pte.nombarch

        FROM
        ficha.personas_tipo_estudios as pte
        WHERE
        pte.iperstipoestudid ='" . $iperstipoestudid . "'
        and pte.bHabilitado = 1
        ORDER BY pte.iperstipoestudid ASC
        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function update($iperstipoestudid,$ccentroestudios,$dfechainicio,$dfechatermino,$cgrado_titulo,$ccolegiaturanro)
    {
        $sql = "UPDATE ficha.personas_tipo_estudios 
        SET
        dfechatermino = '" .$dfechatermino. "'
        ,dfechainicio = '" .$dfechainicio. "'
        ,ccolegiaturanro= '" .$ccolegiaturanro. "'
        ,cgrado_titulo= '" .$cgrado_titulo. "'
        ,ccentroestudios = '" .$ccentroestudios. "'
        WHERE iperstipoestudid = '" .$iperstipoestudid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $iperstipoestudid;
        return $rs;
       
    }
    public function delete($iperstipoestudid)
    {
        $sql = "DELETE FROM ficha.personas_tipo_estudios 
        WHERE iperstipoestudid = '" .$iperstipoestudid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $iperstipoestudid;
        return $rs;
       
    }

}
