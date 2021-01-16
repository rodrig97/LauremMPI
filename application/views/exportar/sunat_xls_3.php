<?PHP 

 $this->load->library('Excel');


 $this->excel = new Excel();  


 $this->excel->setActiveSheetIndex(0);
 //name the worksheet
 $this->excel->getActiveSheet()->setTitle('SUNAT_PORPLANILLA'); // 3500


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

 $headers = array('#', 'PLANILLA','REGIMEN', 'FUENTE' );


 $this->excel->getActiveSheet()->setCellValue( 'A1', 'CONSOLIDADO SUNAT POR PLANILLA' );
 $this->excel->getActiveSheet()->mergeCells('A1:C1');

 $this->excel->getActiveSheet()->setCellValue( 'A2',  'AÑO - MES:');
 $this->excel->getActiveSheet()->setCellValue( 'C2',  ( $reporte_info['anio'].' '.$reporte_info['mes'] ) );
 $this->excel->getActiveSheet()->mergeCells('A2:B2');
 
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
 

 $POSICIONES = array('E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T');
 $columna_indice = 0;

 foreach ($reporte[0] as $key => $dato)
 {  
 
   if(strpos($key, 'DATO_' ) !== FALSE)
   {

        list($p, $dato_label) = explode('_', $key);

        $this->excel->getActiveSheet()->setCellValueExplicit(
            ( $POSICIONES[$columna_indice].$f), 
            trim($dato_label),
            PHPExcel_Cell_DataType::TYPE_STRING
        ); 

        $columna_indice++;
    
   }
 }

  
 $f++;



 foreach($reporte as $reg)
 { 
     

     $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
     $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
  
     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('B'.$f), 
         trim($reg['pla_codigo']),
         PHPExcel_Cell_DataType::TYPE_STRING
     ); 

     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('C'.$f), 
         trim($reg['plati_nombre']),
         PHPExcel_Cell_DataType::TYPE_STRING
     ); 

     $fuente = $reg['fuente_id'].'-'.$reg['tipo_recurso'];

     $this->excel->getActiveSheet()->setCellValueExplicit(
         ('D'.$f), 
         trim($fuente),
         PHPExcel_Cell_DataType::TYPE_STRING
     ); 


        
     $columna_indice = 0;
     foreach ($reg as $key => $dato)
     {  
     
       if(strpos($key, 'DATO_' ) !== FALSE)
       {

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ( $POSICIONES[$columna_indice].$f), 
                trim($dato),
                PHPExcel_Cell_DataType::TYPE_NUMERIC
            ); 
            $sheet->getColumnDimension($POSICIONES[$columna_indice])->setWidth(15);

            $columna_indice++;


       }
     }


     $c++;
     $f++;
     
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

