<?PHP
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class migrar extends CI_Controller {
    
    public $usuario;
    
    public function __construct(){
        parent::__construct();
       
        
        
    }
  
    public function test_trans()
    { 

       $t = ignore_user_abort();
       var_dump($t);

       exit();
       
       $this->db->trans_begin();
 
       $sql =" INSERT INTO planillas.firmas(fiti_id) VALUES(9) ";
       $this->db->query($sql);

       $this->test2();
 
       $sql =" INSERT INTO planillas.firmas(fiti_id) VALUES(11) ";
       $this->db->query($sql);
      


       var_dump($this->db->trans_status());
     
      if ($this->db->trans_status() === FALSE)
      {
          $this->db->trans_rollback();
         echo 'Im here false <br/>';
      }
      else
      {
          $this->db->trans_commit();
          $ok = true;
          echo 'Im here true<br/>';
      }    
     
    }


    public function test2()
    {
   
       $sql =" INSERT INTO planillas.firmas(fiti_id) VALUES(1x0) ";
       $this->db->query($sql);

   //     $this->db->trans_complete(); 
      
        // $this->db->trans_rollback();

        
 /*     if ($this->db->trans_status() === FALSE)
      {
          $this->db->trans_rollback();
          echo 'nf <br/>';
      }
      else
      {
          $this->db->trans_commit();
          $ok = true;
          echo 'nt <br/>';
      }    */
    }


    public function generar_individuos_x()
    {
        
      
        $this->load->library(array('db/public/individuo','App/situacionlaboral','App/pension','App/segurosalud'));
         
        // Codigos de situaciones laborales
        $codigos = array(
            
                '1' => '2',     // Nombrados
                '2' => '3',     // Empleado contratado
                '3' => '4',     // Obrero Nombrado
                '4' => '5',     // Obrero Contratado
                '5' => '1',     // Empleado de Inversiones
                '6' => '9',     // Construccion civil
                '7' => '11',    // Pensionista
                '8' => '10',    // Regidor
                '9' => '6',     // Cas Inversiones
                '0' => '7',     // Cas funcionamiento
                'A' => '8'      // Practicante
        );
        
        $estado_civil = array(
            
                'SOLTERO'    => '1',
                'CASADO'     => '2',   
                'SOLTERA'    => '1',
                'CASADA'     => '2',
                'VIUDO'      => '4',
                'VIUDA'      => '4',
                'DIVORCIADO' => '3',
                'DIVORCIADA' => '3',
                'CONVIVIENT' => '5'
            
        );
        
        $afps_ids = array(
            
             '02' => '2', //  Integra
             '03' => '1', // profuturo
             '04' => '4', // horizonte
             '06' => '3'  // Prima
				
        );
		

        $sql = " SELECT distinct(elec) as dni FROM pla.pla005 "; // Obteniendo los DNIS 
         
        $empleados = $this->db->query($sql)->result_array(); // Por cada DNI buscaremos los registros
       
        echo "Encontrados : ".sizeof($empleados)." registros de DNIS diferentes <br/><br/>";
        
        foreach($empleados as $empleado ){  // Recorremos los DNIS
            


            $sql = " SELECT * FROM public.individuo WHERE indiv_dni = ? LIMIT 1 ";
            $existente = $this->db->query($sql, array($empleado['dni']) )->result_array();

            if($existente['indiv_dni'] != ''){

                  $existe = true;  
            } 

            $sql = " SELECT * FROM pla.pla005 where elec = ? ORDER BY row_id desc " ;  // Ordenando para obtener el ultimo ingresado, primero
            $registros_empleado = $this->db->query($sql, array($empleado['dni']) )->result_array();
             
            $indiv_id = '';
            
            foreach($registros_empleado as $k => $data_empleado){ // Foreach registros del empleado
                
                 if($k == 0){  // SI K es cero entonces genera la data base en individuo
                     
                    $values =  array(

                        'indiv_appaterno'       =>  trim($data_empleado['apno']),
                        'indiv_apmaterno'       =>  trim($data_empleado['mate']),
                        'indiv_nombres'         =>  trim($data_empleado['nomb']),
                        'indiv_telefono'        =>  trim($data_empleado['tele']), 
                        'indiv_dni'             =>  trim($data_empleado['elec']),
                        'indiv_libmilitar_cod'  =>  trim($data_empleado['mili']),
                     
                        'indiv_estadocivil'     =>  $estado_civil[trim($data_empleado['esta'])],
                        'indiv_direccion1'      =>  trim($data_empleado['dire']),
                        'migrado_codi'          =>  trim($data_empleado['codi']),
                        'indti_id'              => '1'
                             
                    );
 
                   if(trim($data_empleado['fena']) != '') $values['indiv_fechanac'] =  trim($data_empleado['fena']);
                    
                   list($indiv_id,$key) =  $this->individuo->registrar($values,true); // Insertamos el registro base table individuo

                        /* AFP / ONP */

                        $values_p = array(

                                    'pers_id'       => $indiv_id,
                                    'afp_id'        => (trim($data_empleado['cafp']) == '') ? '0' : $afps_ids[trim($data_empleado['afp'])],
                                    'pentip_id'     => (trim($data_empleado['cafp']) == '') ? '1' : '2', // 1 : ONP , 2: AFP
                                    'peaf_codigo'   => (trim($data_empleado['cafp']) == '') ? ''  : trim($data_empleado['cafp']),
                                    'migrado_codi'  => trim($data_empleado['codi']) 

                        );

                        $this->pension->registrar($values_p);


                        /* SEGURO DE SALUD ESSALUD */ 

                        if(trim($data_empleado['ipss']) != ''){
                            $values_sa = array(

                                        'pers_id'        => $indiv_id,
                                        'persa_codigo'   => trim($data_empleado['ipss']),
                                        'migrado_codi'   => trim($data_empleado['codi']) 

                            );

                            $this->segurosalud->registrar($values_sa);
                        }
                        
                        /* BUSCAR SI TIENE USUARIO ACTUAL Y COLOCAR EL CAMPO INDIV_ID */ 
                        
                        if(trim($empleado['dni']) != '')
                        {
                            $sql = " UPDATE  \"public\".v001 SET indiv_id = ? WHERE ndoc = ? ";
                            $this->db->query($sql, array($indiv_id, trim($empleado['dni']) ) );
                        }
                        
                        
                 } // FIN K == 0  
                 
                 // generamos el registro de situacion laboral
                 $values_h = array(
                        
                            'pers_id'               => $indiv_id,
                            'plati_id'              => $codigos[trim($data_empleado['tita'])],
                          
                            'persla_estado'         => '1',
                            'persla_vigente'        => '2', // 1: vigente 0: no vigente 2: no precisado
                            'persla_descripcion'    => trim($data_empleado['ocup']).' '.trim($data_empleado['nive']),
                            'persla_generado'       => '1',
                            'persla_ultimo'         => ($k==0) ? '1' : '0',
                            'persla_porcompletar'   => '1',
                            'migrado_codi'          => trim($data_empleado['codi']) 

                 );
                 
                 if(trim($data_empleado['fein']) != '') $values_h['persla_fechaini'] =  trim($data_empleado['fein']);
                 
                 $this->situacionlaboral->registrar($values_h);
                
                
            }
            
          
        }  // FIN foreach DNI's
        
        /*
        
        $sql = "INSERT INTO public.individuo() ";
        
        
           $sql ="SELECT * FROM public.v001 ";
        
        $sql = "INSERT INTO "; */ 
        
    }










     /* FUNCIONA */
    public function generar_individuos_2(){
        
      
        $this->load->library(array('db/public/individuo','App/situacionlaboral','App/pension','App/segurosalud','App/persona'));
         
        // Codigos de situaciones laborales
        $codigos = array(
            
                '1' => '2',     // Nombrados
                '2' => '3',     // Empleado contratado
                '3' => '4',     // Obrero Nombrado
                '4' => '5',     // Obrero Contratado
                '5' => '1',     // Empleado de Inversiones
                '6' => '9',     // Construccion civil
                '7' => '11',    // Pensionista
                '8' => '10',    // Regidor
                '9' => '6',     // Cas Inversiones
                '0' => '7',     // Cas funcionamiento
                'A' => '8'      // Practicante
        );
        
        $estado_civil = array(
            
                'SOLTERO'    => '1',
                'CASADO'     => '2',   
                'SOLTERA'    => '1',
                'CASADA'     => '2',
                'VIUDO'      => '4',
                'VIUDA'      => '4',
                'DIVORCIADO' => '3',
                'DIVORCIADA' => '3',
                'CONVIVIENT' => '5'
            
        );
        
        $afps_ids = array(
            
             '02' => '2', //  Integra
             '03' => '1', // profuturo
             '04' => '4', // horizonte
             '06' => '3'  // Prima
            
        );


           $bancos = array(
         
                 'S' => '6',
                 'N' => '4',
                 'C' => '7',
                 'I' => '5'


            );
         
        $sql = " SELECT distinct(elec) as dni FROM pla.pla005 "; // Obteniendo los DNIS 
         
        $empleados = $this->db->query($sql)->result_array(); // Por cada DNI buscaremos los registros
       
        echo "Encontrados : ".sizeof($empleados)." registros de DNIS diferentes <br/><br/>";
        
        $c_e = 0;

        foreach($empleados as $empleado ){  // Recorremos los DNIS

            $c_e++;
            $c_re = 0;

            echo '<br/>Registro de empleado: '.$c_e.'<br/>';
            
            $existe = false;    
            $indiv_id = '';

            $sql = " SELECT * FROM public.individuo WHERE indiv_dni = ? LIMIT 1 ";
            $existente = $this->db->query($sql, array($empleado['dni']) )->result_array();

            if( trim($existente[0]['indiv_id']) != ''){

                  $existe = true;  
                  $indiv_id = $existente[0]['indiv_id'];
            } 

            $sql = "SELECT e.*, t.deta,
                          (CASE WHEN e.fein is null THEN  
                         e.fesa
                      ELSE
                               e.fein
                      END 
                          ) as fechia

                    from pla.pla005  e 
                    left join pla.pla000 t
                    on e.tita = t.tipo   
                    where elec = ? order by fechia desc;

                  " ;  // Ordenando para obtener el ultimo ingresado, primero
            $registros_empleado = $this->db->query($sql, array($empleado['dni']) )->result_array();
        
            
            foreach($registros_empleado as $k => $data_empleado){ // Foreach registros del empleado
                
                $c_re++;

                echo 'I: '.$c_re.'<br/>';
                 if($c_re == 1 && !$existe){  // SI K es cero entonces genera la data base en individuo
                             
                            $values =  array(

                                'indiv_appaterno'       =>  trim($data_empleado['apno']),
                                'indiv_apmaterno'       =>  trim($data_empleado['mate']),
                                'indiv_nombres'         =>  trim($data_empleado['nomb']),
                                'indiv_telefono'        =>  trim($data_empleado['tele']), 
                                'indiv_dni'             =>  trim($data_empleado['elec']),
                                'indiv_libmilitar_cod'  =>  trim($data_empleado['mili']),
                             
                                'indiv_estadocivil'     =>  $estado_civil[trim($data_empleado['esta'])],
                                'indiv_direccion1'      =>  trim($data_empleado['dire']),
                                'migrado_codi'          =>  trim($data_empleado['codi']),
                                'indti_id'              => '1'
                                     
                            );
         
                           if(trim($data_empleado['fena']) != '') $values['indiv_fechanac'] =  trim($data_empleado['fena']);
                            
                           list($indiv_id,$key) =  $this->individuo->registrar($values,true); // Insertamos el registro base table individuo
 
                 } // FIN K == 0


                 if($c_re == 1){

                          /* AFP / ONP */
                          
                                $modo_afp = array('F' => '1',
                                                  'N' => '1',
                                                  'S' => '2');   


                                $values_p = array(

                                            'pers_id'      => $indiv_id,
                                            'afp_id'       => (trim($data_empleado['cafp']) == '') ? '0' : $afps_ids[trim($data_empleado['afp'])],
                                            'pentip_id'    => (trim($data_empleado['cafp']) == '') ? '1' : '2', // 1 : ONP , 2: AFP
                                            'peaf_codigo'  => (trim($data_empleado['cafp']) == '') ? ''  : trim($data_empleado['cafp']),
                                            'afm_id'       => (trim($data_empleado['afpmod']) == '') ? '0'  : $modo_afp[trim($data_empleado['afpmod'])],
                                            'migrado_codi' => trim($data_empleado['codi']) 

                                );

                                $this->pension->registrar($values_p);
 

                                /* SEGURO DE SALUD ESSALUD */ 

                                if(trim($data_empleado['ipss']) != ''){
                                    $values_sa = array(

                                                'pers_id'        => $indiv_id,
                                                'persa_codigo'   => trim($data_empleado['ipss']),
                                                'migrado_codi'   => trim($data_empleado['codi']) 

                                    );

                                    $this->segurosalud->registrar($values_sa);
                                }

                              
                                if(trim($data_empleado['banco']) != ''){ 

                                         $values = array(
                             
                                             'pers_id' =>$indiv_id,
                                             'pecd_cuentabancaria' => $data_empleado['cta'],
                                             'ebanco_id' => $bancos[$data_empleado['banco']],
                                             'migrado_codi' => trim($data_empleado['codi']) 

                                         ); 
                        //var_dump($data_empleado);
                                          $this->persona->add_cuentadeposito(  $indiv_id, $values);

                                 }

                                
                                /* BUSCAR SI TIENE USUARIO ACTUAL Y COLOCAR EL CAMPO INDIV_ID */ 
                                
                                if(trim($empleado['dni']) != '')
                                {
                                    $sql = " UPDATE  \"public\".v001 SET indiv_id = ? WHERE ndoc = ? ";
                                    $this->db->query($sql, array($indiv_id, trim($empleado['dni']) ) );
                                }


                            


                 }



                 
                 // generamos el registro de situacion laboral
                 $values_h = array(
                        
                            'pers_id'               => $indiv_id,
                            'plati_id'              => $codigos[trim($data_empleado['tita'])],
                          
                            'persla_estado'         => '1',
                            'persla_vigente'        => '2', // 1: vigente 0: no vigente 2: no precisado
                            'persla_descripcion'    => trim($data_empleado['ocup']).' '.trim($data_empleado['nive']),
                            'persla_generado'       => '1',
                            'persla_ultimo'         => ($k==0) ? '1' : '0',
                            'persla_porcompletar'   => '1',
                            'migrado_codi'          => trim($data_empleado['codi']) 

                 );
                 
                 if(trim($data_empleado['fein']) != '') $values_h['persla_fechaini'] =  trim($data_empleado['fein']);

                 if(trim($data_empleado['fesa']) != '') $values_h['persla_fechafin'] =  trim($data_empleado['fesa']);
                    
             //       var_dump($values_h);
                 $this->situacionlaboral->registrar($values_h);
                
                
            }
            
          
        }  // FIN foreach DNI's
        
        /*
        
        $sql = "INSERT INTO public.individuo() ";
        
        
           $sql ="SELECT * FROM public.v001 ";
        
        $sql = "INSERT INTO "; */ 
        
    }


    public function migrar_variables_conceptos(){


            $this->load->library(array('App/empleadovariable', 'App/empleadoconcepto'));

            $_DATOS = array();

            $sql ="  SELECT * FROM public.individuo ind
                     INNER JOIN rh.persona_situlaboral sit ON  ind.indiv_id = sit.pers_id AND  persla_ultimo = 1 AND persla_estado = 1
                     INNER JOIN pla.pla005 pl ON sit.migrado_codi = pl.codi   ";

            
            $rs = $this->db->query($sql)->result_array();

            $variables_grid = array(

                 'NOMBRADOS'  => array( 

                             'espe' => '223',  // BONIFICACION ESPCIAL
                             'fami' => '229',   //  =  FAMILIAR
                             'pers' => '228',   // =  PERSONAL 
                             'covi' => '226',  // COSTO DE VIDA
                             'enca' => '225',   // ENCARGATURA
                             'incr' => '231',  // INCREMENTO AFP
                             'movi' => '230',  // movilidad
                             'reun' =>  '222', // reunificada
                             'basi' => '221', // basico
                             'tran' =>  '224', //transitoria
                             'judi' =>  '237',   // JUDICIAL 
                             'hijo' =>   '215'
                    )
 

            );

            foreach($rs as $reg)
            {   

                if($reg['plati_id'] == TIPOPLANILLA_NOMBRADOS){ 


                     foreach($variables_grid['NOMBRADOS'] as $field => $vari_id ){

                          echo  'INDIV: '.$reg['indiv_id'].' DATO: '.$field.' : '.$reg[$field].'<br/>';
                          if($reg[$field] > 0 ){

                                if($field=='judi') $reg[$field] = $reg[$field]/100;
                                
                                $this->empleadovariable->registrar( array('indiv_id' => $reg['indiv_id'], 
                                                                          'vari_id' =>   $vari_id, 
                                                                          'empvar_value' => $reg[$field]  ) );

                                if($field == 'judi'){

                                    $this->empleadoconcepto->registrar($reg['indiv_id'], '121');

                                }
                          }


                          // VERIFICAR TIPO DE PENSION
                          if($reg['afil']=='S'){

                                $this->empleadoconcepto->registrar($reg['indiv_id'], '116');
                                $this->empleadoconcepto->registrar($reg['indiv_id'], '117');
                                $this->empleadoconcepto->registrar($reg['indiv_id'], '118');

                          }
                          else{

                                $this->empleadoconcepto->registrar($reg['indiv_id'], '119');
                          }

                          // SINDICATO 

                          if($reg['sind']=='S'){
                                 $this->empleadoconcepto->registrar($reg['indiv_id'], '122');
                          }
                     }
                }
           }

    }


    public function actualizar_bancos(){


           $this->load->library(array('App/persona')); 

           $sql ="  SELECT indiv.indiv_id,indiv.indiv_dni,e.elec,e.banco,e.cta  from pla.pla005 e
                INNER JOIN public.individuo indiv ON e.elec = indiv.indiv_dni
              where e.banco != '' ";

           $rs = $this->db->query($sql,array())->result_array(); 


           $bancos = array(
         
                 'S' => '6',
                 'N' => '4',
                 'C' => '7',
                 'I' => '5'


            );

           echo 'AQUII ';

           foreach($rs as $reg){
                    
                 $values = array(
                     
                     'pers_id' => $reg['indiv_id'],
                     'pecd_cuentabancaria' => $reg['cta'],
                     'ebanco_id' => $bancos[$reg['banco']]

                 ); 

                 $this->persona->add_cuentadeposito( $reg['indiv_id'], $values);

           }
    }


    public function get_equivalente($id_en_nombrados, $tipo, $de){


        $sql =" SELECT ".$de." FROM  planillas.temp_equi_vars WHERE  nombrados = ? AND  tipo = ?  LIMIT 1 ";

        $rs =  $this->db->query($sql, array($id_en_nombrados, $tipo))->result_array();

        return $rs[0][$de];
    }


    public function clone_calculo__no_execute(){



        $this->load->library(array('App/variable','App/concepto', 'App/conceptooperacion'));

        $NOMBRADOS_ID = 2;

        $TO_CLONE_PLATI = 6;
        $field_equivalente_to = "casinv";


         // CLONANDO VARIABLES   BUSCAMOS LOS CONCEPTOS DE LOS NOMBRADOS
        $sql =" SELECT * FROM planillas.variables WHERE plati_id = ?  AND  vari_estado = 1 ";  
        $rs_to_clone = $this->db->query($sql, array($NOMBRADOS_ID))->result_array();  
        
         /*
         foreach($rs_to_clone as $reg){
              $sql =" INSERT INTO  planillas.temp_equi_vars  (tipo,nombrados) VALUES(?,?)"; 
              $this->db->query($sql, array('1',$reg['vari_id']));
         }*/

        foreach($rs_to_clone as $reg){

            $values = $reg;
            $values['plati_id'] = $TO_CLONE_PLATI;
            $vari_m = $values['vari_id'];
            unset($values['vari_id']); // KILL THIS
            unset($values['vari_key']); // KILL THIS
          //  echo '<br/><br/>';
           // var_dump($values);
            list($new_id, $new_key) =  $this->variable->registrar($values, true);    
            $sql =" UPDATE planillas.temp_equi_vars SET ".$field_equivalente_to." = ? WHERE tipo = 1 AND nombrados = ? ";
            $this->db->query($sql, array($new_id, $vari_m));
         

        }

     
        $sql =" SELECT * FROM planillas.conceptos WHERE plati_id = ?  AND  conc_estado = 1 ";
        $rs_to_clone = $this->db->query($sql, array($NOMBRADOS_ID))->result_array();  // CLONANDO CONCEPTOS  
         
         /*
        foreach($rs_to_clone as $reg){
              $sql =" INSERT INTO  planillas.temp_equi_vars  (tipo,nombrados) VALUES(?,?)"; 
              $this->db->query($sql, array('2',$reg['conc_id']));
         }*/

 
        foreach($rs_to_clone as $reg){

            $values = $reg;
            $values['plati_id'] = $TO_CLONE_PLATI;
            $conc_m = $values['conc_id'];
            unset($values['conc_id']); // KILL THIS
            unset($values['conc_key']); // KILL THIS
          //  echo '<br/><br/>';
           // var_dump($values);
            list($new_id, $new_key) =  $this->concepto->registrar($values, true);    
             $sql =" UPDATE planillas.temp_equi_vars SET ".$field_equivalente_to." = ? WHERE tipo = 2 AND nombrados = ? ";
            $this->db->query($sql, array($new_id, $conc_m));
        }



        $sql = " SELECT * FROM planillas.conceptos_ops coo WHERE coo.coops_estado = 1 AND generado = 0  order by coops_id ";

        $rs_ops = $this->db->query($sql, array())->result_array();

        foreach($rs_ops as $reg){
            

            $values                    = $reg;
            var_dump($values);
            echo '<br/><br/>';
       
            $values['conc_id']         = $this->get_equivalente($reg['conc_id'], 2 ,$field_equivalente_to);

            $tipo = $values['coops_operando1_t'];
            $values['coops_operando1'] =  ( $tipo == 1 ||  $tipo == 2  ) ? $this->get_equivalente($reg['coops_operando1'], $tipo ,$field_equivalente_to) : $reg['coops_operando1'];
 
            $tipo = $values['coops_operando2_t'];
            $values['coops_operando2'] =  ( $tipo == 1 ||  $tipo == 2  ) ? $this->get_equivalente($reg['coops_operando2'], $tipo ,$field_equivalente_to) : $reg['coops_operando2'];
            $values['generado']        = '1'; 

            unset($values['coops_id']); // KILL THIS
            unset($values['coops_key']); // KILL THIS

          var_dump($values);
           echo '<br/><br/>';
            $this->conceptooperacion->registrar($values);

        }

        /* */



    } 


    public function generar_residentes_tareos(){


        $sql =" SELECT v001.codi, v003.carg from public.v001  inner join public.v003 on v001.codi = v003.usur
                where v003.carg = 7  ";

        $rs=  $this->db->query($sql,array())->result_array();

        foreach($rs  as $reg){

            $sql =" INSERT INTO tareo.funciones (codi,codi_v001) VALUES (?,?) ";
            $this->db->query($sql, array('7', $reg['codi']) );
        }

    }  


    public function update_fechas_ini(){


        $sql = " SELECT * FROM public.individuo ind
                 INNER join rh.persona_situlaboral h ON h.pers_id = ind.indiv_id AND persla_vigente = 1   

                 WHERE ind.indiv_id = 1";
        $personas = $this->_CI->db->query($sql, array())->result_array();


        foreach($personas as $persona)
        {    
            $sql = " SELECT * FROM rh.persona_situlaboral WHERE pers_id = ? ORDER BY persla_fechaini "; 
        }



    }



    public function numero_de_hijos()
    {

        $sql = " INSERT INTO planillas.empleado_variable (indiv_id, vari_id, empvar_value, empvar_displayprint )
                SELECT situ.pers_id,  139, hijo,2 FROM rh.persona_situlaboral situ   
                          INNER JOIN public.individuo indiv ON situ.pers_id = indiv.indiv_id AND indiv.indiv_estado = 1
                          INNER JOIN pla.pla005 tra ON tra.elec = indiv.indiv_dni AND tra.tita = '6'

                WHERE situ.persla_ultimo = 1 AND situ.persla_estado = 1 AND situ.plati_id = 9 AND tra.hijo > 0 
               ";


        $sql ="  UPDATE planillas.planilla_empleado_variable plaev 
                  SET plaev_valor = ( SELECT empvar_value 
                          FROM planillas.empleado_variable empvar 
                          INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_id = plaev.plaemp_id AND empvar.indiv_id = plaemp.indiv_id  
                          WHERE empvar.vari_id= 139  
                          GROUP BY plaemp.indiv_id,empvar_value
                            )
                  WHERE plaev.vari_id = 139
 

        "; 
    }


    public function migrar_ocupacion()
    { 

        $codigos = array(
            
                '1' => '2',     // Nombrados
                '2' => '3',     // Empleado contratado
                '3' => '4',     // Obrero Nombrado
                '4' => '5',     // Obrero Contratado
                '5' => '1',     // Empleado de Inversiones
                '6' => '9',     // Construccion civil
                '7' => '11',    // Pensionista
                '8' => '10',    // Regidor
                '9' => '6',     // Cas Inversiones
                '0' => '7',     // Cas funcionamiento
                'A' => '8'      // Practicante
        );


        $sql = " SELECT distinct(tita), ocup FROM pla.pla005 ORDER BY tita, ocup ";
        
        $rs = $this->db->query($sql, array() )->result_array();
 

        foreach ($rs as $reg)
        {
              
            if( trim($reg['tita']) != '')
            {
                $sql ="   INSERT INTO planillas.ocupacion(ocu_nombre, plati_id, temp_tita ) VALUES( ?, ?, ? ) ";
                $this->db->query($sql, array( trim($reg['ocup']), $codigos[$reg['tita']], $reg['tita'] ));
            }

        }

        $sql = "  SELECT indiv.indiv_id, ocu.ocu_id, ocu.plati_id, pla.ocup ,pla.codi 
                          FROM public.individuo indiv 
                         INNER JOIN pla.pla005 pla ON indiv.indiv_dni = pla.elec 
                         INNER JOIN planillas.ocupacion ocu ON pla.ocup = ocu.ocu_nombre AND pla.tita = ocu.temp_tita  
                     WHERE pla.ocup != '' AND pla.elec NOT IN ( SELECT elec FROM (

                                          SELECT elec, tita, count(*) as t
                                          FROM pla.pla005
                                          GROUP BY elec,tita

                                          ) as datos

                                          WHERE datos.t > 1 ) 
                      ORDER BY indiv_id, ocu_id, ocu.plati_id  

               ";

        $rs = $this->db->query($sql, array() )->result_array();

        foreach ($rs as $reg)
        {
              
           if(trim($reg['ocup']) != '')
           { 
            
               $sql = "INSERT INTO planillas.empleado_ocupacion(indiv_id, ocu_id, plati_id ) VALUES (?, ?, ? ) ";

               $this->db->query($sql ,array($reg['indiv_id'], $reg['ocu_id'], $reg['plati_id']) );

           }

        }


    }
 
    
    public function migrar_obreros()
    { 

       $this->db->trans_begin();

        $this->load->library(array('App/pension', 'App/persona', 'App/situacionlaboral'));

        $sql = "SELECT * FROM  planillas.importar_obreros ";

        $rs = $this->db->query($sql, array())->result_array();

        foreach ($rs as $reg)
        {
            
            $sql = "SELECT indiv_id FROM public.individuo WHERE indiv_dni = ?  LIMIT 1";
            $indiv_id_rs = $this->db->query($sql, array($reg['indiv_dni']))->result_array();

            $indiv_id = $indiv_id_rs[0]['indiv_id'];

            if($indiv_id != '')
            {

                // PENSION 

                $sql = " UPDATE rh.persona_pension pepe SET peaf_estado = 0 WHERE pers_id = ? ";
                $this->db->query($sql , array($indiv_id));

                $pentip_id = trim($reg['pentip_id']);

                var_dump($pentip_id);

                if($pentip_id == '2')
                { 
                    $afp_id = trim($reg['afp_id']);

                    if($afp_id == '' || $afp_id == '0')
                    {
                        echo ' <br/> Problema con afp registro '.$reg['row_id'];
                    }

                    $afp_modo = trim($reg['afm_id']);  
                  

                    if($afp_modo == '' || $afp_modo == '0')
                    {
                           echo ' <br/> Problema con afp registro '.$reg['row_id'];
                    }

                    $afp_cusp = trim($reg['afp_codigo']);
                      

                    if($afp_cusp == '' || $afp_cusp == '0')
                    {
                           echo ' <br/> Problema con afp registro '.$reg['row_id'];
                    }  

                }
                else
                { 

                    $pentip_id = '1';
                    $afp_id = '0';
                    $afp_modo = '0';
                    $afp_cusp = '';

                }
 
                $rs1 = $this->pension->registrar(array('pers_id' => $indiv_id, 
                                                'afp_id' => $afp_id,
                                                'afm_id' => $afp_modo,
                                                'peaf_codigo' => $afp_cusp,
                                                'pentip_id' => $pentip_id )); 


                // CUENTA
                $this->persona->remove_cuenta($indiv_id);

                if( trim($reg['pecd_cuentabancaria']) != '' && trim($reg['pecd_cuentabancaria']) != "''" )
                {

                   $banco_id = trim($reg['ebanco_id']);
                   $cuenta = trim($reg['pecd_cuentabancaria']);

                   if($banco_id == '' || $banco_id == '0')
                   {
                        echo ' <br/> Problema con banco registro '.$reg['row_id'];
                   }

                    $rs2 = $this->persona->add_cuentadeposito( $indiv_id, array( 
                                                                'ebanco_id'           => $banco_id,
                                                                'pecd_cuentabancaria' => $cuenta
                                                                ));  



                }
/*

               $rs3= $this->situacionlaboral->registrar(array( 'pers_id' =>  $indiv_id,
                                                               'persla_vigente' => '1',
                                                               'plati_id' => '5',
                                                               'persla_fechaini' => $reg['fecha_inicio'],
                                                               'persla_terminoindefinido' => '1' ));*/


                // SITuACION laboral

               if($rs1 == FALSE || $rs2 == FALSE || $rs3 == FALSE)
               {

                    $this->db->trans_rollback();
                    echo '<br/> DIE IN : '.$reg['row_id'];
                    break;  

               }
            }
            else
            {

                echo ' <br/>Registro no exist dni :'.$reg['row_id'];
            }

        }


        if($this->db->trans_status() === FALSE) 
        { 
           echo ' <br/>NO funciono :/ ';
            $this->db->trans_rollback();
            $ok = false;
                
        }else{
                    
            $this->db->trans_commit();
            echo 'EXITO :D ';
            $ok = true;
        } 

    }



    public function region_migrar_trabajadores()
    {
    
        $this->load->library(array('db/public/individuo','App/situacionlaboral','App/pension','App/segurosalud','App/persona'));
          




        // Codigos de situaciones laborales
        $codigos = array(
            
                '1' => '2',     // Nombrados
                '2' => '3',     // Empleado contratado
                '4' => '9',     // Construccion civil
                '6' => '7 ',     //CAS
                'U' => '8',     //PRACTICANTE
                'T' => '1'     // INVERSIONES
        );
		
         
        
        $estado_civil = array(
            
                'SOLTERO'    => '1',
                'CASADO'     => '2',   
                'SOLTERA'    => '1',
                'CASADA'     => '2',
                'VIUDO'      => '4',
                'VIUDA'      => '4',
                'DIVORCIADO' => '3',
                'DIVORCIADA' => '3',
                'CONVIVIENT' => '5'
            
        );
        
        $afps_ids = array(
            
             '02' => '2', //  Integra
             '03' => '1', // profuturo
             '04' => '4', // horizonte
             '06' => '3'  // Prima
            
        );


           $bancos = array(
         
                 'S' => '6',
                 'N' => '4',
                 'C' => '7',
                 'I' => '5'


            );
         
        $sql = '  SELECT distinct(d.dni) as dni
                   FROM (

                      SELECT data.*, tra.*

                      FROM
                      (
                        SELECT  DISTINCT(tra."ELEC") as dni, SUBSTRING(pla."TIPO" FROM 1 FOR 1) as tipo, MAX(pla."HIJOS")
                        FROM pla.pla100 pla 
                        LEFT JOIN pla.pla050 cod ON pla."CTTO" = cod."CODI" 
                        LEFT JOIN pla.pla005 tra ON cod."TRAB" = tra."CODI"
                        WHERE "PLAN" like \'201308\'  
                        GROUP BY dni,tipo 
                        ORDER BY tra."ELEC"
                      ) as data
                      LEFT JOIN pla.pla005 tra ON data.dni = tra."ELEC"

                      ORDER BY tra."APNO", tra."MATE", tra."NOMB"

                  )  as d


        '; // Obteniendo los DNIS 
         
        $empleados = $this->db->query($sql)->result_array(); // Por cada DNI buscaremos los registros
        
        echo "Encontrados : ".sizeof($empleados)." registros de DNIS diferentes <br/><br/>";
        
        $c_e = 0;

        $total_migrados = 0;

        $total_cuentas = 0;

        $total_afps = 0;

        foreach($empleados as $empleado )
        {  // Recorremos los DNIS

            
            
            $existe = false;    
            $indiv_id = '';

            

            /*
            $sql = " SELECT * FROM public.individuo WHERE indiv_dni = ? LIMIT 1 ";
            $existente = $this->db->query($sql, array($empleado['dni']) )->result_array();

            if( trim($existente[0]['indiv_id']) != ''){

                  $existe = true;  
                  $indiv_id = $existente[0]['indiv_id'];
            }*/


            $sql =  '   SELECT data.*, tra.*

                            FROM
                            (
                              SELECT  tra."ELEC" as dni, SUBSTRING(pla."TIPO" FROM 1 FOR 1) as tipo,   MAX(pla."HIJOS") as  hijos 
                              FROM pla.pla100 pla 
                              LEFT JOIN pla.pla050 cod ON pla."CTTO" = cod."CODI" 
                              LEFT JOIN pla.pla005 tra ON cod."TRAB" = tra."CODI"
                              WHERE "PLAN" like ?  AND tra."ELEC"= ?
                         
                              GROUP BY dni,tipo  
                              ORDER BY tra."ELEC"
                            ) as data
                            LEFT JOIN pla.pla005 tra ON data.dni = tra."ELEC"

                          LIMIT 1 
                  ';  // Ordenando para obtener el ultimo ingresado, primero
           list($data_empleado) = $this->db->query($sql, array('%201308%', $empleado['dni']) )->result_array();
         
        
            
            $c_re++;

             $values =  array(

                 'indiv_appaterno'       =>  trim($data_empleado['APNO']),
                 'indiv_apmaterno'       =>  trim($data_empleado['MATE']),
                 'indiv_nombres'         =>  trim($data_empleado['NOMB']),
                 'indiv_dni'             =>  trim($data_empleado['ELEC']), 

                 'indiv_direccion1'      =>  trim($data_empleado['DOMI']),
                 'migrado_codi'          =>  trim($data_empleado['CODI']),
                 'indti_id'              => '1',
                 'indiv_sexo'            => (trim($data_empleado['SEXO']) == '2' ? '2' : '1' )
                      
             );
                
                if(trim($data_empleado['FENA']) != '') $values['indiv_fechanac'] =  trim($data_empleado['FENA']);
                 
                list($indiv_id,$key) =  $this->individuo->registrar($values,true); // Insertamos el registro base table individuo
                
                if($indiv_id)
                {
                   $total_migrados++;
                }

                // generamos el registro de situacion laboral
                  $values_h = array(
                         
                             'pers_id'               => $indiv_id,
                             'plati_id'              => $codigos[trim($data_empleado['tipo'])],
                           
                             'persla_estado'         => '1',
                             'persla_vigente'        => '1', // 1: vigente 0: no vigente 2: no precisado
                             'persla_descripcion'    => trim($data_empleado['ocup']).' '.trim($data_empleado['nive']),
                             'persla_generado'       => '1',
                             'persla_terminoindefinido' => '1',
                             'persla_ultimo'         => '1',
                             'persla_porcompletar'   => '1' 

                  );
                  
                  if(trim($data_empleado['FEIN']) != '') $values_h['persla_fechaini'] =  trim($data_empleado['FEIN']);

                  if(trim($data_empleado['fesa']) != '') $values_h['persla_fechafin'] =  trim($data_empleado['fesa']);
                     
                
                $this->situacionlaboral->registrar($values_h);


/*

               
           
 
                 */
                
				/*
			00 => ONP 
			06 => JUBILADO
			01 => VIDA
			02 => INTEGRA
			03 => PROFUTURO
			04 => HORIZONTE
			05 => PRIMA 
			07 => HABITAT
			
			 $afps_ids = array(
            
             '02' => '2', //  Integra
             '03' => '1', // profuturo
             '04' => '4', // horizonte
             '06' => '3'  // Prima
            
        );
		*/
		  $afps_ids = array(
             
			 '01' => '5',
             '02' => '2', //  Integra
             '03' => '1', // profuturo
             '04' => '4', // horizonte
             '05' => '3',  // Prima
			 '07'   => '6'
            
        );

				  $modo_afp = array('F' => '1',
                                    'N' => '1',
                                    'S' => '2');   
				
				 if($data_empleado['AFP'] != '' && $data_empleado['AFP'] != '06' )
				 {
						if( $data_empleado['AFP'] == '00')
						{
							 $values_p = array(

									  'pers_id'      => $indiv_id,
									  'afp_id'       => 0,
									  'pentip_id'    => 1,
									  'peaf_codigo'  => '',
									  'migrado_codi' => trim($data_empleado['CODI']) 

						  );
						}
						else
						{
							  $values_p = array(

									  'pers_id'      => $indiv_id,
									  'afp_id'       => $afps_ids[$data_empleado['AFP']],
									  'pentip_id'    => 2,
									  'peaf_codigo'  =>  trim($data_empleado['CAFP']),
									  'afm_id'       => (trim($data_empleado['MIXTA']) == 'S') ? AFP_SALDO  : AFP_FLUJO,
									  'migrado_codi' => trim($data_empleado['CODI']) 

						  );	
						
						}
					
						

						  $this->pension->registrar($values_p);
						  
				  }
					
                 if(trim($data_empleado['BANC']) != '' && trim($data_empleado['BANC']) != '00'  && trim($data_empleado['CCTA']) != '')
                 { 

                      $values = array(
                 
                          'pers_id' => $indiv_id,
                          'pecd_cuentabancaria' => trim($data_empleado['CCTA']),
                          'ebanco_id' =>  '4',
                          'migrado_codi' => trim($data_empleado['CODI']) 

                      ); 
                 
                     $this->persona->add_cuentadeposito(  $indiv_id, $values);

                     $total_cuentas++;
                  } 


                  if($data_empleado['hijos'] != '0' &&  $data_empleado['hijos'] != '' && is_numeric($data_empleado['hijos'])  && $data_empleado['tipo'] == '4' )
                  { 

                       $sql = " INSERT INTO planillas.empleado_variable(indiv_id, vari_id, empvar_value) VALUES(?,?, ?)";
                       $this->db->query($sql, array($indiv_id, '139', $data_empleado['hijos']) );

 
                  } 
                
               
          
        }  // FIN foreach DNI's
        
  
        // Registrar trabajador

        // Registrar Cuenta

        // Registrar AFP 

        // Registrar Conceptos y Variables
    }


    public function migrar_metas()
    { 

        $sql = "SELECT DISTINCT(sec_func) as meta FROM pla.pla053 ORDER BY sec_func ";
        $rs = $this->db->query($sql, array())->result_array();

        foreach ($rs as $reg)
        {
              
            $sql ="  SELECT * FROM pla.pla053 WHERE sec_func = ? LIMIT  1";
            list($meta_info) = $this->db->query($sql, array($reg['meta']))->result_array();
        
            $meta_sql = " INSERT INTO pip.meta (sec_ejec,ano_eje, sec_func, nombre) VALUES('1','2013', ?, ? ) ";
            $this->db->query($meta_sql, array($reg['meta'], $meta_info['DETA']));

            $tarea_sql = " INSERT INTO sag.tarea(ano_eje, sec_func, tarea_nro, tarea_nombre) VALUES('2013', ?, 1,? ) ";
            $this->db->query($tarea_sql, array($reg['meta'], $meta_info['DETA']));

        }


    }
  

    public function region_migrar_metas()
    {


    }

}


/* 
 * X
 *  
 */