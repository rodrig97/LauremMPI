<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');
  
class exportar extends CI_Controller{
     
    public $usuario;
 
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

        $this->user->set_keys( $this->usuario['syus_id'] );   

        
        $this->load->library(array( 'App/exportador', 'App/persona','App/planilla','App/tipoplanilla', 'App/anioeje'));
    }

  

    public function exportacion_y_reportes()
    {

         $this->load->library(array('Catalogos/banco',
                                    'Catalogos/afp'));

         $tipos = $this->tipoplanilla->get_all();
         
         $reportes = array();
         
         $reportes['SUNAT']  = $this->exportador->getReportes(REPORTETIPO_SUNAT);   
         $reportes['OTROS']  = $this->exportador->getReportes(REPORTETIPO_OTROS);  
         $reportes['SIAF']   = $this->exportador->getReportes(REPORTETIPO_SIAF);   
         $reportes['BANCOS'] = $this->banco->get_list();   
         $reportes['AFPS']   = $this->afp->get_list();   

         $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;


         $this->load->view('planillas/p_planilla_exportador', array(
                                                                  'tipos'    => $tipos, 
                                                                  'reportes' => $reportes,
                                                                  'anios'    => $anios
                                                                  ));
    }
  

    public function view($tipo)
    {
 
         $this->load->library(array('App/exportador',
                                    'App/tipoplanilla',
                                    'Catalogos/banco',
                                    'Catalogos/afp',
                                    'App/reporte', 
                                    'App/planilla'));

         $_MESES = array( 
                             '01' => 'ENERO',
                             '02' => 'FEBRERO',
                             '03' => 'MARZO',
                             '04' => 'ABRIL',
                             '05' => 'MAYO',
                             '06' => 'JUNIO',
                             '07' => 'JULIO',
                             '08' => 'AGOSTO',
                             '09' => 'SEPTIEMBRE',
                             '10' => 'OCTUBRE',
                             '11' => 'NOVIEMBRE',
                             '12' => 'DICIEMBRE'
                         );

         $data = $this->input->post();

         $reporte_info  = array();
         $params_modelo = array();
         $planillas_id  = array();

         $params_modelo['anio'] = $data['anio'];
         $params_modelo['mes']  = $data['mes'];
         
         $reporte_info['anio']  = $data['anio'];
         $reporte_info['mes']   = $_MESES[$params_modelo['mes']];
         $reporte_info['planillas_codigos'] = array();
 

         if(trim($this->input->post('planillas')) != '')
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
                 $reporte_info['planillas_codigos'][] = $planilla['pla_codigo'];
             }    

             $params_modelo['planillas'] = $planillas;
 

             $reporte_info['planillas'] = $planillas_codigos;
             $reporte_info['total_trabajadores'] = $this->planilla->contar_trabajadores($planillas);

         }
         else
         {

            $params_modelo['planillas'] = array();

            if( trim($this->input->post('tipoplanilla')) != '0' && trim($this->input->post('tipoplanilla')) != '' )
            {
               
               $plati_id = $this->tipoplanilla->get_id( trim($this->input->post('tipoplanilla')) );

               if($plati_id != '') $params_modelo['plati'] = array( $plati_id);
               
               $plati_info = $this->tipoplanilla->get($plati_id);
 
               $reporte_info['plati'] = trim($plati_info['plati_nombre']);
              
            }
            else
            {
               $params_modelo['plati'] = array(); 
            }
 

            $tipogasto = trim( $this->input->post('tipogasto') );  
 
            if( $tipogasto != '0' && $tipogasto != '')
            { 

                $sql = "  SELECT plati_id 
                          FROM planillas.planilla_tipo 
                          WHERE plati_estado = 1 AND plati_afp_tipo = ? ";
                
                $rs = $this->db->query($sql, array($tipogasto) )->result_array();

                $platis = array(); 
                
                foreach($rs as $reg)
                {
                    $platis[] = $reg['plati_id'];
                } 

                $params_modelo['plati'] = $platis;
                $reporte_info['plati'] =  '';
 
                 
                $tipos_gasto = array('F' =>  'Funcionamiento', 'I' => 'Inversiones'); 
                $reporte_info['tipogasto'] = $tipos_gasto[$tipogasto]; 
                
                
            }



            $planillas = $this->exportador->find_planillas( array(   'anio'   => $data['anio'], 
                                                                     'mes'    => $data['mes'],
                                                                     'plati'  => $params_modelo['plati'] ) );
 

             foreach($planillas as $planilla)
             {
                 $planillas_id[] = $planilla['pla_id'];
                 $planillas_codigos.=" - ".$planilla['planilla_codigo'];
                 $planillas_keys[] = $planilla['pla_key']; 
                 $reporte_info['planillas_codigos'][] = $planilla['planilla_codigo'];

             }    
            
             $planillas_keys_txt = implode('_',  $planillas_keys );

             $params_modelo['planillas'] = $planillas_id;
             $reporte_info['planillas'] = $planillas_codigos;
         }
 

         // REPORTE DE BANCO
         if($data['modo'] == 'BANCO')
         {

                $params_modelo['banco'] = $data['banco'];     
 
                $reporte =  $this->exportador->bancos($params_modelo); // GENERAMOS EL REPORTE
                $b_rs    =  $this->banco->get($data['banco']); // OBTENEMOS EL NOMBRE DEL BANCO

                $reporte_info['banco'] = $b_rs[0]['ebanco_nombre'];
  
                $this->load->view('planillas/exporter/v_por_banco', array(  
                                                                         'datos_post'   => $data,
                                                                         'reporte'      => $reporte, 
                                                                         'reporte_info' => $reporte_info, 
                                                                         'params'       => $data 
                                                                          ));

         }


         if($data['modo'] == 'AFP')
         {
  
                 $params_modelo['afp']       = $data['afp'];
 
                 $afp                        = $this->afp->get( $data['afp']);
                 $reporte_info['afp_nombre'] = $afp[0]['afp_nombre'];
 
                 $reporte = $this->exportador->afp($params_modelo);


                 if($data['tipogasto'] != '0')
                 {

                    $tipo_gasto = trim($data['tipogasto']);
                 }
                 else
                 {
                     $tipo_gasto = '';
                 }

                 $this->load->view('planillas/exporter/v_por_afp', array( 
                                                                          'datos_post'   => $data,
                                                                          'reporte'      => $reporte, 
                                                                          'reporte_info' => $reporte_info, 
                                                                          'params'       => $data,
                                                                          'tipogasto'    => $tipo_gasto 
                                                                         ));
   
         }


         if($data['modo'] == 'SUNAT' || $data['modo'] == 'OTROS' ||  $data['modo'] == 'SIAF')
         {
               
               $reporte_id = $this->input->post('tiporeporte');

               list($tipo_reporte) = $this->reporte->get($this->input->post('tiporeporte'));

               if( trim($tipo_reporte['rep_model']) != '')
               {
                    
                    $reporte_info['reporte_nombre'] = $tipo_reporte['rep_nombre'];

                    $metodo = $tipo_reporte['rep_model'];
                    
                    if( sizeof($params_modelo['planillas']) == 0) die('Debe especificar una planilla');
                  
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

                    
                    $estructura = array('Fuente' => 'fuente_financiamiento');
                     
                    if($w_planilla)
                    {
                        $estructura['Planilla'] = 'pla_codigo'; 
                    }
                    
                    $estructura['Clasificador'] = 'clasificador_codigo'; 
                    $estructura['Meta']         = 'sec_func'; 

                    if($w_tarea)
                    {
                        $estructura['Tarea'] = 'tarea_nro'; 
                    }

                     $estructura['Total']               = 'total'; 

                     $estructura['Meta_nombre']         = 'meta_nombre'; 
                     $estructura['Clasificador_nombre'] = 'clasificador_nombre'; 




                    // LLamamos a la vista
                    $this->load->view( $tipo_reporte['rep_view'], array(   'datos_post'   =>    $data,
                                                                           'reporte'            => $reporte, 
                                                                           'reporte_info'       => $reporte_info,  
                                                                           'params'             => $data,
                                                                           'estructura'         => $estructura,
                                                                           'planillas_keys_txt' => $planillas_keys_txt,
                                                                           'reporte_id'         => $reporte_id
                                                                         )); 
                    
               } 
               else
               {

                    echo ' <span class="sp12b"> Este reporte no esta implementado aun </span> ';
               }
         
              
         } 
 

    }


    public function generar_archivo()
    {

        header("Content-Type: application/json");
        
        $this->load->library(array('Zip'));
 
        $this->load->library(array('App/exportador',
                                   'App/tipoplanilla',
                                   'Catalogos/banco',
                                   'Catalogos/afp',
                                   'App/reporte', 
                                   'App/planilla'));
  
 

        $_MESES = array( 
                            '01' => 'ENERO',
                            '02' => 'FEBRERO',
                            '03' => 'MARZO',
                            '04' => 'ABRIL',
                            '05' => 'MAYO',
                            '06' => 'JUNIO',
                            '07' => 'JULIO',
                            '08' => 'AGOSTO',
                            '09' => 'SEPTIEMBRE',
                            '10' => 'OCTUBRE',
                            '11' => 'NOVIEMBRE',
                            '12' => 'DICIEMBRE'
                        );

 
        $data = $this->input->post();
         
        $reporte_info  = array();
        $params_modelo = array();
        $planillas     = array();

        $params_modelo['anio'] = $data['anio'];
        $params_modelo['mes']  = $data['mes'];
        
        $reporte_info['anio']  = $data['anio'];
        $reporte_info['mes']   = $_MESES[$params_modelo['mes']];
 
    
        if(trim($this->input->post('planillas')) != '')
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

            $params_modelo['planillas'] = $planillas;

            $reporte_info['planillas'] = $planillas_codigos;
            $reporte_info['total_trabajadores'] = $this->planilla->contar_trabajadores($planillas);

        }
        else
        {

           $params_modelo['planillas'] = array();

           if( trim($this->input->post('tipoplanilla')) != '0' && trim($this->input->post('tipoplanilla')) != '' )
           {
              
              $plati_id = $this->tipoplanilla->get_id( trim($this->input->post('tipoplanilla')) );

              if($plati_id != '') $params_modelo['plati'] = array( $plati_id);
              
              $plati_info = $this->tipoplanilla->get($plati_id);
        
              $reporte_info['plati'] = trim($plati_info['plati_nombre']);
             
           }
           else
           {
              $params_modelo['plati'] = array(); 
           }
        

           $tipogasto = trim( $this->input->post('tipogasto') );  
        
           if( $tipogasto != '0' && $tipogasto != '')
           { 

               $sql = "  SELECT plati_id 
                         FROM planillas.planilla_tipo 
                         WHERE plati_estado = 1 AND plati_afp_tipo = ? ";
               
               $rs = $this->db->query($sql, array($tipogasto) )->result_array();

               $platis = array(); 
               
               foreach($rs as $reg)
               {
                   $platis[] = $reg['plati_id'];
               } 

               $params_modelo['plati'] = $platis;
               $reporte_info['plati'] =  '';
        
                
               $tipos_gasto = array('F' =>  'Funcionamiento', 'I' => 'Inversiones'); 
               $reporte_info['tipogasto'] = $tipos_gasto[$tipogasto]; 
               
               
           }



           $planillas = $this->exportador->find_planillas( array(   'anio'   => $data['anio'], 
                                                                    'mes'    => $data['mes'],
                                                                    'plati'  => $params_modelo['plati'] ) );
        

            foreach($planillas as $planilla)
            {
                $planillas_id[] = $planilla['pla_id'];
                $planillas_codigos.=" - ".$planilla['planilla_codigo'];
            }    

            $params_modelo['planillas'] = $planillas_id;
            $reporte_info['planillas'] = $planillas_codigos;
        }

  
        $path_files = 'docsmpi/exportar/';
        $path_files_tmp = 'docsmpi/exportar/temp/';


        if($data['generar'] == 'SUNAT')
        {

            $reporte_id = $data['tiporeporte'];
            $anio = $reporte_info['anio'];
            $mes = $reporte_info['mes'];
            $usur_key = trim($this->usuario['usur_key']);

            list($tipo_reporte) = $this->reporte->get($reporte_id);

           // var_dump($tipo_reporte);

            if( trim($tipo_reporte['rep_model']) != '')
            {
                 
                 $reporte_info['reporte_nombre'] = $tipo_reporte['rep_nombre'];

                 $metodo = $tipo_reporte['rep_model'];
                 
                 if( sizeof($params_modelo['planillas']) == 0) die('Debe especificar una planilla');
               
                 $reporte = call_user_func_array( array($this->exportador, $metodo ), 
                                                  array($params_modelo) ); 
                 


                 $prefijo = trim($tipo_reporte['rep_nombre_corto']);

                 $vista_xls = trim($tipo_reporte['rep_xls']);
                 $vista_pdf = trim($tipo_reporte['rep_pdf']);


                  

                    if( $tipo_reporte['rep_id'] == '7' || $tipo_reporte['rep_id'] == '8')
                    {
    


                          
                          $nombre_file =  '0601'.$anio.$params_modelo['mes'].RUC_INSTITUCION;  

                          $generado = false;

                          if($tipo_reporte['rep_id'] == '7')
                          { 
                            $file_txt = $path_files.$nombre_file.'.rem';
                          }


                          if($tipo_reporte['rep_id'] == '8')
                          { 
                            $file_txt = $path_files.$nombre_file.'.pen';
                          }

                          ini_set('auto_detect_line_endings', false);
                          
                          foreach($reporte as $reg)
                          {
                              
                             
                              $deposito =sprintf("%01.2f", $reg['monto']);  

                              $concepto = sprintf("%04s", $reg['cosu_codigo']);

         
                              $linea =  '01|'.$reg['dni'].'|'.$concepto.'|'.$deposito.'|'.$deposito.'|';
                             
                          
                              $contenido_txt.= $linea."\r\n";
                          
                          }
                          

                          if(file_put_contents($file_txt, "\xEF\xBB\xBF".$contenido_txt))
                          {   
                              $txt_generado = true;
                          } 
                          else
                          {
                              $txt_generado = false;
                          }
                            
                          
                          if($txt_generado) 
                          {
                              $generado = true;
                              $this->zip->read_file($file_txt);    
                          }

                       

                   }
                   else
                   {


                       $nombre_file = $mes."_CONSOLIDADOS_SUNAT";

                      /*  $file_pdf = $path_files.$nombre_file.'.pdf';

                        $this->load->view('exportar/banco_pdf_1', array('file_pdf' => $file_pdf,
                                                                        'reporte_info' => $reporte_info, 
                                                                        'reporte' => $reporte) );

                        $this->zip->read_file($file_pdf);  */

                        /*GENERANDO XLS */

                        $file_xls = $path_files.'SUNAT_'.$mes.'_PORTRABAJADOR.xls';


                        $this->load->view('exportar/sunat_xls_2',  array('file_xls' => $file_xls,
                                                                        'reporte_info' => $reporte_info, 
                                                                        'reporte' => $reporte) ); 
                        
                        $this->zip->read_file($file_xls); 


                        $file_xls = $path_files.'SUNAT_'.$mes.'_PORPLANILLA.xls'; 
                        $reporte = call_user_func_array( array($this->exportador, 'consolidado_sunat_por_planilla' ), 
                                                         array($params_modelo) ); 


                        $this->load->view('exportar/sunat_xls_3',  array('file_xls' => $file_xls,
                                                                        'reporte_info' => $reporte_info, 
                                                                        'reporte' => $reporte) ); 
                        
                        $this->zip->read_file($file_xls); 
                        

                        $generado = true;


                   }

                   $file_zip = $path_files.$nombre_file.'.zip';
                   $this->zip->archive($file_zip);

 
            
                 $result = array(
                                   'result' => ( $generado ? '1' : '0'), 
                                   'file'   => ( $generado ? ($nombre_file.'.zip') : '' )
                                );
 

            } 
            else
            {

                 echo ' <span class="sp12b"> Este reporte no esta implementado aun </span> ';
            }
            
        }

        // REPORTE DE BANCO
        if($data['generar'] == 'BANCO')
        { 
          
          

           $params_modelo['banco'] = $data['banco'];     

           $reporte =  $this->exportador->bancos($params_modelo); // GENERAMOS EL REPORTE
           $b_rs    =  $this->banco->get($data['banco']); // OBTENEMOS EL NOMBRE DEL BANCO

           $reporte_info['banco'] = $b_rs[0]['ebanco_nombre'];
          

           $banco_nombre = str_replace(" ", "", trim($reporte_info['banco']));
           $mes = $reporte_info['mes'];
           $usur_key = trim($this->usuario['usur_key']);
          
           $nombre_file = $mes."_".$banco_nombre."_".$usur_key;
          

           $this->load->helper('file');

           $txt_generado = false;
           $txt_generarse = true;
          
           if($data['banco'] == BANCO_INTERBANK )
           {  
             

            /*
                  REPLACE pla.tipocta WITH '02'
                  REPLACE pla.dni WITH albi.dni
                  REPLACE pla.moneda WITH '01'
                  REPLACE pla.monto WITH RIGHT('000000000000000' + (ALLTRIM(STR(INT(albi.neto))) + RIGHT(ALLTRIM(TRANSFORM(albi.neto,'9999999.99')),2)), 15)
                  REPLACE pla.tabono WITH '009'
                  REPLACE pla.tipo WITH '002'
                  REPLACE pla.monecta WITH '01'
                  REPLACE pla.tienda WITH '341'
                  REPLACE pla.nrocta WITH SUBSTR(albi.cuenta,4,14)
                  REPLACE pla.codigo WITH 'P01'
                  REPLACE pla.dni2 WITH albi.dni
                  REPLACE pla.apno WITH ALLTRIM(albi.apno) +';'
                  REPLACE pla.mate WITH ALLTRIM(albi.mate) +';'
                  REPLACE pla.nombre WITH ALLTRIM(albi.nombres) + ';'
                  REPLACE pla.relleno WITH '01000000000000000'
            */
          
                foreach($reporte as $reg)
                {
                    
                    $tipocta = '02';
                    $dni     = $reg['indiv_dni'];    
                    $moneda  = '01';

                    $deposito = explode('.',sprintf("%01.2f", $reg['deposito']));
                 
                    $importe = sprintf("%015s", ($deposito[0].$deposito[1] ) );
                     
                    $tabono  = '009';
                    $tipo    = '002';
                    $monecta = '01';
                    $tienda  = '341';
                    $nrocta  = substr(trim($reg['pecd_cuentabancaria']),3,10);
                    $codigo  = 'P01';
                    $dni_2   = trim($reg['indiv_dni']);
                    $paterno = sprintf("%-20s",substr(trim($reg['indiv_appaterno']),0,19) );
                    $materno = sprintf("%-20s",substr(trim($reg['indiv_apmaterno']),0,19) );                      
                    $nombres = sprintf("%-20s",substr(trim($reg['indiv_nombres']),0,19) );
                    $relleno = '01000000000000000';
          
                    $linea =  $tipocta.$dni."                                         ".$moneda.$importe.$tabono.$tipo.$monecta.$tienda.$nrocta."          ".$codigo.$dni_2."       ".$paterno.$materno.$nombres.$relleno;

                    $file_txt = $path_files.$nombre_file.'.txt';

                    $contenido_txt.= $linea."\x0D\x0A";
          

                }
          
            /*    if(write_file( $file_txt, $contenido_txt, 'w+b'))
                {   
                    $txt_generado = true;
                } 
                else
                {
                    $txt_generado = false;
                     
                } */

                if(file_put_contents($file_txt, $contenido_txt))
                {   
                    $txt_generado = true;
                } 
                else
                {
                    $txt_generado = false;
                }


          
           }


           if($data['banco'] == BANCO_SCOTIABANK )
           {  
          

            

            /*
                  REPLACE albi.planilla WITH pla100.plan      *
                  REPLACE albi.sema WITH thisform.text4.value * 
                  REPLACE albi.rucemp WITH '2015449187320'
                  REPLACE albi.codi WITH pla100.codi
                  REPLACE albi.nomb WITH ALLTRIM(pla005.apno)+" " +ALLTRIM(pla005.mate)+" " +ALLTRIM(pla005.nomb)
                  REPLACE albi.situ WITH '1'
                  REPLACE albi.impor1 WITH '        0.00'
                  REPLACE albi.impor2 WITH RIGHT(SPACE(12)+ALLTRIM(STR(pla100.neto,12,2)),12)
                  REPLACE albi.impor3 WITH '0000000.00'
                  REPLACE albi.impor4 WITH '0000000.00'
                  REPLACE albi.impor5 WITH '0000000.00'
                  REPLACE albi.impor6 WITH '0000000.00'
                  REPLACE albi.impor7 WITH '0000000.00'
                  REPLACE albi.cta WITH pla005.cta
                  REPLACE albi.mod WITH '5'
                  REPLACE albi.ctainst WITH '010004642961'
                  REPLACE albi.tipdoc WITH '01'
                  REPLACE albi.dni WITH pla005.elec
                  REPLACE albi.tesore WITH 'ROSA ARRIVASPLATA CUEVA'
                  REPLACE albi.dia WITH RIGHT(DTOS(DATE()),2)
                  REPLACE albi.mes WITH SUBSTR(DTOS(DATE()),5,2)
                  REPLACE albi.anno WITH LEFT(DTOS(DATE()),4)
                  REPLACE albi.fue WITH pla100.fue1
                  REPLACE albi.tar1 WITH pla100.tar1
                  suma = suma + pla100.neto
            */

                $codigo = 0;

                foreach($reporte as $reg)
                {
                    $codigo++;

                    $codigo_g = sprintf("%05s",$codigo);  

                    $rucmpi    = '2015449187320';
                    $dni       = $reg['indiv_dni'];   
                    $situ      = '1';


                    $paterno   =  ((trim($reg['indiv_appaterno'])));
                    $materno   =  ((trim($reg['indiv_apmaterno'])));
                    $nombres   =  ((trim($reg['indiv_nombres'])));

                    $nombre_completo = $paterno." ".$materno." ".$nombres;

                    $nombre_completo = str_replace('Ñ','N', utf8_decode($nombre_completo));

                    $moneda    = '01';
                    
                    $t         = explode(".", $reg['deposito']);
                    $monto     = '000000000000000'.$t[0].$t[1];

                    $deposito = sprintf("%01.2f", $reg['deposito']);

                    $importe = sprintf("%012s", $deposito);

                    $importe1  = '0000000.00';
                    $importe2  = '0000000.00';
                    $importe3  = '0000000.00';
                    $importe4  = '0000000.00';
                    $importe5  = '0000000.00';
                    $importe6  = '0000000.00';
                    $importe7  = '0000000.00';   

                    $tabono    = '009';
                    $tipo      = '002';
                    $monecta   = '01';
                   
                    $cta       = trim($reg['pecd_cuentabancaria']);
                    $mod       = '5';
                    $ctainst   = '010004642961';
                    $tipdoc    = '01'; 
                    $dni_2     = $reg['indiv_dni'];     
                    $teso      = 'ROSA ARRIVASPLATA C.';

                    $dia       = date('d');
                    $mes       = date('m');
                    $anio      = date('Y'); 
            

                    $linea = $rucmpi."          ".$codigo_g."     ".sprintf("%-30s",substr($nombre_completo,0,29)).$situ.sprintf("%12s", '0.00').$importe.$importe1.$importe2.$importe3.$importe4.$importe5."".$cta."                  ".$mod.$ctainst."  ".$tipdoc."".$dni."    ".$teso."          ".$dia.$mes.$anio;

                    $file_txt = $path_files.$nombre_file.'.txt';

                   // $contenido_txt.= $linea.PHP_EOL; //.chr(13).chr(10); //."\r\n";
                    $contenido_txt.= $linea."\x0D\x0A";

                }
            
            /*    if(write_file( $file_txt, $contenido_txt, 'w+b'))
                {   
                    $txt_generado = true;
                } 
                else
                {
                    $txt_generado = false;
                     
                } */

                if(file_put_contents($file_txt, $contenido_txt))
                {   
                    $txt_generado = true;
                } 
                else
                {
                    $txt_generado = false;
                }


            

           
           }


           if($data['banco'] == BANCO_NACION )
           {  
              
              
              if(BANCO_NACION_DBF == true)
              { 
                    
                      $base_dbf = $path_files.'abonosiaf.dbf';
                        $def = array(
                          array("NUM_CTA",     "C", 50),
                          array("TIPO_DOC",     "C", 5),
                          array("NUM_DOC",      "C", 8),
                          array("MONTO",    "N", 8,2),
                          array("ESTADO", "C", 1)
                        );


                          unlink($base_dbf);

                        // creación
                        if (!dbase_create($base_dbf, $def)) {
                          echo "Error, no se puede crear la base de datos\n";
                        }
                        else{ 
                    
                        
                           $db = dbase_open($base_dbf, 2);
                           
                           if($db)
                           {
                           
                                 foreach($reporte as $reg)
                                 {

                                     //$cuenta = '0'.substr(trim($reg['pecd_cuentabancaria']),0,10);

                                     $cuenta = str_replace('-', '', trim($reg['pecd_cuentabancaria']));
                                     if(substr($cuenta,0,1) == '0') $cuenta = substr($cuenta, 1, strlen($cuenta));
                                     $cuenta = '0'.substr($cuenta,0,10);


                                     $importe = sprintf("%01.2f", $reg['deposito']);
                                      
                                     dbase_add_record($db, array(
                                           $cuenta, 
                                           '01', 
                                           $reg['indiv_dni'], 
                                            $importe,
                                           'I'
                                     ));   
                                
                                 }
                                
                                 dbase_close($db);
                            }
                        }

                        $this->zip->read_file($base_dbf);    
                    
                    $txt_generarse = false;
                    $txt_generado = false;


            
              }
              

              if(BANCO_NACION_TXT == true)
              { 

                  ini_set('auto_detect_line_endings', false);

                  $file_txt = $path_files.$nombre_file.'.txt';
                  

                  foreach($reporte as $reg)
                  {
                      
                      $digito = '0';

                      $cuenta = str_replace('-', '', trim($reg['pecd_cuentabancaria']));
                      if(substr($cuenta,0,1) == '0') $cuenta = substr($cuenta, 1, strlen($cuenta));
                      $cuenta = substr($cuenta,0,10);

                      $deposito = explode('.',sprintf("%01.2f", $reg['deposito']));

                      $importe = sprintf("%013s", $deposito[0]).$deposito[1];
                  
                      $linea =  $digito.$cuenta.$importe;
                    
                      $contenido_txt.= $linea."\r\n";
                  
                  }
                  
                  if(file_put_contents($file_txt, "\xEF\xBB\xBF".$contenido_txt))
                  {   
                      $txt_generado = true;
                  } 
                  else
                  {
                      $txt_generado = false;
                  }
                  
              }

            
           }    


           if($data['banco'] == BANCO_CAJAAQP )
           {  

                $txt_generarse = false;
                $txt_generado = false;

           }
          
          
          if($txt_generado) 
          {

              $this->zip->read_file($file_txt);    
          }

          /*GENERANDO PDF*/

          $file_pdf = $path_files.$nombre_file.'.pdf';

          $this->load->view('exportar/banco_pdf_1', array('file_pdf' => $file_pdf,
                                                          'reporte_info' => $reporte_info, 
                                                          'reporte' => $reporte) );

          $this->zip->read_file($file_pdf); 


          /*GENERANDO XLS */

          $file_xls = $path_files.$nombre_file.'.xls';


          $this->load->view('exportar/banco_xls_1', array('file_xls' => $file_xls,
                                                          'reporte_info' => $reporte_info, 
                                                          'reporte' => $reporte) );
          
          $this->zip->read_file($file_xls); 


          $file_zip = $path_files.$nombre_file.'.zip';
          $this->zip->archive($file_zip);


          $result = array(
                        'result' => ( ($txt_generado || $txt_generarse == FALSE) ? '1' : '0'), 
                        'file'   => ( ($txt_generado || $txt_generarse == FALSE) ? ($nombre_file.'.zip') : '' )
                      );
          
          
          
        }




        if(trim($data['generar']) == 'AFP')
        {    

           $params_modelo['afp']  = $data['afp'];

           if($data['tipogasto'] != '0')
           {

              $tipo_gasto = trim($data['tipogasto']);
           }
           else
           {
               $tipo_gasto = '';
           }
        
           $params_modelo['tipogasto'] = $tipo_gasto;
            

           $afp                        = $this->afp->get( $data['afp']);
           $reporte_info['afp_nombre'] = $afp[0]['afp_nombre'];


           $afp_nombre = str_replace(" ", "", trim($reporte_info['afp_nombre']));
           $mes = $reporte_info['mes'];
           $usur_key = trim($this->usuario['usur_key']);
           

           $nombre_file = $mes."_AFP_".$afp_nombre."_".$usur_key;



           $reporte = $this->exportador->afp($params_modelo);
  

           $file_pdf = $path_files.$nombre_file.'.pdf';

           $this->load->view('exportar/afp_pdf_1', array( 
                                                          'file_pdf' => $file_pdf, 
                                                          'reporte_info' => $reporte_info, 
                                                          'reporte' => $reporte 
                                                          ));
       

           $file_xls = $path_files.$nombre_file.'.xls';
  
           $this->load->view('exportar/afp_xls_1', array( 
                                                          'file_xls' => $file_xls, 
                                                          'reporte_info' => $reporte_info, 
                                                          'reporte' => $reporte 
                                                          ));

 
           $file_xls_2 = $path_files.$nombre_file.'_importar.xls';
 
           $this->load->view('exportar/afp_xls_2', array( 
                                                          'file_xls_2' => $file_xls_2, 
                                                          'reporte_info' => $reporte_info, 
                                                          'reporte' => $reporte 
                                                          )); 
 
 
           $this->zip->read_file($file_pdf); 
           $this->zip->read_file($file_xls); 
           $this->zip->read_file($file_xls_2); 
 
           $file_zip = $path_files.$nombre_file.'.zip';
           $this->zip->archive($file_zip);


          $result = array(
  
                       'result' => ( true ? '1' : '0'), 
                       'file'   => ( true ? ($nombre_file.'.zip') : '' )
  
                     );




        }     


        if(trim($data['generar']) == 'DESCUENTOS')
        {     
 
            $mes = $reporte_info['mes'];
            $usur_key = trim($this->usuario['usur_key']);

            $nombre_file = $mes."_DESCUENTOS_".$usur_key;
  
            $file_xls = $path_files.$nombre_file.'.xls';
  
            $this->load->library(array('App/grupovc'));

            $grupos = $this->grupovc->get_descuentos();
 
            $this->load->view('exportar/descuentos_xls_1', array('file_xls' => $file_xls,
                                                                 'reporte_info' => $reporte_info, 
                                                                 'grupos' => $grupos,
                                                                 'params_modelo'   => $params_modelo

                                                                 ) );
 
          
           // $this->zip->read_file($file_pdf); 
            $this->zip->read_file($file_xls); 


            $file_zip = $path_files.$nombre_file.'.zip';
            $this->zip->archive($file_zip);


            $result = array(
                              'result' => ( ($txt_generado || $txt_generarse == FALSE) ? '1' : '0'), 
                              'file'   => ( ($txt_generado || $txt_generarse == FALSE) ? ($nombre_file.'.zip') : '' )
                           );

        }



        if(trim($data['generar']) == 'CONCEPTOS')
        {     
          

            $this->load->library(array('App/concepto', 'App/tipoplanilla'));

            $mes = $reporte_info['mes'];
            $usur_key = trim($this->usuario['usur_key']);

            $nombre_file = "reporteconcepto_";
         

            $data = $this->input->post();

            $params = array();

            if( trim($data['siaf']) != '' &&  is_numeric(trim($data['siaf'])) )
            {
                $params['siaf'] = trim($data['siaf']);
            }

            if( trim($data['planilla']) != '' )
            { 

                $pla_info = $this->planilla->get(trim($data['planilla']), true);
                $pla_id = $pla_info['pla_id'];

                if($pla_id != '')
                {
                    
                    $reporte_info['planilla'] = trim($data['planilla']);
                    $params['planilla'] = trim($pla_id);                  
                
                }

            }

            if( trim($data['concepto']) != '' )
            {

                list($tipo, $id_main) = explode('_', trim($data['concepto']));

                if($tipo == 'concepto')
                {
                    $conc_id = $this->concepto->get_id(trim($id_main));
                    $params['concepto'] = $conc_id;
                    $params['grupo'] = '';

                }
                else if ($tipo == 'grupo')
                {
                    $params['concepto'] = '';
                    $params['grupo'] = $id_main;                  
                }

            }
     
            if( trim($data['anio']) != '' )
            {
                 $params['anio'] =  trim($data['anio']);
            }


            if( trim($data['mes']) != '00' )
            {
                 $params['mes'] =  trim($data['mes']);
            }


            if( trim($data['planillatipo']) != '' )
            {

                 $plati_id = $this->tipoplanilla->get_id( trim($data['planillatipo']));
                 $params['planillatipo'] = $plati_id;
                 $plati_info = $this->tipoplanilla->get($plati_id);
                 
                 $reporte_info['planillatipo'] = trim($plati_info['plati_nombre']);
            }


            $reporte = $this->exportador->reporte_por_conceptos($params);
  
            if($params['grupo'] == '')
            {

              $conc_info = $this->concepto->get($params['concepto']);
              $reporte_info['concepto'] = $conc_info['conc_nombre'].' ('.$conc_info['concepto_tipo'].') '; 
              
            }

            $reporte_info['siaf'] = $data['siaf'];
 
            /*GENERANDO PDF*/

            $file_pdf = $path_files.$nombre_file.'.pdf';

            $this->load->view('exportar/conceptos_pdf_1', array('file_pdf' => $file_pdf,
                                                                'reporte_info' => $reporte_info, 
                                                                'reporte' => $reporte) );

            $this->zip->read_file($file_pdf); 


            /*GENERANDO XLS */

            $file_xls = $path_files.$nombre_file.'.xls';


            $this->load->view('exportar/conceptos_xls_1', array('file_xls' => $file_xls,
                                                               'reporte_info' => $reporte_info, 
                                                               'reporte' => $reporte) );

            $this->zip->read_file($file_xls); 


            $file_zip = $path_files.$nombre_file.'.zip';
            $this->zip->archive($file_zip);


            $result = array(
                              'result' => '1',
                              'file'   => ($nombre_file.'.zip')
                           );

        }


        if(trim($data['generar']) == 'CONCEPTOS_MES')
        {     
          

            $this->load->library(array('App/concepto', 'App/tipoplanilla','App/tarea', 'App/grupovc'));

            $mes = $reporte_info['mes'];
            $usur_key = trim($this->usuario['usur_key']);

            $nombre_file = "reporte_concepto_mes";
         
            $data = $this->input->post();

            $params = array();

            $params['anio'] = trim($data['anio']);


            if( trim($data['planillatipo']) != '' && trim($data['planillatipo']) != '0' )
            {
                 $plati_id = $this->tipoplanilla->get_id( trim($data['planillatipo']));
                 $params['plati_id'] = $plati_id;
                 $plati_info = $this->tipoplanilla->get($plati_id);
                 
                 $reporte_info['planillatipo'] = trim($plati_info['plati_nombre']);

                 $reporte_info['con_regimen'] = true;
            }
            else
            {
                $params['plati_id'] = ''; 
                $reporte_info['con_regimen'] = false;
            }
            


            if( trim($data['tarea']) != '' )
            {
                 $tarea_id =  trim($data['tarea']);
                 $params['tarea_id'] = $tarea_id;
                 $tarea_info = $this->tarea->get_info($tarea_id);
                 $reporte_info['tarea_nombre'] = trim($tarea_info['tarea_codigo']).' - '.trim($tarea_info['tarea_nombre']);
                 
                 $reporte_info['con_meta'] = true;
            }
            else
            {
               $reporte_info['con_meta'] = false;
            }

            $params['modo'] = $data['modo'];

            if($data['modo']=='concepto')
            {

                if( trim($data['concepto']) != '' )
                {

                    list($tipo, $id_main) = explode('_', trim($data['concepto']));

                    if($tipo == 'concepto')
                    {
                        $conc_id = $this->concepto->get_id(trim($id_main));
                        $concepto_info = $this->concepto->get($conc_id);

                        $params['conc_id'] = $conc_id;
                        $params['grupo'] = '';
                        $reporte_info['modo_label'] = 'CONCEPTO';
                        $reporte_info['modo_nombre'] = $concepto_info['conc_nombre'];
                    }
                    else if ($tipo == 'grupo')
                    {
                        $params['conc_id'] = '';
                        $params['grupo'] = $id_main; 

                        $grupo_info = $this->grupovc->get($id_main);

                        $reporte_info['modo_label'] = 'GRUPO'; 
                        $reporte_info['modo_nombre'] = $grupo_info['gvc_nombre'];
                
                    }

                }
            }
            else if($data['modo'] == 'grupo')
            {
                $params['conc_id'] = ''; 
                $params['grupo'] = $data['grupo'];
                $grupo_info = $this->grupovc->get($data['grupo']);
                $reporte_info['modo_label'] = 'GRUPO';       
                $reporte_info['modo_nombre'] = $grupo_info['gvc_nombre'];
            }
            else if($data['modo'] == 'bruto' || $data['modo'] == 'neto' || $data['modo'] == 'costo')
            {
               $params['conc_id'] =  ''; 
               $params['grupo']   =  '';
               $reporte_info['modo_label'] = 'TIPO';  

               $consolidados_nombres = array('bruto' => 'INGRESO BRUTO', 'neto' => 'INGRESO NETO (Ingresos- descuentos) ', 'costo'  => 'COSTO TOTAL (Ingresos + Aportaciones)' );
                $reporte_info['modo_nombre'] = $consolidados_nombres[$data['modo']];
            }
            else
            {
               $params['conc_id'] =  '0'; 
               $params['grupo']   =  '';
            }
 
            $reporte = $this->exportador->consolidado_por_mes($params);
         
            /*GENERANDO XLS */
        
            $file_xls = $path_files.$nombre_file.'.xls';


            $this->load->view('exportar/v_concepto_mes', array('file_xls' => $file_xls,
                                                               'reporte_info' => $reporte_info, 
                                                               'reporte' => $reporte) );

            $this->zip->read_file($file_xls); 


            $file_zip = $path_files.$nombre_file.'.zip';
            $this->zip->archive($file_zip);


            $result = array(
                              'result' => '1',
                              'file'   => ($nombre_file.'.zip')
                           );

        }


        if(trim($data['generar']) == 'OTROS')
        {     
           
           $reporte_id = $data['tiporeporte'];
           $mes = $reporte_info['mes'];
           $usur_key = trim($this->usuario['usur_key']);

           list($tipo_reporte) = $this->reporte->get($reporte_id);

           if( trim($tipo_reporte['rep_model']) != '')
           {
                
                $reporte_info['reporte_nombre'] = $tipo_reporte['rep_nombre'];

                $metodo = $tipo_reporte['rep_model'];
                
                if( sizeof($params_modelo['planillas']) == 0) die('Debe especificar una planilla');
            
                if( trim($tipo_reporte['rep_vistadinamica']) == '')
                { 
                    

                    $reporte = call_user_func_array( array($this->exportador, $metodo ), 
                                                     array($params_modelo) );  
                  
                    $prefijo = trim($tipo_reporte['rep_nombre_corto']);

                    $vista_xls = trim($tipo_reporte['rep_xls']);
                    $vista_pdf = trim($tipo_reporte['rep_pdf']);

                    $nombre_file = $mes."_".$prefijo."_".$usur_key;  

                    $generado = false;

                    if($vista_xls != '')
                    {

                        $file_xls = $path_files.$nombre_file.'.xls';
                        
                        $this->load->view('exportar/'.$vista_xls, array( 
                                                                       'file_xls' => $file_xls, 
                                                                       'reporte_info' => $reporte_info, 
                                                                       'reporte' => $reporte 
                                                                       ));

                        $this->zip->read_file($file_xls); 
                    
                        $generado = true;
                    }

                    if($vista_pdf != '')
                    {

                         $file_pdf = $path_files.$nombre_file.'.pdf';

                         $this->load->view('exportar/'.$vista_pdf, array( 
                                                                        'file_pdf' => $file_pdf, 
                                                                        'reporte_info' => $reporte_info, 
                                                                        'reporte' => $reporte 
                                                                        ));

                         $this->zip->read_file($file_pdf); 

                         $generado = true;

                    }
     
                    $file_zip = $path_files.$nombre_file.'.zip';
                    $this->zip->archive($file_zip); 
 
                }
                else
                {

                    $nombre_file = $this->load->view('exportar/'.trim($tipo_reporte['rep_vistadinamica']), array('parametros_data' => $params_modelo), true);
                    $generado = true;
                
                }

                $result = array(
                                  'result' => ( $generado ? '1' : '0'), 
                                  'file'   => ( $generado ? ($nombre_file.'.zip') : '' )
                               );

                
           } 
           else
           {

                echo ' <span class="sp12b"> Este reporte no esta implementado aun </span> ';
           }
           

        }
 
         echo json_encode($result);
    }

    
    
    public function sctr_oc($anio, $mes)
    {

       $sql = " SELECT  (tarea.sec_func || '-' || tarea.tarea_nro ) as tarea , tarea_nombre, meta.nombre as meta_nombre, indiv.indiv_dni, indiv.indiv_fechanac, ( indiv_appaterno || ' ' || indiv_apmaterno || ' ' || indiv_nombres ) as trabajador, datos.sctt

                FROM ( 


                  SELECT  
                        
                          plaec.tarea_id, plaemp.indiv_id, SUM(plaec_value) as sctt

                           FROM planillas.planilla_empleado_concepto plaec  
                           INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1
                           INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = '".$anio."' AND  pla.pla_mes = '".$mes."' AND pla.plati_id IN (5)  
                           INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".ESTADO_PLANILLA_CERRADA." 

                  WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.conc_afecto = 1 AND plaec.conc_id IN (266)

                  GROUP BY plaec.tarea_id, plaemp.indiv_id, plaec.conc_id  

                  ORDER BY plaec.tarea_id, plaemp.indiv_id

                ) as datos 

                LEFT JOIN public.individuo indiv ON datos.indiv_id = indiv.indiv_id 
                INNER JOIN sag.tarea ON datos.tarea_id = tarea.tarea_id 
                LEFT JOIN pip.meta ON tarea.sec_func = meta.sec_func AND tarea.ano_eje = meta.ano_eje

                 
                ORDER BY tarea.tarea_nro, trabajador
    

                     ";

              $reporte = $this->db->query($sql, array() )->result_array();
              
    
    
              $this->load->library('Excel');

              $this->excel = new Excel();
               
              $hoja = 0;

              $this->excel->setActiveSheetIndex($hoja);

              $this->excel->getActiveSheet()->setTitle('SCTR'); // 3500
               
            
              $f = 2;

              $total = 0;
              $tarea_c = '';


              $this->excel->getActiveSheet()->setCellValue( ('A1'), 'TAREA'  );
              $this->excel->getActiveSheet()->setCellValue( ('B1'), 'META'  );
              $this->excel->getActiveSheet()->setCellValue( ('C1'), 'DNI' );
              $this->excel->getActiveSheet()->setCellValue( ('D1'), 'FECHA NACIMIENTO'  );
              $this->excel->getActiveSheet()->setCellValue( ('E1'), 'TRABAJADOR'  );
              $this->excel->getActiveSheet()->setCellValue( ('F1'), 'SCTR'  );


              foreach($reporte as $reg)
              {
                  
                  if($tarea_c != $reg['tarea'])
                  {
                      $this->excel->getActiveSheet()->setCellValue( ('F'.$f), $total  );
                      $f++;
                      $total = 0;

                      $tarea_c = $reg['tarea'];
                  }  
       
                  $this->excel->getActiveSheet()->setCellValueExplicit(
                      ('A'.$f), 
                      trim($reg['tarea']),
                      PHPExcel_Cell_DataType::TYPE_STRING
                  );

                  $this->excel->getActiveSheet()->setCellValueExplicit(
                      ('B'.$f), 
                      trim($reg['tarea_nombre']),
                      PHPExcel_Cell_DataType::TYPE_STRING
                  );

                  $this->excel->getActiveSheet()->setCellValueExplicit(
                      ('C'.$f), 
                      trim($reg['indiv_dni']),
                      PHPExcel_Cell_DataType::TYPE_STRING
                  );


                  $this->excel->getActiveSheet()->setCellValue( ('D'.$f), $reg['indiv_fechanac']  );
                  $this->excel->getActiveSheet()->setCellValue( ('E'.$f), $reg['trabajador']  );
                  $this->excel->getActiveSheet()->setCellValue( ('F'.$f), $reg['sctt']  );

                  $total+=(($reg['sctt'] != '') ? $reg['sctt'] : 0);
                   
                  $f++;
              }  


              $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

              //force user to download the Excel file without writing it to server's HD
              $objWriter->save('docsmpi/exportar/sctr_oc.xls');
    

              echo  "<a href='http://10.0.0.7/sigerhu/docsmpi/exportar/sctr_oc.xls'> Descargar SCTR Obreros  </a>";


    }     


    public function beneficiarios_judiciales()
    {

        $this->load->library(array('App/exportador',
                                   'App/tipoplanilla',
                                   'Catalogos/banco',
                                   'Catalogos/afp',
                                   'App/reporte', 
                                   'App/planilla','zip'));

        $mes = '04'; 

        $parametros_data = array( 'anio' => $this->usuario['anio_ejecucion'] ,'mes' => $mes );

        $rs = $this->exportador->beneficiarios_judiciales($parametros_data);


        $nombre_file = 'beneficiarios_judiciales';

        $file_zip = 'docsmpi/exportar/'.$nombre_file.'.zip';
 
        unlink($file_zip); 

        $this->load->library('Excel');

        $this->excel = new Excel();
         
        $hoja = 0;

        $this->excel->setActiveSheetIndex($hoja);

        $this->excel->getActiveSheet()->setTitle('Beneficiarios_judiciales'); // 3500
         
        
        $f = 2;

        $total = 0;
        $fuente = '';
        $benef = '';
        $benef_total = 0;
        $nb= false;

        $this->excel->getActiveSheet()->setCellValue( ('A1'), 'FUENTE'  );
        $this->excel->getActiveSheet()->setCellValue( ('B1'), 'TRABAJADOR' );
        $this->excel->getActiveSheet()->setCellValue( ('C1'), 'DNI '  );
        $this->excel->getActiveSheet()->setCellValue( ('D1'), 'PLANILLA '  );
        $this->excel->getActiveSheet()->setCellValue( ('E1'), 'PERIODO '  ); 
        $this->excel->getActiveSheet()->setCellValue( ('F1'), 'MONTO'  );
        $this->excel->getActiveSheet()->setCellValue( ('G1'), 'BENEFICIARIO'  );
        $this->excel->getActiveSheet()->setCellValue( ('H1'), 'DNI'  );
        $this->excel->getActiveSheet()->setCellValue( ('I1'), 'CUENTA'  );
        $this->excel->getActiveSheet()->setCellValue( ('J1'), 'BANCO'  );




        foreach($rs as $reg)
        {
            
            if($fuente != $reg['fuente'])
            {

                $this->excel->getActiveSheet()->setCellValue( ('F'.$f), $total  );
                $f+=2;
                $total = 0;

                $fuente = $reg['fuente'];
            }   
 

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('A'.$f), 
                trim($reg['fuente']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );

            $this->excel->getActiveSheet()->setCellValue( ('B'.$f), $reg['trabajador']  );


            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('C'.$f), 
                trim($reg['trabajador_dni']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );

            $this->excel->getActiveSheet()->setCellValue( ('D'.$f), $reg['planilla']  );

            $this->excel->getActiveSheet()->setCellValue( ('E'.$f), $reg['periodo']  );

 

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('F'.$f), 
                trim($reg['total']),
                PHPExcel_Cell_DataType::TYPE_NUMERIC
            );

            $this->excel->getActiveSheet()->setCellValue( ('G'.$f), $reg['beneficiario']  );

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('H'.$f), 
                trim($reg['beneficiario_dni']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );


            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('I'.$f), 
                trim($reg['cuenta']),
                PHPExcel_Cell_DataType::TYPE_STRING
            ); 


            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('J'.$f), 
                trim($reg['banco']),
                PHPExcel_Cell_DataType::TYPE_STRING
            ); 



            $total+=(($reg['total'] != '') ? $reg['total'] : 0);

            $benef_total+=(($reg['total'] != '') ? $reg['total'] : 0);
              
            $f++;
        }  

        $path_files = 'docsmpi/exportar/';
        $file_xls = $path_files.'beneficiarios_judiciales'.'.xls';

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save($file_xls);

        $this->zip->read_file($file_xls);    


        // ARCHIVO PARA ABONO MASIVO
        $parametros_data['banco'] = BANCO_NACION;

        $reporte = $this->exportador->beneficiarios_judiciales($parametros_data);
        
        
        if(BANCO_NACION_DBF == FALSE)
        { 
        
              ini_set('auto_detect_line_endings', false);

              $file_txt = $path_files.'judiciales_bancodelanacion.txt';  
              $file_abono_judiciales = $file_txt;           

              foreach($reporte as $reg)
              {
                  $digito = '0'; 
                  $cuenta = str_replace('-', '', trim($reg['cuenta']));
                  if(substr($cuenta,0,1) == '0') $cuenta = substr($cuenta, 1, strlen($cuenta));
                  $cuenta = substr($cuenta,0,10);

                  $deposito = explode('.',sprintf("%01.2f", $reg['total']));
                  $importe  = sprintf("%013s", $deposito[0]).$deposito[1];
                  $linea    = $digito.$cuenta.$importe;
                  $contenido_txt.= $linea."\r\n";
        
              }
        
              if(file_put_contents($file_txt, "\xEF\xBB\xBF".$contenido_txt))
              {   
                  $txt_generado = true;
              } 
              else
              {
                  $txt_generado = false;
              }
            
        }
        else
        {

              $base_dbf = $path_files.'abonosiaf_judiciales.dbf';
              $file_abono_judiciales = $base_dbf;

              $def = array(
                array("NUM_CTA",     "C", 50),
                array("TIPO_DOC",     "C", 5),
                array("NUM_DOC",      "C", 8),
                array("MONTO",    "N", 8,2),
                array("ESTADO", "C", 1)
              );

               unlink($base_dbf);

              // creación
              if (!dbase_create($base_dbf, $def)) {
                echo "Error, no se puede crear la base de datos\n";
              }
              else{ 
         
                 $db = dbase_open($base_dbf, 2);
                 
                 if($db)
                 {
                 
                       foreach($reporte as $reg)
                       {
                           
                           $cuenta = str_replace('-', '', trim($reg['cuenta']));
                           if(substr($cuenta,0,1) == '0') $cuenta = substr($cuenta, 1, strlen($cuenta));
                           $cuenta = substr($cuenta,0,10);
 
                           $cuenta = '0'.$cuenta;
                           $importe = sprintf("%01.2f", $reg['total']);
                            
                           dbase_add_record($db, array(
                                  $cuenta, 
                                  '01', 
                                  $reg['beneficiario_dni'], 
                                  $importe,
                                 'I'
                           ));   
                      
                       }
                      
                       dbase_close($db);
                  }
              }
 
            
        }
        
        $this->zip->read_file($file_abono_judiciales);    
        $this->zip->archive($file_zip);


       
        echo  "<a href='http://10.0.0.7/sigerhu/docsmpi/exportar/beneficiarios_judiciales.zip'> Descargar archivo </a>";


    }
 

 
    public function sctr_cs($anio, $mes)
    { 

 
        $sql = " SELECT datos.sec_func, 
                        meta.nombre as meta_nombre,
                        indiv.indiv_dni, 
                        indiv.indiv_fechanac, 
                        ( indiv_appaterno || ' ' || indiv_apmaterno || ' ' || indiv_nombres ) as trabajador, 
                        datos.sctt

                  FROM ( 
 
                    SELECT  
                          
                            tarea.sec_func, plaemp.indiv_id, SUM(plaec_value) as sctt

                             FROM planillas.planilla_empleado_concepto plaec  
                             INNER JOIN sag.tarea ON plaec.tarea_id = tarea.tarea_id 
                             INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1
                             INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = '".$anio."' AND pla.pla_mes = '".$mes."' AND pla.plati_id IN (9)   
                             INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id 
                             AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".ESTADO_PLANILLA_CERRADA."    

                    WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.conc_afecto = 1 AND plaec.conc_id IN (175)

                    GROUP BY tarea.sec_func, plaemp.indiv_id, plaec.conc_id  

                    ORDER BY tarea.sec_func, plaemp.indiv_id

                  ) as datos 

                  LEFT JOIN public.individuo indiv ON datos.indiv_id = indiv.indiv_id 
                  LEFT JOIN pip.meta ON meta.sec_func = datos.sec_func AND meta.ano_eje = '".$anio."'


                  ORDER BY datos.sec_func, trabajador



               ";

        $reporte = $this->db->query($sql, array() )->result_array();
        


        $this->load->library('Excel');

        $this->excel = new Excel();
         
        $hoja = 0;

        $this->excel->setActiveSheetIndex($hoja);

        $this->excel->getActiveSheet()->setTitle('SCTR'); // 3500
         
      
        $f = 2;

        $total = 0;
        $meta_c = '';


        $this->excel->getActiveSheet()->setCellValue( ('A1'), 'META'  );

        $this->excel->getActiveSheet()->setCellValue( ('B1'), 'META_NOMBRE'  );
        $this->excel->getActiveSheet()->setCellValue( ('C1'), 'DNI' );
        $this->excel->getActiveSheet()->setCellValue( ('D1'), 'FECHA NACIMIENTO'  );
        $this->excel->getActiveSheet()->setCellValue( ('E1'), 'TRABAJADOR'  );
        $this->excel->getActiveSheet()->setCellValue( ('F1'), 'SCTR'  );


        foreach($reporte as $reg)
        {
            
            if($meta_c != $reg['sec_func'])
            {

                $this->excel->getActiveSheet()->setCellValue( ('F'.$f), $total  );
                $f++;
                $total = 0;

                $meta_c = $reg['sec_func'];
            }  
 
            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('A'.$f), 
                trim($reg['sec_func']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('B'.$f), 
                trim($reg['meta_nombre']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('C'.$f), 
                trim($reg['indiv_dni']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );


            $this->excel->getActiveSheet()->setCellValue( ('D'.$f), $reg['indiv_fechanac']  );
            $this->excel->getActiveSheet()->setCellValue( ('E'.$f), $reg['trabajador']  );
            $this->excel->getActiveSheet()->setCellValue( ('F'.$f), $reg['sctt']  );

            $total+=(($reg['sctt'] != '') ? $reg['sctt'] : 0);
             
            $f++;
        }  


        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

        //force user to download the Excel file without writing it to server's HD
      
  $objWriter->save('docsmpi/exportar/sctr_cs.xls');
 
       echo  "<a href='http://10.0.0.7/sigerhu/docsmpi/exportar/sctr_cs.xls'> Descargar SCTR Construccion civil  </a>";




    }


    public function exportador_excel()
    {

        $this->load->library(array('App/tipoplanilla'));

        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;
        $tipo_planillas = $this->tipoplanilla->get_all();
 
        $this->load->view('planillas/v_exportar_excel', array('tipo_planillas' => $tipo_planillas, 'anios' => $anios));
    }



    public function exportar_data()
    {
 
        $this->load->library(array('Zip','App/exportador','App/tipoplanilla'));

        $generado = false;
        $data = $this->input->post();

        $params = array();
        $params['plati'] = $data['plati'];
        $params['mes']   = $data['mes'];

        $params['anio'] = trim($data['anio']);
        $params['plati_info'] = $data['plati_info'];
        $params['incluir_params'] = $data['incluir_params'];
 
        if(trim($data['dni']) != '')
        {
            
            $indiv_info =  $this->persona->get_by_dni(trim($data['dni']));

            if(sizeof($indiv_info) > 0)
            {
              $params['indiv_id'] = $indiv_info['indiv_id'];
              $params['dni'] = trim($data['dni']);
            }
            else
            {
              $params['indiv_id'] = '0';
              $params['dni'] = trim($data['dni']);
            }
        
        }
        else
        {
           $params['indiv_id'] = '';
           $params['dni'] = '';
        }

        $usur_key = trim($this->usuario['usur_key']);


        if($data['exportacion_data'] == '1')
        {

            $nombre_file = 'data_trabajadores_'.$usur_key;
            
            $file_xls = 'docsmpi/exportar/'.$nombre_file.'.xls';
            $file_zip = 'docsmpi/exportar/'.$nombre_file.'.zip';

            unlink($file_xls);
            unlink($file_zip); 

            $params['plati'] = $data['plati_info'];
            $params['estado_trabajador'] = $data['estado_trabajador'];

            $rs = $this->exportador->get_trabajadores_info($params);

            $this->load->view('exportar/v_exportar_trabajadores', array('reporte' => $rs, 'file_xls' => $file_xls, 'anio' => $params['anio'], 'meses' => $params['mes'], 'planillatipos' => $params['plati'] ));

            $generado = true;
            $this->zip->read_file($file_xls);    
            $this->zip->archive($file_zip);

        }
        else if($data['exportacion_data'] == '2')
        {

            $nombre_file = 'data_planillas_'.$usur_key;
 
            $file_xls = 'docsmpi/exportar/'.$nombre_file.'.xls';
            $file_zip = 'docsmpi/exportar/'.$nombre_file.'.zip';

            unlink($file_xls);
            unlink($file_zip); 

            $this->load->view('exportar/planilladetallada_xls_1', array('file_xls' => $file_xls, 'anio' => $params['anio'], 'meses' => $params['mes'], 'planillatipos' => $params['plati'], 'indiv_id' => $params['indiv_id'] ));

            $generado = true;
            $this->zip->read_file($file_xls);    
            $this->zip->archive($file_zip);
        
        }
        else if($data['exportacion_data'] == '3')
        {

           $nombre_file = 'datatrabajadorvertical_'.$usur_key;
           
           $file_xls = 'docsmpi/exportar/'.$nombre_file.'.xls';
           $file_zip = 'docsmpi/exportar/'.$nombre_file.'.zip';

           unlink($file_xls);
           unlink($file_zip);  

           $rs = $this->exportador->exportar_individuo_concepto_vertical( array('anio' => $params['anio'], 'mes' => $params['mes'], 'plati' => $params['plati'], 'indiv_id' => $params['indiv_id'] ) );

           $this->load->view('exportar/xls_trabajadorconcepto_vertical', array('reporte' => $rs, 
                                                                               'file_xls' => $file_xls, 'anio' => $params['anio'],
                                                                               'meses' => $params['mes'], 
                                                                               'planillatipos' => $params['plati'] )
                            );

           $generado = true;
           $this->zip->read_file($file_xls);    
           $this->zip->archive($file_zip);

        } 
        else if($data['exportacion_data'] == '4')
        {

           $nombre_file = 'dataplanillavertical_'.$usur_key;
           
           $file_xls = 'docsmpi/exportar/'.$nombre_file.'.xls';
           $file_zip = 'docsmpi/exportar/'.$nombre_file.'.zip';

           unlink($file_xls);
           unlink($file_zip);  
           
           $rs = $this->exportador->exportar_planilla_concepto_vertical( array('anio' => $params['anio'],  'mes' => $params['mes'], 'plati' => $params['plati'], 'indiv_id' => $params['indiv_id'], 'dni' => $params['dni'] ) );

           $this->load->view('exportar/xls_planillaconcepto_vertical', array('reporte' => $rs, 
                                                                               'file_xls' => $file_xls, 'anio' => $params['anio'],
                                                                               'meses' => $params['mes'], 
                                                                               'planillatipos' => $params['plati'] )
                            );

           $generado = true;
           $this->zip->read_file($file_xls);    
           $this->zip->archive($file_zip);
                          

        } 
        else if($data['exportacion_data'] == '5')
        {

           $nombre_file = 'dataplanilladescuentos_'.$usur_key;
           
           $file_xls = 'docsmpi/exportar/'.$nombre_file.'.xls';
           $file_zip = 'docsmpi/exportar/'.$nombre_file.'.zip';

           unlink($file_xls);
           unlink($file_zip);  
           
           $rs = $this->exportador->consolidado_descuentos_porplanilla( array('mes' => $params['mes'], 'anio' => $params['anio'], 'plati' => $params['plati'], 'indiv_id' => $params['indiv_id'], 'dni' => $params['dni'] ) );

           $this->load->view('exportar/xls_planilladescuentos',          array('reporte' => $rs, 
                                                                               'file_xls' => $file_xls, 
                                                                               'anio' => $params['anio'],
                                                                               'meses' => $params['mes'], 
                                                                               'planillatipos' => $params['plati'] )
                            );

           $generado = true;
           $this->zip->read_file($file_xls);    
           $this->zip->archive($file_zip);
                          

        } 
        else if($data['exportacion_data'] == '6')
        {

            $nombre_file = 'data_planillas_agrupado_por_mes'.$usur_key;
        
            $file_xls = 'docsmpi/exportar/'.$nombre_file.'.xls';
            $file_zip = 'docsmpi/exportar/'.$nombre_file.'.zip';

            unlink($file_xls);
            unlink($file_zip); 

            $this->load->view('exportar/planilladetallada_xls_1', array('file_xls' => $file_xls, 'anio' => $params['anio'], 'meses' => $params['mes'], 'planillatipos' => $params['plati'], 'agrupar_por_mes' => true, 'indiv_id' => $params['indiv_id'], 'dni' => $params['dni'] ));

            $generado = true;
            $this->zip->read_file($file_xls);    
            $this->zip->archive($file_zip);
        
        }
        else
        {
 
          $generado = false;

        }

        
        $result = array(
                          'result' => ( $generado ? '1' : '0'), 
                          'file'   => ( $generado ? ($nombre_file.'.zip') : '' )
                       );

        echo json_encode($result);
    }
 

    public function remuneraciones()
    {

        $rs =  $this->exportador->remuneraciones();

        $nombre_file = 'remuneraciones';
        
        $file_xls = 'docsmpi/exportar/'.$nombre_file.'.xls';
 
        $this->load->view('exportar/v_remuneraciones_xls', array('file_xls' => $file_xls, 'reporte' => $rs ));


        echo  "<a href='http://10.0.0.7/sigerhu/docsmpi/exportar/remuneraciones.xls'> Descargar archivo </a>";
    }



    public function ingresos_mensuales()
    {

        $rs =  $this->exportador->ingresos_mensuales();

        $nombre_file = 'ingresosmensuales';
        
        $file_xls = 'docsmpi/exportar/'.$nombre_file.'.xls';
    
        $this->load->view('exportar/v_ingresosmensuales_xls', array('file_xls' => $file_xls, 'reporte' => $rs ));
 
        echo  "<a href='http://10.0.0.7/sigerhu/docsmpi/exportar/ingresosmensuales.xls'> Descargar archivo </a>";
    }

    public function sunat_pdt()
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
 
       $file_zip = 'docsmpi/exportar/PDT'.$params['anio'].$params['mes'].'_'.$usur_key.'.zip';

       $nombre_zip = 'PDT'.$params['anio'].$params['mes'].'_'.$usur_key.'.zip';



       //  **************************************** ARCHIVOS PARA T-REGISTRO  ***************************************************************************

       // EDU

       $contenido_txt = '';
       $reporte =  $this->reportesunat->edu($params);

       $nombre_file =  'RP_'.RUC_INSTITUCION.'.EDU';
       $file_txt = $path_files.$nombre_file;

       foreach($reporte as $reg) {  
           $data = array(  
                'tipo'          => PDT_DOCUMENTO_DNI,
                'numero'        => $reg['indiv_dni'],
                'pais'          => PDT_PAIS,
                'completo'      => '1',
                'institucion'   => $reg['cees_codigo'],
                'carrera'       => $reg['carpro_codigo'],
                'egreso'        => $reg['anio_egreso']
            );
       
           $linea = $data['tipo'].'|'.$data['numero'].'|'.$data['pais'].'|'.$data['completo'].'|'.$data['institucion'].'|'.$data["carrera"].'|'.$data["egreso"];
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
                           'regimen'              => $reg['sunat_regimenlaboral'],
                           'niveleducativo'       => $reg['sunat_educacion'],
                           'ocupacion'            => $reg['sunat_ocupacion'],
                           'discapacidad'         => '0',
                           'CUSPP'                => (trim($reg['cuspp']) != '' && strpos(trim($reg['cuspp']),'.' ) === FALSE  ? trim($reg['cuspp']) : '' ),
                           'SCTR'                 => $reg['sctr'],
                           'tipocontrato'         => $reg['sunat_tipocontrato'],
                           'regimenalternativo'   => 0,
                           'jornadatrabajomaxima' => 0,
                           'horarionocturno'      => 0,
                           'sindicalizado'        => 0,
                           'periodoremuneracion'  => $reg['sunat_periodoremuneracion'],
                           'montorembasicainicial' => '0.00',
                           'situacion'             => '1',
                           'rentaquintaexonerada'  => '0',
                           'situacionespecialtrabajador' => '0',
                           'tipopago'             => PDT_TIPOPAGO_DEPOSITOCUENTA,
                           'categoriaocupacional' => '',
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
                           'CATEGORIA' => '1',
                           'REGIMENESSALUD' => '00',
                           'sctr' => '1'
                          
                        );

           $fechaini = '01/'.$params['mes'].'/'.$params['anio'];
       
           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'1'.'|'.$fechaini.'|'.''.'|'.''.'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";

           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'2'.'|'.$fechaini.'|'.''.'|'.$reg['sunat_codigo_trabajador'].'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";

           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'3'.'|'.$fechaini.'|'.''.'|'.$data['REGIMENESSALUD'].'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";

           $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'4'.'|'.$fechaini.'|'.''.'|'.$reg['sunat_regimenpensionario'].'|'.''.'|';
           $contenido_txt.= $linea."\x0D\x0A";

       
          if($reg['sctr'] == '1')
           { 
              $linea = $data['tipodoc'].'|'.$reg['indiv_dni'].'|'.$data['pais'].'|'.$data['CATEGORIA'].'|'.'5'.'|'.$fechaini.'|'.''.'|'.$data['sctr'].'|'.''.'|';
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
           $linea =  PDT_DOCUMENTO_DNI.'|'.$reg['indiv_dni'].'|'.PDT_PAIS.'|'.RUC_INSTITUCION.'|'.$reg['sunat_establecimiento'].'|';
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

          $horas_asistencia = $reg['asistencia'];

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
  
     $contenido_txt = '';
      
       $p_params = $params;
       $p_params['tipoplanilla'] = TIPOPLANILLA_PENSIONSITAS;

       $reporte =  $this->reportesunat->rem($p_params);

       
       $nombre_file =  '0601'.$params['anio'].$params['mes'].RUC_INSTITUCION.'.pen';   
       $file_txt = $path_files.$nombre_file;
       $x=1;
       foreach($reporte as $reg)
       { 

           $deposito =sprintf("%01.2f", $reg['monto']);  
           $concepto = sprintf("%04s", $reg['cosu_codigo']);
       
           $linea =  '01|'.$reg['dni'].'|'.$concepto.'|'.$deposito.'|'.$deposito.'|';
       
           $contenido_txt.= $linea."\x0D\x0A";
            
         

            $xx++;
       }
       
       if(file_put_contents($file_txt, $contenido_txt))
       {   
             $this->zip->read_file($file_txt);   
       } 
       else
       {
          if(sizeof($reporte) > 0)  $generado = false;
       }



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


    public function descuentos_porplanilla()
    {

         $this->load->library(array('Zip'));
         
         $this->exportador->consolidado_descuentos_porplanilla();

         
         $file_xls = $path_files.'SUNAT_'.$mes.'_PORPLANILLA.xls'; 
         $reporte = call_user_func_array( array($this->exportador, 'consolidado_sunat_por_planilla' ), 
                                          array($params_modelo) ); 


         $this->load->view('exportar/sunat_xls_3',  array('file_xls' => $file_xls,
                                                         'reporte_info' => $reporte_info, 
                                                         'reporte' => $reporte) ); 
         
         $this->zip->read_file($file_xls); 
         
    }


    public function data_certificados()
    { 

        $this->load->library(array( 'App/persona',
                                    'App/situacionlaboral',
                                    'App/documento',
                                    'App/ocupacion'
                                   ));
        
         $path_files = 'docsmpi/exportar/';
         $nombre_file = 'datos_para_certificados_detrabajo.xls';

         $file_xls = $path_files.$nombre_file;

         $data = $this->input->post();

         $views = trim($data['views']);
         $id_s =  explode('_', $data['views']);
         array_shift($id_s); 
          
         $persla_ids = $this->situacionlaboral->get_multiple_id_persla($id_s); 
      
         $detalle_certificados = $this->situacionlaboral->get_detalle_contrato($persla_ids);
            
         $this->load->view('exportar/data_certificados', array('detalle_certificados' => $detalle_certificados, 'file_xls' => $file_xls) );
         $generado = true;
 

         $result = array(
                           'result' => ( $generado ? '1' : '0'), 
                           'file'   => ( $generado ? ($nombre_file) : '' )
                        );

         echo json_encode($result) ;
    }


    public function escalafon()
    {


        header("Content-Type: application/json");
               
        $data = $this->input->get();

        $tipo = $data['tipoview'];
        $values = array();

        $params = array();

        $meses = array( '1' =>  'ENERO',
                        '2' => 'FEBRERO',
                        '3' => 'MARZO',
                        '4' => 'ABRIL',
                        '5' => 'MAYO',
                        '6' => 'JUNIO',
                        '7' => 'JULIO',
                        '8' => 'AGOSTO',
                        '9' => 'SEPTIEMBRE',
                        '10' => 'OCTUBRE',
                        '11' => 'NOVIEMBRE',
                        '12' => 'DICIEMBRE'
                      );

        
        $params['busquedaporfecha'] = $data['busquedaporfecha'];
        
        if( trim($data['desde']) != '' )
        {
            $params['desde'] =  trim($data['desde']); 
        }
        else
        {
           $params['desde'] =  '';
        }

        if( trim($data['hasta']) != '' )
        {
            $params['hasta'] =  trim($data['hasta']); 
        }
        else
        {
           $params['hasta'] =  '';
        }

        if( trim($data['agruparpor']) != '0'  )
        {
           $params['agrupar'] =  trim($data['agruparpor']);

           $params['valoracumulado'] =  (is_numeric($data['valoracumulado']) ? $data['valoracumulado'] : 0 );
        }  
        else
        {
            $params['agrupar'] = '';
            $params['valoracumulado'] = '';
        }

        if( trim($data['dni']) != ''  )
        {
            $pers_info = $this->persona->get_by_dni(trim($data['dni']) );
            $params['indiv_id'] = $pers_info['indiv_id'];
           
            if($pers_info['indiv_id'] == '')
            {
              echo json_encode($response);
              die();
            }
        }  


        if($tipo == 5) // descanso medico
        {

            if( $this->user->has_key('TRABAJADOR_DESCANSOMEDICO_VER') )
            { 
                  
                 header("Content-Type: application/json");
                 
                 
                 $this->load->library('App/descansomedico'); 
                 
                 $rs =  $this->descansomedico->get_by_filtro($params);
                 
                 $total = sizeof($rs);
                 $start = 0;
                 $end =  $total;
                 
                 header("Content-Range: " . "items ".$start."-".$end."/".$total);     
                 $data = array();
                 $c = 1;
        
                 if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
                 {

                     foreach($rs as $registro)
                     {
                           $data['id'] =   trim($registro['indice']);
                           $data['numeral'] = $c;
                           $data['trabajador_nombre'] =  $registro['trabajador'];
                           $data['trabajador_dni'] =  $registro['trabajador_dni'];
                           $data['trabajador_regimen'] =   $registro['regimen'];
                           $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                           $data['total'] = $registro['total'];
                           $response[] = $data;
                           $c++;
                     }
        
                 }
                 else
                 {
                  
                     foreach($rs as $registro)
                     {
                  /*        
                         $data['id'] =   trim($registro['perdm_key']);
                         $data['numeral'] = $c;
                         $data['col2'] = (trim($registro['perdm_documento']) != '') ?  trim($registro['perdm_documento']) : '-------';
                         $data['col2'] = (trim($registro['perdm_documento']) != '') ?  trim($registro['perdm_documento']) : '-------';
                         $data['col3'] = _get_date_pg($registro['perdm_fechaini']);
                         $data['col4'] = _get_date_pg($registro['perdm_fechafin']);
                         $data['col5'] = $registro['tipo'];
                         $data['col6'] = $registro['dias'];
                         $data['col7'] = (trim($registro['perdm_obs']) == '') ? '------- ' : trim($registro['perdm_obs']);
                         $response[] = $data;
                         $c++;*/

                         $data['id'] =   trim($registro['perdm_key']);
                         $data['numeral'] = $c;
                         $data['trabajador_nombre'] =  $registro['trabajador'];
                         $data['trabajador_dni'] =  $registro['trabajador_dni'];
                         $data['trabajador_regimen'] =   $registro['regimen'];
                         $data['documento'] =  trim($registro['perdm_documento']) == '' ? '-----' : $registro['perdm_documento']; 
                         $data['desde'] = _get_date_pg($registro['perdm_fechaini']);
                         $data['hasta'] = _get_date_pg($registro['perdm_fechafin']);
                         $data['dias'] = $registro['dias'];
                         $data['descripcion'] =   (trim($registro['perdm_obs']) == '') ? '-----' : trim($registro['perdm_obs']);
                         $response[] = $data;
                         $c++;
                     }


                 }


             }
              
        
        }
        else if($tipo == 2) // Licencias 
        {

            if($data['tipolic'] != '0' && $data['tipolic'] != '' )
            {
                $params['tipo'] = $data['tipolic'];
            }
            else
            {
               $params['tipo'] = '';
            }

           if( $this->user->has_key('TRABAJADOR_LICENCIAS_VER') )
           { 
                 
                header("Content-Type: application/json");
                 
                $this->load->library('App/licencia'); 
                
                $rs =  $this->licencia->get_by_filtro($params);
                
                $total = sizeof($rs);
                $start = 0;
                $end =  $total;
                
                header("Content-Range: " . "items ".$start."-".$end."/".$total);     
                $data = array();
                $c = 1;
           
                if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
                {

                    foreach($rs as $registro)
                    {
                          $data['id'] =   trim($registro['indice']);
                          $data['numeral'] = $c;
                          $data['trabajador_nombre'] =  $registro['trabajador'];
                          $data['trabajador_dni'] =  $registro['trabajador_dni'];
                          $data['trabajador_regimen'] =   $registro['regimen'];
                          $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                          $data['total'] = $registro['total'];
                          $response[] = $data;
                          $c++;
                    }
           
                }
                else
                {
                 
                    foreach($rs as $registro)
                    {
            
                        $data['id'] =   trim($registro['peli_key']);
                        $data['numeral'] = $c;
                        $data['trabajador_nombre'] =  $registro['trabajador'];
                        $data['trabajador_dni'] =  $registro['trabajador_dni'];
                        $data['trabajador_regimen'] =   $registro['regimen'];
                        $data['documento'] = trim($registro['peli_documento']) == '' ? '-----' : $registro['peli_documento']; 
                        $data['desde'] = _get_date_pg($registro['peli_fechavigencia']);
                        $data['hasta'] = _get_date_pg($registro['peli_fechacaducidad']);
                        $data['dias'] = $registro['dias'];
                        $data['tipo'] = (trim($registro['tipo']) == '') ? '-----' : trim($registro['tipo']); 
                        $data['observacion'] = (trim($registro['peli_observacion']) == '') ? '-----' : trim($registro['peli_observacion']); 
                        $response[] = $data;
                        $c++;
                    }


                }


            }
        }
        else if($tipo == 3) // Permisos
        {

            if($data['tipolic'] != '0' && $data['tipolic'] != '' )
            {
                $params['tipo'] = $data['tipolic'];
            }
            else
            {
               $params['tipo'] = '';
            }

           if( $this->user->has_key('TRABAJADOR_PERMISOS_VER') )
           { 
                 
                header("Content-Type: application/json");
                 
                $this->load->library('App/permiso'); 
                
                $rs =  $this->permiso->get_by_filtro($params);
                
                $total = sizeof($rs);
                $start = 0;
                $end =  $total;
                
                header("Content-Range: " . "items ".$start."-".$end."/".$total);     
                $data = array();
                $c = 1;
           
                if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
                {

                    foreach($rs as $registro)
                    {
                          $data['id'] =   trim($registro['indice']);
                          $data['numeral'] = $c;
                          $data['trabajador_nombre'] =  $registro['trabajador'];
                          $data['trabajador_dni'] =  $registro['trabajador_dni'];
                          $data['trabajador_regimen'] =   $registro['regimen'];
                          $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                          $data['total'] = $registro['total'];
                          $response[] = $data;
                          $c++;
                    }
           
                }
                else
                {
                 
                    foreach($rs as $registro)
                    {
            
                        $data['id'] =   trim($registro['pepe_key']);
                        $data['numeral'] = $c;
                        $data['trabajador_nombre'] =  $registro['trabajador'];
                        $data['trabajador_dni'] =  $registro['trabajador_dni'];
                        $data['trabajador_regimen'] =   $registro['regimen'];
                        $data['documento'] = trim($registro['pepe_documento']) == '' ? '-----' : $registro['pepe_documento'];
                        $data['desde']     = _get_date_pg($registro['pepe_fechadesde']);
                        $data['hsalida']   =  ($registro['pepe_horaini']);
                        $data['hingreso']  = ( trim($registro['pepe_horafin']) == '' ? '-----' : $registro['pepe_horafin'] );
                        $response[] = $data;
                        $c++;
                    }


                }


            }
        }
        else if($tipo == 1) // Comision de servicio 
        {

           if($data['ciudad'] != '0'){
               
               list($params['distrito'], $params['provincia'], $params['departamento'] ) = explode('-', $data['ciudad']);
           }

           if( $this->user->has_key('TRABAJADOR_COMISIONSERVICIO_VER') )
           { 
                 
                header("Content-Type: application/json");
                 
                $this->load->library('App/comision'); 
                
                $rs =  $this->comision->get_by_filtro($params);
                
                $total = sizeof($rs);
                $start = 0;
                $end =  $total;
                
                header("Content-Range: " . "items ".$start."-".$end."/".$total);     
                $data = array();
                $c = 1;
           
                if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
                {

                    foreach($rs as $registro)
                    {
                          $data['id'] =   trim($registro['indice']);
                          $data['numeral'] = $c;
                          $data['trabajador_nombre'] =  $registro['trabajador'];
                          $data['trabajador_dni'] =  $registro['trabajador_dni'];
                          $data['trabajador_regimen'] =   $registro['regimen'];
                          $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                          $data['total'] = $registro['total'];
                          $response[] = $data;
                          $c++;
                    }
           
                }
                else
                {
                 
                    foreach($rs as $registro)
                    {
            
                        $data['id'] =   trim($registro['peco_key']);
                        $data['numeral'] = $c;
                        $data['trabajador_nombre'] =  $registro['trabajador'];
                        $data['trabajador_dni'] =  $registro['trabajador_dni'];
                        $data['trabajador_regimen'] =   $registro['regimen'];
                        $data['documento'] = trim($registro['peco_documento']) == '' ? '-----' : $registro['peco_documento'];
                        $data['desde'] = _get_date_pg($registro['peco_fechadesde']);
                        $data['hasta'] = _get_date_pg($registro['peco_fechahasta']); 
                        $data['col6'] = (trim($registro['peco_motivo']) != '' ? trim($registro['peco_motivo']) : '------'); 
                        $data['col3'] = (trim($registro['destino']) != '' ? trim($registro['destino']) : '------'); 
                         
                        $response[] = $data;
                        $c++;
                    }


                }


            }
        }
        else if($tipo == 6) //  Faltas
        {
        
           if( $this->user->has_key('TRABAJADOR_FALTASTAR_VER') )
           { 
                 
                header("Content-Type: application/json");
                 
                $this->load->library('App/falta'); 
                
                $rs =  $this->falta->get_by_filtro($params);
                
                $total = sizeof($rs);
                $start = 0;
                $end =  $total;
                
                header("Content-Range: " . "items ".$start."-".$end."/".$total);     
                $data = array();
                $c = 1;
           
                if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
                {

                    foreach($rs as $registro)
                    {
                          $data['id'] =   trim($registro['indice']);
                          $data['numeral'] = $c;
                          $data['trabajador_nombre'] =  $registro['trabajador'];
                          $data['trabajador_dni'] =  $registro['trabajador_dni'];
                          $data['trabajador_regimen'] =   $registro['regimen'];
                          $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                          $data['total'] = $registro['total'];
                          $response[] = $data;
                          $c++;
                    }
           
                }
                else
                {
                 
                    foreach($rs as $registro)
                    {
            
                        $data['id'] =   trim($registro['pefal_key']);
                        $data['numeral'] = $c;
                        $data['trabajador_nombre'] =  $registro['trabajador'];
                        $data['trabajador_dni'] =  $registro['trabajador_dni'];
                        $data['trabajador_regimen'] =   $registro['regimen'];
                        $data['desde'] = _get_date_pg($registro['pefal_desde']);
                        $data['hasta'] = _get_date_pg($registro['pefal_hasta']); 
                      
                        $data['justificada'] = (trim($registro['pefal_justificada']) == '1') ? 'Si' : 'No';
                        $data['justificacion'] = (trim($registro['pefal_justificacion']) != '') ? trim($registro['pefal_justificacion']) : '-------'; 
                          
                        $response[] = $data;
                        $c++;
                    }


                }


            }
        }
        else if($tipo == 7) //  Tard
        {
        
           if( $this->user->has_key('TRABAJADOR_FALTASTAR_VER') )
           { 
                 
                header("Content-Type: application/json");
                 
                $this->load->library('App/tardanza'); 
                
                $rs =  $this->tardanza->get_by_filtro($params);
                
                $total = sizeof($rs);
                $start = 0;
                $end =  $total;
                
                header("Content-Range: " . "items ".$start."-".$end."/".$total);     
                $data = array();
                $c = 1;
           
                if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
                {

                    foreach($rs as $registro)
                    {
                          $data['id'] =   trim($registro['indice']);
                          $data['numeral'] = $c;
                          $data['trabajador_nombre'] =  $registro['trabajador'];
                          $data['trabajador_dni'] =  $registro['trabajador_dni'];
                          $data['trabajador_regimen'] =   $registro['regimen'];
                          $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                          $data['total'] = $registro['total'];
                          $response[] = $data;
                          $c++;
                    }
           
                }
                else
                {
                 
                    foreach($rs as $registro)
                    {
            
                        $data['id'] =   trim($registro['peft_key']);
                        $data['numeral'] = $c;
                        $data['trabajador_nombre'] =  $registro['trabajador'];
                        $data['trabajador_dni'] =  $registro['trabajador_dni'];
                        $data['trabajador_regimen'] =   $registro['regimen'];
                        $data['desde'] = _get_date_pg($registro['peft_fecha']);
                        $data['minutos'] =   $registro['minutos'];
                        $data['justificacion'] = (trim($registro['peft_justificacion']) != '') ? trim($registro['peft_justificacion']) : '-------'; 
                          
                        $response[] = $data;
                        $c++;
                    }


                }


            }
        }

        echo json_encode($response);
 
    }


    public function reporte_de_licencias(){

 
        $this->load->library(array('App/licencia'));


        $datos = $this->input->post();
 

        $parametros = array();

        $parametros['tipo_licencia'] = '0';

        $parametros['anio'] = '';

        if($datos['tipoPeriodo'] == 'anio'){

            $parametros['anio'] = ($datos['anio'] == '0' || $datos['anio'] == '') ? '' : $datos['anio'];

        } 


        $parametros['fecha_registro_sistema'] = false;
        if($datos['tipoPeriodo'] == 'registro_sistema'){

            $parametros['fecha_registro_sistema'] = true;
        } 

        
        if( trim($datos['fecha_desde']) != ''){

            $parametros['fechadesde'] = trim($datos['fecha_desde']);
        }
        
        if( trim($datos['fecha_hasta']) != ''){

            $parametros['fechahasta'] = trim($datos['fecha_hasta']);
        }

        $nombre_reporte = ' Reporte de Licencias';
        $nombre_archivo = 'reporte_licencias';
        if( trim($datos['tipo']) != ''){
            
            $tipo_codigo = trim($datos['tipo']);

            $todosLosTipos = false;
        
            if($tipo_codigo != '0'){
                list($tipo, $tipo_id) = explode('_', $tipo_codigo );
            } else { 
                $tipo = '0';
                $todosLosTipos = true;
            }
        
            $incluir = array();
             
            if( $tipo == 'vac' || $todosLosTipos ){
                $incluir[] = 'vacaciones';
                $nombre_reporte = ' Reporte de Vacaciones'; 
                $nombre_archivo = 'reporte_vacaciones';
            }
            

            if( $tipo == 'desm' || $todosLosTipos ){
                $incluir[] = 'descanso_medico';
                $nombre_reporte = ' Reporte de D.Medicos';
                $nombre_archivo = 'reporte_descansos';
            }
            

            if( $tipo == 'comc' || $todosLosTipos ){
                $incluir[] = 'comision_servicio';
                $nombre_reporte = ' Reporte de Comisiones';
                $nombre_archivo = 'reporte_comisiones';
            }
            
            if( $tipo == 'lic' || $todosLosTipos ){
                $incluir[] = 'licencia';
                $parametros['tipo_licencia'] = (trim($tipo_id)!= '' ? trim($tipo_id) : '0' );
                $nombre_reporte = ' Reporte de Licencias';
            }

            $parametros['incluir'] = $incluir;
        
        }

        if($datos['filtrar_por'] == 'trabajador'){

            if( trim($datos['trabajador']) != ''){

                $indiv_id = $this->persona->get_id(trim($datos['trabajador']));
                $parametros['indiv_id'] = $indiv_id;
            }
            
        }
        else if($datos['filtrar_por'] == 'tipotrabajador'){

            $parametros['tipotrabajador'] = $datos['tipotrabajador'];
        }


        
        if( trim($datos['agruparpor']) == 'anio'){

            $parametros['agrupar_por'] = trim($datos['agruparpor']);
            $parametros['poracumulado']= trim($datos['poracumulado']);
            $parametros['valoracumulado']= trim($datos['valoracumulado']);
        }
        

        $rs = $this->licencia->getLicenciasDia($parametros);
        

        if( trim($datos['agruparpor']) == 'anio'){

           $this->load->view('exportar/reporte_licencias_poranio', array('reporte' => $rs, 'nombre_reporte' => $nombre_reporte, 'nombre_archivo' => $nombre_archivo));
        }
        else{

           $this->load->view('exportar/reporte_licencias', array('reporte' => $rs, 'nombre_reporte' => $nombre_reporte, 'nombre_archivo' => $nombre_archivo));
        }

    }


    public function asistencia_excel(){


      $this->load->library(array('App/hojaasistencia','App/hojadiariotipo'));

      //$datos = $this->input->post();
 
      $data =  $this->input->post(); 
      
      if($data['detalle_importacion'] != '1')
      { 

              // $plati_id = $this->tipoplanilla->get_id($data['planillatipo']);
              // $config =  $this->hojaasistencia->get_plati_config($plati_id);   

              if($data['planillatipo'] != '0')
              {
                  $plati_id = $this->tipoplanilla->get_id($data['planillatipo']); 
              
                  $plati_id = array($plati_id);
              }
              else{   
              
                  $tiposPlanilla = $this->tipoplanilla->get_all( 1, array('tipo_registro_asistencia' => $data['tipo_registro_asistencia'])); 
                  $plati_id = array();

                  foreach ($tiposPlanilla as $tp) {
                      array_push($plati_id, $tp['plati_id']);
                  } 
               
              } 
       
              $params = array(); 
             
              $params['tipobusqueda'] = $data['tipobusqueda'];
      

              if($params['tipobusqueda'] == '1')
              {
                  $params['plati_id'] = $plati_id; 
                  $params['tarea_id'] = '';
                  $params['area_id'] = '';
                  $params['hoja'] = '';
                  $params['trabajador'] = '';
              }   
              else if($params['tipobusqueda'] == '2')
              {
                  $params['plati_id'] = '';
                  $params['tarea_id'] =  $data['tarea'];
                  $params['area_id'] = '';
                  $params['hoja'] = '';
                  $params['trabajador'] = '';
              } 
              else if($params['tipobusqueda'] == '3')
              {
                  $params['plati_id'] = '';
                  $params['tarea_id'] =  '';
                  $params['area_id'] = $data['dependencia'];
                  $params['hoja'] = '';
                  $params['trabajador'] = '';
              }
              else if($params['tipobusqueda'] == '4')
              {
                  $params['plati_id'] = '';
                  $params['tarea_id'] =  '';
                  $params['area_id'] = '';
                  $params['hoja'] =  $data['codigohoja'];
                  $params['trabajador'] = '';
              }
              else if($params['tipobusqueda'] == '5')
              {
                  $params['plati_id'] = '';
                  $params['tarea_id'] =  '';
                  $params['area_id'] = '';
                  $params['hoja'] =   '';
                  $params['dni'] = xss_clean(trim($data['dni']));
              }
              else
              {
                  $params['plati_id'] = $plati_id; 
                  $params['tarea_id'] = '';
                  $params['area_id'] = '';
                  $params['hoja'] = '';
                  $params['trabajador'] = '';
              }
              

              $params['lugar_de_trabajo'] = trim($data['lugar_de_trabajo']);
              
              $params['mostraractivos'] = $data['mostraractivos'];

              $params['fechadesde'] = $data['fechadesde'];
              $params['fechahasta'] = $data['fechahasta'];
      
      } 

      $estados_dia = $this->hojadiariotipo->getAll();
      $rs_estados_dia = array();

      foreach($estados_dia as $st)
      {
          $rs_estados_dia[$st['hatd_id']] = $st;
      } 


 

      $params['modo_ver'] = (trim($data['ver_modo']) == '' ? '1' : trim($data['ver_modo']) );
      

      // $params['count'] = true;

      // $total_registros = $this->hojaasistencia->get_registro_asistencia($params, true, false);

      // $params['count'] = false;

      // $data['pagina'] = ($data['pagina'] != '') ? ($data['pagina'] * 1) : 1;

      // $params['limit'] = 30;

      // if($data['pagina'] > 1)
      // {
      //     $params['offset'] = $params['limit'] * ($data['pagina'] - 1);
      // }

      $params['count'] = false;
      $contador_inicial = ($data['pagina'] - 1) * $params['limit'];


      $calendario_data = $this->hojaasistencia->get_registro_asistencia($params, true, true);
  
      $params['modo_ver'] = MODOVERCALENDARIO_TARDANZAS;

      $this->load->view('exportar/xls_registrodeasistencia_formato2', array( 'calendario' => $calendario_data, 
                                                                              'rs_estados_dia' => $rs_estados_dia,
                                                                              'total_registros' => $total_registros,
                                                                              'params' => $params,
                                                                              'estructura_tabla_resumen' => $estructura,
                                                                              'categorias' => $categorias,
                                                                              'config' => $config,
                                                                              'paginaactual' => $data['pagina'],
                                                                              'contador_inicial' => $contador_inicial,
                                                                              'file_xls' => 'docsmpi/exportar/resumen_asistencia_excel2.xls'
                                                                             )); 

    }




    public function asistencia_permisos_excel(){


      $this->load->library(array('App/hojaasistencia','App/hojadiariotipo'));

      //$datos = $this->input->post();
    
      $data =  $this->input->post(); 
      
      if($data['detalle_importacion'] != '1')
      { 

              // $plati_id = $this->tipoplanilla->get_id($data['planillatipo']);
              // $config =  $this->hojaasistencia->get_plati_config($plati_id); 
              
              if($data['planillatipo'] != '0')
              {
                  $plati_id = $this->tipoplanilla->get_id($data['planillatipo']); 
              
                  $plati_id = array($plati_id);
              }
              else{   
              
                  $tiposPlanilla = $this->tipoplanilla->get_all( 1, array('tipo_registro_asistencia' => $data['tipo_registro_asistencia'])); 
                  $plati_id = array();

                  foreach ($tiposPlanilla as $tp) {
                      array_push($plati_id, $tp['plati_id']);
                  } 
               
              } 
              
              $params = array(); 
             
              $params['tipobusqueda'] = $data['tipobusqueda'];
      

              if($params['tipobusqueda'] == '1')
              {
                  $params['plati_id'] = $plati_id; 
                  $params['tarea_id'] = '';
                  $params['area_id'] = '';
                  $params['hoja'] = '';
                  $params['trabajador'] = '';
              }   
              else if($params['tipobusqueda'] == '2')
              {
                  $params['plati_id'] = '';
                  $params['tarea_id'] =  $data['tarea'];
                  $params['area_id'] = '';
                  $params['hoja'] = '';
                  $params['trabajador'] = '';
              } 
              else if($params['tipobusqueda'] == '3')
              {
                  $params['plati_id'] = '';
                  $params['tarea_id'] =  '';
                  $params['area_id'] = $data['dependencia'];
                  $params['hoja'] = '';
                  $params['trabajador'] = '';
              }
              else if($params['tipobusqueda'] == '4')
              {
                  $params['plati_id'] = '';
                  $params['tarea_id'] =  '';
                  $params['area_id'] = '';
                  $params['hoja'] =  $data['codigohoja'];
                  $params['trabajador'] = '';
              }
              else if($params['tipobusqueda'] == '5')
              {
                  $params['plati_id'] = '';
                  $params['tarea_id'] =  '';
                  $params['area_id'] = '';
                  $params['hoja'] =   '';
                  $params['dni'] = xss_clean(trim($data['dni']));
              }
              else
              {
                  $params['plati_id'] = $plati_id; 
                  $params['tarea_id'] = '';
                  $params['area_id'] = '';
                  $params['hoja'] = '';
                  $params['trabajador'] = '';
              }
          
              $params['mostraractivos'] = $data['mostraractivos'];

              $params['fechadesde'] = $data['fechadesde'];
              $params['fechahasta'] = $data['fechahasta'];
      
      } 

      $estados_dia = $this->hojadiariotipo->getAll();
      $rs_estados_dia = array();

      foreach($estados_dia as $st)
      {
          $rs_estados_dia[$st['hatd_id']] = $st;
      } 



      $params['modo_ver'] = (trim($data['ver_modo']) == '' ? '1' : trim($data['ver_modo']) );
      

      // $params['count'] = true;

      // $total_registros = $this->hojaasistencia->get_registro_asistencia($params, true, false);

      // $params['count'] = false;

      // $data['pagina'] = ($data['pagina'] != '') ? ($data['pagina'] * 1) : 1;

      // $params['limit'] = 30;

      // if($data['pagina'] > 1)
      // {
      //     $params['offset'] = $params['limit'] * ($data['pagina'] - 1);
      // }

      $params['count'] = false;
      $contador_inicial = ($data['pagina'] - 1) * $params['limit'];
    
      $params['incluir_permisos'] = true;
      $params['considerar_solo_permisos'] = true;

      $calendario_data = $this->hojaasistencia->get_registro_asistencia($params, true, true);
    
      $params['modo_ver'] = MODOVERCALENDARIO_TARDANZAS;

      $this->load->view('exportar/xls_registrodeasistencia_permisos', array( 'calendario' => $calendario_data, 
                                                                              'rs_estados_dia' => $rs_estados_dia,
                                                                              'total_registros' => $total_registros,
                                                                              'params' => $params,
                                                                              'estructura_tabla_resumen' => $estructura,
                                                                              'categorias' => $categorias,
                                                                              'config' => $config,
                                                                              'paginaactual' => $data['pagina'],
                                                                              'contador_inicial' => $contador_inicial,
                                                                              'file_xls' => 'docsmpi/exportar/resumen_asistencia_excel2.xls'
                                                                             )); 

    }


} 