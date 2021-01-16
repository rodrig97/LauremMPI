<div class="window_container">

  <div id="dvViewName" class="dv_view_name">
      
        <table class="_tablepadding2" border="0">
            <tr> 
                <td> 
                     <?PHP 
                               $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                           ?>
                </td>

               <td>
                    Registrar Nuevo Permiso
                </td>
            </tr>
      </table>
  </div>
    

   <div>

      <form dojoType="dijit.form.Form" id="form_permiso_nuevasolicitud"> 
          
            <table class="_tablepadding4" width="100%"> 

                 <tr class="row_form">
                     <td width="190">
                         <span class="sp11b"> Fecha </span>
                     </td>  
                     <td>
                         <span class="sp11b"> : </span>
                     </td>  
                     <td>   

                           <!-- 
                                  <div id="cal_crpla_desde" data-dojo-type="dijit.form.DateTextBox"
                                            data-dojo-props='type:"text", name:"fechadesde", value:"",
                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                            lang:"es",
                                            required:true,
                                            promptMessage:"mm/dd/yyyy",
                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                            
                                              onChange="dijit.byId('cal_crpla_hasta').constraints.min = this.get('value'); dijit.byId('cal_crpla_hasta').set('value', this.get('value') );  "
                                            >
                                        </div> 
                            -->

                          <?php
                               // $hoy = date('Y').'-'.date('m').'-'.date('d');

                               // echo get_fecha_larga($hoy); 
                           ?>


                          <div id="dvnuevopermiso_fecha"  data-dojo-type="dijit.form.DateTextBox"
                                data-dojo-props='type:"text", name:"fecha", value:"",
                                  constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                lang:"es",
                                required:true,
                                promptMessage:"mm/dd/yyyy",
                                invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                               
                                 onChange="" > </div> 
                     </td>  
                 </tr>

                 <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Trabajador </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>

                       <input type="hidden" id="HdPermisoNuevoTipoTrabajador" value="<?PHP echo $plati_id; ?>">

                        <select id="selsolicitudper_solicita"  
                               data-dojo-type="dijit.form.FilteringSelect" class="formelement-200-11" 
                               data-dojo-props='name:"solicita", 
                                                disabled:false, 
                                                autoComplete:false, 
                                                highlightMatch: "all",  
                                                queryExpr:"${0}", 
                                                invalidMessage: "La Persona no esta registrada" ' style="width:300px;"  >
                        </select>


                    </td>  
                </tr>  
                <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Jefe Inmediato (Autoriza) </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
                          <select id="selsolicitudper_autoriza"  
                                 data-dojo-type="dijit.form.FilteringSelect" class="formelement-200-11" 
                                 data-dojo-props='name:"autoriza", 
                                                  disabled:false, 
                                                  autoComplete:false, 
                                                  highlightMatch: "all",  
                                                  queryExpr:"${0}", 
                                                  invalidMessage: "La Persona no esta registrada" ' style="width:300px;"  >
                          </select>

                    </td>  
                </tr>  

                <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Documento de referencia </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
                          <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="name:'documento'" class="formelement-200-11"  />
                    </td>  
                </tr>  
                

                <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Motivo </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td> 

                          <select id="selsolicitudper_motivo"  
                                  data-dojo-type="dijit.form.Select" 
                                  class="formelement-200-11" 
                                  data-dojo-props='name:"motivo" ' style="width:200px;"  >

                                   <?PHP 

                                      foreach ($motivos as $motivo)
                                      {
                                           echo " <option value='".$motivo['permot_id']."' > ".$motivo['permot_nombre']." </option> ";
                                      }

                                   ?>

                          </select>
                       
                    </td>  
                </tr>  
                 
                <!-- <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Lugar de comisión </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
                         <input type="text" 
                                data-dojo-type="dijit.form.TextBox" 
                                data-dojo-props="name:'destino'"  
                                style="font-size: 11px; width: 200px;">   
                    </td>  
                </tr>   -->


                <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Hora de Salida </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
 
                        <input  id="selsolicitudper_horasalida" 
                                data-dojo-type="dijit.form.TimeTextBox"
                                data-dojo-props='type:"text", 
                                                 name:"horasalida", 
                                                 value:"T07:45:00",
                                                 title:"",
                                                 constraints:{formatLength:"short"},
                                                 required:true,
                                                 invalidMessage:"" ' 
                                 style="width:60px; font-size:11px;"
                                 onChange=""   />
 
                    </td>  
                </tr>  


                <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Hora de retorno </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
 
                        <input  id="selsolicitudper_horaretorno" 
                                data-dojo-type="dijit.form.TimeTextBox"
                                data-dojo-props='type:"text", 
                                                 name:"horaretorno", 
                                                 value:"T07:45:00",
                                                 title:"",
                                                 constraints:{formatLength:"short"},
                                                 required:true,
                                                 invalidMessage:"" ' 
                                 style="width:60px; font-size:11px;"
                                 onChange=""   />

                          
                    </td>  
                </tr>  

                <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Observación </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
                        <textarea data-dojo-type="dijit.form.TextArea" data-dojo-props="name:'observacion'" class="formelement-300-11"></textarea>
                    </td>  
                </tr>  



            </table> 
          
      </form>
           
 </div>



  <div align="center" style="margin: 20px 0px 0px 0px;">
        
    
        <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
          <?PHP 
             $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
          ?>
            <script type="dojo/method" event="onClick" args="evt">

                  var data  = dojo.formToObject('form_permiso_nuevasolicitud'), mensaje = ' <ul> ', err = false;
     
                  if ( dijit.byId('dvnuevopermiso_fecha') != null && (data.fecha == '' || data.fecha == undefined || data.fecha == null) ) { 
                       mensaje+='<li> La fecha no es valida </li> ';
                       err = true;
                  } 

                  if (data.solicita == '' || data.solicita == undefined || data.solicita == null) {  
                       mensaje+='<li> La persona que solicita no esta registrada </li> ';
                       err = true;
                  } 
                  
                  if (data.autoriza == '' || data.autoriza == undefined || data.autoriza == null) { 
                       mensaje+='<li> La persona que autoriza no esta registrada </li> ';
                       err = true;
                  } 
                  
                  if ( dijit.byId('selsolicitudper_horasalida') != null && (data.horasalida == '' || data.horasalida == undefined || data.horasalida == null) ) { 
                       mensaje+='<li> La hora de salida no es valida </li> ';
                       err = true;
                  } 

                  // if( dijit.byId('selsolicitudper_horaretorno') != null && (data.horasalida == '' || data.horasalida == undefined || data.horasalida == null) )
                  // { 
                  //      mensaje+='<li> La hora de retorno no es valida </li> ';
                  //      err = true;
                  // } 

                  if (data.motivo == '' || data.motivo == undefined || data.motivo == null) { 
                   
                       mensaje+='<li> Por favor verifique el motivo del permiso</li> ';
                       err = true;
                  }
                  

                  if (err === false) {
                       
                      if (Permisos._M.registrar_papeleta.process(data)) {

                            Permisos._V.nuevo_permiso.close(); 
                      }

                  } else {
                       app.alert(mensaje);
                  }

            </script>
            <label class="sp12">
                 Guardar  
            </label>
       </button>
   

  </div>
    
</div>