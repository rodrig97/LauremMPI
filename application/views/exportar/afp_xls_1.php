<?PHP 

 $this->load->library('Excel');


 $this->excel = new Excel();  


 $this->excel->setActiveSheetIndex(0);
 //name the worksheet
 $this->excel->getActiveSheet()->setTitle('REPORTE DE AFPs'); // 3500


 $columnas = array( 'A', 'B', 'C', 'D', 'E', 
                    'F', 'G', 'H', 'I', 'J', 
                    'K', 'L', 'M', 'N', 'O',
                    'P'  );

 $sizes    = array( 5, 20, 20, 20, 12 , 
                    20, 20, 20, 5, 10,
                    5, 10, 10, 10, 12,
                    10 );


 $sheet = $this->excel->getActiveSheet();

 foreach($columnas as $k => $col)
 {
      $sheet->getColumnDimension($col)->setWidth($sizes[$k]);
 }


 $total = 0;
 $c = 1;

 $headers = array('#', 'Apellido Paterno', 'Apellido Materno', 'Nombres', 'DNI', 'Tipo de Trabajador', 'AFP', 'Codigo', 'IC', 'Fecha I/C', 'CS', 'Pensionable', 'Comision', 'Jubilacion', 'Invalidez', 'Aporte CS' );


 $this->excel->getActiveSheet()->setCellValue( 'A1', 'RESUMEN DE AFPs' );
 $this->excel->getActiveSheet()->mergeCells('A1:C1');

 $this->excel->getActiveSheet()->setCellValue( 'A2',  'AÑO - MES:');
 $this->excel->getActiveSheet()->setCellValue( 'C2',  ( $reporte_info['anio'].' '.$reporte_info['mes'] ) );
 $this->excel->getActiveSheet()->mergeCells('A2:B2');

 $this->excel->getActiveSheet()->setCellValue( 'A3',  'AFP: ');
 $this->excel->getActiveSheet()->setCellValue( 'C3',  ( trim($reporte_info['afp_nombre']) != '' ? $reporte_info['afp_nombre'] : '--------' ) );
 $this->excel->getActiveSheet()->mergeCells('A3:B3');

 $this->excel->getActiveSheet()->setCellValue( 'A4',  'Régimen : ');
 $this->excel->getActiveSheet()->setCellValue( 'C4',  ( trim($reporte_info['plati']) != '' ? $reporte_info['plati'] : '--------' ) );
 $this->excel->getActiveSheet()->mergeCells('A4:B4');

 $this->excel->getActiveSheet()->setCellValue( 'A5',  'TIPO DE GASTO : ');
 $this->excel->getActiveSheet()->setCellValue( 'C5',  ( trim($reporte_info['tipogasto']) != '' ? $reporte_info['tipogasto'] : '--------' ) );
 $this->excel->getActiveSheet()->mergeCells('A5:B5');

 $this->excel->getActiveSheet()->setCellValue( 'A6',  'PLANILLAS: ');
 $this->excel->getActiveSheet()->setCellValue( 'C6',   ( trim($reporte_info['planillas']) != '' ? $reporte_info['planillas'] : '--------' )   );
 $this->excel->getActiveSheet()->mergeCells('A6:B6');

 $f = 9;
 foreach($headers as $k => $header)
 {

   $pos = ( $columnas[$k].$f);

   $this->excel->getActiveSheet()->setCellValue( $pos, $header );
   $this->excel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   $this->excel->getActiveSheet()->getStyle($pos)->getFont()->setBold(true);

 } 

  
 $f++;

 foreach($reporte as $reg)
 { 
     

     $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
     $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

     $this->excel->getActiveSheet()->setCellValue( ('B'.$f), utf8_decode(utf8_encode(trim($reg['indiv_appaterno'])) )    );
     $this->excel->getActiveSheet()->setCellValue( ('C'.$f), utf8_decode(utf8_encode(trim($reg['indiv_apmaterno'])) ) );
     $this->excel->getActiveSheet()->setCellValue( ('D'.$f), utf8_decode(utf8_encode(trim($reg['indiv_nombres'])) ) );
     /*
     $this->excel->getActiveSheet()->setCellValue( ('E'.$f), trim($reg['indiv_dni']) );
     $this->excel->getActiveSheet()->getStyle('E'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
*/

     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('E'.$f), 
         trim($reg['indiv_dni']),
         PHPExcel_Cell_DataType::TYPE_STRING
     ); 
     
     $this->excel->getActiveSheet()->setCellValue( ('F'.$f), trim($reg['regimen']) );
     $this->excel->getActiveSheet()->getStyle('F'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
    
     $this->excel->getActiveSheet()->setCellValue( ('G'.$f), trim($reg['afp_nombre']) ); 
     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('H'.$f), 
         utf8_decode(trim($reg['peaf_codigo'])),
         PHPExcel_Cell_DataType::TYPE_STRING
     ); 

     $fecha = (trim($reg['fecha']) != '' ) ? _get_date_pg(trim($reg['fecha'])) : '';

     $this->excel->getActiveSheet()->setCellValue( ('I'.$f), trim($reg['tipo']) );
     $this->excel->getActiveSheet()->setCellValue( ('J'.$f), $fecha );
     $this->excel->getActiveSheet()->setCellValue( ('K'.$f), trim($reg['cs']) ); 
 

     $pensionable = trim($reg['PENSIONABLE']);
     $comision    = trim($reg['AFP - COMISION']);
     $jubilacion = trim($reg['AFP - JUBILACION']);
     $seguro = trim($reg['AFP - SEGURO']);

     $aporte = trim($reg['AFP APORTE CONS.CIV']);

     $this->excel->getActiveSheet()->setCellValue( ('L'.$f), $pensionable );
     $this->excel->getActiveSheet()->setCellValue( ('M'.$f), $comision );
     $this->excel->getActiveSheet()->setCellValue( ('N'.$f), $jubilacion );
     $this->excel->getActiveSheet()->setCellValue( ('O'.$f), $seguro );
     $this->excel->getActiveSheet()->setCellValue( ('p'.$f), $aporte );

/*     if($reg['plati_id'] == TIPOPLANILLA_CONSCIVIL )
     {

            $t_c = $comision / $pensionable;
            $t_s = $seguro / $pensionable; 

            $jubilacion_n = $pensionable * 0.11;

            $pensionable-= ($jubilacion_n - $jubilacion);

            $jubilacion = $jubilacion_n;

            $comision = $pensionable * $t_c;

            $seguro = $pensionable * $t_s;

            $aporte = $pensionable * 0.001;
     }




     $this->excel->getActiveSheet()->setCellValue( ('Q'.$f), $pensionable );
     $this->excel->getActiveSheet()->setCellValue( ('R'.$f), $comision );
     $this->excel->getActiveSheet()->setCellValue( ('S'.$f), $jubilacion );
     $this->excel->getActiveSheet()->setCellValue( ('T'.$f), $seguro );
     $this->excel->getActiveSheet()->setCellValue( ('U'.$f), $aporte );*/


     $total+= $reg['deposito'];

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

 $sheet->setSharedStyle($sharedStyle1,'A1:P'.$f); 


 //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
 //if you want to save it as .XLSX Excel 2007 format
 $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
 //force user to download the Excel file without writing it to server's HD
 $objWriter->save($file_xls);

