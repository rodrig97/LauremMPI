<?php

 
class familiar extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'pefa_id',
                                    'code'  => 'pefa_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'pefa_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_familia';
    protected $_PREF_TABLE= 'famili'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    public function get_familia($pers, $ex = array() )
    {
            
         $sql = "SELECT fami.*, parents.paren_nombre, civil.estciv_nombre, 
                          COALESCE(date_part('YEAR', age(now(), fami.pefa_fechanace) ),0) as edad 
                 FROM rh.persona_familia fami  
                 LEFT JOIN rh.parentescos parents ON fami.paren_id = parents.paren_id
                 LEFT JOIN rh.estadocivil civil ON fami.pefa_estadocivil = civil.estciv_id 
                 WHERE pers_id = ?  AND fami.pefa_estado = 1 order by pefa_id 

                 ";
         
         $rs =  $this->_CI->db->query($sql, array($pers))->result_array();
         
         return $rs;
         
    }
    
    public function view($id){
        
            
         $sql = 'SELECT fami.*, parents.paren_nombre,
                   perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni , civil.estciv_nombre,ocu.ocupa_nombre   as ocupacion
             
                 FROM rh.persona_familia fami 
                 LEFT JOIN public.individuo perso ON fami.pers_id = perso.indiv_id 
                 LEFT JOIN rh.parentescos parents   ON fami.paren_id = parents.paren_id
                 LEFT JOIN rh.ocupaciones ocu   ON ocu.ocupa_id = fami.ocupa_id
                 LEFT JOIN rh.estadocivil civil ON fami.pefa_estadocivil = civil.estciv_id 
              
                 where fami.pefa_id = ?  LIMIT 1 ';
          
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
           
    }


    public function estudiante_cambio_estado($params = array())
    {

        $sql = " UPDATE rh.persona_familia SET pefa_estudiante = (CASE WHEN pefa_estudiante = 1 THEN   0 ELSE  1 END )  WHERE pefa_id = ? AND paren_id = ?  ";
    
        $ok = $this->_CI->db->query($sql, array($params['familiar_id'], 4 ));

        return ($ok) ? true : false;
    }
     
    
}
