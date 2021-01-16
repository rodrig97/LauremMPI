<?php

 
class vacacionestrabajador extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'perva_id',
                                    'code'  => 'perva_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'perva_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_vacaciones';
    protected $_PREF_TABLE= 'vacacxx'; 
    
    public function __construct()
    {
        
        parent::__construct();
        
    }
   
    public function get_vacaciones($pers, $params = array() )
    {
            
         $sql =  " SELECT *, 
                         perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,
                         perso.indiv_dni
  
                 FROM rh.persona_vacaciones vac 
                 LEFT JOIN public.individuo perso ON vac.pers_id = perso.indiv_id 
                 WHERE  vac.perva_estado = 1 
                ";


        
        if($pers != '0'  ||  $pers != '' )
        {
            $sql.=" AND vac.pers_id = ? ";
            $query[] = $pers;
        } 


        if($params['fechaini'] != '')
        {

            if($params['fechafin']  != '')
            {

                $sql.=" AND ( perva_fechaini  between ? AND ? ) ";
                $query[] = $params['fechaini'];
                $query[] = $params['fechafin'];
            }
            else
            {

                $sql.=" AND ( perva_fechaini > ? ) ";
                $query[] = $params['fechaini'];
            }

        } 

        
        if($params['anio'] != '' && $params['anio'] != '0'){
            $sql.=" AND EXTRACT('year' FROM perva_fechaini ) = ?";
            $query[] = $params['anio'];
        }

 

        $sql.= " order by perva_fechaini";
         
         $rs =  $this->_CI->db->query($sql, $query)->result_array();
         
         return $rs;
         
    }
    
    
    public function view($id){
        
        
         $sql =  "SELECT *,
                          
                         perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni
  
                 FROM rh.persona_vacaciones vaca 
                 LEFT JOIN public.individuo perso ON vaca.pers_id = perso.indiv_id
                  where vaca.perva_id = ? LIMIT 1";
         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
        
        
    }
     
  
}
