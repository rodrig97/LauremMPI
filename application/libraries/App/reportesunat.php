<?php

class reportesunat extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => '',
                                    'code'  => '',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => ''
                            );
    
    protected $_SCHEMA = '';
    protected $_TABLE = '';
    protected $_PREF_TABLE= ''; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }


    public function multiple_regimen($params= array())
    {

          $sql ="  SELECT 
                    indiv.indiv_dni,
                    indiv.indiv_appaterno,
                    indiv.indiv_apmaterno, 
                    plati.plati_nombre,
                    (indiv.indiv_appaterno || ' ' ||  indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador
                  FROM (
                    SELECT * FROM (

                       SELECT  plaemp.indiv_id, count(distinct(persla.plati_id)) as c_regimen
                       FROM planillas.planilla_empleados plaemp 
                       INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ? 
                       INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT." 
                       INNER JOIN rh.persona_situlaboral persla ON plaemp.persla_id = persla.persla_id 
                       INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1
                       INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.cosu_id != 0
                       GROUP BY plaemp.indiv_id 

                    ) as h
                    WHERE h.c_regimen > 1

                  ) 
                  as t
                  INNER JOIN (


                   SELECT distinct(plaemp.indiv_id) as indiv_id, persla.plati_id 
                   FROM planillas.planilla_empleados plaemp 
                   INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                   INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT." 
                   INNER JOIN rh.persona_situlaboral persla ON plaemp.persla_id = persla.persla_id 
                   INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1
                   INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.cosu_id != 0
                   ORDER BY  plaemp.indiv_id, persla.plati_id 

                  ) as d ON t.indiv_id = d.indiv_id 
                  INNER JOIN public.individuo indiv ON t.indiv_id = indiv.indiv_id 
                  INNER JOIN planillas.planilla_tipo plati On d.plati_id = plati.plati_id ";

         $rs = $this->_CI->db->query($sql, array($params['anio'], $params['mes'], $params['anio'], $params['mes'] ))->result_array();

         return $rs;
    }
   

    public function historico_ingresos_mes($params = array() )
    { 
        $meses = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

        $params['anio_anterior'] = ($params['anio'] * 1) - 1;
        $params['anio_anterior'] = trim($params['anio_anterior']);

        if($params['mes'] == '01')
        {

            $params['mes2'] = '12';

        }
        else
        {

          for($i = 0; $i<= 11; $i++)
          { 
              if( $params['mes'] == $meses[$i] )
              {
                 break;
              } 
              else
              {
                 $params['mes2'] = $meses[$i];
              }

          }

        }

        $sql_meseje ="   SELECT  * FROM 
                     ( VALUES (''".$params['anio_anterior']."_12'') ";
        
        foreach ($meses as $mes)
        {
            
            $sql_meseje.= ",(''".$params['anio']."_".$mes."'')";

        }

        $sql_meseje.=" ) as mes_eje"; 


        $sql_base = "   SELECT * FROM crosstab(' 

                          SELECT  (plaemp.indiv_id || ''-''|| persla.plati_id) as indice, 
                                   plaemp.indiv_id, 
                                   persla.plati_id, 
                                   (pla.pla_anio || ''_'' || pla.pla_mes) as mes, 
                                   COALESCE(SUM(plaec_value),0) as total 
                          FROM planillas.planilla_empleados plaemp 
                          INNER JOIN rh.persona_situlaboral persla ON plaemp.persla_id = persla.persla_id 
                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1
                          INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT."
                          INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_tipo = 1 AND plaec.conc_afecto = 1 
                          INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.cosu_id != 0  
                          WHERE plaec.plaec_value > 0  AND ( ( pla.pla_anio = ''".$params['anio']."'') OR ( pla.pla_anio = ''".$params['anio_anterior']."''  AND pla.pla_mes = ''12'') )
                          GROUP BY plaemp.indiv_id, persla.plati_id, pla.pla_anio, pla.pla_mes 
                          ORDER BY plaemp.indiv_id, persla.plati_id, pla.pla_anio, pla.pla_mes 

                       ', '".$sql_meseje."' )as ct(
                          
                          \"indice\" text, 
                          \"indiv_id\" numeric, 
                          \"plati_id\" numeric, 
                          \"".$params['anio_anterior']."_12\" double precision,
                          \"".$params['anio']."_01\" double precision,
                          \"".$params['anio']."_02\" double precision,
                          \"".$params['anio']."_03\" double precision,
                          \"".$params['anio']."_04\" double precision,
                          \"".$params['anio']."_05\" double precision,
                          \"".$params['anio']."_06\" double precision,
                          \"".$params['anio']."_07\" double precision,
                          \"".$params['anio']."_08\" double precision,
                          \"".$params['anio']."_09\" double precision,
                          \"".$params['anio']."_10\" double precision, 
                          \"".$params['anio']."_11\" double precision,
                          \"".$params['anio']."_12\" double precision  
                         
                       ) ";


        $sql=" SELECT

                    (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres) as trabajador,  
                     ind.indiv_dni,
                     plati.plati_nombre,
                     persla.*,
                     ingresos.*

                 FROM (
                      ".$sql_base."
                 ) as ingresos
                 LEFT JOIN \"public\".individuo ind ON ingresos.indiv_id = ind.indiv_id  
                 LEFT JOIN rh.persona_situlaboral persla ON ind.indiv_id = persla.pers_id AND persla_estado = 1 AND persla.persla_ultimo = 1
                 LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                 ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres

           ";
 
    

        $get_fields = ' * ';
        
        if($params['retornar_sql'] ===  true)
        {
          $sql_ = $sql_base;
        }
        else
        {
          $sql_ = $sql;
        }

        $sql = " SELECT ".$get_fields." FROM (".$sql_.") as data ";

        if($params['mes'] != '01')
        {
            if( $params['modo'] == 'altas' )
            {
 
                $sql.= " WHERE (data.\"".$params['anio']."_".$params['mes2']."\" = 0 OR data.\"".$params['anio']."_".$params['mes2']."\" is null) AND data.\"".$params['anio']."_".$params['mes']."\" > 0  
                       ";

            }


            if( $params['modo'] == 'bajas' )
            {
 
                $sql.= " WHERE (data.\"".$params['anio']."_".$params['mes']."\" = 0 OR data.\"".$params['anio']."_".$params['mes']."\" is null) AND data.\"".$params['anio']."_".$params['mes2']."\" > 0  
                       ";

            }  
          
        }
        else
        {

            if( $params['modo'] == 'altas' )
            {
  
                $sql.= " WHERE (data.\"".$params['anio_anterior']."_".$params['mes2']."\" = 0 OR data.\"".$params['anio_anterior']."_".$params['mes2']."\" is null) AND data.\"".$params['anio']."_".$params['mes']."\" > 0  
                        ";

            }


            if( $params['modo'] == 'bajas' )
            {
 
                $sql.= "  WHERE (data.\"".$params['anio']."_".$params['mes']."\" = 0 OR data.\"".$params['anio']."_".$params['mes']."\" is null) AND data.\"".$params['anio_anterior']."_".$params['mes2']."\" > 0  
                      ";

            }  
        }

      
       if($params['retornar_sql'] ===  true)
       {
          $rs = $sql;
       }
       else
       {
          $rs = $this->_CI->db->query($sql, $values )->result_array();
       }   
          
        
        return $rs;   


    }

  
    //  T REGISTRO 
     // BAJAS :PER , ALTAS:  IDE TRA  PER EST EST  


    public function per_bajas($params = array())
    {

        $indivs = $this->historico_ingresos_mes(array('modo' => 'bajas', 'anio' => $params['anio'], 'mes' => $params['mes']));

        $indiv_id = array();

        foreach ($indivs as $reg)
        {
           $indiv_id[] = $reg['indiv_id'];
        }
     
        $params['indivs_id_in'] = implode(',', $indiv_id);

        $meses = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    
        $dias_meses = array(
                             '01' => '31',
                             '02' => '28',
                             '03' => '31',
                             '04' => '30',
                             '05' => '31',
                             '06' => '30',
                             '07' => '31',
                             '08' => '31',
                             '09' => '30',
                             '10' => '31',
                             '11' => '30',
                             '12' => '31'
                           );

        if($params['mes'] == '01')
        {
            $mes = '12';
            $anio = ($params['anio'] * 1) - 1;
            $anio = trim($anio);
        }
        else
        {
            $anio = $params['anio'];

            for($i = 0; $i<= 11; $i++)
            { 
                if( $params['mes'] == $meses[$i] )
                {
                   break;
                } 
                else
                {
                   $mes = $meses[$i];
                }

            }
         }

         $dia = $dias_meses[$mes];

         $fecha_cese = $dia.'/'.$mes.'/'.$anio;


         $sql = "  SELECT indiv.indiv_id, indiv.indiv_dni, '".$fecha_cese."' as fecha_cese
                   FROM public.individuo indiv  
                   WHERE indiv.indiv_id IN (".$params['indivs_id_in'].") 
                   ORDER BY indiv.indiv_dni  ";

          
          $rs = $this->_CI->db->query($sql, array() )->result_array();

          return $rs;
 
    }



    // IDE
    public function ide($params)
    {
 
          $indivs = $this->historico_ingresos_mes(array('modo' => 'altas', 
                                                        'anio' => $params['anio'], 
                                                        'mes' => $params['mes'] ));

          $indiv_id = array();

          foreach ($indivs as $reg)
          {
             $indiv_id[] = $reg['indiv_id'];
          }


          $params['indivs_id_in'] = implode(',', $indiv_id);
 
  
          $sql = " SELECT tra.indiv_id,     
                          indiv.*,
                          plati.*

                   FROM ( ";
 
                      
              $sql.=" SELECT indiv_id, persla.persla_id 
                      FROM public.individuo indiv 
                      INNER JOIN  rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1  AND persla.persla_ultimo = 1 
                      WHERE indiv.indiv_id IN (".$params['indivs_id_in'].")";
    

          $sql.="  ) as tra 
                   INNER JOIN public.individuo indiv ON tra.indiv_id = indiv.indiv_id 
                   LEFT JOIN  rh.persona_pension pepe  ON indiv.indiv_id = pepe.pers_id AND pepe.peaf_estado = 1  
                   LEFT JOIN  rh.persona_situlaboral persla ON tra.persla_id = persla.persla_id 
                   INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id
 
                   ORDER BY indiv.indiv_dni 
               ";
 
         $rs = $this->_CI->db->query($sql, array() )->result_array();

         return $rs;          

    }



    public function tra($params)
    {
 
         $sql_main_trabajadores = $this->historico_ingresos_mes(array('modo'  => 'altas', 
                                                                       'anio' => $params['anio'], 
                                                                       'mes'  => $params['mes'],
                                                                       'retornar_sql' => true ));
 
        $sql = " SELECT indiv.indiv_dni,
                        indiv.indiv_id,
                        peaf_codigo as cuspp,
                         (CASE WHEN sctr.total > 0 THEN
                             
                             (CASE WHEN pepe.pentip_id = ".PENSION_AFP." THEN
                                '2'
                                
                              ELSE 
                                 '1'

                              END )

                         ELSE 
                             ''

                         END ) as sctr,

                        plati.*

                 FROM (".$sql_main_trabajadores.") as data  
                 LEFT JOIN ( 
                  
                     SELECT  plaemp.indiv_id, pla.plati_id, SUM(plaec_value) as total 
                     FROM planillas.planilla_empleados plaemp 
                     INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                     INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT."
                     INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1 
                     INNER JOIN planillas.conceptos_sistema cosi ON  pla.plati_id = cosi.plati_id AND  plaec.conc_id = conc_sctr   

                     WHERE plaec.plaec_value > 0 

                     GROUP BY plaemp.indiv_id, pla.plati_id 
                 
                 ) as sctr ON data.indiv_id = sctr.indiv_id  AND data.plati_id = sctr.plati_id 

                 INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id
                 INNER JOIN planillas.planilla_tipo plati ON data.plati_id = plati.plati_id 
                 LEFT JOIN  rh.persona_pension pepe ON indiv.indiv_id = pepe.pers_id AND pepe.peaf_estado = 1  

                 ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres
               ";


        $rs = $this->_CI->db->query($sql, array($params['anio'], $params['mes'] ) )->result_array();

        return $rs;          

    }
 

    public function per($params)
    {
      
         $sql_main_trabajadores = $this->historico_ingresos_mes(array('modo'  => 'altas', 
                                                                       'anio' => $params['anio'], 
                                                                       'mes'  => $params['mes'],
                                                                       'retornar_sql' => true ));


        $sql = " SELECT   
                      data.indiv_id,     
                      indiv.*,
                    
                      (CASE WHEN pepe.peaf_id is null THEN 

                           '".PDT_PENSION_OTRO."'

                       WHEN pepe.peaf_id is not null AND pepe.pentip_id = ".PENSION_SNP." THEN 

                           '".PDT_PENSION_ONP."'

                       WHEN pepe.peaf_id is not null AND pepe.pentip_id = ".PENSION_AFP." THEN 
                          
                           afp.sunat_regimenpensionario 

                       END) as sunat_regimenpensionario,
   
                      (CASE WHEN sctr.total > 0 THEN
                           1

                       ELSE 
                           0

                       END ) as sctr,

                       plati.*


                 FROM (".$sql_main_trabajadores.") as data  
                 LEFT JOIN ( 
                  
                     SELECT  plaemp.indiv_id, pla.plati_id, SUM(plaec_value) as total 
                     FROM planillas.planilla_empleados plaemp 
                     INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                     INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT."
                     INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1 
                     INNER JOIN planillas.conceptos_sistema cosi ON  pla.plati_id = cosi.plati_id AND  plaec.conc_id = conc_sctr   

                     WHERE plaec.plaec_value > 0 

                     GROUP BY plaemp.indiv_id, pla.plati_id 
                 
                 ) as sctr ON data.indiv_id = sctr.indiv_id  AND data.plati_id = sctr.plati_id 
                 
                 INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                 INNER JOIN planillas.planilla_tipo plati ON data.plati_id = plati.plati_id 
                 LEFT JOIN  rh.persona_pension pepe  ON indiv.indiv_id = pepe.pers_id AND pepe.peaf_estado = 1  
                 LEFT JOIN  rh.afp ON pepe.afp_id = afp.afp_id
                  
               ";


        $values =  array( $params['anio'], $params['mes'] );


        $rs = $this->_CI->db->query($sql, $values )->result_array();

        return $rs;          

     }



    public function est($params)
    {

        $sql_main_trabajadores = $this->historico_ingresos_mes(array('modo'  => 'altas', 
                                                                      'anio' => $params['anio'], 
                                                                      'mes'  => $params['mes'],
                                                                      'retornar_sql' => true ));

       $sql = " SELECT indiv.indiv_dni, data.indiv_id, plati.sunat_establecimiento  
                FROM ( ".$sql_main_trabajadores." ) as data  
                INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                INNER JOIN planillas.planilla_tipo plati ON data.plati_id = plati.plati_id 
                ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres ";
                        

       $rs = $this->_CI->db->query($sql, array() )->result_array();

       return $rs;

    }



    // PDT 
    public function tas($params)
    {
  
      $sql =  "  SELECT  
                      indiv.indiv_dni, 
                      MIN(plati.sunat_tasa_sctr) as tasa_sctr
                 FROM ( 

                    SELECT  plaemp.indiv_id, pla.plati_id, SUM(plaec_value) as total 
                    FROM planillas.planilla_empleados plaemp 
                    INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                    INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT." 
                    INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1 
                    INNER JOIN planillas.conceptos_sistema cosi ON  pla.plati_id = cosi.plati_id AND  plaec.conc_id = conc_sctr   
                    WHERE plaec.plaec_value > 0 
                    GROUP BY plaemp.indiv_id, pla.plati_id 

                 ) as data 
                 INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id  
                 INNER JOIN planillas.planilla_tipo plati On data.plati_id = plati.plati_id 
                 GROUP BY indiv.indiv_dni 
      

             ";
       
       $rs = $this->_CI->db->query($sql, array( $params['anio'], $params['mes']  ) )->result_array();

       return $rs;

    }
  


     public function toc($params)
     {
 
         $sql = " SELECT indiv.indiv_dni,  

                         0 as asegura_tu_pension,  


                         (CASE WHEN masvida.total > 0 THEN
                             1

                          ELSE 
                                0

                          END ) as masvida
 

                 FROM (  

                   SELECT distinct(plaemp.indiv_id) as indiv_id
                   FROM planillas.planilla_empleados plaemp 
                   INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                   INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT." 
                   INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1
                   INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.cosu_id != 0
                  
                 ) as tra  
                 LEFT JOIN ( 
                  
                   SELECT  plaemp.indiv_id, SUM(plaec_value) as total 
                   FROM planillas.planilla_empleados plaemp 
                   INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                   INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT." 
                   INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1
                   INNER JOIN planillas.conceptos_sistema cosi ON  pla.plati_id = cosi.plati_id AND  plaec.conc_id = conc_masvida   

                   GROUP BY plaemp.indiv_id 
                
                 ) as masvida ON tra.indiv_id = masvida.indiv_id  
                  INNER JOIN public.individuo indiv ON tra.indiv_id = indiv.indiv_id  
                  LEFT JOIN  rh.persona_pension pepe  ON indiv.indiv_id = pepe.pers_id AND pepe.peaf_estado = 1 


                  ORDER BY indiv.indiv_dni, indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 

                ";

        
        $rs = $this->_CI->db->query($sql, array( $params['anio'], $params['mes'],  $params['anio'], $params['mes'] ) )->result_array();

        return $rs;

     }

 

     public function jor($params)
     {
 
         $sql = " SELECT indiv.indiv_dni, 
                          
                       (  ROUND(asistencia.total) * 8 ) as asistencia 
 
                 FROM (  

                    SELECT distinct(plaemp.indiv_id) as indiv_id
                    FROM planillas.planilla_empleados plaemp 
                    INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                    INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT." 
                    INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1
                    INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.cosu_id != 0
                  
                 ) as tra  
                 LEFT JOIN ( 
                  
                   SELECT  plaemp.indiv_id, SUM(plaec_value) as total 
                   FROM planillas.planilla_empleados plaemp 
                   INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                   INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT."  
                   INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 0 
                   INNER JOIN planillas.conceptos_sistema cosi ON  pla.plati_id = cosi.plati_id AND  plaec.conc_id = conc_asistencia   

                   WHERE plaec.plaec_value > 0 

                   GROUP BY plaemp.indiv_id 
                
                 ) as asistencia ON tra.indiv_id = asistencia.indiv_id  
                  INNER JOIN public.individuo indiv ON tra.indiv_id = indiv.indiv_id  


                  WHERE asistencia.total > 0
                  
                  ORDER BY indiv.indiv_dni,  indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 

                ";

        
        $rs = $this->_CI->db->query($sql, array( $params['anio'], $params['mes'], $params['anio'], $params['mes'] ) )->result_array();

        return $rs;

     }



     public function  rem($params)
     {

         /* AGRUPAR LOS CONCEPTOS POR CONCEPTO DE SUNAT (snt) */
 
         $plati_restric = '';

         if($params['tipoplanilla'] == '')
         {
          
             $plati_restric = " NOT IN (".TIPOPLANILLA_REGIDORES.",".TIPOPLANILLA_PENSIONSITAS.")";

         }
         else if($params['tipoplanilla'] == TIPOPLANILLA_PENSIONSITAS )
         {
            
             $plati_restric = " IN (".TIPOPLANILLA_PENSIONSITAS.")";
         }

     
         $sql = "   SELECT  
                       ind.indiv_id,
                       ind.indiv_dni as dni,
                       ( ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres  ) as trabajador,
                       montos_sunat.*,
                       cosus.cosu_codigo,
                       cosus.cosu_descripcion
          
                     FROM (

                           SELECT  plaem.indiv_id, conc.cosu_id, sum(plaec_value) as monto 
                                     
                           FROM planillas.planilla_empleado_concepto pec 
                           INNER JOIN planillas.planilla_empleados plaem ON pec.plaemp_id = plaem.plaemp_id AND plaem.plaemp_estado = 1
                           INNER JOIN planillas.planillas pla ON plaem.pla_id = pla.pla_id AND pla.plati_id ".$plati_restric." AND pla_anio = ? AND pla_mes = ?  
                           INNER JOIN planillas.planilla_movimiento movs ON pla.pla_id = movs.pla_id AND plamo_estado = 1 AND  movs.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT." 
                           INNER JOIN planillas.conceptos conc ON pec.conc_id = conc.conc_id AND conc.cosu_id is not null AND conc.cosu_id != 0
     
                           WHERE  pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1 AND pec.plaec_value > 0
                           GROUP BY  plaem.indiv_id, conc.cosu_id

                           ORDER BY plaem.indiv_id, conc.cosu_id
                      
                     ) as  montos_sunat
                      INNER JOIN planillas.conceptos_sunat cosus ON montos_sunat.cosu_id = cosus.cosu_id AND cosu_incluir_enarchivo = 1
                      LEFT JOIN public.individuo ind ON montos_sunat.indiv_id = ind.indiv_id  
                  

                   ORDER BY ind.indiv_dni, montos_sunat.cosu_id  
                ";
     
         $rs= $this->_CI->db->query($sql, array($params['anio'] , $params['mes']))->result_array();       

         return $rs;
      }



      public function _pen($params){

         
         $params['tipoplanilla'] = TIPOPLANILLA_PENSIONSITAS;

         return  $this->rem($params);
      
      }
     
      

}