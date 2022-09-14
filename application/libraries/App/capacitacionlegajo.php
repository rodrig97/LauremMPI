<?php

class capacitacionlegajo  extends Table
{

   public function __construct()
    {

        parent::__construct();
    }


    public function get_ipersid($ipersid)
    {

        $sql = "
        Select 
        ptc.iperstipocapacid
        ,ptc.itipocapacid
        ,ptc.ipersid
        ,ptc.ccentroestudios
        ,ptc.ipaisid
        ,ptc.idptoid
        ,ptc.iprovid
        ,ptc.idistid
        ,ptc.cdenominacion
        ,ptc.ihoras
        ,ptc.dfechainicio
        ,ptc.dfechatermino
        ,ptc.clugar_cap
        ,ptc.documento
        ,ptc.nombarch
        
        
        FROM
        ficha.personas_tipo_capacitacion as ptc
        
        WHERE
        ptc.ipersid ='" . $ipersid . "'
        and ptc.bHabilitado = 1
        ORDER BY ptc.iperstipocapacid ASC
      

        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function listxiperstipocapac($iperstipocapacid){
        $data =  (
            "
            SELECT
             ptc.iperstipocapacid
            ,ptc.itipocapacid
            ,ptc.ipersid
            ,ptc.ccentroestudios
            ,ptc.ipaisid
            ,ptc.idptoid
            ,ptc.iprovid
            ,ptc.idistid
            ,ptc.cdenominacion
            ,ptc.ihoras
            ,ptc.dfechainicio
            ,ptc.dfechatermino
            ,ptc.clugar_cap
            ,ptc.documento
            ,ptc.nombarch
           
            
            FROM
            ficha.personas_tipo_capacitacion as ptc
            
            WHERE
            ptc.iperstipocapacid ='" . $iperstipocapacid . "'
            and ptc.bHabilitado = 1
            ORDER BY ptc.iperstipocapacid ASC
            "
        );
        $rs =  $this->_CI->db->query($data)->result_array();
        return $rs;
    }

    public function listTipoCapac($ipersid)
    {

        $sql = "
        Select 
         itipocapacid
        ,ctipocapacdsc
 
        FROM
        ficha.tipo_capacitacion
        
        WHERE
        bHabilitado = 1
        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function store($ipersid, $itipocapacid, $cdenominacion,$ihoras, $dfechainicio, $dfechatermino, $ccentroestudios)
    {
        $sql = "INSERT INTO ficha.personas_tipo_capacitacion (ipersid,itipocapacid,cdenominacion,ihoras,dfechainicio,dfechatermino,iestado,bhabilitado,ccentroestudios ) 
        VALUES($ipersid,$itipocapacid,'".$cdenominacion."',$ihoras,'".$dfechainicio."','".$dfechatermino."',1,1,'".$ccentroestudios."')";
         $this->_CI->db->query($sql);
        $rs =  $this->_CI->db->insert_id();
        return $rs;
       
    }

    public function update($iperstipocapacid,$itipocapacid, $cdenominacion,$ihoras, $dfechainicio, $dfechatermino, $ccentroestudios)
    {
        $sql = "UPDATE ficha.personas_tipo_capacitacion 
        SET
        itipocapacid = '" .$itipocapacid. "'
        ,cdenominacion= '" .$cdenominacion. "'
        ,ihoras= '" .$ihoras. "'
        ,dfechatermino = '" .$dfechatermino. "'
        ,dfechainicio = '" .$dfechainicio. "'
        ,ccentroestudios = '" .$ccentroestudios. "'
        WHERE iperstipocapacid = '" .$iperstipocapacid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $iperstipocapacid;
        return $rs;
    }
    public function delete($iperstipocapacid)
    {
        $sql = "DELETE FROM ficha.personas_tipo_capacitacion 
        WHERE iperstipocapacid = '" .$iperstipocapacid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $iperstipocapacid;
        return $rs;
       
    }
}
