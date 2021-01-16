<?php


Class sisgedocs{
    
    protected $_CI;
    protected $DB;
     
    public function __construct(){
        
       $this->_CI=& get_instance();  
        
 
       
       $this->DB = $this->_CI->load->database('sisgedo',true);
        
    }
    

    public function buscar($id,$t = 'codigo'){
        
         
        if($t == 'codigo'){
            
            $sql ="
                 select tip.texp_descripcion as tipo_doc,(exp.expe_numero_doc || ''|| exp.expe_siglas_doc ) as expe_codigo, exp.expe_id, expe_firma, expe_fecha_doc, expe_asunto as asunto  from public.expediente exp 
                 LEFT JOIN  public.tipo_expediente tip ON tip.texp_id = exp.texp_id 
                 where  (exp.expe_numero_doc || ''|| exp.expe_siglas_doc )   =  ?

             ";
            
        }
        else{
            
              $sql ="

                     select tip.texp_descripcion as tipo_doc,(exp.expe_numero_doc || ''|| exp.expe_siglas_doc ) as expe_codigo, exp.expe_id, expe_firma, expe_fecha_doc, expe_asunto as asunto  from public.expediente exp 
                     LEFT JOIN  public.tipo_expediente tip ON tip.texp_id = exp.texp_id 
                     where   exp.expe_id = ?

                 ";
            
        }
       
        // '159-2012/OCI/MPI'
        
        $rs = $this->DB->query($sql,array($id))->result_array();
        
        if(sizeof($rs) == 0 ) $rs = array(array());
    //s    var_dump($rs);

        return $rs[0];
    }
    
    
    
}