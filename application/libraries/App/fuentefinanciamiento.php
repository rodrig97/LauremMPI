<?php

 
class fuentefinanciamiento extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => '',
                                    'code'  => '',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => ''
                            );
    
    protected $_SCHEMA = 'pip';
    protected $_TABLE = 'fuente_financ';
    protected $_PREF_TABLE= 'FFINANCI'; 
    
    
    public function __construct(){
           parent::__construct();
    }
     
    
    public function get_list()
    {
          return array();
    }
    
    
    public function get_by_usadas($ano_eje)
    {
         
         $sql  =" SELECT  distinct(t_s.fuente_financ), ff.nombre, ff.abrev   
                  FROM sag.tarea_saldo  t_s
                  LEFT JOIN pip.fuente_financ ff ON t_s.ano_eje = ff.ano_eje AND t_s.fuente_financ = ff.fuente_financ 
                  WHERE t_s.ano_eje = ? ";

        $rs = $this->_CI->db->query($sql,array($ano_eje))->result_array();
        
        return $rs;
    }

    
    public function get_ff_tr($params = array())
    {

         $sql = "SELECT  
                        (datos.fuente_financ || '-' || datos.tipo_recurso) as codigo,

                        (CASE WHEN datos.tipo_recurso = '0' THEN
                       ff.nombre    
                        ELSE
                       tr.nombre
                        END) as nombre     

                FROM (

                    SELECT distinct(t_s.fuente_financ), t_s.tipo_recurso, t_s.ano_eje    
                    FROM sag.tarea_saldo t_s 
                    WHERE ano_eje = ?  
                    ORDER BY fuente_financ, tipo_recurso 

                ) as datos 
                LEFT JOIN pip.fuente_financ ff ON datos.fuente_financ = ff.fuente_financ AND datos.ano_eje = ff.ano_eje 
                LEFT JOIN pip.tipo_recurso tr ON tr.fuente_financ = datos.fuente_financ AND tr.tipo_recurso = datos.tipo_recurso AND datos.ano_eje = tr.ano_eje 

                ";

        $rs = $this->_CI->db->query($sql, array($params['anio_eje']))->result_array();

        return $rs;

    }
    
    public function get_by_tarea($cod_tarea = 0, $ano_eje)
    {
        
        $sql  =" 
                SELECT  distinct(t_s.fuente_financ,t_s.tipo_recurso ) as rela_id, 
                            t_s.fuente_financ as fuente_id,  
                            ff.nombre as fuente_nombre, 
                            t_s.tipo_recurso as recurso_id, 
                            t_r.nombre as recurso_nombre   
                    FROM sag.tarea_saldo  t_s
                    LEFT JOIN pip.fuente_financ ff ON t_s.ano_eje = ff.ano_eje AND t_s.fuente_financ = ff.fuente_financ 
                    LEFT JOIN pip.tipo_recurso t_r ON t_s.ano_eje = t_r.ano_eje AND t_s.tipo_recurso = t_r.tipo_recurso AND t_s.fuente_financ = t_r.fuente_financ 
                    where t_s.ano_eje = ? AND t_s.tarea_id = ?
                    order by  t_s.fuente_financ, t_s.tipo_recurso 

         ";

        $rs = $this->_CI->db->query($sql,array($ano_eje,$cod_tarea))->result_array();
        
        return $rs;
                 
    }

    public function get_tr_by_ff_in_tarea($cod_tarea = 0, $ano_eje)
    {
        
        $sql  =" 
                SELECT  distinct(t_s.fuente_financ,t_r.tipo_recurso ) as rela_id, 
                            t_s.fuente_financ as fuente_id,  
                            ff.nombre as fuente_nombre, 
                            t_r.tipo_recurso as recurso_id, 
                            t_r.nombre as recurso_nombre   
                    FROM sag.tarea_saldo  t_s
                    LEFT JOIN pip.fuente_financ ff ON t_s.ano_eje = ff.ano_eje AND t_s.fuente_financ = ff.fuente_financ 
                    LEFT JOIN pip.tipo_recurso t_r ON t_s.ano_eje = t_r.ano_eje AND t_s.fuente_financ = t_r.fuente_financ 
                    where t_s.ano_eje = ? AND t_s.tarea_id = ?
                    order by  t_s.fuente_financ, t_r.tipo_recurso 

         ";

        $rs = $this->_CI->db->query($sql,array($ano_eje,$cod_tarea))->result_array();
        
        return $rs;
                 
    }

    public function get_all($ano_eje)
    {   

        $sql  ="  SELECT ff.fuente_financ as fuente_id,  
                           ff.nombre as fuente_nombre, 
                           tr.tipo_recurso as recurso_id, 
                           tr.nombre as recurso_nombre ,

                           (ff.fuente_financ || '-' || tr.tipo_recurso) as codigo,

                            (CASE WHEN tr.tipo_recurso = '0' THEN
                                   ff.nombre    
                            ELSE
                                 tr.nombre
                            END) as nombre     
                           
                    FROM pip.tipo_recurso  tr
                    LEFT JOIN pip.fuente_financ ff ON tr.fuente_financ = ff.fuente_financ AND tr.ano_eje = ff.ano_eje 
                    WHERE tr.ano_eje = ? AND ff.fuente_financ is not null AND tr.activo = 1
                    ORDER BY fuente_id, recurso_id
         ";

        $rs = $this->_CI->db->query($sql,array($ano_eje))->result_array();
        
        return $rs;

    }
 
    
}


?>
