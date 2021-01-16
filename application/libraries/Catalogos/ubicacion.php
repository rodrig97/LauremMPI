<?php

 
class ubicacion extends Table{
     
  
    protected $_FIELDS=array(   
                                    'id'    => 'afp_id',
                                    'code'  => 'afp_key',
                                    'name'  => 'afp_nombre',
                                    'descripcion' => '',
                                    'state' => 'afp_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'afp';
    protected $_PREF_TABLE= 'AFPMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
 
    
    public function get_departamentos(){
         
        $sql = "Select * from public.departamento where dpto_id != '00' AND dpto_estado = '1' order by dpto_nombre";
        return  $this->_CI->db->query($sql)->result_array();
    }
    
    public function get_provincias($depar = ''){
         
        if($depar != ''){
            
        } 
        
        $sql = "Select * from public.provincia where dpto_id = ? AND prvn_estado = '1' order by prvn_nombre";
        return  $this->_CI->db->query($sql, array($depar))->result_array();
        
    }
    
    public function get_distritos($depar = '',$prov = ''){
        
         if($prov != ''){
            
        } 
        
        $sql = "Select * from public.distrito where dpto_id = ? AND prvn_id = ? AND dstt_estado = '1' order by dstt_nombre";
        return  $this->_CI->db->query($sql, array($depar,$prov))->result_array();
        
    }
    

    public function get_ciudades(){

        $sql = " 
                  select   d.dstt_id as distrito_id, d.dstt_nombre as distrito,
                           d.prvn_id as provincia_id, p.prvn_nombre as provincia, 
                           d.dpto_id as departamento_id, de.dpto_nombre as departamento


                     from public.distrito  d 
                        left join public.provincia p ON d.prvn_id = p.prvn_id AND d.dpto_id = p.dpto_id
                        left join public.departamento de ON de.dpto_id = p.dpto_id

                   order by de.dpto_nombre, p.prvn_nombre, d.dstt_nombre     
 
               ";

        return $this->_CI->db->query($sql)->result_array();       
    }
    
}
