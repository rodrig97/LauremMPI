<?php

class biometricoimportacion extends Table{
     

   protected $_FIELDS=array(   
                                    'id'          => 'bimp_id',
                                    'code'        => 'bimp_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'bimp_estado'
                            );
    
   protected $_SCHEMA     = 'planillas';
   protected $_TABLE      = 'biometrico_importacion';
   protected $_PREF_TABLE = 'bioimporx'; 
   

   private $_columna_dni = 0;

   private $_columna_fecha = 0;


   public function registrar( $values = array(), $return_id = false)
   {
        
         $sql = " UPDATE planillas.biometrico_importacion SET bimp_estado = 0 WHERE biom_id = ? ";
         $this->_CI->db->query($sql, array($values['biom_id']));

        return parent::registrar($values, $return_id);

   }

 
   public function get_list()
   {

         $sql = " SELECT * FROM planillas.biometrico WHERE biom_estado = 1 ORDER BY biom_descripcion ";
         $rs = $this->_CI->db->query($sql, array())->result_array();
         return $rs;
   }

   public function set_idimportacion_byFile( $biom_id, $file_id)
   {

         $sql =" UPDATE planillas.biometrico_data SET bimp_id = ? WHERE archim_id = ? ";
         $rs = $this->_CI->db->query($sql, array($biom_id, $file_id));
         
         return ($rs) ?  true : false;
   }

}