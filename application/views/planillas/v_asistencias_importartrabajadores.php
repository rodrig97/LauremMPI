<div class="window_container">
 
 	<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' 
 	style="width:830px; height: 440px; margin: 0px 0px 0px 0px; padding:0px 0px 0px 0px; ">
 	    
 	     <div  dojoType="dijit.layout.ContentPane" 
 	           data-dojo-props='region:"left", style:"width: 460px;"' 
 	           style="padding:0px 0px 0px 0px;">
 	 		 
 	 		 <div style="margin:3px 0px 5px 3px;"> 
 	 		         <div  data-dojo-type="dijit.form.DropDownButton" >

 	 		             <span class="sp12b">
 	 		                   <?PHP 
 	 		                     $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
 	 		                  ?>

 	 		                     Filtrar hojas de asistencia
 	 		             </span>
 	 		             <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Busqueda peronalizada"'>

 	 		                 <div class="dv_formu_find_tt">
 	 		                       <form id="form_registroasistencias"  data-dojo-type="dijit.form.Form">   
 	 		                             <table class="_tablepadding4" style="width:100%">
 	 		                                   <tr class="row_form" >
 	 		                                           <td colspan="3"> <span class="sp12b">Parámetros de busqueda</span>

 	 		                                           			<input type="hidden" value="<?PHP echo $hoja_key; ?>" name="from" />
 	 		                                           </td>
 	 		                                   </tr>
 	 		                                    <tr class="row_form" >
 	 		                                        <td> <span class="sp12b">Código</span></td>
 	 		                                        <td> <span class="sp12b">: </span></td>
 	 		                                        <td>  
 	 		                                            <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:40, name:'codigo'" class="formelement-100-11"/>
 	 		                                        </td>
 	 		                                   </tr>
 	 		                                   <tr class="row_form" >
 	 		                                       
 	 		                                        <td width="130"> <span class="sp12b">Tipo de trabajador</span></td>
 	 		                                        <td width="10"> <span class="sp12b">: </span></td>
 	 		                                        <td width="300">  
 	 		                                            <select data-dojo-type="dijit.form.Select" data-dojo-props="name: 'tipoplanilla'" class="formelement-150-11" style="width:200px;">
 	 		                                                   <?PHP
 	 		                                                    foreach($tipos as $tipo){
 	 		                                                         echo "<option value='".trim($tipo['plati_id'])."'>".trim($tipo['plati_nombre'])."</option>";
 	 		                                                    }
 	 		                                                  ?>
 	 		                                            </select>
 	 		                                        </td>
 	 		                                   </tr>


 	 		                                    <tr  class="row_form"> 
 	 		                                       <td width="165">
 	 		                                           <span class="sp12b">
 	 		                                           Año 
 	 		                                           </span>
 	 		                                       </td>
 	 		                                       <td width="5" width="10">
 	 		                                            <span class="sp12b"> : </span>
 	 		                                       </td>
 	 		                                            
 	 		                                       <td width="330">
 	 		                                            <select id="selplani_anio"  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:0px; font-size:11px; width: 80px;">
 	 		                                             <?PHP 
 	 		                                               foreach ($anios as $anio)
 	 		                                               {
 	 		                                                   echo '<option value="'.$anio['ano_eje'].'" >'.$anio['ano_eje'].'</option>';
 	 		                                               }
 	 		                                            ?>
 	 		                                           </select>
 	 		                                       </td>
 	 		                                   </tr>

 	 		                                  <tr class="row_form" >
 	 		                                        <td> <span class="sp12b">Mes </span></td>
 	 		                                        <td> <span class="sp12b">: </span></td>
 	 		                                         <td>  
 	 		                                             <select data-dojo-type="dijit.form.Select" data-dojo-props="name: 'mes'"  class="formelement-100-11" style="width:150px;">
 	 		                                                <option value="0"  selected="selected"  > No Especificar </option>
 	 		                                                <option value="01"> Enero</option>
 	 		                                                <option value="02"> Febrero</option>
 	 		                                                <option value="03"> Marzo</option>
 	 		                                                <option value="04"> Abril</option>
 	 		                                                <option value="05"> Mayo</option>
 	 		                                                <option value="06"> Junio</option>
 	 		                                                <option value="07"> Julio</option>
 	 		                                                <option value="08"> Agosto</option>
 	 		                                                <option value="09"> Septiembre</option>
 	 		                                                <option value="10"> Octubre</option>
 	 		                                                <option value="11"> Noviembre</option>
 	 		                                                <option value="12"> Diciembre</option>
 	 		                                               
 	 		                                            </select> 

 	 		                                             
 	 		                                        </td>
 	 		                                   </tr>
 	 		                                 

 	 		                                   <tr  class="row_form"  id="tr_crpla_seltarea_row" > 
 	 		                                       <td>
 	 		                                           <span class="sp12b">
 	 		                                            Proyecto
 	 		                                           </span>
 	 		                                       </td>
 	 		                                       <td>
 	 		                                           :
 	 		                                       </td>
 	 		                                            
 	 		                                       <td>
 	 		                                            <select  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="font-size:11px; width: 250px;">
 	 		                                                <option value="0"> No Especificar </option>
 	 		                                                   <?PHP
 	 		                                                   foreach($tareas as $tarea){
 	 		                                                        echo "<option value='".trim($tarea['tarea_id'])."' ";

 	 		                                                        if($tarea['tarea_id'] == $hoja_info['tarea_id'])
 	 		                                                        {
 	 		                                                        	 echo " selected='selected' ";
 	 		                                                        }

																	if (trim($tarea['ano_eje']) >= 2021) {
																		echo " > (".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).') '.trim($tarea['tarea_nombre'])."</option>";
																	} else {
																		echo " > (".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).'-'.trim($tarea['tarea_nro']).') '.trim($tarea['tarea_nombre'])."</option>";
																	}
 	 		                                                   }
 	 		                                                 ?>
 	 		                                           </select>
 	 		                                       </td>
 	 		                                   </tr>

 	 		                                
 	 		                                   <tr class="row_form" >
 	 		                                        <td> <span class="sp12b">Descripcion</span></td>
 	 		                                        <td> <span class="sp12b">: </span></td>
 	 		                                        <td>  
 	 		                                            <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:40, name:'descripcion'" class="formelement-250-11"/>
 	 		                                        </td>
 	 		                                   </tr>
 	 		                                   
 	 		                                   <tr>
 	 		                                       <td colspan="3" align="center"> 
 	 		                                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
 	 		                                                   <?PHP 
 	 		                                                      $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
 	 		                                                   ?>
 	 		                                                     <script type="dojo/method" event="onClick" args="evt">
 	 		                                                             Asistencias.Ui.Grids.asistencias_registro_importacion.refresh();
 	 		                                                     </script>
 	 		                                                     <label class="sp11">
 	 		                                                         Realizar Busqueda
 	 		                                                     </label>
 	 		                                             </button>
 	 		                                       </td> 
 	 		                                   </tr> 
 	 		                                     
 	 		                             </table>
 	 		                       </form>
 	 		                   </div>
 	 		             </div>
 	 		         </div> 

 	 		 </div>
 	           		  
 	       
 	         <div id="dvasisregistroimportacion_table" ></div>
 	         
 	     </div>
 	    
 	     <div data-dojo-type="dijit.layout.ContentPane" 
 	          data-dojo-props='region:"center", style:"width: 460px;"' style=" padding:0px 0px 0px 0px; overflow: hidden; scroll:none; ">
 	  		
 			  <form id="dvasisimportacion_trabajadores_data" data-dojo-type="dijit.form.Form">   

 			  	 <input type="hidden" id="hdasisimportacion_trabajadores_view"  name="view" value=""  />
 			  	 <input type="hidden"   name="hoja" value="<?PHP echo $hoja_key; ?>"  />

 			  </form>

 			  <div class="dv_busqueda_personalizada_pa2"> 
 			  		<table class="_tablepadding2"> 
 			  			<tr> 
 			  				<td> <span class="sp11b"> Codigo </span> </td>
 			  				<td> <span class="sp11b"> : </span> </td>
 			  				<td> <span class="sp11" id="spasisimportacion_codigo"> </span> </td>
 			  			</tr>
 			  			<tr> 
 			  				<td> <span class="sp11b"> Proyecto </span> </td>
 			  				<td> <span class="sp11b"> : </span> </td>
 			  				<td> <span class="sp11" id="spasisimportacion_proyecto"> </span> </td>
 			  			</tr>
 			  			<tr> 
 			  				<td> <span class="sp11b"> Periodo </span> </td>
 			  				<td> <span class="sp11b"> : </span> </td>
 			  				<td> <span class="sp11" id="spasisimportacion_periodo"> </span> </td>
 			  			</tr>
 			  		</table>	

 			   </div>

 	          <div id="dvasisimportacion_trabajadores"></div>

 	     </div>
 	  
 	     <div  dojoType="dijit.layout.ContentPane"  
 	     	   data-dojo-props='region:"bottom", style:"height:40px;"' 
 	     	   style=" padding:0px 0px 0px 0px; overflow: hidden; ">

 	     	   <?PHP 
 	     	      if( trim($config['registro_asistencia_diario']) != '1' )
 	     	      {
 	     	           $fechamin = $hoja_info['hoa_fechaini'];
 	     	           $fechamax = $hoja_info['hoa_fechafin'];
 	     	      }  
 	     	      else
 	     	      {
 	     	           $fechamin = date('Y').'-'.date('m').'-'.date('d');
 	     	           $fechamax = $fechamin;
 	     	      }

 	     	   ?>

 	     	   <input type="hidden" id="hddetallefechamin" value="<?PHP  echo $fechamin;   ?>"/>
 	     	   <input type="hidden" id="hddetallefechamax" value="<?PHP  echo $fechamax;   ?>"/>

 	     	    <div class="dv_busqueda_personalizada_pa2" style="margin-top:8px; padding-left: 10px;">

 	     	   	 	 <form id="dvasisimportacion_trabajadores_data_2" data-dojo-type="dijit.form.Form">   
 
		 	     	     	 <span class="sp12b"> Fecha de inicio de trabajo: </span>
		 	     	    
		 	     	         <div id="calasis_addetini"  data-dojo-type="dijit.form.DateTextBox"
		 	     	                                              data-dojo-props='type:"text", name:"fechainiciotrabajo", value:"",
		 	     	                                               constraints:{datePattern:"dd/MM/yyyy", strict:true},
		 	     	                                              lang:"es",
		 	     	                                              required:true,
		 	     	                                              promptMessage:"mm/dd/yyyy",
		 	     	                                              invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"

		 	     	                                              onChange="dijit.byId('calasis_addetfin').constraints.min = this.get('value');  "
		 	     	                                             
		 	     	                                              >
		 	     	         </div> 

		 	     	         <span class="sp12b"> Hasta: </span>
		 	     	   
		 	     	         <div id="calasis_addetfin"  data-dojo-type="dijit.form.DateTextBox"
		 	     	                                              data-dojo-props='type:"text", name:"fechafintrabajo", value:"",
		 	     	                                               constraints:{datePattern:"dd/MM/yyyy", strict:true},
		 	     	                                              lang:"es",
		 	     	                                              required:true,
		 	     	                                              promptMessage:"mm/dd/yyyy",
		 	     	                                              invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
		 	     	         </div> 


     	     	         <button id="btn_importar_fromhoja" data-dojo-props="disabled:false"  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
     	     	                <?PHP 
     	     	                   $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
     	     	                ?>
     	     	                 <span class="sp11">Importar relacion de trabajadores</span>
    	     	                 <script type="dojo/method" event="onClick" args="evt">
    	     	                        
    	     	                         var data = dojo.formToObject('dvasisimportacion_trabajadores_data');
    	     	                         
    	     	                         var values = {}

    	     	                         for(x in data)
    	     	                         {
    	     	                           values[x] = data[x];
    	     	                         } 

    	     	                         data = dojo.formToObject('dvasisimportacion_trabajadores_data_2');
    		 	                         
    		 	                         for(x in data)
    		 	                         {
    		 	                           values[x] = data[x];
    		 	                         } 

    		 	                         // alert('Importar');

    		 	                         var ok = Asistencias._M.importar_trabajadores.process(values);
     
    		 	                         if(ok)
    		 	                         {
    		 	                         	Asistencias._V.importacion_trabajadores.close();
    		 	                         	Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value );
    		 	                         }

    	     	                 </script>
     	     	         </button>

 	     	     	</form>
 	    
 	     	        

 	     	  </div>
 	   

 	     </div>
 	    
 	             
 	</div>


</div>