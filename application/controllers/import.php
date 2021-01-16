<?php
 
 
class import extends CI_Controller 
{
     
    public function __construct(){
        
        parent::__construct();
        
        
    }
    
    public function acciones(){
        
    } 
    
    
    public function update_keys()
    {
        /*
     
        
        $tabla = 'dependencias';
        $f_key = 'depe_key';
        $f_id  = 'depe_id';
        $pref  = 'depen';
       
         
        $tabla = 'niveles';
        $f_key = 'niv_key';
        $f_id  = 'niv_id';
        $pref  = 'nive';
         
        
        $tabla = 'metas_siaf';
        $f_key = 'mesi_key';
        $f_id  = 'mesi_id';
        $pref  = 'MESI';
        
         
        $tabla = 'metas_sag';
        $f_key = 'mesa_key';
        $f_id  = 'mesa_id';
        $pref  = 'MESA';
       
        
           $tabla = 'unidades_medida';
        $f_key = 'unmed_key';
        $f_id  = 'unmed_id';
        $pref  = 'UNMED';
         *  $tabla = 'acciones';
        $f_key = 'acc_key';
        $f_id  = 'acc_id';
        $pref  = 'action';
       
         $tabla = 'meta_objetivo_accion';
        $f_key = 'meac_key';
        $f_id  = 'meac_id';
        $pref  = 'MEAC';
        
         $tabla = 'users';
        $f_key = 'user_key';
        $f_id  = 'user_id';
        $pref  = 'USER';
        */
        
        $tabla = 'sigerhu.planilla_tipos';
        $f_key = 'plati_key';
        $f_id  = 'plati_id';
        $pref  = 'PLATI';
        
        $sql ='SELECT * FROM '.$tabla;
        
        $q  = $this->db->query($sql)->result_array();
        
       foreach ($q as $record){
           
           $md5 = md5($pref.$record[$f_id]);
           
           $sql2 = 'UPDATE '.$tabla.' SET '.$f_key.' = \''.$md5.'\' WHERE '.$f_id.' = '.$record[$f_id];
           $this->db->query($sql2);
       }
        
        
    }
    
    
    public function set_meta_table(){
        
        
         
         $sql = ' SELECT * FROM sag ';
        
         $q  = $this->db->query($sql)->result_array();
        
           foreach ($q as $meta){
                
               
               $id = trim($meta['id_system']);
               
               $siaf = trim($meta['siaf_system']);
               $siaf = ($siaf=='') ? '0' : $siaf;
               
               $sag_parent = trim($meta['sag_parent']);
               $sag_parent = ($sag_parent=='') ? '0' : $sag_parent;
               
               $nombre =  trim($meta['Ingresos']);
               $codigo = trim($meta['nombre']);
                
               $v_tipo=explode('-',$codigo);
               $es_componente  = ( trim($v_tipo[1])!= '') ? '1' : '0';
               
               
               $sql2 = 'SELECT * FROM sag_presupuesto WHERE meta = ? LIMIT 1';
               $detalle_meta =   $this->db->query($sql2, array($codigo) )->result_array();
               
               $presu  = trim($detalle_meta[0]['Presupuesto']);
                
               $presu  = ($presu == '') ? '0' : $presu;
               
               
               $depe = trim($detalle_meta[0]['depe']);
               $depe  = ($depe == '') ? '0' : $depe;
               
               
               //obtener presupuesto y meta_padre
               $sql_insert = 'INSERT INTO metas_sag (mesa_id,mesa_nombre,mesa_codigo,mesi_id,mesa_id_parent,mesa_montopia,depe_id) 
                               VALUES (?,?,?,?,?,?,?)';
               
                $this->db->query($sql_insert, array($id,$nombre,$codigo,$siaf,$sag_parent,$presu,$depe) );
               
           }
        
         
         
        
    }
    
    
    public function update_meta_accion(){
        
          $sql = ' SELECT * FROM acciones ';
        
         $q  = $this->db->query($sql)->result_array();
        
           foreach ($q as $accion){
                
               $sql_meob=  'SELECT  FROM meta_objetivos WHERE niv_id = ';
               
           }
        
        
    }
    
