<?PHP 

  $this->load->library(array('fpdf','reportes/pdf'));
  $basepath = dirname(FCPATH);

  $this->paginador = true;

  $this->pdf = new PDF();

  $this->pdf->initialize('p','mm','A4');
  $this->pdf->AliasNbPages();
  $this->pdf->AddPage();

  $this->pdf->ln();
  $this->pdf->SetFont('Arial','B',8);
  $this->pdf->Cell(190,4,' RESUMEN DE CUENTA DE DEPOSITO','',0,'C');
   
  $this->pdf->ln();
  $this->pdf->ln();

   
 

  $this->pdf->SetFont('Arial','B',6);  

  $alto_fila= 4; 

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
  $this->pdf->Cell( 8,  $alto_fila,  utf8_decode('BANCO') , '', 0 , 'L');
  $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
  $this->pdf->SetFont('Arial','',6);
  $this->pdf->Cell( 30,  $alto_fila, $reporte_info['banco'] , '', 0 , 'L');     

  $this->pdf->SetFont('Arial','B',6);
  $this->pdf->Cell( 21,  $alto_fila,  utf8_decode('Régimen') , '', 0 , 'L');
  $this->pdf->Cell( 3,   $alto_fila,  ': ' , '', 0 , 'L');  
  $this->pdf->SetFont('Arial','',6);
  $this->pdf->Cell( 35,  $alto_fila, ( trim($reporte_info['plati']) != '' ? $reporte_info['plati'] : '--------' ) , '', 0 , 'L');     

  $this->pdf->ln();

  $this->pdf->SetFont('Arial','B',6);
  $this->pdf->Cell( 15,  $alto_fila,  utf8_decode('PLANILLAS') , '', 0 , 'L');
  $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
  $this->pdf->SetFont('Arial','',6);


  if(strlen(trim($reporte_info['planillas'])) > 170 )
  {
      $linestxt = wordwrap(trim($reporte_info['planillas']), 170, '_');
      $lines = explode('_', $linestxt);
 
      foreach ($lines as $line)
      {
         $this->pdf->Cell( 180,  $alto_fila,  $line , '', 0 , 'L');
         $this->pdf->ln();
      }
  }
  else
  {
      $this->pdf->Cell( 180,  $alto_fila,  ( trim($reporte_info['planillas']) != '' ? $reporte_info['planillas'] : '--------' ) , '', 0 , 'L');     
  }

  $this->pdf->ln();

  $this->pdf->SetFont('Arial','B',6);
  $this->pdf->Cell( 15,  $alto_fila,  'GENERADO EL' , '', 0 , 'L');
  $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
  $this->pdf->SetFont('Arial','',6);
  $this->pdf->Cell( 100,  $alto_fila,   date('d/m/Y').' a las '.date("H:i:s") , '', 0 , 'L');     

  $this->pdf->ln();
  $this->pdf->ln();
 
  $total = 0;
  $c = 1;


  $this->pdf->SetFont('Arial','B',6);

  $this->pdf->Cell( 8,  $alto_fila,  '#', 'TBLR', 0 , 'C');    
  $this->pdf->Cell( 15, $alto_fila, 'PLANILLA'   , 'TBLR', 0 , 'L'); 
  $this->pdf->Cell( 20, $alto_fila, 'AP. PATERNO'   , 'TBLR', 0 , 'L'); 
  $this->pdf->Cell( 20, $alto_fila, 'AP. MATERNO'  , 'TBLR', 0 , 'L'); 
  $this->pdf->Cell( 30, $alto_fila, 'NOMBRES'  , 'TBLR', 0 , 'L'); 
  $this->pdf->Cell( 12, $alto_fila, 'DNI'  , 'TBLR', 0 , 'C'); 
  $this->pdf->Cell( 27, $alto_fila, 'BANCO'   , 'TBLR', 0 , 'C');     
  $this->pdf->Cell( 21, $alto_fila, 'CUENTA '   , 'TBLR', 0 , 'C');
  $this->pdf->Cell( 16, $alto_fila, 'MONTO'  , 'TBLR', 0 , 'C');
  $this->pdf->Cell( 13, $alto_fila, 'TAREA'  , 'TBLR', 0 , 'C');
  $this->pdf->Cell( 13, $alto_fila, 'FUENTE' , 'TBLR', 0 , 'C');
  $this->pdf->ln();


  $this->pdf->SetFont('Arial','',6);
  foreach($reporte as $reg)
  { 
      $this->pdf->Cell( 8,  $alto_fila,  $c   , 'TBLR', 0 , 'C');    
      $this->pdf->Cell( 15, $alto_fila,  trim($reg['pla_codigo'])   , 'TBLR', 0 , 'L'); 
      $this->pdf->Cell( 20, $alto_fila,  utf8_decode(trim($reg['indiv_appaterno']))   , 'TBLR', 0 , 'L'); 
      $this->pdf->Cell( 20, $alto_fila,  utf8_decode(trim($reg['indiv_apmaterno']))   , 'TBLR', 0 , 'L'); 
      $this->pdf->Cell( 30, $alto_fila,  trim($reg['indiv_nombres'])   , 'TBLR', 0 , 'L'); 
      $this->pdf->Cell( 12, $alto_fila,  trim($reg['indiv_dni'])   , 'TBLR', 0 , 'C'); 
      $this->pdf->Cell( 27, $alto_fila,  trim($reg['ebanco_nombre'])   , 'TBLR', 0 , 'C');     
      $this->pdf->Cell( 21, $alto_fila,  trim($reg['pecd_cuentabancaria'])   , 'TBLR', 0 , 'C');
      $this->pdf->Cell( 16, $alto_fila,  sprintf("%01.2f", $reg['deposito'])   , 'TBLR', 0 , 'C');
      $this->pdf->Cell( 13, $alto_fila,  trim($reg['tarea_codigo'])   , 'TBLR', 0 , 'C');
      $this->pdf->Cell( 13, $alto_fila,  trim($reg['fuente_codigo'])   , 'TBLR', 0 , 'C');  
      $this->pdf->ln();
      $total+= $reg['deposito'];
      $c++;
  }

  $this->pdf->ln();

  $this->pdf->SetFont('Arial','B',7); 

  $this->pdf->Cell( 20, $alto_fila,  '', '', 0 , 'L'); 
   
  $this->pdf->Cell( 20, $alto_fila,  ('TOTAL: S./'.$total) , '', 0 , 'L'); 
     
  $this->pdf->Output( $file_pdf ,'F');