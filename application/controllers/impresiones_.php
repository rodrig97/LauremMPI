<?php
 

Class impresiones extends CI_Controller {
    
    public function __construct(){
        
        parent::__construct();

        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
        
     
    }

    public function view()
    {
         
        $modo = $this->input->post('mode'); 

        if($modo == 'reporte_masivo')
        {

            $pla_key = $this->input->post('planilla');  
            $ref = 'impresiones/boletas_de_pago/'.$pla_key;

        }
        else if($modo == 'boleta_de_pago')
        {
           
           $plaemp_key = $this->input->post('detalle');  
           $ref = 'impresiones/boleta_de_pago/'.$plaemp_key;

        }
        else if($modo == 'resumen_planilla')
        {
           
           $pla_key = $this->input->post('planilla');  
           $ref = 'impresiones/resumen_planilla/'.$pla_key;

        }
        else if($modo == 'resumen_planilla_detallado')
        {

           $pla_key = $this->input->post('planilla');  
           $ref = 'impresiones/resumen_detallado/'.$pla_key;

        }
        else if($modo == 'resumen_presupuestal_siaf')
        {
           $ref =  'impresiones/resumen_siaf';
        }


       // echo "prueabaa";
      //  echo "<iframe src='".$ref."' width='828' height='450'  />";
      
        $this->load->view('planillas/v_pdf_view', array('ref' => $ref));


    }
     
    
    public function boleta_de_pago($detalle_key)
    {
        
        $this->load->library(array('App/planilla','App/planillaempleado','App/persona'));
     
        $plaemp_id     = $this->planillaempleado->get_id($detalle_key);
        
        $plaemp_info   = $this->planillaempleado->get($detalle_key, true);
        $indiv_id      = $plaemp_info['indiv_id'];
        $persona_info  = $this->persona->get_info($indiv_id);
        
        $planilla_info = $this->planilla->get($plaemp_info['pla_id']);
        $tipo_empleado =  '';  
        
        $variables                     = array();
        $variables['calculo_empleado'] = $this->planillaempleado->get_planillaempleado_variables($plaemp_id, true, true);
        $variables['calculo_sistema']  = $this->planillaempleado->get_planillaempleado_variables($plaemp_id, false, true);
        
        $conceptos                     = array();
        $conceptos['ingresos']         = $this->planillaempleado->get_planillaempleado_conceptos($plaemp_id, TIPOCONCEPTO_INGRESO   ,1 , true );
        $conceptos['descuentos']       = $this->planillaempleado->get_planillaempleado_conceptos($plaemp_id, TIPOCONCEPTO_DESCUENTO, 1 ,  true );
        $conceptos['aportaciones']     = $this->planillaempleado->get_planillaempleado_conceptos($plaemp_id, TIPOCONCEPTO_APORTACION, 1 ,  true);
        
        ob_clean(); 
        $this->load->library(array('fpdf','reportes/pdf'));
        $basepath = dirname(FCPATH);
        $this->load->view('impresiones/boleta_pago', array( 
                                                             'persona_info'  => $persona_info,
                                                             'conceptos'     => $conceptos, 
                                                             'variables'     => $variables, 
                                                             'planilla_info' => $planilla_info,
                                                             'tipo_empleado' => $tipo_empleado,
                                                             'detalle_info'  => $plaemp_info 
                                                           ));  
       
    }
 

    public function boletas_de_pago($planilla_key )
    {

         $this->load->library(array('App/planilla','App/planillaempleado','App/persona'));
          
         $planilla_id      = $this->planilla->get_id($planilla_key);
         $planilla_detalle = $this->planillaempleado->get_list($planilla_id);
         
         $planilla_info    = $this->planilla->get($planilla_id);

         $this->load->library(array('fpdf','reportes/pdf'));
         $basepath = dirname(FCPATH);
         $this->load->view('impresiones/masivo_boletas', array('planilla_detalle' => $planilla_detalle, 'planilla_info' => $planilla_info ));  
    

    }
     

    public function resumen_planilla($planilla_key){
 

        $this->load->library(array('App/planilla'));
        
        $planilla_id               = $this->planilla->get_id($planilla_key);
        $planilla_info             = $this->planilla->get($planilla_id); 
        $afectacion_fuentes        = $this->planilla->get_afectacion_fuentes($planilla_id);



        $this->load->library(array('fpdf','reportes/pdf')); 

        $basepath = dirname(FCPATH);
        $this->load->view('impresiones/resumen_planilla', array(
                                                                 'planilla_info'      => $planilla_info, 
                                                                 'afectacion_fuentes' => $afectacion_fuentes 

                                                                ));  
         
    }

    public function resumen_detallado($planilla_key)
    {

        
        $this->load->library(array('App/planilla'));

        $planilla_id               = $this->planilla->get_id($planilla_key);
        $planilla_info             = $this->planilla->get($planilla_id); 
        
        $conceptos_header          =  array();
    
        $plati_id = $planilla_info['plati_id'];

        

        $sql =" SELECT rs_x.conc_id,  
                       conc.conc_planillon_nombre as nombre 

                FROM 
                (

                  SELECT  distinct(rs_d.conc_id) FROM
                  (
                    SELECT 

                       ( CASE WHEN ggvc.gvcg_id is null THEN
                                  pec.conc_id 
                            ELSE
                                  ggvc.conc_id_ref     
                            END
                                )  as conc_id 

                       FROM planillas.planilla_empleados pe 
                       INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.conc_afecto = 1 AND pec.plaec_marcado = 1 AND pec.conc_tipo = ?
                       INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id  
                       LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       
                       
                       WHERE pe.pla_id = ? AND pe.plaemp_estado = 1
                  
                  ) as rs_d 

              ) rs_x

              LEFT JOIN planillas.conceptos conc ON rs_x.conc_id = conc.conc_id 
 
         
              ORDER BY nombre   ";


        $conceptos_header['ingresos'] = $this->db->query($sql, array(TIPOCONCEPTO_INGRESO, $planilla_id))->result_array();
  
        $conceptos_header['descuentos'] = $this->db->query($sql, array(TIPOCONCEPTO_DESCUENTO, $planilla_id))->result_array();
  
        $conceptos_header['aportaciones'] = $this->db->query($sql, array(TIPOCONCEPTO_APORTACION, $planilla_id))->result_array();

        $conceptos_header['info_vars'] = array( array('codigo' => 'afp',
                                                      'nombre' => 'AFP'),
                                                array('codigo' => 'afp_codigo',
                                                      'nombre' => 'CODIGO AFP') 
                                                );


/*
        $sql ="  SELECT distinct(pec.conc_id)  
                               FROM planillas.planilla_empleados pe 
                               INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1
                               INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id 
                               WHERE pe.pla_id = ?  ";
*/
      
        $sql =" SELECT rs_x.conc_id,  conc.conc_planillon_nombre as nombre FROM 
                (

                  SELECT  distinct(rs_d.conc_id) FROM
                  (
                    SELECT 
                     ( CASE WHEN ggvc.gvcg_id is null THEN
                                pec.conc_id 
                          ELSE
                               ggvc.conc_id_ref     
                          END
                              )  as conc_id 

                     FROM planillas.planilla_empleados pe 
                     INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1
                     INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id 
                     LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       
                              
                       WHERE pe.pla_id = ?  AND pe.plaemp_estado = 1
                  ) as rs_d 

              ) rs_x

              LEFT JOIN planillas.conceptos conc ON rs_x.conc_id = conc.conc_id 

           
              ORDER BY conc_id

        ";
                               
      
        $conceptos_planilla = $this->db->query($sql, array($planilla_id))->result_array(); 

        $sql_result = '';
        foreach($conceptos_planilla as $k => $reg)
        {

            if($k>0)  $sql_result.=',';
            $sql_result.=' "'.$reg['conc_id'].'" text'; 

        }

/*
        $sql_main = "
                    SELECT  (ind.indiv_appaterno ||' '|| ind.indiv_apmaterno ||' '|| ind.indiv_nombres ) as nombre_trabajador,
                            ind.indiv_dni,main.*

                    FROM  (     

                        SELECT * FROM crosstab('


                                        SELECT pe.indiv_id, pec.conc_id, pec.plaec_value 
                                                           FROM planillas.planilla_empleados pe 
                                                           INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1
                                                           INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id      
                                                WHERE pe.pla_id = ".$planilla_id."             
                                                ORDER BY pe.indiv_id, pec.conc_id   
                         
                        ','
                           SELECT distinct(pec.conc_id)  
                                   FROM planillas.planilla_empleados pe 
                                   INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1
                                   INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id 
                                   WHERE pe.pla_id = ".$planilla_id."           
              
                         ')
                        as (

                           indiv_id int,
                           ".$sql_result."
                            
                        )

                    ) as main

                    LEFT JOIN  public.individuo ind ON main.indiv_id = ind.indiv_id
 
        ";

*/  

        /* Obtenemos las variables que van a ir en el planillon */ 

        $sql = " SELECT vari_id, vari_planillon_nombre 
                 FROM planillas.variables 
                 WHERE vari_planillon = 1 AND vari_estado = 1 AND plati_id = ?
                 ORDER BY vari_id  ";

        $rs = $this->db->query($sql ,array($plati_id))->result_array();
          
        $vari_id_s = array();
        $vari_id_header = array();
        $vari_keys = array();
        $in_variables_planillon = '';
        $in_variables_header = '';

        $has_variables = true;

        if(sizeof($rs) == 0 )
        {

            $has_variables = false;

        }

        foreach($rs as $reg)
        {
            $vari_id_s[] = $reg['vari_id'];
            $vari_id_header[] = '"'.$reg['vari_planillon_nombre'].'" double precision ';
            $vari_keys[] = $reg['vari_planillon_nombre'];
        }

        $in_variables_planillon = implode(',', $vari_id_s );
        $in_variables_header = implode(',' , $vari_id_header );

/* 

        $sql_variables = " SELECT DISTINCT(plaev.vari_id), vari.vari_planillon_nombre
                                    FROM planillas.planilla_empleado_variable plaev 
                                    INNER JOIN planillas.variables vari ON plaev.vari_id = vari.vari_id AND vari.vari_estado = 1 AND vari.vari_planillon = 1 

                                     WHERE plaev.plaev_estado = 1 AND 
                                           plaev.plaemp_id IN (SELECT plaemp_id   
                                                               FROM planillas.planilla_empleados plaemp 
                                                               WHERE plaemp.pla_id = ? AND plaemp.plaemp_estado = 1 ) 
                        "; 

        $rs = $this->db->query($sql_variables, array($planilla_id) )->result_array();
*/  

        /*




SELECT * FROM crosstab(' 
                            SELECT plaev.plaemp_id, plaev.vari_id, SUM(plaev_valor) 
                            FROM planillas.planilla_empleado_variable plaev

                            WHERE  plaev.plaev_estado = 1 AND 
                                   plaev.plaemp_id IN (SELECT plaemp_id   
                                                       FROM planillas.planilla_empleados plaemp 
                                                       WHERE plaemp.pla_id = 247   AND plaemp.plaemp_estado = 1 )  
                                    
                                    AND plaev.vari_id IN (135,391,417)

                              GROUP BY plaev.plaemp_id, plaev.vari_id

                              ORDER BY plaev.plaemp_id, plaev.vari_id
                            ', 
                            ' SELECT vari_id 
                              FROM planillas.variables 
                              WHERE vari_planillon = 1 AND vari_estado = 1 AND plati_id = 9
                              ORDER BY vari_id   ' ) 
                            as ( plaemp_id int,
                                "HF" int,"HL" int,"DM" int  )

        */

        $sql = "  SELECT * FROM crosstab(' 
                                    SELECT plaev.plaemp_id, 
                                           plaev.vari_id, 
                                           SUM(plaev_valor) as valor
                                    
                                    FROM planillas.planilla_empleado_variable plaev

                                    WHERE  plaev.plaev_estado = 1 AND 
                                           plaev.plaemp_id IN (SELECT plaemp_id   
                                                               FROM planillas.planilla_empleados plaemp 
                                                               WHERE plaemp.pla_id = ".$planilla_id."  AND plaemp.plaemp_estado = 1 )  
                                            
                                            AND plaev.vari_id IN (".$in_variables_planillon.")

                                    GROUP BY plaev.plaemp_id, plaev.vari_id
                                    ORDER BY plaev.plaemp_id, plaev.vari_id

                            ', 
                            ' SELECT vari_id 
                              FROM planillas.variables 
                              WHERE vari_planillon = 1 AND vari_estado = 1 AND  plati_id = ".$plati_id."
                              ORDER BY vari_id   ' ) 
                            as ( plaemp_id  int,
                                ".$in_variables_header."  

                               );  
                ";

        $this->db->query($sql ,array())->result_array();




        $sql_main = "  SELECT (ind.indiv_appaterno ||' '|| ind.indiv_apmaterno ||' '|| ind.indiv_nombres ) as nombre_trabajador, 
                               platica_nombre as ocupacion,
                            ind.indiv_dni,
                            afp_nombre as afp, pensi.peaf_codigo as afp_codigo, persa_codigo as seguro,
                            main.*,
                            varis.*

                    FROM  (     

                        SELECT * FROM crosstab('
 
                                          SELECT   t_rs.plaemp_id, t_rs.platica_id, t_rs.indiv_id, t_rs.conc_id,   SUM(t_rs.monto) as monto  

                                           FROM (

                                             SElECT 
                                                pemp.plaemp_id, 
                                                pemp.platica_id, 
                                                pemp.indiv_id,  
                                               ( CASE WHEN ggvc.gvcg_id is null THEN
                                                    pec.conc_id 
                                              ELSE
                                                   ggvc.conc_id_ref     
                                              END
                                               ) as conc_id,
                                                    pec.plaec_value as monto
                                             FROM  planillas.planilla_empleados pemp  
                                             LEFT JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pemp.plaemp_id  AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1
                                             LEFT JOIN planillas.conceptos concs ON pec.conc_id = concs.conc_id
                                             LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON concs.gvc_id = ggvc.gvc_id 

                                             WHERE  pemp.plaemp_estado = 1 AND pemp.pla_id = ".$planilla_id." 

                                           ) t_rs

                                          GROUP BY  plaemp_id, t_rs.platica_id, indiv_id,conc_id

                                          order by  plaemp_id, t_rs.platica_id, conc_id

                         

                        ','

                               SELECT  distinct(rs_d.conc_id) FROM
                                                (
                                                  SELECT 
                                                       ( CASE WHEN ggvc.gvcg_id is null THEN
                                                              pec.conc_id 
                                                        ELSE
                                                             ggvc.conc_id_ref     
                                                        END
                                                            )  as conc_id 

                                                   FROM planillas.planilla_empleados pe 
                                                   LEFT JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1
                                                   LEFT JOIN planillas.conceptos con ON pec.conc_id = con.conc_id 
                                                   LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       
                                                            
                                                     WHERE pe.pla_id =  ".$planilla_id." AND plaemp_estado = 1

                                                     order by conc_id
                                                  ) as rs_d 

 
              
                         ')
                        as (
                            plaemp_id int,
                            platica_id int,
                            indiv_id int,
                           ".$sql_result."
                            
                        )

                    ) as main 

                    LEFT JOIN (


                          SELECT * FROM crosstab(' 
                                                              SELECT plaev.plaemp_id, 
                                                                     plaev.vari_id, 
                                                                     SUM(plaev_valor) as valor
                                                              
                                                              FROM planillas.planilla_empleado_variable plaev

                                                              WHERE  plaev.plaev_estado = 1 AND 
                                                                     plaev.plaemp_id IN (SELECT plaemp_id   
                                                                                         FROM planillas.planilla_empleados plaemp 
                                                                                         WHERE plaemp.pla_id = ".$planilla_id."  AND plaemp.plaemp_estado = 1 )  
                                                                      
                                                                      AND plaev.vari_id IN (".$in_variables_planillon.")

                                                              GROUP BY plaev.plaemp_id, plaev.vari_id
                                                              ORDER BY plaev.plaemp_id, plaev.vari_id

                                                      ', 
                                                      ' SELECT vari_id 
                                                        FROM planillas.variables 
                                                        WHERE vari_planillon = 1 AND vari_estado = 1 AND  plati_id = ".$plati_id."
                                                        ORDER BY vari_id   ' ) 
                                                      as ( plaemp_id  int,
                                                          ".$in_variables_header."  

                                                         )   

                    ) as varis ON varis.plaemp_id = main.plaemp_id 

                    LEFT JOIN  public.individuo ind ON main.indiv_id = ind.indiv_id
                    LEFT JOIN  planillas.planilla_tipo_categoria ocu ON main.platica_id = ocu.platica_id
                    LEFT JOIN  rh.persona_pension pensi ON pensi.pers_id = ind.indiv_id AND pensi.peaf_estado = 1 
                    LEFT JOIN  rh.afp ON afp.afp_id = pensi.afp_id  
                    LEFT JOIN  rh.persona_essalud essa  ON essa.pers_id = ind.indiv_id AND persa_estado = 1  
  
              ORDER BY ind.indiv_appaterno, ind.indiv_apmaterno, ind.indiv_nombres

        ";


 //echo $sql_main;

         $rs_main = $this->db->query($sql_main, array())->result_array(); 
 
         $this->load->library(array('fpdf','reportes/pdf')); 

         $basepath = dirname(FCPATH);
      
         $this->load->view('impresiones/resumen_detallado', array( 'planilla_info' => $planilla_info, 
                                                                   'conceptos_header' => $conceptos_header, 
                                                                   'variables' => $vari_keys,
                                                                   'resumen' => $rs_main ));  
         



    } 
    

    public function hoja_asistencia($hoja_key){


        $this->load->library(array('App/hojadiariotipo','App/hojaasistencia'));
        
        $hoja_id = $this->hojaasistencia->get_id($hoja_key);
 
        $hoja_info   = $this->hojaasistencia->get($hoja_id);
        $estados_dia = $this->hojadiariotipo->get(1);

        $rs_estados = array();
        foreach($estados_dia as $st) $rs_estados[$st['hatd_id']] = $st;
          
        $calendario_data = ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_calendar($hoja_id) : array() ;
     
        $this->load->library(array('fpdf','reportes/pdf')); 

         $basepath = dirname(FCPATH);

        $this->load->view('impresiones/hoja_asistencia_print', array('calendario' => $calendario_data, 'hoja_info' => $hoja_info, 'rs_estados_dias' => $rs_estados, 'usuario' => $this->usuario));
 
    }


    public function resumen_siaf()
    {

         $this->load->library(array('App/planilla','App/reporte', 'App/exportador'));

         $reporte_id = $this->input->post('reporte');
        
         $planillas_keys = $this->input->post('planillas');
 
         $planillas_keys = explode('_', $planillas_keys);

        // array_shift($planillas_keys);
  

         $planillas_info    = $this->planilla->get_codigos($planillas_keys , false);
          
         $planillas_codigos = '';         

         foreach($planillas_info as $planilla)
         {
             $planillas[] = $planilla['pla_id'];
             $planillas_codigos.=" - ".$planilla['pla_codigo'];
         }    

         $params_modelo['planillas'] = $planillas;
         $reporte_info['planillas'] = $planillas_codigos;

         $reporte_info['total_trabajadores'] = $this->planilla->contar_trabajadores($planillas);

 

         list($tipo_reporte) = $this->reporte->get($reporte_id );



         if( trim($tipo_reporte['rep_model']) != '')
         {
              
              $reporte_info['reporte_nombre'] = $tipo_reporte['rep_nombre'];

              $metodo = $tipo_reporte['rep_model'];
             
            
              $reporte = call_user_func_array( array($this->exportador, $metodo ), 
                                               array($params_modelo) ); 
              
              $tipo = $tipo_reporte['rep_alias']; 

              $w_planilla = false;
              $w_tarea    = false;

              if($tipo=='fpcm' || $tipo == 'fpcmt')
              {
                  $w_planilla = true;
              }

              if($tipo=='fpcmt' || $tipo == 'fcmt')
              {
                  $w_tarea = true;
              }


              $estructura = array('Fuente' => array('fuente_financiamiento',15,'C') );
               
              if($w_planilla)
              {
                  $estructura['Planilla'] = array('pla_codigo', 18, 'C'); 
              }
              
              $estructura['Clasificador'] = array('clasificador_codigo', 15, 'C'); 
              $estructura['Meta']         = array('sec_func', 15, 'C'); 

              if($w_tarea)
              {
                  $estructura['Tarea'] = array('tarea_nro', 15, 'C'); 
              }

               $estructura['Total']               = array('total', 12, 'L'); 

               $estructura['Meta_nombre']         = array('meta_nombre', 43,'L'); 
               $estructura['Clasificador_nombre'] = array('clasificador_nombre', 43, 'L'); 
                     
               

      
              ob_clean(); 
              $this->load->library(array('fpdf','reportes/pdf'));
              $basepath = dirname(FCPATH);  

              $this->load->view( 'impresiones/resumen_siaf', array( 
                                                                     'reporte'            => $reporte, 
                                                                     'reporte_info'       => $reporte_info,   
                                                                     'estructura'         => $estructura 
                                                                   )); 

       }



       
    }

     


}