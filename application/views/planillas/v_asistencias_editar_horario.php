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
	              Actualizar horario por defecto.
	          </td>
	      </tr>
	    </table>
	</div>

	<div class="dv_busqueda_personalizada">

		<span class="sp12b"> 
			 Tipo de trabajador : 
		</span>
		<span class="sp12"> 

			<?PHP 	


				 if($modo == 'regimen')
				 {
				 	 echo trim($view_info['plati_nombre']);
				 }

			?> 

		</span>
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
			 	<td> 
			 			Dia Laborable
 				</td>
			 	<td width="140"> 
			 		<span class="sp11b"> Estado  </span>
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
						 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'laborable'" style="font-size:11px; width:40px;"> 
						 		<option value="1"> Si </option>
						 		<option value="0"> No </option>
						 </select>
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
				 	 <td>
				 	 	<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
				 	 		  <?PHP 
				 	 		     $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
				 	 		  ?>
				 	 		   <script type="dojo/method" event="onClick" args="evt">
				 	 	 	   </script>
				 	 		   <label class="sp11">
				 	 		                               
				 	 		   </label>
				 	 	</button>
				 	 </td>
				 	 <td>
				 	 	 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
							  <?PHP 
							     $this->resources->getImage('search_add.png',array('width' => '14', 'height' => '14'));
							  ?>
							   <script type="dojo/method" event="onClick" args="evt">
						 	   </script>
							   <label class="sp11">
							                          
							   </label>
						</button>
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
		 

	</table>



</div>