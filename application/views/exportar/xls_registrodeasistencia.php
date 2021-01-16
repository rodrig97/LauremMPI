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


$n_fields = sizeof($calendario[0] );
  

if(sizeof($calendario) > 0  && is_array($calendario))
{ 
     

    $counter_trabajadores = $contador_inicial;

    $mes_act = 0;
    $cc_hm = 0; /* contador de dias del mes, para el colspan del header mes*/


    $trabajador_fila = '';

    $fila = 1;


foreach($calendario as $ind =>  $reg)
{ 

  $pos_letra = 5;

  if($ind==0)
  { 

    /* HEADER DEL CALENDARIO */ 
    $header = array_keys($reg); 

    $this->excel->getActiveSheet()->setCellValueExplicit( 'A'.$fila,'#', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->setCellValueExplicit( 'B'.$fila,'TRABAJADOR', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->setCellValueExplicit( 'C'.$fila,'METAS', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->setCellValueExplicit( 'D'.$fila,'DNI', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->setCellValueExplicit( 'E'.$fila,'CATEGORIA', PHPExcel_Cell_DataType::TYPE_STRING  ); 
 
    

      foreach($header as $k => $field)
      {
         if($k > 5 && validar_fecha_postgres($field) )
         { 
              $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, $field, PHPExcel_Cell_DataType::TYPE_STRING  ); 
              $pos_letra++;
         }  

      }

 

  }
  
  $fila++;
  $pos_letra = 5;

   
  $t_metas = array();

  for($tt=1; $tt<=6; $tt++)
  {
     if(trim($reg['tarea_'.$tt]) != '')
     {
        $t_metas[] = trim($reg['tarea_'.$tt]);
     }
  }
  $metas = implode(',' , $t_metas);

  $this->excel->getActiveSheet()->setCellValueExplicit( 'A'.$fila, $counter_trabajadores, PHPExcel_Cell_DataType::TYPE_STRING  ); 
  $this->excel->getActiveSheet()->setCellValueExplicit( 'B'.$fila, $reg['trabajador'], PHPExcel_Cell_DataType::TYPE_STRING  ); 
  $this->excel->getActiveSheet()->setCellValueExplicit( 'C'.$fila, $metas, PHPExcel_Cell_DataType::TYPE_STRING  ); 
  $this->excel->getActiveSheet()->setCellValueExplicit( 'D'.$fila, $reg['dni'], PHPExcel_Cell_DataType::TYPE_STRING  ); 
  $this->excel->getActiveSheet()->setCellValueExplicit( 'E'.$fila, ($reg['categoria'] == '' ? '---' : $reg['categoria']), PHPExcel_Cell_DataType::TYPE_STRING  ); 
 

  $counter_trabajadores++; 
    $col = 1;
    $cf = 1;

  foreach($reg as $k => $field)
  {
     
     if( validar_fecha_postgres($k) )
     {  

      $fecha_corta = _get_date_pg($k);   

      $ft =  strtotime($k);
      $anio = date('Y', $ft );
      $mes = $_MESES[date('n', $ft)];
      $dia = date('d', $ft);
      $dia_sem = date('N', $ft);
      $dial = $_DIAS_L[date('N', $ft)];
      $fecha_larga =  $dial.', '.$dia.' de '.$mes.' del '.$anio;
 
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
           
      $dia_importado = trim($data[15]);
      $pla_id = trim($data[16]);
      $pla_codigo = trim($data[17]);
      
     

      if( trim($field) == '' )
      {
        $tipo = ASISDET_NOCONSIDERADO;
      } 
 

        $label = ($tipo == ASISDET_NOCONSIDERADO ) ? '' : $rs_estados_dia[$tipo]['hatd_label'];
  
      
        if($tipo == ASISDET_NOCONSIDERADO && $obs != '' ) $label = 'Obs'; 
        
        if($tipo == ASISDET_ASISTENCIA  )
        {

            if( $tardanzas_horas !='0' || $tardanzas_minutos != '0' )
            {
               
               if( ($tardanzas_horas*1) > 0 || ($tardanzas_minutos * 1) > 0 )
               {

                 $minutos_tardanzas = $tardanzas_minutos*1;
                 $emin = ($tardanzas_horas > 0 ) ? ($tardanzas_horas*60) : 0;
                 $minutos_tardanzas+=$emin;
               }
          
            } 
      
        }  


        $obs = ($obs != '') ? $obs : '-------';
   
               
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
                if( ($tardanzas_horas*1) > 0 || ($tardanzas_minutos * 1) > 0 )
                {
                  $emin = ($tardanzas_horas > 0 ) ? ($tardanzas_horas*60) : 0;
                  $tardanzas_minutos+=$emin;
                } 
                else
                {
                  $tardanzas_minutos = '0';
                }
               
                $label = $tardanzas_minutos.' min ';

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

        $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, $label, PHPExcel_Cell_DataType::TYPE_STRING  ); 


        $pos_letra++;


     } /*  FIN IF PARA COMPROBAR SI ES UNA FECHA */ 
 


  } /*  FIN RECORRIDO COLUMNAS */
 
} /* FIN RECORRIDO REPORTE */

  

} /* FIN IF MAIN */
 


   

  $sharedStyle1 = new PHPExcel_Style();

  $sharedStyle1->applyFromArray( array('fill'   => array(
                                                         'type'    => PHPExcel_Style_Fill::FILL_SOLID,
                                                         'color'   => array('rgb' => 'ffffff')
                                                     ),
                                   
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
  $objWriter->save($file_xls);

