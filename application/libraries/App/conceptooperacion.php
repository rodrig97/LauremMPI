 <?PHP
 
  
 Class conceptooperacion extends Table{
     
      
    protected $_FIELDS=array(   
                                    'id'    => 'coops_id',
                                    'code'  => 'coops_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'coops_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'conceptos_ops';
    protected $_PREF_TABLE= 'CONCSOPS'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }


    public function get_next_ecuacion_id($concepto_id){

        // $sql = " SELECT coops_ecuacion_id FROM planillas.conceptos_ops WHERE  conc_id = ? ORDER BY coops_ecuacion_id desc LIMIT 1 ";

         $sql = " SELECT conc_ecuacion_id FROM planillas.conceptos WHERE conc_id = ? LIMIT 1";
         $rs  = $this->_CI->db->query($sql, array($concepto_id))->result_array();

         $t = ($rs[0]['conc_ecuacion_id'] == '') ? 0 : $rs[0]['conc_ecuacion_id'];

         return ($t+1);

    }

    public function generar_codigo_ecuacion($concepto_id){

    }

     
    
    public function guardar_ecuacion($concepto = 0, $ecuacion_param = '', $mode = '1'){ //$mode = 0, se refiere que es un preview
        
        
        
      
       $operadores      =  array('1' => '+' ,'2' => '-' , '3' => 'x', '4' => '/', '0' => '¿'); 
       $operadores_ids  =  array('+' => '+' ,'-' => '-' , 'x' => 'x', '/' => '/', '¿' => '0'); 
     
       $subfijos        =  array('operador' => 'operador','variable' => 'variable', 'constante' => 'constante');
       
       
       $tipo_operandos = array( 'co' => '2',
                                'va' => '1',
                                'cs' => '3'); // Concepto, variable, constante
       
       /* TOMAMOS Y LIMPIAMOS LA ECUACION*/
       $ecuacionall =  $ecuacion_param;
       $ecuacionall = explode('_',$ecuacionall);
       $ecuacion= array();
       
       foreach($ecuacionall as $parte) if(trim($parte) != '') $ecuacion[] = $parte;
        
        /*
       $values = array(
                          'coops_enlace'        => '',
                          'coops_operando1'     => '',
                          'coops_operando1_t'   => '',
                          'coops_operador'      => '',
                          'coops_operando2'     => '',
                          'coops_operando2_t'   => '',
                          'coops_grupo'         => '',
                          'conc_id'             => '',
                          'coops_orden'         => '',
                          'coops_fase'          => $mode,
                          'coops_ecuacion_id'   => '0'
                     ); */
  
         $parentesis_abierto = false;
         $parentesis_cerrado = false;

         $grupo_count = 0;
         $grupo_actual = 0; 

         $operando1 =  0;
         $operando1_tipo = '0';
         $operando1_def = false;

         $operando2 =  0;
         $operando2_tipo = '0';
         $operando2_def = false;
          
         $operador_enlace = '¿';
         $operador_enlace_parentesis = '¿';
         $operador= '¿';
 
         $operadores_symbol = array_values($operadores);

         $orden = 0;

         $concepto_id = $concepto;

         $insert_ = false;
           
         $this->_CI->db->trans_begin();
         
       
         $conc_ecuacion_id =  $this->get_next_ecuacion_id($concepto_id);


         
         
         
         //var_dump($ecuacion); // DEBUG 1
         foreach($ecuacion as $k => $c){

              if( ( $operando1_def == true && $operando2_def == true ) || $parentesis_cerrado    ) // || ($p_abre_parentesis && ($operador_enlace != '' || $operador != '') ) 
              {

                   if(  $operando1_def == true || $operando2_def == true){
 
                          $values = array(
                              'coops_enlace'        =>   ( $parentesis_abierto == false) ? $operadores_ids[$operador_enlace] : $operadores_ids[$operador_enlace_parentesis],
                              'coops_operando1'     =>  $operando1,
                              'coops_operando1_t'   =>  $operando1_tipo,
                              'coops_operador'      =>  $operadores_ids[$operador],
                              'coops_operando2'     =>  $operando2,
                              'coops_operando2_t'   =>  $operando2_tipo,
                              'coops_grupo'         =>  $grupo_actual,
                              'conc_id'             =>  $concepto_id,
                              'coops_orden'         =>  '0',
                              'coops_fase'          => $mode,
                              'coops_ecuacion_id'   => $conc_ecuacion_id

                          );

                          $this->registrar($values);

                          if($parentesis_abierto) 
                          {
                              $parentesis_abierto = false;
                              $operador_enlace_parentesis = '¿';
                          }

                          if($parentesis_cerrado){
                              $grupo_actual = 0;
                          } 
 
                          $operando1 =  0;
                          $operando2 =  0;
                          $operando1_tipo = '0';
                          $operando2_tipo = '0';
                          $operando1_def = false;
                          $operando2_def = false;
                          $parentesis_cerrado = false;
                          $operador= '¿';
                          $operador_enlace = '¿';

                          $insert_ = true;

                   }
                   else{
                       $insert_ = false;
                       $parentesis_cerrado = false;
                       $grupo_actual = 0;
                   }


              }
 
              if($c == '('){
                    $grupo_count++; 
                    $grupo_actual = $grupo_count;
                    $parentesis_abierto = true;
                    $parentesis_cerrado = false;


                    if(  $operando1_def == true || $operando2_def == true){

                          $values = array(
                              'coops_enlace'        =>  $operadores_ids[$operador_enlace],
                              'coops_operando1'     =>  $operando1,
                              'coops_operando1_t'   =>  $operando1_tipo,
                              'coops_operador'      =>  $operadores_ids[$operador],
                              'coops_operando2'     =>  $operando2,
                              'coops_operando2_t'   =>  $operando2_tipo,
                              'coops_grupo'         =>  '0',
                              'conc_id'             =>  $concepto_id,
                              'coops_orden'         =>  '0',
                              'coops_fase'          => $mode,
                              'coops_ecuacion_id'   => $conc_ecuacion_id

                          );
 
                          $this->registrar($values);

                          // Operador cerrado || operador _

                          $operando1 =  0;
                          $operando2 =  0;
                          $operando1_tipo = '0';
                          $operando2_tipo = '0';
                          $operando1_def = false;
                          $operando2_def = false;

                          $operador= '¿';
                          $operador_enlace = '¿';
 
                   } 
              }
              else if($c == ')'){


                       $parentesis_cerrado = true;

              }
              else{

                   if(in_array($c, $operadores_symbol)){     // SI ES OPERADOR
 
                       if ( $ecuacion[$k + 1 ] != '('   )
                       {
                         if($operando1_def  ){    // &&  ( $ecuacion[$k + 1 ] != '(' ) Si ya esta definido el operando1 y se encuentra un operador entonces es el operador
                             $operador = $c;
                         }          
                         else{              // Si se encuentra un operador y no esta definido el operando1 entonces es un operador de enlace
                              $operador_enlace = $c;
                         }
                       }
                       else{

                            $operador_enlace_parentesis = $c;

                       }
                   }
                   else{  // SI NO ES OPERADOR ENTONCES PUEDE SER VARIABLE CONCEPTO O CONSTANTE 

                          if($operando1_def == false){
                               $t_c_operando = explode('|',$c);
                               
                               $operando1 = $t_c_operando[1]; 
                               $x_tipo_ope = substr($t_c_operando[0], 0,2);
//                               var_dump($x_tipo_ope);

                               $operando1_tipo = $tipo_operandos[$x_tipo_ope] ;
                          //        var_dump($operando1_tipo);
                               $operando1_def = true; 

                          }
                          else if( $operando2_def == false){
                               $t_c_operando = explode('|',$c);
                               
                               $operando2 = $t_c_operando[1]; 
                               $x_tipo_ope = substr($t_c_operando[0], 0,2);
                            //   var_dump($x_tipo_ope);
                               $operando2_tipo = $tipo_operandos[$x_tipo_ope] ;
                              //     var_dump($operando2_tipo);
                               $operando2_def = true;

                          }  

                   }  

              }

         }
           
             
           if(  $operador_enlace != '¿' || $operador != '¿' ){

                 $values = array(
                              'coops_enlace'        =>  $operadores_ids[$operador_enlace],
                              'coops_operando1'     =>  $operando1,
                              'coops_operando1_t'   =>  $operando1_tipo,
                              'coops_operador'      =>  $operadores_ids[$operador],
                              'coops_operando2'     =>  $operando2,
                              'coops_operando2_t'   =>  $operando2_tipo,
                              'coops_grupo'         =>  $grupo_actual,
                              'conc_id'             =>  $concepto_id,
                              'coops_orden'         =>  '0',
                              'coops_fase'          => $mode,
                              'coops_ecuacion_id'   => $conc_ecuacion_id

                  );

                  $this->registrar($values);

                  $operando1 =  0;
                  $operando2 =  0;
                  $operando1_tipo = '0';
                  $operando2_tipo = '0';
                  $operando1_def = false;
                  $operando2_def = false;
                  $parentesis_cerrado = false;
                  $operador_enlace = '¿';
                  $operador= '¿';
                  $grupo_actual = 0;
           }
           else{
                /* MODIFICACION IMPLEMENTADA PARA SOPORTAR GUARDAR UNA SOLA VARIABLE/CONCEPTO DE CALCULO */
                   $values = array(
                              'coops_enlace'        =>  0,
                              'coops_operando1'     =>  $operando1,
                              'coops_operando1_t'   =>  $operando1_tipo,
                              'coops_operador'      =>  0,
                              'coops_operando2'     =>  0,
                              'coops_operando2_t'   =>  0,
                              'coops_grupo'         =>  0,
                              'conc_id'             =>  $concepto_id,
                              'coops_orden'         =>  0,
                              'coops_fase'          => $mode,
                              'coops_ecuacion_id'   => $conc_ecuacion_id

                  );

                  $this->registrar($values);

                  $operando1 =  0;
                  $operando2 =  0;
                  $operando1_tipo = '0';
                  $operando2_tipo = '0';
                  $operando1_def = false;
                  $operando2_def = false;
                  $parentesis_cerrado = false;
                  $operador_enlace = '¿';
                  $operador= '¿';
                  $grupo_actual = 0;
               
           }
           

            $mensaje = '';

            $ecuacion_no_recursiva =  $this->validar_ecuacion( $concepto_id, $concepto_id, $conc_ecuacion_id );
           
            if($ecuacion_no_recursiva == FALSE)
            {

               $mensaje = ' No es posible guardar la ecuacion porque el concepto se esta llamando asi mismo. ';   
                           
            } 


            if ($this->_CI->db->trans_status() === FALSE || $ecuacion_no_recursiva == FALSE )
            {
 
               $this->_CI->db->trans_rollback();

               $ok = array(false, $mensaje, 0);
            }
            else
            {
             
                $this->save_id_ecuacion($conc_ecuacion_id,  $concepto_id);

                $this->_CI->db->trans_commit();
                $ok = array(true, '', $conc_ecuacion_id);
            }
            
            return $ok;
 
    }


     // TRUE si la ecucacion no conteiene el concepto buscado
    public function validar_ecuacion($conc_id, $conc_id_search, $ecuacion_id = 0 )
    {


      /* VALIDAR ECUACION */ 
      if($ecuacion_id == 0)
      {
           
           $sql         = ' SELECT conc_ecuacion_id FROM planillas.conceptos conc WHERE conc.conc_id = ? LIMIT 1'; 
           $rs          = $this->_CI->db->query($sql, array($conc_id))->result_array();
           $ecuacion_id = $rs[0]['conc_ecuacion_id'];
      }
        

      $sql =  " SELECT * FROM planillas.conceptos_ops 
                WHERE coops_ecuacion_id = ? AND conc_id = ? AND coops_estado = 1 ORDER BY coops_id";
      
      $operaciones_data = $this->_CI->db->query($sql,array($ecuacion_id, $conc_id))->result_array();
 

      if(sizeof($operaciones_data) == 0)
      {
         return true;
      }

      // 1ro GENERAMOS LA ECUACION
       
      foreach($operaciones_data as $k => $ope)
      {
           
           if($ope['coops_operando1'] != '0' && $ope['coops_operando1_t'] == '2'   ) 
           {
              
                if( $ope['coops_operando1'] == $conc_id_search )
                {
                  
                   return false;

                }
                else{
   
                   $no_contiene_concepto = $this->validar_ecuacion($ope['coops_operando1'],  $conc_id_search );

                   if($no_contiene_concepto == FALSE) return FALSE;
      
                }

             
           }
            

           if($ope['coops_operando2'] != '0' && $ope['coops_operando2_t'] == '2' ) 
           {
            
                if( $ope['coops_operando2'] == $conc_id_search )
                {
                  
                     return false;

                }
                else
                {
                     $no_contiene_concepto = $this->validar_ecuacion($ope['coops_operando2'],  $conc_id_search );

                     if($no_contiene_concepto == FALSE) return FALSE;
                
                }
  
           }
          
      }


      return TRUE;
    
    }


    public function save_id_ecuacion($conc_ecuacion_id, $concepto_id){

         $sql = " UPDATE planillas.conceptos
                  SET conc_ecuacion_id = ? 
                  WHERE conc_id = ? ";
         
         $rs = $this->_CI->db->query( $sql, array($conc_ecuacion_id, $concepto_id) );
       
         return ($rs) ? true : false;
    }

    public function duplicar_ecuacion($concepto_id)
    {


      $sql = "SELECT * FROM planillas.conceptos_ops WHERE conc_id = ? AND coops_estado = 1 ORDER BY coops_id";

      $operaciones = $this->_CI->db->query($sql, array($concepto_id));

      foreach($operaciones as $ope){

        $sql = " INSERT INTO ";

      }

    }
     
 }