<?php

class xlsimportacion extends Table{
     

   protected $_FIELDS=array(   
                                    'id'          => 'archim_id',
                                    'code'        => 'archim_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'archim_estado'
                            );
    
   protected $_SCHEMA     = 'planillas';
   protected $_TABLE      = 'archivos_importacion';
   protected $_PREF_TABLE = 'ARCHIVOSCS'; 
     

   
   public $_variables            = array();
   
   public $by_planilla           = false;
   public $by_mes                = false;
   
   private $_columnas            = array();
   private $_columnas_variables  = array();
   private $_columnas_datos      = array();

  // public  $columnas_variables_config = array();

   private $_columna_planilla    = 0;    
   private $_columna_mes         = 0;
   private $_columna_dni         = 0;
   private $_columna_subtipo     = 0;
   private $_columna_ocupaciontxt     = 0;
   private $_columna_fuente     = 0;
   public  $_plati_id            = array(0); 
   private $_planilla_especifica = false;    
   private $_planilla_id         = 0;
   private $_vincular_trabajador = true; // SI ES TRUE ENTONCES LE BUSCA UNA PLANILLA AL TRABAJADOR
   private $_subtipo             = false;


   public function __construct(){
         
       parent::__construct();
         
   }

   public function get_modos_importacion()
   {
      
      $sql = " SELECT * FROM planillas.xls_importacion_modos 
               WHERE xim_estado = 1 ORDER BY xim_orden ";
      
      $rs  = $this->_CI->db->query($sql, array())->result_array();

      return $rs;

   }

   public function get_modo_importacion($modo){


      $sql= " SELECT * FROM planillas.xls_importacion_modos WHERE xim_id = ? LIMIT 1 ";
      list($rs) = $this->_CI->db->query($sql, array($modo))->result_array();

      return $rs;
   }

   public function get_file_info($id){

       $sql = " SELECT * FROM  planillas.archivos_importacion WHERE archim_id = ? LIMIT 1";
       $rs  = $this->_CI->db->query($sql, array($id))->result_array();

       return $rs[0];

   }  
   

   public function destroy($id)
   {
       
       $current_info = $this->get_file_info($id);
       $file_name    = $current_info['archim_file'];
       
       $sql =" DELETE FROM planillas.archivos_importacion WHERE archim_id = ? ";
       $this->_CI->db->query($sql, array($id));
 
       unlink('docsmpi/importar/'.$file_name);    

   }

   public function validar_mes($mes)
   {
   
      $meses = array('01','02','03','04','05','06','07','08','09','10','11','12');

      return (in_array($mes, $meses)) ? true : false;

   }
   

   public function get_planilla($pla , $codigo = true)
   {
      
      $this->_CI->load->library('App/planilla');

      $info =  $this->_CI->planilla->get($pla, $codigo);
      
      return $info;   

   }


   public function get_dni( $dni, $planilla_codigo = '', $mes = '', $subtipo = '' )
   {
       

         if(trim($planilla_codigo) == '' && trim($mes) == '' )
         {


             $sql =  "  SELECT ind.*, 
                             persla.persla_id, 
                             persla.plati_id, 
                             persla.persla_vigente,
                             plati.plati_nombre as regimen 
                        FROM (
                           SELECT indiv_id, indiv_appaterno, indiv_apmaterno, indiv_nombres, indiv_dni  
                           FROM public.individuo
                           WHERE indiv_dni = ? LIMIT 1

                        ) as ind 
                        LEFT JOIN rh.persona_situlaboral persla ON persla.pers_id = ind.indiv_id AND persla.persla_ultimo = 1 
                        LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id =plati.plati_id 
                    
                     ";

             $rs = $this->_CI->db->query($sql, array($dni) )->result_array();

             return $rs[0];
        
        }
        else
        {
             

             $params = array($dni);



             $sql = " SELECT  

                          ind.*, 
                          persla.plati_id, 
                          persla.persla_vigente,
                          plati.plati_nombre as regimen,
                           
                          data_planilla.pla_id, 
                          data_planilla.pla_mes,
                          data_planilla.pla_codigo as codigo_planilla,
                          data_planilla.plati_id as tipo_planilla,
                          plaemp.plaemp_id, 
                          plaemp.platica_id

                      FROM 
                        ( SELECT indiv_id, indiv_appaterno, indiv_apmaterno, indiv_nombres, indiv_dni 
                           FROM public.individuo WHERE indiv_dni = ? LIMIT 1 ) as ind 

                         LEFT JOIN rh.persona_situlaboral persla ON persla.pers_id = ind.indiv_id AND persla.persla_ultimo = 1 
                         LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                         LEFT JOIN (

                              SELECT 
                                 ( substring( pla.pla_anio from 3 for 2) || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id ) as pla_codigo,

                                   pla.pla_id,
                                   pla.pla_mes, 
                                   pla.plati_id,
                                   pla.pla_estado 
         
                              FROM planillas.planillas pla
                              LEFT JOIN planillas.planilla_movimiento mov ON mov.pla_id = pla.pla_id AND mov.plamo_estado = 1  
                              WHERE pla.pla_estado = 1 AND mov.plaes_id =   ".ESTADOPLANILLA_ELABORADA; 


                             if($planilla_codigo != '' && $mes == '' )
                             {
                                 $sql.= " AND ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  = ? ";
                                 $params[] = $planilla_codigo;
                             } 

                             if($mes != '')
                             {
                                 $sql.=" AND pla.pla_mes = ? ";
                                 $params[] = $mes;
                             }    


            $sql.="            

                         ) as data_planilla  ON data_planilla.pla_estado = 1
                         
                         LEFT JOIN planillas.planilla_empleados plaemp ON data_planilla.pla_id = plaemp.pla_id AND plaemp_estado = 1  ";

                         if($subtipo != '')
                         { 
                              
                             $sql.= " AND plaemp.platica_id = ? ";
                             $params[] = $subtipo;
                         }    


           $sql.="              
                      WHERE persla.persla_estado = 1
 
                    ";
 

 
             $rs = $this->_CI->db->query($sql, $params )->result_array();

             return $rs;

        }
         
      
   }

   public function get_tipostrabajador($plati){


       $sql = " SELECT platica_id FROM planillas.planilla_tipo_categoria WHERE plati_id = ?  AND  platica_estado  = 1 ";
       $rs = $this->_CI->db->query($sql, array($plati))->result_array();

       $tipos = array();

       foreach($rs as $reg)
       {
         $tipos[] = $reg['platica_id'];
       }

       return $tipos;

   }



   public function get_indiv_id($dni)
   {
       
     $sql = " SELECT indiv_id FROM public.individuo  WHERE indiv_dni ='".$dni."' AND indiv_estado = 1 ";

     $rs = $this->_CI->db->query($sql, array($dni))->result_array();

     return $rs[0]['indiv_id'];
   }

   
   public function get_pla_id($codigo)
   {
   
       $sql = " SELECT pla_id FROM planillas.planillas pla
                WHERE ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id   ) = ? 
              ";

       $rs = $this->_CI->db->query($sql, array($codigo))->result_array();            

