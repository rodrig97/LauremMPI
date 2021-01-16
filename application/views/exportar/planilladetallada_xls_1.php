<?PHP 

 $this->load->library('Excel');
 
 $this->excel = new Excel();  
 
 $hoja = 0;

 $this->excel->setActiveSheetIndex($hoja);
 //name the worksheet
 $this->excel->getActiveSheet()->setTitle('INFORMACION'); // 3500

 $f = 1;
 
 $this->excel->getActiveSheet()->setCellValue( 'A1', 'INFORMACION' );
 
 $plati_nombres = '';

 foreach ($planillatipos as $plati)
 {
    
    $rs_plati = $this->tipoplanilla->get($plati);
    $plati_nombres.= ' - '.$rs_plati['plati_nombre'];

 }

 $meses_txt = implode(',', $meses);
 
  
 $this->excel->getActiveSheet()->setCellValue( 'A1', 'DETALLE DE PLANILLAS' );
 $this->excel->getActiveSheet()->mergeCells('A1:C1');

 $this->excel->getActiveSheet()->setCellValue( 'A2',  'AÑO: ');
 $this->excel->getActiveSheet()->setCellValue( 'C2',  $anio );
 $this->excel->getActiveSheet()->mergeCells('A2:B2');

 $this->excel->getActiveSheet()->setCellValue( 'A3',  'MES: ');
 $this->excel->getActiveSheet()->setCellValue( 'C3',  $meses_txt );
 $this->excel->getActiveSheet()->mergeCells('A3:B3');

 $this->excel->getActiveSheet()->setCellValue( 'A4',  'Régimen : ');
 $this->excel->getActiveSheet()->setCellValue( 'C4',  ( trim($plati_nombres) != '' ? $plati_nombres : '--------' ) );
 $this->excel->getActiveSheet()->mergeCells('A4:B4');
  
 $this->excel->getActiveSheet()->setCellValue( 'A5',  'DNI : ');
 $this->excel->getActiveSheet()->setCellValue( 'C5',  ( trim($dni) != '' ? $dni : '--------' ) );
 $this->excel->getActiveSheet()->mergeCells('A5:B5');
 

 
 $letras = array('A', 'B', 'C', 'D', 'E', 
                    'F', 'G', 'H', 'I', 'J', 
                    'K', 'L', 'M', 'N', 'O',
                    'P', 'Q','R','S','T','U','V','W','X','Y','Z' );

$POSICIONES = $letras;
$cl = 1;


foreach ($letras as $a)
{ 

   if($cl==6) break;
   foreach ($letras as $b)
   {  
      $POSICIONES[] = $a.$b;
   }
   $cl++;
}
 
 

foreach ($planillatipos as $plati)
{
  
    $rs_plati = $this->tipoplanilla->get($plati);
    $plati_nombre = $rs_plati['plati_nombre'];

    $hoja++;

    $columna_indice = 1;
    $f = 1;
    $c = 1;

    $this->excel->createSheet();
    $this->excel->setActiveSheetIndex($hoja);
    $this->excel->getActiveSheet()->setTitle($plati_nombre); // 3500  


    $agrupar_por_mes = ($agrupar_por_mes=== true ? $agrupar_por_mes : false);
    
    $params = array( 'anio' => $anio,  'mes' => $meses, 'plati' => $plati, 'agrupar_por_mes' => $agrupar_por_mes, 'indiv_id' => $indiv_id); 

    $reporte = $this->exportador->planillas_detalladas($params);


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

               
           }
           else
           {  

               $this->excel->getActiveSheet()->setCellValueExplicit(
                   ( $POSICIONES[$columna_indice].$f), 
                   trim(strtoupper($key) ),
                   PHPExcel_Cell_DataType::TYPE_STRING
               ); 
       
           }

           $columna_indice++;

       }

        
       $f++;


       foreach($reporte as $reg)
       { 
           

           $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
           $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
       
           $columna_indice = 1;
           foreach ($reg as $key => $dato)
           {  
           
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
                       trim($dato),
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


}



//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
//force user to download the Excel file without writing it to server's HD
$objWriter->save($file_xls);

