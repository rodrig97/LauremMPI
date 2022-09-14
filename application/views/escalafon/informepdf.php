<?php

class informepdf extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        
    }

    public function informepdf(){
        require_once(APPPATH.'libraries/PHPExcel/Shared/PDF/tcpdf.php');

        //Create pdf document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf -> SetTitle('Legajo Informe');
        $pdf -> addPage();

        //Design doc
        $html = '<h1>Informe Legajo</h1>';
        $html .= '';
        $pdf->writeHTML($html);
        $pdf->Output('informepdfphp', 'I');
    }
    
}

?>