    public function generar_usuarios(){
        
        $sql = 'SELECT  * FROM dependencias ORDER BY depe_nombre ';
        $dependencias =   $this->db->query($sql )->result_array();
            
        foreach($dependencias as $depe){
             
            $nombre = 'Usuario '.trim($depe['depe_nombrecorto']);
            $nick = '2011'.trim($depe['depe_nombrecorto']);
            $n1 = rand(1, 9);
            $n2 = rand(1, 9);
            $pass =  trim($depe['depe_nombrecorto']).'2011'.$n1.$n2;
            $pass= strtolower($pass);
            $nick = strtolower($nick);
            $sql = 'INSERT INTO users (user_nombre, user_nick, user_pass, depe_id  ) VALUES(?,?,?,?)';
            $this->db->query($sql , array($nombre,$nick,$pass,$depe['depe_id']) );
            $user_id =$this->db->insert_id();
            
            $sql = 'UPDATE users SET  t_depenombre = ? WHERE user_id = ? ';
            $this->db->query($sql , array(trim($depe['depe_nombre']),$user_id) );
        }
        
        
    }
    
    
    public function buscar_estrategicas()
    {
        
      /*  $sql = 'SELECT * FROM acciones WHERE acc_tipo = 2';
        $rs = $this->db->query($sql )->result_array();
        
        foreach($rs as $accion){
            
            $sql_obj = 'SELECT * FROM meta_objetivos WHERE niv_id = ? LIMIT 1';
            
            
        } 
       * 
-- sacar la dependencia y el objetivo general padre 
-- luego verificar si la dependencia tiene el general 
-- sino lo tiene se genera
-- recuperar la meta objetivo, recuperar una meta asociada de la dependencia principal
-- generar meta_objetivo  ( meta recuperada y objetivo 
-- generar meta_objetivo_accion 
 
       *   
       * 
       */
        
        
        // Estrategicas 
        
        $sql = ' SELECT * FROM acciones WHERE acc_tipo = 2 AND depe_id != 0 ';
        
        $q = $this->db->query($sql)->result_array();
        
        foreach($q as $acc){
            
            $acc_id    = $acc['acc_id'];
            $depe      =  $acc['depe_id'];
            $obj_espe  = $acc['t_especifico_id'];
            
            $sql2 = ' SELECT niv_parent FROM niveles WHERE niv_id = '.$obj_espe;
            $qGen = $this->db->query($sql)->result_array();
            $obj_gen   = $qGen['niv_parent'];
            
            $sql3 = ' ';
            
            // meta objetivo
            
            $sql3 = 'SELECT mesa_id FROM metas_sag WHERE depe_id = '.$depe.' LIMIT 1';
            $qMeta = $this->db->query($sql3)->result_array();
            $meta = $qMeta[0]['mesa_id'];
            
               $sqlMeob = "INSERT INTO meta_objetivos(mesa_id, niv_id) VALUES('".$meta."','".$obj_espe."') ";
            
            // META, OBJETIVO
           //  echo $sqlMeob.'<br/>';
               $this->db->query($sqlMeob); 
              $meob_id =   $this->db->insert_id();
            // recupero el meob_id
            
           // $this->_CI->db->insert('meta_objetivo_accion', array('meob_id' => '', 'accion' => '') );
            $sqlAcc = "INSERT INTO meta_objetivo_accion (meob_id, acc_id) VALUES('".$meob_id."','".$acc_id."') ";
            $this->db->query($sqlAcc);
            $this->db->insert_id();
            // insertar partida servicios directos y 
            echo $sqlAcc.'<br/><br/>';
        }
        
        
        
        $sql = ' ';
        
         
        
        
    }
    
    
    public function financiar_estrategicas(){
        
        
        
        $sql = ' Select * from acciones where acc_tipo = 2 and acc_saldoini > 0 and acc_estado = 1 and acc_estrategica_v = 0 and  t_tarea is not null order by acc_id ';
        
        $q = $this->db->query($sql)->result_array();
        
        foreach($q as $acc){
            
            $acc_id    = $acc['acc_id'];
            $depe      = $acc['depe_id'];
            $obj_espe  = $acc['t_especifico_id'];
            $tarea     = $acc['t_tarea'];
            $saldoIni  = $acc['acc_saldoini'];
            /*
            $sql2 = ' SELECT niv_parent FROM niveles WHERE niv_id = '.$obj_espe;
            $qGen = $this->db->query($sql)->result_array();
            $obj_gen   = $qGen['niv_parent'];
            
            $sql3 = ' ';
            
            // meta objetivo
            
            $sql3 = 'SELECT mesa_id FROM metas_sag WHERE depe_id = '.$depe.' LIMIT 1';
            $qMeta = $this->db->query($sql3)->result_array();
            $meta = $qMeta[0]['mesa_id'];
            */
            
            $sql= 'Select meob_id from meta_objetivos where meob_estado = 1 AND niv_id = '.$obj_espe.' and mesa_id ='.$tarea.' LIMIT 1';
            $qMeob = $this->db->query($sql)->result_array();
            
            if( trim($qMeob[0]['meob_id']) ==  '' ){
                
                 $sqlMeob = "INSERT INTO meta_objetivos(mesa_id, niv_id,meob_generate) VALUES('".$tarea."','".$obj_espe."',1) ";
                 $this->db->query($sqlMeob); 
                 $meob_id =   $this->db->insert_id();
            }
            else{
                $meob_id =   trim($qMeob[0]['meob_id']);
            }
            
           
            
            // META, OBJETIVO
           //  echo $sqlMeob.'<br/>';
               
            // recupero el meob_id
            
           // $this->_CI->db->insert('meta_objetivo_accion', array('meob_id' => '', 'accion' => '') );
            $sqlAcc = "INSERT INTO meta_objetivo_accion (meob_id, acc_id,meac_generate,unmed_id,meac_mes1,meac_mes2,meac_mes3,meac_mes4,meac_mes5,meac_mes6,meac_mes7,meac_mes8,meac_mes9,meac_mes10,meac_mes11,meac_mes12)
                       VALUES('".$meob_id."','".$acc_id."',1,53,1,1,1,1,1,1,1,1,1,1,1,1) ";
            $this->db->query($sqlAcc);
            $meac_id = $this->db->insert_id();
            
            $mensual = $saldoIni / 12;
            //$mensual = number_format($mensual,2);
            
            $sqlParti = "INSERT INTO accion_partida(acc_id,parti_id,accpar_mes1,accpar_mes2,accpar_mes3,accpar_mes4,accpar_mes5,accpar_mes6,accpar_mes7,accpar_mes8,accpar_mes9,accpar_mes10,accpar_mes11,accpar_mes12) 
                        VALUES(".$meac_id.",121,".$mensual.",".$mensual.",".$mensual.",".$mensual.",".$mensual.",".$mensual.",".$mensual.",".$mensual.",".$mensual.",".$mensual.",".$mensual.",".$mensual.")";
            $this->db->query($sqlParti);
         //   $meac_id = $this->db->insert_id();
            // partida 121
             
            
            
            // insertar partida servicios directos y 
            echo $sqlParti.'<br/><br/>';
        }
        
        
        
        $sql = ' ';
        
         
        
    }
    
