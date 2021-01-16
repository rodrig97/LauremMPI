


<div class="dv_celeste_padding4" style="margin:10px 10px 0px 10px;"> 
    <table class="_tablepadding4">
        <tr>
            <td> <span class="sp12b"> Trabajador</span></td>
            <td><span class="sp12b">:</span></td>
            <td> 
              <span class="sp12">
              <?PHP 
                
              
                    echo $pers_info['indiv_nombres'].' '.$pers_info['indiv_appaterno'].' '.$pers_info['indiv_apmaterno'].' '.' DNI: '.$pers_info['indiv_dni'].' - '.$pers_info['tipo_empleado'].' ('.$pers_info['estado_trabajador'].') ';
                ?>
              </span>
                
              
            </td>
        </tr>
 
    </table>

</div>


<div style="margin: 10px 10px 10px 10px; border:1px solid #E8EFF7; padding:6px 6px 6px 6px; min-height: 220px; ">

  <form id="form_info_acceso_directo" data-dojo-type="dijit.form.Form" >
    
    
    <input type="hidden" value="<?PHP echo trim($pers_info['indiv_key']); ?>" name="view" />

    <table class="_tablepadding4" width="100%">
    	 
        <tr class="row_form">
               <td width="140"> 
                  <span class="sp12b"> Grupo </span> 
              </td>
              <td width="10"> 
                   <span class="sp12b"> : </span> 
              </td> 
              <td>
              
                  <input type="hidden" id="hd_grupo_id" value="<?PHP echo trim($pers_info['grupo_empleado']); ?>" />
                  
                  <select id="fip_grupo"  name="grupo"  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"grupo", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Grupo no registrado" ' class="formelement-200-12" > 
                      <option value="0" selected="true"></option>  
                      <?PHP 
                        foreach($grupos as $g)
                        {
                           
                             echo '<option value="'.trim($g['gremp_id']).'" > '.trim($g['gremp_nombre']).' </option>';
                        }
                      ?>
                  </select>  
              </td>   
        </tr>

        <tr class="row_form">
               <td> 
                  <span class="sp12b"> Ocupación </span> 
              </td>
              <td width="10"> 
                   <span class="sp12b"> : </span> 
              </td> 
              <td>
              
                  <input type="hidden" id="hd_ocupacion_id" value="<?PHP echo trim($pers_info['ocupacion_id']); ?>" />
                  
                  <select id="fip_ocupacion"  name="ocupacion"  data-dojo-type="dijit.form.FilteringSelect" 
                         data-dojo-props='name:"ocupacion", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Ocupacion no registrada" ' class="formelement-200-12" > 
                         <option value="0" selected="true"></option>  
                        <?PHP 
                          foreach($ocupaciones as $ocu)
                          {
                             
                               echo '<option value="'.trim($ocu['ocu_id']).'" > '.trim($ocu['ocu_nombre']).' </option>';
                          }
                        ?>
                  </select>  
              </td>   
        </tr>

