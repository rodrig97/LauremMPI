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
	              Actualizar horario 
	          </td>
	      </tr>
	    </table>
	</div>

	<?PHP 

		$dias = array(
						'Lunes' => '1',
						'Martes' => '2',
						'Miercoles' => '3',
						'Jueves' => '4',
						'Viernes' => '5',
						'Sabado' => '6',
						'Domingo' => '7' 
					 );

	?>

	<table class="_tablepadding4">

			<tr class="tr_header_celeste">
			 	<td width="100"> 
			 		<span class="sp11b"> Día </span>
			 	</td>		
			 	<td width="10"> 
			 		 
			 	</td>		
			 	<td width="140"> 
			 		<span class="sp11b"> Estado </span>
			 	</td>		
			 	<td width="140"> 
			 		<span class="sp11b"> Horario </span>
			 	</td>		
			</tr>

		<?PHP 
			 foreach ($dias as $dia => $dia_c)
			 {
		?>
 				<tr class="row_form">
					<td> 
						<span class="sp11b"> <?PHP echo $dia; ?> </span>
					</td>
					<td> 
						<span class="sp11b">:  </span>
					</td>
				 	 <td> 
		 				 <select data-dojo-type="dijit.form.FilteringSelect"  
		 				 		 data-dojo-props=' name:"estado",	
		 				 		 				    autoComplete:false, 
		 				 		 				    highlightMatch: "all",  
		 				 		 				    queryExpr:"*${0}*", 
		 				 		 				    invalidMessage: "Estado no registrado"   ' 
		 				 		  class="formelement-100-11" style="width:120px;">
			 		    	  
			 		    	  <?php
			 		    	  	foreach($estados_dia as $est){

			 		    	  		echo "<option value='".$est['hatd_id']."' ";

			 		    	  		if($est['hatd_id'] == $diario_info['hatd_id']){
		 									
		 								echo " selected='true'";
			 		    	  		}

			 		    	  		echo ">  ".$est['hatd_nombre']." </option>";

			 		    	  	}
			 		    	  ?>
			 		    </select>		
				 	 </td>
				</tr>

		<?PHP
			 }
		?>

			<tr>	
				<td align="center" colspan="3">

						<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
							  <?PHP 
							     $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
							  ?>
							   <script type="dojo/method" event="onClick" args="evt">
						 	   </script>
							   <label class="sp11">
							                 Actualizar             
							   </label>
						</button>
				</td>
			</tr>


		 	<tr id="trregistrodiario_horario1" class="row_form">
		 		<td><span class="sp11b">Mañana Ingreso</span></td>
		 		<td><span class="sp11b">:</span></td>
		 		<td> 
		 			 <input id="fec_hora1"  data-dojo-type="dijit.form.TimeTextBox"
		 			            data-dojo-props='type:"text", name:"hora1", value:"T07:45:00",
		 			            title:"",
		 			            constraints:{formatLength:"short", min:"T06:45:00",max:"T17:15:00"},
		 			            required:true,
		 			            invalidMessage:"" ' 
		 			            style="width:60px; font-size:11px;" 

		 			            onChange="dijit.byId('fec_hora2').constraints.min = this.get('value'); "

		 			            />

		 			  <input id="fec_horax" data-dojo-type="dijit.form.TimeTextBox"
		 			             data-dojo-props='type:"text", name:"hora4", value:"T07:45:00",
		 			             title:"",
		 			             constraints:{formatLength:"short"},
		 			             required:true,
		 			             invalidMessage:"" ' style="width:60px; font-size:11px;" 

		 			             />
		 		</td>

		 		<td><span class="sp11b"> Salida</span></td>
		 		<td><span class="sp11b">:</span></td>
		 		<td> 

		 			 <input id="fec_hora2"  data-dojo-type="dijit.form.TimeTextBox"
		 			            data-dojo-props='type:"text", name:"hora2", value:"T07:45:00",
		 			            title:"",
		 			            constraints:{formatLength:"short"},
		 			            required:true,
		 			            invalidMessage:"" ' style="width:60px; font-size:11px;"/>

		 		</td>
		 	</tr>


		 	<?PHP 

		 		if($diario_info['hor_numero_horarios'] == '2')
		 		{

		 	?>
		 	
		  	<tr id="trregistrodiario_horario2" class="row_form">
		 		<td><span class="sp11b">Tarde Ingreso</span></td>
		 		<td><span class="sp11b">:</span></td>
		 		<td> 
		 			 <input id="fec_hora3"  data-dojo-type="dijit.form.TimeTextBox"
		 			            data-dojo-props='type:"text", name:"hora3", value:"T07:45:00",
		 			            title:"",
		 			            constraints:{formatLength:"short"},
		 			            required:true,
		 			            invalidMessage:"" ' style="width:60px; font-size:11px;"
		 			             onChange="dijit.byId('fec_hora4').constraints.min = this.get('value'); "
		 			            />
		 		</td>

		 		<td><span class="sp11b"> Salida</span></td>
		 		<td><span class="sp11b">:</span></td>
		 		<td> 
		 			
		 			 <input id="fec_hora4" data-dojo-type="dijit.form.TimeTextBox"
		 			            data-dojo-props='type:"text", name:"hora4", value:"T07:45:00",
		 			            title:"",
		 			            constraints:{formatLength:"short"},
		 			            required:true,
		 			            invalidMessage:"" ' style="width:60px; font-size:11px;" 

		 			            />

		 		</td>
		 	</tr>

		 	<?PHP 
		 	    }
		 	?>
		 	
		 	<!-- Descontar horas por almuerzo -->

		 	<!-- Descontar solo si la hora2 es mayor a..  -->


		 	

	</table>


</div>