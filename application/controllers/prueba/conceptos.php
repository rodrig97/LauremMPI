<?php
 
/*
 *  @Nombre     : Conceptos remunerativos controlador
 *  @Tipo       : Controller 
 *  @Autores    : Giordhano Valdez Linares
 *  @Ultima Modificacion : 06/06/2012
 *  @Descripcion: 
 *  
 *  @Requerimientos : 
 * 
 *  @Excepciones Detectadas:
 *     
 */

if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class conceptos extends CI_Controller {
    
    public $usuario;
    
    public function __construct(){
        parent::__construct();
        
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
        
        
        $this->load->library( array('App/concepto','App/variable','App/conceptooperacion'));
        
    }
    
    
     public function nuevo_concepto(){
         
         
         $this->load->library(array('App/partida','App/grupovc'));

         $grupos =$this->grupovc->get_list();
         
         $partidas = $this->partida->listar($null,$this->usuario['ano_eje'], array('tipo_transaccion' => '2'));
         
         $this->load->view('planillas/v_nuevoconcepto', array('partidas_presupuestales' => $partidas, 'grupos' => $grupos));
         
     }
     
    
     
    
     public function preview(){
         
       $operadores      =  array('1' => '+' ,'2' => '-' , '3' => 'x', '4' => '/', '0' => '¿'); 
       $operadores_ids  =  array('+' => '+' ,'-' => '-' , 'x' => 'x', '/' => '/', '¿' => '0'); 
      // array_combine(array_values($operadores), array_keys($operadores));
       $subfijos        =  array('operador' => 'operador','variable' => 'variable', 'constante' => 'constante');
       
       /* TOMAMOS Y LIMPIAMOS LA ECUACION*/
       $ecuacionall =  trim($this->input->post('ecuacion'));
       
       $ecuacionall = explode('_',$ecuacionall);
       
       $ecuacion= array();
       
       foreach($ecuacionall as $parte) if(trim($parte) != '') $ecuacion[] = $parte;
       
       
       foreach($ecuacion as $k => $parte){
           //  if(trim($parte) != '') $ecuacion[] = $parte;
            
           // $p = strpos($parte, 'xoperandox');
             
       }
       
       
       $values = array(
                          'coops_enlace' => '',
                          'coops_operando1' => '',
                          'coops_operando1_t' => '',
                          'coops_operador' => '',
                          'coops_operando2' => '',
                          'coops_operando2_t' => '',
                          'coops_grupo' => '',
                          'conc_id' => '',
                          'coops_orden' => ''
                         
                     );
       
    //   if($g1 !== false && $fin_g1 !== false ){
            
             // ( a + b + c) / d
            
          //  for($i=$g1; $i<= $fin_g1  )
            
             $parentesis_abierto = false;
             $parentesis_cerrado = false;
             
             $grupo_count = 0;
             $grupo_actual = 0; 
             
             $operando1 =  0;
             $operando1_def = false;
             
             $operando2 =  0;
             $operando2_def = false;
             
             $operador_enlace = '¿';
             $operador_enlace_parentesis = '¿';
             $operador= '¿';
             
             
              
             $operadores_symbol = array_values($operadores);
           
             $orden = 0;
             
             $concepto_id = rand(1,100);
             
             $insert_ = false;
             
             var_dump($ecuacion);
             
             foreach($ecuacion as $k => $c){
                 
               
                 //  echo "- rule $k "   
                  var_dump($parentesis_cerrado);
                 
                  if( ( $operando1_def == true && $operando2_def == true ) || $parentesis_cerrado    ) // || ($p_abre_parentesis && ($operador_enlace != '' || $operador != '') ) 
                  {
                        
                       if(  $operando1_def == true || $operando2_def == true){
                             
                             
                            

                              $values = array(
                                  'coops_enlace'        =>   ( $parentesis_abierto == false) ? $operadores_ids[$operador_enlace] : $operadores_ids[$operador_enlace_parentesis],
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operadores_ids[$operador],
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  $grupo_actual,
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                              );

                              
                              echo "ON PRIMER INSERT IN ".$k." | '".$c."' ";
                              var_dump($parentesis_cerrado);
                              var_dump($values);
                             
                              
                              $this->conceptooperacion->registrar($values);
  
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
                     
                       
                    
                      
                      
                 /*     
                    if(!$p_abre_parentesis){
                        $operador_enlace = '';
                     }  
                   */   
                      
                     
                      
                    //  $p_abre_parentesis = false;
                      
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
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operadores_ids[$operador],
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  '0',
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                              );
 
                              
                              echo "ON SEGUNDO INSERT IN ".$k." | ".$c." ";
                              var_dump($values);
                              $this->conceptooperacion->registrar($values);
  
                              // Operador cerrado || operador _
                              
                              $operando1 =  0;
                              $operando2 =  0;
                              $operando1_def = false;
                              $operando2_def = false;
                            
                              $operador= '¿';
                              $operador_enlace = '¿';
                              
                             
                       }
                         
                        
                  }
                  else if($c == ')'){
                     
                      //  $grupo_actual = 0;
                          
                     //  if(!$insert_){
                           $parentesis_cerrado = true;
                       //}
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

                                   $operando1 = $c; 
                                   $operando1_def = true; 

                              }
                              else if( $operando2_def == false){

                                   $operando2 = $c; 
                                   $operando2_def = true;

                              }  
                            
                       }  
 
                  }
                   
             }
           
             
             
               echo "__END __ "; 
               if(  $operador_enlace != '¿' || $operador != '¿' ){
                     $values = array(
                                  'coops_enlace'        =>  $operadores_ids[$operador_enlace],
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operadores_ids[$operador],
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  $grupo_actual,
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                      );

                      var_dump ($values);
                      $this->conceptooperacion->registrar($values);

                      $operando1 =  0;
                      $operando2 =  0;
                      $operando1_def = false;
                      $operando2_def = false;
                      $parentesis_cerrado = false;
                      $operador_enlace = '¿';
                      $operador= '¿';
                      $grupo_actual = 0;
               }
           
       } 
       
       
       
       public function procesa_ecuacion(){
        
           $MEMORY = array();
           
           /* 
            *  $memory['conceptos']['100'] = '';
            * 
            *   // SI lo encuentra en memoria entocnes ya no hace la peticion a la DB
            * 
            */
           
           
       }
       
       
    public function preview_FUNCIONA(){
         
       $operadores      =  array('1' => '+' ,'2' => '-' , '3' => 'x', '4' => '/', '0' => '¿'); 
       $operadores_ids  =  array('+' => '1' ,'-' => '2' , 'x' => '3', '/' => '4', '¿' => '0'); 
      // array_combine(array_values($operadores), array_keys($operadores));
       $subfijos        =  array('operador' => 'operador','variable' => 'variable', 'constante' => 'constante');
       
       /* TOMAMOS Y LIMPIAMOS LA ECUACION*/
       $ecuacionall =  trim($this->input->post('ecuacion'));
       
       $ecuacionall = explode('_',$ecuacionall);
       
       $ecuacion= array();
       
       foreach($ecuacionall as $parte) if(trim($parte) != '') $ecuacion[] = $parte;
       
       
        foreach($ecuacion as $k => $parte){
           //  if(trim($parte) != '') $ecuacion[] = $parte;
            
           // $p = strpos($parte, 'xoperandox');
             
        }
       
        
       
       $values = array(
                          'coops_enlace' => '',
                          'coops_operando1' => '',
                          'coops_operando1_t' => '',
                          'coops_operador' => '',
                          'coops_operando2' => '',
                          'coops_operando2_t' => '',
                          'coops_grupo' => '',
                          'conc_id' => '',
                          'coops_orden' => ''
                         
                     );
       
    //   if($g1 !== false && $fin_g1 !== false ){
            
             // ( a + b + c) / d
            
          //  for($i=$g1; $i<= $fin_g1  )
            
             $parentesis_abierto = false;
             $parentesis_cerrado = false;
             
             $grupo_count = 0;
             $grupo_actual = 0; 
             
             $operando1 =  0;
             $operando1_def = false;
             
             $operando2 =  0;
             $operando2_def = false;
             
             $operador_enlace = '';
             $operador_enlace_parentesis = '';
             $operador= '';
             
             
              
             $operadores_symbol = array_values($operadores);
           
             $orden = 0;
             
             $concepto_id = rand(1,100);
             
             $insert_ = false;
             
             var_dump($ecuacion);
             
             foreach($ecuacion as $k => $c){
                 
               
                  echo "- rule $k "; 
                  var_dump($parentesis_cerrado);
                 
                  if( ( $operando1_def == true && $operando2_def == true ) || $parentesis_cerrado    ) // || ($p_abre_parentesis && ($operador_enlace != '' || $operador != '') ) 
                  {
                        
                       if(  $operando1_def == true || $operando2_def == true){
                             
                             
                            

                              $values = array(
                                  'coops_enlace'        =>   ( $parentesis_abierto == false) ? $operador_enlace : $operador_enlace_parentesis,
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operador,
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  $grupo_actual,
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                              );

                              
                              echo "ON PRIMER INSERT IN ".$k." | '".$c."' ";
                              var_dump($parentesis_cerrado);
                              var_dump($values);
                             
                              
                              $this->conceptooperacion->registrar($values);
  
                              if($parentesis_abierto) 
                              {
                                  $parentesis_abierto = false;
                                  $operador_enlace_parentesis = '';
                              }
                              
                              if($parentesis_cerrado){
                                  $grupo_actual = 0;
                              } 
                           
                              
                              
                              $operando1 =  0;
                              $operando2 =  0;
                              $operando1_def = false;
                              $operando2_def = false;
                              $parentesis_cerrado = false;
                              $operador= '';
                              $operador_enlace = '';
                              
                              $insert_ = true;
                              
                       }
                       else{
                           $insert_ = false;
                           $parentesis_cerrado = false;
                           $grupo_actual = 0;
                       }
                     
                       
                    
                      
                      
                 /*     
                    if(!$p_abre_parentesis){
                        $operador_enlace = '';
                     }  
                   */   
                      
                     
                      
                    //  $p_abre_parentesis = false;
                      
                  }
                  
                  
                  
                 
                  if($c == '('){
                        $grupo_count++; 
                        $grupo_actual = $grupo_count;
                        $parentesis_abierto = true;
                        $parentesis_cerrado = false;
                        
                        
                        if(  $operando1_def == true || $operando2_def == true){
                               
                              $values = array(
                                  'coops_enlace'        =>  $operador_enlace,
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operador,
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  '0',
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                              );
 
                              
                              echo "ON SEGUNDO INSERT IN ".$k." | ".$c." ";
                              var_dump($values);
                              $this->conceptooperacion->registrar($values);
  
                              // Operador cerrado || operador _
                              
                              $operando1 =  0;
                              $operando2 =  0;
                              $operando1_def = false;
                              $operando2_def = false;
                            
                              $operador= '';
                              $operador_enlace = '';
                              
                             
                       }
                         
                        
                  }
                  else if($c == ')'){
                     
                      //  $grupo_actual = 0;
                          
                     //  if(!$insert_){
                           $parentesis_cerrado = true;
                       //}
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

                                   $operando1 = $c; 
                                   $operando1_def = true; 

                              }
                              else if( $operando2_def == false){

                                   $operando2 = $c; 
                                   $operando2_def = true;

                              }  
                            
                       }  
 
                  }
                   
             }
           
             
             
               echo "__END __ "; 
               if(  $operador_enlace != '' || $operador != '' ){
                     $values = array(
                                  'coops_enlace'        =>  $operador_enlace,
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operador,
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  $grupo_actual,
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                      );

                      var_dump ($values);
                      $this->conceptooperacion->registrar($values);

                      $operando1 =  0;
                      $operando2 =  0;
                      $operando1_def = false;
                      $operando2_def = false;
                      $parentesis_cerrado = false;
                      $operador_enlace = '';
                      $operador= '';
                      $grupo_actual = 0;
               }
           
       } 
       
       
   public function preview_funcional(){
         
       $operadores      =  array('1' => '+' ,'2' => '-' , '3' => 'x', '4' => '/', '0' => '¿'); 
       $operadores_ids  =  array('+' => '1' ,'-' => '2' , 'x' => '3', '/' => '4', '¿' => '0'); 
      // array_combine(array_values($operadores), array_keys($operadores));
       $subfijos        =  array('operador' => 'operador','variable' => 'variable', 'constante' => 'constante');
       
       /* TOMAMOS Y LIMPIAMOS LA ECUACION*/
       $ecuacionall =  trim($this->input->post('ecuacion'));
       
       $ecuacionall = explode('_',$ecuacionall);
       
       $ecuacion= array();
       
       foreach($ecuacionall as $parte) if(trim($parte) != '') $ecuacion[] = $parte;
       
       
        foreach($ecuacion as $k => $parte){
           //  if(trim($parte) != '') $ecuacion[] = $parte;
            
           // $p = strpos($parte, 'xoperandox');
             
        }
       
        
       
       $values = array(
                          'coops_enlace' => '',
                          'coops_operando1' => '',
                          'coops_operando1_t' => '',
                          'coops_operador' => '',
                          'coops_operando2' => '',
                          'coops_operando2_t' => '',
                          'coops_grupo' => '',
                          'conc_id' => '',
                          'coops_orden' => ''
                         
                     );
       
    //   if($g1 !== false && $fin_g1 !== false ){
            
             // ( a + b + c) / d
            
          //  for($i=$g1; $i<= $fin_g1  )
            
             $parentesis_abierto = false;
             $parentesis_cerrado = false;
             
             $grupo_count = 0;
             $grupo_actual = 0; 
             
             $operando1 =  0;
             $operando1_def = false;
             
             $operando2 =  0;
             $operando2_def = false;
             
             $operador_enlace = '';
             $operador_enlace_parentesis = '';
             $operador= '';
             
             
              
             $operadores_symbol = array_values($operadores);
           
             $orden = 0;
             
             $concepto_id = rand(1,100);
             
             $insert_ = false;
             
             foreach($ecuacion as $k => $c){
                 
                 
                  // Registro  
                  
              //    $is_abierto = ( $p_abre_parentesis &&  ($operando1_def == true || $operando2_def == true )); || $p_abre_parentesis
                  
                  
                 
                 
                  if( ( $operando1_def == true && $operando2_def == true ) || $parentesis_cerrado    ) // || ($p_abre_parentesis && ($operador_enlace != '' || $operador != '') ) 
                  {
                        
                       if(  $operando1_def == true || $operando2_def == true){
                             
                            

                              $values = array(
                                  'coops_enlace'        =>   ( $parentesis_abierto == false) ? $operador_enlace : $operador_enlace_parentesis,
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operador,
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  $grupo_actual,
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                              );

                              var_dump ($values);
                              $this->conceptooperacion->registrar($values);
  
                              if($parentesis_abierto) 
                              {
                                  $parentesis_abierto = false;
                                  $operador_enlace_parentesis = '';
                              }
                              
                              if($parentesis_cerrado){
                                  $grupo_actual = 0;
                              } 
                           
                              
                              
                              $operando1 =  0;
                              $operando2 =  0;
                              $operando1_def = false;
                              $operando2_def = false;
                              $parentesis_cerrado = false;
                              $operador= '';
                              $operador_enlace = '';
                              
                              $insert_ = true;
                              
                       }
                       else{
                           $insert_ = false;
                       }
                     
                       
                    
                      
                      
                 /*     
                    if(!$p_abre_parentesis){
                        $operador_enlace = '';
                     }  
                   */   
                      
                     
                      
                    //  $p_abre_parentesis = false;
                      
                  }
                  
                  
                  
                 
                  if($c == '('){
                        $grupo_count++; 
                        $grupo_actual = $grupo_count;
                        $parentesis_abierto = true;
                        
                        
                        
                        if(  $operando1_def == true || $operando2_def == true){
                               
                              $values = array(
                                  'coops_enlace'        =>  $operador_enlace,
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operador,
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  '0',
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                              );

                              var_dump ($values);
                              $this->conceptooperacion->registrar($values);
  
                            
                              
                              $operando1 =  0;
                              $operando2 =  0;
                              $operando1_def = false;
                              $operando2_def = false;
                              $parentesis_cerrado = false;
                              $operador= '';
                              $operador_enlace = '';
                              
                             
                       }
                         
                        
                  }
                  else if($c == ')'){
                     
                      //  $grupo_actual = 0;
                          
                     //  if(!$insert_){
                           $parentesis_cerrado = true;
                       //}
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

                                   $operando1 = $c; 
                                   $operando1_def = true; 

                              }
                              else if( $operando2_def == false){

                                   $operando2 = $c; 
                                   $operando2_def = true;

                              }  
                            
                       }  
 
                  }
                   
             }
           
             
             
               echo "__END __ "; 
               if(  $operador_enlace != '' || $operador != '' ){
                     $values = array(
                                  'coops_enlace'        =>  $operador_enlace,
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operador,
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  $grupo_actual,
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                      );

                      var_dump ($values);
                      $this->conceptooperacion->registrar($values);

                      $operando1 =  0;
                      $operando2 =  0;
                      $operando1_def = false;
                      $operando2_def = false;
                      $parentesis_cerrado = false;
                      $operador_enlace = '';
                      $operador= '';
                      $grupo_actual = 0;
               }
           
       } 
     
    
    public function preview__(){
         
       $operadores      =  array('1' => '+' ,'2' => '-' , '3' => 'x', '4' => '/', '0' => '¿'); 
       $operadores_ids  =  array('+' => '1' ,'-' => '2' , 'x' => '3', '/' => '4', '¿' => '0'); 
      // array_combine(array_values($operadores), array_keys($operadores));
       $subfijos        =  array('operador' => 'operador','variable' => 'variable', 'constante' => 'constante');
       
       /* TOMAMOS Y LIMPIAMOS LA ECUACION*/
       $ecuacionall =  trim($this->input->post('ecuacion'));
       
       $ecuacionall = explode('_',$ecuacionall);
       
       $ecuacion= array();
       
       foreach($ecuacionall as $parte) if(trim($parte) != '') $ecuacion[] = $parte;
       
       
        foreach($ecuacion as $k => $parte){
           //  if(trim($parte) != '') $ecuacion[] = $parte;
            
           // $p = strpos($parte, 'xoperandox');
             
        }
       
       
       /* EMPIEZA EL ALGORITMO DE RENDERIZADO DE ECUACION */
       /*
       $parentesis_abierto = false;
       
       $errores = array();
       
       foreach($ecuacion as $k => $parte){
           
            if($parte=='(')
            {
                if($parentesis_abierto){
                    
                     $errores[] = ' Un parentesis esta equivado';
                }
                else{
                    $parentesis_abierto = true;
                }
                echo " ( ";
                
                
            }else if($parte == ')'){
                
                if(!$parentesis_abierto){
                    
                    $errores[] = ' Un parentesis esta equivado';
                    
                } 
                echo " ) ";
            }
            else{
             
                $p = explode('xxxxx',$parte);
                $tipo = $p[0];
                $valor = $p[1];
                
                if($tipo == 'operador')
                {
                     echo " ".$operadores[$valor];
                }
                else{
                     echo " ".$valor;
                }
            }
              
       }*/
       
       
    /* 
     *   2 + 2 + 3 * 4 + 6 * 2 / 2  
     *     0   0   1   0   1   1
     *   
     *   2 + 2 + ( 3 * 4 + 6 ) * 2 / 2  
     *     0   0   1   0   1   1
     *     
     */
       
      // $g1 = array_search("(", $ecuacion );
      // $fin_g1 = array_search(")", $ecuacion ); 
       
       $values = array(
                          'coops_enlace' => '',
                          'coops_operando1' => '',
                          'coops_operando1_t' => '',
                          'coops_operador' => '',
                          'coops_operando2' => '',
                          'coops_operando2_t' => '',
                          'coops_grupo' => '',
                          'conc_id' => '',
                          'coops_orden' => ''
                         
                     );
       
    //   if($g1 !== false && $fin_g1 !== false ){
            
             // ( a + b + c) / d
            
          //  for($i=$g1; $i<= $fin_g1  )
            
             $p_abierto = false;
             $p_abre_parentesis = false;
             $parentesis_completo = false;
             $grupo_count = 0;
             
             $operando1 =  0;
             $operando1_def = false;
             
             $operando2 =  0;
             $operando2_def = false;
             
             $operador_enlace = '';
             $operador_enlace_parentesis = '';
             $operador= '';
             
             $grupo_actual = 0;
              
             $operadores_symbol = array_values($operadores);
              var_dump($operadores_symbol);
              var_dump($ecuacion);
             $orden = 0;
             
             $concepto_id = rand(1,100);
             
             foreach($ecuacion as $k => $c){
                 
                 
                  // Registro  
                  
              //    $is_abierto = ( $p_abre_parentesis &&  ($operando1_def == true || $operando2_def == true )); || $p_abre_parentesis
                 
                 
                  if( ( $operando1_def == true && $operando2_def == true ) || $parentesis_completo    ) // || ($p_abre_parentesis && ($operador_enlace != '' || $operador != '') ) 
                  {
                       if($p_abre_parentesis)
                       {
                             // echo "  - - -- - - -  enlace  $k";
                             var_dump($operador_enlace);
                       }
                 
                      
                       if($parentesis_completo){
                          $grupo_actual = 0;
                         
                       }
                      
                    
                               if(  $operando1_def == true || $operando2_def == true){
                                      echo 'here '.$k;
                                      var_dump($operador_enlace_parentesis);
                                      var_dump($p_abre_parentesis);
                                      $values = array(
                                          'coops_enlace'        =>   ( $p_abre_parentesis == false) ? $operador_enlace : $operador_enlace_parentesis,
                                          'coops_operando1'     =>  $operando1,
                                          'coops_operando1_t'   =>  '0',
                                          'coops_operador'      =>  $operador,
                                          'coops_operando2'     =>  $operando2,
                                          'coops_operando2_t'   =>  '0',
                                          'coops_grupo'         =>  $grupo_actual,
                                          'conc_id'             =>  $concepto_id,
                                          'coops_orden'         =>  '0'

                                      );

                                      var_dump ($values);
                                      $this->conceptooperacion->registrar($values);
                                       
                                      
                                      if($p_abre_parentesis) $p_abre_parentesis = false;
                                      
                               }
                     
                       
                      $operando1 =  0;
                      $operando2 =  0;
                      $operando1_def = false;
                      $operando2_def = false;
                      $parentesis_completo = false;
                      
                      
                 /*     
                    if(!$p_abre_parentesis){
                        $operador_enlace = '';
                     }  
                   */   
                      $operador= '';
                     
                      
                    //  $p_abre_parentesis = false;
                      
                  }
                  
                  
                  
                 
                  if($c == '('){
                        $grupo_count++; 
                        $grupo_actual = $grupo_count;
                        $p_abre_parentesis = true;
                        
                  }
                  else if($c == ')'){
                      //  $p_abierto = false; 
                      //  $grupo_actual = 0;
                        $parentesis_completo = true;
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
                                echo 'Asignaa en '.$k.' : '.$operador_enlace_parentesis.' operador: '.$c." /operador";
                               // $operador = '';
                           }
                       }
                       else{  // SI NO ES OPERADOR ENTONCES PUEDE SER VARIABLE CONCEPTO O CONSTANTE 
                             
                              if($operando1_def == false){

                                   $operando1 = $c; 
                                   $operando1_def = true; 

                              }
                              else if( $operando2_def == false){

                                   $operando2 = $c; 
                                   $operando2_def = true;

                              }  
                            
                       }  
 
                  }
                   
             }
           
             
             
               echo "__END __ "; 
               if( $operador_enlace != '' || $operador != '' ){
                     $values = array(
                                  'coops_enlace'        =>  $operador_enlace,
                                  'coops_operando1'     =>  $operando1,
                                  'coops_operando1_t'   =>  '0',
                                  'coops_operador'      =>  $operador,
                                  'coops_operando2'     =>  $operando2,
                                  'coops_operando2_t'   =>  '0',
                                  'coops_grupo'         =>  $grupo_actual,
                                  'conc_id'             =>  $concepto_id,
                                  'coops_orden'         =>  '0'

                      );

                      var_dump ($values);
                      $this->conceptooperacion->registrar($values);

                      $operando1 =  0;
                      $operando2 =  0;
                      $operando1_def = false;
                      $operando2_def = false;
                      $parentesis_completo = false;
                      $operador_enlace = '';
                      $operador= '';
                      $grupo_actual = 0;
               }
           
       } 
       
       
   /// }
     
    
}
  