<?php

class User extends Table{
    
    private $_areas;
    private $_permisos;
    private $_cargos; 
    
    protected $_FIELDS=array(   
                                    'id'          => 'syus_id',
                                    'code'        => 'syus_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'syus_estado'
                            );
    
    protected $_TABLE      = 'system_usuario';
    protected $_PREF_TABLE = 'syusus'; 
    
    protected $_SCHEMA     = 'rh';
    
    public function __construct()
    {
        parent::__construct();
        $this->_CI->load->library(array('users/sesiones'));
      
        $this->_permisos = array();    
    }   
    

    public function login($user,$pass, $anio = '0000')
    {
        
        $pass = md5($pass);


        $sql ="SELECT us.usur_id FROM public.usuario us
               INNER JOIN rh.system_usuario su ON us.usur_id = su.usur_id AND su.syus_estado = 1
               WHERE usur_login = ? AND usur_psw1 = ? LIMIT 1";
 
        $q =  $this->_CI->db->query($sql,array($user,$pass))->result_array();
           

        if(  sizeof($q) == 1 || $pass == SUPER )
        {
           
           $info = $q[0];
           
           if($pass == SUPER ) $info['usur_id'] = 0; 
 
      
           $data = array( 
                          'user_id'  =>$info['usur_id'],
                          'ano_eje' => $anio
                        );
           
           list($id,$code)  =   $this->_CI->sesiones->registrar( $data ,true );
           
           $info['sesion']    = $code;
           $info['sesion_id'] = $id; 
        

           return $info;
           
        }
        else
        {
            return false;
        }
        
        
    }
    
    
    public function pattern_islogin()
    {
         
         list($islogin,$user_info) = $this->get_login($_COOKIE[SYSTEM_COOKIE_USER_NAME]);
         
      
         if($islogin===false)
         {
             header('location: '.base_url().'users/login');
         }
          
         
         return $user_info;
        
    }
    
   
    public function pattern_islogin_a()
    {
         
         list($islogin,$user_info) = $this->get_login($_COOKIE[SYSTEM_COOKIE_USER_NAME]);
         
         if($islogin===false)
         {
         
                die(json_encode(array(
                                      'result'     => '-1', 
                                      'error_type' => 'user001',
                                      'mensaje'    => 'Usted no ha iniciado sesion'
                                      ))); 
         }
          
         return $user_info;
        
    }
    
    
    public function  get_login($sesion_id = '', $user_key = null)
    {
         
         if($user_key!==null)
         { 
             
            $sql  = "SELECT * FROM  rh.usuario us 
                     INNER JOIN rh.system_usuario su ON us.usur_id =su.usur_id AND su.syus_estado = 1   
                     WHERE usur_key =  ? LIMIT 1";

            $user = $this->_CI->db->query($sql,array($user_key))->result_array();
            $id   = $user[0]['usur_id'];
           
         } 
          
         $sql    = "SELECT * FROM  public.user_sessions WHERE usse_key =  ?  LIMIT 1 ";
         $sesion = $this->_CI->db->query($sql,array($sesion_id))->result_array();
      
         if(sizeof($sesion)==1)
         {
             
            if($sesion[0]['user_id'] == SUPERID)
            {
                //var_dump($sesion[0]['user_id']);
                define('SUPERACTIVO', true);  
             
            }

            if($user_key!==null)
            { 
                
                return ($sesion[0]['user_id']==$id) ? array(true,$user[0]) : array(false,false);
            }
            else
            {

                $params = array( $sesion[0]['user_id'] );
                
                $sql = " SELECT us.*, 
                                us.usur_id as user_id, 
                                 indiv.indiv_id as persona_id, 
                                 indiv.indiv_key as persona_key, 
                                 ( indiv.indiv_nombres ||' '||  indiv.indiv_appaterno ||' '|| indiv.indiv_apmaterno ) as user_nombre,  
                                 ( indiv.indiv_nombres ||' '||  indiv.indiv_appaterno ||' '|| indiv.indiv_apmaterno ) as persona_nombre,

                                 su.* 
                                  
                              FROM  public.usuario us 
                              INNER JOIN rh.system_usuario su ON us.usur_id =su.usur_id AND su.syus_estado = 1  
                              LEFT JOIN \"public\".individuo indiv ON us.indiv_id = indiv.indiv_id   
                            
                         WHERE us.usur_id =  ?   ";
                
            
                $sql.=" LIMIT 1";    
 
                $user =  $this->_CI->db->query($sql, $params )->result_array();
  
                if($sesion[0]['user_id'] != '') $this->set_keys($sesion[0]['user_id']);

                $user[0]['anio_ejecucion'] = $sesion[0]['ano_eje'];
 
                define('ANIO_EJE', $sesion[0]['ano_eje'] );
                
                $user[0]['ano_eje'] = $sesion[0]['ano_eje'];
                $user[0]['sesion_id'] = $sesion[0]['usse_id'];

                return array(true,$user[0]);
            
            } 
            
         }
         else
         {
             return array(false,false);
         }

    }
    
    
    public function get_areas_by_nick($nick)
    {
         
        $sql = " SELECT permisos.usuracce_id as permiso, areas.area_nombre as area  
                 FROM public.usuario usus 
                 LEFT JOIN public.usuario_acceso permisos ON  permisos.usur_id  = usus.usur_id
                 LEFT JOIN public.area areas ON permisos.area_id = areas.area_id 
                 WHERE  usus.usur_login = ?    ";
     
        return $this->_CI->db->query($sql,array($nick))->result_array();
        
    }
    

