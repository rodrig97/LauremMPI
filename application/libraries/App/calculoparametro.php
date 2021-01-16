<?PHP
  
 Class calculoparametro extends Table
 {
     
      
    protected $_FIELDS=array(   
                                    'id'    => 'calpar_id',
                                    'code'  => 'calpar_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'calpar_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'calculos_parametros';
    protected $_PREF_TABLE= 'calcconparam'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

    public function registrar($params = array(), $return_id = false){

        $sql =" SELECT * FROM planillas.calculos_parametros WHERE calpar_estado = 1 AND plati_id = ? AND calculotipo_id = ? AND vari_id = ? ";
        list($rs) = $this->_CI->db->query($sql, array($params['plati_id'], $params['calculotipo_id'], $params['vari_id'] ))->result_array();

        if(sizeof($rs) == 0){

            return parent::registrar($params, $return_id);
        }
        else {

            return ($return_id) ? array($rs['calpar_id'], $rs['calpar_key']) : true;
        }
    }

    public function get($params = array()){

        $sql = "SELECT cp.*,
                       vari.vari_nombre
                FROM planillas.calculos_parametros cp 
                LEFT JOIN planillas.variables vari ON cp.vari_id = vari.vari_id 
                WHERE calpar_estado = 1 AND cp.plati_id = ? AND cp.calculotipo_id = ?
               ";

        $rs = $this->_CI->db->query($sql, array($params['plati_id'], $params['calculotipo_id']))->result_array();

        return $rs;
    }

}