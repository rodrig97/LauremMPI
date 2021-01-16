<?php

/*! @Class Catalog
 *  @Brief 
 *   datospersonales
 */

class Table{
    
    protected $_FIELDS=array(   
                                    'id'    => '',
                                    'code'  => '',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => ''
                            );
    
    protected $_ID_DEFAULT        =null; //Id protegido, valor x defecto, ej "No especificado"
    protected $_TABLES_ASSOCIATED = array();
    protected $_TABLE             = null;
    protected $_TABLE_WS          = '';
    protected $_CI                = null;
    protected $_PREF_TABLE        = null; //Sirve para concatenar con el id, aplicar MD5 y generar el CODE
    
    protected $_SCHEMA = 'public';
    
    
    public function __construct() {
        
        $this->_CI=& get_instance(); 
        $this->_CI->load->database();
         
        $this->_TABLE_WS = $this->_SCHEMA.'.'.$this->_TABLE;


    }
     
    
    public function registrar($values = array(), $Return_Id = false){
        
         
         $r=$this->_CI->db->insert($this->_TABLE_WS,$values);
         
         if($r){
             
             $id=$this->_CI->db->insert_id();
             $code=$id.$this->_PREF_TABLE;
             $code=md5($code);
             
             $gc=$this->actualizar($id, array( $this->_FIELDS['code'] => $code ),false); //Generate code
             if(!$Return_Id) return ($gc) ? true : false;
             else return array($id,$code); 
         }
         else{
             return false;
         }
         
    }
    
    
    public function actualizar($id,$data = array(),$is_code=true){
        
        $p=($is_code)? 'code' : 'id';
 
        $op= $this->_CI->db->where($this->_FIELDS[$p], $id)
                           ->update($this->_TABLE_WS, $data); 
        
        return $op;
    }
    
    
    
    // $id , true si desea eliminar de las tablas asociadas
    public function eliminar($id,$update_associate=false,$is_code=false){
      
        $this->_CI->db->trans_begin();
            
            $p=($is_code)? 'code' : 'id';
          
            $idN=$id;
            if($is_code) $idN=$this->get_id_for_code($id);
           
            $this->_CI->db->where($this->_FIELDS[$p], $id) 
                           ->delete($this->_TABLE_WS); 

            
            if($update_associate){

                foreach($this->_TABLES_ASSOCIATED as $table)
                {
                    $this->_CI->db->where($this->_FIELDS['id'], $idN) 
                                  ->update($table, array($this->_FIELDS['id'] =>  $this->_ID_DEFAULT )); 
                }
            }
         
        if ($this->_CI->db->trans_status() === FALSE)
        {
            $this->_CI->db->trans_rollback();
            //Registro del error en log del sistema
            return false;
        }
        else
        {
            $this->_CI->db->trans_commit();
            return true;
        }
        
    }
    
    
    public function activar($id, $is_code=false){
        
        $p=($is_code)? 'code' : 'id';
        $this->_CI->db->where($this->_FIELDS[$p], $id)
                       ->update($this->_TABLE_WS, array($this->_FIELDS['state'] => '1')); 
    }
    
    
    public function desactivar($id,$update_associate=true,$is_code=false){
         
        
        $this->_CI->db->trans_begin();
            
            $p=($is_code)? 'code' : 'id';
          
            $idN=$id;
            if($is_code) $idN=$this->get_id_for_code($id);
           
            $this->_CI->db->where($this->_FIELDS[$p], $id)
                          ->update($this->_TABLE_WS, array($this->_FIELDS['state'] => '0')); 
 
            if($update_associate){

                foreach($this->_TABLES_ASSOCIATED as $table)
                {
                    $this->_CI->db->where($this->_FIELDS['id'], $idN) 
                                  ->update($table, array($this->_FIELDS['id'] =>  $this->_ID_DEFAULT )); 
                }
            }
         
        if ($this->_CI->db->trans_status() === FALSE)
        {
            $this->_CI->db->trans_rollback();
            //Registro del error en log del sistema
            return false;
        }
        else
        {
            $this->_CI->db->trans_commit();
            return true;
        }
        
        
    }
    
    
    public function get_id($code){
        
        $q =  $this->_CI->db->get_where($this->_TABLE_WS, array($this->_FIELDS['code'] => trim($code)), 1, 0);
        $r= $q->result_array();
     
        return (sizeof($r)==1) ? $r[0][$this->_FIELDS['id']] : false;
    }
    
    
    public function get_code($id){
        
        $q =  $this->_CI->db->get_where($this->_TABLE_WS, array($this->_FIELDS['id'] => trim($id)), 1, 0);
        $r= $q->result_array();
     
        return (sizeof($r)==1) ? $r[0][$this->_FIELDS['code']] : false;
    }
    
    
   
    public function get($id, $is_code=false){
        
        $p=($is_code)? 'code' : 'id';
        $op= $this->_CI->db->where($this->_FIELDS[$p], $id)
                      ->from($this->_TABLE_WS)
                      ->limit(1);
                      
        return $op->get()->result_array();
    }
    
    
    public function load_for_combo($fieldid_id=true, $field_label = null,$field_order  = null){
        
        $field_id=($fieldid_id) ? 'id' : 'code';
        $field_id = $this->_FIELDS[$field_id];
        $field_label = ($field_label != null ) ? $field_label : $this->_FIELDS['name'];
        $field_order = ($field_order != null ) ? $field_order : $this->_FIELDS['name'];
        
        $sql = 'SELECT '.$field_id.' as id, '.$field_label.' as label, '.$this->_FIELDS['id'].'  FROM '.$this->_TABLE_WS.' WHERE '.$this->_FIELDS['state'].' = 1 ORDER BY '.$field_order;
       /* $q = $this->_CI->db->select(array($this->_FIELDS[$t],$field))
                      ->from($this->_TABLE_WS)
                       ->where($this->_FIELDS['state'],'1')
                      ->order_by($field);     */

                  
        
        $q=$this->_CI->db->query($sql);
        
        return $q->result_array();
        
    }
    
    
}


?>
