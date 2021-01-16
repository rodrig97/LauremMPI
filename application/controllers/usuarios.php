<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
class usuarios extends CI_Controller {
    
    
    public $cargos;
    public $permisos;
    
    
    public function __construct()
    {
        parent::__construct();
         
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else
        {
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
   
         $this->load->library(array('users/user'));
 
    } 

    public function gestionar_usuarios()
    {   

        $this->load->view('sistema/p_gestionar_usuarios', array());

    }

    public function get_usuarios()
    {   
        header("Content-Type: application/json");
        
        $rs = $this->user->get_usuarios();

        $c = 1;
        foreach($rs as $reg)
        {
            $data['id']   =   trim($reg['key']);
            $data['col1'] =  $c;
            $data['col2'] =  trim($reg['nombres']);
            $data['col3'] =  trim($reg['dni']);
            $data['col4'] =  trim($reg['usuario']); 
            $data['col5'] =  (trim($reg['syus_categoria']) != '' ? trim($reg['syus_categoria']) : '-----'); 
            $data['col6'] =  ( trim($reg['estado']) == '1' ? 'Si' : 'No' ); 
        
            $response[] = $data;
            $c++;
        }

        echo json_encode($response) ;

    }


    public function detalle_usuario()
    {   

        $this->load->library(array('users/user'));

        $view = $this->input->post('view');

        $syus_id = $this->user->get_id($view);

        $info = $this->user->by_id($syus_id);
 
        $menu = $this->user->config_get_menu( array('usuario' => $syus_id, 'parent' => 0 ));
 
        $otras_opciones = $this->user->get_opciones_menu(  array('usuario' => $syus_id, 'menu' => 0 ) );


        $this->load->view('sistema/v_detalle_usuario', array( 'info' => $info, 
                                                              'menu' => $menu, 
                                                              'usuario' =>   $view, 
                                                              'usuario_id' => $syus_id,
                                                              'otras_opciones' => $otras_opciones 

                                                              ) );
    }


    public function actualizar_llave()
    {

        $this->load->library(array('users/user','users/menu','users/opcionsistema'));


        $data = $this->input->post();


        $func = $data['view'];

        $usuario = $data['us'];

        $estado  = ($data['estado'] == '1') ? true : false;

        $tipo = $data['tipo'];

        $usuario_id = $this->user->get_id($usuario);

       // var_dump($usuario_id);

        if($tipo == 'menu' || $tipo=='submenu')
        {


            $params = array(

                            'usuario' => $usuario_id,
                            'menu'    => $this->menu->get_id($func),
                            'estado'  => $estado,
                            'tipo'    => $tipo

                      );

            $rs =  $this->user->set_permiso_menu($params);
     
        }
        else
        {

            $params = array(

                            'usuario' => $usuario_id,
                            'opcion'  => $this->opcionsistema->get_id($func),
                            'estado'  => $estado

                      );

            $rs =  $this->user->set_option_sistema($params);

        }    

        $response =  array(
               
               'result'  =>  ($rs)? '1' : '0',
               'mensaje' =>  ($rs)? ' Ok ' : 'Ocurrio un error durante la operacion',
               'data'    =>  array()
        );
        
        echo json_encode($response);
 
    }   


    public function nuevo()
    {
         
        $this->load->library(array('App/persona'));

        $data = $this->input->post();

        $dni = trim($data['view']);

         if( is_numeric($dni) === FALSE || strlen($dni) != NUMERO_CARACTERES_DNI )
        {
            
            echo ' <b> EL DNI INGRESADO ES INVALIDO: '.$dni.'</b>';            

        }
        else
        {

            $info = $this->user->by_dni($dni);
            $this->load->view('sistema/v_usuario_nuevo', array('data' => $info, 'dni' => $dni ));
        
        }
    }

    public function registrar_nuevo()
    {

        $this->load->library(array('App/persona'));

        $data = $this->input->post();
 
        $usario_existe =  $this->user->existe_usuario_login(trim($data['usuario']));
        
        $rs = false;

        $mensaje = 'Ocurrio un error durante la operacion';

        if($usario_existe === FALSE)
        {

            if($data['modo'] == '1')
            {

                 $values =  array(
                                 'indiv_nombres' =>  strtoupper( trim($data['nombre'])),
                                 'indiv_appaterno' =>  strtoupper( trim($data['paterno'])),
                                 'indiv_apmaterno' =>  strtoupper( trim($data['materno'])),
                                 'indiv_fechanac' =>   trim($data['fechanac']),
                                 'indiv_direccion1' =>  strtoupper( trim($data['direccion'])),
                                 'indiv_telefono' =>  strtoupper( trim($data['telefono'])),
                                 'indiv_dni'    =>   trim($data['dni'])

                            );


                list($indiv_id, $indiv_key) =  $this->persona->registrar($values, true);
  
            }
            else
            {
                $indiv_id = $this->persona->get_id(trim($data['persona']) );
            }


            if($data['modo'] == '2' || trim($data['usuario_id']) == '')
            { 
                
                 list($ok, $usuario_id)  = $this->user->registrar_usuario( array('indiv_id' => $indiv_id, 
                                                                            'usuario' => trim($data['usuario']), 
                                                                            'pass' => trim($data['psw1']) ) 
                                                                         );

            }
            else
            {
                
                $usuario_id = $this->user->get_id_usuario($data['usuario_id']);
            }


            
            list($ok, $habilitado_id)  = $this->user->habilitar_usuario(array('usuario' => $usuario_id, 
                                                                              'descripcion' => trim($data['descripcion']),
                                                                              'categoria' => trim($data['categoria']) )
                                
                                                                        );

            $rs = true;

        }
        else
        {

            $mensaje = ' El ID de usuario no esta disponible';
        }


        $response =  array(
               
               'result'  =>  ($rs)? '1' : '0',
               'mensaje' =>  ($rs)? ' Usuario registrado y habilitado correctamente ' : $mensaje ,
               'data'    =>  array()
        );
        
        echo json_encode($response);
 
    }
}   