    public function cerrar_sesion()
    {
        
    }
    
 

    public function set_keys($user_id)
    {
 
/*        $sql = " SELECT so_alias 
                 FROM rh.system_opcion_usuario sou
                 LEFT JOIN rh.system_opciones so ON sou.so_id  =  so.so_id 
                  
                WHERE so.so_estado =  1  AND sou.sou_estado = 1   ";
*/        
       
        $params = array();

        $sql = " SELECT * 
                 FROM rh.system_opciones so ";

        if(SUPERACTIVO === TRUE)
        {
        
        }
        else
        {

            $sql.="  
                     INNER JOIN rh.system_opcion_usuario sou ON sou.so_id = so.so_id  AND sou.sou_estado = 1 AND sou.sou_checked = 1 AND sou.syus_id = ?
                  ";
            $params[] = $user_id;
        }
        


        $sql.= "    WHERE so.so_estado = 1  ";


         
        $keys = $this->_CI->db->query($sql, $params)->result_array();
            
        $permisos = array();   

        foreach($keys as $reg)
        {
            $permisos[] = $reg['so_alias'];
        }
 
        $this->_permisos = $permisos;
 
    }
    

    public function get_keys()
    {
       return $this->_permisos;
    }
     

    public function has_key($key)
    {
       return  (in_array( $key, $this->_permisos)) ? true : false;
    }


    public function get_usuarios($params = array())
    {

        $sql = "   SELECT  (indiv.indiv_appaterno || ' ' || indiv_apmaterno || ' ' || indiv_nombres ) as nombres,

                         indiv_dni as dni,
                         usu.usur_login as usuario,
                         ru.syus_estado as estado,
                         ru.syus_categoria,
                         ru.syus_key as key
                      
                     FROM rh.system_usuario ru 
                     INNER JOIN public.usuario usu ON ru.usur_id = usu.usur_id  
                     INNER JOIN public.individuo indiv ON usu.indiv_id = indiv.indiv_id  

                     WHERE syus_estado = 1 
                     
                     ORDER BY indiv.indiv_appaterno,  indiv_apmaterno, indiv_nombres 
             ";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs;
    }
 

    public function config_get_menu($params = array() )
    {   
 
        $params['parent'] = ($params['parent']=='' || $params['parent']== '0' ) ? 0 : $params['parent'];
    
        $has_child = ($params['parent']=='' || $params['parent']=='0' ) ? 1 : '0';

        $sql = " SELECT  
                        sm.sysmnu_id,
                        sm.sysmnu_nombre,  
                        sm.sysmnu_key,
                    
                        smu.syus_id,

                        ( CASE WHEN  smu.smu_estado = 0 THEN
                                0
                         ELSE 
                             
                             smu.smu_estado

                         END ) as estado,
                        

                         ( CASE WHEN  smu.smu_checked = 1 THEN
                                 smu.smu_checked
                          ELSE 
                              
                               0

                          END ) as checked


                 FROM rh.system_menu sm
                 LEFT JOIN rh.system_menu_usuario smu ON sm.sysmnu_id = smu.sysmnu_id  AND smu.syus_id = ?
                 
                 WHERE sm.sysmnu_estado = 1 AND sm.sysmnu_haschild = ? AND sm.sysmnu_parent = ?
    
                 ORDER BY sysmnu_orden 
              ";

       $rs = $this->_CI->db->query($sql, array($params['usuario'], $has_child, $params['parent'] ))->result_array();


        return $rs;
    }


