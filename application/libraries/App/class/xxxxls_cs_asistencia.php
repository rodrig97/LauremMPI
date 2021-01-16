<?php

class xls_cs_asistencia extends xlsimportacion{
     
 
    public $_variables            = array();
    
    public $by_planilla           = true;
    public $by_mes                = false;
 
    private $_columnas            = array();
    private $_columnas_variables  = array();
    private $_columnas_datos      = array();
    private $_columna_planilla    = 0;    
    private $_columna_mes         = 0;
    private $_columna_dni         = 0;
    private $_columna_subtipo     = 0;
    private $_plati_id            = 9; 
    private $_planilla_especifica = false;    
    private $_vincular_trabajador = true; // SI ES TRUE ENTONCES LE BUSCA UNA PLANILLA AL TRABAJADOR
    private $_subtipo             = true;


    public function __construct(){
          
        parent::__construct();
          
    }

    public function get_file_info($id){

        $sql = " SELECT * FROM  planillas.archivos_importacion WHERE archim_id = ? LIMIT 1";
        $rs  = $this->_CI->db->query($sql, array($id))->result_array();

        return $rs[0];

    }  
  

    public function validar_mes($mes)
    {
 
       $meses = array('01','02','03','04','05','06','07','08','09','10','11','12');

       return (in_array($mes, $meses)) ? true : false;

    }
  

