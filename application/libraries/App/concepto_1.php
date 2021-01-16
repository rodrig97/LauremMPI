 <?PHP
 
  
 Class Concepto extends Table{
     
      
    protected $_FIELDS=array(   
                                    'id'    => 'conc_id',
                                    'code'  => 'conc_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'conc_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'conceptos';
    protected $_PREF_TABLE= 'XCONCEPTOSMPI'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
    
    public function get_list($params = array()){
        
        $q  = array();
        
        
        $sql = " SELECT concs.*, tipos.plati_nombre as tipo_planilla,  conti.concti_nombre as concepto_tipo, oprint.opim_nombre

                 FROM planillas.conceptos concs
                 LEFT JOIN planillas.planilla_tipo tipos ON concs.plati_id = tipos.plati_id
                 LEFT JOIN planillas.concepto_tipo conti ON concs.conc_tipo = conti.concti_id
                 LEFT JOIN planillas.opcion_impresion oprint ON concs.conc_showprint = oprint.opim_id

                 WHERE concs.conc_estado = 1 
                 ";
        
       if($params['tipoplanilla'] != ''){
            $sql.=" AND concs.plati_id = ?";
             $q[] = $params['tipoplanilla'];
        }
        
        if($params['nombre'] != ''){
            $sql.=" AND concs.conc_nombre like '%".$params['nombre']."%'";
         //   $q[] = $params['nombre'];
        }
        
        
        
        $sql.=" 
                 ORDER BY concs.conc_nombre,concs.conc_tipo, tipo_planilla ";
        
        $rs = $this->_CI->db->query($sql,$q)->result_array();
        
        return $rs;
    }
    
    
    public function procesar($plaec_id)
    { // $planilla_empleado_concepto
        
         // get operaciones
      //   echo " EMPLEADO_CONCEPTO: ".$plaec_id."<br/>";
         
        $sql = " SELECT * FROM planillas.planilla_empleado_concepto WHERE plaec_id = ? LIMIT 1";
        $rs = $this->_CI->db->query($sql, array($plaec_id))->result_array();
        
        $conc_id = trim($rs[0]['conc_id']);
        $plaemp_id = trim($rs[0]['plaemp_id']);
         
     //   echo " CONCEPTO: ".$conc_id."<br/>";
      
        
        $sql =" SELECT * FROM planillas.conceptos_ops WHERE conc_id = ? ORDER BY coops_id";
        
        $operaciones_data = $this->_CI->db->query($sql,array($conc_id))->result_array();
        
        $operaciones = array();
        
        $parentesis_a =  false;  // Pareentesis abierto
        $n_id = 0;
        
        $nivel_signo = array(   '+' => 1,
                                '-' => 1,
                                '*' => 2,
                                '/' => 2);
        $monto  = 0;
        $valor1 = 0;
        $valor2 = 0;
        $current_operacion = 0;
        
        // 1ro GENERAMOS LA ECUACION
        foreach($operaciones_data as $k => $ope){
            
            $signo_enlace  = '';
            $signo_ope     = '';
              
            if($ope['coops_enlace'] != '0' && !$parentesis_a){
                 
                $operaciones[] = $ope['coops_enlace'];
            }
            
            if($n_id != $ope['coops_grupo']){
                 
                
                $operaciones[] = ($parentesis_a) ? ')' : '(';
                
                if($parentesis_a) $operaciones[] = $ope['coops_enlace'];
                
                $parentesis_a = !$parentesis_a;
                $n_id = $ope['coops_grupo'];
            }
            else{
                
                if($parentesis_a) $operaciones[] = $ope['coops_enlace'];
            }
             
            
           if($ope['coops_operando1'] != '0'){
                  
               if($ope['coops_operando1_t'] == '3'){  // CONSTANTE

                   $valor1 = $ope['coops_operando1'];

               } 

               if($ope['coops_operando1_t'] == '1'){ // VARIABLE 
                   
                   $sql =" SELECT plaev_valor FROM planillas.planilla_empleado_variable WHERE plaemp_id = ? AND vari_id = ? AND plaev_estado = 1 LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando1']  ))->result_array();
                   $valor1 = ($t_rs[0]['plaev_valor'] != '') ? $t_rs[0]['plaev_valor'] :  0;
               
               }

               if($ope['coops_operando1_t'] == '2'){ // CONCEPTO
                   
                   $sql =" SELECT plaec_value FROM planillas.planilla_empleado_concepto 
                           WHERE plaemp_id = ? AND conc_id = ? AND plaec_estado = 1 AND plaec_calculado = 1 LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando1']  ))->result_array();
                   
                   if($t_rs[0]['plaec_value']==''){
                        $this->procesar($ope['coops_operando1']);
                   }
                   else{
                        $valor1 = ($t_rs[0]['plaec_value'] != '0') ? $t_rs[0]['plaec_value'] :  0;
                   }
                  
                   
               } 

               $operaciones[] = $valor1;
           
           }
           
           if($ope['coops_operador'] != '0'){
                 
                $operaciones[] = $ope['coops_operador'];
           }
           
             
           if($ope['coops_operando2'] != '0'){
           
               if($ope['coops_operando2_t'] == '3'){  // CONSTANTE

                   $valor2 = $ope['coops_operando2'];

               } 

               if($ope['coops_operando2_t'] == '1'){ // VARIABLE 
                   
                     
                   $sql =" SELECT plaev_valor FROM planillas.planilla_empleado_variable WHERE plaemp_id = ? AND vari_id = ? AND plaev_estado = 1 LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando2']  ))->result_array();
                   $valor2 = ($t_rs[0]['plaev_valor'] != '') ? $t_rs[0]['plaev_valor'] :  0;
                
               }

               if($ope['coops_operando2_t'] == '2'){ // CONCEPTO
                   
                   $sql =" SELECT plaec_value FROM planillas.planilla_empleado_concepto 
                           WHERE plaemp_id = ? AND conc_id = ? AND plaec_estado = 1 AND plaec_calculado = 1 LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando2']  ))->result_array();
                   
                   if($t_rs[0]['plaec_value']==''){
                        $this->procesar($ope['coops_operando2']);
                   }
                   else{
                        $valor2 = ($t_rs[0]['plaec_value'] != '0') ? $t_rs[0]['plaec_value'] :  0;
                   }

               }  
               $operaciones[] = $valor2;

           }
            
        }
        
        
       if($parentesis_a){
           $operaciones[] =')';
           $parentesis_a= false;
       }
      /*  echo "ECUACION : ";
       foreach($operaciones as $o => $x){
           echo " $x ";
           
       } 
       echo " | "; */
       // ECUACION LISTA PARA EL CALCULO
       
      
       
       
       /*  Buscar los parentesis 
            Calcular el itnerno del paretesis y luego extraerlo
        *  
        */
       $pos_parentesis = 0;
       
        // BUSCAMOS PARENTESIS 
       for($i= 0; $i<=sizeof($operaciones) -1; $i++){
           
           if(($operaciones[$i] == 'x') || ($operaciones[$i] == '/')){
              $operador = ($operaciones[$i] == 'x') ? 'x' : '/';
              $pos_mul_div = $i;      
              break;
           }  
         
       } 
       
       
       
       
       
       
       $pos_mul_div = 0;
       $operador = '';
       // BUSCAMOS MULTIPLICACION Y DIVISION
       for($i= 0; $i<=sizeof($operaciones) -1; $i++){
           
           if(($operaciones[$i] == 'x') || ($operaciones[$i] == '/')){
              $operador = ($operaciones[$i] == 'x') ? 'x' : '/';
              $pos_mul_div = $i;      
              break;
           }  
         
       }
       
       while($pos_mul_div != 0){ //$existe_multi_divi
              
            $nv = ($operador == 'x') ?  ($operaciones[$pos_mul_div-1] * $operaciones[$pos_mul_div+1])  :  ($operaciones[$pos_mul_div-1] / $operaciones[$pos_mul_div+1]);
            array_splice($operaciones, ($pos_mul_div-1), 3, $nv  );
        
            // Buscamos si hay multi o divi
           $pos_mul_div = 0;
           $operador    =  '';
           for($i= 0; $i<=sizeof($operaciones) -1; $i++){

               if(($operaciones[$i] == 'x') || ($operaciones[$i] == '/')){
                  $operador = ($operaciones[$i] == 'x') ? 'x' : '/';
                  $pos_mul_div = $i;      
                  break;
               }  

           }
            
       }
       
       
       // LA ECUACION QUEDA SOLO CON SUMAS Y RESTAS  
         $total+=$operaciones[0];
         foreach($operaciones as $o => $x){

                if($x == '+'){
                     $total+=$operaciones[$o+1];
                }
                else if($x=='-')
                {
                     $total-=$operaciones[$o+1];
                }

          }
          
       //  echo "<br/> TOTAL : $total";
        
        
        $sql = " UPDATE planillas.planilla_empleado_concepto SET plaec_value = ?, plaec_calculado = 1 WHERE plaec_id = ? ";
        $this->_CI->db->query($sql, array($total,$plaec_id));
        
        return $total;  
       
      
    }
    
    
    private function calcular_total_fragmento($ecu = array()){
        
    }
    
    
    public function procesar_bkk($conc_id){
        
         // get operaciones
        
        
        $sql =" SELECT * FROM planillas.conceptos_ops WHERE conc_id = ? ORDER BY coops_id";
        
        $operaciones_data = $this->_CI->db->query($sql,array($conc_id))->result_array();
        
    //    var_dump($operaciones_data);
        
        $operaciones = array();
        
        $parentesis_a =  false;  // Pareentesis abierto
        $n_id = 0;
        
        $nivel_signo = array(   '+' => 1,
                                '-' => 1,
                                '*' => 2,
                                '/' => 2);
        $monto  = 0;
        $valor1 = 0;
        $valor2 = 0;
        $current_operacion = 0;
        
        // Generamos la ecuacion
        foreach($operaciones_data as $k => $ope){
            
            $signo_enlace  = '';
            $signo_ope     = '';
             
            
              
            if($ope['coops_enlace'] != '0' && !$parentesis_a){
                 
                $operaciones[] = $ope['coops_enlace'];
            }
           
            
            if($n_id != $ope['coops_grupo']){
                 
                
                $operaciones[] = ($parentesis_a) ? ')' : '(';
                
                if($parentesis_a) $operaciones[] = $ope['coops_enlace'];
                
                $parentesis_a = !$parentesis_a;
                $n_id = $ope['coops_grupo'];
            }
            else{
                
                if($parentesis_a) $operaciones[] = $ope['coops_enlace'];
            }
            
            
          
           
           if($ope['coops_operando1'] != '0'){
                  
               if($ope['coops_operando1_t'] == '0'){  // CONSTANTE

                   $valor1 = $ope['coops_operando1'];

               } 

               if($ope['coops_operando1_t'] == '1'){ // VARIABLE 

               }

               if($ope['coops_operando1_t'] == '2'){ // CONCEPTO

               } 

               $operaciones[] = $valor1;
           
           }
           
           if($ope['coops_operador'] != '0'){
                 
                $operaciones[] = $ope['coops_operador'];
           }
           
             
           if($ope['coops_operando2'] != '0'){
           
               if($ope['coops_operando2_t'] == '0'){  // CONSTANTE

                   $valor2 = $ope['coops_operando2'];

               } 

               if($ope['coops_operando2_t'] == '1'){ // VARIABLE 

               }

               if($ope['coops_operando2_t'] == '2'){ // CONCEPTO

               }  
               $operaciones[] = $valor2;

           }
           
           
           
        }
       if($parentesis_a){
           $operaciones[] =')';
           $parentesis_a= false;
       }
        
       foreach($operaciones as $o => $x){
           echo " $x ";
           
       }
       var_dump($operaciones);
       // ECUACION LISTA PARA EL CALCULO
       
       $pos_mul_div = 0;
       $operador = '';
       
       //$existe_multi_divi = false;
       
       // Buscamos si hay multi o divi
       for($i= 0; $i<=sizeof($operaciones) -1; $i++){
           
           if(($operaciones[$i] == 'x') || ($operaciones[$i] == '/')){
              $operador = ($operaciones[$i] == 'x') ? 'x' : '/';
              $pos_mul_div = $i;      
              break;
           }  
         
       }
        /*
       
       foreach($operaciones as $o => $x){
            if($x == 'x' ||$x == '/' ){
                $existe_multi_divi =  true;
               // echo "<br/> encontrado en : ".$o;
            }
       }*/
       
       while($pos_mul_div != 0){ //$existe_multi_divi
              
           echo "<br/><br/>";
           /*foreach($operaciones as $o => $x){
               echo " $x ";

           }
            */
            var_dump($operaciones);
          /*
            $ind = 0;
           foreach($operaciones as $o => $x){
            //  $nv = ($x == 'x') ?  ($operaciones[$o-1] * $operaciones[$o+1])  :  ($operaciones[$o-1] / $operaciones[$o+1]);
               if(($x == 'x') || ($x == '/')){
                   
               }  
             
           } */
            $nv = ($operador == 'x') ?  ($operaciones[$pos_mul_div-1] * $operaciones[$pos_mul_div+1])  :  ($operaciones[$pos_mul_div-1] / $operaciones[$pos_mul_div+1]);
            array_splice($operaciones, ($pos_mul_div-1), 3, $nv  );
           
          // echo "<br/> $nv ";
           /*
           $existe_multi_divi = false;
           foreach($operaciones as $o => $x){  
               if($x == 'x' ||$x == '/' ){
               $existe_multi_divi =  true;
             //  echo "<br/> encontrado en : ".$o. "  ".$x;
               
               }
           }*/
            
            // Buscamos si hay multi o divi
           $pos_mul_div = 0;
           $operador    =  '';
           for($i= 0; $i<=sizeof($operaciones) -1; $i++){

               if(($operaciones[$i] == 'x') || ($operaciones[$i] == '/')){
                  $operador = ($operaciones[$i] == 'x') ? 'x' : '/';
                  $pos_mul_div = $i;      
                  break;
               }  

           }
            
       }
       
       
       /*
      $ex_parentesis = false;
      $n = 0;
      foreach($operaciones as $o => $x){
       //     echo " $x ";
        //  array_splice();
          
          if($x == '+' || $x == '-'){
               $nv = ($x == '+') ?  ($operaciones[$o-1] + $operaciones[$o+1])  :  ($operaciones[$o-1] - $operaciones[$o+1]);
              array_splice($operaciones, ($o-1), 2, $nv  );
          }
          else if($x == 'x' || $x == '/'){
              $nv = ($x == 'x') ?  ($operaciones[$o-1] * $operaciones[$o+1])  :  ($operaciones[$o-1] / $operaciones[$o+1]);
              array_splice($operaciones, ($o-1), 2, $nv  );
          }
          else{
              
          }
      }   */
       
      echo "<br/><br/>";
      foreach($operaciones as $o => $x){
           echo " $x ";
           
       }
       
      
       
      $total+=$operaciones[0];
          
      
      foreach($operaciones as $o => $x){
            
            if($x == '+'){
                 $total+=$operaciones[$o+1];
            }
            else if($x=='-')
            {
                 $total-=$operaciones[$o+1];
            }
            
       } 
       echo "<br/> TOTAL : $total";
        
      
    }
    
     public function get_aplicables($tipo_planilla){
        
        $params = array($tipo_planilla);
        
        $sql = " SELECT * FROM planillas.conceptos concs
                 WHERE concs.conc_estado = 1 AND concs.plati_id = ? ";
        
        return $this->_CI->db->query($sql,$params)->result_array();
         
    }
    
    public function get_value_concepto(){
        
    }
     
    public function set_value_concepto(){
        
    }
    
     
     
 }