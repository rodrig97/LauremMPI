<?PHP 
 
$this->load->library('Excel');

$this->excel = new Excel();

$this->excel->setActiveSheetIndex(0);
 
$this->excel->getActiveSheet()->setTitle('DATA CERTIFICADOS'); // 3500
 

$labels =  array('#', 'Trabajador', 'DNI', 
                 'Regimen', 'Area', 'Proyecto', 
                 'Categoria', 'Ocupacion', 'Desde', 
                 'Hasta', 'Dias Laborados');


$columnas = array( 'A', 'B', 'C', 
                   'D', 'E', 'F', 
                   'G', 'H', 'I',
                   'J', 'K' ,
                    'L',
                   'M','N', 'O', 
                   'P');

$sizes = array(10, 30, 10, 
               20, 10, 40,
               25, 24, 15,
               15, 10 );



$sheet = $this->excel->getActiveSheet();
foreach($columnas as $k => $col)
{
     $sheet->getColumnDimension($col)->setWidth($sizes[$k]);
}


$fila = 1;

 $cc = 0;
 foreach ($labels as $p => $field)
 {
      
    $celda = $columnas[$p].trim($fila);

    $this->excel->getActiveSheet()->setCellValue( $celda, $field );
    $cc++;
 }

$c = 1;
$fila = 2;

foreach($detalle_certificados as $reg)
{
      
     $data = array();
   
     $data['col1']  = $c;
     $data['col2']  =   trim($reg['indiv_appaterno']).' '.trim($reg['indiv_apmaterno']).' '.trim($reg['indiv_nombres']);
     $data['col11'] =   trim($reg['indiv_dni']);
     $data['col3']  =   trim($reg['plati_nombre']);
     $data['col4']  =   (trim($reg['area_abrev']) != '' ? trim($reg['area_abrev']) : '------');
     $data['col5']  =   (trim($reg['proyecto']) != '') ? (trim($reg['ano_eje']).' - '.' '.trim($reg['sec_func']).' '.trim($reg['proyecto'])) : '-----------';
     $data['col6']  =   (trim($reg['platica_nombre']) != '' ? trim($reg['platica_nombre']) : '------'); 
     $data['col7']  =   (trim($reg['ocupacion']) != '' ? trim($reg['ocupacion']) : '------');   
     $data['col8']  =   _get_date_pg(trim($reg['fecha_ini']));
     $data['col9']  =   _get_date_pg(trim($reg['fecha_fin']));
     $data['col10'] =   trim($reg['duracion']);
 

     $this->excel->getActiveSheet()->setCellValue( ('A'.$fila), $c );
     
     $cc = 0;
     foreach ($data as $p => $field)
     {
          
        $celda = $columnas[$cc].trim($fila);
         
        $this->excel->getActiveSheet()->setCellValue( $celda, $field );
        $cc++;
     }
     $fila++; 

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

$sheet->setSharedStyle($sharedStyle1,'A1:K'.$fila); 

 
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  

$objWriter->save($file_xls);