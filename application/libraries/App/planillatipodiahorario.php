<?php

class planillatipodiahorario extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'platidh_id',
                                    'code'  => 'platidh_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'platidh_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planillatipo_dia_horario';
    protected $_PREF_TABLE= 'ptdhx1'; 
    
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
                        
                        $sql = " SELECT * FROM planillas.planillatipo_dia_horario 
                                 WHERE plati_id = ?  AND dia_id = ? ";
                        list($rs) = $this->_CI->db->query($sql, array($plati_id, $dia['dia']))->result_array();    

                        if(sizeof($rs) === 0)
                        {
                            $sql =" INSERT INTO planillas.planillatipo_dia_horario(dia_id, plati_id, hor_id, platidh_fecreg ) VALUES( ?, ?, ?, now() ) ";
                            $this->_CI->db->query($sql, array($dia['dia'], $plati_id, $dia['hor_id'] ));

                        }
                        else
                        {
                             if(  trim($dia['hor_id']) != trim($rs['hor_id']) )
                             {  
                                $sql = " UPDATE planillas.planillatipo_dia_horario 
                                          SET hor_id = ?, platidh_fecreg = now() 
                                          WHERE plati_id = ? AND dia_id = ? ";

                                $this->_CI->db->query($sql, array($dia['hor_id'], $plati_id, $dia['dia'] ));

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

    public function actualizar_horario_trabajadores($plati_id , $params = array() )
    {

        $sql =" UPDATE planillas.individuo_dia_horario idh
                SET hor_id = d.hor_id 
                FROM ( 
                    SELECT indiv_id
                    FROM public.individuo indiv
                    INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                    ORDER BY indiv_id 
                ) as indiv, (SELECT pdh.dia_id, pdh.hor_id FROM planillas.planillatipo_dia_horario pdh WHERE plati_id = ? ) as d
                WHERE idh.indiv_id = indiv.indiv_id AND idh.dia_id = d.dia_id AND idh.idh_personalizado = 0
              ";

        $this->_CI->db->query($sql, array($plati_id,$plati_id));


 
        $sql ="  INSERT INTO planillas.individuo_dia_horario(indiv_id, dia_id, hor_id )  
                 SELECT data.indiv_id, data.dia_id, data.hor_id 
                 FROM
                 (
                    ( SELECT t1.indiv_id,
                           d.dia_id,
                           d.hor_id 

                    FROM (
                        SELECT indiv_id
                        FROM public.individuo indiv
                        INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                        ORDER BY indiv_id 

                    ) as t1, (SELECT pdh.dia_id, pdh.hor_id FROM planillas.planillatipo_dia_horario pdh WHERE plati_id = ? ) as d

                    ORDER BY t1.indiv_id,
                           d.dia_id,
                           hor_id )
                 ) as data
                 LEFT JOIN  planillas.individuo_dia_horario dh ON data.indiv_id = dh.indiv_id AND data.dia_id = dh.dia_id         
                 WHERE dh.idh_id is null 
                 ORDER BY data.indiv_id, data.dia_id  ";

        $this->_CI->db->query($sql, array($plati_id,$plati_id));
                   
    }


    public function actualizar_horario_trabajador($plati_id, $indiv_id){


        $sql =" UPDATE planillas.individuo_dia_estado ide
               SET hatd_id = d.hatd_id, ide_laborable = d.platide_laborable
               FROM ( 
                   SELECT indiv_id
                   FROM public.individuo indiv
                   INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                   WHERE indiv.indiv_id = ? 
                   ORDER BY indiv_id 
               ) as indiv, (SELECT pde.dia_id, pde.hatd_id, platide_laborable FROM planillas.planillatipo_dia_estado pde WHERE plati_id = ? ) as d
               WHERE ide.indiv_id = indiv.indiv_id AND ide.dia_id = d.dia_id AND ide.ide_personalizado = 0
             ";

           $this->_CI->db->query($sql, array($plati_id, $indiv_id, $plati_id));


         
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
                           WHERE indiv.indiv_id = ? 
                           ORDER BY indiv_id 

                       ) as t1, (SELECT pde.dia_id, pde.hatd_id, pde.platide_laborable FROM planillas.planillatipo_dia_estado pde WHERE plati_id = ? ) as d

                       ORDER BY t1.indiv_id,
                              d.dia_id,
                              hatd_id )
                    ) as data
                    LEFT JOIN  planillas.individuo_dia_estado de ON data.indiv_id = de.indiv_id AND data.dia_id = de.dia_id         
                    WHERE de.ide_id is null 
                    ORDER BY data.indiv_id, data.dia_id  ";
          
          $this->_CI->db->query($sql, array($plati_id, $indiv_id, $plati_id));
         


         /* HORARIOOO */   

          $sql =" UPDATE planillas.individuo_dia_horario idh
                   SET hor_id = d.hor_id 
                   FROM ( 
                       SELECT indiv_id
                       FROM public.individuo indiv
                       INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                       WHERE indiv.indiv_id = ?
                       ORDER BY indiv_id 
                   ) as indiv, (SELECT pdh.dia_id, pdh.hor_id FROM planillas.planillatipo_dia_horario pdh WHERE plati_id = ? ) as d
                   WHERE idh.indiv_id = indiv.indiv_id AND idh.dia_id = d.dia_id AND idh.idh_personalizado = 0
                 ";

          $this->_CI->db->query($sql, array($plati_id, $indiv_id, $plati_id));
         

         
           $sql ="  INSERT INTO planillas.individuo_dia_horario(indiv_id, dia_id, hor_id )  
                    SELECT data.indiv_id, data.dia_id, data.hor_id 
                    FROM
                    (
                       ( SELECT t1.indiv_id,
                              d.dia_id,
                              d.hor_id 

                       FROM (
                           SELECT indiv_id
                           FROM public.individuo indiv
                           INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                           WHERE indiv.indiv_id = ?
                           ORDER BY indiv_id 

                       ) as t1, (SELECT pdh.dia_id, pdh.hor_id FROM planillas.planillatipo_dia_horario pdh WHERE plati_id = ? ) as d

                       ORDER BY t1.indiv_id,
                              d.dia_id,
                              hor_id )
                    ) as data
                    LEFT JOIN  planillas.individuo_dia_horario dh ON data.indiv_id = dh.indiv_id AND data.dia_id = dh.dia_id         
                    WHERE dh.idh_id is null 
                    ORDER BY data.indiv_id, data.dia_id  ";
             

          $this->_CI->db->query($sql, array($plati_id, $indiv_id, $plati_id));

    }

}