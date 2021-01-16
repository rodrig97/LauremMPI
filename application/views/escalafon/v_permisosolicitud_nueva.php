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
                    Registrar Nueva solicitud
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
                               $hoy = date('Y').'-'.date('m').'-'.date('d');

                               echo get_fecha_larga($hoy); 
                           ?>
                     </td>  
                 </tr>

                 <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Solicita </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
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
                        <span class="sp11b"> Hora de Salida </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
<!-- 
                        <input  id="selsolicitudper_hora" 
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
-->
                         <?PHP 

                             $hora  = date('H').':'.date('i').' horas ';
                             echo $hora;
                         ?>
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
                                 data-dojo-type="dijit.form.FilteringSelect" class="formelement-200-11" 
                                 data-dojo-props='name:"motivo", 
                                                  disabled:false, 
                                                  autoComplete:false, 
                                                  highlightMatch: "all",  
                                                  queryExpr:"*${0}*", 
                                                  invalidMessage: "El motivo no esta registrado" ' style="width:300px;"  >

                                   <?PHP 

                                      foreach ($motivos as $motivo)
                                      {
                                           echo " <option value='".$motivo['permot_key']."' > ".$motivo['permot_nombre']." </option> ";
                                      }

                                   ?>

                          </select>
                       
                    </td>  
                </tr>  
                 
                <tr class="row_form"> 
                    <td>
                        <span class="sp11b"> Lugar de destino </span>
                    </td>  
                    <td>
                        <span class="sp11b"> : </span>
                    </td>  
                    <td>
                         <select id="selsolicitudper_destino"  
                                data-dojo-type="dijit.form.FilteringSelect" class="formelement-200-11" 
                                data-dojo-props='name:"destino", 
                                                 disabled:false, 
                                                 autoComplete:false, 
                                                 highlightMatch: "all",  
                                                 queryExpr:"*${0}*", 
                                                 invalidMessage: "El destino no esta registrado" ' style="width:300px;"  >
                             
                             <?PHP 
                             
                                foreach ($destinos as $destino)
                                {
                                     echo " <option value='".$destino['perde_key']."' > ".$destino['perde_nombre']." </option> ";
                                }

                             ?>

                         </select>


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
    

                  if(data.solicita == '' || data.solicita == undefined || data.solicita == null)
                  {  
                       mensaje+='<li> La persona que solicita no esta registrada </li> ';
                       err = true;
                  } 
                  if(data.autoriza == '' || data.autoriza == undefined || data.autoriza == null)
                  { 
                       mensaje+='<li> La persona que autoriza no esta registrada </li> ';
                       err = true;
                  } 
                  if( dijit.byId('selsolicitudper_hora') != null && (data.horasalida == '' || data.horasalida == undefined || data.horasalida == null) )
                  { 
                       mensaje+='<li> La hora de salida no es valida </li> ';
                       err = true;
                  } 
                  if(data.motivo == '' || data.motivo == undefined || data.motivo == null)
                  { 
                       mensaje+='<li> Por favor verifique el motivo del permiso</li> ';
                       err = true;
                  }
                  

                  if(err === false) 
                  {
                      if(data.destino == '')
                      {
                           data.destino_label = dijit.byId('selsolicitudper_destino').get('displayedValue');
                      }

                      if(Permisos._M.registrar_solicitud.process(data))
                      {

                            Permisos._V.nueva_solicitud.close();
                            dojo.byId('mnupermisos_missolicitudes').click();
                      }
                  }                  
                  else
                  {
                       app.alert(mensaje);
                  }

            </script>
            <label class="sp12">
                 Guardar  
            </label>
       </button>
   

  </div>
    
</div>