    public function asociar(){
        
        
        $sql = ' SELECT 

                        mesa.mesa_nombre,  acc.acc_nombre, part.parti_codigo, part.parti_nombre, accpar.*   

                        FROM metas_sag mesa 
                        LEFT JOIN meta_objetivos meob ON mesa.mesa_id = meob.mesa_id
                        LEFT JOIN  niveles niv ON niv.niv_id =meob.niv_id
                        LEFT JOIN meta_objetivo_accion meac ON meac.meob_id = meob.meob_id
                        LEFT JOIN acciones acc ON acc.acc_id = meac.acc_id 
                        LEFT JOIN accion_partida accpar ON accpar.acc_id = meac.meac_id 
                        LEFT JOIN partidas part ON accpar.parti_id = part.parti_id	

                  WHERE acc.acc_estado = 1  AND accpar.accpar_estado = 1  AND meac.meac_estado = 1 AND  mesa.mesa_id  = 157
                        
                  ORDER BY mesa.mesa_id   ';
             
        
        
    }
    
    
    public function asociar_estrategicas()
    {
        
        
        $sql  = " SELECT * FROM acciones accs
                          WHERE  accs.acc_tipo = 2  ";
        
        $q = $this->db->query($sql)->result_array();
        
        
        // comprobar si tiene el meob
        
        // si no tiene el meob_Id, genera el registro en meta_objetivo
        
        // genera meta_objetivo_accion
        
        
        foreach($q as $accion){
             
            $sql = "SELECT * FROM meta_objetivo WHERE niv_id = ? AND mesa_id = ? ";
            $q = $this->db->query($sql,array( $accion['t_especifico_id'], $accion['depe_id'] ))->result_array();
            // depe
            $depe = $accion['depe_id'];
            // vincular directamente con la gerencia. 
        
        }
        
    }
    
