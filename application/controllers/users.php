<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
class users extends CI_Controller {
    
    
    public $cargos;
    public $permisos;
    
    
    public function __construct()
    {
       
         parent::__construct();
   
         $this->load->library(array('users/user', 'App/anioeje'));
 
    }
    
    public function index(){
        
        $this->login();
        
    }
    
    public function login()
    {
        
         list($islogin,$user_info) = $this->user->get_login($_COOKIE[SYSTEM_COOKIE_USER_NAME]);

         $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;
 
         if($islogin===false)
         {
             $this->load->view('pk_login/login.php',array('_PAGE' => 'LOGIN', 'anios' => $anios));
         }
         else{
             
             header('location: '.base_url().'');
         }
          
       
    }
    
    
    public function auntentificar()
    {


        $user = trim($this->input->post('access_to'));
        $for  = trim($this->input->post('pass'));
        $anio = trim($this->input->post('anio'));
        
        $mensaje = '';  

        //$permitidos = array('jalonso','gvaldez','lsalazar','jmontoya','emanchego');
   
        $info = $this->user->login($user,$for, $anio);
 
        if($info === false ) // || !in_array($user, $permitidos) 
        {
      
            $msm = 'Error, Usuario o Password incorrectos.';
            $rs  = '0';
            setcookie(SYSTEM_COOKIE_USER_NAME,'',null,'/');
           
        }
        else{
            
                $msm     = 'Bienvenido';
                $rs      = '1';
                $cookie  = array(
                        'name'   => 'login',
                        'value'  => 'login',
                        'secure' => TRUE
                );

             setcookie(SYSTEM_COOKIE_USER_NAME,$info['sesion'],null,'/');
        }
  
       
        echo json_encode(array( 'result'=>  $rs , 'mensaje' => $msm, 'info' => $info  ) );  
    }
    
    
    
    public function get_areas(){
         
        
         $nick = trim($this->input->post('nick'));
          
         $areas = $this->user->get_areas_by_nick($nick);
           
         echo json_encode($areas); 
    }
    
    
    public function cerrar()
    {
           
            setcookie(SYSTEM_COOKIE_USER_NAME,'',null,'/');
            
            header('location: '.base_url().'users/login');
           
    }
    
    public function view_nuevo(){
         
        $this->load->view('users/nuevo'); 
        
    }
    

}