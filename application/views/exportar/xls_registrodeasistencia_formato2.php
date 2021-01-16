<?PHP 
 

if(function_exists('validar_fecha_postgres') == FALSE)
{

  function validar_fecha_postgres($fecha)
  {
      list($anio, $mes, $dia) = explode('-', $fecha);

      if( strlen($anio) == 4 && strlen($mes) == 2 && strlen($dia) == 2 )
      {
           return true;
      }
      else
      {
           return false;
      }
  }
   
}


 $this->load->library('Excel');
 
 $this->excel = new Excel();  
 
 $hoja = 0;

 $this->excel->setActiveSheetIndex($hoja);
 //name the worksheet
 $this->excel->getActiveSheet()->setTitle('REGISTRO DE ASISTENCIA'); // 3500
 
 
 $letras = array('A', 'B', 'C', 'D', 'E', 
                    'F', 'G', 'H', 'I', 'J', 
                    'K', 'L', 'M', 'N', 'O',
                    'P', 'Q','R','S','T','U','V','W','X','Y','Z' );

$POSICIONES = $letras;
$cl = 1;
foreach ($letras as $a)
{ 

   if($cl==3) break;
   foreach ($letras as $b)
   {  
      $POSICIONES[] = $a.$b;
   }
   $cl++;
}

 

if(sizeof($calendario) == 0)
{
 

}
 
$_DIAS = array(
        '1' => 'L',
        '2' => 'M',
        '3' => 'M',
        '4' => 'J',
        '5' => 'V',
        '6' => 'S',
        '7' => 'D'  );

$_DIAS_L = array(
        '1' => 'Lunes',
        '2' => 'Martes',
        '3' => 'Miercoles',
        '4' => 'Jueves',
        '5' => 'Viernes',
        '6' => 'Sabado',
        '7' => 'Domingo'  );


$_MESES = array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');


$estiloCajaPloma = new PHPExcel_Style();

