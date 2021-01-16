<?PHP
 
 /*
    Cambios rama principal xxx
 */  
 Class cuentacontable extends Table
 {
     
      
    protected $_FIELDS=array(   
                                    'id'    => 'ccont_id',
                                    'code'  => 'ccont_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'ccont_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'cuenta_contable';
    protected $_PREF_TABLE= 'cuentax'; 
     
    public function __construct(){
          
        parent::__construct();
          
    }

    public function get(){

    	$sql = "SELECT * FROM planillas.cuenta_contable 
    			WHERE ccont_estado = 1 ORDER BY ccont_codigo ";
   
    	$rs = $this->_CI->db->query($sql, array())->result_array();

    	return $rs;
    }

}
