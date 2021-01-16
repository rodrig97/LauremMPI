<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class afps extends CI_Controller {
    
    public $usuario;
    
    public function __construct(){
        parent::__construct();
        
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
        
        
        $this->load->helper(array('formatoDB_helper','form', 'url'));

        $this->load->library(array('App/documento','Catalogos/afp'));
         
    } 


    public function gestionar()
    { 

         $table_afp = $this->afp->get_table();

         $this->load->view('planillas/v_gestionar_afps', array('table' => $table_afp));
    }


    public function actualizar()
    {
     
        $datos = $this->input->post('data');

        $datos = explode('_',$datos);
  
        $afp_id = explode('-', $datos[0]);
        $afp_id = $afp_id[1];
        
        $data = array(
                        'obligatorio' => $datos[1],
                        'comision'    => $datos[2],
                        'seguro'      => $datos[3],
                        'cc'          => $datos[4], 
                        'saldo'       => ( trim($datos[5]) == '' ? '0' : $datos[5] ),
                        'flujo'       => '0',
                        'max_asegurable' => $datos[6]
                      );   


        $ok = $this->afp->actualizar_tabla($afp_id, $data);


          $response =  array(

                 'result'  =>  ($ok)? '1' : '0',
                 'mensaje' => ($ok)? ' Datos actualizados correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array('key' => '' )
          );
       
          echo json_encode($response);
     

    }

    


}
    