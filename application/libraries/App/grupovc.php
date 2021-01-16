<?PHP
 
  
 Class grupovc extends Table{
     
      
    protected $_FIELDS=array(   
                                    'id'    => 'gvc_id',
                                    'code'  => 'gvc_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'gvc_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'grupos_vc';
    protected $_PREF_TABLE= 'GRUPOVC'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

    public function get($grupo)
    {
         $sql = "SELECT * FROM planillas.grupos_vc WHERE gvc_id = ? LIMIT 1 ";
         $rs = $this->_CI->db->query($sql, array($grupo))->result_array();   
         
         return $rs[0];
    }

    public function get_list()
    {

        $sql = " SELECT * FROM planillas.grupos_vc WHERE gvc_estado = 1 ORDER BY gvc_nombre";
        return $this->_CI->db->query($sql)->result_array();
    }
 

    public function get_descuentos()
    {
        
        $sql = " SELECT * FROM planillas.grupos_vc 
                 WHERE gvc_estado = 1 AND gvc_reporte_descuento = 1 
                 ORDER BY gvc_nombre 
               ";
        
        return $this->_CI->db->query($sql, array() )->result_array();
    }

}   

?>