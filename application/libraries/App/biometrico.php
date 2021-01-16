<?php

class biometrico extends Table{
     

   protected $_FIELDS=array(   
                                    'id'          => 'biom_id',
                                    'code'        => 'biom_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'biom_estado'
                            );
    
   protected $_SCHEMA     = 'planillas';
   protected $_TABLE      = 'biometrico';
   protected $_PREF_TABLE = 'ARCHIVOSCS'; 
   

   private $_columna_dni = 0;
   private $_columna_fecha = 0;
   private $_columna_marcacion1 = 0;
   private $_columna_marcacion2 = 0;
   private $_columna_tardanza = 0;
 
   public function get_list()
   {

         $sql = " SELECT * FROM planillas.biometrico WHERE biom_estado = 1 ORDER BY biom_descripcion ";
         $rs = $this->_CI->db->query($sql, array())->result_array();
         return $rs;
   }

   public function get_file_info($id){

       $sql = " SELECT * FROM  planillas.archivos_importacion WHERE archim_id = ? LIMIT 1";
       $rs  = $this->_CI->db->query($sql, array($id))->result_array();

       return $rs[0];

   }  
   
   public function get_info_biometrico($biom_id , $param = 1)
   {

         $p = ($param == 1) ? 'biom_key' : 'biom_id'; 

         $sql = " SELECT * FROM planillas.biometrico WHERE  ".$p." = ? LIMIT 1 ";
         
         list($rs) = $this->_CI->db->query($sql, array($biom_id))->result_array();


         return $rs;

   }


   public function registrar_importacion()
   {

   }