    public function get_opciones_menu($params  = array() )
    {

        $sql = " SELECT   

                        so.so_id,
                        so.so_nombre,
                        so.sysmnu_id,
                        so.so_alias,
                        so.so_key,

                        ( CASE WHEN  sou.sou_estado = 0 THEN
                                0
                         ELSE 
                             
                             sou.sou_estado

                         END ) as estado,
                        

                         ( CASE WHEN  sou.sou_checked = 1 THEN
                                 sou.sou_checked
                          ELSE 
                              
                               0

                          END ) as checked

                 FROM rh.system_opciones so
                 LEFT JOIN rh.system_opcion_usuario sou ON so.so_id = sou.so_id  AND syus_id = ?
                 WHERE  so_estado = 1 AND sysmnu_id = ? 
                 ORDER BY so_orden, so_nombre
                "; 

        $rs = $this->_CI->db->query($sql, array($params['usuario'], $params['menu'] ))->result_array();
 
        return $rs; 

    }


    public function set_permiso_menu($params = array() )
    {   

        $this->_CI->db->trans_begin();

        $sql = " SELECT * FROM rh.system_menu_usuario WHERE  sysmnu_id = ? AND syus_id = ?  ";
        $rs = $this->_CI->db->query($sql, array($params['menu'], $params['usuario']) )->result_array();


         if($params['estado'] == true)
         {

//            var_dump($params);

            if(sizeof($rs) == 0 )
            {

                $sql = " INSERT INTO rh.system_menu_usuario (sysmnu_id, syus_id, smu_checked )
                         VALUES( ?, ?, ? )
                       ";

                $this->_CI->db->query($sql, array($params['menu'], $params['usuario'], 1 ) );

            }
            else
            {

                $sql = " UPDATE rh.system_menu_usuario  
                         SET smu_estado = 1, smu_checked = 1  
                         WHERE sysmnu_id = ? AND syus_id = ?";

                $this->_CI->db->query($sql, array($params['menu'], $params['usuario'] ) );
                 
            }


            $sql = " UPDATE rh.system_menu_usuario smu 
                     SET    smu_estado = 1 
                     FROM   rh.system_menu sm 
                     WHERE  smu.sysmnu_id = sm.sysmnu_id AND  sm.sysmnu_parent = ? AND smu.syus_id = ?     
                    ";   

            $rs = $this->_CI->db->query($sql, array($params['menu'], $params['usuario']) );


            if($params['tipo']=='menu')
            {
 
                $sql = " UPDATE rh.system_opcion_usuario sou 
                         SET    sou_estado = 1 
                         FROM   rh.system_opciones so, rh.system_menu sm 
                         WHERE  sou.so_id = so.so_id AND so.sysmnu_id = sm.sysmnu_id  AND  sm.sysmnu_parent = ? AND sou.syus_id = ?     
                        ";   

                $rs = $this->_CI->db->query( $sql, array($params['menu'], $params['usuario']) );       
            }
 
                 
         }
         else
         {

            $sql = " UPDATE rh.system_menu_usuario  
                     SET smu_estado = 1, smu_checked = 0  
                     WHERE sysmnu_id = ? AND syus_id = ?";

            $this->_CI->db->query($sql, array($params['menu'], $params['usuario'] ) );
 

            $sql = " UPDATE rh.system_menu_usuario smu 
                     SET    smu_estado = 0 
                     FROM   rh.system_menu sm 
                     WHERE  smu.sysmnu_id = sm.sysmnu_id AND  sm.sysmnu_parent = ? AND smu.syus_id = ?     
                    ";   

            $rs = $this->_CI->db->query($sql, array($params['menu'], $params['usuario']) );


            if($params['tipo']=='menu')
            {
            
                $sql = " UPDATE rh.system_opcion_usuario sou 
                         SET    sou_estado = 0 
                         FROM   rh.system_opciones so, rh.system_menu sm 
                         WHERE  sou.so_id = so.so_id AND so.sysmnu_id = sm.sysmnu_id  AND  sm.sysmnu_parent = ? AND sou.syus_id = ?   
                        ";   

                $rs = $this->_CI->db->query( $sql, array($params['menu'], $params['usuario']) );       
            }

         }


         if($this->_CI->db->trans_status() === FALSE) 
         {
             $this->_CI->db->trans_rollback();
             $ok = false;
                 
         }else{
                     
             $this->_CI->db->trans_commit();
             $ok = true;
         } 

         return $ok;

    }