<!-- 

        <tr class="row_form">
              <td width="130"> <span class="sp12b">ESSALUD</span></td>
              <td width="10"> :</td>
              <td> 
                  <select id="fip_hasessalud_ar"  name="hasessalud"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12" style="width:40px;">
                      <option value="1"    <?PHP if(trim($pers_info['persa_id'])!='') echo 'selected="selected"'; ?> >Si</option>
                      <option value="0"  <?PHP if(trim($pers_info['persa_id'])=='') echo 'selected="selected"'; ?>>No</option>
                  </select>
                  

                  <div class="containerItems" style="display:none">
                        <span class="sp12b">Codigo: </span>
                        <input  name="essaludcod" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12"   value="<?PHP if(trim($pers_info['persa_id'])!='') echo trim($pers_info['persa_codigo']); ?>" />
                  
                  </div>
              </td>
         </tr> -->
          <tr class="row_form">
              <td> <span class="sp12b">Cuenta Bancaria</span></td>
              <td> <span class="sp12b"> : </span> </td>
              <td> 
                  
                  <select id="fip_hascbanco_ar" name="hascbanco"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12" style="width:40px;" >
                      <option value="1" <?PHP if(trim($pers_info['pecd_id'])!='') echo 'selected="selected"'; ?> >Si</option>
                      <option value="0" <?PHP if(trim($pers_info['pecd_id'])=='') echo 'selected="selected"'; ?> >No</option>
                   </select>

                  <div class="containerItems" style="display:none">

                        <span class="sp12b"># </span>
                        
                        <input name="bancocod" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12" value="<?PHP if(trim($pers_info['pecd_id'])!='') echo trim($pers_info['pecd_cuentabancaria']); ?>"  />

                        <div class="dvblock">
                            
                            <span class="sp12b">Entidad bancaria: </span>
                            
                            <select  name="banco"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12" style="width:150px;" >
                              
                                <?PHP
                                    $sel = false;
                                
                                foreach($bancos as $banco){
                                    
                                    echo '<option value="'.trim($banco['id']).'" ';
                                    
                                    if($banco['ebanco_id']== $pers_info['ebanco_id'] ){
                                        echo ' selected="selected" ';
                                        $sel= true;
                                    }
                                    echo ' >'.trim($banco['label']).'</option>';
                                   
                                }
                                
                                
                             ?>
                                  <option value="0" <?PHP if(!$sel) echo 'selected="selected"'; ?> > ------ </option>
                            </select>
                       </div>
                  </div>
             </td> 
         </tr>
         <tr class="row_form">
              <td> <span class="sp12b">Sistema de Pensiones</span></td>
              <td> <span class="sp12b"> : </span> </td>
              <td>  
                   <div class="containerItems" style="display:block"> 
                      <select id="fip_tipopension_ar" name="tipopension"   data-dojo-type="dijit.form.Select" data-dojo-props="name:'tipopension'" class="formelement-50-12" style="width:60px;">
                         <option value="1" <?PHP if(trim($pers_info['pentip_id'])=='1') echo 'selected="selected"'; ?> >ONP</option>
                         <option value="2" <?PHP if(trim($pers_info['pentip_id'])=='2') echo 'selected="selected"'; ?>>AFP</option>
                      </select> 
                     <span class="sp12b"># </span>
                      <input  name="codpension" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12" value="<?PHP if(trim($pers_info['peaf_id'])!='') echo trim($pers_info['peaf_codigo']); ?>"  />
                      <span class="sp12b"> Jubilado:  </span>
                      <select id="fip_pesion_jubilado_ar" name="estajubilado"   data-dojo-type="dijit.form.Select" data-dojo-props="name:'estajubilado' " class="formelement-50-12" style="width:60px;">
                          <option value="0"  <?PHP if(trim($pers_info['peaf_jubilado'])=='0') echo 'selected="selected"'; ?> > No </option>
                          <option value="1"  <?PHP if(trim($pers_info['peaf_jubilado'])=='1') echo 'selected="selected"'; ?> > Si </option>
                      </select>

                       <div class="containerItems" style="display:none"> 
                         <span class="sp12b">Afiliado a: </span>
                          <select  name="afp"  data-dojo-type="dijit.form.Select" data-dojo-props="name:'afp'" class="formelement-50-12" style="width:100px;" >
                                 
                                 
                                 <?PHP
                                        $sel = false;
                                       foreach($afps as $afp){

                                            echo '<option value="'.$afp['id'].'" ';

                                            if($afp['afp_id']== $pers_info['afp_id'] ){
                                                echo ' selected="selected" ';
                                                $sel= true;
                                            }
                                            echo ' >'.trim($afp['label']).'</option>';

                                        }


                                 ?>
                              <option value="0" <?PHP if(!$sel) echo 'selected="selected"'; ?> > ------ </option>
                                 
                                 
                                 
                          </select> 

                           <span class="sp12b"> Modo Comisión: </span>

                          <select name="modoafp"   data-dojo-type="dijit.form.Select" data-dojo-props="name:'modoafp'" class="formelement-50-12" style="width:70px;">
                             <option value="1" <?PHP if(trim($pers_info['afm_id']) == AFP_FLUJO) echo 'selected="selected"'; ?> >FLUJO</option>
                             <option value="2" <?PHP if(trim($pers_info['afm_id']) == AFP_SALDO) echo 'selected="selected"'; ?>> SALDO</option>
                          </select> 


                          <?PHP 

                           if(AFP_QUITARINVALIDEZ_AUTOMATICO == FALSE)
                           {

                          ?>
                          
                            <span class="sp12b">Invalidez:  </span>
                            <select id="fip_pesion_aplicainvalidez_ar" name="aplica_invalidez"   data-dojo-type="dijit.form.Select" data-dojo-props="name:'aplica_invalidez' " class="formelement-50-12" style="width:60px;">
                                <option value="1"  <?PHP if(trim($pers_info['peaf_invalidez'])=='1') echo 'selected="selected"'; ?> > Si </option>
                                <option value="0"  <?PHP if(trim($pers_info['peaf_invalidez'])=='0') echo 'selected="selected"'; ?> > No </option>
                                
                            </select>   


                          <?PHP 
                            }
                          ?>

                       </div>
                  </div>

              </td>
         </tr>

        <?PHP 
          if($plati_info['plati_id'] == TIPOPLANILLA_CONTRATADOS && $plati_info['plati_calcula_quinta'] == '1'){ 
        ?>

         <tr class="row_form">
               <td width="140"> 
                   <span class="sp12b"> Para la proyección de la gratificación </span> 
               </td>
               <td width="10"> 
                    <span class="sp12b"> : </span> 
               </td> 
               <td> 
                    
                   <select name="quinta_proyectar_gratificacion"  
                           data-dojo-type="dijit.form.Select" 
                           data-dojo-props='name:"quinta_proyectar_gratificacion" ' 
                           class="formelement-250-12" style="width:240px;"> 

                       <option value="1" <?PHP echo ($pers_info['persla_quinta_gratificacionproyeccion'] == '1' ? 'selected="selected"' : '') ?> > Considerar la remuneración mensual </option>  
                       <option value="2" <?PHP echo ($pers_info['persla_quinta_gratificacionproyeccion'] == '2' ? 'selected="selected"' : '') ?> > Considerar Aguinaldo </option>  
                       
                   </select>  
               </td>   
         </tr>

         <?PHP } ?>
          
        <?PHP 
          if($plati_info['plati_calcula_cuarta'] == '1'){ 
        ?>
         <tr>
           <td>
             <span class="sp12b"> Suspensión de cuarta </span>
           </td>
           <td>
             <span class="sp12b">:</span>
           </td>
           <td>
               <select id="selsuspension_presento" name="suspension_cuarta"   
                       data-dojo-type="dijit.form.Select" 
                       data-dojo-props="name:'suspension_cuarta' " 
                       class="formelement-50-12" style="width:100px;">
                  
                   <option value="1"  <?PHP if(trim($pers_info['indiv_suspension_cuarta'])=='1') echo 'selected="selected"'; ?> > Presento </option>
                   <option value="0"  <?PHP if(trim($pers_info['indiv_suspension_cuarta'])=='0') echo 'selected="selected"'; ?> > No Presento </option>
                   
               </select>   
               

               <input type="hidden" id="hdSuspension_fecha_registrada" value="<?PHP echo _get_date_pg($pers_info['indiv_suspension_fecha']); ?>" />


               <div id="div_suspension_fecha" style="display: none;">
                    
                     <span class="sp12b"> Fecha del documento: </span> 
                     <div  id="calSuspension_fecha"   data-dojo-type="dijit.form.DateTextBox"
                                data-dojo-props='type:"text", name:"fecha_suspension", value:"<?PHP echo $pers_info['indiv_suspension_fecha']; ?>",
                                 constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                lang:"es",
                                required:true,
                                promptMessage:"mm/dd/yyyy",
                                invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' style="width:80px; font-size: 11px;">
                     </div>

               </div>
           </td>
         </tr>
        
        <?PHP } ?>


    </table>

    
    <div style="margin: 10px 0px 0px 10px;"> 

       <button  data-dojo-type="dijit.form.Button" class="dojobtnfs_12" > 
         <?PHP 
            $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
         ?>
          <script type="dojo/method" event="onClick" args="evt">
            
                
                 var data = dojo.formToObject('form_info_acceso_directo'), 
                     err = false, 
                     mensaje ='<ul>';
 
                 
                 if( data.hascbanco == 1 && ( data.bancocod.length == 0 || data.banco == 0 ) ){
                     
                     err = true;
                     mensaje += '<li> Verifique los datos de la cuenta de bancaria.</li>'; 
                     
                 } 
                 
                 if( data.haspension == 1 )
                 {
                
                     if( ( data.tipopension == 2 && data.afp == 0 ) )
                     { 
                      
                         err = true;
                         mensaje += '<li> Verifique los datos del sistema de pensiones.</li>'; 
                     }
                 }  


                 if(data.grupo == '')
                 {
                     data.grupo_label = dojo.trim(dijit.byId('fip_grupo').get('displayedValue'));  
                 }



                 if(data.ocupacion == '')
                 {
                     data.ocupacion_label = dojo.trim(dijit.byId('fip_ocupacion').get('displayedValue'));  
                 }
                
                mensaje +='</ul>';

                        if(err){

                              var myDialog = new dijit.Dialog({
                                        title: "Atenci&oacute;n",
                                        content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                        style: "width: 350px"
                                    });
                         myDialog.show();
                } 
                else
                {
                    if(confirm('Realmente desea actualizar la informacion del trabajador?')){
                         

                         if(Trabajadores._M.actualizar_info.process(data))
                         {
                              Trabajadores._V.gestionar_datos_rapida.reload();
                         }  
                  
                              
                    }
                 
                }


          </script>
          <label class="sp11">
                          Actualizar datos                           
          </label>
       </button>

    </div>

  </form>

 

