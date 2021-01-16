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
  $this->pdf->Cell(190,4,' LISTADO DE CONCEPTOS POR TRABAJADOR','',0,'C');

  $this->pdf->ln();
  $this->pdf->SetFont('Arial','B',7);
  $this->pdf->Cell(190,4, $reporte_info['concepto'],'',0,'C');
   
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


  if( trim($reporte_info['mes'])!= '')
  { 

    $this->pdf->SetFont('Arial','B',6);
    $this->pdf->Cell( 8,  $alto_fila,  utf8_decode('MES') , '', 0 , 'L');
    $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
    $this->pdf->SetFont('Arial','',6);
    $this->pdf->Cell( 20,  $alto_fila, $reporte_info['mes'] , '', 0 , 'L');    
   
  }

  if( trim($reporte_info['siaf']) != '')
  { 

    $this->pdf->SetFont('Arial','B',6);
    $this->pdf->Cell( 8,  $alto_fila,  utf8_decode('SIAF') , '', 0 , 'L');
    $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
    $this->pdf->SetFont('Arial','',6);
    $this->pdf->Cell( 20,  $alto_fila, $reporte_info['siaf'] , '', 0 , 'L');    
   
  }

  if( trim($reporte_info['planilla']) != '')
  { 

    $this->pdf->SetFont('Arial','B',6);
    $this->pdf->Cell( 8,  $alto_fila,  utf8_decode('Planilla') , '', 0 , 'L');
    $this->pdf->Cell( 3,  $alto_fila,  ': ' , '', 0 , 'L');  
    $this->pdf->SetFont('Arial','',6);
    $this->pdf->Cell( 20,  $alto_fila, $reporte_info['planilla'] , '', 0 , 'L');    
   
  }

  if(trim($reporte_info['planillatipo']) != '')
  { 

    $this->pdf->SetFont('Arial','B',6);
    $this->pdf->Cell( 21,  $alto_fila,  utf8_decode('Régimen') , '', 0 , 'L');
    $this->pdf->Cell( 3,   $alto_fila,  ': ' , '', 0 , 'L');  
    $this->pdf->SetFont('Arial','',6);
    $this->pdf->Cell( 35,  $alto_fila, ( trim($reporte_info['planillatipo']) != '' ? $reporte_info['planillatipo'] : '--------' ) , '', 0 , 'L');     
    
  }

  $this->pdf->ln();
  $this->pdf->ln();
 
 
  $total = 0;
  $c = 1;


  $this->pdf->SetFont('Arial','B',6);

  $this->pdf->Cell( 8,  $alto_fila,  '#', 'TBLR', 0 , 'C');    
  $this->pdf->Cell( 60, $alto_fila, 'APELLIDOS Y NOMBRES'   , 'TBLR', 0 , 'C'); 
  $this->pdf->Cell( 20, $alto_fila, 'DNI'   , 'TBLR', 0 , 'C'); 
  $this->pdf->Cell( 20, $alto_fila, 'PLANILLA'  , 'TBLR', 0 , 'C');
  $this->pdf->Cell( 20, $alto_fila, 'SIAF'  , 'TBLR', 0 , 'C'); 
  $this->pdf->Cell( 30, $alto_fila, 'MONTO'  , 'TBLR', 0 , 'C');  
  $this->pdf->ln();


  $this->pdf->SetFont('Arial','',6);
  
  foreach($reporte as $reg)
  { 
      $this->pdf->Cell( 8,  $alto_fila,  $c, 'TBLR', 0 , 'C');    
      $this->pdf->Cell( 60, $alto_fila, utf8_decode($reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '.$reg['indiv_nombres'])   , 'TBLR', 0 , 'L'); 
      $this->pdf->Cell( 20, $alto_fila,  $reg['indiv_dni']   , 'TBLR', 0 , 'C'); 
      $this->pdf->Cell( 20, $alto_fila, $reg['pla_codigo']  , 'TBLR', 0 , 'C');


      $siaf =  (trim($reg['siaf']) == '') ? '-----' : trim($reg['siaf']); 

      $this->pdf->Cell( 20, $alto_fila, $siaf  , 'TBLR', 0 , 'C'); 
      $this->pdf->Cell( 30, $alto_fila, $reg['total']  , 'TBLR', 0 , 'C');  
      $this->pdf->ln();
      
      $total+= $reg['total'];
      $c++;

      if($this->pdf->getY() >= 260)
      {
             $this->pdf->AddPage();
             $this->pdf->Cell( 8,  $alto_fila,  '#', 'TBLR', 0 , 'C');    
             $this->pdf->Cell( 60, $alto_fila, 'APELLIDOS Y NOMBRES'   , 'TBLR', 0 , 'C'); 
             $this->pdf->Cell( 20, $alto_fila, 'DNI'   , 'TBLR', 0 , 'C'); 
             $this->pdf->Cell( 20, $alto_fila, 'PLANILLA'  , 'TBLR', 0 , 'C');
             $this->pdf->Cell( 20, $alto_fila, 'SIAF'  , 'TBLR', 0 , 'C'); 
             $this->pdf->Cell( 30, $alto_fila, 'MONTO'  , 'TBLR', 0 , 'C');  
             $this->pdf->ln(); 

      }
  }


  if(sizeof($reporte) == 0)
  {   
      $this->pdf->ln();
      $this->pdf->Cell( 60, $alto_fila, 'No se encontraron registros.'   , '', 0 , 'L'); 
  }

  $this->pdf->ln();
  $this->pdf->SetFont('Arial','B',7); 

  $this->pdf->Cell( 20, $alto_fila,  '', '', 0 , 'L'); 
  $this->pdf->Cell( 20, $alto_fila,  ('TOTAL: S./'.$total) , '', 0 , 'L'); 
     
  $this->pdf->Output( $file_pdf ,'F');