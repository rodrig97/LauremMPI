
<div class="window_container">

	<div data-dojo-type="dijit.layout.BorderContainer" 
		 data-dojo-props='design:"headline", liveSplitters:true' style="width:840px; height: 425px;">

		  <div  data-dojo-type="dijit.layout.ContentPane" 
		  		data-dojo-props=' splitter: true, region:"top", style:"height: 80px; padding: 0px 0px 0px 0px;"' >

		 		 <div id="dvViewName" class="dv_view_name">
		 		    

		 		      <table class="_tablepadding2" border="0">
		 		          <tr> 
		 		             <td> 
		 		                 <?PHP 
		 		                  $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
		 		                  ?>
		 		             </td>
		 		             <td>
		 		                  SUNAT - PDT
		 		             </td>
		 		          </tr>
		 		     
		 		      </table>
		 		 </div>
 	
		 		<form dojoType="dijit.form.Form" id="frmfiltrar_pdtregimen">  	

			 		 <table class="_tablepadding4">
			 		 	<tr>
			 		 		<td> 
			 		 			<span class="sp11b"> Tipo de planilla </span>
			 		 		</td>
			 		 		<td> 

			 		 			<span class="sp11b"> : </span>
			 		 		</td>
			 		 		<td> 
			 		 			
			 		 			<select id="selpdtpensionable_regimen"  
			 		 					data-dojo-type="dijit.form.Select" data-dojo-props='name:"planillatipo", disabled:false'
			 		 			        style="margin-left:6px; font-size:12px; width:200px;">
			 		 			      <?PHP
			 		 			        foreach($tipos as $tipo){
			 		 			             echo "<option value='".trim($tipo['plati_key'])."'>".trim($tipo['plati_nombre'])."</option>";
			 		 			        }
			 		 			      ?>
			 		 			</select>

			 		 		</td>
			 		 	 
			 		 	</tr>	
			 		 </table>	

		 		</form>

		 </div>


		  <div id="dvpdtpensionable_regimen_container"  data-dojo-type="dijit.layout.ContentPane" 
		  		data-dojo-props=' splitter: true, region:"center", style:"width: 420px;"' >
		    		
		<!--     	Pensionable la formula actual 
		    	
		    	Conceptos del pensionable 

 			    Otros ingresos que no estan en el pensionable pero tienen casilla.		 -->


		   </div>


	</div>
</div>