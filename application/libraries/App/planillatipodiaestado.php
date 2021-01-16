<?php

class planillatipodiaestado extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'platide_id',
                                    'code'  => 'platide_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'platide_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planillatipo_dia_estado';
    protected $_PREF_TABLE= 'ptdexx1'; 
    
    protected $sql_base =  "";
     
    public function __construct()
    {          
        parent::__construct();

      
    }

    public function actualizar( $plati_id, $dias = array() )
    {
    
         $this->_CI->db->trans_begin();

         if(sizeof($dias) === 7)
         {

                 foreach($dias as $dia)
                 {
                        
                        $sql = " SELECT * FROM planillas.planillatipo_dia_estado 
                                 WHERE plati_id = ?  AND dia_id = ? ";
                        list($rs) = $this->_CI->db->query($sql, array($plati_id, $dia['dia']))->result_array();    

                        if(sizeof($rs) === 0)
                        {
                            $sql =" INSERT INTO planillas.planillatipo_dia_estado(dia_id, plati_id, hatd_id, platide_laborable, platide_fecreg ) VALUES( ?, ?, ?, ?, now() ) ";
                            $this->_CI->db->query($sql, array($dia['dia'], $plati_id, $dia['hatd_id'], $dia['laborable'] ));

                        }
                        else
                        {
                             if(  trim($dia['hatd_id']) != trim($rs['hatd_id']) ||  trim($dia['laborable']) != trim($rs['platide_laborable']) )
                             {  
                                $sql = " UPDATE planillas.planillatipo_dia_estado 
                                          SET hatd_id = ?, platide_laborable = ?, platide_fecreg = now() 
                                          WHERE plati_id = ? AND dia_id = ? ";

                                $this->_CI->db->query($sql, array($dia['hatd_id'], $dia['laborable'], $plati_id, $dia['dia'] ));

                             }
                        }
          
                 }

                 if($this->_CI->db->trans_status() === FALSE) 
                 {
                     $this->_CI->db->trans_rollback();
                     return  false;
                 }
                 else
                 {
                     $this->_CI->db->trans_commit();
                     return true;
                 } 


         }
         else
         {
             return false;
         }
            

    }   


    public function actualizar_estado_trabajadores($plati_id , $params = array() )
    {

        $sql =" UPDATE planillas.individuo_dia_estado ide
                SET hatd_id = d.hatd_id, ide_laborable = d.platide_laborable
                FROM ( 
                    SELECT indiv_id
                    FROM public.individuo indiv
                    INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                    ORDER BY indiv_id 
                ) as indiv, (SELECT pde.dia_id, pde.hatd_id, platide_laborable FROM planillas.planillatipo_dia_estado pde WHERE plati_id = ? ) as d
                WHERE ide.indiv_id = indiv.indiv_id AND ide.dia_id = d.dia_id AND ide.ide_personalizado = 0
              ";

        $this->_CI->db->query($sql, array($plati_id,$plati_id));


    
        $sql ="  INSERT INTO planillas.individuo_dia_estado(indiv_id, dia_id, hatd_id, ide_laborable )  
                 SELECT data.indiv_id, data.dia_id, data.hatd_id, data.platide_laborable  
                 FROM
                 (
                    ( SELECT t1.indiv_id,
                           d.dia_id,
                           d.hatd_id,
                           d.platide_laborable

                    FROM (
                        SELECT indiv_id
                        FROM public.individuo indiv
                        INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                        ORDER BY indiv_id 

                    ) as t1, (SELECT pde.dia_id, pde.hatd_id, pde.platide_laborable FROM planillas.planillatipo_dia_estado pde WHERE plati_id = ? ) as d

                    ORDER BY t1.indiv_id,
                           d.dia_id,
                           hatd_id )
                 ) as data
                 LEFT JOIN  planillas.individuo_dia_estado de ON data.indiv_id = de.indiv_id AND data.dia_id = de.dia_id         
                 WHERE de.ide_id is null 
                 ORDER BY data.indiv_id, data.dia_id  ";

        $this->_CI->db->query($sql, array($plati_id,$plati_id));
                 


    }

}