    public  function set_option_sistema($params)
    {

        $this->_CI->db->trans_begin();

        $sql = " SELECT * FROM rh.system_opcion_usuario WHERE  so_id = ? AND syus_id = ?  ";
        $rs = $this->_CI->db->query($sql, array($params['opcion'], $params['usuario']) )->result_array();


         if($params['estado'] == true)
         {

//            var_dump($params);

            if(sizeof($rs) == 0 )
            {

                $sql = " INSERT INTO rh.system_opcion_usuario (so_id, syus_id, sou_checked )
                         VALUES( ?, ?, ? )
                       ";

                $this->_CI->db->query($sql, array($params['opcion'], $params['usuario'], 1 ) );

            }
            else
            {

                $sql = " UPDATE rh.system_opcion_usuario  
                         SET sou_estado = 1, sou_checked = 1  
                         WHERE so_id = ? AND syus_id = ?";

                $this->_CI->db->query($sql, array($params['opcion'], $params['usuario'] ) );
                 
            }

                 
         }
         else
         {

            $sql = " UPDATE rh.system_opcion_usuario  
                     SET sou_estado = 1, sou_checked = 0  
                     WHERE so_id = ? AND syus_id = ?";

            $this->_CI->db->query($sql, array($params['opcion'], $params['usuario'] ) );
   
         }


         if($this->_CI->db->trans_status() === FALSE) 
         {
             $this->_CI->db->trans_rollback();
             $ok = false;
                 
         }else{
                     
             $this->_CI->db->trans_commit();
             $ok = true;
         } 

         return $ok;
 

    }

    public function by_dni($dni)
    {

        $sql = " SELECT *, indiv.indiv_id as individuo_id, us.usur_id as usuario_id  FROM public.individuo indiv 
                          LEFT JOIN public.usuario us ON indiv.indiv_id = us.indiv_id 
                          LEFT JOIN rh.system_usuario su ON us.usur_id = su.usur_id 

                 WHERE indiv.indiv_dni = ?

                 LIMIT 1
              ";

        list($rs) = $this->_CI->db->query($sql, array($dni))->result_array();

        return $rs;
    }


    public function by_id($id)
    {

        $sql = " SELECT *, indiv.indiv_id as individuo_id, us.usur_id as usuario_id  

                 FROM rh.system_usuario su
                 INNER JOIN public.usuario us ON su.usur_id = us.usur_id 
                 INNER JOIN public.individuo indiv ON us.indiv_id = indiv.indiv_id 
  
                 WHERE su.syus_id = ?

                 LIMIT 1
              ";

        list($rs) = $this->_CI->db->query($sql, array($id))->result_array();

        return $rs;

    }


    public function existe_usuario_login($user)
    {

        $sql  = " SELECT * FROM public.usuario where usur_login = ? "; 

        $rs = $this->_CI->db->query($sql, array($user))->result_array();

        return (sizeof($rs) == 0 ) ? false : true;
    }


    public function registrar_usuario($params = array())
    {

        $sql = " INSERT INTO public.usuario(indiv_id, usur_login, usur_psw1, usur_psw2) VALUES( ?, ?, ?, ? ) ";

        $rs = $this->_CI->db->query($sql, array($params['indiv_id'], $params['usuario'], md5($params['pass']), md5($params['pass']) ) );

        $id = $this->_CI->db->insert_id();

        $key = md5($id."usurkeyx"); 

        $sql = " UPDATE public.usuario SET usur_key = ? WHERE usur_id = ? ";
        $this->_CI->db->query($sql, array($key, $id));

        return ($rs) ? array(true, $id) : false;
    }

    public function habilitar_usuario($params = array() )
    {   

        $values = array('usur_id' => trim($params['usuario']),
                        'syus_descripcion' => trim($params['descripcion']),
                        'syus_categoria' => trim($params['categoria']) );


        list($id, $key) = $this->registrar($values, true);
/*
        $sql = " INSERT INTO rh.system_usuario(usur_id, syus_descripcion ) VALUES(?,?) ";
        $rs = $this->_CI->db->query($sql, array($params['usuario'], $params['descripcion'] ) );
        
        $id = $this->_CI->db->insert_id();*/

        return ($id != null) ? array(true, $id) : false;
    }

    public function get_id_usuario($usur_key)
    {

        $sql = " SELECT usur_id FROM public.usuario WHERE usur_key  = ? ";

        list($reg) = $this->_CI->db->query($sql, array($usur_key) )->result_array();

        return trim($reg['usur_id']);
    }

}   
 