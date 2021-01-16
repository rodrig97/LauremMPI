<?php
  
if(!defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class contabilidadcontroller extends CI_Controller {
    
    public $usuario;
    
    public function __construct()
    {
        parent::__construct();
         
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  

        $this->user->set_keys( $this->usuario['syus_id'] );    
        
        $this->load->library(array('App/persona','App/hojaasistencia','App/tipoplanilla','App/anioeje','App/exportador'));
 
    }

    public function contabilizar_planillas(){

        $this->load->library(array('Catalogos/banco',
                                   'Catalogos/afp'));

        $tipos = $this->tipoplanilla->get_all();
        
        $reportes = array();
        
        $reportes['SUNAT']  = $this->exportador->getReportes(REPORTETIPO_SUNAT);   
        $reportes['OTROS']  = $this->exportador->getReportes(REPORTETIPO_OTROS);  
        $reportes['SIAF']   = $this->exportador->getReportes(REPORTETIPO_SIAF);   
        $reportes['BANCOS'] = $this->banco->get_list();   
        $reportes['AFPS']   = $this->afp->get_list();   

        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;


        $this->load->view('planillas/p_contabilizar_planillas', array(
                                                                 'tipos'    => $tipos, 
                                                                 'reportes' => $reportes,
                                                                 'anios'    => $anios
                                                                 ));
        
    }
}