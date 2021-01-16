<?php
 

Class System{
    
    private $_CI;
    
    protected $_SCHEMA = 'rh';
    
    public function __construct(){
        
        $this->_CI=& get_instance(); 
        $this->_CI->load->database();
    }
    // MENU 
    public function getMenu($parent = 0, $system = 0, $user = 0){ // User
        
        
        $params = array($system,$parent);

       

        $sql = 'SELECT * FROM  rh.system_menu mnu  ';

        if(SUPERACTIVO === TRUE)
        {

        }
        else
        {
            $sql.= 'LEFT JOIN rh.system_menu_usuario smu ON mnu.sysmnu_id = smu.sysmnu_id AND smu_estado = 1 AND smu_checked = 1
                    LEFT JOIN rh.system_usuario su ON smu.syus_id = su.syus_id AND su.syus_estado = 1         
                    ';
        }

        $sql.='  

                WHERE 
                        sysmnu_estado = 1 
                    AND system_id = ? 
                    AND sysmnu_parent = ?  ';

        if(SUPERACTIVO  === TRUE)
        {

        }
        else
        {
            $sql.="  AND su.usur_id = ?  ";
            $params[] = $user;
        }
                   
                           
         $sql.=' ORDER BY mnu.sysmnu_orden';
         

        $rs = $this->_CI->db->query($sql, $params)->result_array();
         
        return $rs;
    }
    
    public function print_map_menu()
    {
         
    }
    
    private function _get_menu_children(){
        
    }
    

    
    
}

?>