$estiloCajaPloma->applyFromArray( array('fill'   => array(
                                                       'type'    => PHPExcel_Style_Fill::FILL_SOLID,
                                                       'color'   => array('rgb' => 'dadada')
                                                   ),
                                 
                                    'borders' => array(
                                                       'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                                                    ) 
                                  ));



$estiloHeader = new PHPExcel_Style();

$estiloHeader->applyFromArray( array('fill'   => array(
                                                       'type'    => PHPExcel_Style_Fill::FILL_SOLID,
                                                       'color'   => array('rgb' => '7dd7ff')
                                                   ),
                                 
                                    'borders' => array(
                                                       'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                                                    ),
                                    
                                    'alignment' => array(
                                                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                      'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER  
                                    )

                                  ));



$n_fields = sizeof($calendario[0] );
  
$hojaExcel = $this->excel->getActiveSheet();

$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(34);
 

if(sizeof($calendario) > 0  && is_array($calendario))
{   

    $fila_mes = 1;
    $fila_diasemana = 2;
    $fila_dianumero = 3;

 
    $this->excel->getActiveSheet()->setCellValueExplicit( 'A'.$fila_mes,'#', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->setCellValueExplicit( 'B'.$fila_mes,'DNI', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->setCellValueExplicit( 'C'.$fila_mes,'TRABAJADOR', PHPExcel_Cell_DataType::TYPE_STRING  ); 
   
    $header = array_keys($calendario[0]);  
    
    $this->excel->getActiveSheet()->mergeCells( 'A'.$fila_mes.':A'.$fila_dianumero );
    $this->excel->getActiveSheet()->mergeCells( 'B'.$fila_mes.':B'.$fila_dianumero );
    $this->excel->getActiveSheet()->mergeCells( 'C'.$fila_mes.':C'.$fila_dianumero );
     
    $mes_actual = '';
    $pos_letra = 3; 
    $pos_fin_dias = 3;
    $pos_letra_inicio_mes = 3;
 
    foreach ($header as $datoHeader) {
        
        if( validar_fecha_postgres($datoHeader) ){  
 
            $fecha = $datoHeader; 
            $fechaTime =  strtotime($fecha);
                         
            $dia = date('j', $fechaTime);
            $mes = date('n', $fechaTime);
            $diaSemana = date('N', $fechaTime);
            $anio = date('Y', $fechaTime);
            $mk_limite  =  mktime(0,0,0,$mes,$dia,$ano);    

            if($mes_actual == ''){
               $mes_actual = $mes; 
            }


            if($mes != $mes_actual){
 
                $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra_inicio_mes].$fila_mes, $_MESES[trim($mes_actual)], PHPExcel_Cell_DataType::TYPE_STRING  ); 
                $this->excel->getActiveSheet()->getStyle(($POSICIONES[$pos_letra_inicio_mes].$fila_mes))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra_inicio_mes].$fila_mes.':'.$POSICIONES[($pos_letra-1)].$fila_mes );
                $mes_actual =  $mes;
                $pos_letra_inicio_mes = $pos_letra;

            }

            $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_diasemana, $_DIAS[trim($diaSemana)], PHPExcel_Cell_DataType::TYPE_STRING  ); 
            $this->excel->getActiveSheet()->getStyle(($POSICIONES[$pos_letra].$fila_diasemana))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    
            $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_dianumero, $dia, PHPExcel_Cell_DataType::TYPE_STRING  ); 
            $this->excel->getActiveSheet()->getStyle(($POSICIONES[$pos_letra].$fila_dianumero))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $pos_fin_dias = $pos_letra;
            $pos_letra++;
        }
 
    }   
 
    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra_inicio_mes].$fila_mes, $_MESES[trim($mes_actual)], PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->getStyle(($POSICIONES[$pos_letra_inicio_mes].$fila_mes))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra_inicio_mes].$fila_mes.':'.$POSICIONES[($pos_letra-1)].$fila_mes );
   
    
    for($pt=3; $pt<=$pos_fin_dias; $pt++){ 
        
        $this->excel->getActiveSheet()->getColumnDimension($POSICIONES[$pt])->setWidth(4);
    }

    // Imprimimos el resumen
    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'TARDANZA', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'CITACION JUDICIAL', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'LIC.CON GOCE', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'FALTA INJUSTIFICADA', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'PERMISO ONOMASTICO', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'PERMISO PARTICULAR', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'DESCANSO MEDICO', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'COMISION DE SERVICIOS', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'LICENCIA CAPACITACION', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'LICENCIA PATERNIDAD', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    


    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'LICENCIA FALLECIMIENTO', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    


    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'GOCE VACACIONAL', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'SUSPENSION TEMPORAL', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'COMPENSACION HORAS', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'CITA MEDICA', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    
 
    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'LICENCIA SINDICAL', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    
 
    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'DIA NO LABORADOS', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes, 'DIA EFECTIVOS LABORADOS', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_letra++;    


 
    $this->excel->getActiveSheet()->setSharedStyle($estiloHeader, 'A1:'.($POSICIONES[($pos_letra-1)]).$fila_dianumero ); 

    $fila = $fila_dianumero + 1;
    $counter_trabajadores = 1;

    foreach($calendario as $ind =>  $reg)
    { 

      //   $pos_letra = 5;

      //   if($ind==0)
      //   { 

      //       /* HEADER DEL CALENDARIO */ 
      //       $header = array_keys($reg); 

      //       $this->excel->getActiveSheet()->setCellValueExplicit( 'A'.$fila,'#', PHPExcel_Cell_DataType::TYPE_STRING  ); 
      //       $this->excel->getActiveSheet()->setCellValueExplicit( 'B'.$fila,'DNI', PHPExcel_Cell_DataType::TYPE_STRING  ); 
      //       $this->excel->getActiveSheet()->setCellValueExplicit( 'C'.$fila,'TRABAJADOR', PHPExcel_Cell_DataType::TYPE_STRING  ); 
          
      //       foreach($header as $k => $field)
      //       {
      //          if($k > 5 && validar_fecha_postgres($field) )
      //          { 
      //               $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, $field, PHPExcel_Cell_DataType::TYPE_STRING  ); 
      //               $pos_letra++;
      //          }  

      //       }
       
      //   }
        
      // $fila++;
      // $pos_letra = 5;
     

      // $this->excel->getActiveSheet()->setCellValueExplicit( 'A'.$fila, $counter_trabajadores, PHPExcel_Cell_DataType::TYPE_STRING  ); 
      // $this->excel->getActiveSheet()->setCellValueExplicit( 'B'.$fila, $reg['dni'], PHPExcel_Cell_DataType::TYPE_STRING  ); 
      // $this->excel->getActiveSheet()->setCellValueExplicit( 'C'.$fila, $reg['trabajador'], PHPExcel_Cell_DataType::TYPE_STRING  );  

      // $counter_trabajadores++; 

      $this->excel->getActiveSheet()->setCellValueExplicit( 'A'.$fila, $counter_trabajadores, PHPExcel_Cell_DataType::TYPE_STRING  ); 
      $this->excel->getActiveSheet()->setCellValueExplicit( 'B'.$fila, $reg['dni'], PHPExcel_Cell_DataType::TYPE_STRING  ); 
      $this->excel->getActiveSheet()->setCellValueExplicit( 'C'.$fila, $reg['trabajador'], PHPExcel_Cell_DataType::TYPE_STRING  ); 
      
        
      $pos_letra = 3; 
      $pos_letra_inicio_mes = 3;

      $minutosTardanzasPeriodoTrabajador = 0;
      $dias_no_laborados = 0;
      $dias_efectivamente_laborados = 0; 

      // Imprimimos el calendario
          foreach($reg as $k => $field)
          {
              
             if( validar_fecha_postgres($k) )
             {  

                  $tardanzas_minutos_dia = 0;
                  $data      = explode('_',$field);
             
                  $tipo      = trim($data[0]);
                  $obs     = trim($data[1]);
                  
                  $tardanzas_horas = trim($data[2]);
                  $tardanzas_minutos = trim($data[3]);
             
                  $permisos  = trim($data[4]);
                  $sin_contrato  = trim($data[5]); 
                  $laborable  = trim($data[6]);
                  $biometrico_id  = trim($data[7]);
                  $horas_trabajadas = trim($data[8]);
                  $min_trabajadas = trim($data[9]);
                  $horas_contabilizadas = trim($data[10]);

                  list($horas, $minutos, $segundos) = explode(':', $data[11]);
                  $marcacion1 = $horas.':'.$minutos;    
                  list($horas, $minutos, $segundos) = explode(':', $data[12]);
                  $marcacion2 = $horas.':'.$minutos;    
                  list($horas, $minutos, $segundos) = explode(':', $data[13]);
                  $marcacion3 = $horas.':'.$minutos;  
                  list($horas, $minutos, $segundos) = explode(':', $data[14]);
                  $marcacion4 = $horas.':'.$minutos;      
                       
                  if( trim($field) == '' )
                  {
                    $tipo = ASISDET_NOCONSIDERADO;
                  } 
 
                  $label = ($tipo == ASISDET_NOCONSIDERADO ) ? '' : $rs_estados_dia[$tipo]['hatd_label'];
               
                    
                  if($tipo == ASISDET_ASISTENCIA  )
                  {

                      if( $tardanzas_horas !='0' || $tardanzas_minutos != '0' )
                      {
                         
                         if( ($tardanzas_horas*1) > 0 || ($tardanzas_minutos * 1) > 0 )
                         {
 
                           $horas_tardanzas_en_minutos = ($tardanzas_horas > 0 ) ? ($tardanzas_horas*60) : 0;
                           $tardanzas_minutos_dia =  ($tardanzas_minutos * 1) + $horas_tardanzas_en_minutos;
                           $minutosTardanzasPeriodoTrabajador+=$tardanzas_minutos_dia;
                         }
                    
                      } 
                      
                      $dias_efectivamente_laborados++;

                  }  
                  else{
                      $dias_no_laborados++;
                  }
               
                           
                    if( $params['modo_ver'] == MODOVERCALENDARIO_XDEFECTO )
                    { 
                         $label =  $label;  
                    }
                    else if (  $params['modo_ver'] == MODOVERCALENDARIO_HORASASISTENCIA )
                    {
                    
                        if($tipo == ASISDET_ASISTENCIA)
                        { 
                            if($config['diario_tipo_horatrabajadas'] == '1')
                            {
                                $label = $horas_trabajadas.'h '.$min_trabajadas.'m';
                            }
                            else
                            {
                                $label = $horas_contabilizadas;
                            }
                        }
                        else
                        {
                            $label = $label;  
                        }

                    } 
                    else if (  $params['modo_ver'] == MODOVERCALENDARIO_TARDANZAS )
                    {
                        if($tipo == ASISDET_ASISTENCIA)
                        { 
                           
                            $label = ( trim($tardanzas_minutos_dia) != '' && ($tardanzas_minutos_dia*1) > 0 ) ? $tardanzas_minutos_dia : '';

                        }
                        else
                        {
                            $label = $label;  
                        }  
                    } 
                    else if (  $params['modo_ver'] == MODOVERCALENDARIO_MARCACION1 )
                    {  
                          $label = ($tipo == ASISDET_ASISTENCIA ? $marcacion1 : $label);
                    } 
                    else if (  $params['modo_ver'] == MODOVERCALENDARIO_MARCACION2 )
                    {
                          $label = ($tipo == ASISDET_ASISTENCIA ? $marcacion2 : $label);
                    }
                    else if (  $params['modo_ver'] == MODOVERCALENDARIO_MARCACION3 )
                    {
                          $label = ($tipo == ASISDET_ASISTENCIA ? $marcacion3 : $label);
                    }
                    else if (  $params['modo_ver'] == MODOVERCALENDARIO_MARCACION4 )
                    {
                          $label = ($tipo == ASISDET_ASISTENCIA ? $marcacion4 : $label);
                    }   
                    else
                    {
                          $label = $label;  
                    }

                    if($tipo == ASISDET_NOCONSIDERADO) {
                        $this->excel->getActiveSheet()->setSharedStyle($estiloCajaPloma,$POSICIONES[$pos_letra].$fila  ); 
                    }

                    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, $label, PHPExcel_Cell_DataType::TYPE_STRING  ); 
                    $this->excel->getActiveSheet()->getStyle(($POSICIONES[$pos_letra].$fila))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                    $pos_letra++;


             } /*  FIN IF PARA COMPROBAR SI ES UNA FECHA */ 
           
          } /*  FIN RECORRIDO COLUMNAS */
      
        // Imprimimos el resumen 

          // define('ASISDET_NOCONSIDERADO','0');
          // define('ASISDET_ASISTENCIA','1');
          // define('ASISDET_PERMISOSPARTICULARES','2');
          // define('ASISDET_DESCANSOMEDICO','3');
          // define('ASISDET_COMISIONSERV','4');
          // define('ASISDET_LICENCIASIND','5');
          // define('ASISDET_VACACIONES','6');
          // define('ASISDET_FALTA','7'); 
          // define('ASISDET_FALTAJUSTIFICADA','8'); 
          // define('ASISDET_LICENCIAGOCE','9'); 
          // define('ASISDET_LICENCIASINGOCE','10');
          // define('ASISDET_LICENCIA_SINDICAL','15');
          // define('ASISDET_TARDANZAS','11');  
          // define('ASISDET_CONTRATOVENCIDO','12');  
          // define('ASISDET_INDEFINIDO','13');  
          // define('ASISDET_DESCANSOSEMANAL','20');  
          // ASISDET_LICE_ONOMASTICO,56
          // define('ASISDET_CITAMEDICA','54');
          // define('ASISDET_CITAJUDICIAL','57');
          // define('ASISDET_LICE_CAPACITACION','59');
          // define('ASISDET_LICE_PATERNIDAD','60');
          // define('ASISDET_LICE_FALLECIMIENTO','61');
          // define('ASISDET_SUSPENSION_TEMPORAL','62');


         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($minutosTardanzasPeriodoTrabajador), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    

         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_CITAJUDICIAL)] == '' ? 0 : $reg['dato_'.trim(ASISDET_CITAJUDICIAL)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    

         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_LICENCIAGOCE)] == '' ? 0 : $reg['dato_'.trim(ASISDET_LICENCIAGOCE)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    

         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_FALTA)] == '' ? 0 : $reg['dato_'.trim(ASISDET_FALTA)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    

         // Permiso onomastico
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_LICE_ONOMASTICO)] == '' ? 0 : $reg['dato_'.trim(ASISDET_LICE_ONOMASTICO)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    

         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_PERMISOSPARTICULARES)] == '' ? 0 : $reg['dato_'.trim(ASISDET_PERMISOSPARTICULARES)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    

         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_DESCANSOMEDICO)] == '' ? 0 : $reg['dato_'.trim(ASISDET_DESCANSOMEDICO)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // comision de serv
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_COMISIONSERV)] == '' ? 0 : $reg['dato_'.trim(ASISDET_COMISIONSERV)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // Lic capacitacion
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_LICE_CAPACITACION)] == '' ? 0 : $reg['dato_'.trim(ASISDET_LICE_CAPACITACION)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // PAternidad
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_LICE_PATERNIDAD)] == '' ? 0 : $reg['dato_'.trim(ASISDET_LICE_PATERNIDAD)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // Fallecimiento
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_LICE_FALLECIMIENTO)] == '' ? 0 : $reg['dato_'.trim(ASISDET_LICE_FALLECIMIENTO)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // Vacacional
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_VACACIONES)] == '' ? 0 : $reg['dato_'.trim(ASISDET_VACACIONES)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // Suspension
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_SUSPENSION_TEMPORAL)] == '' ? 0 : $reg['dato_'.trim(ASISDET_SUSPENSION_TEMPORAL)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // Compensacion
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(COMPENSA)] == '' ? 0 : $reg['dato_'.trim(COMPENSA)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // Cita medica
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_CITAMEDICA)] == '' ? 0 : $reg['dato_'.trim(ASISDET_CITAMEDICA)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         // Licencia Sindical
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, ($reg['dato_'.trim(ASISDET_LICENCIASIND)] == '' ? 0 : $reg['dato_'.trim(ASISDET_LICENCIASIND)]), PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    
         
         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, $dias_no_laborados, PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    

         $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, $dias_efectivamente_laborados, PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
         $pos_letra++;    


        $fila++; 
        $counter_trabajadores++;

    } /* FIN RECORRIDO REPORTE */ 


} /* FIN IF MAIN */

$sharedStyle1 = new PHPExcel_Style();

$sharedStyle1->applyFromArray( array( 
                                 
                                    'borders' => array(
                                                       'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                       'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                                                    ) 
                                  ));

//$sheet->setSharedStyle($sharedStyle1,'A1:'.($POSICIONES[$columna_indice -1]).$f ); 


//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
//force user to download the Excel file without writing it to server's HD

$filename ='reporte_asistencia_formato2.xls';
$objWriter->save('docsmpi/exportar/'.$filename);

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

readfile('docsmpi/exportar/'.$filename); 

