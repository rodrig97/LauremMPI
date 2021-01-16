<div class="window_container">

 <form id="form_info_cesar" name="form_info_cesar"  data-dojo-type="dijit.form.Form">  
	
	<div id="dvViewName" class="dv_view_name">
      
		    <table class="_tablepadding2" border="0">

		      <tr> 
		          <td> 
		               <?PHP 
		                         $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
		                     ?>
		          </td>

		        <td>
		            Cese de trabajadores 
		          </td>
		      </tr>
		  </table>
	</div>
 
 


	<div style="margin:4px 4px 4px 4px;">
		 <span class="sp12b">
		 	 Trabajadores seleccionados :
		 </span>
	</div>

    <div style='width:800px; height: 250px; font-size:12px; border:1px solid #988888; margin-left: 10px;'>

		<table id="tablecese_trabajadores" class="_tablepadding2">

			<tr class="tr_header_celeste">
				<td width="30">
					<span class="sp11b">
						 #
					</span>
				 </td>
				<td width="60">
					<span class="sp11b">
						 DNI 
					</span>
				 </td>
				<td width="300">
					<span class="sp11b">
						 TRABAJADOR
					</span>
				 </td>
				<td width="160">
					<span class="sp11b">
						 REGIMEN
					</span>
				 </td>
				 <td width="100">
				 	<span class="sp11b">
				 		 DESDE
				 	</span>
				  </td>
				<td width="100">
					<span class="sp11b">
						 HASTA
					</span>
				 </td>
			</tr>

		 <?PHP 
		 	$c = 1;
			foreach ($info_trabajadores as $reg)
			{ 
     	  ?>	
        		<tr class="tr_row_celeste">
        			<td> 
        				<span class="sp11"> <?PHP echo $c; ?>  </span>
        			</td>
        			<td> 
        				<span class="sp11"> <?PHP echo $reg['indiv_dni']; ?>  </span>
        			</td>
        			<td> 
        				<span class="sp11"> <?PHP echo $reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '.$reg['indiv_nombres']; ?>  </span>
        			</td>
        			<td> 
        				<span class="sp11"> <?PHP echo $reg['plati_nombre']; ?>  </span>
        			</td>
        			<td align="center"> 
        				<span class="sp11"> <?PHP echo (trim($reg['persla_fechaini']) != '' ?  _get_date_pg($reg['persla_fechaini']) : '-------'); ?>  </span>
        			</td>
        			<td align="center"> 
        				<span class="sp11"> <?PHP echo (trim($reg['persla_fechafin']) != '' ?  _get_date_pg($reg['persla_fechafin']) : '-------'); ?>  </span>
        			</td>
        		</tr>
 
        <?PHP 	
        		$c++;
			}
 			
 			if(sizeof($info_trabajadores) == 0)
 			{
 		?>
 			
 			<tr class="tr_row_celeste">

 				<td colspan="3"> 
 					 Tiene que seleccionar por lo menos un registro ACTIVO.			 
 				</td>
 				<td> 
 				 
 				</td>
 				<td align="center"> 
 					 
 				</td>
 				<td align="center"> 
 					 
 				</td>
 			</tr>

 		<?PHP 		
 			}
		?>	

		</table>	

	</div>

 	<?PHP 
 	
		if(sizeof($info_trabajadores)> 0)
		{

 	?>


	<div style="margin:10px 0px 0px 5px; border:1px solid #D4D4D4; padding: 3px 3px 3px 3px;">

		 <table class="_tablepadding2">	
		 	 <tr>
		 	 	 <td width="120"> 
		 	 	 	<span class="sp12b">Fecha de cese</span>
		 	 	 </td>
		 	 	 <td width="10"> 
		 	 	 	<span class="sp12b">: </span>
		 	 	 </td>
		 	 	 <td> 
		 	 	 	<input type="hidden" value="<?PHP  echo $fecha_minima; ?>" id="calcese_fechaminima" />

 	 				<input id="calcese_fecha" type="text" class="formelement-100-12" data-dojo-type="dijit.form.DateTextBox"
 	 	                                                    data-dojo-props='type:"text", name:"fechacese", value:"01/01/1980",
 	 	                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
 	 	                                                    lang:"es",
 	 	                                                    required:true,
 	 	                                                    promptMessage:"mm/dd/yyyy",
 	 	                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'

 	 	                                                onChange=""     
 	 	                                             />
		 	 	 </td>
		 	 </tr>
		 	 <tr>
		 	 	 <td> 
		 	 	 		<span class="sp12b">Observación </span>
		 	 	 </td>
		 	 	 <td> 
		 	 	 		<span class="sp12b">: </span>
		 	 	 </td>
		 	 	 <td> 
 	 				  <textarea data-dojo-type="dijit.form.TextArea" data-dojo-props="name:'observacion'" class="formelement-200-12" style="width:430px;" ></textarea>

		 	 	 </td>
		 	 </tr>
		 </table>	

	</div>

 	<input type="hidden" value="<?PHP echo $views; ?>" name="view" />


	<div align="center" style="margin:15px 0px 0px 0px;">
			<button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	                      <?PHP  $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
	                                  ?>
	                       <label class="sp11"> Cesar Trabajadores </label>
	                       <script type="dojo/method" event="onClick" args="evt">
	                               
	                              Historiallaboral.Ui.btn_cesar_masivo(this,evt);
	                       </script>
	        </button>
	</div>

	<?PHP 
		}
	?>
</form>

</div>
