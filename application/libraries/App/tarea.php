<?php

class Tarea extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => 'tarea_id',
                                    'code'  => 'tarea_key',
                                    'name'  => 'tarea_nombre',
                                    'descripcion' => '',
                                    'state' => 'tarea_estado'
                            );
    
    protected $_TABLE = 'tarea';
    protected $_PREF_TABLE= 'USERSYS'; 
    
    protected $_SCHEMA = 'sag';
    
    public function __construct(){
        
        parent::__construct();
       $this->_CI->load->library(array('users/sesiones'));
        
    }   

/*
    public function registrar( $params= array())
    {

        $sql =" INSERT INTO sag.tarea(ano_eje, sec_ejec, sec_func, tarea_nro, tarea_nombre ) VALUES( ?,) ";

        $rs =  $this->_CI->db->query($sql, array());

        return ($rs) ? true : false;
    }
    */
    
    
    public function get_list($params = array()){
       
        $values = array();

        $sql = " SELECT tarea.*, 
                        tarea_id as cod_tarea, 
                        tarea_nombre as nombre, 
                        tarea_nro as tarea ,
                        CASE WHEN cast(tarea.ano_eje as int) >= 2021 
							THEN meta.sec_func 
							ELSE ( meta.sec_func || ' - ' || tarea.tarea_nro ) 
						END as tarea_codigo
                 FROM sag.tarea 
                 LEFT JOIN pip.meta ON tarea.sec_func = meta.sec_func AND tarea.ano_eje = meta.ano_eje 
                 WHERE tarea_estado = '1'   
              ";
         
        if($params['ano_eje'] != '')
        {
            $sql.=' AND tarea.ano_eje = ? ';
            $values[] = trim($params['ano_eje']);
        }

        if($params['nombre'] != '')
        {
            $sql.=" AND ( tarea.tarea_nombre like ? OR  ( meta.sec_func || ' - ' || tarea.tarea_nro ) like ?  ) ";
            $values[] = '%'.trim($params['nombre']).'%';
            $values[] = '%'.trim($params['nombre']).'%';
        }
      
        $sql.=' ORDER BY sec_func,tarea_nro, tarea_nombre';
 

        return $this->_CI->db->query($sql, $values )->result_array();
        
        
    }
     
    public function get_tareas($user){
         
        $sql = ' Select res.*,tar.nombre as tarea_nombre, tar.cod_tarea as tarea_cod  from public.v003_tarea res
                 left join sag.tarea tar ON res.cod_tarea = tar.cod_tarea   
                 where cod_usu1 = '.$user;
       
        return $this->_CI->db->query($sql, array($user) )->result_array();
        
        
    }
    
    public function saldos($tarea, $fuentes = array(), $partidas = array()){
         //2200
        
        
        /*
             CONSULTA MATRIZ
         * 
SELECT DISTINCT ON (ts.ano_eje, ts.sec_ejec, ts.codigo, ts.fuente_financ, t.sec_func, t.tarea, ts.tipo_recurso, ed.tipo_transaccion, ed.generica, ed.subgenerica, ed.subgenerica_det, ed.especifica, ed.especifica_det ) 
	            ts.codigo, ts.ano_eje,  ts.sec_ejec,  ts.cod_tarea, ts.fuente_financ, ts.tipo_recurso, ts.id_clasificador, ts.monto_pia, ts.monto_ingreso, ts.monto_egreso, ts.monto_ejec, ( ts.monto_pia +  ts.monto_ingreso -  ts.monto_egreso ) as saldo,

                    t.sec_func, t.tarea, t.nombre AS nombre_tarea, 
                    ff.nombre AS nombre_fuefin, 
                    tr.nombre AS nombre_tr,
		    ed.descripcion as partida_nombre, ed.tipo_transaccion, ed.generica, ed.subgenerica, ed.subgenerica_det, ed.especifica, ed.especifica_det,
		    (ed.generica ||'.'|| ed.subgenerica ||'.'|| ed.subgenerica_det ||'.'|| ed.especifica ||'.'||ed.especifica_det ) as partida_codigo

			
FROM sag.tarea_saldo AS ts INNER JOIN sag.tarea AS t  ON ts.cod_tarea = t.cod_tarea 
                           INNER JOIN pip.fuente_financ  AS ff ON ts.ano_eje || ts.fuente_financ = ff.ano_eje || ff.fuente_financ
                           INNER JOIN pip.tipo_recurso   AS tr ON ts.ano_eje || ts.fuente_financ || ts.tipo_recurso = tr.ano_eje || tr.fuente_financ || tr.tipo_recurso
			   INNER JOIN pip.especifica_det AS ed ON ts.ano_eje || ts.id_clasificador = ed.ano_eje || ed.id_clasificador 

where   ts.cod_tarea =  '90' 
AND 
 ts.id_clasificador in ( 'ACJNeNZ','ACJNeNa')

ORDER BY ts.fuente_financ, t.sec_func, t.tarea, ts.tipo_recurso, ed.tipo_transaccion, ed.generica, ed.subgenerica, ed.subgenerica_det, ed.especifica, ed.especifica_det 


	
        */
         
          $sql = " XSELECT DISTINCT ON (ts.ano_eje, ts.sec_ejec, ts.tareasald_id, ts.fuente_financ, t.sec_func, t.tarea_nro, ts.tipo_recurso, ed.tipo_transaccion, ed.generica, ed.subgenerica, ed.subgenerica_det, ed.especifica, ed.especifica_det ) 
	                                      ts.tareasald_id, ts.ano_eje,  ts.sec_ejec,  ts.tarea_id, ts.fuente_financ, ts.tipo_recurso, ts.id_clasificador, ( ts.monto_pia +  ts.monto_ingreso -  ts.monto_egreso ) as saldo,

                    t.sec_func, t.tarea_nro, t.tarea_nombre AS nombre_tarea, 
                    ff.nombre AS nombre_fuefin, 
                    tr.nombre AS nombre_tr,
		    ed.descripcion as partida_nombre, 
		    (ed.tipo_transaccion ||'.'|| ed.generica ||'.'|| ed.subgenerica ||'.'|| ed.subgenerica_det ||'.'|| ed.especifica ||'.'||ed.especifica_det ) as partida_codigo

			
FROM sag.tarea_saldo AS ts INNER JOIN sag.tarea AS t  ON ts.tarea_id = t.tarea_id 
                           INNER JOIN pip.fuente_financ  AS ff ON ts.ano_eje || ts.fuente_financ = ff.ano_eje || ff.fuente_financ
                           INNER JOIN pip.tipo_recurso   AS tr ON ts.ano_eje || ts.fuente_financ || ts.tipo_recurso = tr.ano_eje || tr.fuente_financ || tr.tipo_recurso
			   INNER JOIN pip.especifica_det AS ed ON ts.ano_eje || ts.id_clasificador = ed.ano_eje || ed.id_clasificador 

where   ts.tarea_id =  ? 
 
               ";
               
        
      //  $sql.= '  WHERE  ts.tarea_id = ?  ';
        
        $f = false; 
        $p =false;
        $at = ''; 
        
        if(sizeof($fuentes)>0){  // si = 0 , all fuentes
            $f = true;
            $at = ' AND ts.fuente_financ in ( ';    
            foreach($fuentes as $k =>  $fuente){

                $at.= (  $k>0  ) ?  ",'".$fuente."' " : "'".$fuente."'"; 

            }
             $at.= ') '; 
        }
          $sql.=$at;
        
          $at = '';
       if(sizeof($partidas)>0){  // si = 0 all partidas
            $p = true;
             
            $at =  ' AND ts.id_clasificador in ( ' ;    
            foreach($partidas as $k =>  $partida){

                $at.= (  $k>0 ) ?  ",'".$partida."' " : "'".$partida."'"; 

            }
             $at.= ') '; 
        }
          $sql.=$at;
          
          $sql.= ' ORDER BY ts.fuente_financ, t.sec_func, t.tarea_nro, ts.tipo_recurso, ed.tipo_transaccion, ed.generica, ed.subgenerica, ed.subgenerica_det, ed.especifica, ed.especifica_det ';
          
        // echo $sql;
          
          return $this->_CI->db->query($sql, array($tarea))->result_array();
        
    }
    


    public function get_saldoplanillas_por_tarea($tarea_id = '0')
    {

        $sql =" SELECT data.*,
                t.tarea_nombre,
                ff.nombre AS nombre_fuefin, 
                                    tr.nombre AS nombre_tr 
                 FROM (
                  SELECT 

                    ts.ano_eje, ts.tarea_id, t.sec_func, t.tarea_nro, ts.fuente_financ, ts.tipo_recurso, 
                    ed.tipo_transaccion, 
                    ed.generica,

                    SUM( ts.monto_pia +  ts.monto_ingreso -  ts.monto_egreso ) as saldo
                     
                   
                  FROM sag.tarea_saldo AS ts 
                  INNER JOIN sag.tarea AS t ON ts.tarea_id = t.tarea_id 

                  INNER JOIN pip.especifica_det AS ed ON ts.ano_eje || ts.id_clasificador = ed.ano_eje || ed.id_clasificador 
                  WHERE ts.tarea_id = ? AND ed.tipo_transaccion = '2' AND  ed.generica = '1'
                 
                  GROUP BY ts.ano_eje, ts.tarea_id, t.sec_func, t.tarea_nro,ts.fuente_financ, ts.tipo_recurso, 
                  ed.tipo_transaccion, ed.generica   
                 
                  ORDER BY ts.ano_eje, ts.tarea_id, t.sec_func, t.tarea_nro,ts.fuente_financ, ts.tipo_recurso, 
                  ed.tipo_transaccion, ed.generica   
                 

                ) as data 
                 
                  INNER JOIN pip.fuente_financ AS ff ON  data.ano_eje = ff.ano_eje AND data.fuente_financ = ff.fuente_financ   
                  INNER JOIN pip.tipo_recurso AS tr ON   data.ano_eje = tr.ano_eje AND data.fuente_financ = tr.fuente_financ AND data.tipo_recurso = tr.tipo_recurso 
                  INNER JOIN sag.tarea AS t ON data.tarea_id = t.tarea_id 
                  ORDER BY data.ano_eje, data.sec_func, data.tarea_nro,data.fuente_financ, data.tipo_recurso, 
                  data.tipo_transaccion, data.generica   
            ";


          $rs = $this->_CI->db->query($sql, array($tarea_id) )->result_array();

          return $rs;

    }

    public function get_info($tarea_id)
    {

      $sql = " SELECT *, ( tarea.sec_func || ' - ' || tarea.tarea_nro ) as tarea_codigo FROM sag.tarea where tarea_id = ? LIMIT 1";
      $rs = $this->_CI->db->query($sql, array($tarea_id))->result_array();

      return $rs[0];
    }


    public function tiene_afectacion()
    {
     //   $sql = "  SELECT * FROM planillas.planilla_empleado_concepto plaec WHERE "
    }
}

?>
