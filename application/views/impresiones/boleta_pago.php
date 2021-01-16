<?php
 
$this->pdf->initialize('p','mm','A4');
$this->pdf->logo = false;
$this->pdf->AliasNbPages();

$this->pdf->AddPage();
$this->pdf->SetTopMargin(0);
 

$parametos = array(  'planilla_info' => $planilla_info, 
                     'persona_info' => $persona_info,
                     'conceptos' => $conceptos, 
                     'variables' => $variables,
                     'detalle_info' => $detalle_info  );

$parametos['POSICION_Y_INICIAL'] = IMPRESION_POSY_BOLETA1;

$this->load->view('impresiones/boleta_de_pago',  $parametos );

$parametos['POSICION_Y_INICIAL'] = IMPRESION_POSY_BOLETA2;

$this->load->view('impresiones/boleta_de_pago',  $parametos );



$this->pdf->Output();