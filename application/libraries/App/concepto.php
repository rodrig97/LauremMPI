<?PHP
 
 /*
    Cambios rama principal xxx
 */  
 Class Concepto extends Table
 {
     
      
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
    
    public function get($conc_id)
    {

       $sql = " SELECT concs.*, 
                       tipos.plati_nombre as tipo_planilla, tipos.plati_key,  
                       conti.concti_nombre as concepto_tipo, 
                       oprint.opim_nombre as  impresion,
                       cpc.id_clasificador as id_clasificador,
                       cpc.cuentadebe_id,
                       cpc.cuentahaber_id,
                       (tipo_transaccion ||'.'|| generica || '.' || subgenerica || '.' || subgenerica_det || '.' || especifica || '.' || especifica_det ) as clasificador
                       ,part.descripcion as clasificador_nombre,
                      
                        ( CASE WHEN gr.gvc_nombre is null THEN 
                             '------- '
                        ELSE  
                             gr.gvc_nombre
                        END   ) as grupo_nombre,

                        cmm.*,

                        cosus.cosu_codigo,
                        cosus.cosu_descripcion   
                     
                      FROM planillas.conceptos concs
                      LEFT JOIN planillas.planilla_tipo tipos ON concs.plati_id = tipos.plati_id
                      LEFT JOIN planillas.concepto_tipo conti ON concs.conc_tipo = conti.concti_id
                      LEFT JOIN planillas.opcion_impresion oprint ON concs.conc_displayprint = oprint.opim_id
                      LEFT JOIN planillas.grupos_vc gr ON gr.gvc_id = concs.gvc_id    
                      LEFT JOIN planillas.conceptos_presu_cont cpc ON concs.conc_id = cpc.conc_id AND copc_estado = 1
                      LEFT JOIN pip.especifica_det part ON cpc.id_clasificador = part.id_clasificador AND cpc.ano_eje = part.ano_eje 
                      LEFT JOIN planillas.conceptos_min_max cmm ON cmm.conc_id = concs.conc_id AND cmm_estado = 1 
                      LEFT JOIN planillas.conceptos_sunat cosus ON concs.cosu_id = cosus.cosu_id 
                 WHERE concs.conc_id =  ? LIMIT 1 

                 ";
        
        $rs = $this->_CI->db->query($sql,array($conc_id))->result_array();
        
        return $rs[0];
    }
    

    public function get_list( $params = array() )
    {
        
        $q  = array();
        
        
        $sql = " SELECT concs.*, 
                        tipos.plati_nombre as tipo_planilla,
                        conti.concti_nombre as concepto_tipo, 
                        oprint.opim_nombre,
                          ( CASE WHEN gr.gvc_nombre is null THEN 
                                   '------- '
                              ELSE  
                                   gr.gvc_nombre
                              END   ) as grupo_nombre,
                              cpc.id_clasificador  

                       FROM planillas.conceptos concs
                       INNER JOIN planillas.planilla_tipo tipos ON concs.plati_id = tipos.plati_id AND plati_estado = 1
                       LEFT JOIN planillas.concepto_tipo conti ON concs.conc_tipo = conti.concti_id
                       LEFT JOIN planillas.opcion_impresion oprint ON concs.conc_displayprint = oprint.opim_id
                       LEFT JOIN planillas.grupos_vc gr ON gr.gvc_id = concs.gvc_id 
                       LEFT JOIN planillas.conceptos_presu_cont cpc ON concs.conc_id = cpc.conc_id AND copc_estado = 1
                       LEFT JOIN pip.especifica_det part ON cpc.id_clasificador = part.id_clasificador AND cpc.ano_eje = part.ano_eje
                       WHERE concs.conc_estado = 1 
                 ";
        
        
        if($params['tipoplanilla'] != '')
        {
            $sql.=" AND concs.plati_id = ?";
             $q[] = $params['tipoplanilla'];
        }

        if($params['tipoconcepto'] != '')
        {
        
            $sql.=" AND concs.conc_tipo = ?";
             $q[] = $params['tipoconcepto'];
        }
        
        if(trim($params['nombre']) != '')
        {
            $sql.=" AND concs.conc_nombre like '%".$params['nombre']."%'";
         //   $q[] = $params['nombre'];
        }
         
         if($params['sunat'] != '')
         {
         
             $sql.=" AND concs.cosu_id = ?";
             $q[] = $params['sunat'];
         }

         if($params['predeterminado'] != '')
         {
             $sql.=" AND concs.conc_esxdefecto = ?";
             $q[] = $params['predeterminado'];
         }


        if($params['grupo'] != '' )
        { 
            if(is_array($params['grupo']))
            {
               
               $tg = '';

               foreach($params['grupo'] as $i => $g)
               {  
                  if($i>0) $tg.=',';
                  $tg.= '?';
                  $q[] = $g;
               }

               $sql.=" AND concs.gvc_id IN (".$tg.") ";
  

            }
            else
            {
              $sql.=" AND concs.gvc_id = ?";
              $q[] = $params['grupo'];
            }

        }

 
        if($params['accesodirecto'] == '1'){
            $sql.=" AND concs.conc_accesodirecto = ?";
            $q[] = '1';
        }


        if( trim($params['afecto_cuarta']) != ''){

            $sql.=" AND (concs.conc_afecto_cuarta = ? ) ";
            $q[] =  trim($params['afecto_cuarta']);
        }


        if( trim($params['afecto_quinta']) != ''){

            if(trim($params['afecto_quinta'])  != QUINTA_TIPO_CONCEPTO_AMBOS ){

              $sql.=" AND (concs.conc_afecto_quinta = ? ) ";
              $q[] =  trim($params['afecto_quinta']);
            }
            else{

              $sql.=" AND (concs.conc_afecto_quinta IN (".QUINTA_TIPO_CONCEPTO_PROYECTABLE.",".QUINTA_TIPO_CONCEPTO_NOPROYECTABLE.") ) "; 
            }
            
        }

        if($params['partida'] != '')
        {
           if($params['partida'] != '0')
           {
             $sql.=" AND cpc.id_clasificador = ? ";
             $q[] = $params['partida'];
              
           }
           else
           {
               $sql.=" AND cpc.id_clasificador is null ";
           } 
           
        }

          
        
        $sql.=" 
                 ORDER BY tipo_planilla, concs.gvc_id, concs.conc_tipo, concs.conc_nombre   ";
        
        $rs = $this->_CI->db->query($sql,$q)->result_array();
        
        return $rs;
    }
    
    
    public function print_ecuacion($concepto_id , $return_text = true, $ecuacion_id = 0 )
    {
     
        if($ecuacion_id == 0)
        {
          
          $sql = " SELECT conc_ecuacion_id FROM planillas.conceptos conc WHERE conc_id = ? LIMIT 1 ";
          $t = $this->_CI->db->query($sql, array($concepto_id))->result_array();
          $ecuacion_id = $t[0]['conc_ecuacion_id'];

        }  
        
        $sql =" SELECT * FROM planillas.conceptos_ops  
                WHERE conc_id = ? AND coops_ecuacion_id = ? AND coops_estado = 1 
                       AND  ( coops_operando1 = 0 AND coops_operando1_t = 0 AND coops_operando2 = 0 AND coops_operando2_t = 0  ) = false  

                ORDER BY coops_id";
        
        $operaciones_data = $this->_CI->db->query($sql,array($concepto_id, $ecuacion_id ))->result_array();
 

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
         
        foreach($operaciones_data as $k => $ope)
        {
            
            $signo_enlace  = '';
            $signo_ope     = '';
              
            if($ope['coops_enlace'] != '0' && !$parentesis_a){
                 
                $operaciones[] = '<span class="pec_operador">'.$ope['coops_enlace'].'</span>';
            }
   
            if($n_id != $ope['coops_grupo']){
            
                if($parentesis_a)
                {
                     $operaciones[] = '<span class="pec_operador"> ) </span>';
                     $operaciones[] = '<span class="pec_operador">'.$ope['coops_enlace'].'</span>';
                     $parentesis_a = false;
                }
                
                if($ope['coops_grupo'] != 0)
                {
                     $operaciones[] = '<span class="pec_operador"> ( </span>';
                     $parentesis_a = true;
                }
              
                $n_id = $ope['coops_grupo'];
            }
            else{
                
                if($parentesis_a) $operaciones[] =  '<span class="pec_operador">'.$ope['coops_enlace'].'</span>';
            }
             
            
           if($ope['coops_operando1'] != '0' || ( $ope['coops_operando1'] == '0' && $ope['coops_operando1_t'] == '3' ) )
           {
                  
               if($ope['coops_operando1_t'] == '3'){  // CONSTANTE

                   $valor1 = $ope['coops_operando1'].' (Const)';

               } 

               if($ope['coops_operando1_t'] == '1'){ // VARIABLE 
                   
                   $sql =" SELECT vari_nombrecorto FROM planillas.variables WHERE vari_id = ?   LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $ope['coops_operando1']  ))->result_array();
                   $valor1 =  $t_rs[0]['vari_nombrecorto'].' (Var)'; 
               
               }

               if($ope['coops_operando1_t'] == '2'){ // CONCEPTO
                   
                   
                   $sql =" SELECT conc_nombrecorto FROM planillas.conceptos WHERE conc_id = ?   LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $ope['coops_operando1']  ))->result_array();
                   $valor1 =  $t_rs[0]['conc_nombrecorto'].' (Conc)'; 
                    
               } 

               $operaciones[] =  '<span class="pec_operando">'.$valor1.'</span>'; 
           
           } 

           
           if($ope['coops_operador'] != '0'){
                 
                $operaciones[] =  '<span class="pec_operador">'.$ope['coops_operador'].'</span>';  
           }
           
             
            if($ope['coops_operando2'] != '0' || ( $ope['coops_operando2'] == '0' && $ope['coops_operando2_t'] == '3' ) ) 
            {
           
               if($ope['coops_operando2_t'] == '3'){  // CONSTANTE

                   $valor2 = $ope['coops_operando2'].' (Const)';

               } 

               if($ope['coops_operando2_t'] == '1'){ // VARIABLE 
                   
                   
                   $sql =" SELECT vari_nombrecorto FROM planillas.variables WHERE vari_id = ?   LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $ope['coops_operando2']  ))->result_array();
                   $valor2 =  $t_rs[0]['vari_nombrecorto'].' (Var)';
                    
               }

               if($ope['coops_operando2_t'] == '2'){ // CONCEPTO
                   
                   $sql =" SELECT conc_nombrecorto FROM planillas.conceptos WHERE conc_id = ?   LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $ope['coops_operando2']  ))->result_array();
                   $valor2 =  $t_rs[0]['conc_nombrecorto'].' (Conc)';
                   
                     
               }  
               $operaciones[] = '<span class="pec_operando">'.$valor2.'</span>';   

           } 
            
        }
        
        
       if($parentesis_a){
           $operaciones[] = '<span class="pec_operador"> ) </span>';
           $parentesis_a= false;
       }

       if($return_text){ 
        
           $ecuacion = '';
           foreach($operaciones as $ope){
               $ecuacion.=' '.$ope;
           }

       }
       else{
          $ecuacion = $operaciones;
       }
        
       return $ecuacion;
    }
     

    public function print_ecuacion_encode($concepto_id = 0, $tipo_planilla = 0, $ecuacion_id = 0 )
    {
        

        if($ecuacion_id == 0)
        {
          
          $sql = " SELECT conc_ecuacion_id FROM planillas.conceptos conc WHERE conc_id = ? LIMIT 1 ";
          $t = $this->_CI->db->query($sql, array($concepto_id))->result_array();
          $ecuacion_id = $t[0]['conc_ecuacion_id'];

        }  
        
        $sql =" SELECT * FROM planillas.conceptos_ops  
                WHERE conc_id = ? AND coops_ecuacion_id = ? AND coops_estado = 1 
                     AND  ( coops_operando1 = 0 AND coops_operando1_t = 0 AND coops_operando2 = 0 AND coops_operando2_t = 0  ) = false 
                ORDER BY coops_id";
        
        $operaciones_data = $this->_CI->db->query($sql,array($concepto_id, $ecuacion_id ))->result_array();
     

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
       
        foreach($operaciones_data as $k => $ope){
            
            $signo_enlace  = '';
            $signo_ope     = '';
              
            if($ope['coops_enlace'] != '0' && !$parentesis_a){
                 
                $operaciones[] = trim($ope['coops_enlace']);
            }
   
            if($n_id != $ope['coops_grupo']){
            
                if($parentesis_a)
                {
                     $operaciones[] = ')';
                     $operaciones[] =  trim($ope['coops_enlace']);
                     $parentesis_a = false;
                }
                
                if($ope['coops_grupo'] != 0)
                {
                     $operaciones[] = '(';
                     $parentesis_a = true;
                }
              
                $n_id = $ope['coops_grupo'];
            }
            else{
                
                if($parentesis_a) $operaciones[] = trim($ope['coops_enlace']);
            }
             
            
           if($ope['coops_operando1'] != '0' || ( $ope['coops_operando1'] == '0' && $ope['coops_operando1_t'] == '3' ) )
           {
                  
               if($ope['coops_operando1_t'] == '3'){  // CONSTANTE

               
                   $valor1 = $ope['coops_operando1'];
               } 

               if($ope['coops_operando1_t'] == '1'){ // VARIABLE 
                   
                   $valor1=  'va'.$tipo_planilla.'|'.$ope['coops_operando1'];
               
               }

               if($ope['coops_operando1_t'] == '2'){ // CONCEPTO
                   
                   $valor1=  'co'.$tipo_planilla.'|'.$ope['coops_operando1'];
                     
               } 

               $operaciones[] =  $valor1; 
           
           }
         

           
           if($ope['coops_operador'] != '0'){
                 
                $operaciones[] =  $ope['coops_operador']; 
           }
           
             
          if($ope['coops_operando2'] != '0' || ( $ope['coops_operando2'] == '0' && $ope['coops_operando2_t'] == '3' ) ){
           
               if($ope['coops_operando2_t'] == '3'){  // CONSTANTE

                   $valor2 = $ope['coops_operando2'];

               } 

               if($ope['coops_operando2_t'] == '1'){ // VARIABLE 
                     
                   $valor2=  'va'.$tipo_planilla.'|'.$ope['coops_operando2']; 
               }

               if($ope['coops_operando2_t'] == '2'){ // CONCEPTO
                    
                   $valor2=  'co'.$tipo_planilla.'|'.$ope['coops_operando2'];  
                   
                     
               }  
               $operaciones[] = $valor2;   

           }
         
            
        }
        
        
       if($parentesis_a){
           $operaciones[] = ')';
           $parentesis_a= false;
       }
      
       $lineas = array();
       $linea = array();
       $ecuacion = '';
 
       $p = 1;
       
       for($i =0 ; $i<sizeof($operaciones); $i++){
       
           $ope = $operaciones[$i];
       
           if($p == 1)
           {
               if($ope == '('){
                   $linea[] = 1;
                    $p++;
               }else{
                    $linea[] = 0; 
                    $linea[] = $ope; 
                     $p = $p +2;
               }
               
           }
           else if($p==2){
               $linea[] = $ope; 
               $p++;
           }
           else if($p == 3){
                if($ope == ')'){
                   $linea[] = 1;
                    $p++;
               }else{
                    $linea[] = 0; 
                    $linea[] = $ope; 
                    $p = $p +2;
               }
           }

           if($p == 4 ){
              
               $i++;
               $linea[] = $operaciones[$i]; 
               $p++;
           }
           
            
           if($p>=4){
       
               $lineas[]=$linea;
       
               $linea = array();
               $p = 1;
           }
          
       }
       
        if(sizeof($linea) > 0 ){
              $lineas[]=$linea;
        }
       
        
       return $lineas;
    }
     
    
    public function procesar($plaec_id, $is_plaec = true, $plaemp_id = 0  )
    { 
      
      // echo $plaec_id.'<br/>';
      
       $args = func_get_args();
      
       if($is_plaec)
       { 
           $sql         = " SELECT * FROM planillas.planilla_empleado_concepto 
                            WHERE plaec_id = ? LIMIT 1";
           $rs          = $this->_CI->db->query($sql, array($plaec_id))->result_array();
           
           $conc_id     = trim($rs[0]['conc_id']);
           $plaemp_id   = trim($rs[0]['plaemp_id']);
           $ecuacion_id = $rs[0]['conc_ecuacion_id'];
       
       }
       else
       {
            $conc_id     = $plaec_id;  
            
            $sql         = " SELECT * FROM planillas.planilla_empleado_concepto 
                             WHERE plaemp_id = ? AND conc_id = ?  AND plaec_estado = 1 LIMIT 1 ";
            $rs          = $this->_CI->db->query($sql, array( $plaemp_id, $conc_id))->result_array();
            
            $plaec_id    = trim($rs[0]['plaec_id']);
            $conc_id     = trim($rs[0]['conc_id']);
            $plaemp_id   = trim($rs[0]['plaemp_id']);
            $ecuacion_id = $rs[0]['conc_ecuacion_id'];
            // PLAEMP_ID se define como parametro;
       }   
      

       if( $conc_id =='' || $plaec_id == '' ||  $ecuacion_id == '' ||  $ecuacion_id == '0')
       {
          return 0;
       }
      
      

       $sql =" SELECT * FROM planillas.conceptos_ops 
               WHERE coops_ecuacion_id = ? AND conc_id = ? AND coops_estado = 1 
                     AND  ( coops_operando1 = 0 AND coops_operando1_t = 0 AND coops_operando2 = 0 AND coops_operando2_t = 0  ) = false 
               ORDER BY coops_id";
       
       $operaciones_data = $this->_CI->db->query($sql,array($ecuacion_id, $conc_id))->result_array();
      
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
       

       if(sizeof($operaciones_data) == 0){

          return 0;
       }

       // 1ro GENERAMOS LA ECUACION
        
       foreach($operaciones_data as $k => $ope){
           
           $signo_enlace  = '';
           $signo_ope     = '';
             
           if($ope['coops_enlace'] != '0' && !$parentesis_a){
                
               $operaciones[] = $ope['coops_enlace'];
           }
      
           if($n_id != $ope['coops_grupo']){
            
               if($parentesis_a)
               {
                    $operaciones[] = ')';
                    $operaciones[] = $ope['coops_enlace'];
                    $parentesis_a = false;
               }
               
               if($ope['coops_grupo'] != 0)
               {
                    $operaciones[] = '(';
                    $parentesis_a = true;
               }
              
               $n_id = $ope['coops_grupo'];
           }
           else{
               
               if($parentesis_a) $operaciones[] = $ope['coops_enlace'];
           }
            
           
          if($ope['coops_operando1'] != '0' || ( $ope['coops_operando1'] == '0' && $ope['coops_operando1_t'] == '3' ) ) 
          {
                 
              if($ope['coops_operando1_t'] == '3'){  // CONSTANTE

                  $valor1 = $ope['coops_operando1'];

              } 

              if($ope['coops_operando1_t'] == '1'){ // VARIABLE 
                  
                  $sql =" SELECT plaev_valor FROM planillas.planilla_empleado_variable 
                          WHERE plaemp_id = ? AND vari_id = ? AND plaev_estado = 1 
                          LIMIT  1";
                  $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando1']  ))->result_array();
                  $valor1 = ($t_rs[0]['plaev_valor'] != '') ? $t_rs[0]['plaev_valor'] :  '0';
      
              }
      

              if($ope['coops_operando1_t'] == '2')
              { // CONCEPTO
               
                  $sql =" SELECT plaec_id, plaec_value, plaec_marcado, plaec_calculado 
                          FROM planillas.planilla_empleado_concepto 
                          WHERE plaemp_id = ? AND conc_id = ? AND plaec_estado = 1  
                          LIMIT  1";

                  $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando1']  ))->result_array();
                  
                    
                   if($t_rs[0]['plaec_id'] != '') // Si el empleado tiene el concepto asignado
                   {   
                      
                        if(  $t_rs[0]['plaec_calculado'] == '0' && $t_rs[0]['plaec_marcado'] == '1' )
                        {
                              
                             $valor1 =  $this->procesar($ope['coops_operando1'], false, $plaemp_id);
                        }
                        else
                        {
                        
                            $valor1 = ($t_rs[0]['plaec_value'] != '0') ? $t_rs[0]['plaec_value'] :  '0';
                            if($t_rs[0]['plaec_marcado'] == '0' ) $valor1 = '0'; 
                        }
                   
                   }
                   else{

                      $valor1 = '0';
                   }                     

                  
              } 

              $operaciones[] = $valor1;
          
          }
          
          if($ope['coops_operador'] != '0'){
                
               $operaciones[] = $ope['coops_operador'];
          }
          
            
          if($ope['coops_operando2'] != '0' || ( $ope['coops_operando2'] == '0' && $ope['coops_operando2_t'] == '3' ) ) 
          {
          
              if($ope['coops_operando2_t'] == '3'){  // CONSTANTE

                  $valor2 = $ope['coops_operando2'];

              } 

              if($ope['coops_operando2_t'] == '1'){ // VARIABLE 
                  
                    
                  $sql =" SELECT plaev_valor FROM planillas.planilla_empleado_variable 
                          WHERE plaemp_id = ? AND vari_id = ? AND plaev_estado = 1 LIMIT  1";
                  $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando2']  ))->result_array();
                  $valor2 = ($t_rs[0]['plaev_valor'] != '') ? $t_rs[0]['plaev_valor'] :  '0';
               
              }

              if($ope['coops_operando2_t'] == '2'){ // CONCEPTO
                  
                  $sql =" SELECT plaec_id, plaec_value, plaec_marcado,plaec_calculado 
                          FROM planillas.planilla_empleado_concepto 
                          WHERE plaemp_id = ? AND conc_id = ? AND plaec_estado = 1  
                          LIMIT  1";

                  $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando2']  ))->result_array();
                  /*
                  if($t_rs[0]['plaec_value']==''){
                       $this->procesar($ope['coops_operando2'], false, $plaemp_id);
                  }
                  else{
                       $valor2 = ($t_rs[0]['plaec_value'] != '0') ? $t_rs[0]['plaec_value'] :  '0';
                  }*/



                   if($t_rs[0]['plaec_id'] != '') // Si el empleado tiene el concepto asignado
                   {   
                     
                   
                     if(  $t_rs[0]['plaec_calculado']=='0' && $t_rs[0]['plaec_marcado'] == '1' ){
                       
                           $valor2 = $this->procesar($ope['coops_operando2'], false, $plaemp_id);
                        
                      }
                      else{
                          $valor2 = ($t_rs[0]['plaec_value'] != '0') ? $t_rs[0]['plaec_value'] :  '0';

                          if($t_rs[0]['plaec_marcado'] == '0' ) $valor2 = '0'; 
                      }
                   
                   }
                   else{

                      $valor2 = '0';
                   } 


              }  
              $operaciones[] = $valor2;

          }
           
       }
       
       
      if($parentesis_a){
          $operaciones[] =')';
          $parentesis_a= false;
      }
      
      
      /*if($conc_id == '13')
      {
        var_dump($operaciones);
      }*/

       // BUSCAMOS PARENTESIS 
      $pos_parentesis_i = -1;
      $pos_parentesis_f = 0;
      
       for($i= 0; $i<=sizeof($operaciones) -1; $i++)
       {
          if( ''.$operaciones[$i] == '('){  $pos_parentesis_i = $i;     }  
          if( ''.$operaciones[$i] == ')'){  $pos_parentesis_f = $i; break; }  
       } 
       
       if(  $pos_parentesis_i > -1 ) $operaciones_parentesis = array_slice($operaciones, ($pos_parentesis_i +1 ), ($pos_parentesis_f - 1 -$pos_parentesis_i) );
      
       
      
       while($pos_parentesis_i > -1)
       {
         
           $total = $this->_calcular_total_fragmento($operaciones_parentesis); 
           
          /* if($conc_id == '13')
           {
             var_dump($total);
           }*/
      
           array_splice($operaciones, $pos_parentesis_i , ($pos_parentesis_f - $pos_parentesis_i + 1), $total  ); // Reemplazar los elementos por el valor del parentesis resuelto
         
           $pos_parentesis_i = -1;
           $pos_parentesis_f = 0; 
           
           for($i= 0; $i<=sizeof($operaciones) -1; $i++)
           {
                  if( (''.$operaciones[$i]) == '('){  $pos_parentesis_i = $i;     }  // echo 'Entro aqui: '. $operaciones[$i]; var_dump($operaciones[$i]); 
                  if( (''.$operaciones[$i]) == ')'){  $pos_parentesis_f = $i; break; }  
           } 
            
          if(  $pos_parentesis_i > -1 ) $operaciones_parentesis = array_slice($operaciones, ($pos_parentesis_i +1 ), ($pos_parentesis_f - 1 -$pos_parentesis_i) );
           
       } 
      

       $total_calculado = $this->_calcular_total_fragmento($operaciones);
       $value_pre = $total_calculado;
       $value = $total_calculado;

      
       $sql = "SELECT * FROM planillas.conceptos_min_max WHERE conc_id = ? AND cmm_estado = 1 LIMIT 1 ";
       $rs  = $this->_CI->db->query($sql, array($conc_id))->result_array();
       $mm   = $rs[0];


       if($mm['cmm_id'] != '') // Si tiene una restriccion minimo maximo
       { 

             $this->_CI->load->library(array('App/planillaempleadoconcepto','App/planillaempleadoconceptomov'));
             
             if(  $mm['cmm_from'] == MINMAX_RANGO_ESTATICO  )
             {
                  $min = $mm['cmm_min'] * 1;
                  $max = $mm['cmm_max'] * 1;              
             }  
             else{
                 
                 // Obtener min max desde conceptos
             }



             if($mm['cmm_modo'] == MINMAX_MODO_ASEGURABLE_AFP)
             { 
      
                   $sql = " SELECT pepe.afp_id, afcv_seguros, afcv_max_asegurable   
                            FROM planillas.planilla_empleados plaemp
                            INNER JOIN rh.persona_pension pepe ON plaemp.peaf_id = pepe.peaf_id AND pepe.peaf_estado = 1
                            INNER JOIN planillas.afps_porcentajes ap ON pepe.afp_id = ap.afp_id AND ap.afcv_estado = 1 
                            WHERE plaemp.plaemp_id = ? 
                            LIMIT 1  
                          ";
      

                   list($mm_rs) = $this->_CI->db->query($sql, array($plaemp_id) )->result_array();       

                   if($mm_rs['afp_id'] != '' && $mm_rs['afp_id'] != '0')
                   {

                       $porcentaje    = $mm_rs['afcv_seguros'] * 1;
                       $max_segurable = $mm_rs['afcv_max_asegurable'] * 1;

                       $max = $max_segurable * $porcentaje / 100;
      
                       // MODIFICACION SOPORTE AFP 11000 en un mes en varias boletas 01/05/2014  

                       $datos = $this->_CI->planillaempleadoconcepto->get_full_info($plaec_id); 

                       $sql = " SELECT SUM(pec.plaec_value) as total
                                FROM planillas.planilla_empleado_concepto pec
                                INNER JOIN planillas.planilla_empleados pem ON pec.plaemp_id = pem.plaemp_id AND pem.plaemp_estado = 1
                                INNER JOIN planillas.planillas pla ON pem.pla_id = pla.pla_id AND pla.pla_estado = 1 
                                WHERE pec.plaec_estado = 1  AND 
                                      pec.plaec_marcado = 1 AND 
                                      pla.pla_anio = ? AND 
                                      pla.pla_mes = ? AND 
                                      pem.indiv_id = ? AND 
                                      pec.conc_id = ?  ";

                       $rs = $this->_CI->db->query($sql, array($datos['pla_anio'], $datos['pla_mes'], $datos['indiv_id'], $datos['conc_id']))->result_array();          
                       $total_acumulado = $rs[0]['total'];
                         
                       $total_acumulado = ($total_acumulado*1); // + $total_calculado;

                       // FIN 



                       /*if($total_calculado > $max)
                       {
                          $value = $max;
                          $rest = $total_calculado - $max;
                          $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                   'plaecm_res' => $rest,
                                                                                   'cmm_id' => $mm['cmm_id'] ));
                       }*/
                       
                       if($total_acumulado > $max)
                       {
                          $value = 0;
                          $rest = $total_calculado;
                          $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                   'plaecm_res' => $rest,
                                                                                   'cmm_id' => $mm['cmm_id'] ));
                       }
                       else if(  ($total_acumulado + $total_calculado)  > $max)
                       {

                           $value = $max - $total_acumulado;
                           $rest = $total_calculado - $value;
                           $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                    'plaecm_res' => $rest,
                                                                                    'cmm_id' => $mm['cmm_id'] ));
                       }

                   }

             }
             else if($mm['cmm_modo'] == MINMAX_MODO_NORMAL )
             {

                    if($total_calculado < $min)
                    {
                        if($min > 0)
                        {                  
                             $value = $min;
                             $add = $min - $total_calculado;

                             $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                      'plaecm_add' => $add,
                                                                                      'cmm_id' => $mm['cmm_id'] ));
                        }
                    } 
                    else if($total_calculado > $max)
                    {

                         $value = $max;
                         $rest = $total_calculado - $max;

                         $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                  'plaecm_res' => $rest,
                                                                                  'cmm_id' => $mm['cmm_id'] ));
                    }

             }
             else if( $mm['cmm_modo'] == MINMAX_MODO_ACUMULADO ) // ESTO SE USA POR ESSALUD CONFIGURADO 
             {

                    $sql = " SELECT pla.plati_id, plaev_valor 
                             FROM planillas.planilla_empleado_variable plaev 
                             LEFT JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_id = plaev.plaemp_id   
                             LEFT JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id 
                             WHERE plaev.plaemp_id = ? AND plaev.plaev_estado = 1 AND plaev.vari_id = ? LIMIT 1";
               
                    $rs_hl = $this->_CI->db->query($sql, array($plaemp_id, VARI_ASISTENCIA_CS ))->result_array();
                    $tiempo_trabajado = $rs_hl[0]['plaev_valor'];
                  
                    /*
                        Comprobación de caso con planilla de reintegros @REVISAR
                    */
                    if( $rs_hl[0]['plati_id'] != TIPOPLANILLA_CONSCIVIL  || ($tiempo_trabajado != '' &&  ($tiempo_trabajado*1) > 0) )  // SOLO SI EL TIEMPO TRABAJADO ES MAYOR QUE CERO 
                    { 
                    
                        $datos = $this->_CI->planillaempleadoconcepto->get_full_info($plaec_id); 

                        $sql = " SELECT SUM(pec.plaec_value) as total
                                 FROM planillas.planilla_empleado_concepto pec
                                 INNER JOIN planillas.planilla_empleados pem ON pec.plaemp_id = pem.plaemp_id AND pem.plaemp_estado = 1
                                 INNER JOIN planillas.planillas pla ON pem.pla_id = pla.pla_id AND pla.pla_estado = 1 
                                 WHERE pec.plaec_estado = 1  AND 
                                       pec.plaec_marcado = 1 AND 
                                       pla.pla_anio = ? AND 
                                       pla.pla_mes = ? AND 
                                       pem.indiv_id = ? AND 
                                       pec.conc_id = ?  ";

                        $rs = $this->_CI->db->query($sql, array($datos['pla_anio'], $datos['pla_mes'], $datos['indiv_id'], $datos['conc_id']))->result_array();          
                        $total_acumulado = $rs[0]['total'];
      

                        $acumulado = ($total_acumulado*1) + $total_calculado;
                      

                        $sql = " SELECT    SUM(pecm.plaecm_prestado) as prestado, 
                                           SUM(pecm.plaecm_pagado) as pagado, 
                                           SUM(pecm.plaecm_excedente) as excedente
                                     
                                     FROM planillas.planilla_empleado_concepto_mov pecm
                                     INNER JOIN planillas.planilla_empleado_concepto pec ON pecm.plaec_id = pec.plaec_id AND pec.plaec_estado = 1
                                     INNER JOIN planillas.planilla_empleados pem ON pec.plaemp_id = pem.plaemp_id AND pem.plaemp_estado = 1
                                     INNER JOIN planillas.planillas pla ON pem.pla_id = pla.pla_id AND pla.pla_estado = 1 
                                     WHERE pecm.plaecm_estado = 1  AND pla.pla_anio = ? AND pla.pla_mes = ? AND pem.indiv_id = ? AND pec.conc_id = ?  ";

                        $rs = $this->_CI->db->query($sql, array($datos['pla_anio'], $datos['pla_mes'], $datos['indiv_id'], $datos['conc_id']))->result_array();    
                           
                        $montos = $rs[0];
                        $prestado = ($montos['prestado'] == '' ? 0 : $montos['prestado'] ) * 1;
                        $pagado =  ($montos['pagado'] == '' ? 0 : $montos['pagado'] ) * 1;  
                         
                        $debe = $prestado - $pagado;  
                          
                        if( $acumulado < $min)
                        {  

                             if($min > 0)
                             {      
                                 $prestar = $min - $acumulado;
                                 $value+= $prestar;

                                 $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                          'plaecm_prestado' => $prestar,
                                                                                          'cmm_id' => $mm['cmm_id'] ));
                             }   
                        }
                        else if( $acumulado > $max )
                        {

                             if( $debe > 0)
                             {
                                // DEBE $min $max 
                                  if($value >= $debe)
                                  {
                                      $value-= $debe;
                                      $pagar = $debe;

                                  }
                                  else{

                                       $pagar = $value;
                                       $value = 0;
                                  } 

                                  $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                           'plaecm_pagado' => $pagar,
                                                                                           'cmm_id' => $mm['cmm_id'] ));

                                  $acumulado-=$pagar;

                             } 

                             if($acumulado > $max)
                             { 
            
                                 $excendente = $acumulado - $max;
                                 $value-= $excendente;

                                 $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                           'plaecm_excedente' => $excendente,
                                                                                           'cmm_id' => $mm['cmm_id'] ));

                             }  
                           

                        }
                        else{

          
                             if( $debe > 0)
                             {
                                // DEBE $min $max 
                                if($value >= $debe)
                                {
                                    $value-= $debe;
                                    $pagar = $debe;

                                }
                                else{

                                     $pagar = $value;
                                     $value = 0;
                                } 

                                $this->_CI->planillaempleadoconceptomov->registrar(array('plaec_id' => $plaec_id,
                                                                                         'plaecm_pagado' => $pagar,
                                                                                         'cmm_id' => $mm['cmm_id'] ));

                             } 
      
                        }


                   }


             }
             else{


             }
      
         }

         if($value < 0)
         {
             $value = 0;
         }
        

       $sql = " UPDATE planillas.planilla_empleado_concepto 
                SET plaec_value = ?, 
                    plaec_value_pre = ?, 
                    plaec_calculado = 1 
                WHERE plaec_id = ? ";
      
      
       $this->_CI->db->query($sql, array( round($value,2), round($value_pre,2),$plaec_id));
      
      
       return $value;  
        
    }

    public function preview_ecuacion($plaec_id, $is_plaec = true, $plaemp_id = 0 )
    { 
  
        if($is_plaec)
        { 
            $sql = " SELECT * FROM planillas.planilla_empleado_concepto WHERE plaec_id = ? LIMIT 1";
            $rs = $this->_CI->db->query($sql, array($plaec_id))->result_array();
            
            $conc_id = trim($rs[0]['conc_id']);
            $plaemp_id = trim($rs[0]['plaemp_id']);
            $ecuacion_id = $rs[0]['conc_ecuacion_id'];
        
        }else{

             $conc_id = $plaec_id;

             $sql = " SELECT * FROM planillas.planilla_empleado_concepto WHERE plaemp_id = ? AND conc_id = ?  AND plaec_estado = 1 LIMIT 1 ";
             $rs = $this->_CI->db->query($sql, array($plaec_id))->result_array();
            
             $conc_id = trim($rs[0]['conc_id']);
             $plaemp_id = trim($rs[0]['plaemp_id']);
             $ecuacion_id = $rs[0]['conc_ecuacion_id'];
             
        }   


        if( $conc_id =='' || $plaec_id == '' ||  $ecuacion_id == '' ||  $ecuacion_id == '0')
        {
           return 0;
        }
    
        /*
        $sql =" SELECT conc_ecuacion_id FROM planillas.conceptos WHERE conc_id = ? LIMIT 1 ";
        $t = $this->_CI->db->query($sql,array($conc_id))->result_array();
        $ecuacion_id = $t[0]['conc_ecuacion_id'];
        */ 

        $sql =" SELECT * FROM planillas.conceptos_ops 
                WHERE coops_ecuacion_id = ? AND conc_id = ? AND coops_estado = 1 
                     AND  ( coops_operando1 = 0 AND coops_operando1_t = 0 AND coops_operando2 = 0 AND coops_operando2_t = 0  ) = false 
                ORDER BY coops_id";
        
        $operaciones_data = $this->_CI->db->query($sql,array($ecuacion_id, $conc_id))->result_array();


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
            
                if($parentesis_a)
                {
                     $operaciones[] = ')';
                     $operaciones[] = $ope['coops_enlace'];
                     $parentesis_a = false;
                }
                
                if($ope['coops_grupo'] != 0)
                {
                     $operaciones[] = '(';
                     $parentesis_a = true;
                }
                
                $n_id = $ope['coops_grupo'];
            }
            else{
                
                if($parentesis_a) $operaciones[] = $ope['coops_enlace'];
            }
             
            
           if($ope['coops_operando1'] != '0' || ( $ope['coops_operando1'] == '0' && $ope['coops_operando1_t'] == '3' ) ) 
           {
                  
               if($ope['coops_operando1_t'] == '3'){  // CONSTANTE

                   $valor1 = $ope['coops_operando1'];

               } 

               if($ope['coops_operando1_t'] == '1'){ // VARIABLE 
                   
                   $sql =" SELECT plaev_valor FROM planillas.planilla_empleado_variable WHERE plaemp_id = ? AND vari_id = ? AND plaev_estado = 1 LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando1']  ))->result_array();
                   $valor1 = ($t_rs[0]['plaev_valor'] != '') ? $t_rs[0]['plaev_valor'] :  '0';
               
               }

               if($ope['coops_operando1_t'] == '2'){ // CONCEPTO
                   
                   $sql =" SELECT plaec_value FROM planillas.planilla_empleado_concepto 
                           WHERE plaemp_id = ? AND conc_id = ? AND plaec_estado = 1 AND plaec_calculado = 1 LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando1']  ))->result_array();
                   
                   $valor1 = ($t_rs[0]['plaec_value'] != '') ? $t_rs[0]['plaec_value'] :  '0';
                   
               } 

               $operaciones[] = $valor1;
           
           }
           
           if($ope['coops_operador'] != '0'){
                 
                $operaciones[] = $ope['coops_operador'];
           }
           
             
           if($ope['coops_operando2'] != '0' || ( $ope['coops_operando2'] == '0' && $ope['coops_operando2_t'] == '3' ) ){
           
               if($ope['coops_operando2_t'] == '3'){  // CONSTANTE

                   $valor2 = $ope['coops_operando2'];

               } 

               if($ope['coops_operando2_t'] == '1'){ // VARIABLE 
                   
                     
                   $sql =" SELECT plaev_valor FROM planillas.planilla_empleado_variable WHERE plaemp_id = ? AND vari_id = ? AND plaev_estado = 1 LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando2']  ))->result_array();
                   $valor2 = ($t_rs[0]['plaev_valor'] != '') ? $t_rs[0]['plaev_valor'] :  '0';
                
               }

               if($ope['coops_operando2_t'] == '2'){ // CONCEPTO
                   
                   $sql =" SELECT plaec_value FROM planillas.planilla_empleado_concepto 
                           WHERE plaemp_id = ? AND conc_id = ? AND plaec_estado = 1 AND plaec_calculado = 1 LIMIT  1";
                   $t_rs =$this->_CI->db->query($sql,array( $plaemp_id, $ope['coops_operando2']  ))->result_array();
                   
                   $valor2 = ($t_rs[0]['plaec_value'] != '') ? $t_rs[0]['plaec_value'] :  '0';

               }  
               $operaciones[] = $valor2;

           }
            
        }
        
        
       if($parentesis_a){
           $operaciones[] =')';
           $parentesis_a= false;
       }

       

 

        return $operaciones;  


    }

    
    
    private function _calcular_total_fragmento($operaciones = array()){
        
       $pos_mul_div = 0;
       $operador = '';
       // BUSCAMOS MULTIPLICACION Y DIVISION
 

       for($i= 0; $i<=sizeof($operaciones) -1; $i++){
           
           if((trim($operaciones[$i]) == 'x') || (trim($operaciones[$i]) == '/'))
           {
              $operador = (trim($operaciones[$i]) == 'x') ? 'x' : '/';
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

               if(( trim($operaciones[$i]) == 'x') || ( trim($operaciones[$i]) == '/')){
                  $operador = ( trim($operaciones[$i]) == 'x') ? 'x' : '/';
                  $pos_mul_div = $i;      
                  break;
               }  

           }
            
       }
       
       
       // LA ECUACION QUEDA SOLO CON SUMAS Y RESTAS  
         $total+=$operaciones[0];
         foreach($operaciones as $o => $x){

                if( trim($x) == '+'){
                     $total+=$operaciones[$o+1];
                }
                else if( trim($x)=='-')
                {
                     $total-=$operaciones[$o+1];
                }

          }
          (string) $total;
          return $total;
        
    }
    

 
     
    
     public function get_aplicables($tipo_planilla, $ops)
     {
        
        $params = array($tipo_planilla);
        
        $sql = " SELECT concs.*,
                       cpc.id_clasificador as partida_id, 
                       cpc.copc_id as afectacion_id 
                       
                       FROM planillas.conceptos concs 
                       LEFT JOIN  planillas.conceptos_presu_cont cpc ON concs.conc_id = cpc.conc_id AND copc_estado = 1
                 WHERE concs.conc_estado = 1 AND concs.plati_id = ? ";
        
        if($ops['predeterminado'] != ''){
             
            $sql.= " AND conc_esxdefecto = ? ";
            $params[] = ($ops['predeterminado']) ? '1' : '0'; 
                
        }
        
        
        return $this->_CI->db->query($sql,$params)->result_array();
         
    }
    
    
     public function get_operando_conceptos($conc_id)
     {
         
        $rs = array();
        
        $sql  =" SELECT  distinct(conc.conc_id) as concepto_id, 
                         conc_nombre, 
                         conc_nombrecorto, 
                         plati.plati_nombre, 
                         conc.conc_ecuacion_id,
                         conc.conc_key as concepto_key 
                 
                 FROM planillas.conceptos_ops ops  
                 INNER JOIN planillas.conceptos conc ON ops.conc_id = conc.conc_id AND ops.coops_ecuacion_id = conc.conc_ecuacion_id
                 LEFT JOIN planillas.planilla_tipo plati ON plati.plati_id = conc.plati_id  
                
                 WHERE  ops.coops_estado = 1 AND 
                        conc.conc_estado = 1 AND  
                        ( ( coops_operando1 = ? AND coops_operando1_t = ".TIPOOPERANDO_CONCEPTO."  ) OR ( coops_operando2 = ? AND coops_operando2_t = ".TIPOOPERANDO_CONCEPTO."  ) )";
        
        $rs =  $this->_CI->db->query($sql,array($conc_id,$conc_id))->result_array();
        
        return $rs;
        
    }  
     
     
    public function actualizar_estado_planilla($pla_id, $conc_id, $estado)
    {

       $sql ="UPDATE planillas.planilla_empleado_concepto plaec
              SET plaec_marcado = ?, plaec_marcado_bk =  plaec_marcado 
              WHERE plaec.plaec_estado = 1 AND plaec.conc_id = ? 
                    AND plaemp_id in (
                       SELECT plaemp_id FROM planillas.planilla_empleados plaemp WHERE pla_id = ? AND plaemp_estado = 1
                    ) 
            ";

       return ($this->_CI->db->query($sql, array($estado,$conc_id,$pla_id))) ? true : false;     
    }


    public function get_just_directos($params = array())
    {
 
       $sql = " SELECT * FROM planillas.conceptos conc
                LEFT JOIN planillas.empleado_concepto empcon ON conc.conc_id = empcon.conc_id AND empcon.indiv_id = ? AND empcon.empcon_estado = 1 
                WHERE conc.conc_accesodirecto = 1 AND conc.conc_estado = 1 AND conc.plati_id = ?
                ORDER BY conc.conc_nombre

              ";

      
 
      return $this->_CI->db->query($sql, array($params['indiv_id'], $params['plati_id']))->result_array();


    }
    

    public function delete($conc_to_del)
    {
       
       
         $this->_CI->load->library(array('App/conceptooperacion'));
         $this->_CI->db->trans_begin();
 
         $concs_relacionados = $this->get_operando_conceptos($conc_to_del);
 
         
         foreach($concs_relacionados as $conc)
         {

            $conc_id = $conc['concepto_id'];

            $ecuacion_id_anterior = $conc['conc_ecuacion_id'];

            $ecuacion_id = $this->_CI->conceptooperacion->get_next_ecuacion_id($conc_id);
            //$ecuacion_id_anterior = $ecuacion_id - 1;
 

            $sql = "INSERT INTO planillas.conceptos_ops 
                         (coops_enlace, coops_operando1, coops_operando1_t, coops_operador, 
                          coops_operando2, coops_operando2_t, coops_grupo, conc_id, 
                          coops_orden, coops_estado, coops_ecuacion_id ) 
                    (SELECT   coops_enlace, coops_operando1, coops_operando1_t, coops_operador, coops_operando2, 
                              coops_operando2_t, coops_grupo, conc_id, coops_orden, coops_estado, ?
                      FROM planillas.conceptos_ops 
                      WHERE conc_id = ? AND  coops_ecuacion_id = ? AND coops_estado = 1  ORDER BY coops_id)"; 

             $this->_CI->db->query($sql, array($ecuacion_id, $conc_id, $ecuacion_id_anterior ));


             /* 
             $sql = "UPDATE planillas.conceptos_ops SET coops_estado = 0 WHERE conc_id =  ? AND coops_ecuacion_id = ? "; 
             $this->_CI->db->query($sql, array($conc_id, $ecuacion_id_anterior));
             */

             $sql = "UPDATE planillas.conceptos_ops SET coops_key = md5(coops_id || 'ecuacoop') WHERE conc_id =  ? AND coops_ecuacion_id = ? "; 
             $this->_CI->db->query($sql, array($conc_id, $ecuacion_id));

             $sql = "UPDATE planillas.conceptos_ops SET coops_operando1 = 0, coops_operando1_t = 3 
                      WHERE (coops_operando1_t = 2 AND coops_operando1 = ? )  AND coops_estado  = 1 AND conc_id =  ? AND coops_ecuacion_id = ? "; 

             $this->_CI->db->query($sql, array( $conc_to_del, $conc_id, $ecuacion_id));

             $sql = "UPDATE planillas.conceptos_ops SET coops_operando2 = 0, coops_operando2_t = 3 
                      WHERE (coops_operando2_t = 2 AND coops_operando2 = ? ) AND coops_estado  = 1 AND conc_id =  ? AND coops_ecuacion_id = ? "; 

             $this->_CI->db->query($sql, array( $conc_to_del, $conc_id, $ecuacion_id));


             $this->_CI->conceptooperacion->save_id_ecuacion($ecuacion_id,  $conc_id);


         } 

         /*
         $sql = " UPDATE planillas.planilla_empleado_concepto SET plaec_estado = 0 WHERE plaec_estado = 1  AND conc_id = ? ";
         $this->_CI->db->query($sql, array($conc_to_del)); 
         */

         $sql =" UPDATE planillas.empleado_concepto SET empcon_estado = 0 WHERE conc_id = ? AND empcon_fecmod = now() ";
         $this->_CI->db->query($sql, array($conc_to_del) );


         $sql= " UPDATE planillas.conceptos SET conc_estado = 0 WHERE conc_id = ? ";
         $this->_CI->db->query($sql, array($conc_to_del));


         if ($this->_CI->db->trans_status() === FALSE)
         {
            $this->_CI->db->trans_rollback();
            $ok= false;
         }
         else
         {
            $this->_CI->db->trans_commit();
            $ok = true;
         }    
  

          return $ok; 
    }


    public function registrar_min_max($params = array())
    {
  
        $sql = " UPDATE planillas.conceptos_min_max SET cmm_estado = 0 
                 WHERE  conc_id = ?  ";

        $this->_CI->db->query($sql, array($params['concepto']));

        $sql = " INSERT INTO planillas.conceptos_min_max(conc_id, cmm_min, cmm_max,  cmm_from, cmm_modo, cmm_ajuste, cmm_obs  ) 
                 VALUES( ? , ? , ? , ? , ?, ?, ? ) ";

        if($params['minimo'] == ''){
           $params['minimo'] = '0';
        }          

        if($params['maximo'] == ''){
           $params['maximo'] = '1000000';
        }          

        $data = array(
                   $params['concepto'], $params['minimo'], $params['maximo'], '0', $params['modo_calculo'], $params['ajuste_de'], $params['obs']

                );

        $rs = $this->_CI->db->query($sql, $data);

        return ($rs) ? true : false;
    }


    public function quitar_min_max($params)
    {
 
        $sql = " UPDATE planillas.conceptos_min_max SET cmm_estado = 0 
                 WHERE  conc_id = ?  ";

        $rs = $this->_CI->db->query($sql, array($params['concepto']));
 
        return ($rs) ? true : false;

    }
    

    public function actualizar_planillas($conc_id, $id_nueva_ecuacion, $values = array(), $delete_concepto = false )
    {
        
        $this->_CI->load->library(array('App/planillaempleadovariable'));


        if($delete_concepto)
        {
          
            $sql = "  UPDATE planillas.planilla_empleado_concepto  
                      SET  plaec_estado = 0
                      WHERE conc_id = ? AND plaemp_id IN( 
                                           SELECT plaemp_id
                                           FROM planillas.planilla_empleados plaemp
                                           INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1
                                           INNER JOIN planillas.planilla_movimiento mov ON mov.pla_id = pla.pla_id AND mov.plamo_estado = 1  AND mov.plaes_id = ?
                                         ) ";

            $this->_CI->db->query($sql, array( $conc_id, ESTADOPLANILLA_ELABORADA ) );

            return true;

        }


        $sql = "  UPDATE planillas.planilla_empleado_concepto  
                  SET gvc_id = ?,  conc_tipo = ?
                  WHERE conc_id = ?  AND plaemp_id IN ( 
                                       SELECT plaemp_id
                                       FROM planillas.planilla_empleados plaemp
                                       INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1
                                       INNER JOIN planillas.planilla_movimiento mov ON mov.pla_id = pla.pla_id AND mov.plamo_estado = 1  AND mov.plaes_id = ?
                                     )  ";

        $this->_CI->db->query($sql, array($values['gvc_id'], $values['conc_tipo'],  $conc_id,  ESTADOPLANILLA_ELABORADA ) );


        /* */ 
        $sql = "  UPDATE planillas.planilla_empleado_concepto  
                  SET clasificador_id = ?
                  WHERE conc_id = ?  AND plaemp_id IN ( 
                                       SELECT plaemp_id
                                       FROM planillas.planilla_empleados plaemp
                                       INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_afectacion_presu = ".PLANILLA_AFECTACION_ESPECIFICADA_X_DETALLE."
                                       INNER JOIN planillas.planilla_movimiento mov ON mov.pla_id = pla.pla_id AND mov.plamo_estado = 1  AND mov.plaes_id = ?
                                     )  ";

        $this->_CI->db->query($sql, array($values['clasificador'],  $conc_id,  ESTADOPLANILLA_ELABORADA ) );





        $sql = "SELECT current_data.plaemp_id, 
                       current_data.platica_id, 
                       current_data.variable, 
                       plaev.plaev_id, empvar.empvar_id,
  
                ( CASE WHEN empvar.empvar_id is null THEN
                  varis.vari_valordefecto
                        ELSE    
                  empvar.empvar_value
                  END ) as valor,

                ( CASE WHEN empvar.empvar_id is null THEN
                  varis.vari_displayprint
                        ELSE    
                  empvar.empvar_displayprint
                  END ) as displayprint,
                  varis.vtd_id,
                  vtd.y_value_key 
                   
              FROM (

                                    SELECT plaemp.plaemp_id, plaemp.platica_id, plaemp.indiv_id, fv.variable 
                                              
                                              FROM planillas.planillas pla
                                              
                                              INNER JOIN planillas.planilla_movimiento mov ON pla.pla_id = mov.pla_id AND plamo_estado = 1 AND plaes_id = ".ESTADOPLANILLA_ELABORADA." 
                                              INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1
                                              INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec.conc_id = ? AND plaec.conc_ecuacion_id != ?
                     
                                              LEFT JOIN (

                                                      SELECT  ops.conc_id, coops_operando1 as variable
                                                      FROM planillas.conceptos_ops ops  
                                                      WHERE ops.coops_operando1_t = 1  AND  ops.conc_id = ? AND ops.coops_ecuacion_id =  ?

                                                      UNION

                                                      SELECT ops.conc_id, coops_operando2 as variable
                                                      FROM planillas.conceptos_ops ops  
                                                      WHERE ops.coops_operando2_t = 1  AND  ops.conc_id = ? AND ops.coops_ecuacion_id =  ?

                                                      ORDER BY conc_id, variable 

                                              ) as fv ON plaec.conc_id = fv.conc_id 
                                              
                                            
                                    
                                    WHERE pla.pla_estado = 1  

                                    GROUP BY  plaemp.plaemp_id, plaemp.platica_id, plaemp.indiv_id, fv.variable  
                                    ORDER BY  plaemp.plaemp_id, plaemp.platica_id, plaemp.indiv_id, fv.variable 
                              
                              ) as current_data   

                  INNER JOIN planillas.variables varis ON current_data.variable = varis.vari_id AND varis.vari_estado = 1 
                  LEFT JOIN planillas.variables_tabla_datos vtd ON varis.vtd_id = vtd.vtd_id
                  LEFT JOIN planillas.planilla_empleado_variable plaev ON current_data.plaemp_id = plaev.plaemp_id AND current_data.variable = plaev.vari_id   AND plaev.plaev_estado = 1 
                  LEFT JOIN planillas.empleado_variable empvar ON current_data.variable = empvar.vari_id AND current_data.indiv_id = empvar.indiv_id AND empvar.empvar_estado = 1

                  WHERE plaev.plaev_id is null
                  
                  ORDER BY current_data.plaemp_id, current_data.platica_id, current_data.variable
              
               ";   


        $variables_a_vincular =  $this->_CI->db->query($sql, array($conc_id, $id_nueva_ecuacion, $conc_id, $id_nueva_ecuacion, $conc_id, $id_nueva_ecuacion ) )->result_array();
        
       
        foreach($variables_a_vincular as $vari)
        {

             $static_data['y_values'] = array('platica_id' => $vari['platica_id']); 

             $data = array( 'plaemp_id'          =>  $vari['plaemp_id'], 
                            'vari_id'            =>  $vari['variable'],
                            'plaev_valor'        =>  $vari['valor'],
                            'plaev_procedencia'  =>  ( ($vari['empvar_id'] != '' && $vari['empvar_id'] != '0' ) ? PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO : PROCENDENCIA_VARIABLE_VALOR_XDEFECTO ),
                            'plaev_displayprint' =>  $vari['displayprint']);

             $static_data['vtd_id']      = $vari['vtd_id'];
             $static_data['y_value_key'] = $vari['y_value_key'];
       
             $this->_CI->planillaempleadovariable->registro_directo($data, false, $static_data);
        } 

    
        $sql = " UPDATE planillas.planilla_empleado_concepto plaec 
                 SET    conc_ecuacion_id = ? 
                 WHERE  conc_id = ?  AND  conc_ecuacion_id != ? 
                        AND plaemp_id IN ( 
                                                  SELECT plaemp.plaemp_id 
                                                  FROM planillas.planillas pla 
                                                  INNER JOIN planillas.planilla_movimiento mov ON pla.pla_id = mov.pla_id AND plamo_estado = 1 AND plaes_id = ".ESTADOPLANILLA_ELABORADA." 
                                                  LEFT JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1
                                                )  

                 "; 

      $this->_CI->db->query($sql, array($id_nueva_ecuacion, $conc_id, $id_nueva_ecuacion) );      
      
 
    }   


    public function get_list_reporte($params)
    {

        $sql ="  SELECT * FROM  planillas.conceptos 
                 WHERE conc_tipo = ? AND plati_id = ?  AND conc_estado = 1 ";
 
        if(trim($params['nombre']) != '')
        {
            $sql.=" AND conc_nombre like '%".$params['nombre']."%'";
        }
  
        $sql.="  ORDER BY conc_nombre  ";


        $rs = $this->_CI->db->query($sql ,array( $params['tipoconcepto'], $params['plati_id']) )->result_array();

        return $rs;
    }



    public function get_conceptos_ecuacion($concepto_id, $ecuacion_id = 0 )
    {
     
        if($ecuacion_id == 0)
        {
          
          $sql = " SELECT conc_ecuacion_id FROM planillas.conceptos conc WHERE conc_id = ? LIMIT 1 ";
          $t = $this->_CI->db->query($sql, array($concepto_id))->result_array();
          $ecuacion_id = $t[0]['conc_ecuacion_id'];

        }  
        
         $sql =" SELECT * FROM planillas.conceptos_ops  
                WHERE conc_id = ? AND coops_ecuacion_id = ? AND coops_estado = 1 
                       AND  ( coops_operando1 = 0 AND coops_operando1_t = 0 AND coops_operando2 = 0 AND coops_operando2_t = 0  ) = false  

                ORDER BY coops_id";
         
         $operaciones_data = $this->_CI->db->query($sql,array($concepto_id, $ecuacion_id ))->result_array();

         $conceptos = array();

         foreach ($operaciones_data as $reg)
         {
               if($reg['coops_operando1_t'] == TIPOOPERANDO_CONCEPTO)
               {
                  $conceptos[] = $reg['coops_operando1'];
               } 

               if($reg['coops_operando2_t'] == TIPOOPERANDO_CONCEPTO)
               {
                  $conceptos[] = $reg['coops_operando2'];
               } 
         }
  
        return $conceptos;

    }


    public function get_info_conceptos($conceptos = array(), $params = array())
    {

         if(sizeof($conceptos) == 0)  $conceptos = array('0'); 

         $in_conceptos = implode(',', $conceptos);

         $con = ($params['not_in'] === true ) ?' NOT IN ' : ' IN ';

         $con_cosu = ($params['conceptosunat_obligatorio'] === true) ? ' INNER ' : ' LEFT ';

         $sql_tipo = ($params['conc_tipo'] != '0' && $params['conc_tipo'] != '') ? ' AND conc_tipo = '.$params['conc_tipo'] : '';

         $sql = " SELECT concs.*, 
                         cosu.cosu_codigo, cosu.cosu_descripcion  
                  FROM planillas.conceptos concs
                  ".$con_cosu." JOIN planillas.conceptos_sunat cosu ON concs.cosu_id = cosu.cosu_id 
                  WHERE concs.conc_estado = 1 AND concs.plati_id = ? AND concs.conc_id ".$con." ( ".$in_conceptos." ) ".$sql_tipo;

         $rs = $this->_CI->db->query($sql, array($params['plati_id']))->result_array();

         return $rs;

    }


 }