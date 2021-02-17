<?PHP 
	  

$POSITION_Y  = $POSICION_Y_INICIAL;

 
$bordes = '';
$tamanio_letra = 7; 


$this->pdf->setXY(5, $POSITION_Y); 
$this->pdf->SetFont('Arial','',4); 
$this->pdf->Image('frontend/images/institucion/logo_institucion.jpg',  5,   $POSITION_Y, 48,12);
$this->pdf->setXY(17, ($POSITION_Y + 9));        
$this->pdf->Cell( 260 ,4, 'Sistema Integral de Getion de Recursos Humanos ', ' ',0,'L',false); 
 
$this->pdf->setXY(10, ($POSITION_Y +10) );    
 

$this->pdf->SetFont('Arial','B',9);
$this->pdf->Cell(120,4,'BOLETA DE PAGO - '.trim($planilla_info['tipo']),'',0,'R');

if(MOSTRAR_RUC_EN_BOLETA)
{ 
    $this->pdf->SetFont('Arial','B',8);
    $this->pdf->Cell(70,4,'RUC: '.RUC_INSTITUCION,'',0,'R');
}


$this->pdf->ln(); 
$this->pdf->SetFont('Arial','B',$tamanio_letra);
$alto_fila = 4;
$this->pdf->Cell( 12,$alto_fila,'Planilla: ','',0,'L');
$this->pdf->Cell( 15,$alto_fila,trim($planilla_info['pla_codigo']),'',0,'L');

if( trim($planilla_info['pla_fecini']) != '' )
{ 
    $this->pdf->Cell( 13,$alto_fila,'Periodo: ','',0,'L');
    $this->pdf->Cell( 30,$alto_fila, _get_date_pg(trim($planilla_info['pla_fecini'])).' - '._get_date_pg(trim($planilla_info['pla_fecfin'])),'',0,'L');
    $max_strlen_tarea = 65;

    if($planilla_info['pla_semana'] != '')
    {
        $this->pdf->Cell( 13,$alto_fila,'Semana: ','',0,'L');
        $this->pdf->Cell( 6,$alto_fila,trim($planilla_info['pla_semana']) );
    }


}
else
{  
    $this->pdf->Cell( 8,$alto_fila,'Mes: ','',0,'L');
    $this->pdf->Cell( 23,$alto_fila,trim($planilla_info['mes']).' - '.trim($planilla_info['pla_anio']),'',0,'L');
    $max_strlen_tarea = 65;
}
 
$max_strlen_tarea = 50;

$this->pdf->Cell( 12,$alto_fila,'C.Costo: ','',0,'L');

$tarea_nombre =  trim($detalle_info['tarea_codigo']).' '.substr(utf8_decode(trim($detalle_info['tarea_nombre'])),0,$max_strlen_tarea );
$this->pdf->Cell( 150,$alto_fila,$tarea_nombre,'',0,'L');



// LINEA ----------------------------------
$this->pdf->ln();
$this->pdf->Cell(190,1,'','T',0,'C');

$alto_fila = 4;

