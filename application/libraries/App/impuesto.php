<?php

class impuesto
{
    
    private $_CI;
 
    public function __construct()
    {

        $this->_CI=& get_instance(); 
        $this->_CI->load->database(); 
     
    }

    public function busqueda_trabajadores_quinta($params, $count = false )
    {

    	$sql = ' SELECT  ';

    	if($count)
    	{
    	    $sql.=' count(*) as total ';
    	}
    	else
    	{
    	
    	   $sql.= '    indiv.indiv_key,
    	   			   indiv.indiv_appaterno,
    				   indiv.indiv_apmaterno,
    				   indiv.indiv_nombres,
    				   indiv.indiv_dni,
    				   persla.*,
    				   plati.plati_nombre,
    				   montos.* ';
    	}

    	$sql.= "    
    				 

    			 FROM public.individuo indiv 
    			 INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1   
    		     INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
    			 LEFT JOIN ( 

    			 	SELECT plaemp.indiv_id,  
    			 		   SUM( CASE WHEN concs.gvc_id = ? THEN
 								 plaec.plaec_value 
    			 		  	ELSE 
		   			 		  		 0
    			 		  	END) as rem_base,

    			 		   SUM( CASE WHEN concs.gvc_id = ? THEN
 								 plaec.plaec_value 
    			 		  	ELSE 
		   			 		  		 0
    			 		  	END) as rem_descuento


    			 	FROM planillas.planillas pla 
    			 	INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id  AND plamo.plamo_estado = 1
    			 	INNER JOIN planillas.planilla_empleados plaemp ON pla.pla_id = plaemp.pla_id AND plaemp.plaemp_estado = 1
    			 	INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec.plaec_calculado = 1  
    			 	INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id    
 
    			 	WHERE pla.pla_anio = ?  AND plaec.conc_afecto = 0
    			 	GROUP BY plaemp.indiv_id 

    			 ) as montos ON indiv.indiv_id = montos.indiv_id
 				 
 			  ";


 			  if($count == FALSE)
 			  {
 			  
 			    $sql.= "ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres ";  

 			    if($params['limit']>0) $sql.= "  LIMIT ".$params['limit'];
 			      
 			    if($params['offset'] > 0 && $params['limit'] > 0 ){
 			        
 			       $sql.= " OFFSET ".$params['offset']; 
 			       
 			    }
 			  
 			  }


 		    $rs = $this->_CI->db->query($sql, array(QUINTA_BASE, QUINTA_DESCUENTO, $params['anio'] ))->result_array();

 		    if($count)
 		    {
 		    	return $rs[0]['total']; 
 		    }
 		    else
 		    {
 		    	return $rs;
 		    }

    }
 	

 	public function getResumenQuinta()
 	{

		$sql = "   SELECT mes_id, 
					tiporegistro_id,
					reqm_valor1 
				 
				 FROM  planillas.quintacategoria_movimiento
				 WHERE reqm_estado = 1
				      AND indiv_id = 28680 

				    ORDER BY mes_id, tiporegistro_id  ";


		$rs = $this->_CI->db->query($sql, array() )->result_array();

		$data = array();

		$meses = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

		$registros = array(1,2,3,4,5,6,7,8);

		$mes = '';
		foreach ($rs as $reg)
		{
			 	
			if($mes !=  $reg['mes_id'])
			{
				$mes_key = $reg['mes_id']; 
				$mes = $reg['mes_id'];

				$data[$mes_key] = array( $reg['tiporegistro_id'] => $reg['reqm_valor1'] );
			}
			else
			{

				$data[$mes_key][$reg['tiporegistro_id']] = $reg['reqm_valor1']; 
			}

		}
 		
 		return $data;

 	}

 	public function get_resumen_por_trabajador()
 	{

 		$sql = " SELECT * FROM   "; 


 	}

    public function sunat_planillas($params = array(), $modo = '' )
    {

        $planillas_in = implode( ',', $params['planillas']);

        if($modo == 'seleccionar')
        {
            $modo = '1';
        }
        else if($modo == 'deseleccionar')
        {
            $modo = '0';
        }    
        else
        {
             return false;
        }
        
        $sql = " UPDATE planillas.planillas SET sunat_seleccionada = ".$modo." 
                 WHERE pla_id IN (".$planillas_in.")
               ";

        $rs = $this->_CI->db->query($sql, array());

        return ($rs) ? true : false;
    }   

    

