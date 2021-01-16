<?PHP 

 $this->load->library('Excel');


 $this->excel = new Excel();  
 
 $this->excel->setActiveSheetIndex(0); 

 $this->excel->getActiveSheet()->setTitle('Reporte de licencias por año'); 


 $columnas = array( 'A', 'B', 'C', 'D', 'E', 
                    'F', 'G', 'H', 'I', 'J', 
                    'K', 'L', 'M', 'N', 'O',
                    'P'  );

 $sizes    = array( 5, 32, 12,
                    20, 20, 11, 
                    11, 8, 8, 
                    15,
                    5, 10, 10, 10, 12,
                    10 );


 $sheet = $this->excel->getActiveSheet();


 foreach($columnas as $k => $col)
 {
      $sheet->getColumnDimension($col)->setWidth($sizes[$k]);
 }

 

 $this->excel->getActiveSheet()->setCellValue( 'A1', 'Reporte de licencias por Año' );
 $this->excel->getActiveSheet()->mergeCells('A1:C1');

 // $this->excel->getActiveSheet()->setCellValue( 'A2',  'AÑO - MES:');
 // $this->excel->getActiveSheet()->setCellValue( 'C2',  ( $reporte_info['anio'].' '.$reporte_info['mes'] ) );
 // $this->excel->getActiveSheet()->mergeCells('A2:B2');
 
 // $this->excel->getActiveSheet()->setCellValue( 'A6',  'PLANILLAS: ');
 // $this->excel->getActiveSheet()->setCellValue( 'C6',   ( trim($reporte_info['planillas']) != '' ? $reporte_info['planillas'] : '--------' )   );
 // $this->excel->getActiveSheet()->mergeCells('A6:B6');
 
 
$f = 3;
$finicio = $f;

$this->excel->getActiveSheet()->setCellValue( ('A'.$f), '#' );
$this->excel->getActiveSheet()->setCellValue( ('B'.$f), 'Trabajador' );
$this->excel->getActiveSheet()->setCellValue( ('C'.$f), 'DNI' );
$this->excel->getActiveSheet()->setCellValue( ('D'.$f), 'Tipo de Trabajador' );
$this->excel->getActiveSheet()->setCellValue( ('E'.$f), 'Tipo de Licencia' ); 
$this->excel->getActiveSheet()->setCellValue( ('F'.$f), 'Año' );
$this->excel->getActiveSheet()->setCellValue( ('G'.$f), 'Dias' ); 
  //  PHPExcel_Cell_DataType::TYPE_NUMERIC
 
 $c = 1; 
 $f++;
 foreach($reporte as $reg)
 { 
     

     $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
     $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
  
     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('B'.$f), 
         trim($reg['trabajador_nombre']),
         PHPExcel_Cell_DataType::TYPE_STRING
     ); 

     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('C'.$f), 
         trim($reg['trabajador_dni']),
         PHPExcel_Cell_DataType::TYPE_STRING
     );  

     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('D'.$f), 
         trim($reg['tipo_trabajador']),
         PHPExcel_Cell_DataType::TYPE_STRING
     );   


     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('E'.$f), 
         trim($reg['tipo']),
         PHPExcel_Cell_DataType::TYPE_STRING
     );   

     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('F'.$f), 
         trim($reg['anio']),
         PHPExcel_Cell_DataType::TYPE_STRING
     );  
 

     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('G'.$f), 
         trim($reg['numero_dias']),
         PHPExcel_Cell_DataType::TYPE_STRING
     );  
 

     $c++;
     $f++;
     
 }


 $sharedStyle1 = new PHPExcel_Style();

 $sharedStyle1->applyFromArray( array('fill'   => array(
                                                         'type'    => PHPExcel_Style_Fill::FILL_SOLID,
                                                         'color'   => array('rgb' => '4080bf')
                                                     ),
                                   
                                      'borders' => array(
                                                         'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                         'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                         'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                                         'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                                                      ) 
                                    ));

 $sheet->setSharedStyle($sharedStyle1,'A'.$finicio.':G'.$finicio); 


 $sharedStyle2 = new PHPExcel_Style();

 $sharedStyle2->applyFromArray( array('fill'   => array(
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

 $sheet->setSharedStyle($sharedStyle2,'A'.($finicio+1).':G'.$f); 

 
 //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
 //if you want to save it as .XLSX Excel 2007 format
 $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
 //force user to download the Excel file without writing it to server's HD
  

$filename = $nombre_archivo.'.xls';
$objWriter->save('docsmpi/exportar/'.$filename);
 
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

readfile('docsmpi/exportar/'.$nombre_archivo.'.xls'); 