/* Informacion Personal */
 
 $POSITION_Y_1 =  $POSITION_Y + 15; 


    $columnas = array('',10,75,125,170);     // POS X 
    $filas =    array('',($POSITION_Y_1 +3),($POSITION_Y_1 +6),($POSITION_Y_1 +9),($POSITION_Y_1 +12),($POSITION_Y_1 +15),($POSITION_Y_1 +18)); // POS Y 
    
    $sub_columnas = array(0,array(15,5,35),array(21,5,25),array(14,5,25),array(10,5,20));
  

    // PRIMERA COLUMNA
    
    $this->pdf->setXY($columnas[1],$filas[1]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell( $sub_columnas[1][0] ,$alto_fila,'DNI',$bordes,0,'L');
    $this->pdf->Cell( $sub_columnas[1][1] ,$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell( $sub_columnas[1][2] ,$alto_fila, trim($persona_info['indiv_dni']) ,$bordes,0,'L');
    
    
    $this->pdf->setXY($columnas[1],$filas[2]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[1][0],$alto_fila,'Apellidos',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[1][2],$alto_fila, utf8_decode(trim($persona_info['indiv_appaterno'])).' '.utf8_decode(trim($persona_info['indiv_apmaterno'])) ,$bordes,0,'L');
    
    $this->pdf->setXY($columnas[1],$filas[3]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[1][0],$alto_fila,'Nombres',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[1][2],4, trim($persona_info['indiv_nombres']) ,$bordes,0,'L');
    
  /*  
    $this->pdf->setXY($columnas[1],$filas[4]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[1][0],$alto_fila,'Direccion',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[1][2],$alto_fila, (trim($persona_info['indiv_direccion1']) != '') ? substr( utf8_decode(trim($persona_info['indiv_direccion1'])),0,30) : '------' ,$bordes,0,'L');
    */


    if( in_array( $planilla_info['plati_id'] , array(TIPOPLANILLA_CONSCIVIL, TIPOPLANILLA_OBRECONTRATADOS) ) == FALSE )
    {
 
        $this->pdf->setXY($columnas[1],$filas[4]); 
        $this->pdf->SetFont('Arial','B',$tamanio_letra);
        $this->pdf->Cell($sub_columnas[1][0],$alto_fila,'Ocupacion',$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'C');
        $this->pdf->SetFont('Arial','',$tamanio_letra); 

        if($planilla_info['pla_tiene_categoria']  == '1' )
        {   
           $ocupacion_label = '';     

           if( trim($detalle_info['plaemp_ocupacion_label']) != '')
           { 
                $ocupacion_label =trim($detalle_info['plaemp_ocupacion_label']);
           }
           else
           {
                $ocupacion_label = (trim($detalle_info['categoria_nombre']) != '') ? trim($detalle_info['categoria_nombre']) : '------';
           }

           $this->pdf->Cell($sub_columnas[1][2],$alto_fila, $ocupacion_label ,$bordes,0,'L'); 
        }
        else
        {
           $this->pdf->Cell($sub_columnas[1][2],$alto_fila, (trim($detalle_info['ocupacion_nombre']) != '') ? trim($detalle_info['ocupacion_nombre']) : '------'  ,$bordes,0,'L');    
        }

    }
    else
    {

        if($planilla_info['pla_tiene_categoria']  == '1' )
        {    

            $this->pdf->setXY($columnas[1],$filas[4]);
            $this->pdf->SetFont('Arial','B',$tamanio_letra);
            $this->pdf->Cell($sub_columnas[1][0],$alto_fila,'Categoria', $bordes,0,'L');
            $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'C');
            $this->pdf->SetFont('Arial','',$tamanio_letra);
            $this->pdf->Cell($sub_columnas[1][2],$alto_fila, (trim($detalle_info['categoria_nombre']) != '') ? trim($detalle_info['categoria_nombre']) : '------' , $bordes,0,'L');
            
        }


    }
        
  
    
    // SEGUNDA COLUMNA
     
    $this->pdf->setXY($columnas[2],$filas[1]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[2][0],$alto_fila,'Estado civil',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[2][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[2][2],$alto_fila, (trim($persona_info['estado_civil']) != '') ? trim($persona_info['estado_civil']) : '------' , $bordes,0,'L');
    
    $this->pdf->setXY($columnas[2],$filas[2]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[2][0],$alto_fila,'Nacionalidad',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[2][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[2][2],$alto_fila, (trim($persona_info['nacionalidad']) != '') ? trim($persona_info['nacionalidad']) : '------' , $bordes,0,'L');
    
    $this->pdf->setXY($columnas[2],$filas[3]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[2][0],$alto_fila,'Fecha Nacimiento',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[2][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[2][2],$alto_fila, (trim($persona_info['indiv_fechanac']) != '') ? _get_date_pg(trim($persona_info['indiv_fechanac'])) : '------' , $bordes,0,'L');
    
/*    $this->pdf->setXY($columnas[2],$filas[4]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[2][0],$alto_fila,'Lugar Nacimiento', $bordes,0,'L');
    $this->pdf->Cell($sub_columnas[2][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[2][2],$alto_fila, (trim($persona_info['ciudad_origen']) != '') ? trim($persona_info['ciudad_origen']) : '------' , $bordes,0,'L');
  */  

   

    if(in_array( $planilla_info['plati_id'] , array(TIPOPLANILLA_CONSCIVIL, TIPOPLANILLA_OBRECONTRATADOS) ) == TRUE)
    {
        
        if($planilla_info['pla_tiene_categoria']  == '1' )
        {    

            

              $ocupacion_label =( trim($detalle_info['plaemp_ocupacion_label'])!='' ? trim($detalle_info['plaemp_ocupacion_label']) : ( trim($detalle_info['categoria_nombre']) != '' ? trim($detalle_info['categoria_nombre']) : '-------' ) );
                
              $this->pdf->setXY($columnas[2],$filas[4]); 
              $this->pdf->SetFont('Arial','B',$tamanio_letra);
              $this->pdf->Cell($sub_columnas[2][0],$alto_fila,'Ocupacion',$bordes,0,'L');
              $this->pdf->Cell($sub_columnas[2][1],$alto_fila,':',$bordes,0,'C');
              $this->pdf->SetFont('Arial','',$tamanio_letra); 
              $this->pdf->Cell($sub_columnas[2][2],$alto_fila, (trim($ocupacion_label) != '') ? trim($ocupacion_label) : '------'  ,$bordes,0,'L');    
              
        }

    }
   



     // TERCERA COLUMNA
    $jubilado = (trim($persona_info['peaf_jubilado']) == '1') ? '(J) ' : '';  

    $this->pdf->setXY($columnas[3],$filas[1]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[3][0],$alto_fila,'AFP',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[3][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[3][2],$alto_fila, (trim($persona_info['afp']) != '') ? trim($persona_info['afp']).' '.$jubilado  : '------' , $bordes,0,'L');
    
    $this->pdf->setXY($columnas[3],$filas[2]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[3][0],$alto_fila,'Codigo AFP',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[3][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[3][2],$alto_fila, (trim($persona_info['afp_codigo']) != '') ? utf8_decode(trim($persona_info['afp_codigo'])) : '------' , $bordes,0,'L');
     /*
    $this->pdf->setXY($columnas[3],$filas[3]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[3][0],$alto_fila, 'ESSALUD' , $bordes,0,'L');
    $this->pdf->Cell($sub_columnas[3][1],$alto_fila, ':' , $bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[3][2],$alto_fila, (trim($persona_info['indiv_essalud']) != '') ? trim($persona_info['indiv_essalud']) : '------' , $bordes,0,'L');
     
   
    $this->pdf->setXY($columnas[3],$filas[4]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[3][0],4,'Telefono',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[3][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[3][2],4, (trim($persona_info['indiv_telefono']) != '' || trim($persona_info['indiv_celular']) != '') ? trim($persona_info['indiv_telefono']).' '.trim($persona_info['indiv_celular']) : '------' , $bordes,0,'L');
        */



   // CUARTA COLUMNA
     
    $this->pdf->setXY($columnas[4],$filas[1]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[4][0],$alto_fila,'Ingreso',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[4][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[4][2],$alto_fila, (trim($detalle_info['persla_fechaini']) != '') ? _get_date_pg(trim($detalle_info['persla_fechaini'])) : '------' , $bordes,0,'L');
    
    $this->pdf->setXY($columnas[4],$filas[2]);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[4][0],$alto_fila,'Cese',$bordes,0,'L');
    $this->pdf->Cell($sub_columnas[4][1],$alto_fila,':',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[4][2],$alto_fila, (trim($detalle_info['persla_fechacese']) != '') ? _get_date_pg(trim($detalle_info['persla_fechacese'])) : '------' , $bordes,0,'L');
      
    
    $current_Y = ($POSITION_Y_1 +21);
    // LINEA -------------------------------------------------------
    $this->pdf->setXY(10,$current_Y);
    $this->pdf->Cell(190,4,'','T',0,'C');
    
    
    /* CONCEPTOS REMUNERATIVOS  Y VARIABLES */
      
    $_MAX_ROWS_FOR_COLUMN = 40;
    
    $fila_ini_begin = $current_Y + 2;
    
    $fila_ini =  $fila_ini_begin;
    
    
    
    
    $columnas = array('',10,54,106,158);     // POS X 
   // $filas =    array('',40,43,46 ); // POS Y 
    $this->pdf->line(53 ,$fila_ini,53 , $fila_ini + 81);
    $this->pdf->line(105,$fila_ini,105, $fila_ini + 81);
    $this->pdf->line(157,$fila_ini,157, $fila_ini + 52);
    
    $sub_columnas = array(0,array(28,3,11),array(36,3,11),array(36,3,11),array(30,3,11));
  
    $bordes = '';
    $alto_fila = 3;
    $tamanio_letra = 7;  
    
   
    $current_col = 1;
    
/* VARIABLES */
     
    $alto_fila = 3;
    
    /* VARIABLES DE SISTEMA  */ 
    
 
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->setXY($columnas[1],$fila_ini);
    $this->pdf->Cell($sub_columnas[1][0]+$sub_columnas[1][1]+$sub_columnas[1][2],$alto_fila,'VARIABLES',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
     
    
    $fila_ini+=4;
    
    foreach( $variables['calculo_sistema'] as $k => $var)
    {
        $variable_unidad = '';
        
        if(MOSTRAR_UNDIADES_EN_BOLETA)
        {

            $variable_unidad = sprintf("%-3s", trim($var['unidad_abrev']) ); 

        }


        $this->pdf->setXY($columnas[1],$fila_ini);
        $this->pdf->Cell($sub_columnas[1][0],$alto_fila,$var['nombre_corto'],$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[1][2],$alto_fila, sprintf('%.2f',trim($var['plaev_valor'])).' '.$variable_unidad,$bordes,0,'R');
        $fila_ini+=$alto_fila;
    }
    
    
    $this->pdf->setXY($columnas[$current_col],$fila_ini);
    $this->pdf->Cell($sub_columnas[$current_col][0]+$sub_columnas[$current_col][1]+$sub_columnas[$current_col][2],$alto_fila,'------------------------------------------------',$bordes,0,'L');
    
    
    $fila_ini+=4;
    
    $this->pdf->setXY($columnas[1],$fila_ini);
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->Cell($sub_columnas[1][0]+$sub_columnas[1][1]+$sub_columnas[1][2],$alto_fila,'DATOS',$bordes,0,'C');
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    $fila_ini+=4;
    /* VARIABLES DEL USUARIO */
   

    foreach( $variables['calculo_empleado'] as $k => $var)
    {
    


        if(MOSTRAR_DOMINICAL_BOLETAS)
        {


            if($planilla_info['plati_id'] == '5' && $var['vari_id'] == '8')
            {
                $this->pdf->setXY($columnas[1],$fila_ini);
                $this->pdf->Cell($sub_columnas[1][0],$alto_fila,'DESC. SEMANAL',$bordes,0,'L');
                $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'L');
                $this->pdf->Cell($sub_columnas[1][2],$alto_fila, trim($var['plaev_valor']).' / 6   ' ,$bordes,0,'R');
                $fila_ini+=$alto_fila;
            }


            if($planilla_info['plati_id'] == '9' && $var['vari_id'] == '391')
            {
                $ndias = round(( $var['plaev_valor'] / 8),0);

                $this->pdf->setXY($columnas[1],$fila_ini);
                $this->pdf->Cell($sub_columnas[1][0],$alto_fila,'DESC. SEMANAL',$bordes,0,'L');
                $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'L');
                $this->pdf->Cell($sub_columnas[1][2],$alto_fila, trim($ndias).' / 6   ' ,$bordes,0,'R');
                $fila_ini+=$alto_fila;
            }

        }

        $variable_unidad = '';
        
        if(MOSTRAR_UNDIADES_EN_BOLETA)
        {

            $variable_unidad = sprintf("%-3s", trim($var['unidad_abrev']) ); 

        }

        $monto =  sprintf('%.2f',trim($var['plaev_valor']));
        $monto_f = sprintf("%15s",  $monto ); 

        $this->pdf->setXY($columnas[1],$fila_ini);
        $this->pdf->Cell($sub_columnas[1][0],$alto_fila,$var['nombre_corto'],$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[1][1],$alto_fila,':',$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[1][2],$alto_fila, $monto_f.' '.$variable_unidad ,$bordes,0,'R');
        $fila_ini+=$alto_fila;
    }
    $this->pdf->setXY($columnas[$current_col],$fila_ini);
    $this->pdf->Cell($sub_columnas[$current_col][0]+$sub_columnas[$current_col][1]+$sub_columnas[$current_col][2],$alto_fila,'------------------------------------------------',$bordes,0,'L');
     
    
    
/* INGRESOS */ 
    
    $alto_fila = 4; ; // SUPORTE HASTA  19 ingresos | 14 descuentos
    
    $fila_ini = $fila_ini_begin;
    $current_col = 2;
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->setXY($columnas[$current_col],$fila_ini);
    $this->pdf->Cell($sub_columnas[$current_col][0]+$sub_columnas[$current_col][1]+$sub_columnas[$current_col][2],$alto_fila,'REMUNERACIONES E INGRESOS',$bordes,0,'C');
     
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    
    $fila_ini+=4;
    
    $ingresos = 0;
    
    foreach( $conceptos['ingresos'] as $k => $concepto){
        
        $this->pdf->setXY($columnas[2],$fila_ini);
        $this->pdf->Cell($sub_columnas[$current_col][0],$alto_fila,$concepto['nombre_corto'],$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[$current_col][1],$alto_fila,':',$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[$current_col][2],$alto_fila,sprintf('%.2f',trim($concepto['plaec_value'])),$bordes,0,'R');
        $fila_ini+=$alto_fila;
        $ingresos+=$concepto['plaec_value'];
    }
    
    $this->pdf->setXY($columnas[$current_col],$fila_ini);
    $this->pdf->Cell($sub_columnas[$current_col][0]+$sub_columnas[$current_col][1]+$sub_columnas[$current_col][2],$alto_fila,'------------------------------------------------------',$bordes,0,'L');
    
    
 
    
/* DESCUENTOS  */ 
    
    $descuentos = 0;
    
    $fila_ini = $fila_ini_begin;
    $current_col = 3;
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->setXY($columnas[$current_col],$fila_ini);
    $this->pdf->Cell($sub_columnas[$current_col][0]+$sub_columnas[$current_col][1]+$sub_columnas[$current_col][2],$alto_fila,'DEDUCCIONES Y DESCUENTOS',$bordes,0,'C');
     
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    
    $fila_ini+=4;
    
    foreach( $conceptos['descuentos'] as $k => $concepto){
  //  for($i=1; $i<=26; $i++){     
        $this->pdf->setXY($columnas[$current_col],$fila_ini);
        //$this->pdf->Cell($sub_columnas[$current_col][0],$alto_fila,$var['variable'],$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[$current_col][0],$alto_fila, $concepto['nombre_corto'],$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[$current_col][1],$alto_fila,':',$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[$current_col][2],$alto_fila,sprintf('%.2f',trim($concepto['plaec_value'])),$bordes,0,'R');
        $fila_ini+=$alto_fila;
        $descuentos+=$concepto['plaec_value'];
        
    }
    $this->pdf->setXY($columnas[$current_col],$fila_ini);
    $this->pdf->Cell($sub_columnas[$current_col][0]+$sub_columnas[$current_col][1]+$sub_columnas[$current_col][2],$alto_fila,'------------------------------------------------------',$bordes,0,'L');
    
    
/* APORTACIONES */
    
    $aportaciones  = 0;
    
    $fila_ini = $fila_ini_begin;
    $current_col = 4;
    $this->pdf->SetFont('Arial','B',$tamanio_letra);
    $this->pdf->setXY($columnas[$current_col],$fila_ini);
    $this->pdf->Cell($sub_columnas[$current_col][0]+$sub_columnas[$current_col][1]+$sub_columnas[$current_col][2],$alto_fila,'APORTACIONES',$bordes,0,'C');
     
    $this->pdf->SetFont('Arial','',$tamanio_letra);
    
    $fila_ini+=4;
    
    foreach( $conceptos['aportaciones'] as $k => $concepto){
        
        $this->pdf->setXY($columnas[$current_col],$fila_ini);
        $this->pdf->Cell($sub_columnas[$current_col][0],$alto_fila,$concepto['nombre_corto'],$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[$current_col][1],$alto_fila,':',$bordes,0,'L');
        $this->pdf->Cell($sub_columnas[$current_col][2],$alto_fila,sprintf('%.2f',trim($concepto['plaec_value'])),$bordes,0,'R');
        $fila_ini+=$alto_fila;
        $aportaciones+=$concepto['plaec_value'];
        
    }
    
    $this->pdf->setXY($columnas[$current_col],$fila_ini);
    $this->pdf->Cell($sub_columnas[$current_col][0]+$sub_columnas[$current_col][1]+$sub_columnas[$current_col][2],$alto_fila,'------------------------------------------------',$bordes,0,'L');
    
    
 /* TOTALES */    
    $total = $ingresos - $descuentos;
     $resumen_pos_y = 35;
    
    $fila_ini = $fila_ini_begin;
    $alto_fila =5; 
    $this->pdf->SetFont('Arial','B',$tamanio_letra); 
   
    $this->pdf->line(158, $fila_ini +  $resumen_pos_y,200, $fila_ini +  $resumen_pos_y);
    
    $this->pdf->setXY(158,$fila_ini +  $resumen_pos_y);
    $this->pdf->Cell( 23,$alto_fila,'Total Ingresos','',0,'L');
    $this->pdf->Cell( 3,$alto_fila,':','',0,'C');
    $this->pdf->Cell( 14,$alto_fila,sprintf('%.2f',$ingresos),'',0,'R');
    
    $this->pdf->setXY(158,$fila_ini +  $resumen_pos_y + 4 );
    $this->pdf->Cell( 23,$alto_fila,'Total Descuentos','',0,'L');
    $this->pdf->Cell( 3,$alto_fila,':','',0,'C');
    $this->pdf->Cell( 14,$alto_fila,sprintf('%.2f',$descuentos),'',0,'R');
    
    $this->pdf->setXY(158,$fila_ini +  $resumen_pos_y + 8 );
    $this->pdf->Cell( 23,$alto_fila,'NETO A PAGAR','',0,'L');
    $this->pdf->Cell( 3,$alto_fila,':','',0,'C');
    $this->pdf->Cell( 14,$alto_fila,sprintf('%.2f',$total),'',0,'R');
    
    $this->pdf->setXY(158,$fila_ini + $resumen_pos_y + 12);
    $this->pdf->Cell( 23,$alto_fila,'Total Aportes','',0,'L');
    $this->pdf->Cell( 3,$alto_fila,':','',0,'C');
    $this->pdf->Cell( 14,$alto_fila,sprintf('%.2f',$aportaciones),'',0,'R');
    
    
    // FIRMAS
      
    $this->pdf->line(105, ($fila_ini + $resumen_pos_y + 17), 200, ($fila_ini + $resumen_pos_y + 17) ); // Linea separacion de firmas
     
    $this->pdf->SetFont('Arial','B',7);
    
    $this->pdf->setXY(110,$fila_ini + 75);
    $this->pdf->Cell( 40,4,'EMPLEADOR','T',0,'C');
    
    $this->pdf->setXY(153,$fila_ini + 75);
    $this->pdf->Cell( 40,4,'TRABAJADOR','T',0,'C');


    $imagen_firma = $detalle_info['firma_imagen'];

    $this->pdf->Image('frontend/images/institucion/'.$imagen_firma ,   112, ($fila_ini + 53), 38,21); // modificado 01/01/2019 octavio
//    $this->pdf->Image('frontend/images/institucion/'.$imagen_firma ,   118, ($fila_ini + 53), 21,21); 


    // LINEA CIERRE -------------------------------------------------------
    $this->pdf->setXY(10, ($POSITION_Y + 120));
    $this->pdf->Cell(190,4,'','T',0,'C');

if(MOSTRAR_FECHAGENERADO_BOLETA === TRUE)
{

    $this->pdf->setXY(10, ($POSITION_Y + 121));

    $this->pdf->SetFont('Arial','', 5); 
    $this->pdf->Cell( 40,4,'Generado el '._get_date_pg(trim($detalle_info['plaemp_fecreg'])).' a las '._get_date_pg(trim($detalle_info['plaemp_fecreg']),'hora')  ,'',0,'L');
}







