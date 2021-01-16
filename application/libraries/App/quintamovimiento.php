<?php

class quintamovimiento extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'reqm_id',
                                    'code'  => 'reqm_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'reqm_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'quintacategoria_movimiento';
    protected $_PREF_TABLE= 'quintamovimiento'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }
 
  
    public function revertir_calculo_planilla($params)
    {
        // anio_eje, mes_id, indiv_id 

        $sql = "  SELECT anio_eje, mes_id, indiv_id 
                  FROM planillas.quintacategoria_movimiento qm 
                  WHERE qm.pla_id = ? AND reqm_estado = 1 
                                         
               ";

        $rs = $this->_CI->db->query($sql, array($params['pla_id'] ) )->result_array();

        foreach ($rs as $reg)
        {
            
            $sql = " UPDATE planillas.quintacategoria_movimiento qm 
                     SET reqm_estado = 1
                     FROM ( 
                                SELECT anio_eje, mes_id, indiv_id, plaemp_id, MAX(reqm_id)  
                                FROM planillas.quintacategoria_movimiento qm 
                                WHERE qm.pla_id != ? AND reqm_estado = 0 AND anio_eje = ? AND indiv_id = ? AND mes_id = ?
                                GROUP BY anio_eje, mes_id, indiv_id,plaemp_id
                             ) as d 
                      WHERE qm.plaemp_id = d.plaemp_id 
                    ";
            $this->_CI->db->query($sql, array($params['pla_id'], $reg['anio_eje'], $reg['indiv_id'], $reg['mes_id']  ));
                
        }

        $sql = " DELETE FROM planillas.quintacategoria_movimiento 
                 WHERE pla_id = ? 
               ";

        $this->_CI->db->query($sql, array($params['pla_id']) );

/*        $sql = " DELETE FROM planillas.quintacategoria_movimiento 
                 WHERE pla_id = ? 
               ";

        $this->_CI->db->query($sql, array($params['pla_id']) );


        $sql = " SELECT * FROM planillas.quintacategoria_movimiento WHERE  anio_eje = ? AND mes_id = ? AND indiv_id = ?   ";

        $sql = " UPDATE planillas.quintacategoria_movimiento 
                SET reqm_estado = 0 
                FROM ( SELECT plaemp_id FROM planillas.quintacategoria_movimiento WHERE anio_eje = ? AND mes_id = ? AND indiv_id = ?  ORDER BY reqm_id desc LIMIT 1 ) as d 
                WHERE plaemp_id = d.plaemp_id  ";

        $sql = " DELETE FROM planillas.quintacategoria_movimiento ";*/



    }

}