    public function import_planilla(){
        
        /*
        $sql = "SELECT * from planillas_tareas ORDER BY tarea_id";
        $q = $this->db->query($sql)->result_array();
        
        foreach($q as $reg){
            $sql2= "update planillas_tareas set total = ".$reg['monto']." where plate_id = ".$reg['plate_id'];
            $this->db->query($sql2);
        }
       ¨
        
        $sql = "SELECT * from planillas_metas ORDER BY tarea_id";
        $q = $this->db->query($sql)->result_array();
        
        foreach($q as $reg){
            
            $total_1 = (trim($reg['total'])== '' || trim($reg['total']) == '-') ? '0.00' : trim($reg['total']);
            $renta = (trim($reg['renta_aduanas'])== '' || trim($reg['renta_aduanas']) == '-') ? '0.00' : trim($reg['renta_aduanas']);
            $fondo = (trim($reg['fondo_compe'])== '' || trim($reg['fondo_compe']) == '-') ? '0.00' : trim($reg['fondo_compe']);
            $oim = (trim($reg['oim'])== '' || trim($reg['oim']) == '-') ? '0.00' : trim($reg['oim']);
            $rdr = (trim($reg['rdr'])== '' || trim($reg['rdr']) == '-') ? '0.00' : trim($reg['rdr']);
            $total_2 = (trim($reg['total_2'])== '' || trim($reg['total_2']) == '-') ? '0.00' : trim($reg['total_2']);
            
            $sql2= "UPDATE planillas_metas SET
                    n_total = ".$total_1.",
                    n_renta_aduanas = ".$renta.",
                    n_fondo_compe = ".$fondo.",
                    n_oim = ".$oim.",
                    n_rdr = ".$rdr.",
                    n_total2 = ".$total_2."
                     WHERE plame_id = ".$reg['plame_id'];
            
            $this->db->query($sql2);
        }
        */
        
        $sql = "delete from acciones where acc_generate = 1";
        $q = $this->db->query($sql);
        
        $sql = "delete from meta_objetivo_accion where meac_generate = 1";
        $q = $this->db->query($sql);
        
        $sql = "delete from accion_partida where accpar_planilla = 1";
        $q = $this->db->query($sql);
        
        
        $sql = "SELECT * from planillas_tareas ORDER BY tarea_id";
        $q = $this->db->query($sql)->result_array();
        
        $tarea = '';
        $meac_id = '';
        $meob_id = '';
        $acc_id = '';
       
        foreach($q as $reg){
            
          
            
            if($tarea != $reg['tarea_id'] ){
           
                     $tarea = $reg['tarea_id'];
                
                    $sql= 'Select meob_id from meta_objetivos where meob_estado = 1 AND niv_id = 39 and mesa_id ='.$reg['tarea_id'].' LIMIT 1';
                    $qMeob = $this->db->query($sql)->result_array();

                    if( trim($qMeob[0]['meob_id']) ==  '' ){

                         $sqlMeob = "INSERT INTO meta_objetivos(mesa_id, niv_id,meob_generate) VALUES('".$reg['tarea_id']."','39',2) ";
                         $this->db->query($sqlMeob); 
                         $meob_id =   $this->db->insert_id();
                    }
                    else{
                        $meob_id =   trim($qMeob[0]['meob_id']);
                    }

                    //CREAR ACCION

                    $sql = "INSERT INTO acciones(acc_nombre,t_tarea,acc_generate,acc_tipo) VALUES('Gestion del Personal ".$reg['tarea_id']."','".$reg['tarea_id']."','1','3') ";
                    $this->db->query($sql);
                    $acc_id = $this->db->insert_id();
                     
                    $sqlAcc = "INSERT INTO meta_objetivo_accion (meob_id, acc_id,meac_generate,unmed_id,meac_mes1,meac_mes2,meac_mes3,meac_mes4,meac_mes5,meac_mes6,meac_mes7,meac_mes8,meac_mes9,meac_mes10,meac_mes11,meac_mes12)
                               VALUES('".$meob_id."','".$acc_id."',1,53,1,1,1,1,1,1,1,1,1,1,1,1) ";
                    $this->db->query($sqlAcc);
                    $meac_id = $this->db->insert_id();
 

            }
            
            // COMPROBACION DE SALDOS DE LA META .. //FF1, FF2,FF3, FF4
            
            $total =  $reg['total'];
            $total_n = $total;
            // cargar el registro de la meta
            
            $sql = "SELECT * FROM planillas_metas WHERE tarea_id = ".$reg['tarea_parent_id']." AND partida_id = '".$reg['partida_id']."'";
            $fuentes = $this->db->query($sql)->result_array();
            
            $f1 = $fuentes[0]['r_renta_aduanas']; // estado 5
            $f2 = $fuentes[0]['r_fondo_compe'];  // estado 6
            $f3 = $fuentes[0]['r_oim']; // estado 7
            $f4 = $fuentes[0]['r_rdr']; //estado 1
            $monto_afec = 0;
            
            if($total == 0 ){
                
                  $sqlParti = "INSERT INTO accion_partida(acc_id,parti_id,accpar_mes1,accpar_planilla,accpar_estado,plate_id) 
                                     VALUES(".$meac_id.",".$reg['partida_id'].",0,1,5,".$reg['plate_id'].")";
                  $this->db->query($sqlParti);
                  echo $sqlParti.'<br/>';
                
            }
            else{

                    if ($f1 > 0 ){

                         if($f1>$total_n){
                             $f1 -= $total_n; 
                             $monto_afec = $total_n;
                             $total_n = 0;
                         }
                         else{
                             $total_n-=$f1;
                             $monto_afec = $f1;
                             $f1 = 0;
                             
                         }

                         $update = 'UPDATE planillas_metas SET r_renta_aduanas = r_renta_aduanas - '.$monto_afec.' where plame_id = '.$fuentes[0]['plame_id'];
                         $this->db->query($update);
                         $update = 'UPDATE planillas_metas SET r_total = r_total - '.$monto_afec.' where plame_id = '.$fuentes[0]['plame_id'];
                         $this->db->query($update);
                         $sqlParti = "INSERT INTO accion_partida(acc_id,parti_id,accpar_mes1,accpar_planilla,accpar_estado,plate_id) 
                                     VALUES(".$meac_id.",".$reg['partida_id'].",".$monto_afec.",1,5,".$reg['plate_id'].")";
                         $this->db->query($sqlParti);
                         echo $sqlParti.'<br/>';
                    }

                    if ($f2 > 0 && $total_n > 0 ){

                        if($f2>$total_n){
                             $f2 -= $total_n; 
                             
                             $monto_afec = $total_n;
                             $total_n = 0;
                         }
                         else{
                             $total_n-=$f2;
                             
                             $monto_afec = $f2;
                             $f2 = 0;
                         }

                         $update = 'UPDATE planillas_metas SET r_fondo_compe = r_fondo_compe - '.$monto_afec.' where plame_id = '.$fuentes[0]['plame_id'];
                         $this->db->query($update);
                         $update = 'UPDATE planillas_metas SET r_total = r_total - '.$monto_afec.' where plame_id = '.$fuentes[0]['plame_id'];
                         $this->db->query($update);
                         $sqlParti = "INSERT INTO accion_partida(acc_id,parti_id,accpar_mes1,accpar_planilla,accpar_estado,plate_id) 
                                     VALUES(".$meac_id.",".$reg['partida_id'].",".$monto_afec.",1,6,".$reg['plate_id'].")";
                         $this->db->query($sqlParti);
                         echo $sqlParti.'<br/>';

                    }

                    if ($f3 > 0  && $total_n > 0 ){

                         if($f3>$total_n){
                             $f3 -= $total_n; 
                            
                             $monto_afec = $total_n;
                              $total_n = 0;
                         }
                         else{
                             $total_n-=$f3;
                            
                             $monto_afec = $f3;
                             $f3 = 0;
                         }

                         $update = 'UPDATE planillas_metas SET r_oim = r_oim - '.$monto_afec.' where plame_id = '.$fuentes[0]['plame_id'];
                         $this->db->query($update);
                         $update = 'UPDATE planillas_metas SET r_total = r_total - '.$monto_afec.' where plame_id = '.$fuentes[0]['plame_id'];
                         $this->db->query($update);
                         $sqlParti = "INSERT INTO accion_partida(acc_id,parti_id,accpar_mes1,accpar_planilla,accpar_estado,plate_id) 
                                     VALUES(".$meac_id.",".$reg['partida_id'].",".$monto_afec.",1,7,".$reg['plate_id'].")";
                         $this->db->query($sqlParti);
                         echo $sqlParti.'<br/>';

                    }

                    if ($f4 > 0  && $total_n > 0 ){


                        if($f4>$total_n){
                             $f4 -= $total_n; 
                             
                             $monto_afec = $total_n;
                             $total_n = 0;
                         }
                         else{
                             $total_n-=$f4;
                             
                             $monto_afec = $f4;
                             $f4 = 0;
                         }

                         $update = 'UPDATE planillas_metas SET r_rdr = r_rdr - '.$monto_afec.' where plame_id = '.$fuentes[0]['plame_id'];
                         $this->db->query($update);
                         $update = 'UPDATE planillas_metas SET r_total = r_total - '.$monto_afec.' where plame_id = '.$fuentes[0]['plame_id'];
                         $this->db->query($update);
                         $sqlParti = "INSERT INTO accion_partida(acc_id,parti_id,accpar_mes1,accpar_planilla,accpar_estado,plate_id) 
                                     VALUES(".$meac_id.",".$reg['partida_id'].",".$monto_afec.",1,1,".$reg['plate_id'].")";
                         $this->db->query($sqlParti);
                         echo $sqlParti.'<br/>';



                    }

            }
            
         
            echo '<br/> <br/> -----------------------------------------------   ';
        }
         
        
    }
    
    
    public function personal_set_monto(){
        
        
    }
    
    
    public function actualizar_conceptos_pension(){


         $this->db->trans_begin();

         $this->load->library(array('App/persona', 'App/concepto','App/empleadoconcepto'));

         $sql = " SELECT indiv.indiv_id, persla.persla_id, persla.plati_id, pe.pentip_id FROM public.individuo indiv
                           INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                           INNER JOIN rh.persona_pension pe ON pe.pers_id = indiv.indiv_id AND  peaf_estado = 1  ";

         $rs = $this->db->query($sql, array())->result_array();                  

         $c = 0;
         foreach($rs as $info)
         {

                  $c++;  
                  

                  if( $info['pentip_id'] == PENSION_AFP )
                  {
                     $grupo     = GRUPOVC_AFP;
                     $grupo_del = GRUPOVC_ONP;
                  }
                  else
                  {
                     $grupo     = GRUPOVC_ONP;
                     $grupo_del = GRUPOVC_AFP;
                  }

                  $plati_id = $info['plati_id'];

                  $id_pers = $info['indiv_id'];

                  $conceptos_pension_del = $this->concepto->get_list(array(
                                                                            'tipoplanilla' => $plati_id,
                                                                            'grupo'        => $grupo_del    ));

     
                  foreach($conceptos_pension_del as $concepto)
                  {  
                     $this->empleadoconcepto->desvincular_concepto($id_pers, $concepto['conc_id']); 

                  }




                  $conceptos_pension = $this->concepto->get_list(array(
                                                                            'tipoplanilla' => $plati_id,
                                                                            'grupo'        => $grupo    ));

      
                  foreach($conceptos_pension as $concepto)
                  {  
                     $this->empleadoconcepto->registrar($id_pers, $concepto['conc_id']); 

                  }
        

                 echo $c.'<br/>';

         }


         if($this->db->trans_status() === FALSE) 
         {
             $this->db->trans_rollback();
             return false;
         }
         else
         {
             $this->db->trans_commit();
             return true;
         } 

    }    


    public function comprobar_conceptos_redudantes()
    {

          $this->load->library(array('App/conceptooperacion'));

          $sql =" SELECT * FROM planillas.conceptos conc WHERE conc.conc_estado = 1 ";
            
          $rs = $this->db->query($sql, array())->result_array();  

          foreach($rs as $conc_info)
          {

              $ok = $this->conceptooperacion->validar_ecuacion($conc_info['validar_ecuacion'],$conc_info['validar_ecuacion']);

              if($ok == FALSE)
              {

                 echo ' CONCEPTO REDUDANTE: '.$conc_info['conc_nombre'].'  ID: '.$conc_info['conc_id'].' <br/>';
              }
              else
              {

                 echo ' CONCEPTO '.$conc_info['conc_nombre'].' OK  <br/>';
              }

          }

    }
     
}