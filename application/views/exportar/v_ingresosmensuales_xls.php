<?PHP 

$this->load->library('Excel');

$this->excel = new Excel();

$this->excel->setActiveSheetIndex(0);
//name the worksheet
$this->excel->getActiveSheet()->setTitle('Ingresos Mensuales'); // 3500
 

$columnas = array( 'A', 'B', 'C', 
                   'D', 'E', 'F', 
                   'G', 'H', 'I',
                   'J', 'K','L','M','N','O','P' );

$sizes    = array( 5, 12, 20, 
                  20, 20, 12 , 
                  20, 20, 12,
                  12, 12,12,12,12,12,12,12,12,12,12,12 );


$sheet = $this->excel->getActiveSheet();


foreach($columnas as $k => $col)
{
     $sheet->getColumnDimension($col)->setWidth($sizes[$k]);
}


$total = 0;
$c = 1;

$headers = array('#', 'Trabajador', 'DNI', 'Regimen' );

$meses = array('enero','febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
$letras_meses = array('E','F','G','H','I','J','K','L','M','N','O','P');



$this->excel->getActiveSheet()->setCellValue( 'A1', 'RESUMEN DE INGRESOS MENSUALES' );
$this->excel->getActiveSheet()->mergeCells('A1:C1');
 
$f = 3;
foreach($headers as $k => $header)
{

  $pos = ( $columnas[$k].$f);

  $this->excel->getActiveSheet()->setCellValue( $pos, $header );
  $this->excel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $this->excel->getActiveSheet()->getStyle($pos)->getFont()->setBold(true);

} 

foreach($meses as $k => $mes)
{

  $pos = ( $letras_meses[$k].$f);

  $this->excel->getActiveSheet()->setCellValue( $pos, $mes );
  $this->excel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $this->excel->getActiveSheet()->getStyle($pos)->getFont()->setBold(true);

} 
 
$f++;


 

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

    $this->excel->getActiveSheet()->setCellValue( ('D'.$f), trim($reg['plati_nombre']) );

    foreach ($meses as $j => $mes)
    {
    
      $this->excel->getActiveSheet()->setCellValueExplicit(
          ($letras_meses[$j].$f), 
          sprintf("%01.2f", $reg[$mes]),
          PHPExcel_Cell_DataType::TYPE_NUMERIC
      ); 

    }





 
    $f++;
    $c++;
  
} 


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

$sheet->setSharedStyle($sharedStyle1,'A1:K'.$f); 


//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
//force user to download the Excel file without writing it to server's HD
$objWriter->save($file_xls);