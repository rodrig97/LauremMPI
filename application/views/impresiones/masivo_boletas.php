<?PHP 
 
$this->pdf->initialize('p','mm','A4');
$this->pdf->logo = false;

$this->pdf->AliasNbPages();
 
$np = false;

foreach($planilla_detalle as $detalle)
{

        if($numero_copias == 2){

            $this->pdf->AddPage();
        
        } else {

            if($np === false){

                $this->pdf->AddPage(); 
                $np = true;
            }else{
                $np = false;
            }

        }

        $this->pdf->SetTopMargin(0);
    

        if($planilla_info=='' || is_null($planilla_info) || is_array($planilla_info) == FALSE )
        {

            $planilla_info    = $this->planilla->get($detalle['pla_id']);  
             
        }

        $persona_info = $this->persona->get_info($detalle['indiv_id']);
        $variables                     = array();
        $variables['calculo_empleado'] = $this->planillaempleado->get_planillaempleado_variables($detalle['detalle_id'], true,  true);
        $variables['calculo_sistema']  = $this->planillaempleado->get_planillaempleado_variables($detalle['detalle_id'], false, true);
         
        $conceptos                     = array();
        $conceptos['ingresos']         = $this->planillaempleado->get_planillaempleado_conceptos($detalle['detalle_id'], TIPOCONCEPTO_INGRESO , 1 , true );
        $conceptos['descuentos']       = $this->planillaempleado->get_planillaempleado_conceptos($detalle['detalle_id'], TIPOCONCEPTO_DESCUENTO, 1 , true);
        $conceptos['aportaciones']     = $this->planillaempleado->get_planillaempleado_conceptos($detalle['detalle_id'], TIPOCONCEPTO_APORTACION, 1 , true);
        
        $detalle_info = $this->planillaempleado->get($detalle['detalle_id']);


        $parametos = array(  'planilla_info' => $planilla_info, 
                             'persona_info' => $persona_info,
                             'conceptos' => $conceptos, 
                             'variables' => $variables,
                             'detalle_info' => $detalle_info  );

        $parametos['POSICION_Y_INICIAL'] = IMPRESION_POSY_BOLETA1;
         
        if($numero_copias == 1 && $np === false){

            $parametos['POSICION_Y_INICIAL'] = IMPRESION_POSY_BOLETA2;
        }

        $this->load->view('impresiones/boleta_de_pago',  $parametos );

        if($numero_copias == 2){

            $parametos['POSICION_Y_INICIAL'] = IMPRESION_POSY_BOLETA2;

            $this->load->view('impresiones/boleta_de_pago',  $parametos );
            
        }

        $planilla_info = '';
 
}



$this->pdf->Output();

?>
