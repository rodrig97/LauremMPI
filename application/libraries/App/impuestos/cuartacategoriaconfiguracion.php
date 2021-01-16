<?php

class cuartacategoriaconfiguracion  extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => 'cuartaconf_id',
                                    'code'  => 'cuartaconf_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'cuartaconf_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'cuartacategoria_configuracion';
    protected $_PREF_TABLE= 'cuartconfig'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }

    public function get($plati_id){

        $sql = " SELECT conf.*, 
                     vari.vari_id as variable_id, vari.vari_nombre as variable_importar, 
                     conc.conc_id as concepto_id, conc.conc_nombre as concepto_importar
                 FROM planillas.cuartacategoria_configuracion conf
                 LEFT JOIN planillas.variables vari ON conf.cuarta_vari = vari.vari_id AND conf.plati_id = vari.plati_id AND vari_estado = 1 
                 LEFT JOIN planillas.conceptos conc ON conf.cuarta_conc = conc.conc_id AND conf.plati_id = conc.plati_id AND conc_estado = 1
                 WHERE conf.plati_id = ? AND cuartaconf_estado = 1";

        list($rs) = $this->_CI->db->query($sql, array($plati_id))->result_array();

        return $rs;
    }

}