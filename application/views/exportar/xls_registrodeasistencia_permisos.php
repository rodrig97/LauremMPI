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
 

    $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila_mes,'Total', PHPExcel_Cell_DataType::TYPE_STRING  ); 
    $this->excel->getActiveSheet()->mergeCells( $POSICIONES[$pos_letra].$fila_mes.':'.$POSICIONES[$pos_letra].$fila_dianumero );
    $pos_fin_dias++;

    $this->excel->getActiveSheet()->getColumnDimension($POSICIONES[$pos_letra])->setWidth(6);

    $this->excel->getActiveSheet()->setSharedStyle($estiloHeader, 'A1:'.($POSICIONES[$pos_fin_dias]).$fila_dianumero ); 

    $fila = $fila_dianumero + 1;
    $counter_trabajadores = 1;

    foreach($calendario as $ind =>  $reg)
    { 
 
      $this->excel->getActiveSheet()->setCellValueExplicit( 'A'.$fila, $counter_trabajadores, PHPExcel_Cell_DataType::TYPE_STRING  ); 
      $this->excel->getActiveSheet()->setCellValueExplicit( 'B'.$fila, $reg['dni'], PHPExcel_Cell_DataType::TYPE_STRING  ); 
      $this->excel->getActiveSheet()->setCellValueExplicit( 'C'.$fila, $reg['trabajador'], PHPExcel_Cell_DataType::TYPE_STRING  ); 
      
        
      $pos_letra = 3; 
      $pos_letra_inicio_mes = 3;
      
      $total_minutos_a_descontar = 0;

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

                  $dia_importado = trim($data[15]);
                  $pla_id = trim($data[16]);
                  $pla_codigo = trim($data[17]);

                  $minutos_a_descontar = trim($data[20]);
                       

                  // if( trim($field) == '' )
                  // {
                  //   $tipo = ASISDET_NOCONSIDERADO;
                  // } 

                  if($tipo == ASISDET_ASISTENCIA  )
                  {
                      $label = $minutos_a_descontar; 
                      $total_minutos_a_descontar+=$minutos_a_descontar;
                  }  
                  else{
                      $label = ''; 
                  }
                

                  // if($tipo == ASISDET_NOCONSIDERADO) {
                  //     $this->excel->getActiveSheet()->setSharedStyle($estiloCajaPloma,$POSICIONES[$pos_letra].$fila  ); 
                  // }

                  $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, $label, PHPExcel_Cell_DataType::TYPE_NUMERIC  ); 
                  $this->excel->getActiveSheet()->getStyle(($POSICIONES[$pos_letra].$fila))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                  $pos_letra++;


             } /*  FIN IF PARA COMPROBAR SI ES UNA FECHA */ 
           
          } /*  FIN RECORRIDO COLUMNAS */
          


          $this->excel->getActiveSheet()->setCellValueExplicit( $POSICIONES[$pos_letra].$fila, $total_minutos_a_descontar, PHPExcel_Cell_DataType::TYPE_STRING  ); 
          $this->excel->getActiveSheet()->getStyle(($POSICIONES[$pos_letra].$fila))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 

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

$filename ='reporte_asistencia_permisos.xls';
$objWriter->save('docsmpi/exportar/'.$filename);

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

readfile('docsmpi/exportar/'.$filename); 

