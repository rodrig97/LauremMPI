<?PHP
  
 Class calculoconcepto extends Table
 {
     
      
    protected $_FIELDS=array(   
                                    'id'    => 'calconc_id',
                                    'code'  => 'calconc_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'calconc_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'calculos_conceptos';
    protected $_PREF_TABLE= 'calcconcpto'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }


    public function registrar($params = array(), $return_id = false){

        $sql =" SELECT * FROM planillas.calculos_conceptos WHERE calconc_estado = 1 AND plati_id = ? AND calculotipo_id = ? AND conc_id = ? ";
        list($rs) = $this->_CI->db->query($sql, array($params['plati_id'], $params['calculotipo_id'], $params['conc_id'] ))->result_array();

        if(sizeof($rs) == 0){

            return parent::registrar($params, $return_id);
        }
        else {

            return ($return_id) ? array($rs['calconc_id'], $rs['calconc_key']) : true;
        }
    }

    public function get($params = array()){

        $sql = "SELECT cp.*,
                       conc.conc_nombre
                FROM planillas.calculos_conceptos cp 
                LEFT JOIN planillas.conceptos conc ON cp.conc_id = conc.conc_id 
                WHERE calconc_estado = 1 AND cp.plati_id = ? AND cp.calculotipo_id = ?
               ";

        $rs = $this->_CI->db->query($sql, array($params['plati_id'], $params['calculotipo_id']))->result_array();

        return $rs;
    }
}