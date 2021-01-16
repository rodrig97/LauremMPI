
<!--  

<div style="margin: 8px 8px 8px 8px;">
 -->

	<div style="margin:10px 0px 10px 0px;">


	
	    <table class="_tablepadding2" border="0">

		    <tr> 
		        <td> 
		             <?PHP 
		                       $this->resources->getImage('accept.png',array('width' => '20', 'height' => '20'));
		                   ?>
		        </td>

		      <td>
		  	 			<span class="dv_subtitle1"> Resumen  de la importación </span>
		        </td>
		    </tr>
		</table>
	
	</div>
<!-- 	
	<div class="dv_busqueda_personalizada">

		<div style="margin:10px 0px 10px 0px">

			<span class="sp12b"> N° de registros importados </span>
			<span class="sp12b"> : </span> 
			<span>   <?PHP echo $total_rows; ?> </span>

		</div> 	

			<?PHP 
				foreach($totales_columnas as $columna => $columna_result)
				{

					echo '<div style="margin:5px"> ';

					echo '<div> <span class="sp12b"> Desde la columna:  </span> '.$columna." </div>";
		  	
		 	
					foreach($columna_result as $vari => $total)
					{

					 	 echo " <div style='margin-left:8px;'>  <span class='sp12b'> A la variable: </span> ".$vari."  <span class='sp12b'> Total:  </span> ".$total." </div>";
					}


					echo '</div>';

				}
			?>
	</div>

</div> -->