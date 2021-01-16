<?php


class Menu{
    
    
    protected $_SCHEMA = 'sisplae';
    
    public function __construct(){
        
       $this->_CI=& get_instance(); 
       $this->_CI->load->database();  
    }
    
    public function generar( $user = 1 ){
        
        $sql="SELECT sm.mnu_id,mnu_alias,smo.menop_id,menop_alias,menop_image,menop_to 
                FROM usuario_menu_opciones ump 
                LEFT JOIN sistema_menu_opciones smo ON ump.menop_id = smo.menop_id 
                LEFT JOIN sistema_menu sm ON smo.mnu_id=sm.mnu_id 
                WHERE ump.persona_id =".$user." AND ump.umop_estado=1 
                ORDER BY mnu_orden,menop_order";
        
        $rs=$this->_CI->db->query($sql);
        
        return $rs->result_array();
    }
    
    
    public function addOpcion(){
        
    }
    
    
}

?>
