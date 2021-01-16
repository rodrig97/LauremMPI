<?PHP 

 $this->load->library('Excel');
 
 $this->excel = new Excel();  
 
 $hoja = 0;

 $this->excel->setActiveSheetIndex($hoja);
 //name the worksheet
 $this->excel->getActiveSheet()->setTitle('DESCUENTOS'); // 3500

  
 
 
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


$columna_indice = 1;

$f = 1;  

foreach ($reporte[0] as $key => $dato)
{  
  
 
    $this->excel->getActiveSheet()->setCellValueExplicit(
       ( $POSICIONES[$columna_indice].$f), 
       trim(strtoupper($key) ),
       PHPExcel_Cell_DataType::TYPE_STRING
   );  

   $columna_indice++;



}


$f++;

$c = 1;

foreach($reporte as $reg)
{ 
   

   $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
   $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

   $columna_indice = 1;
   
   foreach ($reg as $key => $dato)
   {  
        
   /*    $this->excel->getActiveSheet()->setCellValueExplicit(
          ( $POSICIONES[$columna_indice].$f), 
          trim($dato),
          PHPExcel_Cell_DataType::TYPE_STRING
       ); */

       if(strpos($key, 'DATO_' ) !== FALSE)
       {

             $this->excel->getActiveSheet()->setCellValueExplicit(
                ( $POSICIONES[$columna_indice].$f), 
                trim($dato),
                PHPExcel_Cell_DataType::TYPE_NUMERIC
            ); 

           
       }
       else
       {  

           $this->excel->getActiveSheet()->setCellValueExplicit(
               ( $POSICIONES[$columna_indice].$f), 
               trim(strtoupper($dato) ),
               PHPExcel_Cell_DataType::TYPE_STRING
           ); 
       
       }

 
       $columna_indice++;
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

//$sheet->setSharedStyle($sharedStyle1,'A1:'.($POSICIONES[$columna_indice -1]).$f ); 

 



//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
//force user to download the Excel file without writing it to server's HD
$objWriter->save($file_xls);
