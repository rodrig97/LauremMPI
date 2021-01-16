<?PHP


/*GENERANDO XLS*/ 


$this->load->library('Excel');

$this->excel = new Excel();
 
$hoja = 0;

$this->excel->setActiveSheetIndex($hoja);

$this->excel->getActiveSheet()->setTitle('Resumen'); // 3500
 
$this->excel->getActiveSheet()->setCellValue( 'A1', 'RESUMEN DE DESCUENTOS' );
$this->excel->getActiveSheet()->mergeCells('A1:C1');

$this->excel->getActiveSheet()->setCellValue( 'A2',  'AÑO - MES:');
$this->excel->getActiveSheet()->setCellValue( 'C2',  ( $reporte_info['anio'].' '.$reporte_info['mes'] ) );
$this->excel->getActiveSheet()->mergeCells('A2:B2');

$this->excel->getActiveSheet()->setCellValue( 'A3',  'Régimen : ');
$this->excel->getActiveSheet()->setCellValue( 'C3',  ( trim($reporte_info['plati']) != '' ? $reporte_info['plati'] : '--------' ) );
$this->excel->getActiveSheet()->mergeCells('A3:B3');

$this->excel->getActiveSheet()->setCellValue( 'A4',  'PLANILLAS: ');
$this->excel->getActiveSheet()->setCellValue( 'C4',   ( trim($reporte_info['planillas']) != '' ? $reporte_info['planillas'] : '--------' )   );
$this->excel->getActiveSheet()->mergeCells('A4:B4');


$headers = array('#', 'PLANILLA','NOMBRE', 'DNI', 'TAREA', 'MONTO', 'SIAF' );


foreach($grupos as $grupo)
{

    $hoja++;
    $this->excel->createSheet();
    $this->excel->setActiveSheetIndex($hoja);
    $this->excel->getActiveSheet()->setTitle($grupo['gvc_nombre']); // 3500



    if($grupo['gvc_reporte_descuento_tabla'] == '0')
    { 
      
        $columnas = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G' );
        $sizes    = array( 5, 10,  40, 10, 20, 12  );
         
        $sheet = $this->excel->getActiveSheet();
     
        foreach($columnas as $k => $col)
        {
             $sheet->getColumnDimension($col)->setWidth($sizes[$k]);
        }
     
        $f = 1;

        foreach($headers as $k => $header)
        {
            $pos = ( $columnas[$k].$f);
            $this->excel->getActiveSheet()->setCellValue( $pos, $header );
            $this->excel->getActiveSheet()->getStyle($pos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle($pos)->getFont()->setBold(true);
        
        } 

        $params_modelo['grupos'] = array($grupo['gvc_id']);

        $reporte = $this->exportador->grupo_descuento($params_modelo);
         
        $f++;
         
        $fuente_actual = '';
        $c = 1;


        if(sizeof($reporte) == 0)
        {
     
            $this->excel->getActiveSheet()->setCellValue( ('A'.$f), 'No hay registros de trabajadores' );
            $this->excel->getActiveSheet()->mergeCells('A'.$f.':D'.$f); 

        }

        foreach($reporte as $reg)
        {   

            if($reg['fuente_codigo'] != $fuente_actual)
            {

                $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $reg['fuente_codigo']  );
                $this->excel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->mergeCells('A'.$f.':D'.$f);    

                $f++;
                $fuente_actual = $reg['fuente_codigo'];
            
            }


            $this->excel->getActiveSheet()->setCellValue( ('A'.$f), $c );
          
      
            $nombre_completo =  utf8_decode(utf8_encode(trim($reg['indiv_appaterno']))).' '.utf8_decode(trim($reg['indiv_apmaterno'])).' '.trim($reg['indiv_nombres']);

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('B'.$f), 
                trim($reg['pla_codigo']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );
            $this->excel->getActiveSheet()->getStyle('B'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
            
            $this->excel->getActiveSheet()->setCellValue( ('C'.$f), $nombre_completo  );
              
            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('D'.$f), 
                trim($reg['indiv_dni']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );


            $this->excel->getActiveSheet()->setCellValue( ('E'.$f), trim($reg['tarea_codigo']) );
            $this->excel->getActiveSheet()->getStyle('E'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('F'.$f), 
                sprintf("%01.2f", $reg['total']),
                PHPExcel_Cell_DataType::TYPE_NUMERIC
            );
            $this->excel->getActiveSheet()->setCellValue( ('G'.$f), trim($reg['siaf']) );
     
            $total+= $reg['total'];

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

        $sheet->setSharedStyle($sharedStyle1,'A1:G'.$f); 
     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

    }
    else
    {

        
    }

}  


$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
//force user to download the Excel file without writing it to server's HD
$objWriter->save($file_xls);
