<?PHP 

    /* GENERANDO PDF */

   $this->load->library(array('fpdf','reportes/pdf'));
   $basepath = dirname(FCPATH);

   $this->pdf = new PDF();

   $this->pdf->initialize('p','mm','A4');
   $this->pdf->AliasNbPages();
   $this->pdf->AddPage();

   $this->pdf->ln();
   $this->pdf->SetFont('Arial','B',8);
   $this->pdf->Cell(190,4,' REPORTE PARA AFP ','',0,'C');
    
   $this->pdf->ln();
   $this->pdf->ln();

    
   $this->pdf->SetFont('Arial','B',6);  

   $alto_fila= 3; 

   foreach ($estructura as $key => $v)
   {
       $this->pdf->Cell($v[1], $alto_fila, $key, 'TBLR', 0 , 'C'); 
   }   

   $this->pdf->ln();

   $this->pdf->Cell( 8,  $alto_fila,  utf8_decode('AÑO') , '', 0 , 'L');
   $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
  
   $this->pdf->SetFont('Arial','',6);
   $this->pdf->Cell( 10,  $alto_fila, $reporte_info['anio'] , '', 0 , 'L');   


   $this->pdf->SetFont('Arial','B',6);
   $this->pdf->Cell( 8,  $alto_fila,  utf8_decode('MES') , '', 0 , 'L');
   $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
   $this->pdf->SetFont('Arial','',6);
   $this->pdf->Cell( 20,  $alto_fila, $reporte_info['mes'] , '', 0 , 'L');    

   $this->pdf->SetFont('Arial','B',6);
   $this->pdf->Cell( 8,  $alto_fila,  utf8_decode('AFP') , '', 0 , 'L');
   $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
   $this->pdf->SetFont('Arial','',6);
   $this->pdf->Cell( 30,  $alto_fila,  ( trim($reporte_info['afp_nombre']) != '' ? $reporte_info['afp_nombre'] : '--------' )  , '', 0 , 'L');     

   $this->pdf->SetFont('Arial','B',6);
   $this->pdf->Cell( 21,  $alto_fila,  utf8_decode('Régimen') , '', 0 , 'L');
   $this->pdf->Cell( 3,   $alto_fila,  ': ' , '', 0 , 'L');  
   $this->pdf->SetFont('Arial','',6);
   $this->pdf->Cell( 35,  $alto_fila, ( trim($reporte_info['plati']) != '' ? $reporte_info['plati'] : '--------' ) , '', 0 , 'L');     

   $this->pdf->ln();

   $this->pdf->SetFont('Arial','B',6);
   $this->pdf->Cell( 15,  $alto_fila,  utf8_decode('TIPO DE GASTO') , '', 0 , 'L');
   $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
   $this->pdf->SetFont('Arial','',6);
   $this->pdf->Cell( 100,  $alto_fila,  ( trim($reporte_info['tipogasto']) != '' ? $reporte_info['tipogasto'] : '--------' ) , '', 0 , 'L');     

   $this->pdf->ln();

   $this->pdf->SetFont('Arial','B',6);
   $this->pdf->Cell( 15,  $alto_fila,  utf8_decode('PLANILLAS') , '', 0 , 'L');
   $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
   $this->pdf->SetFont('Arial','',6);
   $this->pdf->Cell( 100,  $alto_fila,  ( trim($reporte_info['planillas']) != '' ? $reporte_info['planillas'] : '--------' ) , '', 0 , 'L');     

   $this->pdf->ln();

   $this->pdf->SetFont('Arial','B',6);
   $this->pdf->Cell( 15,  $alto_fila,  'GENERADO EL' , '', 0 , 'L');
   $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
   $this->pdf->SetFont('Arial','',6);
   $this->pdf->Cell( 100,  $alto_fila,  '' , '', 0 , 'L');     

   $this->pdf->ln();
   $this->pdf->ln();
  
   $total = 0;
   $c = 1;


   $this->pdf->SetFont('Arial','B', 4);

   $this->pdf->Cell( 8,  $alto_fila, '#', 'TBLR', 0 , 'C');    
   $this->pdf->Cell( 16, $alto_fila, 'AP. PATERNO'   , 'TBLR', 0 , 'L'); 
   $this->pdf->Cell( 16, $alto_fila, 'AP. MATERNO'  , 'TBLR', 0 , 'L'); 
   $this->pdf->Cell( 21, $alto_fila, 'NOMBRES'  , 'TBLR', 0 , 'L'); 
   $this->pdf->Cell( 12, $alto_fila, 'DNI'  , 'TBLR', 0 , 'L'); 
   $this->pdf->Cell( 18, $alto_fila, 'TIPO DE TRABAJADOR'  , 'TBLR', 0 , 'C'); 
   $this->pdf->Cell( 12, $alto_fila, 'AFP'   , 'TBLR', 0 , 'C');     
   $this->pdf->Cell( 17, $alto_fila, 'CODIGO'   , 'TBLR', 0 , 'C');
   $this->pdf->Cell( 6,  $alto_fila, 'I/C'  , 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10,  $alto_fila, 'FECHA I/C'  , 'TBLR', 0 , 'C');
   $this->pdf->Cell( 7,  $alto_fila, 'TIPO CS'  , 'TBLR', 0 , 'C');
   $this->pdf->Cell( 11, $alto_fila, 'PENSIONABLE'  , 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10, $alto_fila, 'COMISION'  , 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10, $alto_fila, 'JUBILACION'  , 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10, $alto_fila, 'INVALIDEZ'  , 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10, $alto_fila, 'APORTE C.S'  , 'TBLR', 0 , 'C');
   $this->pdf->ln();


   $totales = array(
                      'PENSIONABLE'         => 0,
                      'AFP - COMISION'      => 0,
                      'AFP - JUBILACION'    => 0,
                      'AFP - SEGURO'        => 0,
                      'AFP APORTE CONS.CIV' => 0
                    ); 

   $this->pdf->SetFont('Arial','',4);
   
   foreach($reporte as $reg)
   {  
     
       $this->pdf->Cell( 8,  $alto_fila, $c,  'TBLR', 0 , 'C');    
       $this->pdf->Cell( 16, $alto_fila, utf8_decode(trim($reg['indiv_appaterno']))   , 'TBLR', 0 , 'L'); 
       $this->pdf->Cell( 16, $alto_fila, utf8_decode(trim($reg['indiv_apmaterno']))  , 'TBLR', 0 , 'L'); 
       $this->pdf->Cell( 21, $alto_fila, utf8_decode(trim($reg['indiv_nombres'])) , 'TBLR', 0 , 'L'); 
       $this->pdf->Cell( 12, $alto_fila, trim($reg['indiv_dni'])  , 'TBLR', 0 , 'C'); 
       $this->pdf->Cell( 18, $alto_fila, trim($reg['regimen'])  , 'TBLR', 0 , 'C'); 
       $this->pdf->Cell( 12, $alto_fila, trim($reg['afp_nombre'])   , 'TBLR', 0 , 'L');     
       $this->pdf->Cell( 17, $alto_fila, trim($reg['peaf_codigo'])   , 'TBLR', 0 , 'C');
       $this->pdf->Cell( 6,  $alto_fila, (trim($reg['tipo']) != '' ? trim($reg['tipo']) : '---')   , 'TBLR', 0 , 'C');
       $this->pdf->Cell( 10, $alto_fila, (trim($reg['fecha']) != '' ? trim($reg['fecha']) : '---')  , 'TBLR', 0 , 'C');
       $this->pdf->Cell( 7,  $alto_fila, (trim($reg['cs']) != '' ? trim($reg['cs']) : '---')  , 'TBLR', 0 , 'C');
       $this->pdf->Cell( 11, $alto_fila, (trim($reg['PENSIONABLE']) != '' ? trim($reg['PENSIONABLE']): '---')  , 'TBLR', 0 , 'C');
       $this->pdf->Cell( 10, $alto_fila, (trim($reg['AFP - COMISION']) != '' ? trim($reg['AFP - COMISION']) : '---')  , 'TBLR', 0 , 'C');
       $this->pdf->Cell( 10, $alto_fila, (trim($reg['AFP - JUBILACION']) != '' ? trim($reg['AFP - JUBILACION']) : '---')  , 'TBLR', 0 , 'C');
       $this->pdf->Cell( 10, $alto_fila, (trim($reg['AFP - SEGURO']) != '' ? trim($reg['AFP - SEGURO']) : '---')  , 'TBLR', 0 , 'C');
       $this->pdf->Cell( 10, $alto_fila, (trim($reg['AFP APORTE CONS.CIV']) != '' ? trim($reg['AFP APORTE CONS.CIV']) : '---' )  , 'TBLR', 0 , 'C');
       $this->pdf->ln();

       $totales['PENSIONABLE']+= ($reg['PENSIONABLE']=='' ? 0 : $reg['PENSIONABLE']);
       $totales['AFP - COMISION']+= ($reg['AFP - COMISION']=='' ? 0 : $reg['AFP - COMISION']);
       $totales['AFP - JUBILACION']+= ($reg['AFP - JUBILACION']=='' ? 0 : $reg['AFP - JUBILACION']);
       $totales['AFP - SEGURO']+= ($reg['AFP - SEGURO']=='' ? 0 : $reg['AFP - SEGURO']);
       $totales['AFP APORTE CONS.CIV']+= ($reg['AFP APORTE CONS.CIV']=='' ? 0 : $reg['AFP APORTE CONS.CIV']);

       $c++;
   }

   $this->pdf->Cell( 142, ($alto_fila + 1), 'Total: ', '', 0, 'R');
   $this->pdf->Cell( 1,  ($alto_fila + 1), ' ', '', 0, 'C');
   $this->pdf->Cell( 11, ($alto_fila + 1), $totales['PENSIONABLE'], 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10, ($alto_fila + 1), $totales['AFP - COMISION'], 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10, ($alto_fila + 1), $totales['AFP - JUBILACION'], 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10, ($alto_fila + 1), $totales['AFP - SEGURO'], 'TBLR', 0 , 'C');
   $this->pdf->Cell( 10, ($alto_fila + 1), $totales['AFP APORTE CONS.CIV'], 'TBLR', 0 , 'C');

   $this->pdf->ln();
   $this->pdf->ln();
    
   $total = $totales['AFP - COMISION']+ $totales['AFP - JUBILACION'] + $totales['AFP - SEGURO'] + $totales['AFP APORTE CONS.CIV'];

   $this->pdf->SetFont('Arial','B',6); 

   $this->pdf->Cell( 20, $alto_fila,  '', '', 0 , 'L'); 
    
   $this->pdf->Cell( 20, $alto_fila,  ('TOTAL: S./ '.$total) , '', 0 , 'L'); 

      
   $this->pdf->Output( $file_pdf ,'F');