    public function get_planilla($pla , $codigo = true)
    {
 
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
              
              $sql = " SELECT  ind.*,  
                             ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo, 
                             pla.pla_id, pla.pla_mes, plaemp.plaemp_id, plaemp.platica_id,
                             plati.plati_nombre as regimen
                      FROM (
                          SELECT indiv_id, indiv_appaterno, indiv_apmaterno, indiv_nombres, indiv_dni  
                          FROM public.individuo
                          WHERE indiv_dni = ? LIMIT 1

                      ) as ind  
                      LEFT JOIN rh.persona_situlaboral persla ON persla.pers_id = ind.indiv_id AND persla.persla_ultimo = 1 
                      LEFT JOIN planillas.planilla_empleados plaemp ON ind.indiv_id = plaemp.indiv_id AND plaemp.plaemp_estado = 1 
                   ";

                    $params = array($dni);

                    if($subtipo != '')
                    { 
                         
                        $sql.= " AND plaemp.platica_id = ? ";
                        $params[] = $subtipo;
                    }


             $sql.= " 
                      LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id =plati.plati_id 
                      LEFT JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id ";
 

                      if($planilla_codigo != '' && $mes == '' )
                      {
                          $sql.= " AND ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  = ? ";
                          $params[] = $planilla_codigo;
                      } 

                      if($mes != '')
                      {
                          $sql.=" AND pla_mes = ? ";
                          $params[] = $mes;
                      }     


             $sql.="          
                      LEFT JOIN planillas.planilla_movimiento mov ON mov.pla_id = pla.pla_id AND mov.plamo_estado = 1  AND plaes_id = ? 
                      
                    ";

              $sql.= " WHERE persla.persla_estado = 1  "; 

              $params[] = ESTADOPLANILLA_ELABORADA;

            

               

              $rs = $this->_CI->db->query($sql, $params )->result_array();

              return $rs;

         }
          
       
    }

    public function get_tipostrabajador($plati){


        $sql = " SELECT cstt_id FROM planillas.planilla_ocupaciones WHERE plati_id = ?  AND  cstt_estado  = 1 ";
        $rs = $this->_CI->db->query($sql, array($plati))->result_array();

        $tipos = array();

        foreach($rs as $reg)
        {
          $tipos[] = $reg['cstt_id'];
        }

        return $tipos;

    }



    public function get_indiv_id($dni)
    {
        
      $sql = " SELECT indiv_id FROM public.individuo  WHERE indiv_dni = ? AND indiv_estado = 1 ";

      $rs = $this->_CI->db->query($sql, array($dni))->result_array();

      return $rs[0]['indiv_id'];
    }

    
    public function get_pla_id($codigo)
    {
    
        $sql = " SELECT pla_id FROM planillas.plaillas 
                 WHERE ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id   ) = ? 
               ";

        $rs = $this->_CI->db->query($sql, array($codigo))->result_array();            

        return $rs[0]['pla_id'];
    }




    public function explorar($id, $params = array()){


        $this->_CI->load->library( array('App/variable',
                                         'App/xlsimportacion',  
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

        if($this->_subtipo)
        {

            $tipos_trabajador = $this->get_tipostrabajador($this->_plati_id);

        }


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
            else if( $this->_subtipo && $col == 'TIPO')
            {

                $this->_columna_subtipo = $i;
                $this->_columnas[]      = $i;     

            }
            else if( $this->by_mes && $col == 'MES')
            {
                
                $this->_columna_mes = $i;
                $this->_columnas[]  = $i;

            }
            else{

                $f = strpos( $col, 'VAR_');

                if($f)
                {

                    $this->_columnas_variables[$col] = $i;
                    $this->_columnas[]           = $i;

                }


                $f = strpos( $col, 'DATO_');

                if($f)
                {

                    $this->_columnas_datos[$col] = $i;
                    $this->_columnas[]           = $i;

                }
 
            }
 
        }
 

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

                           <table id='tbl_cs_xls_tareo_view' cellpadding='2' border='1' class='_tablepadding4' >  
                             
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

      
        $explorer = array(
                          'planillas_found'      => array(),
                          'planillas_unfound'    => array(),
                          'trabajadores_found'   => array(),
                          'trabajadores_unfound' => array() 
                         );
 


        $_styles = array(

                         '_alert' => ' style="background-color:#990000; color:white;" ' 

                        );
 

        $conforme = false;


        if($this->_planilla_especifica)
        {
          
            $planilla_id = $params['planilla'];
            $planilla_info = $this->planilla_info($planilla_id, false);
 
        }


        $count = 0; 

        $log = array();


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
          
            if($this->_subtipo) $subtipo = $registro[$this->_columna_subtipo];
            
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
                              
                             /*if( $this->_planilla_especifica == FALSE)
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
                                          // TIPO DE TRABAJO
                                          
                                          $indiv_info    = $this->get_dni($celda);
                                          $mensaje_error = '';
                                          $error_1       = false;
                                        

                                          if( $indiv_info['indiv_id'] == null || $indiv_info['indiv_id'] == ''  )
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
                                          $subtipo = '';

                                          if($this->_subtipo)
                                          {

                                              $subtipo = $registro[$this->_columna_subtipo];

                                          }
                                          
                                         
                                          $indiv_info    =  $this->get_dni($celda, $planilla_info['pla_codigo'], '', $subtipo);
                                          $mensaje_error =  '';
                                          $error_1       =  false;
                                          
                                          
                                          if(sizeof($indiv_info) == 0  )
                                          { 
                                             $error_1 = true;
                                             $mensaje_error = 'El DNI no esta registrado';
                                          }
                                          else if(sizeof($indiv_info) == 1 && $indiv_info[0]['pla_id'] == '')
                                          { 
                  
                                             $error_1 =  true;
                                             $mensaje_error = 'El trabajador no esta vinculado a la planilla'; 
                  
                                          }
                                          else if(sizeof($indiv_info) > 1)
                                          { 
                  
                                             $error_1 =  true;
                                             $mensaje_error = 'El Trabajador esta vinculado a varias planillas y no es posible determinar a cual se importaran los datos'; 
                  
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
                                 
                               $subtipo = '';
                               
                               if($this->_subtipo)
                               {
                  
                                  $subtipo = $registro[$this->_columna_subtipo];

                               }               
                               
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
                               else if(sizeof($indiv_info) > 1)
                               {
                                 
                                 $error_1 =  true;
                                 $mensaje_error = 'El Trabajador esta vinculado a varias planillas y no es posible determinar a cual se importaran los datos';
                               
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



                   $html_excel.= ">".$celda."  ".$html_extra." </td> ";     
 
                   if($col == $this->_columna_dni)
                   {
                      $html_excel.='<td> '.$nombre_completo.'</td>';
                      $html_excel.='<td> '.$regimen.'</td>';
                   }
                  

              }

            
            $html_excel.=" </tr> ";
 
        }

        $html_excel.="   </table> 
                      </div> ";


        return array($conforme, $html_excel, $log);
 
    }



    public function importar($id, $params = array() )
    {


        $this->_CI->load->library( array('App/variable',
                                         'App/xlsimportacion',  
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
            


        $columna_a_variable = array('VAR_DIAS' => '',
                                    'VAR_DIAF' => '' );


        if($this->explorar())
        {
 
            for($row=2; $row<=$numero_filas; $row++)
            { 

                $indiv_dni  = $file_excel->val($row, $this->_columna_dni); 
                $indiv_id   = $this->get_indiv_id($indiv_dni);
                
                $pla_codigo = $file_excel->val($row, $this->_columna_planilla);
                $pla_id     = $this->get_pla_id($pla_codigo);

                $tipo       = $file_excel->val($row, $this->_columna_subtipo);


                list($plaemp_id, $plaemp_key) = $this->planillaempleado->registrar( $planilla_id, $indiv_id , $tipo, TRUE );  
 

                foreach( $columna_a_variable as $key => $vari )  
                {
                   
                      $value = $file_excel->val($row, $this->_columnas_variables[$key] );
 
                      if($value!= '' && is_numeric($value))
                      { 

                          $this->planillaempleadovariable->registrar( array(
                                                                              'plaemp_id'          => $plaemp_id,
                                                                              'vari_id'            => $vari_id,
                                                                              'plaev_valor'        => $valor,
                                                                              'plaev_procedencia'  => '',
                                                                              'plaev_displayprint' => '',
                                                                              'archim_id'          => $file_import_id
                                                                            ));
                      }  

                }
 
            }
 
        }
        else
        {



        }

    }
   
}
