<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class sisgedo extends CI_Controller {
    
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
        
         $this->load->library(array( 'sisgedocs'));
       // ,'Catalogos/provincia','Catalogos/distrito'
         
    }
    
    
    public function get_info_documento(){
         
        
       $id = trim($this->input->post('codigo'));
     
       $id = ($id=='') ? '0' : $id; 

        $doc = $this->sisgedocs->buscar($id , 'numero');
       /*
        $doc = array(
                     'expe_id'        => '123123', 
                     'asunto'         => ' PRUEBA NO TE OLVIDES DE CORREGIR ', 
                     'expe_codigo'    => 'SGIE-2222', 
                     'tipo_doc'       => 'MEMO', 
                     'expe_fecha_doc' => '12/10/2012', 
                     'expe_firma'     => 'Giordhano Valdez' );* /
 
       $doc['asunto']  = str_replace('"','', trim($doc['asunto']));
       var_dump(trim($doc['asunto']));
       $doc['asunto']  = utf8_decode(stripslashes(trim($doc['asunto'])));
      */

        $doc['asunto']  = utf8_encode(stripslashes(trim($doc['asunto'])));
       $html = "

               <div class='dv_busqueda_personalizada_pa2'> 


                  
                  <input type = 'hidden' value='".trim($doc['expe_id'])."' name='sisgedo_doc' />
                  <input type = 'hidden' value='".trim($doc['expe_codigo'])."' name='sisgedo_codigo' />
                  <input type = 'hidden' value='".trim($doc['tipo_doc'])."' name='sisgedo_tipodoc' />
                  <input type = 'hidden' value='".trim($doc['expe_fecha_doc'])."' name='sisgedo_fecha' />
                  <input type = 'hidden' value='".trim($doc['expe_firma'])."' name='sisgedo_firma' />
                   <input type = 'hidden' value='' name='sisgedo_asunto' />

                 
                
                  <table> 
                     <tr> 
                         <td> <a class='spquitardoc' href='#' > [X] </a> <b>  Documento:</b> ".$doc['expe_id'].", ".$doc['expe_codigo']." (".$doc['tipo_doc'].") ".$doc['expe_fecha_doc'].",  <b>Firma: </b> ".$doc['expe_firma']."   </td>

                     </tr>
                     <tr> 
                       <td> <b>Asunto:</b> ".$doc['asunto']."</td>
                     </tr>

                  </table>
              </div>
               ";

     if($doc['expe_id'] == '') $html = ' <b>No se encontro el documento:</b> '.$id;
       
       echo json_encode( array('data' => array('html'  => $html) ) );
       
    }
    
}