</div>


 <div style="margin:20px 0px 10px 10px;">

      <table class="_tablepadding4" width="330">
    
         <tr class="tr_header_celeste">
              <td width="40" align="center"> # </td>
              <td width="20">   </td>
              <td width="10">  </td>
              <td width="200">   Concepto  </td>
         </tr>
         <?PHP    
   
             $c = 1;
          foreach($conceptos as $conc){
  
          ?>
                            <tr class="row_form">  
 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                
                                 <td align="center"> 

                                    <input type="hidden" value="<?PHP echo trim($pers_info['indiv_key']); ?> " class="hddiecto_empleado" />
                                    <input type="hidden" value="<?PHP echo trim($conc['conc_key']); ?> "  class="hddirecto_concepto" />
                                     
                                    <input class="chdirecto_checks" type="checkBox" data-dojo-type="dijit.form.CheckBox"  <?PHP if(trim($conc['empcon_estado'])=='1') echo 'checked'; ?>  />
                                       
                                </td> 
                                  
                                <td align="center"> <span class="sp11">:</span></td> 
                        
                               <td> <span class="sp12"> <?PHP echo trim($conc['conc_nombre']); ?> </span> </td>                             
                               

                            </tr> 
                            <?PHP 
                               }
                            ?>
                      </table> 
                  
                   <?PHP 
                       if(sizeof($conceptos) == 0 ) echo " <div class='dv_new_resumen'> No hay conceptos que vincular </div> ";
                     ?>
   

  </div>