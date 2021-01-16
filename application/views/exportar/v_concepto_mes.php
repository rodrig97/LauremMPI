<?PHP 

$this->load->library('Excel');

$this->excel = new Excel();

$this->excel->setActiveSheetIndex(0);
 
$this->excel->getActiveSheet()->setTitle('Reporte Mensualizado'); // 3500
 

$columnas = array( 'A', 'B', 'C', 'D',
                   'E', 'F', 
                   'G', 'H', 'I',
                   'J', 'K','L','M','N','O','P' );

$sizes    = array( 5, 40, 18, 28, 
                   14,14,14,14, 14,14,14,14, 14,14,14,14,
                   14 );


$sheet = $this->excel->getActiveSheet();
 
foreach($columnas as $k => $col)
{
     $sheet->getColumnDimension($col)->setWidth($sizes[$k]);
}


$total = 0;
$c = 1;

$headers = array('#', 'Trabajador', 'DNI', 'Regimen Actual' );

$meses = array('enero','febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
$letras_meses = array('E','F','G','H','I','J','K','L','M','N','O','P');



$this->excel->getActiveSheet()->setCellValue( 'A1', 'RESUMEN MENSUALIZADO' );
$this->excel->getActiveSheet()->mergeCells('A1:M1');
$pi = 3;

if($reporte_info['con_regimen'] == true)
{
  $regimen = trim($reporte_info['planillatipo']);
}
else
{
   $regimen = ' NO ESPECIFICADO';
}

  $this->excel->getActiveSheet()->setCellValue( 'A'.$pi, 'REGIMEN :' );
  $this->excel->getActiveSheet()->setCellValue( 'C'.$pi, $regimen  );
  $this->excel->getActiveSheet()->mergeCells('A'.$pi.':B'.$pi);
  $this->excel->getActiveSheet()->mergeCells('C'.$pi.':M'.$pi);
  $pi++;


/* CONCEPTO GRUPO  BRUTO NETO COSTO  */
$this->excel->getActiveSheet()->setCellValue( 'A'.$pi,  $reporte_info['modo_label'].':' );
$this->excel->getActiveSheet()->setCellValue( 'C'.$pi,  trim($reporte_info['modo_nombre']) );
$this->excel->getActiveSheet()->mergeCells('A'.$pi.':B'.$pi);
$this->excel->getActiveSheet()->mergeCells('C'.$pi.':M'.$pi);
$pi++;

if($reporte_info['con_meta'] == true)
{
  $meta_presupuestal =  trim($reporte_info['tarea_nombre']);
}
else
{
  $meta_presupuestal = ' NO ESPECIFICADA';
}

  $this->excel->getActiveSheet()->setCellValue( 'A'.$pi, 'META PRESUPUESTAL:' );
  $this->excel->getActiveSheet()->setCellValue( 'C'.$pi, $meta_presupuestal );
  $this->excel->getActiveSheet()->mergeCells('A'.$pi.':B'.$pi);
  $this->excel->getActiveSheet()->mergeCells('C'.$pi.':M'.$pi);
  $pi++;



$f = 8;
$fila_header = $f;

foreach($headers as $k => $header)
{

  $pos = ( $columnas[$k].$f);

  $this->excel->getActiveSheet()->setCellValue( $pos, strtoupper($header) );
  $this->excel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $this->excel->getActiveSheet()->getStyle($pos)->getFont()->setBold(true);

} 

foreach($meses as $k => $mes)
{

  $pos = ( $letras_meses[$k].$f);

  $this->excel->getActiveSheet()->setCellValue( $pos, strtoupper($mes) );
  $this->excel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $this->excel->getActiveSheet()->getStyle($pos)->getFont()->setBold(true);

} 
 
$f++;


$totales_vertical = array(); 

foreach($reporte as $reg)
{ 
  
    $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
    $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 
    $this->excel->getActiveSheet()->setCellValue( ('B'.$f), utf8_decode(utf8_encode(trim($reg['trabajador'])) )    );
     
    $this->excel->getActiveSheet()->setCellValueExplicit(
        ('C'.$f), 
        trim($reg['dni']),
        PHPExcel_Cell_DataType::TYPE_STRING
    ); 

    $this->excel->getActiveSheet()->setCellValue( ('D'.$f), trim($reg['regimen_actual']) );

    $total_fila  = 0;

    foreach ($meses as $j => $mes)
    {
    
      $this->excel->getActiveSheet()->setCellValueExplicit(
          ($letras_meses[$j].$f), 
          sprintf("%01.2f", $reg[$mes]),
          PHPExcel_Cell_DataType::TYPE_NUMERIC
      ); 

      $t = (is_numeric($reg[$mes])) ? $reg[$mes] : 0;
      $total_fila+=$t;

      if($totales_vertical[$mes] == '') $totales_vertical[$mes] = 0;
      $totales_vertical[$mes]+=$t; 

    }
  
    $this->excel->getActiveSheet()->setCellValue( ('Q'.$f),  sprintf("%01.2f", $total_fila) );
 
    $f++;
    $c++;
  
} 

$total_fila = 0;
foreach ($meses as $j => $mes)
{

  $this->excel->getActiveSheet()->setCellValueExplicit(
      ($letras_meses[$j].$f), 
      sprintf("%01.2f", $totales_vertical[$mes] ),
      PHPExcel_Cell_DataType::TYPE_NUMERIC
  ); 

  $t = (is_numeric($totales_vertical[$mes])) ? $totales_vertical[$mes] : 0;
  $total_fila+=$t;
}
$this->excel->getActiveSheet()->setCellValue( ('Q'.$f),  sprintf("%01.2f", $total_fila) );

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

$sheet->setSharedStyle($sharedStyle1,'A1:Q'.$f); 


//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
//force user to download the Excel file without writing it to server's HD
$objWriter->save($file_xls);