       return $rs[0]['pla_id'];
   }


   public function detectar_columnas()
   {

   }
 

   public function explorar($id, $params = array())
   {
 
 
       require_once BASEPATH.'../application/libraries/excel_reader2.php';
 

        $info_upload     = $this->get_file_info($id);    
        $file            = $info_upload['archim_file'];
      
       // var_dump($file);
        
        $file_excel      = new Spreadsheet_Excel_Reader("docsmpi/importar/".$file); 
        $file_import_id  = $id;
        
        $hoja            =  0;
        $numero_filas    = $file_excel->rowcount($hoja);
        $numero_columnas = $file_excel->colcount($hoja);

          

    //    var_dump($this->by_planilla, $this->by_mes , $this->_plati_id );

       for($i=1; $i<=$numero_columnas; $i++ )
       {

           $col =  strtoupper(trim($file_excel->val(1,$i) ));
      
           if( $this->by_planilla && $col == 'PLANILLA')
           {

               $this->_columna_planilla = $i;    
               $this->_columnas[]       = $i;

           }
           else if($col == 'DNI')
           {

               $this->_columna_dni = $i;
               $this->_columnas[]  = $i; 

           }
           else if( $col == 'TIPO')
           {

               $this->_columna_subtipo = $i;
               $this->_columnas[]      = $i;     

           }
           else if( $this->by_mes && $col == 'MES')
           {
               
               $this->_columna_mes = $i;
               $this->_columnas[]  = $i;

           }
           else if( $col == 'OCUP')
           {
               
               $this->_columna_ocupaciontxt = $i;
               $this->_columnas[]  = $i;

           }
           else if( $col == 'FUENTE')
           {
               
               $this->_columna_fuente = $i;
               $this->_columnas[]  = $i;

           }
           else{

               $f = strpos( $col, 'VAR_');
             
               if($f === 0)
               {

                   $this->_columnas_variables[$col] = $i;
                   $this->_columnas[]               = $i;

               }


               $f = strpos( $col, 'DATO_');

               if($f === 0 )
               {

                   $this->_columnas_datos[$col] = $i;
                   $this->_columnas[]           = $i;

               }
   
           }
   
       }
      


       /*
       $columnas_variables_requeridas = TRUE;
       
       foreach($this->columnas_variables_config as $var)
       {

           if( in_array(  strtoupper(trim($var)) , array_keys($this->_columnas_variables)) == FALSE )
           {
               $columnas_variables_requeridas = FALSE;
           }
      
       }        
       */
  
       if($this->_columna_mes != 0 )
       {

          $this->by_mes = true;
          $this->by_planilla = false;

       }
       else if($this->_columna_planilla != 0 )
       {

          $this->by_mes = false;
          $this->by_planilla = true;

       }
   


       $html_excel = "   
                       <div style='width:600px; height: 400px; border:1px solid #988888'>

                          <table id='table_resumen_xls' cellpadding='2' border='1' class='_tablepadding4' >  
                            
                            <tr class='tr_header_celeste'>
                                <td> # </td>
                      ";

       /*
          IMPRIMIMOS EL HEADER CON LAS COLUMNAS IMPORTANTES COLOREADAS
       */

       for($c = 1; $c<= $numero_columnas; $c++ )
       {
   
            $celda   = $file_excel->val('1',$c);
            $html_excel.=' <td ';
            
            if( in_array($c, $this->_columnas ))
            {

                $html_excel.=' style="background-color:#336699; color:white;" ';
            
            } 
   
            $html_excel.=' /> '.$celda;

            $html_excel.= ' </td> ';

            if($c == $this->_columna_dni)
            {
            
                $html_excel.='<td style="background-color:#336699; color:white;" > NOMBRE </td>';
                $html_excel.='<td style="background-color:#336699; color:white;" > REGIMEN </td>';  
            
            }

       }

       $html_excel.=" </tr> ";



       if( ( $this->_columna_mes == 0 && $this->_columna_planilla == 0 ) || $this->_columna_dni == 0  )
       {  
            
          $log = array();

          $log[] = array('registro' => 'header',

                       'mensaje' => 'Para poder importar datos es necesario especificar una columna MES o PLANILLA, ademas de una DNI, TIPO y de las columnas de la asistencia');
          $html_excel.=" </table> ";
          return array( false, $html_excel, $log);
       }





     
       $explorer = array(
                         'planillas_found'      => array(),
                         'planillas_unfound'    => array(),
                         'trabajadores_found'   => array(),
                         'trabajadores_unfound' => array() 
                        );
   


       $_styles = array(

                        '_alert' => ' style="background-color:#990000; color:white;" ' 

                       );
   

       $conforme = true;


       if($this->_planilla_especifica)
       {
          
           $planilla_info = $this->planilla_info($this->_planilla_id, false);
       }
 
       /*
         SUBTIPO TRUE O FALSE DEBE OBTENERSE DE LA PLANILLA
       */
 
       $has_subtipos = array(9);


       $count = 0; 

       $log = array();

       $totales_conciliar = array();


       /* INCIAMOS RECORRIDO DE FILAS */

       for($row = 2; $row <= $numero_filas;  $row++ )
       {
             
           $count++;
           
           $registro = array(0);  

           for($col = 1; $col <= $numero_columnas; $col++)
           {
              $registro[$col] = trim($file_excel->val($row,$col)); 
           }


           if($this->_planilla_especifica == FALSE && $this->by_planilla)
           {
               $planilla = $registro[$this->_columna_planilla];
               $planilla_info = $this->get_planilla($planilla, true);
                
           } 
          
           if($this->by_mes)   $mes  =  $registro[$this->_columna_mes];  


           $subtipo =  '';

           if( in_array( $planilla_info['plati_id'], $has_subtipos  ))
           {
              
              $this->_subtipo   = true;
              $subtipo          = $registro[$this->_columna_subtipo];
              $tipos_trabajador = $this->get_tipostrabajador( $planilla_info['plati_id'] );
 
           }

 
           
           $dni = $registro[$this->_columna_dni];


           $clean = true;
           foreach($registro as $k => $d)
           {
               if(trim($d) != '' && $k >= 1 ) $clean = false;
           }
           if($clean) break;



           $html_excel.=" <tr class='tr_row_celeste'> 
                              <td>
                                ".$count."
                              </td> 
                        ";
   


             /* INICIAMOS RECORRIDO DE COLUMNAS */

             for($col = 1; $col <= $numero_columnas; $col++ )
             {
                 

                 $nombre_completo = ' ----- '; 
                 $regimen = ' ----- ';
                 $celda = trim($file_excel->val( $row, $col)); 
                 
                 $html_excel.= " <td ";
                 $html_extra = " ";


                   if($col == $this->_columna_planilla )
                   {
                 
                     if($this->by_planilla)
                     { 

                         
                         if(  in_array($celda, $explorer['planillas_found']) == FALSE && 
                              in_array($celda, $explorer['planillas_unfound']) == FALSE  )
                         {
                             
                            /*
                            if( $this->_planilla_especifica == FALSE)
                            {
                               $planilla_info = $this->get_planilla($celda, true);
                            }*/
                 
                            $ok = ($planilla_info['pla_id'] != '' && $planilla_info['estado_id'] == ESTADOPLANILLA_ELABORADA ) ? true : false;
                  
                            if($ok)
                            {
                               
                               $explorer['planillas_found'][] = $celda;  
                               $html_extra  = " <input type='hidden' class='xlsdata_planilla' value='".$planilla_info['pla_id']."' />";
                                
                            }   
                            else
                            {

                               $explorer['planillas_unfound'][] = $celda;
                               $html_excel.=$_styles['_alert'];
                               $conforme = false;
                               $log[] = array('registro' => $count, 'mensaje' => ' La planilla no existe o su estado es diferente de ELABORAR ' );
                 
                            } 


                            
                         }
                         else
                         {

                            if(in_array($celda, $explorer['planillas_unfound']))
                            {

                                $html_excel.=$_styles['_alert'];
                                $conforme = false;
                            }  

                         }
                          
                     } 

                   }
                   

                   if($col == $this->_columna_mes && $this->_planilla_especifica == FALSE )
                   {

                       if($this->by_mes)
                       {
                 
                           $ok = $validar_mes($celda);

                           if($ok == FALSE)
                           { 
                               $conforme = false;
                               $html_excel.=$_styles['_alert'];
                               $log[] = array('registro' => $count, 'mensaje' => ' El MES es incorrecto ' );
                           }

                       }
                 
                   }
                   

                   if($col == $this->_columna_dni)
                   { 

                          if($this->by_planilla )
                          {
                 
                                       
                                     if($this->_vincular_trabajador)
                                     {

                                         // Comprobar que el trabajador sea del tipo de la planilla 
                                         // TIPO DE TRABAJO }
 
                                         $indiv_info    = $this->get_dni($celda);
                                         $mensaje_error = '';
                                         $error_1       = false;
                                       

                                         if( $indiv_info['indiv_id'] == null || $indiv_info['indiv_id'] == '' || $celda == '' )
                                         {

                                             $error_1 = true;
                                             $mensaje_error = 'El trabajador no existe ';

                                         }
                                         else if($indiv_info['persla_vigente'] == '0')
                                         {
                                            
                                             $error_1 = true;
                                             $mensaje_error = 'El trabajador no esta vigente ';

                                         }
                                         else if($planilla_info['plati_id'] != $indiv_info['plati_id'] )
                                         {
                                            
                                             $error_1 = true;
                                             $mensaje_error = 'El regimen del trabajador no coincide con el de la planilla ';
                                         }
                                         else if( in_array($indiv_info['plati_id'],  $this->_plati_id) == FALSE )
                                         {
                                             
                                             $error_1 = true;
                                             $mensaje_error = 'El regimen del trabajador no esta permitido';
                                         }
                                         else
                                         {

                                             if(in_array($celda, $explorer['trabajadores_found']) == FALSE) $explorer['trabajadores_found'][] = $celda;
                                         }  


                                         if($error_1)
                                         {   
                                             $conforme = false;
                                             $html_excel.=$_styles['_alert'];       
                                             $log[] = array('registro' => $count, 'mensaje' => $mensaje_error);
                                         }
                                         else{
                 
                                             $nombre_completo =  $indiv_info['indiv_appaterno'].' '.$indiv_info['indiv_apmaterno'].' '.$indiv_info['indiv_nombres'];
                                             $regimen = $indiv_info['regimen'];
                                         }


                                     }
                                     else{

                                          // Comprobar que el trabajador este en la planilla
                                         
                                        
                                         $indiv_info    =  $this->get_dni($celda, $planilla_info['pla_codigo'], '', $subtipo);
                                         $mensaje_error =  '';
                                         $error_1       =  false;
                                         
                                         
                                         if(sizeof($indiv_info) == 0  )
                                         { 
                                            $error_1 = true;
                                            $mensaje_error = 'El DNI no esta registrado';
                                         }
                                         else if( sizeof($indiv_info) == 1 && $indiv_info[0]['pla_id'] == ''  )
                                         { 
                                         
                                            $error_1 =  true;
                                            $mensaje_error = 'La planilla no existe o su estado es diferente a elaborar '; 
                                         
                                         }
                                         else if(sizeof($indiv_info) == 1 && $indiv_info[0]['pla_id'] != '' &&  $indiv_info[0]['plaemp_id'] == '' )
                                         { 
                 
                                            $error_1 =  true;
                                            $mensaje_error = 'El trabajador no esta vinculado a la planilla'; 
                 
                                         } 
                                         else if(  sizeof($indiv_info) == 1 && $indiv_info[0]['pla_id'] != '' &&  (in_array($indiv_info[0]['plati_id'],  $this->_plati_id) == FALSE) )
                                         {
                                          
                                             $error_1 = true;
                                             $mensaje_error = 'El regimen del trabajador no esta permitido';
                                         
                                         }   
                                         else if(sizeof($indiv_info) > 1)
                                         { 
                 
                                            $error_1 =  true;
                                            $mensaje_error = ' El Trabajador esta vinculado a varias planillas y no es posible determinar a cual se importaran los datos'; 
                 
                                         }
                                         else{

                                            if(in_array($celda, $explorer['trabajadores_found']) == FALSE) $explorer['trabajadores_found'][] = $celda;
                                         } 

                                         if($error_1)
                                         {

                                            $conforme = false;
                                            $html_excel.=$_styles['_alert'];     
                                            $log[] = array('registro' => $count, 'mensaje' => $mensaje_error );

                                         }
                                         else
                                         {
                                         
                                            $nombre_completo =  $indiv_info['indiv_appaterno'].' '.$indiv_info['indiv_apmaterno'].' '.$indiv_info['indiv_nombres'];
                                            $regimen = $indiv_info['regimen'];
                                         }
                                             

                                     }   
                                 
                 
                          }
                          else if($this->by_mes)
                          {
                                
                               
                              $indiv_info    =  $this->get_dni($celda, '', $mes, $subtipo );   
                              $mensaje_error = '';
                              $error_1       = false;
                              

                              if(sizeof($indiv_info) == 0 )
                              { 

                                 $error_1 = true;
                                 $mensaje_error = 'El DNI no esta registrado';

                              }
                              else if(sizeof($indiv_info) == 1 && $indiv_info[0]['pla_id'] == '')
                              { 
                              
                                 $error_1 =  true;
                                 $mensaje_error = 'El trabajador no esta vinculado a ninguna planilla'; 
                              
                              }
                              else if(  sizeof($indiv_info) == 1 && $indiv_info[0]['pla_id'] != '' &&  (in_array($indiv_info['plati_id'],  $this->_plati_id) == FALSE) )
                              {
                                 
                                  $error_1 = true;
                                  $mensaje_error = 'El regimen del trabajador no esta permitido';

                              }   
                              else if(sizeof($indiv_info) > 1)
                              {
                                
                                $error_1 =  true;
                                $mensaje_error = 'El Trabajador esta vinculado a varias planillas y no es posible determinar a cual se importaran los datos';
                              
                              }
                              else{

                                 if(in_array($celda, $explorer['trabajadores_found']) == FALSE) $explorer['trabajadores_found'][] = $celda;
                              } 


                              if($error_1)
                              {

                                 $conforme = false;
                                 $html_excel.=$_styles['_alert'];     
                                 $log[] = array('registro' => $count, 'mensaje' => $mensaje_error );
                              
                              }
                              else
                              {
                              
                                  $nombre_completo =  $indiv_info[0]['indiv_appaterno'].' '.$indiv_info[0]['indiv_apmaterno'].' '.$indiv_info[0]['indiv_nombres'];

                              }

                 
                          }   
                 
                                
                  }
                 


                  if( $col == $this->_columna_subtipo)
                  {
                          
                      if($this->_subtipo)
                       {
                            if(!in_array($subtipo, $tipos_trabajador))
                            {
                            
                                $conforme = false;
                                $html_excel.=$_styles['_alert'];     
                                $log[] = array('registro' => $count, 'mensaje' => 'El tipo de trabajo no es valido' );
                            
                            }

                      }
                 
                  } 

    

                  if( in_array($col, $this->_columnas_variables))
                  {

                     if($celda != '' && is_numeric($celda) == FALSE)
                     {

                        $conforme = false;
                        $html_excel.= $_styles['_alert'];     
                        $log[] = array('registro' => $count, 'mensaje' => 'La variable debe ser un número' );
 
                     }
                     else
                     {

                          if($totales_conciliar[$col]== '') $totales_conciliar[$col] = 0;

                          $totales_conciliar[$col]+=$celda;
                     }

                  } 
 

                  $html_excel.= ">".$celda."  ".$html_extra." </td> ";     
   
                  if($col == $this->_columna_dni)
                  {
                     $html_excel.='<td> '.$nombre_completo.'</td>';
                     $html_excel.='<td> '.$regimen.'</td>';
                  }
                 

             }

           
           $html_excel.=" </tr> ";
   
       }  

     
       $html_excel.=" <tr> ";

       for($col = 1; $col <= $numero_columnas; $col++ )
       {

            if( in_array($col, $this->_columnas_variables))
            {

               $html_excel.='<td> '.$totales_conciliar[$col].'</td>';

            }
            else{

               $html_excel.='<td> </td>';

               if($col == $this->_columna_dni)
               {
                  $html_excel.='<td>  </td>';
                  $html_excel.='<td>  </td>';
                  $html_excel.='<td>  </td>';
               }
            }

       } 
 
       $html_excel.=" </tr> ";



       $html_excel.="   </table> 
               
                        <input type='hidden' value='1' id='hbxlstable_loaded' />
                     </div> ";

      


       $data = array(

                  'columnas_variables' => $this->_columnas_variables,
                  'planillas'          => $explorer['planillas_found'],
                  'trabajadores'       => $explorer['trabajadores_found'],
                  'registros'          => $count

               );

       return array($conforme, $html_excel, $log, $data);
   
   }





   public function importar($id, $params = array() )
   {

       $this->_CI->db->trans_begin();

       //ignore_user_abort(false);

       $this->_CI->load->library( array('App/variable',
                                        'App/planilla', 
                                        'App/planillaempleado', 
                                        'App/planillaempleadovariable') );

       
       require_once BASEPATH.'../application/libraries/excel_reader2.php';
        
       $info_upload    = $this->get_file_info($id);    
       $file           = $info_upload['archim_file'];
        
       $file_excel     = new Spreadsheet_Excel_Reader("docsmpi/importar/".$file); 
       $file_import_id = $id;
        
       $hoja            =  0;
       $numero_filas    = $file_excel->rowcount($hoja);
       $numero_columnas = $file_excel->colcount($hoja);
        
       $count_record = 0;

       for($i=1; $i<=$numero_columnas; $i++ )
       {

           $col =  strtoupper(trim($file_excel->val(1,$i) ));
       
           if( $this->by_planilla && $col == 'PLANILLA')
           {

               $this->_columna_planilla = $i;    
               $this->_columnas[]       = $i;

           }
           else if($col == 'DNI')
           {

               $this->_columna_dni = $i;
               $this->_columnas[]  = $i; 

           }
           else if( $col == 'TIPO')
           {

               $this->_columna_subtipo = $i;
               $this->_columnas[]      = $i;     

           }
           else if( $this->by_mes && $col == 'MES')
           {
               
               $this->_columna_mes = $i;
               $this->_columnas[]  = $i;

           }
           else if( $col == 'OCUP')
           {
               
               $this->_columna_ocupaciontxt = $i;
               $this->_columnas[]  = $i;

           }
           else if( $col == 'FUENTE')
           {
               
               $this->_columna_fuente = $i;
               $this->_columnas[]  = $i;

           }
           else{

               $f = strpos( $col, 'VAR_');
             
               if($f === 0)
               {

                   $this->_columnas_variables[$col] = $i;
                   $this->_columnas[]               = $i;

               }


               $f = strpos( $col, 'DATO_');

               if($f === 0 )
               {

                   $this->_columnas_datos[$col] = $i;
                   $this->_columnas[]           = $i;

               }
       
           }
       
       }
       



       $data_variables    = array();
       $data_planillas    = array();
      
       $totales_conciliar = array(); 
 
       $total_rows_import = 0;
 
       $planillas = array();
 
       $import_ok = true;


       $punto_inicio = $params['punto_inicio'] + 1;

       $punto_limite = $params['punto_inicio'] +  $params['numero_registros'];

      
      // var_dump($punto_inicio, $punto_limite);

       for( $row = $punto_inicio; $row<=$punto_limite; $row++)
       { 

           $count_record++;
      
          /* if(connection_status() != 0 )
           { 

              // $this->_CI->db->trans_rollback();
               break;
               return false;
           } 
 
           if(connection_status() === 1 || connection_status() === 2 )
           {
              return false;
           }*/
 
           $clean = true; 
           
           for($i=1; $i<=$numero_columnas; $i++)
           {
               if(trim($file_excel->val($row, $i)) != '') $clean = false;
           }
           if($clean) break;



           $indiv_dni   = trim($file_excel->val($row, $this->_columna_dni)); 
           $indiv_id    = $this->get_indiv_id($indiv_dni);
           
           $pla_codigo  = trim($file_excel->val($row, $this->_columna_planilla));

           $ocupacion_labeltxt = trim( $file_excel->val($row, $this->_columna_ocupaciontxt) );
            
           $fuente_especifica = trim($file_excel->val($row, $this->_columna_fuente) );

           if(in_array($pla_codigo, $data_planillas) == FALSE)
           {

              $planilla_id = $this->get_pla_id($pla_codigo);
              $data_planillas[$pla_codigo] = $planilla_id;
           }
           else
           {
              $planilla_id = $data_planillas[$pla_codigo];
           }


           $tipo = $file_excel->val($row, $this->_columna_subtipo);
 
           if($tipo == '')
           {
              $tipo = 0;
           }

           $params_extra = array();

           if($ocupacion_labeltxt != '')
           {
              $params_extra['ocupacion_label'] = $ocupacion_labeltxt;
           }

           if($fuente_especifica != '')
           {
              $params_extra['fuente_especifica'] = $fuente_especifica;
           }

           list($plaemp_id, $plaemp_key) = $this->_CI->planillaempleado->registrar( $planilla_id, $indiv_id , $tipo, TRUE, $params_extra );  


           if($plaemp_id)
           {
              $total_rows_import++;
           }
           else
           {
              $import_ok = false;
           }


         
           for($i = 0; $i <= sizeof($params['columnas']); $i++ )
           { 
                
                $col  = $params['columnas'][$i];
                $vari = $params['variables'][$i];
 
                if($col != '0' && $vari != '0')
                {  
                   $valor = $file_excel->val( $row, $col );

                  //var_dump(array($valor,$vari) );

                   if( ($valor != '' && is_numeric($valor) ) || $valor == '0')
                   { 
                      
                      if(in_array( trim($vari), $data_variables) == FALSE)
                      {
                         $vari_id                     = $this->_CI->variable->get_id($vari);
                         $vari_info                   = $this->_CI->variable->get($vari_id);
                         $vari_opt_print              = $vari_info['vari_displayprint'];
                         $data_variables[trim($vari)] = array($vari_id, $vari_opt_print );    
                      }
                      else
                      {
                         $vari_id        = $data_variables[$vari][0];
                         $vari_opt_print = $data_variables[$vari][1];
                      }
                      


                      $ok =  $this->_CI->planillaempleadovariable->registrar( array(
                                                                                     'plaemp_id'          => $plaemp_id,
                                                                                     'vari_id'            => $vari_id,
                                                                                     'plaev_valor'        => $valor,
                                                                                     'plaev_procedencia'  => PROCENDENCIA_VARIABLE_VALOR_DESDE_XLS,
                                                                                     'plaev_displayprint' => $vari_opt_print,
                                                                                     'archim_id'          => $file_import_id
                                                                                    ));


                      if($ok)
                      {

                          if($totales_conciliar[$col][trim($vari_info['vari_nombre'])] == '' ) $totales_conciliar[$col][trim($vari_info['vari_nombre'])] = 0; 
                          
                          $totales_conciliar[$col][trim($vari_info['vari_nombre'])]+=$valor;

                      }
                      else
                      {
                          if($ok === FALSE) $import_ok = false; 
                      }
 
                      

                   }  
                
                }  
          
          }


          // 


       } /* FIN RECORRIDO DE FILAS*/





      if($this->_CI->db->trans_status() === FALSE || $import_ok === FALSE)  
      {
          $this->_CI->db->trans_rollback();
          return array(false, array() );
          
      }else{
              
          $this->_CI->db->trans_commit();

          $labels_cols = array_keys($this->_columnas_variables);

          $total_resumen = array();


          foreach($totales_conciliar as $pos => $total)
          {

             foreach($this->_columnas_variables as $k => $i)
             {
                  if($pos == $i)
                  {
                     $total_resumen[$k] = $total;

                  }
             }  

          }

  

          $resumen = array(
                            
                            'total_rows'       => $total_rows_import,
                            'totales_columnas' => $total_resumen,
                            'punto_inicio'     => $punto_inicio,
                            'punto_limite'     => $punto_limite
                     );


          return array(true, $resumen);
      } 
  
 
   }




   public function importar_trabajadores($id, $params = array() )
   { 

        $this->_CI->db->trans_strict(TRUE);

         $this->_CI->db->trans_begin();


         $this->_CI->load->library( array('App/variable',
                                          'App/planilla', 
                                          'App/planillaempleado', 
                                          'App/planillaempleadovariable') );

         
         require_once BASEPATH.'../application/libraries/excel_reader2.php';
          
         $info_upload    = $this->get_file_info($id);    
         $file           = $info_upload['archim_file'];
          
         $file_excel     = new Spreadsheet_Excel_Reader("docsmpi/importar/".$file); 
         $file_import_id = $id;
         
         
         $hoja            =  0;
         $numero_filas    = $file_excel->rowcount($hoja);
         $numero_columnas = $file_excel->colcount($hoja); 
          
         
         $COLUMNAS = array(      'DNI'        => array(true, '', 'text'),
                                 'PATERNO'    => array(true, '', 'text'),
                                 'MATERNO'    => array(true, '', 'text'),
                                 'NOMBRES'    => array(true, '', 'text'), 
                                 'REGIMEN_ID' => array(true, '', 'numeric',true), 
                                 'FECHAINI'   => array(true, '', 'date'),  
                                 'FECHAFIN'   => array(false, '', 'date'),
                                 'MONTOCONTRATO' => array(false, '0', 'numeric'),
                                 'SEXO_ID'       => array(false, '0', 'numeric',true), 
                                 'ESTADOCIVIL_ID'=> array(false, '0', 'numeric',true),
                                 'FECHA_NACIMIENTO' => array(true, '', 'date'), 
                                 'DIRECCION' => array(false, '', 'text'), 
                                 'TELEFONO'  => array(false, '', 'text'), 
                                 'EMAIL'     => array(false, '', 'text'), 
                                 'ESSALUD'   => array(false, '', 'text'), 
                                 'CUENTA'    => array(false, '', 'text'), 
                                 'BANCO_ID' => array(false, '0', 'numeric' ,true), 
                                 'TIPO_PENSION_ID' => array(true, '1', 'numeric' ,true ), 
                                 'AFP_ID' => array(false, '0', 'numeric' ,true, array('TIPO_PENSION_ID', PENSION_AFP, '0' ) ), 
                                 'AFP_CUSP' => array(false, '', 'text'), 
                                 'AFP_MODO_ID' => array(false, '1', 'numeric' ,true, array('TIPO_PENSION_ID', PENSION_AFP, '0' )  ),
                                 'INVALIDEZ' => array(false, '1', 'binari', false,  array('TIPO_PENSION_ID', PENSION_AFP, '0' )  ), 
                                 'JUBILADO' => array(false, '0', 'binari'  ) 
                                );
            


            $indices = array('');

            foreach ($COLUMNAS as $col => $params)
            {
                $indices[] = $col;
            } 


         $totales_conciliar = array(); 
 
         $total_rows_import = 0;
  
         $import_ok = true;

         $mensaje = '';
 

           $this->_CI->load->library( array('App/persona', 'App/situacionlaboral') );
         

               for( $row = 2; $row<=$numero_filas; $row++)
               { 
 
                   $clean = true;  
                   for($i=1; $i<=$numero_columnas; $i++)
                   {
                       if(trim($file_excel->val($row, $i)) != '') $clean = false;
                   }
                   if($clean) break;


                   $registro = array('');  

                   for($col = 1; $col <= $numero_columnas; $col++)
                   {
                      $registro[$indices[$col]] = strtoupper(trim($file_excel->val($row,$col))); 

                      if( $registro[$indices[$col]]  == '')
                      {

                         $registro[$indices[$col]] = $COLUMNAS[$indices[$col]][1];
                      }
                      

                   } 

                    $t_x =  $this->_CI->persona->get_by_dni($registro['DNI']);


                    if($t_x['indiv_id'] != '')
                    {
                        $mensaje =   'EL DNI YA ESTA REGISTRADO';
                        $import_ok = FALSE;
                          
                          $this->_CI->db->trans_rollback();
                          break;
                    }


                   $fecha_nac = $registro['FECHA_NACIMIENTO'];
                     
                   $NEWDATA = array(
                                                       'indiv_appaterno'         => $registro['PATERNO'],
                                                       'indiv_apmaterno'         => $registro['MATERNO'],
                                                       'indiv_nombres'           => $registro['NOMBRES'],
                                                       'indiv_sexo'              => $registro['SEXO_ID'],
                                                       'indiv_nacionalidad'      => NACIONALIDAD_PERUANA,
                                                       'indiv_fechanac'          => $fecha_nac,
                                                       'indiv_dni'               => $registro['DNI'], 
                                                       'indiv_direccion1'        => $registro['DIRECCION'],
                                                       'indiv_telefono'          => $registro['TELEFONO'],
                                                       'indiv_email'             => $registro['EMAIL'],
                                                       'indiv_estadocivil'       => $registro['ESTADOCIVIL_ID'],  
                                                       'indiv_essalud'           => $registro['ESSALUD']
                                     );
                    
                    
                    list($id,$code) = $this->_CI->persona->registrar($NEWDATA,true);
        
                    if($id != false && $id != null)
                    {
                        
                     
                              $values = array(
                                                'pers_id'             => $id,  
                                                'plati_id'            => $registro['REGIMEN_ID'],
                                                'persla_fechaini'     => $registro['FECHAINI'],
                                                'persla_vigente'      => '1'
                                          );
                           


                              if( $registro['FECHAFIN'] == '')
                              {
                                  $values['persla_terminoindefinido'] = '1';
                              } 
                              else{

                                  $values['persla_fechafin'] = trim($registro['FECHAFIN']);
                              }

                              if($registro['MONTOCONTRATO'] != '')
                              { 

                                  if(is_numeric($registro['MONTOCONTRATO']) == FALSE)
                                  {

                                    $registro['MONTOCONTRATO'] = '0';
                                                                 
                                  } 

                                  $values['persla_montocontrato'] = trim($registro['MONTOCONTRATO']);
                              } 

                              
                              $this->_CI->situacionlaboral->registrar($values, false);



                              if( $registro['TIPO_PENSION_ID'] !='0' )
                              {
                                      
                                  if($registro['TIPO_PENSION_ID'] == PENSION_AFP ) 
                                  {
                                            
                                            $afp      =  $registro['AFP_ID'];   
                                            $modo_afp =  $registro['AFP_MODO_ID'];
                                            $aplica_invalidez = ($registro['INVALIDEZ']=='' ? '1' : $registro['INVALIDEZ']);
                                  }
                                  else{
                                         
                                          $modo_afp = '0';
                                          $afp      = '0';
                                          $aplica_invalidez = '1';
                                  }

                                  if($registro['JUBILADO']=='') $registro['JUBILADO'] = '0';

                                  if( AFP_QUITARINVALIDEZ_AUTOMATICO ) $aplica_invalidez = '1';

                                  $this->_CI->persona->add_pension( $id, array(
                                                                              'afp_id'          => $afp,
                                                                              'pentip_id'       => $registro['TIPO_PENSION_ID'],
                                                                              'peaf_codigo'     => $registro['AFP_CUSP'],
                                                                              'peaf_jubilado'   => $registro['JUBILADO'],
                                                                              'afm_id'          => $modo_afp,
                                                                              'peaf_invalidez'  => $aplica_invalidez
                                                                          ) 
                                                              );

                              }
       
                        
                        if( $registro['CUENTA'] != '' )
                        {

                            $this->_CI->persona->add_cuentadeposito( $id, array( 
                                                                            'ebanco_id'           => $registro['BANCO_ID'],
                                                                            'pecd_cuentabancaria' => $registro['CUENTA']
                                                                            )); 
        
                        }
                           
                      
                        
                        
                    }
                   
                   
                   
                   $total_rows_import++;
                

               } /* FIN RECORRIDO DE FILAS*/


 


          if($this->_CI->db->trans_status() === FALSE || $import_ok === FALSE)  
          {
              $this->_CI->db->trans_rollback();
              return array(false, array() );
              
          }
          else
          {
                  
              $this->_CI->db->trans_commit();


              $resumen = array(
                                
                                'total_rows'       => $total_rows_import 
                         );


              return array(true, $resumen);
          } 
    
    
    

   }


   public function set_params_personalizado($config)
   {
    
      if($config['by'] == 'planilla')
      {

         $this->by_planilla = true;
         $this->by_mes = false;

      }
      else if($config['by'] == 'mes')
      {

         $this->by_mes = true; 
         $this->by_planilla = false; 
      }
 
      
      $this->_plati_id = array(9);



      if(sizeof($config['tiposplanilla']) > 0 ) $this->_plati_id = $config['tiposplanilla'];
  


      $this->_vincular_trabajador = $config['vincular'];
 


      if($config['planilla_id'] != '')
      {

          $this->_planilla_especifica = true;
          $this->_planilla_id = $config['planilla_id'];
      }

   }
    

   public function set_params_construccioncivil()
   {

        $this->by_planilla               = true;
        $this->_plati_id                 = array(TIPOPLANILLA_CONSCIVIL);
        $this->_vincular_trabajador      = true;
        $this->_planilla_especifica      = false;  
   }


   public function set_params_mantenimiento()
   {

        $this->by_planilla          = true;
        $this->_plati_id            = array(TIPOPLANILLA_OBRECONTRATADOS);
        $this->_vincular_trabajador = true;
        $this->_planilla_especifica = false; 

   }



   public function explorar_trabajadores($id, $params = array())
   {

         require_once BASEPATH.'../application/libraries/excel_reader2.php';
      

          $info_upload     = $this->get_file_info($id);    
          $file            = $info_upload['archim_file'];
        
         // var_dump($file);
          
          $file_excel      = new Spreadsheet_Excel_Reader("docsmpi/importar/".$file); 
          $file_import_id  = $id;
          
          $hoja            =  0;
          $numero_filas    = $file_excel->rowcount($hoja);
          $numero_columnas = $file_excel->colcount($hoja);

                           // COLUMNA => array(requerido, defecto_valor, $data_model )
          $COLUMNAS = array( 'DNI'        => array(true, '', 'text'),
                             'PATERNO'    => array(true, '', 'text'),
                             'MATERNO'    => array(true, '', 'text'),
                             'NOMBRES'    => array(true, '', 'text'), 
                             'REGIMEN_ID' => array(true, '', 'numeric',true), 
                             'FECHAINI'   => array(true, '', 'date'),  
                             'FECHAFIN'   => array(false, '', 'date'),
                             'MONTOCONTRATO' => array(false, '0', 'numeric'),
                             'SEXO_ID'       => array(false, '0', 'numeric',true), 
                             'ESTADOCIVIL_ID'=> array(false, '0', 'numeric',true),
                             'FECHA_NACIMIENTO' => array(true, '', 'date'), 
                             'DIRECCION' => array(false, '', 'text'), 
                             'TELEFONO'  => array(false, '', 'text'), 
                             'EMAIL'     => array(false, '', 'text'), 
                             'ESSALUD'   => array(false, '', 'text'), 
                             'CUENTA'    => array(false, '', 'text'), 
                             'BANCO_ID' => array(false, '0', 'numeric' ,true), 
                             'TIPO_PENSION_ID' => array(true, '1', 'numeric' ,true ), 
                             'AFP_ID' => array(false, '0', 'numeric' ,true, array('TIPO_PENSION_ID', PENSION_AFP, '0' ) ), 
                             'AFP_CUSP' => array(false, '', 'text'), 
                             'AFP_MODO_ID' => array(false, '1', 'numeric' ,true, array('TIPO_PENSION_ID', PENSION_AFP, '0' )  ),
                             'INVALIDEZ' => array(false, '1', 'binari', false,  array('TIPO_PENSION_ID', PENSION_AFP, '0' )  ), 
                             'JUBILADO' => array(false, '0', 'binari'  ) 
                            );
          

          $REQUERIDO_INDICE = 0;
          $VALORDEFECTO_INDICE = 1;
          $TIPODATO_INDICE =2;
          $MODELO_INDICE =3;
          $MODELO_INDICE_RESTRICT = 4;

          $MODELOS = array(); /*
          $MODELOS['REGIMEN_ID'] = array();
          $MODELOS['SEXO_ID'] = array(1,2); 
          $MODELOS['ESTADOCIVIL_ID'] = array(0,1,2,3,4,5);
          $MODELOS['BANCO_ID'] = array();
          $MODELOS['TIPO_PENSION_ID'] = array(1,2);
          $MODELOS['AFP_ID'] = array();
          $MODELOS['AFP_MODO_ID'] = array(1,2);
*/
  

          $MODELOS['REGIMEN_ID'] = array();

          $MODELOS['SEXO_ID'] = array('1' => 'Masculino',
                                      '2' => 'Femenino' );


          $MODELOS['ESTADOCIVIL_ID'] = array( '0' => 'No especificado ',
                                              '1' => 'Soltero(a) ',
                                              '2' => 'Casado(a)',
                                              '3' => 'Divorciado(a) ',
                                              '4' => 'Viudo(a) ' );
 
          $MODELOS['BANCO_ID'] = array();

          $MODELOS['TIPO_PENSION_ID'] = array( '0' => 'NO AFILIADO',
                                               '1' => 'ONP',
                                               '2' => 'AFP' );
          
          $MODELOS['AFP_ID'] = array();
          
          $MODELOS['AFP_MODO_ID'] = array( '1' => 'FLUJO',
                                           '2' => 'SALDO' );



          $sql  = " SELECT plati_id, plati_nombre FROM planillas.planilla_tipo plati WHERE plati_estado = 1 ORDER BY plati_id ";
          $rs = $this->_CI->db->query($sql, array())->result_array(); 

          foreach ($rs as $reg)
          {
              $MODELOS['REGIMEN_ID'][$reg['plati_id']] = $reg['plati_nombre'];
          }

          $sql  = " SELECT ebanco_id, ebanco_nombre FROM public.entidades_bancarias WHERE ebanco_estado = 1 ORDER BY ebanco_id ";
          $rs = $this->_CI->db->query($sql, array())->result_array(); 

          foreach ($rs as $reg)
          {
              $MODELOS['BANCO_ID'][$reg['ebanco_id']] = $reg['ebanco_nombre'];
          }

         

          $sql  = " SELECT afp_id, afp_nombre FROM rh.afp WHERE afp_estado = 1 ORDER BY afp_id ";
          $rs = $this->_CI->db->query($sql, array())->result_array(); 

          foreach ($rs as $reg)
          {
              $MODELOS['AFP_ID'][$reg['afp_id']] = $reg['afp_nombre'];  
          }



          $indices_requeridos = array('');
          $log = array();
          $conforme = true;
          $indices = array('');


          $columnas_estructura = array_keys($COLUMNAS);
          $error_estructura = false;

          for($i=1; $i<=$numero_columnas; $i++ )
          {
              $col_header =  strtoupper(trim($file_excel->val(1,$i) ));
              
              if($col_header != $columnas_estructura[($i-1)] ) 
              {
                  $error_estructura = true;
              }  
          }


          if($error_estructura)
          {
              $conforme = false;
              $log[] = array('registro' => 0, 'mensaje' => 'LA ESTRUCTURA DEL ARCHIVO NO ES LA CORRECTA, VERIFIQUE QUE SE ENCUENTREN TODAS LAS COLUMNAS EN EL ORDEN ESPECIFICADO' );

          }

          foreach ($COLUMNAS as $col => $params)
          {
              
              $indices[] = $col;

              if($params[$REQUERIDO_INDICE]) // Verificar el parametro para saber si el campo es o no requerido
              { 

                  $indices_requeridos[] = $col;

                  $encontrado = false;

                  for($i=1; $i<=$numero_columnas; $i++ )
                  {
                      $col_header =  strtoupper(trim($file_excel->val(1,$i) ));
                      if($col_header == $col)
                      {
                          $encontrado = true;
                          break;
                      } 
                  }

                  if($encontrado == FALSE)
                  {
                     $log[] = array('registro' => 0, 'mensaje' => 'SE NECESITA LA COLUMNA: '.$col );
                     $conforme = false;
                  }
              }

          } 




          //COMPROBANDO QUE EXISTAN LAS COLUMNAS REQUERIDAS

          if($conforme)
          { 


              $html_excel = "   
                              <div style='width:600px; height: 400px; border:1px solid #988888'>

                                 <table id='table_resumen_xls' cellpadding='2' border='1' class='_tablepadding4' >  
                                   
                                   <tr class='tr_header_celeste'>
                                       <td> # </td>
                             ";



              /*
                 IMPRIMIMOS EL HEADER CON LAS COLUMNAS IMPORTANTES COLOREADAS
              */

              for($c = 1; $c<= $numero_columnas; $c++ )
              {
              
                   $celda   = $file_excel->val('1',$c);
                   $html_excel.=' <td ';

                   
                   if( in_array($indices[$c], $indices_requeridos ))
                   {
                       $html_excel.=' style="background-color:#336699; color:white;" ';
                   } 
              
                   $html_excel.=' /> '.$celda;

                   $html_excel.= ' </td> ';

                    if($COLUMNAS[$indices[$c]][$MODELO_INDICE]=== TRUE)
                    {
                          $html_excel.='<td> </td>';
                    }

 
              }

              $html_excel.=" </tr> ";
 
              $explorer = array();
  
              $_styles = array(

                               '_alert' => ' style="background-color:#990000; color:white;" ' 

                              );
              
              
              $count = 0; 
              

              /* INCIAMOS RECORRIDO DE FILAS */

              $dni_archivo = array();

              for($row = 2; $row <= $numero_filas;  $row++ )
              {
                    
                  $count++;
                  
                  $registro = array('');  

                  for($col = 1; $col <= $numero_columnas; $col++)
                  {
                     $registro[$indices[$col]] = trim($file_excel->val($row,$col)); 

                  }

                 

                  // Nos dentenemos si toda la fila esta vacia
                  $clean = true;
                  foreach($registro as $k => $d)
                  {
                      if(trim($d) != '') $clean = false;
                  }
                  if($clean) break;



                  $html_excel.=" <tr class='tr_row_celeste'> 
                                     <td>
                                       ".$count."
                                     </td> 
                               ";
              
                    
                    $dni = $registro['DNI'];

                    $dni_info =  $this->get_dni($dni);  
                    $existe_dni = false;

                    if( sizeof($dni_info) > 0)
                    {
                        $existe_dni = true;
                    }


                    /* INICIAMOS RECORRIDO AUTOMATICO  DE COLUMNAS */

                    for($col = 1; $col <= $numero_columnas; $col++ )
                    { 

                        $celda = trim($registro[$indices[$col]]);
                        $requerida = false;
                        $label_modelo = '';

                        $html_excel.=" <td  ";


                        if( strtoupper($indices[$col]) == 'DNI')
                        {

                            if($existe_dni)
                            {
                               $html_excel.=$_styles['_alert'];   
                               $log[] = array('registro' => $count, 'mensaje' => 'El DNI ya esta registrado en el sistema, '.$indices[$col] );                           
                               $conforme = false;
                            }


                            if( in_array($registro['DNI'], $dni_archivo) )
                            {
                                $html_excel.=$_styles['_alert'];   
                                $log[] = array('registro' => $count, 'mensaje' => 'El DNI figura mas de una vez en el archivo, '.$indices[$col] );                           
                                $conforme = false;
                            }
                            else
                            {
                               $dni_archivo[] = $registro['DNI'];
                            }

                            if( strlen($registro['DNI']) != NUMERO_CARACTERES_DNI ) 
                            {
                               $html_excel.=$_styles['_alert'];   
                               $log[] = array('registro' => $count, 'mensaje' => 'No es un número de DNI valido, '.$indices[$col] );                           
                               $conforme = false;   
                            }

                        }


                        //Si la columna es requerida y esta vacia
                        if( in_array($indices[$col], $indices_requeridos ) && $celda == ''  )
                        {
                            $html_excel.=$_styles['_alert'];   
                            $log[] = array('registro' => $count, 'mensaje' => 'No se especifico el valor de la columna '.$indices[$col] );                           
                            $conforme = false;

                            $requerida = true;
                        }
                
                        $tipo_dato_columna = $COLUMNAS[$indices[$col]][$TIPODATO_INDICE];
                        

                        if($celda != '')
                        {

                            if($tipo_dato_columna == 'numeric')
                            {

                                 if(is_numeric($celda) == FALSE && $celda != '')
                                 {
                                     $html_excel.=$_styles['_alert'];   
                                     $log[] = array('registro' => $count, 'mensaje' => 'Se esperaba un valor numerico en la columna '.$indices[$col] );                           
                                     $conforme = false;
                                 } 

                            }
                            else if($tipo_dato_columna == 'date')
                            {
                        
                                /*if(validateDate($celda, 'Y-m-d' ) == FALSE && validateDate($celda, 'd/m/Y' ) == FALSE )
                                {
                                    $html_excel.=$_styles['_alert'];   
                                    $log[] = array('registro' => $count, 'mensaje' => 'Se esperaba una fecha en formato Y-m-d o d/m/Y '.$indices[$col] );                           
                                    $conforme = false;
                                }*/

                                if($celda != '')
                                {
 
                                    $chkdate = true;
                                   
                                    if(strpos( $celda, '-') > 0 )
                                    {
                                         list($dia, $mes, $anio) = explode('-',$celda);

                                         if( strlen($dia) == 4 )
                                         {
                                             list($anio, $mes, $dia) = explode('-',$celda);
                                         }
                                    } 
                                    else if(strpos( $celda, '/') > 0 )
                                    {
                                                
                                        list($dia, $mes, $anio) = explode('/',$celda);

                                        if(strlen($dia) > 2 || strlen($mes) > 2 || strlen($anio) > 4 ) $chkdate = false;

                                    }
                                    else
                                    {
                                         $chkdate = false;
                                    }
        

                                    if( checkdate($mes, $dia, $anio) == FALSE || $chkdate === FALSE )
                                    {
                                       $html_excel.=$_styles['_alert'];   
                                       $log[] = array('registro' => $count, 'mensaje' => 'No es una fecha valida '.$indices[$col] );                           
                                       $conforme = false;
                                    }

                               }

                            }
                            else if($tipo_dato_columna == 'binari')
                            {
                         
                                if( $celda != '1' && $celda != '0' )
                                {
                                    $html_excel.=$_styles['_alert'];   
                                    $log[] = array('registro' => $count, 'mensaje' => ' Valores permitidos 1 o 0 '.$indices[$col] );                           
                                    $conforme = false;
                                }

                            }

                            $modelo_restrict = $COLUMNAS[$indices[$col]][$MODELO_INDICE_RESTRICT];
 
                            if($COLUMNAS[$indices[$col]][$MODELO_INDICE]=== TRUE)
                            {
  
                                if( $celda != '' && in_array($celda,  array_keys($MODELOS[$indices[$col]]) ) == FALSE )
                                {
                                    
                                    if(is_array($modelo_restrict) === FALSE || (is_array($modelo_restrict) === TRUE && ($registro[$modelo_restrict[0]] == $modelo_restrict[1]) ) )
                                    {
  
                                      $html_excel.=$_styles['_alert'];   
                                      $log[] = array('registro' => $count, 'mensaje' => 'No es un valor permitido '.$indices[$col] );                           
                                      $conforme = false;
                                
                                    }                                    
                                  
                                }
                                else
                                {
                                   $label_modelo = $MODELOS[$indices[$col]][$celda];  
                                   
                                }

                              
                            }
                            



                        }
                        else
                        {

                            if($requerida == false)
                            {
                               $default =   $COLUMNAS[$indices[$col]][$VALORDEFECTO_INDICE];
                               $celda  = $default;
                            }

                            $modelo_restrict = $COLUMNAS[$indices[$col]][$MODELO_INDICE_RESTRICT];

                            

                        }

                        if(is_array($modelo_restrict) === TRUE && ($registro[$modelo_restrict[0]] != $modelo_restrict[1]) )
                        {
                            $celda = $modelo_restrict[2];
                            $label_modelo = '';
                        }
 

                        $html_excel.="  >".$celda."</td>";
                        
                        if($COLUMNAS[$indices[$col]][$MODELO_INDICE]=== TRUE)
                        {

                           $html_excel.=" <td>".$label_modelo."</td>";
                        }

                    }

                  
                  $html_excel.=" </tr> ";


              


              
              }  

              



              $html_excel.="   </table> 
                      
                               <input type='hidden' value='1' id='hbxlstable_loaded' />
                            </div> ";




          }


  
         

         $data = array(
   
                    'registros'          => $count

                 );

         return array($conforme, $html_excel, $log, $data);
      
      

   }


}
