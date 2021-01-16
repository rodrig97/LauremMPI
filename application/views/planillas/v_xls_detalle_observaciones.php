

<div style="padding: 10px; ">
	 
	<br/>

	<div>
		
		 <table class="_tablepadding2" border="0">

		    <tr> 
		        <td> 
		             <?PHP 
		                       $this->resources->getImage('cancel.png',array('width' => '20', 'height' => '20'));
		                   ?>
		        </td>

		      <td>
		  	 			<span class="dv_subtitle1"> Observaciones en el archivo </span>
		        </td>
		    </tr>
		</table>

	 		 
		<div class="dv_xls_errores">
		 
		    <ul class="ul_xls_error"> 
		            	
		            <?PHP 
		            foreach($log as $reg)
		            {
		            ?> 
		                  <li> <b> Registro  <?PHP echo $reg['registro'] ?>:  </b> <?PHP echo $reg['mensaje']; ?>  </li>  
		            
		            <?PHP 
		            }
		            ?>
		    </ul>

		    <div class="clearfix"></div>
	 
		</div>

	</div>

</div>