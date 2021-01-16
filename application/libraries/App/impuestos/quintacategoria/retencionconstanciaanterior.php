<?php

class retencionconstanciaanterior  extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => 'qcoa_id',
                                    'code'  => 'qcoa_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'qcoa_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'quintacategoria_constancias_anteriores';
    protected $_PREF_TABLE= 'QUINTARTCONS'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }

    public function get($params = array()){

        $sql = "SELECT qca.*, 
                       indiv.indiv_appaterno, 
                       indiv.indiv_apmaterno, 
                       indiv.indiv_nombres,  
                       indiv.indiv_dni

                FROM planillas.quintacategoria_constancias_anteriores qca 
                INNER JOIN public.individuo indiv ON qca.indiv_id = indiv.indiv_id 
                WHERE qcoa_estado = 1 ";

        $values = array();
                
        if($params['anio'] != ''){

            $sql.=" AND qca.anio = ?";
            $values[] = $params['anio'];
        
        }
                
        if($params['indiv_id'] != '' && $params['indiv_id'] != 0){

            $sql.=" AND qca.indiv_id = ?";
            $values[] = $params['indiv_id'];
        
        }


        $sql.=" ORDER BY anio,  indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres, indiv.indiv_dni ";

        $rs = $this->_CI->db->query($sql, $values )->result_array();

        return $rs;
    }

  
}