    public function ingresos_mensuales($params = array() )
    {
 
         $sql = " SELECT ";


          if($params['modo'] == '')
          {

             $sql.=" (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres) as trabajador,  
                          ('_'|| ind.indiv_dni) as dni, asistencia.* ";
          }
          else
          {
              $sql.=" asistencia.*, persla.persla_fechaini, persla.persla_fechafin ";
          }
    
        $sql.="    FROM (

                       SELECT * FROM crosstab(' 

                          SELECT  plaemp.indiv_id, pla.pla_mes, SUM(plaec_value) as total 
                          FROM planillas.planilla_empleados plaemp 
                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 
                          INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT."
                          INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_tipo = 1 AND plaec.conc_afecto = 1 
                           
                          WHERE plaec.plaec_value > 0 
                          GROUP BY plaemp.indiv_id, pla.pla_mes 
                          ORDER BY plaemp.indiv_id, pla.pla_mes 

                       ', 'SELECT mes_eje FROM public.mes WHERE  mes_id > 0 ' )as ct(
                         
                          \"indiv_id\" numeric, 
                          \"enero\" numeric,
                          \"febrero\" numeric,
                          \"marzo\" numeric,
                          \"abril\" numeric,
                          \"mayo\" numeric,
                          \"junio\" numeric,
                          \"julio\" numeric,
                          \"agosto\" numeric,
                          \"septiembre\" numeric,
                          \"octubre\" numeric, 
                          \"noviembre\" numeric,
                          \"diciembre\" numeric  
                         
                       )

               ) as asistencia
               LEFT JOIN \"public\".individuo ind ON asistencia.indiv_id = ind.indiv_id  
               LEFT JOIN rh.persona_situlaboral persla ON ind.indiv_id = persla.pers_id AND persla_estado = 1 AND persla.persla_ultimo = 1
               ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres

           ";

        $_MESES = array( 
                            '01' => 'ENERO',
                            '02' => 'FEBRERO',
                            '03' => 'MARZO',
                            '04' => 'ABRIL',
                            '05' => 'MAYO',
                            '06' => 'JUNIO',
                            '07' => 'JULIO',
                            '08' => 'AGOSTO',
                            '09' => 'SEPTIEMBRE',
                            '10' => 'OCTUBRE',
                            '11' => 'NOVIEMBRE',
                            '12' => 'DICIEMBRE'
                        );
    

        $meses= array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre','noviembre', 'diciembre');
    

        if( $params['modo'] == 'altas' )
        {

            $sql_ = $sql;
    
            $sql = " SELECT data.indiv_id FROM (".$sql_.") as data 

                     WHERE (data.noviembre = 0 OR data.noviembre is null) AND data.diciembre > 0 AND data.persla_fechaini > '2013-12-01'
                  ";

        }


        if( $params['modo'] == 'bajas' )
        {

            $sql_ = $sql;
        
            $sql = " SELECT data.indiv_id FROM (".$sql_.") as data 

                     WHERE data.noviembre > 0 AND (data.diciembre = 0 OR  data.diciembre is null)
                  ";

        }

    
           $rs = $this->_CI->db->query($sql, array())->result_array();
          
    
        return $rs;   

    }


    public function analisis_pdt_trabajador( $params = array() )
    {

        $sql_core = "  SELECT plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto  
                       FROM planillas.planilla_empleados plaemp
                       INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = ? AND pla.pla_mes = ? 
                       INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ?
                       INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec_calculado = 1 AND plaec_value > 0
                      
                       WHERE plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?

                       GROUP BY plaemp.indiv_id, plaec.conc_id 
                     ";
 

        $sql = " SELECT  cosu.cosu_codigo, 
                         cosu.cosu_descripcion, 
                         conc.conc_tipo, 
                         data.conc_id, 
                         conc.conc_nombre, 
                         data.monto     
               
                 FROM (".$sql_core.") as data  
                 INNER JOIN planillas.conceptos conc ON data.conc_id = conc.conc_id   
                 INNER JOIN planillas.conceptos_sunat cosu ON conc.cosu_id = cosu.cosu_id 
                 ORDER BY conc.conc_tipo, conc.cosu_id, conc.conc_id, conc.conc_nombre   
               "; 

      
        $rs = $this->_CI->db->query($sql, array($params['anio'], $params['mes'], ESTADO_PLANILLA_CERRADA, $params['indiv_id'] ))->result_array();

        return $rs;
    }

}