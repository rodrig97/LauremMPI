<?PHP 
  /*GENERANDO XLS 2*/ 
  $this->load->library('Excel');

 
   $this->excel = new Excel();  

   $this->excel->setActiveSheetIndex(0);
   //name the worksheet
   $this->excel->getActiveSheet()->setTitle('REPORTE DE AFPs'); // 3500


   $columnas = array( 'A', 'B', 'C', 'D', 'E', 
                      'F', 'G', 'H', 'I', 'J' );

   $sizes    = array( 5, 20, 10, 20, 12 , 
                      20, 8, 10, 12, 6 );


   $sheet = $this->excel->getActiveSheet();


   foreach($columnas as $k => $col)
   {
        $sheet->getColumnDimension($col)->setWidth($sizes[$k]);
   }

  
   $total = 0;
 
   /*
   $headers = array('#','Codigo', 'DNI', 'Apellido Paterno', 'Apellido Materno', 'Nombres', 'IC', 'Fecha I/C', 'Pensionable', 'CS' );

 

   foreach($headers as $k => $header)
   {

     $pos = ( $columnas[$k].$f);

     $this->excel->getActiveSheet()->setCellValue( $pos, $header );
     $this->excel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     $this->excel->getActiveSheet()->getStyle($pos)->getFont()->setBold(true);

   } */

   $f = 1; 
   $c = 1; 
  
   // C cusp tipodoc dni

   $tipo_doc = '0';
   $aporte_afil_fp = '0';
   $aporte_afil_sfp = '0';
   $aporte_voluntario_empleador = '0';


   foreach($reporte as $reg)
   { 
       

       $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
       $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

       $this->excel->getActiveSheet()->setCellValueExplicit(
           ('B'.$f), 
           utf8_decode(trim($reg['peaf_codigo'])),
           PHPExcel_Cell_DataType::TYPE_STRING
       );  

       $this->excel->getActiveSheet()->setCellValueExplicit(
           ('C'.$f), 
           $tipo_doc,
           PHPExcel_Cell_DataType::TYPE_STRING
       ); 

       $this->excel->getActiveSheet()->setCellValueExplicit(
           ('D'.$f), 
           trim($reg['indiv_dni']),
           PHPExcel_Cell_DataType::TYPE_STRING
       ); 

       $this->excel->getActiveSheet()->setCellValue( ('E'.$f), utf8_decode(utf8_encode(trim($reg['indiv_appaterno'])) )    );
       $this->excel->getActiveSheet()->setCellValue( ('F'.$f), utf8_decode(utf8_encode(trim($reg['indiv_apmaterno'])) ) );
       $this->excel->getActiveSheet()->setCellValue( ('G'.$f), utf8_decode(utf8_encode(trim($reg['indiv_nombres'])) ) );
         
       $this->excel->getActiveSheet()->setCellValue( ('H'.$f), trim($reg['tipo']) );
       $this->excel->getActiveSheet()->setCellValue( ('I'.$f), trim($reg['fecha']) );
     
       $this->excel->getActiveSheet()->setCellValue( ('J'.$f), trim($reg['PENSIONABLE']) );
       $this->excel->getActiveSheet()->setCellValue( ('K'.$f), $aporte_afil_fp );
       $this->excel->getActiveSheet()->setCellValue( ('L'.$f), $aporte_afil_sfp );
       $this->excel->getActiveSheet()->setCellValue( ('M'.$f), $aporte_voluntario_empleador );
       
       $this->excel->getActiveSheet()->setCellValue( ('N'.$f), trim($reg['cs']) );  
       $this->excel->getActiveSheet()->setCellValue( ('O'.$f), '' );  
       $total+=$reg['PENSIONABLE'];

       $f++;
       $c++;
     
   }

 

   //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
   //if you want to save it as .XLSX Excel 2007 format
   $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
   //force user to download the Excel file without writing it to server's HD
   $objWriter->save($file_xls_2);
