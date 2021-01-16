<?php
 
if ( !defined('BASEPATH')) exit('<br/><b>Esta trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class sunatrouter extends CI_Controller
{
    
    public $usuario;
    
    public function __construct()
    {
        parent::__construct();
         
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else
        {
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  

        $this->user->set_keys( $this->usuario['syus_id'] );    

        $this->load->library(array('App/persona','App/planilla','App/tipoplanilla', 'App/anioeje','App/impuesto'));
    }

    public function panel(){

        $this->load->library(array('Catalogos/banco',
                                   'Catalogos/afp'));

        $tipos = $this->tipoplanilla->get_all();
        
        $reportes = array();
        
        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;


        $this->load->view('impuestos/p_sunat_panel', array(
                                                                 'tipos'    => $tipos, 
                                                                 'reportes' => $reportes,
                                                                 'anios'    => $anios
                                                                 ));

    } 


    public function panel_detalle(){


        $this->load->view('impuestos/v_sunat_panel_detalle', array());

    }

    public function get_tregistro_trabajadores($tipo)
    { 
  
        $this->load->library(array('App/reportesunat'));

        $datos = $this->input->get();
        
        $response = array();

        if($tipo == 'altas'){

            $trabajdores_altas = $this->reportesunat->get_altas_mes( array('anio' => $datos['anio'], 'mes' => $datos['mes']) );
             
            $c = 1;
            foreach($trabajdores_altas as $reg)
            {
                 $data = array();
                 $data['id']   = trim($reg['indiv_id']);
                 $data['num'] = $c; 
                 $data['nombre'] = $reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '.$reg['indiv_nombres'];
                 $data['dni'] = $reg['indiv_dni'];
                 $data['tipo'] = $reg['plati_nombre'];
                 $data['fecha'] = _get_date_pg($reg['persla_fechaalta']);
                 $response[] = $data;  
                 $c++;
            }
        
        }
        else if($tipo == 'bajas'){

            $trabajdores_bajas = $this->reportesunat->get_bajas_mes( array('anio' => $datos['anio'], 'mes' => $datos['mes']) );

            $c = 1;
            foreach($trabajdores_bajas as $reg)
            {
                 $data = array();
                 $data['id']   = trim($reg['indiv_id']);
                 $data['num'] = $c; 
                 $data['nombre'] = $reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '.$reg['indiv_nombres'];
                 $data['dni'] = $reg['indiv_dni'];
                 $data['tipo'] = $reg['plati_nombre'];
                 $data['fecha'] = _get_date_pg($reg['persla_fechacese']);
                 $response[] = $data;  
                 $c++;
            }
            
        
        }

        echo json_encode($response);

    }
 

    public function generar_tregistro()
    { 

       $this->load->library(array('App/reportesunat'));
       $this->load->library(array('Zip'));

       ini_set('auto_detect_line_endings', false);
       
       $generado = true;
       $path_files = 'docsmpi/exportar/';

       $data = $this->input->post();

       $usur_key = trim($this->usuario['usur_key']);

       $params = array('mes'  => $data['mes'],
                       'anio' => $data['anio'] );
    
    
       $contenido_txt = '';
    
       $file_zip = 'docsmpi/exportar/TREGISTRO_'.$params['anio'].$params['mes'].'_'.$usur_key.'.zip';

       $nombre_zip = 'TREGISTRO_'.$params['anio'].$params['mes'].'_'.$usur_key.'.zip';



       //  **************************************** ARCHIVOS PARA T-REGISTRO  ***************************************************************************


       // PER BAJAS

       $contenido_txt = '';
       
       $reporte =  $this->reportesunat->per_bajas($params);
       
       $nombre_file =  'RP_'.RUC_INSTITUCION.'_BAJAS.PER';   
       $file_txt = $path_files.$nombre_file;
        

       foreach($reporte as $reg)
       {  
             
           $data = array(  
                           'tipodoc' => PDT_DOCUMENTO_DNI,
                           'dni'     => $reg['indiv_dni'],
                           'pais'    => PDT_PAIS,
                           'CATEGORIA' => '1',
                           'REGIMENESSALUD' => '00',
                           'sctr' => '1'
                          
                        );
       
           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'1'.'||'.$reg['fecha_cese'].'|'.'07'.'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";
       
       }
       

       if(file_put_contents($file_txt, $contenido_txt))
       {   
             $this->zip->read_file($file_txt);   
       } 
       else
       {
            if(sizeof($reporte) > 0)  $generado = false;
       }




       // IDE

       $contenido_txt = '';       
       $reporte =  $this->reportesunat->ide($params);
       
       $nombre_file =  'RP_'.RUC_INSTITUCION.'.IDE';   
       $file_txt = $path_files.$nombre_file;
        
       foreach($reporte as $reg)
       {  
             
           if( trim($reg['indiv_fechanac']) == '')  $reg['indiv_fechanac'] = '1965-09-09';

           list($anio, $mes, $dia ) = explode('-',  $reg['indiv_fechanac'] );
           $fechanac = $dia.'/'.$mes.'/'.$anio;
    
           $data = array(  
                           'tipodoc'        =>  PDT_DOCUMENTO_DNI,
                           'dni'            =>  $reg['indiv_dni'],
                           'pais'           =>  PDT_PAIS,
                           'fechanac'       =>  $fechanac,
                           'paterno'        =>  trim($reg['indiv_appaterno']),
                           'materno'        =>  trim($reg['indiv_apmaterno']),
                           'nombres'        =>  trim($reg['indiv_nombres']),
                           'sexo'           =>  (trim($reg['indiv_sexo']) != '' ? trim($reg['indiv_sexo']) : '1'),
                           'nacionalidad'   =>  PDT_PERUANO 
                        );
        
            
            for($i=1; $i<=14; $i++)
            {
                 $data[('x'.$i)] = '';
            }


            $data['referencia'] =  (trim($reg['indiv_direccion1']) != ''  ? substr(trim($reg['indiv_direccion1']),0,20) : '' );
        

            for($i=1; $i<=16; $i++)
            {
                 $data[('xb'.$i)] = '';
            }

            $data['essalud'] = '1';

            $linea = '';
            foreach ($data as $key => $value)
            {
        
                 $linea.=$value.'|';                
            }

            $contenido_txt.= $linea."\x0D\x0A";

       }
       

       if(file_put_contents($file_txt, $contenido_txt))
       {   
             $this->zip->read_file($file_txt);   
       } 
       else
       {
            if(sizeof($reporte) > 0)  $generado = false;
       }


       // TRA

       $contenido_txt = '';
       $reporte =  $this->reportesunat->tra($params);
       
       $nombre_file =  'RP_'.RUC_INSTITUCION.'.TRA';   
       $file_txt = $path_files.$nombre_file;
         
       foreach($reporte as $reg)
       {  
            
           $data = array(  
                           'tipodoc'              => PDT_DOCUMENTO_DNI,
                           'dni'                  => $reg['indiv_dni'],
                           'pais'                 => PDT_PAIS,
                           'regimen'              => trim($reg['regimenlaboral']),
                           'niveleducativo'       => trim($reg['niveleducativo']),
                           'ocupacion'            => trim($reg['ocupacion']),
                           'discapacidad'         => trim($reg['discapacitado']),
                           'CUSPP'                => (trim($reg['cuspp']) != '' && strpos(trim($reg['cuspp']),'.' ) === FALSE  ? trim($reg['cuspp']) : '' ),
                           'SCTR'                 => (trim($reg['sctr']) != '' ? ($reg['pentip_id']) : ''),
                           'tipocontrato'         => trim($reg['tipocontrato']),
                           'regimenalternativo'   => trim($reg['jornadaatipica']),
                           'jornadatrabajomaxima' => trim($reg['jornadamaxima']),
                           'horarionocturno'      => trim($reg['jornadanocturno']),
                           'sindicalizado'        => trim($reg['sindicalizado']),
                           'periodoremuneracion'  => trim($reg['persla_periodopago']),
                           'montorembasicainicial' => ($reg['persla_montocontrato']=='' ? 0 : trim($reg['persla_montocontrato'])),
                           'situacion'             => '1',
                           'rentaquintaexonerada'  => '0',
                           'situacionespecialtrabajador' => trim($reg['situacionespecial']),
                           'tipopago'             => trim($reg['tipopago']),
                           'categoriaocupacional' => trim($reg['catagoriaocupacional']),
                           'convenioparaevitardobletributacion' => '0',
                           'ruc' => ''   // SOLO CAS 
                        );
       
            $linea = '';
            foreach ($data as $key => $value)
            {
        
                 $linea.=$value.'|';                
            }

            $contenido_txt.= $linea."\x0D\x0A";

       }
       

       if(file_put_contents($file_txt, $contenido_txt))
       {   
             $this->zip->read_file($file_txt);   
       } 
       else
       {
           if(sizeof($reporte) > 0)  $generado = false;
       } 
      

       // PER

       $contenido_txt = '';
        
       $reporte =  $this->reportesunat->per($params);
    
       $nombre_file =  'RP_'.RUC_INSTITUCION.'.PER';   
       $file_txt = $path_files.$nombre_file;
              

       foreach($reporte as $reg)
       {  
          
           $data = array(  
                           'tipodoc' => PDT_DOCUMENTO_DNI,
                           'dni'     => $reg['indiv_dni'],
                           'pais'    => PDT_PAIS,
                           'CATEGORIA' => '1', // trabajador 
                           'REGIMENESSALUD' => $reg['regimensalud'],
                           'sctr' =>  $reg['sctr']
                          
                        );

           $fechaini = '01/'.$params['mes'].'/'.$params['anio'];
       
           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'1'.'|'.$reg['persla_fechaalta'].'|'.''.'|'.''.'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";

           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'2'.'|'.$reg['persla_fechaalta'].'|'.''.'|'.trim($reg['tipotrabajador']).'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";

           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'3'.'|'.$reg['persla_fechaalta'].'|'.''.'|'.trim($data['REGIMENESSALUD']).'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";

           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'4'.'|'.$reg['persla_fechaalta'].'|'.''.'|'.trim($reg['sunat_regimenpensionario']).'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";

       
          if($reg['sctr'] == '1')
           { 
              $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'5'.'|'.$reg['persla_fechaalta'].'|'.''.'|'.trim($data['sctr']).'|'.''.'|';
              $contenido_txt.= $linea."\x0D\x0A";
           }

       }
       

       if(file_put_contents($file_txt, $contenido_txt))
       {   
             $this->zip->read_file($file_txt);   
       } 
       else
       {
            if(sizeof($reporte) > 0)  $generado = false;
       }
 

       // .EST 
       $contenido_txt = '';
       $reporte =  $this->reportesunat->est($params);

       $nombre_file =  'RP_'.RUC_INSTITUCION.'.EST';   
       $file_txt = $path_files.$nombre_file;

       
       foreach($reporte as $reg)
       {
           $linea =  PDT_DOCUMENTO_DNI.'|'.$reg['indiv_dni'].'|'.PDT_PAIS.'|'.RUC_INSTITUCION.'|'.trim($reg['establecimiento']).'|';
           $contenido_txt.= $linea."\x0D\x0A";
       
       }
       

       if(file_put_contents($file_txt, $contenido_txt))
       {   
             $this->zip->read_file($file_txt);   
       } 
       else
       {
           if(sizeof($reporte) > 0)  $generado = false;
       }



       if($generado)
       {
       
          $this->zip->archive($file_zip);
       
       }


    
       $result = array(
                         'result' => ( $generado ? '1' : '0'), 
                         'file'   => ( $generado ? ($nombre_zip) : '' )
                      );

       echo json_encode($result);

    }


    public function generar_plame(){

      $this->load->library(array('App/reportesunat'));
      $this->load->library(array('Zip'));

      ini_set('auto_detect_line_endings', false);
      
      $generado = true;
      $path_files = 'docsmpi/exportar/';

      $data = $this->input->post();

      $usur_key = trim($this->usuario['usur_key']);

      $params = array('mes'  => $data['mes'],
                      'anio' => $data['anio'] );
      
      
      $contenido_txt = '';
      
      $file_zip = 'docsmpi/exportar/PDT'.$params['anio'].$params['mes'].'_'.$usur_key.'.zip';

      $nombre_zip = 'PDT'.$params['anio'].$params['mes'].'_'.$usur_key.'.zip';



      //  **************************************** ARCHIVOS PARA PDT ***************************************************************************


      
       // TAS
       $contenido_txt = '';
       
       $reporte =  $this->reportesunat->tas($params);
       
       $nombre_file =  '0601'.$params['anio'].$params['mes'].RUC_INSTITUCION.'.TAS';   
       $file_txt = $path_files.$nombre_file;

       $sctr = 1;

       foreach($reporte as $reg)
       {
           $linea =  PDT_DOCUMENTO_DNI.'|'.$reg['indiv_dni'].'|'.$sctr.'|'.$reg['tasa_sctr'].'|';
           $contenido_txt.= $linea."\x0D\x0A";
       
       }
       

       if(file_put_contents($file_txt, $contenido_txt))
       {   
             $this->zip->read_file($file_txt);   
       } 
       else
       {
          if(sizeof($reporte) > 0)  $generado = false;
       }



       // TOC
        $contenido_txt = '';
       
        $reporte =  $this->reportesunat->toc($params);

        $nombre_file =  '0601'.$params['anio'].$params['mes'].RUC_INSTITUCION.'.TOC';

        $file_txt = $path_files.$nombre_file;

        $ES_ARTISTA = '';
        $DOMICILIADO = '1';

        foreach($reporte as $reg)
        {
            $linea =  PDT_DOCUMENTO_DNI.'|'.$reg['indiv_dni'].'|'.$reg['asegura_tu_pension'].'|'.$reg['masvida'].'|'.$ES_ARTISTA.'|'.$DOMICILIADO.'|';
            $contenido_txt.= $linea."\x0D\x0A";
        
        }
        

        if(file_put_contents($file_txt, $contenido_txt))
        {   
              $this->zip->read_file($file_txt);   
        } 
        else
        {
           if(sizeof($reporte) > 0)  $generado = false;
        }



      

       // JOR
       $contenido_txt = '';
       
       $reporte =  $this->reportesunat->jor($params);
       
       $nombre_file =  '0601'.$params['anio'].$params['mes'].RUC_INSTITUCION.'.JOR';   
       $file_txt = $path_files.$nombre_file;
       

       $minutos = 0;
       $horas_sobret = 0;
       $minutos_sobret = 0;

       foreach($reporte as $reg)
       {  

           $horas_asistencia = ($reg['jornada_horas'] == '' ? '0' : $reg['jornada_horas']);
           $horas_sobret = ($reg['jornada_horas_extras'] == '' ? '0' : $reg['jornada_horas_extras']);

           $linea =  PDT_DOCUMENTO_DNI.'|'.$reg['indiv_dni'].'|'.$horas_asistencia.'|'.$minutos.'|'.$horas_sobret.'|'.$minutos_sobret.'|';
           $contenido_txt.= $linea."\x0D\x0A";
       
       }
       

       if(file_put_contents($file_txt, $contenido_txt))
       {   
             $this->zip->read_file($file_txt);   
       } 
       else
       {
           if(sizeof($reporte) > 0)  $generado = false;
       }
        

        // REM
       $contenido_txt = '';
       $reporte =  $this->reportesunat->rem($params);

      
        $nombre_file =  '0601'.$params['anio'].$params['mes'].RUC_INSTITUCION.'.rem';   
        $file_txt = $path_files.$nombre_file;

        $conceptos_trabajador = array();

        $dni_actual = '';

        $conceptos_sunat = array();

        foreach($reporte as $reg)
        { 

            if($dni_actual != $reg['dni'])
            {

                if($dni_actual != '')
                {
                 
                    if( $situ['plati_id'] == TIPOPLANILLA_CASFUNC )
                    {

                        if(in_array(CONCEPTOSUNAT_CUARTA, $conceptos_trabajador) == FALSE)
                        {
                           
                           $linea =  '01|'.$dni_actual.'|'.sprintf("%04s",CONCEPTOSUNAT_CUARTA).'|0.00|0.00|';
                           $contenido_txt.= $linea."\x0D\x0A"; 
                        } 
                     
                    }  
                    else
                    {
                        if(in_array(CONCEPTOSUNAT_QUINTA, $conceptos_trabajador) == FALSE)
                        {

                           $linea =  '01|'.$dni_actual.'|'.sprintf("%04s",CONCEPTOSUNAT_QUINTA).'|0.00|0.00|';
                           $contenido_txt.= $linea."\x0D\x0A"; 
                        } 
                    }
      
                }
             
                $dni_actual = $reg['dni'];
                $situ =  $this->persona->get_situacionlaboral($dni_actual, 'dni' );

                $conceptos_trabajador = array();

            }


            $conceptos_trabajador[] = $reg['cosu_codigo'];

            $deposito =sprintf("%01.2f", $reg['monto']);  
            $concepto = sprintf("%04s", $reg['cosu_codigo']);
        
            $linea =  '01|'.$reg['dni'].'|'.$concepto.'|'.$deposito.'|'.$deposito.'|';
        
            $contenido_txt.= $linea."\x0D\x0A";
      
        }


        if( $situ['plati_id'] == TIPOPLANILLA_CASFUNC )
        {

            if(in_array(CONCEPTOSUNAT_CUARTA, $conceptos_trabajador) == FALSE)
            {
               
               $linea =  '01|'.$reg['dni'].'|'.CONCEPTOSUNAT_CUARTA.'|0.00|0.00|';
               $contenido_txt.= $linea."\x0D\x0A"; 
            } 
         
        }  
        else
        {
            if(in_array(CONCEPTOSUNAT_QUINTA, $conceptos_trabajador) == FALSE)
            {

               $linea =  '01|'.$reg['dni'].'|'.CONCEPTOSUNAT_QUINTA.'|0.00|0.00|';
               $contenido_txt.= $linea."\x0D\x0A"; 
            } 
        }
        

        if(file_put_contents($file_txt, $contenido_txt))
        {   
              $this->zip->read_file($file_txt);   
        } 
        else
        {
            if(sizeof($reporte) > 0)  $generado = false;
        }
      
      

        //PEN 
      
      // $contenido_txt = '';
       
      //   $p_params = $params;
      //   $p_params['tipoplanilla'] = TIPOPLANILLA_PENSIONSITAS;

      //   $reporte =  $this->reportesunat->rem($p_params);

        
      //   $nombre_file =  '0601'.$params['anio'].$params['mes'].RUC_INSTITUCION.'.pen';   
      //   $file_txt = $path_files.$nombre_file;
      //   $x=1;
      //   foreach($reporte as $reg)
      //   { 

      //       $deposito =sprintf("%01.2f", $reg['monto']);  
      //       $concepto = sprintf("%04s", $reg['cosu_codigo']);
        
      //       $linea =  '01|'.$reg['dni'].'|'.$concepto.'|'.$deposito.'|'.$deposito.'|';
        
      //       $contenido_txt.= $linea."\x0D\x0A";
             
          

      //        $xx++;
      //   }
        
      //   if(file_put_contents($file_txt, $contenido_txt))
      //   {   
      //         $this->zip->read_file($file_txt);   
      //   } 
      //   else
      //   {
      //      if(sizeof($reporte) > 0)  $generado = false;
      //   }



        // Trabajadores con_multiple regimen

        $contenido_txt = ''; 
        
        $reporte =  $this->reportesunat->multiple_regimen($params);

        if(sizeof($reporte) > 0)
        { 
          $nombre_file =  '0601'.$params['anio'].$params['mes'].RUC_INSTITUCION.'_observaciones.txt';   
          $file_txt = $path_files.$nombre_file;
          $x=1;
          $linea =  'Trabajadores con varios regimenes en el mes';
          $contenido_txt.= $linea."\x0D\x0A\x0D\x0A";
          

          foreach($reporte as $reg)
          { 
             
              $nombre_completo = str_replace('Ñ','N', trim($reg['trabajador']) );

              $nombre  = sprintf("%-50s",substr($nombre_completo,0,50) );

              $linea =   $reg['indiv_dni'].' '.$nombre.' '.$reg['plati_nombre'];
              $contenido_txt.= $linea."\x0D\x0A";
      
              $xx++;
          }
          
          if(file_put_contents($file_txt, $contenido_txt))
          {   
                $this->zip->read_file($file_txt);   
          }
        }  


        if($generado)
        {
        
           $this->zip->archive($file_zip);
        
        }


        
        $result = array(
                          'result' => ( $generado ? '1' : '0'), 
                          'file'   => ( $generado ? ($nombre_zip) : '' )
                       );

        echo json_encode($result);


    }


    public function generar_consolidados(){

        $this->load->library(array('App/reportesunat'));
        $this->load->library(array('Zip'));

        ini_set('auto_detect_line_endings', false);
        
        $generado = true;
        $path_files = 'docsmpi/exportar/';

        $data = $this->input->post();

        $usur_key = trim($this->usuario['usur_key']);

        $params = array('mes'  => $data['mes'],
                        'anio' => $data['anio'] );
        
        $params['mes'] = str_pad($data['mes'], 2, '0',STR_PAD_LEFT);
        //var_dump($params);
        
        $contenido_txt = ''; 
  
        $nombre_file = "CONSOLIDADOS_SUNAT_".$params['mes'];

        
        $reporte_info['anio'] = $params['anio'];
        $reporte_info['mes'] = $params['mes'];
         /*GENERANDO XLS */

         $file_xls = $path_files.'SUNAT_'.$mes.'_PORTRABAJADOR.xls';

         $reporte = $this->reportesunat->consolidado_sunat_por_trabajador($params);

         $this->load->view('exportar/sunat_xls_2',  array('file_xls' => $file_xls,
                                                         'reporte_info' => $reporte_info, 
                                                         'reporte' => $reporte) ); 
         
         $this->zip->read_file($file_xls); 


         // $file_xls = $path_files.'SUNAT_'.$mes.'_PORPLANILLA.xls'; 
        
         // $reporte = $this->reportesunat->consolidado_sunat_por_planilla($params);

         // $this->load->view('exportar/sunat_xls_3',  array('file_xls' => $file_xls,
         //                                                 'reporte_info' => $reporte_info, 
         //                                                 'reporte' => $reporte) ); 
         
         // $this->zip->read_file($file_xls); 
         

         $generado = true;


           $file_zip = $path_files.$nombre_file.'.zip';
           $this->zip->archive($file_zip);

         
         
         $result = array(
                           'result' => ( $generado ? '1' : '0'), 
                           'file'   => ( $generado ? ($nombre_file.'.zip') : '' )
                        );

         echo json_encode($result);

    }



}