   public  function explorar($id, $params = array())
   {
      
       // tambien debe verificarse si los trabajadores tienen registro de asistencia para el intervalo en otra hoja de asistencia

      require_once BASEPATH.'../application/libraries/excel_reader2.php';
      
      $info_upload     = $this->get_file_info($id);    
      $file            = $info_upload['archim_file'];
         
      $file_excel      = new Spreadsheet_Excel_Reader("docsmpi/importar/".$file); 
      $file_import_id  = $id;
          
      $hoja            =  0;
      $numero_filas    = $file_excel->rowcount($hoja);
      $numero_columnas = $file_excel->colcount($hoja);
 
      $biometrico_id = $params['biom_id'];


      $_styles = array(

                      '_alert' => ' style="background-color:#990000; color:white;" ' 

                     );
     
 
      $sql = " DELETE FROM planillas.biometrico_data WHERE biom_id = ? ";
      $this->_CI->db->query($sql, array($biometrico_id));
 

       for($i=1; $i<=$numero_columnas; $i++ )
       {

           $col =  strtoupper(trim($file_excel->val(1,$i) ));

           if($col == 'DNI')
           {
               $this->_columna_dni = $i;
          
           }
           else if( $col == 'FECHA')
           {
               $this->_columna_fecha = $i;
           }
           else if( $col == 'MARC-ENT')
           {
               $this->_columna_marcacion1 = $i;
           }
           else if( $col == 'MARC-SAL')
           {
               $this->_columna_marcacion2 = $i;
           }
           else if( $col == 'TARDANZA')
           {
               $this->_columna_tardanza = $i;
           }
           else
           {


           } 
    
       }


         if($this->_columna_dni == 0 || $this->_columna_fecha == 0)
         {


         }
         else
         {

            for($row = 2; $row <= $numero_filas;  $row++ )
            {


                  $dni =  trim($file_excel->val( $row, $this->_columna_dni )); 
                  $fecha =  trim($file_excel->val( $row, $this->_columna_fecha )); 

                  $marcacion_1 = trim($file_excel->val( $row, $this->_columna_marcacion1 ));
                  $marcacion_2 = trim($file_excel->val( $row, $this->_columna_marcacion2 ));
                  $min_tardanzas  = trim($file_excel->val( $row, $this->_columna_tardanza ));


                  if($dni == '' && $fecha == '')
                  {
                     break;
                  }

                  list($dia, $mes, $anio) = explode("/",$fecha);
   
                  $fecha_completa =  $anio.'-'.$mes.'-'.$dia;
                  
                  if($marcacion_1 != '' && $marcacion_2 != ''){

                    $sql = " INSERT INTO planillas.biometrico_data( biodata_dni, biom_id, 
                                                                    biodata_dia, biodata_marcacion1, biodata_marcacion2,
                                                                    biodata_tardanzas, archim_id  ) 
                             VALUES( ?,?,
                                     ?,?,?,
                                     ?,? ) ";

                    $this->_CI->db->query($sql, array($dni, $biometrico_id, 
                                                      $fecha_completa, $marcacion_1, $marcacion_2,
                                                      $min_tardanzas, $file_import_id) );
                    
                  }
                  else if($marcacion_1 != '' &&  $marcacion_2 == ''){

                    $sql = " INSERT INTO planillas.biometrico_data( biodata_dni, biom_id, 
                                                                    biodata_dia, biodata_marcacion1, 
                                                                    biodata_tardanzas, archim_id  ) 
                             VALUES( ?,?,
                                     ?,?,
                                     ?,? ) ";

                    $this->_CI->db->query($sql, array($dni, $biometrico_id, 
                                                      $fecha_completa, $marcacion_1, 
                                                      $min_tardanzas, $file_import_id) );
                  }
                  else if($marcacion_1 == '' &&  $marcacion_2 == ''){

                    $sql = " INSERT INTO planillas.biometrico_data( biodata_dni, biom_id, 
                                                                    biodata_dia,  
                                                                    biodata_tardanzas, archim_id  ) 
                             VALUES( ?,?,
                                     ?, 
                                     ?,? ) ";

                    $this->_CI->db->query($sql, array($dni, $biometrico_id, 
                                                      $fecha_completa,  
                                                      $min_tardanzas, $file_import_id) );
                  }


            }


         }


         $sql = " UPDATE planillas.biometrico_data bio 
                  SET indiv_id = indiv.indiv_id 
                  FROM public.individuo indiv 
                  WHERE bio.biodata_dni = indiv.indiv_dni AND  bio.biom_id = ?  ";

         $this->_CI->db->query($sql ,array($biometrico_id));
  

         $sql = "  SELECT distinct(bio.biodata_dni) as bio_dni, 
                          indiv_dni, 
                          min(biodata_dia) as desde, 
                          max(biodata_dia) as hasta, 
                          indiv_appaterno, indiv_apmaterno, indiv_nombres, plati.plati_nombre
                   
                   FROM planillas.biometrico_data bio
                   LEFT JOIN public.individuo indiv ON bio.indiv_id = indiv.indiv_id AND indiv.indiv_estado = 1
                   LEFT JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_ultimo = 1 AND persla_estado = 1  
                   LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                   LEFT JOIN planillas.individuo_biometrico ib ON bio.biom_id = ib.biom_id AND indiv.indiv_id = ib.indiv_id AND inbio_estado = 1
                   WHERE bio.biodata_estado = 1 AND bio.biom_id = ?

                   GROUP BY biodata_dni, indiv_dni, indiv_appaterno, indiv_apmaterno, indiv_nombres, plati.plati_nombre 

                   ORDER BY  indiv_appaterno, indiv_apmaterno, indiv_nombres
                "; 

         $rs =  $this->_CI->db->query($sql ,array($biometrico_id))->result_array();
          
        


         $html_excel = " <div style='width:600px; height: 400px; font-size:12px; border:1px solid #988888'>

                            <table id='table_resumen_xls' cellpadding='2' border='1' class='_tablepadding4' >  
                                                    
                              <tr class='tr_header_celeste'>
                                  <td> # </td>
                                  <td>
                                      DNI 
                                  </td>
                                  <td>
                                      TRABAJADOR
                                  </td>
                                  <td> 
                                       REGIMEN
                                  </td>
                                  <td> 
                                       DESDE 
                                  </td>
                                  <td> 
                                       HASTA 
                                  </td>
                                  <td>
                                      ENCONTRADO
                                  </td>
                              </tr> 

                         ";

                      $c = 1;

                      $conforme = true;


                      foreach ($rs as $reg)
                      {
                            $nf = false;    
                              
                            $nombre = $reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '.$reg['indiv_nombres'];

                            if( trim($reg['indiv_dni']) == '' )
                            {
                                $conforme = false;          
                                $found = ' NO ';
                                $log[] = array('registro' => $c, 'mensaje' => ' El DNI no esta registrado en el sistema ' );
                                $nf = true;
                            }
                            else
                            {
                                $found = 'SI ';
                            }


                            $html_excel.=" <tr class='tr_row_celeste'>     
                                       <td  align='center' > 
                                          ".$c."
                                       </td>
                                       <td align='center' ";

                            if($nf)  $html_excel.=$_styles['_alert'];
                                

                            $html_excel.=" >
                                          ".$reg['bio_dni']." 
                                       </td>
                                       <td>
                                          ".$nombre." 
                                       </td>  
                                       
                                       <td  align='center'>
                                          ".$reg['plati_nombre']." 
                                       </td>
                                       <td  align='center'>
                                          "._get_date_pg($reg['desde'])." 
                                       </td>
                                       <td  align='center'>
                                          "._get_date_pg($reg['hasta'])." 
                                       </td>  
                                       <td align='center'> 
                                          ".$found." 
                                       </td> 
                                   </tr>";

                            $c++;
                      }   


        $html_excel.="      </table>
                         </div>
                     ";
  
 
         $data = array(
 
                  'num_trabajdores'  => $c

               );


 


         return array($conforme, $html_excel, $log, $data);
       
   }
 


    public function cargar_datos($params = array())
    { 

         $this->_CI->load->library(array('App/biometricoimportacion'));

         $this->_CI->db->trans_begin(); 

         $descripcion_importacion = $params['descripcion'];

         $sql = "  SELECT distinct(archim_id) as file
                   FROM planillas.biometrico_data 
                   WHERE biodata_estado = 1 AND biom_id = ? ";

         list($rs) = $this->_CI->db->query($sql, array($params['biometrico']))->result_array();
         $file_id_procedencia_data = $rs['file'];
  

         if($params['file_id'] != $file_id_procedencia_data)
         {
             return false;
         }


         $sql = " SELECT MIN(biodata_dia) as desde, MAX(biodata_dia) as hasta 
                  FROM planillas.biometrico_data 
                  WHERE biodata_estado = 1 AND biom_id = ? ";

         list($rs) = $this->_CI->db->query($sql, array($params['biometrico']))->result_array();

         $bio_fecha_ini = $rs['desde'];
         $bio_fecha_fin = $rs['hasta'];
         
         $biometrico_id = $params['biometrico'];


         $sql_main = "SELECT trabajadores.indiv_id, cal.caldia_dia as fecha 
                      FROM 
                     ( SELECT distinct(indiv_id) as indiv_id 
                       FROM planillas.biometrico_data    
                       WHERE biodata_estado = 1 AND biom_id = ".$params['biometrico']."
                       ORDER BY indiv_id ) as trabajadores, 
                       public.calendario cal 
                       WHERE cal.caldia_dia between '".$bio_fecha_ini."' AND '".$bio_fecha_fin."' 
                       ORDER BY trabajadores.indiv_id, cal.caldia_dia 
                       ";
 
        // Obtener fecha menor, fecha mayor
        // Combinar trabajador por el rango de fechas 
         // wala 
 

           $sql = " SELECT datos.indiv_id,
                           datos.fecha,
                           biodata.biodata_id,
                           biodata.biodata_marcacion1,
                           biodata.biodata_marcacion2,

                           hoaed.hoaed_id, 
                           plati_config.tipo_registro_asistencia, 

                           (CASE WHEN hoaed.hoaed_id is not null  THEN  hoaed.hatd_id  ELSE  plati_estado_dia.hatd_id  END ) as hatd_id,

                           (CASE WHEN ho_dia.hor_id is not null AND ho_dia.hor_id != 0  THEN 1 ELSE 0 END ) as horario_cargado,

                           (CASE WHEN ho_dia.hor_id is not null AND ho_dia.hor_id != 0  THEN hoaed.hoaed_laborable  ELSE  plati_estado_dia.platide_laborable  END ) as dia_laborable,

                           (CASE WHEN ho_dia.hor_id is not null AND ho_dia.hor_id != 0  THEN  ho_dia.hor_id  ELSE  ho_plati.hor_id  END ) as horario_id,

                           (CASE WHEN ho_dia.hor_id is not null AND ho_dia.hor_id != 0  THEN  ho_dia.hor_hora1_e   ELSE  ho_plati.hor_hora1_e  END ) as horario1_e,

                           (CASE WHEN ho_dia.hor_id is not null AND ho_dia.hor_id != 0  THEN  ho_dia.hor_hora1_s   ELSE  ho_plati.hor_hora1_s  END ) as horario1_s,

                           (CASE WHEN ho_dia.hor_id is not null AND ho_dia.hor_id != 0  THEN  ho_dia.hor_ini_tardanza  ELSE   ho_plati.hor_ini_tardanza   END ) as horario_ini_tardanza,
                           
                           (CASE WHEN ho_dia.hor_id is not null AND ho_dia.hor_id != 0  THEN  ho_dia.hor_hora1_max_ft  ELSE  ho_plati.hor_hora1_max_ft  END ) as horario_ini_ft  
 

                    FROM (
                        ".$sql_main." 
                    ) as datos 
                    LEFT JOIN planillas.biometrico_data biodata ON datos.indiv_id = biodata.indiv_id AND datos.fecha = biodata.biodata_dia AND biodata.biom_id = ?
                    LEFT JOIN rh.persona_situlaboral persla ON datos.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1
                    LEFT JOIN planillas.hojaasistencia_plati_config plati_config ON persla.plati_id = plati_config.plati_id AND hoplac_estado = 1 
                    LEFT JOIN planillas.hojaasistencia_emp_dia hoaed ON datos.indiv_id  = hoaed.indiv_id AND hoaed.hoaed_estado = 1 AND hoaed.hoaed_fecha = datos.fecha
                    LEFT JOIN planillas.hojaasistencia_horarios ho_dia ON hoaed.hor_id = ho_dia.hor_id   
                    LEFT JOIN planillas.planillatipo_dia_horario plati_horario ON plati_horario.plati_id = persla.plati_id AND plati_horario.dia_id  = EXTRACT('dow' FROM(datos.fecha)) AND platidh_estado = 1
                    LEFT JOIN planillas.planillatipo_dia_estado plati_estado_dia ON plati_estado_dia.plati_id = persla.plati_id AND plati_estado_dia.dia_id  = EXTRACT('dow' FROM(datos.fecha)) AND platide_estado = 1
                    
                    LEFT JOIN planillas.hojaasistencia_horarios ho_plati ON plati_horario.hor_id = ho_plati.hor_id     
                     
                    ORDER BY datos.indiv_id, datos.fecha 
                 ";
  
           $rs = $this->_CI->db->query($sql, array($biometrico_id) )->result_array();

           $indiv_id =  '';
           $dia  = '';
           $hora = ''; 

           $ultimo = false;

           $max = sizeof($rs) - 1;

           //platide_laborable

           foreach ($rs as $i => $dia_trabajador)
           { 
              
              $indiv_id = $dia_trabajador['indiv_id'];
              $marcacion_1 = $dia_trabajador['biodata_marcacion1'];
              $marcacion_2 = $dia_trabajador['biodata_marcacion2'];
              $dia = $dia_trabajador['fecha'];
 
              $horario_id = $dia_trabajador['horario_id'];
  
              if($dia_trabajador['tipo_registro_asistencia'] == TIPOREGISTRO_ASISTENCIA_MODULO){ 

                 // Si no existe registro del día
                 
                 if($dia_trabajador['hoaed_id'] == '') 
                 {
                 
                       $sql_i = '';
                       $sql_p = '';
                       
                       /* 
                           Si el registro es laborable y toca un dia de asistencia pero no tiene registro en el reporte del biometrico, entonces se considera falta       
                       */

                       if($dia_trabajador['hatd_id'] == ASISDET_ASISTENCIA &&  $dia_trabajador['dia_laborable'] == '1' && $dia_trabajador['biodata_id'] == ''){

                          $estado = ASISDET_FALTA;
                       }
                       else
                       {
                          $estado = ($dia_trabajador['hatd_id'] == '' ? ASISDET_ASISTENCIA : $dia_trabajador['hatd_id'] );
                       }

                       $params = array($dia_trabajador['dia_laborable'], $estado, $dia, $indiv_id, $horario_id);

                       if($marcacion_1 != '')
                       {
                           $sql_i.= ' hoae_hora1_e, ';
                           $sql_p.= ' ?, ';
                           $params[] = $marcacion_1;
                       }

                       if($marcacion_2 != '')
                       {
                           $sql_i.= ' hoae_hora1_s, ';
                           $sql_p.= ' ?, ';
                           $params[] = $marcacion_2;
                       }

                     
                       $sql = " INSERT INTO planillas.hojaasistencia_emp_dia( hoaed_laborable, hatd_id, hoaed_fecha, 
                                                                              indiv_id, hor_id, ".$sql_i." biom_id, 
                                                                              hoae_bio_fecreg, haoed_reloj_import_marcaciones  ) 

                                VALUES( ?, ?, ?, 
                                        ?, ?, ".$sql_p." 
                                        ?, now(), ? ) ";

                       $params[] = $biometrico_id;

                       $params[] = $marcaciones_dia_txt;

                       $this->_CI->db->query($sql, $params );


                   }
                   else if( $dia_trabajador['hoa_id'] == '' || $dia_trabajador['hoa_estado'] == '' || $dia_trabajador['hoa_estado']  == HOJAASIS_ESTADO_ELABORAR  ) 
                   {

                      $sql_i = '';
                      $sql_p = '';
                      $sql_e = '';
                      $params = array( $biometrico_id, $marcaciones_dia_txt  ); 
  
                      // if($dia_trabajador['hatd_id'] == ASISDET_ASISTENCIA )
                      // {
                      //     $sql_e = "  hatd_id = ?, ";
                      //     $params[] = $estado; 
                      // } 

                      if($dia_trabajador['horario_cargado'] == '0' || $dia_trabajador['hatd_id'] == ASISDET_NOCONSIDERADO ){

                          $sql_e.= "  hoaed_laborable = ?, ";
                          $params[] = $dia_trabajador['dia_laborable']; 

                          $sql_e.= "  hor_id = ?, ";
                          $params[] = $dia_trabajador['horario_id']; 

                      }

                      if(( $marcacion_1 != '' || $marcacion_2 != '') && $dia_trabajador['dia_laborable'] == '1' && ( $dia_trabajador['hatd_id'] == ASISDET_NOCONSIDERADO || $dia_trabajador['hatd_id'] == ASISDET_FALTA)  ){
 
                          $sql_e.= "  hatd_id = ?, ";
                          $params[] = ASISDET_ASISTENCIA; 
                      }
 
                      if($marcacion_1 != '')
                      {
                          $sql_e.= ' hoae_hora1_e = ?, ';
                          $params[] = $marcacion_1;
                      }

                      if($marcacion_2 != '')
                      {
                          $sql_e.= ' hoae_hora1_s = ?, ';
                          $params[] = $marcacion_2;
                      }
  
                       $sql = " UPDATE planillas.hojaasistencia_emp_dia
                                SET biom_id = ?,
                                    haoed_reloj_import_marcaciones = ?,
                                    ".$sql_e." 
                                    hoae_bio_fecreg = now()

                                WHERE hoaed_id = ? 

                              "; 
 

                       $params[] = $dia_trabajador['hoaed_id'];  
                      
                       $this->_CI->db->query($sql, $params );

                  }
                
              }

             $marcaciones_dia_txt = '';

             if( $i == ($max -1) && $ultimo === false )
             {
                $i--;
                $ultimo = true;
             }

              
         } // Fin del recorrido de la data cargada 
  

         if($this->_CI->db->trans_status() === FALSE) 
         {
             $this->_CI->db->trans_rollback();
             return false;
             
         }
         else
         {
             
            list($bimp_id, $biom_key) = $this->_CI->biometricoimportacion->registrar( array('bimp_descripcion' => $descripcion_importacion, 'biom_id' => $biometrico_id ) , true );

            $this->_CI->biometricoimportacion->set_idimportacion_byFile($bimp_id, $file_id_procedencia_data );

            $this->_CI->db->trans_commit();
            return true;
         } 

    }

 
}
