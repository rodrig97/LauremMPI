<?PHP


/*GENERANDO XLS*/ 


$this->load->library('Excel');

$this->excel = new Excel();

$this->excel->setActiveSheetIndex(0);
//name the worksheet
$this->excel->getActiveSheet()->setTitle('REPORTE DE BANCO'); // 3500
 

$columnas = array( 'A', 'B', 'C', 
                   'D', 'E', 'F', 
                   'G', 'H', 'I',
                   'J', 'K' );

$sizes    = array( 5, 12, 20, 
                  20, 20, 12 , 
                  20, 20, 12,
                  12, 12 );


$sheet = $this->excel->getActiveSheet();


foreach($columnas as $k => $col)
{
     $sheet->getColumnDimension($col)->setWidth($sizes[$k]);
}


$total = 0;
$c = 1;

$headers = array('#', 'Planilla', 'Apellido Paterno', 'Apellido Materno', 'Nombres', 'DNI', 'Banco', 'Cuenta bancaria', 'Monto', 'Tarea', 'Fuente' );


$this->excel->getActiveSheet()->setCellValue( 'A1', 'RESUMEN DE CUENTA DE DEPOSITO' );
$this->excel->getActiveSheet()->mergeCells('A1:C1');

$this->excel->getActiveSheet()->setCellValue( 'A2',  'AÑO - MES:');
$this->excel->getActiveSheet()->setCellValue( 'C2',  ( $reporte_info['anio'].' '.$reporte_info['mes'] ) );
$this->excel->getActiveSheet()->mergeCells('A2:B2');

$this->excel->getActiveSheet()->setCellValue( 'A3',  'BANCO: ');
$this->excel->getActiveSheet()->setCellValue( 'C3',  ( $reporte_info['banco'] ) );
$this->excel->getActiveSheet()->mergeCells('A3:B3');

$this->excel->getActiveSheet()->setCellValue( 'A4',  'Régimen : ');
$this->excel->getActiveSheet()->setCellValue( 'C4',  ( trim($reporte_info['plati']) != '' ? $reporte_info['plati'] : '--------' ) );
$this->excel->getActiveSheet()->mergeCells('A4:B4');

$this->excel->getActiveSheet()->setCellValue( 'A5',  'PLANILLAS: ');
$this->excel->getActiveSheet()->setCellValue( 'C5',   ( trim($reporte_info['planillas']) != '' ? $reporte_info['planillas'] : '--------' )   );
$this->excel->getActiveSheet()->mergeCells('A5:B5');

$f = 8;
foreach($headers as $k => $header)
{

  $pos = ( $columnas[$k].$f);

  $this->excel->getActiveSheet()->setCellValue( $pos, $header );
  $this->excel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $this->excel->getActiveSheet()->getStyle($pos)->getFont()->setBold(true);

} 

 
$f++;

$pla_codigo_ini = $reporte[0]['pla_codigo'];
$total_planilla = 0;

foreach($reporte as $reg)
{ 
      
    if($pla_codigo_ini != $reg['pla_codigo'] )
    {

        $pla_codigo_ini = $reg['pla_codigo'];
       
        $this->excel->getActiveSheet()->setCellValue( ('I'.$f), $total_planilla);
        $f+=2;
        $total_planilla = 0;
 
    }

    $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
    $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->excel->getActiveSheet()->setCellValue( ('B'.$f), utf8_decode(utf8_encode(trim($reg['pla_codigo'])) )    );
     
    $this->excel->getActiveSheet()->setCellValue( ('C'.$f), utf8_decode(utf8_encode(trim($reg['indiv_appaterno'])) )    );
    $this->excel->getActiveSheet()->setCellValue( ('D'.$f), utf8_decode(utf8_encode(trim($reg['indiv_apmaterno'])) ) );
    $this->excel->getActiveSheet()->setCellValue( ('E'.$f), utf8_decode(utf8_encode(trim($reg['indiv_nombres']) ) ) );
    $this->excel->getActiveSheet()->setCellValue( ('F'.$f), trim($reg['indiv_dni']) );
    $this->excel->getActiveSheet()->getStyle('F'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $this->excel->getActiveSheet()->setCellValue( ('G'.$f), trim($reg['ebanco_nombre']) );
    $this->excel->getActiveSheet()->getStyle('G'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
   // $this->excel->getActiveSheet()->setCellValue( ('G'.$f), trim($reg['pecd_cuentabancaria']) );

    $this->excel->getActiveSheet()->setCellValueExplicit(
        ('H'.$f), 
        trim($reg['pecd_cuentabancaria']),
        PHPExcel_Cell_DataType::TYPE_STRING
    );

    $this->excel->getActiveSheet()->setCellValue( ('I'.$f), sprintf("%01.2f", $reg['deposito']) );
    $this->excel->getActiveSheet()->getStyle('I'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

    $this->excel->getActiveSheet()->setCellValue( ('J'.$f), trim($reg['tarea_codigo']) );
    $this->excel->getActiveSheet()->setCellValue( ('K'.$f), trim($reg['fuente_codigo']) );

    $total_planilla+= $reg['deposito'];

    $f++;
    $c++;
  
}

  $this->excel->getActiveSheet()->setCellValue( ('I'.$f), $total_planilla);


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