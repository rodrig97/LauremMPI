<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class tareas extends CI_Controller {
    
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
        
         
        $this->load->library(array( 'App/persona', 'App/tarea' ));
       // ,'Catalogos/provincia','Catalogos/distrito'
       //  
    } 
    
    public function get_fuentes(){
          
        
        $this->load->library('App/fuentefinanciamiento');
        
        $cod_tarea = trim($this->input->post('view'));
        $fuentes = array(); 
        $fuentes_rs  =  array();
 
        if($cod_tarea != '') {
            if(CONECCION_AFECTACION_PRESUPUESTAL) {
                $fuentes_rs  =  $this->fuentefinanciamiento->get_by_tarea($cod_tarea,$this->usuario['anio_ejecucion']);
            }
            else {
                $fuentes_rs  =  $this->fuentefinanciamiento->get_all($this->usuario['anio_ejecucion']);
            }
        }

          foreach($fuentes_rs as $k => $ff){
          
              $fuentes[$k]['fuente_nombre'] =   trim($ff['fuente_id']).'-'.trim($ff['recurso_id']).' '.substr(trim($ff['fuente_nombre']),0,36);

              $fuentes[$k]['fuente_codigo'] = trim($ff['fuente_id']).'-'.trim($ff['recurso_id']);
          }
          

        echo json_encode($fuentes);
        
    }
    

    public function get_clasificador(){

       $this->load->library(array('App/partida'));
       $cod_tarea = trim($this->input->post('view'));

       $clasificadores =  array();

        if($cod_tarea != '')
        {
          $rs  =  $this->partida->get_by_tarea( array('tarea' => $cod_tarea, 'anio_eje' => $this->usuario['anio_ejecucion'] ));
          
          foreach($rs as $k => $clasi){
            
              $nombre = trim($clasi['nombre']);

              if(strlen($nombre) > 50 ){

                 $nombre = '..'.substr($nombre,35,60); 
                 if(strlen($clasi['nombre'])>50) $nombre.=".."; 

              } 
              else{

                 $nombre  = substr($nombre,0,25);
                 if(strlen($clasi['nombre'])>25) $nombre.=".."; 

              }

              $clasificadores[$k]['clasificador_label']   =   trim($clasi['codigo']).'-'.$nombre;

              $clasificadores[$k]['clasificador_id'] =    trim($clasi['id_clasificador']);
          }
        }
        echo json_encode($clasificadores);


    }


    public function visualizar_presupuesto()
    {

        $data = $this->input->post();
  
        if($data['tarea'] == '0' || $data['tarea'] == '')
        {

           die('<span class="sp12b"> Debe especificar una tarea. </span>');
        }

        $rs_saldos = $this->tarea->get_saldoplanillas_por_tarea($data['tarea']);

        $tarea_info = $this->tarea->get_info($data['tarea']);


        $this->load->view('planillas/v_tarea_saldopresupuestal', array('saldos' => $rs_saldos, 'tarea_info' => $tarea_info ) );

    }
}