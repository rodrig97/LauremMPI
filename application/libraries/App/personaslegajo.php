<?php

class personaslegajo  extends Table
{
   
    public function __construct()
    {

        parent::__construct();
    }

   

    public function get_pers_id($indiv_id)
    {   

        $sql = "
        Select 
         fp.ipersid
        ,fp.igruposangid
        ,fp.itiposegid
        ,fp.cnroseg
        ,fp.ingr_por
        ,fp.reposicion
        ,fp.acta_repos
        ,fp.cond_labor
        ,fp.mediante1
        ,fp.de_fecha
        ,fp.cargo_nombro
        ,fp.unidad_org_nomb
        ,fp.dependiente
        ,fp.regimen_lab
        ,fp.apartirdel
        ,fp.rotadoa
        ,fp.mediante2
        ,fp.profesion
        ,fp.dirigidoa
        ,fp.fecha_ingr
        ,fp.fecha_nomb
        ,fp.nro_resol
        ,fp.fecha_resol
        ,fp.nro_contr
        ,fp.creferencia
        ,fp.nro_hijos
        ,fp.ape_nom_cony
        ,fp.fam_instit
        ,fp.comentario
        ,fp.area
        ,fp.documento
        ,fp.cestado
        ,fp.nombarch
        ,fp.indiv_id

        ,ind.indiv_appaterno 
        ,ind.indiv_apmaterno 
        ,ind.indiv_nombres 
        ,ind.indiv_dni
        ,ind.indiv_celular
        ,ind.indiv_email
        ,ind.indiv_key
        
        FROM ficha.personas as fp
        INNER JOIN public.individuo as ind ON ind.indiv_id = fp.indiv_id
        INNER JOIN public.persona as pers ON pers.indiv_id = ind.indiv_id

        WHERE ind.indiv_id = $indiv_id
        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;

    }
    public function insert_pers_id($indiv_id)
    {   
        $sql = "
        INSERT INTO ficha.personas
        (indiv_id) 
        VALUES
        ($indiv_id)
        
        ";
        $rs =  $this->_CI->db->query($sql);
      
        return $rs;
    }

    public function listGrupoSanguineo()
    {

        $sql = "
        Select 
        igruposangid
        ,cgruposangdsc
 
        FROM
        ficha.grupo_sanguineo
        
        WHERE
        bHabilitado = 1
        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function listTipoSeguro()
    {

        $sql = "
        Select 
        itiposegid
        ,ctiposegdsc
 
        FROM
        ficha.tipo_seguro
        
        WHERE
        bHabilitado = 1
        
        ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
    }

    public function update($ipersid,$igruposangid,$itiposegid,$cnroseg,$dirigidoa,$area,$creferencia,$reposicion,$ingr_por,$acta_repos,$cond_labor,$mediante1,$de_fecha,$cargo_nombro,$unidad_org_nomb,$dependiente,$regimen_lab,$apartirdel,$rotadoa,$mediante2,$profesion,$comentario,$cpersruc,$cpersdireccion,$dfechanac,$cperslibreta,$cperslicencia)
    {
        $sql = "UPDATE ficha.personas
        SET
        igruposangid = '" .$igruposangid. "'
        ,itiposegid = '" .$itiposegid. "'
        ,cnroseg= '" .$cnroseg. "'
        ,dirigidoa = '" .$dirigidoa. "'
        ,area = '" .$area. "'
        ,creferencia = '" .$creferencia. "'
        ,reposicion = '" .$reposicion. "'
        ,ingr_por = '" .$ingr_por. "'
        ,acta_repos = '" .$acta_repos. "'
        ,cond_labor = '" .$cond_labor. "'
        ,mediante1 = '" .$mediante1. "'
        ,de_fecha = '" .$de_fecha. "'
        ,cargo_nombro = '" .$cargo_nombro. "'
        ,unidad_org_nomb = '" .$unidad_org_nomb. "'
        ,dependiente = '" .$dependiente. "'
        ,regimen_lab = '" .$regimen_lab. "'
        ,apartirdel = '" .$apartirdel. "'
        ,rotadoa = '" .$rotadoa. "'
        ,mediante2 = '" .$mediante2. "'
        ,profesion = '" .$profesion. "'
        ,comentario = '" .$comentario. "'


        WHERE ipersid = '" .$ipersid. "'
         ";
        $this->_CI->db->query($sql);

        $rs =  $ipersid;
        return $rs;
    }
    
}
