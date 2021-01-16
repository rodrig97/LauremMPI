<?php
 

class concepto_metodos
{
    
    private $_CI;
 
    public function __construct()
    {

        $this->_CI=& get_instance(); 
        $this->_CI->load->database(); 
     
    }
    
    public function renta_quinta_categoria($params)
    {    

       $this->_CI->load->library(array('App/quintamovimiento'));

       $indice_meses = array('01' =>  1,
                             '02' =>  2,
                             '03' =>  3,
                             '04' =>  4,
                             '05' =>  5,
                             '06' =>  6,
                             '07' =>  7,
                             '08' =>  8,
                             '09' =>  9,
                             '10' =>  10,
                             '11' =>  11,
                             '12' =>  12
                             ); 


      $factor  = array(  '01' =>  array(12, array()),
                         '02' =>  array(12, array()),
                         '03' =>  array(12, array()),
                         '04' =>  array(9,  array('01', '02', '03')),
                         '05' =>  array(8,  array('01', '02', '03', '04')),
                         '06' =>  array(8,  array('01', '02', '03', '04')),
                         '07' =>  array(8,  array('01', '02', '03', '04')),
                         '08' =>  array(5,  array('01', '02', '03', '04', '05', '06', '07')),
                         '09' =>  array(4,  array('01', '02', '03', '04', '05', '06', '07','08')),
                         '10' =>  array(4,  array('01', '02', '03', '04', '05', '06', '07','08')),
                         '11' =>  array(4,  array('01', '02', '03', '04', '05', '06', '07','08')),
                         '12' =>  array(1,  array('01', '02', '03', '04'))
                      ); 
 

       $sql =" SELECT  * FROM planillas.quintacategoria_configuracion  WHERE plati_id = ?  LIMIT 1";
       list($valores) =  $this->_CI->db->query($sql, array($params['plati_id']))->result_array();

       $id_rempagada = $valores['concid_rempagada'];
       $id_proyectar = $valores['concid_base_proyectar'];
       $id_proyeccion_gratificacion = $valores['concid_proyeccion_gratificacion'];
       $id_grati1 = $valores['concid_gratificacion1'];
       $id_grati2 = $valores['concid_gratificacion2'];
       $id_otrosingresos = $valores['concid_otrosingresos'];
       
        $sql = " SELECT plaec_value as base_planilla 
                FROM planillas.planilla_empleado_concepto 
                WHERE plaemp_id = ?  AND conc_id = ? LIMIT 1 ";
        list($rs) = $this->_CI->db->query($sql, array($params['plaemp_id'], $id_rempagada) )->result_array();
        $base_planilla = $rs['base_planilla'];

        // Acumular lo del mes

        $sql = "  SELECT  plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto_basemes 
                         FROM planillas.planilla_empleado_concepto plaec 
                         INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id  AND plaemp_estado = 1
                         INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ?
                         LEFT JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1
                         INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id
                         WHERE  (plamo.plaes_id = ? OR (  pla.pla_id = ? AND  plaemp.plaemp_id < ?  AND plaec.plaec_calculado = 1) ) AND plaemp.indiv_id = ? AND conc.gvc_id IN (?) AND plaec.plaec_estado = 1 AND pla.pla_mes IN (?)
                         GROUP BY plaemp.indiv_id, plaec.conc_id 
                "; 

        list($rs) = $this->_CI->db->query($sql, array( '2014', ESTADO_PLANILLA_CERRADA, $params['pla_id'], $params['plaemp_id'], $params['indiv_id'], QUINTA_BASE , $params['mes'] ) )->result_array();
        $base_mes = ( $rs['monto_basemes'] == '' ) ? 0 : $rs['monto_basemes'];

        var_dump(array($params['plaemp_id'], $base_mes) );
        $base_mes+= $base_planilla;

        var_dump(array($params['plaemp_id'], $base_mes) );

        //echo 'Base mes : '.$base_mes." </br> ";


        if($id_proyectar != '0')
        {
          
          $sql = " SELECT plaec_value as base_proyectar 
                   FROM planillas.planilla_empleado_concepto 
                   WHERE plaemp_id = ?  AND conc_id = ? LIMIT 1 ";
           list($rs) = $this->_CI->db->query($sql, array($params['plaemp_id'], $id_proyectar) )->result_array();
           $proyectar = $rs['base_proyectar'];
        }
        else
        {
          $proyectar = '';
        }     

         if($proyectar == '' || $proyectar == 0)
         {
             $proyectar = $base_mes;
         }

         //echo 'Proyectar : '.$proyectar." </br> ";

          

         if($id_proyeccion_gratificacion != '' || $id_proyeccion_gratificacion != '0')
         {
            $sql = " SELECT plaec_value as proyeccion_grati 
                     FROM planillas.planilla_empleado_concepto 
                     WHERE plaemp_id = ?  AND conc_id = ? LIMIT 1 ";
             list($rs) = $this->_CI->db->query($sql, array($params['plaemp_id'], $id_proyeccion_gratificacion) )->result_array();
             $grati1 = $rs['proyeccion_grati'];
             $grati2 = $grati1;

         }
         else
         {
             $grati1 = 0;
             $grati2 = 0;
         }
 
         $sql_grati = "  SELECT  plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto 
                         FROM planillas.planilla_empleado_concepto plaec 
                         INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id  AND plaemp_estado = 1
                         INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ?
                         INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ? 
                         INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id
                         WHERE plaemp.indiv_id = ? AND plaec.conc_id = ? AND plaec.plaec_estado = 1 AND pla.pla_mes IN (?)
                         GROUP BY plaemp.indiv_id, plaec.conc_id 
                         ";
 
         if($indice_meses[$params['mes']] >= 7)
         {
              list($rs) = $this->_CI->db->query($sql_grati, array( '2014', ESTADO_PLANILLA_CERRADA, $params['indiv_id'], $id_grati1, '07' ) )->result_array();
              $grati1 = ($rs['monto'] =='' ? 0 : $rs['monto'] ); 
        
         }


         if($indice_meses[$params['mes']] == 12)
         {
              list($rs) = $this->_CI->db->query($sql_grati, array('2014', ESTADO_PLANILLA_CERRADA, $params['indiv_id'], $id_grati2, '12' ) )->result_array();
              $grati2 =  ($rs['monto'] =='' ? 0 : $rs['monto'] );
             
         }

         //echo 'Gratificaciones : '.$grati1." ".$grati2." </br> ";
 

         if( $id_otrosingresos != '0' && $id_otrosingresos != '')
         {

           $sql = " SELECT plaec_value as otros_ingresos 
                    FROM planillas.planilla_empleado_concepto 
                    WHERE plaemp_id = ?  AND conc_id = ? LIMIT 1 ";
            list($rs) = $this->_CI->db->query($sql, array($params['plaemp_id'], $id_otrosingresos) )->result_array();
           $otros_ingresos = $rs['otros_ingresos'];
          
         }
         else
         {
            $otros_ingresos = 0;
         }

         //echo 'Otros ingresos planilla : '.$otros_ingresos." </br> ";
         $sql = " SELECT  plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto_otros_ingresos 
                  FROM planillas.planilla_empleado_concepto plaec 
                  INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id  AND plaemp_estado = 1
                  INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ?
                  LEFT JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1
                  INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id
                  WHERE  (plamo.plaes_id = ? OR (  pla.pla_id = ? AND  plaemp.plaemp_id < ?  AND plaec.plaec_calculado = 1) ) AND plaemp.indiv_id = ? AND conc.gvc_id IN (?) AND plaec.plaec_estado = 1 AND pla.pla_mes IN (?)
                  GROUP BY plaemp.indiv_id, plaec.conc_id 
                 "; 

         list($rs) = $this->_CI->db->query($sql, array( '2014', ESTADO_PLANILLA_CERRADA, $params['pla_id'], $params['plaemp_id'], 
                                                        $params['indiv_id'], QUINTA_OTROS_INGRESOS , $params['mes'] ) )->result_array();

        /* $sql = "  SELECT  plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto_otros_ingresos 
                          FROM planillas.planilla_empleado_concepto plaec 
                          INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id  AND plaemp_estado = 1
                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ?
                          INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ? 
                          INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id
                          WHERE plaemp.indiv_id = ? AND conc.gvc_id IN (?) AND plaec.plaec_estado = 1 AND pla.pla_mes IN (?) AND ( pla.pla_id = ? AND plaemp.plaemp_id < ?) 
                          GROUP BY plaemp.indiv_id, plaec.conc_id 
                 "; 

         list($rs) = $this->_CI->db->query($sql, array( '2014', ESTADO_PLANILLA_CERRADA, $params['indiv_id'], QUINTA_OTROS_INGRESOS , $params['mes'], $params['pla_id'], $params['plaemp_id'] ) )->result_array();
        */
         $otros_ingresos_mes = ( $rs['monto_otros_ingresos'] == '' ) ? 0 : $rs['monto_otros_ingresos'];
         $otros_ingresos_mes+= $otros_ingresos;

          //echo 'Otros ingresos mes : '.$otros_ingresos_mes." </br> ";

        // Obtener mes 
        // determinar el numero de meses x los cuales se va a proyectar, depende del periodo de contrato
        $cantidad_mes  = 12 - $indice_meses[$params['mes']]; 
        $proyectado    = $proyectar * $cantidad_mes; 
        $gratificacion = $grati1 + $grati2;

        $meses_anteriores = array();

        foreach ($indice_meses as $mes => $indice)
        {

             if($mes == $params['mes'] )
             {
                break;
             }
             $meses_anteriores[] = "'".$mes."'";
        }

        if(sizeof($meses_anteriores) > 0 )
        {

            $sql_in_meses = implode(',', $meses_anteriores);

            $sql = " SELECT  plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto 
                     FROM planillas.planilla_empleado_concepto plaec 
                     INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id  AND plaemp_estado = 1
                     INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio =? 
                     INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ? 
                     INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id
                     WHERE plaemp.indiv_id = ? AND conc.gvc_id IN (".QUINTA_TOTAL_INGRESOS.") AND plaec.plaec_estado = 1 AND pla.pla_mes IN (".$sql_in_meses.") 
                     GROUP BY plaemp.indiv_id, plaec.conc_id 
                     ";

            list($rs) = $this->_CI->db->query($sql, array( '2014', ESTADO_PLANILLA_CERRADA, $params['indiv_id'] ) )->result_array();
            $remuneraciones_anteriores = $rs['monto']; 

        }
        else
        {

            $remuneraciones_anteriores = 0;

        }
      
        //echo 'Remuneraciones anteriores : '.$remuneraciones_anteriores." </br> ";


         var_dump(array( $base_mes , $proyectado, $gratificacion, $remuneraciones_anteriores));

        $renta_neta_anual = $base_mes + $proyectado + $gratificacion + $remuneraciones_anteriores;

        $maximo_tope = MULTIPLE_UIT_QUINTA * MONTO_UIT;

        $renta_neta_global = $renta_neta_anual - $maximo_tope;

       // var_dump( $proyectado, $renta_neta_global);
 
        if($renta_neta_global <= 0 )
        {
            return 0;
        }


        if($renta_neta_global <= (27 * MONTO_UIT ) )
        {
            $tasa = 0.15;
        }
        else
        {
            $tasa = 0.21;
        }

        $impuesto_ala_renta = $renta_neta_global * $tasa;

        $impuesto_anual = $impuesto_ala_renta;



        $meses_anteriores = $factor[$params['mes']][1];
 
       if(sizeof($meses_anteriores) > 0 )
       {

          $sql_in_meses = implode("','", $meses_anteriores);

          $sql = " SELECT  plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto 
                   FROM planillas.planilla_empleado_concepto plaec 
                   INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id  AND plaemp_estado = 1
                   INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ? 
                   INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ? 
                   INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id
                   WHERE plaemp.indiv_id = ? AND conc.gvc_id IN (".QUINTA_DESCUENTO.") AND plaec.plaec_estado = 1 AND pla.pla_mes IN ('".$sql_in_meses."') 
                   GROUP BY plaemp.indiv_id, plaec.conc_id
                   LIMIT 1
                   ";

          list($rs) = $this->_CI->db->query($sql, array( '2014', ESTADO_PLANILLA_CERRADA, $params['indiv_id'] ) )->result_array();
        
          $retenciones_anteriores = $rs['monto']; 
        
        }
        else
        {

          $retenciones_anteriores = 0;

        }
 
        $donaciones = 0; // X DETERMINAR
        $impuesto_a_pagar = $impuesto_anual - $retenciones_anteriores - $donaciones;

        $valor_tasa_mensual = $factor[$params['mes']][0];

        $impuesto_calculado_pre = $impuesto_a_pagar / $valor_tasa_mensual; 


        /* Procedimiento especifico */

        if($otros_ingresos_mes > 0)
        {

            $total_anual_2 = $renta_neta_global + $otros_ingresos_mes;
            $t2 = $total_anual_2 * $tasa;
            $diff = $t2 - $impuesto_anual; 

            $impuesto_calculado = $impuesto_calculado_pre + $diff;
            
        }
        else
        {
             $impuesto_calculado = $impuesto_calculado_pre;
        } 


     //   //echo 'impuesto_calculado '.$impuesto_calculado." </br>";
        if( $impuesto_calculado < 0 )  $impuesto_calculado = 0;
        
        $sql = "  SELECT   plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto_descuento 
                         FROM planillas.planilla_empleado_concepto plaec 
                         INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id  AND plaemp_estado = 1
                         INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ?
                         LEFT JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1
                         INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id
                         WHERE  (plamo.plaes_id = ? OR (  pla.pla_id = ? AND  plaemp.plaemp_id < ?  AND plaec.plaec_calculado = 1) ) AND plaemp.indiv_id = ? AND conc.gvc_id IN (?) AND plaec.plaec_estado = 1 AND pla.pla_mes IN (?)
                         GROUP BY plaemp.indiv_id, plaec.conc_id 
                "; 

        list($rs) = $this->_CI->db->query($sql, array( '2014', ESTADO_PLANILLA_CERRADA, $params['pla_id'], $params['plaemp_id'], $params['indiv_id'], QUINTA_DESCUENTO , $params['mes'] ) )->result_array();


     /*   $sql = "  SELECT  plaemp.indiv_id, plaec.conc_id, SUM(plaec_value) as monto_descuento 
                         FROM planillas.planilla_empleado_concepto plaec 
                         INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id  AND plaemp_estado = 1
                         INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ?
                         INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ? 
                         INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id
                         WHERE plaemp.indiv_id = ? AND conc.gvc_id IN (?) AND plaec.plaec_estado = 1 AND pla.pla_mes IN (?) AND ( pla.pla_id != ?  OR ( pla.pla_id = ? AND plaemp.plaemp_id < ?)  )
                         GROUP BY plaemp.indiv_id, plaec.conc_id 
                "; 

        list($rs) = $this->_CI->db->query($sql, array( '2014', ESTADO_PLANILLA_CERRADA, $params['indiv_id'], QUINTA_DESCUENTO , $params['mes'],  $params['pla_id'], $params['pla_id'], $params['plaemp_id'] ) )->result_array();
*/ 

       $descuentos_mes = ( $rs['monto_descuento'] == '' ) ? 0 : $rs['monto_descuento']; 

       echo 'Descuentos del mes : '.$descuentos_mes.' plaemp: '.$params['plaemp_id'];

/*
        //echo ' Descuentos anteriores '.$descuentos_mes." </br>";
*/
        var_dump( array($renta_neta_anual, $renta_neta_global, $impuesto_anual, $impuesto_calculado, $retenciones_anteriores) );
 
        $total_retencion_planilla = $impuesto_calculado -  $descuentos_mes;

        $sql = " UPDATE planillas.planilla_empleado_concepto 
                 SET plaec_value = ?,
                     plaec_value_pre = ?,
                     plaec_calculado = 1  
                 WHERE plaec_id = ? 
               ";
        
        $this->_CI->db->query($sql, array( $total_retencion_planilla, $total_retencion_planilla, $params['plaec_id']));



        $sql = "UPDATE planillas.quintacategoria_movimiento qm
                SET reqm_estado = 0 
                FROM ( SELECT anio_eje, mes_id, indiv_id, plaemp_id, MAX(reqm_id)  
                       FROM planillas.quintacategoria_movimiento 
                       WHERE anio_eje = ? AND mes_id = ? AND indiv_id = ? AND  reqm_estado = 1 
                       GROUP BY anio_eje, mes_id, indiv_id,plaemp_id
                       LIMIT 1 
                     ) as d 
                WHERE qm.plaemp_id = d.plaemp_id 
               
               ";

        $this->_CI->db->query($sql, array( '2014', $params['mes'], $params['indiv_id'] ) );



        $data_registros = array(
                                'REMUNERACION_PROYECTADA'   => array('1', $proyectar ),
                                'REMUNERACION_BASE'         => array('2', $base_mes),
                                'GRATI1'                    => array('3', $grati1),
                                'GRATI2'                    => array('4', $grati2),
                                'REMUNERACION_ANTERIOR'     => array('5', $remuneraciones_anteriores),
                                'RETENCIONES_ANTERIORES'    => array('6', $retenciones_anteriores),
                                'OTROS_INGRESOS'            => array('7', $otros_ingresos_mes),
                                'DESCUENTO_APLICADO'        => array('8', $impuesto_calculado)
                              );


        foreach($data_registros as $key => $data)
        {

             $values = array( 
                               'indiv_id'           => $params['indiv_id'],
                               'mes_id'             => $params['mes'],
                               'tiporegistro_id'    => $data[0],
                               'reqm_valor1'        => $data[1],
                               'reqm_modoregistro'  => '1',
                               'pla_id'             => $params['pla_id'],
                               'plaemp_id'          => $params['plaemp_id'],
                               'anio_eje'           => '2014'
                            );

            $this->_CI->quintamovimiento->registrar($values);
        
        }
        /*
            INSERT INTO planillas.quinta_movimiento() VALUES()
       */
 
    }

    public function convertir_a_horas($params = array())
    { 
      
       $total = $params['dias'] * $params['hora_tasa'];
       return $total;
    }

}