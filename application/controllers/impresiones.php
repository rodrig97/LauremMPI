<?php
 

Class impresiones extends CI_Controller {
    
    public function __construct()
    {
        
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
        
        $data = $this->input->post();

        $modo = $this->input->post('mode'); 

        $visualizar_button = true;

        $params = array();

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
        else if($modo == 'resumen_presupuestal_planilla')
        {

           $pla_key = $this->input->post('planilla');  
           $ref = 'impresiones/resumen_presupuestal_planilla/'.$pla_key;

        }
        else if($modo == 'resumen_contable')
        {

           $pla_key = $this->input->post('planilla');  
           $ref = 'impresiones/resumen_contable/'.$pla_key;

        }
        else if($modo == 'resumen_presupuestal_siaf')
        {
           $ref =  'impresiones/resumen_siaf';
        }
        else if($modo == 'hoja_asistencia')
        {
           $ref =  'impresiones/hoja_asistencia/'.$data['view'];

           foreach($data as $key => $value) 
           {
             $params[$key] = $value;
           } 

        }
        else if($modo == 'certificados_trabajo')
        {
           $ref =  'impresiones/certificados_trabajo';

           $params['views'] = $data['views'];

           $params['nrocertificado'] = $data['nrocertificado'];

           $params['fechadocumento'] = $data['fechadocumento'];

           $visualizar_button = true;
        }


       // echo "prueabaa";
      //  echo "<iframe src='".$ref."' width='828' height='450'  />";
      
        $this->load->view('planillas/v_pdf_view', array('ref' => $ref, 'visualizar_button' => $visualizar_button, 'params' => $params ));


    }
     
    
    public function boleta_de_pago($detalle_key)
    {
        
        $this->load->library(array('App/planilla','App/planillaempleado','App/persona'));
     
        if($detalle_key=='')
        {

            $detalle_key = trim($this->input->post('boletas'));

        }

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
  
         if( trim($this->input->post('boletas')) == '')
         {

             $planilla_id      = $this->planilla->get_id($planilla_key);
             $planilla_detalle = $this->planillaempleado->get_list($planilla_id);
             
             $planilla_info    = $this->planilla->get($planilla_id);  
            
             $numero_copias = 2;
         }
         else
         {

            $boletas =  trim($this->input->post('boletas'));

            $numero_copias = ($this->input->post('copias_por_hoja')*1);

            $boletas =  explode('|', $boletas); 

            array_shift($boletas);

            foreach ($boletas as $key => $value) {
              # code...
            }

            $planilla_detalle = array(); 

            $plaemp_key_in = implode("','", $boletas );

            $sql = "  SELECT plaemp_id as detalle_id, pla_id, indiv_id 
                      FROM planillas.planilla_empleados WHERE plaemp_key IN ('".$plaemp_key_in."') 
                      ORDER BY plaemp_id desc ";

            $planilla_detalle = $this->db->query($sql, array())->result_array();


         }


         $this->load->library(array('fpdf','reportes/pdf'));
         $basepath = dirname(FCPATH);
         $this->load->view('impresiones/masivo_boletas', array('planilla_detalle' => $planilla_detalle, 
                                                               'planilla_info' => $planilla_info,
                                                               'numero_copias' =>  $numero_copias
                                                                ));  

    }
     

    public function resumen_planilla($planilla_key){
 

        $this->load->library(array('App/planilla'));
 
        $planilla_id               = $this->planilla->get_id($planilla_key);
         
        $this->load->library(array('fpdf','reportes/pdf')); 

        $basepath = dirname(FCPATH);
        $this->load->view('impresiones/resumen_planilla', array( 'planillas' => array($planilla_id)  ));  
         
    }


    public function resumen_planillas($siaf)
    { 
          
        $this->load->library(array('App/planilla','fpdf','reportes/pdf')); 

        $basepath = dirname(FCPATH);
          
        $siaf = xss_clean($siaf);

        $this->load->view('impresiones/resumen_agrupado_planillas', array( 'siaf' => $siaf ));  
          
      
    } 

    public function resumen_detallado($planilla_key)
    {

        
        $this->load->library(array('App/planilla', 'fpdf','reportes/pdf'));
   
        $basepath = dirname(FCPATH);

        $planilla_id  = $this->planilla->get_id($planilla_key);
       
        $this->load->view('impresiones/resumen_detallado', array( 'planillas' => array($planilla_id)  ));  
         



    } 


    public function resumen_agrupado_planillas($planillas)
    {

        
        $this->load->library(array('App/planilla'));

        $planilla_id               = $this->planilla->get_id($planilla_key);
        $planilla_info             = $this->planilla->get($planilla_id); 
        
        $conceptos_header          =  array();
    
        $plati_id = $planilla_info['plati_id'];


        $planillas = array(387,388,389);

        $in_planillas = implode(',', $planillas);


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

                       WHERE pe.pla_id IN (".$in_planillas.") AND pe.plaemp_estado = 1
                  
                  ) as rs_d 

              ) rs_x

              LEFT JOIN planillas.conceptos conc ON rs_x.conc_id = conc.conc_id 
    
         
              ORDER BY nombre   ";


        $conceptos_header['ingresos'] = $this->db->query($sql, array(TIPOCONCEPTO_INGRESO ))->result_array();
    
        $conceptos_header['descuentos'] = $this->db->query($sql, array(TIPOCONCEPTO_DESCUENTO ))->result_array();
    
        $conceptos_header['aportaciones'] = $this->db->query($sql, array(TIPOCONCEPTO_APORTACION ))->result_array();

        $conceptos_header['info_vars'] = array( array('codigo' => 'afp',
                                                      'nombre' => 'AFP'),
                                                array('codigo' => 'afp_codigo',
                                                      'nombre' => 'CODIGO AFP') 
                                                );

      
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
                              
                    WHERE pe.pla_id IN (".$in_planillas.")  AND pe.plaemp_estado = 1
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
    

        /* Obtenemos las variables que van a ir en el planillon  

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

      */



        $sql_main = "  SELECT  

                            ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                            main.*  
                    ";

       /* if(sizeof($vari_id_s) > 0 )
        {
    
            $sql_main.=" 
                                ,varis.*
                        ";

        }*/

        $sql_main.=" 
                    FROM  (     

                        SELECT * FROM crosstab('
    
                                         SELECT   t_rs.pla_id, t_rs.conc_id, SUM(t_rs.monto) as monto  

                                           FROM (

                                             SElECT 
                                                pemp.pla_id,

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
                                             LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON concs.gvc_id = ggvc.gvc_id AND gvcg_estado = 1
                                             WHERE  pemp.plaemp_estado = 1 AND pemp.pla_id IN (".$in_planillas.")

                                           ) t_rs

                                          GROUP BY  t_rs.pla_id, t_rs.conc_id

                                          order by   t_rs.pla_id, t_rs.conc_id

                         

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
                                                  
                               WHERE pe.pla_id IN (".$in_planillas.") AND plaemp_estado = 1

                               order by conc_id
                             
                               ) as rs_d 

    
              
                         ')
                        as (
                            pla_id int,
                           ".$sql_result."
                            
                        )

                    ) as main 
          

                    LEFT JOIN  planillas.planillas pla ON main.pla_id = pla.pla_id  

                    ORDER BY  pla.pla_id

    
    
        ";


        /*  if(sizeof($vari_id_s) > 0 )
          {


              $sql_main.=" 


                          LEFT JOIN (


                                SELECT * FROM crosstab('            SELECT plaev.plaemp_id, 
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
        
                ";

          }*/


          $sql_main.="  

        ";

    

         $rs_main = $this->db->query($sql_main, array())->result_array(); 
    
         $this->load->library(array('fpdf','reportes/pdf')); 

         $basepath = dirname(FCPATH);
      
         $this->load->view('impresiones/resumen_agrupado_planillas', array( 'planilla_info' => $planilla_info, 
                                                                   'conceptos_header' => $conceptos_header, 
                                                                   'variables' => $vari_keys,
                                                                   'resumen' => $rs_main ));  
         



    } 
    

    public function hoja_asistencia($hoja_key)
    {


        $this->load->library(array('App/hojadiariotipo','App/hojaasistencia'));
        
        $data =  $this->input->post(); 

        $hoja_id = $this->hojaasistencia->get_id($hoja_key);
  
        $orden = (trim($data['orden']) == '' ? '1' : trim($data['orden']) );
        $modo_ver = (trim($data['ver_modo']) == '' ? '1' : trim($data['ver_modo']) );
         
        $params = array( 'orden' => $orden, 
                         'modo_ver' => $modo_ver );


        $hoja_info   = $this->hojaasistencia->get($hoja_id);
        $plati_id = $hoja_info['plati_id'];

        $estados_dia = $this->hojadiariotipo->get($plati_id);   
        $rs_estados_dia = array();

        foreach($estados_dia as $st)
        {
            $rs_estados_dia[$st['hatd_id']] = $st;
        } 
  
        
        $ancho_hoja = 275;
        $ancho_celda_calendario = 10;
        $sizes = array(6, 45, 18, 9);
        
        $nro_dias = $hoja_info['nro_dias'] * 1;   // Contar dias de la hoja de asistencia
        $nro_estados = sizeof($rs_estados_dia);  // Contar numero de estados del dia involucrados 
 
        $total_celdas = $nro_dias + $nro_estados;
        
        $ancho_calculado = 78 + ($total_celdas * $ancho_celda_calendario);
        
        if($ancho_calculado > $ancho_hoja)
        {
            $reporte_unico = false;
        }
        else
        {
             $reporte_unico = true;
        }

        // Tomamos los datos 
        if($reporte_unico)
        {
            $calendario_data = ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_hoja($hoja_id, $params, true, true ) : array();
            $resumen_data =  array();
        }
        else
        {
            $calendario_data = ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_hoja($hoja_id, $params, true) : array();
            $resumen_data = ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_hoja($hoja_id, $params, false, true ) : array();
        }


        $this->load->library(array('fpdf','reportes/pdf')); 
 
        $basepath = dirname(FCPATH);
 
        $this->load->view('impresiones/hoja_asistencia_print', array('reporte_unico' => $reporte_unico,
                                                                     'calendario' => $calendario_data, 
                                                                     'resumen' => $resumen_data,
                                                                     'hoja_info' => $hoja_info, 
                                                                     'rs_estados_dia' => $rs_estados_dia, 
                                                                     'usuario' => $this->usuario, 
                                                                     'cantidad_celdas' => $total_celdas,
                                                                     'params' => $params,
                                                                     'sizes' => $sizes,
                                                                     'ancho_celda_calendario' => $ancho_celda_calendario));
 
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
  

    public function imprimir()
    {

        $this->load->library(array('App/planilla', 'App/planillaempleado', 'App/persona', 'App/planillaempleado'));

        $data = $this->input->post();
 
        if(trim($data['planillas']) != '')
        {
        
            $planillas_keys     = $this->input->post('planillas');
            $planillas_keys_txt = $planillas_keys;
            $planillas_keys     = explode('_', $planillas_keys);
            array_shift($planillas_keys);

            $planillas_codigos = '';
            $planillas_info    = $this->planilla->get_codigos($planillas_keys , false);
         
            foreach($planillas_info as $planilla)
            {
                $planillas[] = $planilla['pla_id'];
                $planillas_codigos.=" - ".$planilla['pla_codigo'];
            }    
 
        }
        else if(trim($data['siaf']) != '')
        {
            $siaf = trim($data['siaf']);
        }
        else 
        {
            die('Debe especificar por lo menos una planilla.');
        }


        $params = array();

        if($data['documento'] == '1') // BOLETA
        {

             if($data['filtro'] == '1') // Mostrar solo los que tienen cuenta
             {
                  $params['tienecuenta']  = 2;
             }  

             $ordenar_x = $data['ordenar'];

             if($ordenar_x == '2') // COMBINAR PLANILLAS
             {
                $params['orderbyplanilla'] = false;
             }
             else
             {
                $params['orderbyplanilla'] = true;
             }

             $boletas = $this->planillaempleado->get_list( $planillas, false,  $params  );
 

             $trabajador_x_pagina =  $data['trabajadoresxhoja'];
             $copia_x_pagina = $data['copiasxhoja'];

             if($trabajador_x_pagina == 2) $copia_x_pagina = 1;

             ob_clean(); 
             $this->load->library(array('fpdf','reportes/pdf'));
             $basepath = dirname(FCPATH);  

 
             $this->pdf->initialize('p','mm','A4');
             $this->pdf->logo = false;
             $this->pdf->AliasNbPages();
             $this->pdf->AddPage();

             $pos_ini_y = IMPRESION_POSY_BOLETA1; 
             $cb = 0;
             
             foreach ($boletas as $detalle) 
             {

                     $cb++;
     
                     if($trabajador_x_pagina == 1 )
                     {

                          $pos_ini_y = IMPRESION_POSY_BOLETA1; 
                          $this->pdf->AddPage();
                          $this->pdf->SetTopMargin(0);
                     }
     
                     if($trabajador_x_pagina == 2)
                     {
          
                          if($cb==1)
                          { 
                               $this->pdf->AddPage();
                               $this->pdf->SetTopMargin(0);
                               $pos_ini_y = IMPRESION_POSY_BOLETA1;  
                          }
       
                          if($cb==2)
                          {
                             $pos_ini_y = IMPRESION_POSY_BOLETA2; 
                              
                             $cb = 0;
                          }
     
                     } 
                    
                     $planilla_info = array();
                     $persona_info = array();
                     $conceptos = array();
                     $variables = array();
                     $detalle_info = array();

                     $persona_info = $this->persona->get_info($detalle['indiv_id']);
                     $variables                     = array();
                     $variables['calculo_empleado'] = $this->planillaempleado->get_planillaempleado_variables($detalle['detalle_id'], true,  true);
                     $variables['calculo_sistema']  = $this->planillaempleado->get_planillaempleado_variables($detalle['detalle_id'], false, true);
                      
                     $conceptos                     = array();
                     $conceptos['ingresos']         = $this->planillaempleado->get_planillaempleado_conceptos($detalle['detalle_id'], TIPOCONCEPTO_INGRESO , 1 , true );
                     $conceptos['descuentos']       = $this->planillaempleado->get_planillaempleado_conceptos($detalle['detalle_id'], TIPOCONCEPTO_DESCUENTO, 1 , true);
                     $conceptos['aportaciones']     = $this->planillaempleado->get_planillaempleado_conceptos($detalle['detalle_id'], TIPOCONCEPTO_APORTACION, 1 , true);
                     
                     $detalle_info  = $this->planillaempleado->get($detalle['detalle_id']);
                     $planilla_info = $this->planilla->get($detalle['pla_id']);

                  
                     $parametos = array(  'planilla_info' => $planilla_info, 
                                          'persona_info'  => $persona_info,
                                          'conceptos'     => $conceptos, 
                                          'variables'     => $variables,
                                          'detalle_info'  => $detalle_info  );
                  

                     $parametos['POSICION_Y_INICIAL'] = $pos_ini_y;
      
                     $this->load->view('impresiones/boleta_de_pago',  $parametos );
     
     
                     if($copia_x_pagina == 2)
                     { 
                         $pos_ini_y = IMPRESION_POSY_BOLETA2;
                         $parametos['POSICION_Y_INICIAL'] = $pos_ini_y;
     
                         $this->load->view('impresiones/boleta_de_pago',  $parametos );
                     }
  
             } 
 
             $this->pdf->Output();


        }
        else if($data['documento'] == '2') // Resumen
        {
    
            $this->load->library(array('fpdf','reportes/pdf')); 
            $basepath = dirname(FCPATH);
            $this->load->view('impresiones/resumen_planilla', array( 'planillas' => $planillas  ));  
 

        }
        else if($data['documento'] == '3') // Planilla
        {
            $this->load->library(array('fpdf','reportes/pdf')); 
            $basepath = dirname(FCPATH);
 
            $this->load->view('impresiones/resumen_detallado', array( 'planillas' => $planillas  ));  
 
        }
        else if($data['documento'] == '4') // Resumen agrupado x siaf
        { 
           
            $this->load->library(array('fpdf','reportes/pdf')); 
            $basepath = dirname(FCPATH);
            $siaf = xss_clean(trim($data['siaf']));
            $anio = $data['anio'];
            $this->load->view('impresiones/resumen_agrupado_planillas', array( 'siaf' => $siaf, 'anio' => $anio  ));  

        }
        else if($data['documento'] == '9') // Resumen contable
        { 
             
 
            $this->load->library(array('fpdf','reportes/pdf')); 
            $basepath = dirname(FCPATH);
            $siaf = xss_clean(trim($data['siaf']));
            $anio = $data['anio'];
          
            if(trim($data['siaf']) == ''){

               $this->load->view('impresiones/resumen_contable', array('planillas' => $planillas ));  
            }  
            else{  
 
               $planillas_codigos = '';
               $planillas_siaf    = $this->planilla->get_codigos_by_siaf($anio, $siaf);
            
               foreach($planillas_siaf as $planilla)
               {
                   $planillas[] = $planilla['pla_id'];
                   $planillas_codigos.=" - ".$planilla['pla_codigo'];
               }   

               $this->load->view('impresiones/resumen_contable_siaf', array('siaf' => $siaf, 'anio' => $anio, 'planillas_codigos' => $planillas_codigos ));  
            }  

        }
        else
        {
            die();
        }

    }


    public function resumen_presupuestal_planilla($planilla_key)
    {

       $this->load->library(array('fpdf','reportes/pdf','App/planilla'));
       $basepath = dirname(FCPATH);

       $pla_id = $this->planilla->get_id($planilla_key);

       $tabla_afectacion = $this->planilla->get_afectacion_presupuestal($pla_id);

       $planilla_info = $this->planilla->get($pla_id);       
 
       $this->load->view('impresiones/resumen_presupuestal_planilla', array('tabla_afectacion' => $tabla_afectacion, 'planilla_info' => $planilla_info));  

    }

    public function certificados_trabajo()
    {
  
       $this->load->library(array('App/situacionlaboral'));

       $data = $this->input->post();

       $views = trim($data['views']);
       $id_s =  explode('_', $data['views']);
       array_shift($id_s); 
        
       if(sizeof($id_s) == 0)
       {
          die('No especifico ningun registro de historial laboral');
       }

       $fecha_documento = $data['fechadocumento']; 

       if(validar_fecha_postgres($fecha_documento) == FALSE)
       {
           die('La fecha del documento no es valida.');
       }

 

       $fecha_larga = get_fecha_larga($fecha_documento);
       $anio = date('Y', $fecha_documento );

       $codigo_documento = ( is_numeric($data['nrocertificado']) == FALSE ) ? 1 : ( $data['nrocertificado'] * 1);


       $persla_ids = $this->situacionlaboral->get_multiple_id_persla($id_s);
       
       $detalle_cerficados = $this->situacionlaboral->get_detalle_contrato($persla_ids);
       $c = 1;
      
       // view del certificado  Obtener de la tabla planilla_tipo  

      ob_clean(); 
      $this->load->library(array('fpdf','reportes/pdf'));
      $basepath = dirname(FCPATH);
      $this->load->view('impresiones/certificadostrabajo/certificado_base', array('detalle_cerficados' => $detalle_cerficados, 
                                                                                  'nro_inicio' =>  $codigo_documento, 
                                                                                  'fecha' => $fecha_larga,
                                                                                  'anio' => $anio ));  

    } 


    public function resumen_contable($planilla_key){

       $this->load->library(array('fpdf','reportes/pdf','App/planilla'));
       $basepath = dirname(FCPATH);

       $pla_id = $this->planilla->get_id($planilla_key);

       $tabla_afectacion = $this->planilla->get_afectacion_presupuestal($pla_id);

       $planilla_info = $this->planilla->get($pla_id);       

 
       $this->load->view('impresiones/resumen_contable', array('tabla_afectacion' => $tabla_afectacion, 'planillas' => array($pla_id) ));  

    }


}