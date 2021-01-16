<?php

 
class academico extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'perac_id',
                                    'code'  => 'perac_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'perac_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_academico';
    protected $_PREF_TABLE= 'perest'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    public function get_academico($pers, $ex = array() )
    {
            
         $sql = 'SELECT peac.*, carr.carpro_nombre, espe.especi_nombre, cest.cees_nombre, tiest.tiest_nombre, 
                        et.acaet_nombre as estado_titulo, mo.acamod_nombre as modalidad , sit.acasitu_nombre as situacion
                 FROM rh.persona_academico peac
				 LEFT JOIN rh.tipo_estudio tiest ON peac.tiest_id = tiest.tiest_id
                 LEFT JOIN rh.carreras_profesionales carr ON peac.carpro_id = carr.carpro_id 
                 LEFT JOIN rh.especialidades espe ON peac.especi_id = espe.especi_id
                 LEFT JOIN rh.centro_estudio cest ON cest.cees_id = peac.cees_id
                 LEFT JOIN rh.academico_etitulo et ON peac.perac_estadotitulo   =  et.acaet_id 
                 LEFT JOIN rh.academico_modalidad mo ON peac.perac_modalidad    = mo.acamod_id
                 LEFT JOIN rh.academico_situacion sit ON peac.perac_situacion  = sit.acasitu_id 


                WHERE pers_id = ?  and perac_estado = 1 ORDER BY tiest_orden desc,perac_fecfin desc   ';
         
         $rs =  $this->_CI->db->query($sql, array($pers))->result_array();
         
         return $rs;
         
    }
    
    
    public function view($id){
        
        $sql = ' SELECT peac.*, carr.carpro_nombre, 
                               espe.especi_nombre,
                               cest.cees_nombre,
                               tiest.tiest_nombre as tipo_estudio,
                             
                               perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni,
                               et.acaet_nombre as estado_titulo, mo.acamod_nombre as modalidad , sit.acasitu_nombre as situacion

                        FROM rh.persona_academico peac
                        LEFT JOIN rh.tipo_estudio tiest ON peac.tiest_id = tiest.tiest_id
                        LEFT JOIN "public".individuo perso ON peac.pers_id = perso.indiv_id    
                        LEFT JOIN rh.carreras_profesionales carr ON peac.carpro_id = carr.carpro_id 
                        LEFT JOIN rh.especialidades espe ON peac.especi_id = espe.especi_id
                        LEFT JOIN rh.centro_estudio cest ON cest.cees_id = peac.cees_id
                        LEFT JOIN rh.academico_etitulo et ON peac.perac_estadotitulo   =  et.acaet_id 
                        LEFT JOIN rh.academico_modalidad mo ON peac.perac_modalidad    = mo.acamod_id
                        LEFT JOIN rh.academico_situacion sit ON peac.perac_situacion  = sit.acasitu_id 

                WHERE perac_id = ?  LIMIT 1  ';
         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
        
        
    }
    
    
}
