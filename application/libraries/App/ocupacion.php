<?PHP
 
  
 Class ocupacion extends Table{
     
      
    protected $_FIELDS=array(   
                                    'id'    => 'ocu_id',
                                    'code'  => 'ocu_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'ocu_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'ocupacion';
    protected $_PREF_TABLE= 'OCUPACIONx'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
 
    public function get_list()
    {

        $sql = " SELECT * FROM planillas.ocupacion WHERE ocu_estado = 1 AND ocu_nombre != '' ORDER BY ocu_nombre";
        return $this->_CI->db->query($sql)->result_array();
    }
    
    public function actualizar_trabajadores($params =  array())
    {
        $sql = " ";     
    } 


}   
 