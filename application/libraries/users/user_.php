<?php

class User extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => 'user_id',
                                    'code'  => 'user_key',
                                    'name'  => 'user_nombre',
                                    'descripcion' => '',
                                    'state' => 'user_estado'
                            );
    
    protected $_TABLE = 'sisplae.users';
    protected $_PREF_TABLE= 'USERSYS'; 
    
    protected $_SCHEMA = 'sisplae';
    
    public function __construct(){
        
        parent::__construct();
       $this->_CI->load->library(array('users/sesiones'));
        
    }   
    
    public function login($user,$pass){
        
        $sql ="SELECT user_id FROM ".$this->_SCHEMA.".users WHERE user_nick = ? AND user_pass = ? LIMIT 1";
        $q =  $this->_CI->db->query($sql,array($user,$pass))->result_array();
     
       
        if(  sizeof($q) == 1 ){
          //   $this->_CI->sesion
           $info = $q[0]; 
           list($id,$code)  =   $this->_CI->sesiones->register($info['user_id']);
           
           $info['sesion'] = $code;
           $info['sesion_id'] = $id; 
           
           return $info;
           
        }
        else{
            
            return false;
        }
        
        
    }
    
    
    public function pattern_islogin(){
        
        
         list($islogin,$user_info) = $this->get_login($_COOKIE['user_rh']);
         
         
         
         if($islogin===false){
                  header('location: '.base_url().'users/login');
         }
          
         return $user_info;
        
    }
    
   
    public function pattern_islogin_a(){
        
        
         list($islogin,$user_info) = $this->get_login($_COOKIE['user_rh']);
         
         if($islogin===false){
                die(json_encode(array('result'=> '-1', 
                                      'error_type' => 'user001',
                                      'mensaje' => 'Usted no ha iniciado sesion'))); 
         }
          
         return $user_info;
        
    }
    
    
    public function  get_login($sesion_id = '', $user_key = null){
        
        
         if($user_key!==null){ 
             
            $sql ="SELECT * FROM  ".$this->_SCHEMA.".users WHERE us|er_key =  ? LIMIT 1";
            $user =  $this->_CI->db->query($sql,array($user_key))->result_array();
            $id = $user[0]['user_id'];
         } 
         
         //Obtenemos la info de la sesion
         $sql ="SELECT * FROM  ".$this->_SCHEMA.".user_sessions WHERE usse_key =  ?  LIMIT 1 ";
         $sesion =  $this->_CI->db->query($sql,array($sesion_id))->result_array();
       //  var_dump($sesion);
         if(sizeof($sesion)==1){
             
            if($user_key!==null){ 
                
                return ($sesion[0]['user_id']==$id) ? array(true,$user[0]) : array(false,false);
                
            }
            else{
                $sql ='SELECT us.*,depe_nombre FROM  '.$this->_SCHEMA.'.users us 
                      LEFT JOIN "'.$this->_SCHEMA.'".dependencias depe ON us.depe_id = depe.depe_id  WHERE user_id =  ? LIMIT 1';
              
                $user =  $this->_CI->db->query($sql,array($sesion[0]['user_id']))->result_array();
                return array(true,$user[0]);
            } 
            
         }
         else{
             return array(false,false);
         }
    }
    
    public function cerrar_sesion(){
        
    }
    
    public function registrar(){
        
    }
    
    public function actualizar(){
        
    }
    
    public function insert(){
        echo 'me llamaron ami ;';
    }
    
    public function current(){
        if(func_num_args()==1){
             // Si se pasa un parametro, ntons se sobre entiende que el parametro es el compo que se quiere recuperar
        }
        
    } 
    
}

?>
