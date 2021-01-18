<?php

 
class partida extends Table{
    
    
    public $_TABLE = 'pip.especifica_det';
    
    public function __construct(){
           parent::__construct();
    }
    
    
    public function listar($tipo = null, $ano_eje = '', $grupos = array())
    {
        $sql = "SELECT (tipo_transaccion ||'.'|| generica || '.' || subgenerica || '.' || subgenerica_det || '.' || especifica || '.' || especifica_det ) as codigo, ano_eje,id_clasificador, descripcion
                FROM   pip.especifica_det 
                WHERE  ano_eje = ? 
               ";
        
        $params = array($ano_eje);
        
        $grupos_list = array('tipo_transaccion', 'generica', 'subgenerica', 'subgenerica_det', 'especifica', 'especifica_det');
        
        if(sizeof($grupos) > 0 )
        {
            foreach($grupos as $g => $v)
            {
                if(in_array($g, $grupos_list))
                {
                     $sql.=" AND ".$g." = '".$v."'";
                }
            }
            
        }
        
        
        $sql." ORDER By codigo ";
    
        return $this->_CI->db->query($sql, $params)->result_array();
        
    }
    
    
    public function get_memory()
    {
     
        $rs = $this->listar();
          
        $nodes = array();
    

        foreach( $rs as $k => $reg ){
               
             
              $reg_id++;
              $node = array();     
              $node['id'] = trim($reg['id_clasificador']);
              $node['codigo'] = $reg['codigo'];
              $node['partida'] =  $reg['codigo'];
              $node['nombre'] =  $reg['descripcion'];
              $node['name'] = trim($reg['codigo']).'- '.trim($reg['descripcion']);
              $node['key'] =trim($reg['id_clasificador']);
              $node['tipo'] = 'partida';
              $node['tooltip'] =  '<b>Partida:</b> '.trim($reg['codigo']).'- '.trim($reg['descripcion']);
            
              $nodes[] = $node;
                
          }
          
          return json_encode($nodes);
          
    }


    public function get_by_tarea( $params = array() )
    {
 
        $sql = "SELECT  distinct
                       ed.id_clasificador,
                       (tipo_transaccion ||'.'|| generica || '.' || subgenerica || '.' || subgenerica_det || '.' || especifica || '.' || especifica_det ) as codigo,
                        ed.descripcion as nombre
                  
                FROM (SELECT distinct(id_clasificador) as clasi 
                      FROM sag.tarea_saldo 
                      WHERE tarea_id = ?  ) as by_tarea 
               
                INNER JOIN pip.especifica_det ed ON by_tarea.clasi = ed.id_clasificador AND ed.ano_eje = ?
                ORDER BY codigo   
               ";

        $rs = $this->_CI->db->query($sql, array($params['tarea'], $params['anio_eje']))->result_array();

        return $rs;
    }
    
}


?>
