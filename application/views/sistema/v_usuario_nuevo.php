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
	              Registrar un nuevo usuario
	          </td>
	      </tr>
	  </table>
	</div>


<?PHP 

	$existe_persona = false;
	$tiene_usuario = false;
	$habilitado_sistema = false;	


	$modo = 0;
	$boton_label = '';
	 
	if($data['individuo_id'] != '')
	{
		$existe_persona = true;
		  
	    if($data['usuario_id'] != '')
		{
			$tiene_usuario = true;	
	 	  
 	    	if($data['syus_id'] != '')
 	    	{
 	    		$habilitado_sistema = true;	
 	    		$boton_label = 'El usuario ya existe, y tiene acceso al sistema ';
 	    	}
 	    	else
 	    	{
 	    		$boton_label = 'Habilitar usuario';
 	    		$modo = 3;
 	    	}
 
	 	}	
		else
		{
			$boton_label = 'Crear usuario';
			$modo = 2;
		}
		
	}
    else
    {
    	$modo = 1; 
    	$boton_label = 'Registrar persona y crear usuario';
    }

	

//	var_dump($data);
?>

<form dojoType="dijit.form.Form" id="form_usuario_nuevo"> 

	<input type="hidden" value="<?PHP echo $modo ?>" name="modo" />

	<?PHP if($existe_persona){ ?>

	<input type="hidden" value="<?PHP echo $data['indiv_key'] ?>" name="persona" />

	<?PHP } ?>
 
    <?PHP if($tiene_usuario){ ?>
	<input type="hidden" value="<?PHP echo $data['usur_key'] ?>" name="usuario_id" />

	<?PHP } ?>


	<table class="_tablepadding4">
	 	 

	 	<tr>
	 		 <td width="150">
	 		 	 <span class="sp12b"> DNI </span>
	 		 </td>
	 		 <td width="10">
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	 
	 		 	<?PHP 
						 
					if($existe_persona === FALSE )
					{

			   ?> 
	 		 	
	 		 	 <input type="hidden" value="<?PHP echo $dni;  ?>" name="dni" /> 

	 		 	<?PHP 
	 		 		    echo ' <span class="sp12b"> '.$dni.' </span>';

	 		 		}
	 		 		else
	 		 		{

	 		 			echo ' <span class="sp12"> '.trim($data['indiv_dni']).' </span>';

	 		 		}
	 		 	?>

	 		 </td> 	
	 	</tr>

	 	<tr>
	 		 <td>
	 		 	 <span class="sp12b"> Nombre </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	<?PHP 
	 					 
	 				if($existe_persona === FALSE )
	 				{

	 		   ?> 
	  		 	
	  		 	 <input type="text" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'nombre'" />

	  		 	<?PHP 

	  		 		}
	  		 		else
	  		 		{

	  		 			echo ' <span class="sp12"> '.trim($data['indiv_nombres']).' </span>';

	  		 		}
	  		 	?>
	 		 </td> 	
	 	</tr>

	 	<tr>
	 		 <td>
	 		 	 <span class="sp12b"> Apellido Paterno </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	 	<?PHP 
	 					 
	 				if($existe_persona === FALSE )
	 				{

	 		   ?> 
	  		 	
	  		 	 <input type="text" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'paterno'" />

	  		 	<?PHP 

	  		 		}
	  		 		else
	  		 		{

	  		 			echo ' <span class="sp12"> '.trim($data['indiv_appaterno']).' </span>';

	  		 		}
	  		 	?>
	 		 </td> 	
	 	</tr>

	 	<tr>
	 		 <td>
	 		 	 <span class="sp12b"> Apellido Materno </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	<?PHP 
						 
					if($existe_persona === FALSE )
					{

			   ?> 
	 		 	
	 		 	 <input type="text" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'materno'" />

	 		 	<?PHP 

	 		 		}
	 		 		else
	 		 		{

	 		 			echo ' <span class="sp12"> '.trim($data['indiv_apmaterno']).' </span>';

	 		 		}
	 		 	?>
	 		 </td> 	
	 	</tr>

	 	<tr>
	 		 <td>
	 		 	 <span class="sp12b"> Fecha de Nacimiento </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	 <?PHP 
						 
					if($existe_persona === FALSE )
					{

			   ?> 
	 		 	
	 		   
	 		 	 <div id="cal_usuario_nuevo" data-dojo-type="dijit.form.DateTextBox"
	 		 	                  data-dojo-props='type:"text", name:"fechanac", value:"",
	 		 	                   constraints:{datePattern:"dd/MM/yyyy", strict:true},
	 		 	                  lang:"es",
	 		 	                  required:true,
	 		 	                  promptMessage:"mm/dd/yyyy",
	 		 	                  invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-100-12"
	 		 	                  
	 		 	                    onChange=""
	 		 	                  >
	 		 	              </div> 

	 		 	<?PHP 

	 		 		}
	 		 		else
	 		 		{

	 		 			echo ' <span class="sp12"> '.trim($data['indiv_fechanac']).' </span>';

	 		 		}
	 		 	?>
	 		 </td> 	
	 	</tr>

	 	<tr>
	 		 <td>
	 		 	 <span class="sp12b"> Dirección </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	 <?PHP 
						 
					if($existe_persona === FALSE )
					{

			   ?> 
	 		 	
	 		 	 <input type="text" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'direccion'" />

	 		 	<?PHP 

	 		 		}
	 		 		else
	 		 		{

	 		 			echo ' <span class="sp12"> '.(trim($data['indiv_direccion1']) != '' ? trim($data['indiv_direccion1']) : '------').' </span>';

	 		 		}
	 		 	?>
	 		 </td> 	
	 	</tr>

	 	<tr>
	 		 <td>
	 		 	 <span class="sp12b"> Telefono </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	<?PHP 
						 
					if($existe_persona === FALSE )
					{

			   ?> 
	 		 	
	 		 	 <input type="text" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'telefono'" class="formelement-80-12" />

	 		 	<?PHP 

	 		 		}
	 		 		else
	 		 		{

	 		 			echo ' <span class="sp12"> '.(trim($data['indiv_telefono']) != '' ? trim($data['indiv_telefono']) : '------' ).' </span>';

	 		 		}
	 		 	?>
	 		 </td> 	
	 	</tr>

	 	  

	 	 <tr>
	 		 <td>
	 		 	 <span class="sp12b"> Usuario </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 	 	 	 
	  		 	<?PHP 
	 					 
	 				if($tiene_usuario === FALSE )
	 				{

	 		   ?> 
	  		 	
	  		 	 <input type="text" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'usuario'"  class="formelement-100-12"  />

	  		 	<?PHP 

	  		 		}
	  		 		else
	  		 		{

	  		 			echo ' <span class="sp12"> '.(trim($data['usur_login']) != '' ? trim($data['usur_login']) : '------' ).' </span>';

	  		 		}
	  		 	?>

	 		 </td> 	
	 	</tr>


		 	<?PHP 
				 
			if($tiene_usuario === FALSE )
			{

	   ?> 

	 	 <tr>
	 		 <td>
	 		 	 <span class="sp12b"> Contraseña </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	 <input type="password" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'psw1'"  class="formelement-100-12"  />
	 		 </td> 	
	 	</tr>

	 	<tr>
	 		 <td>
	 		 	 <span class="sp12b"> Vuelva a escribir la contraseña </span>
	 		 </td>
	 		 <td>
	 		 	 <span class="sp12b"> : </span>
	 		 </td>
	 		 <td>
	 		 	 <input type="password" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'psw2'" class="formelement-100-12"  />
	 		 </td> 	
	 	</tr>


	 	<?PHP 
	 		}
	 	?>

 		 <tr>
 			 <td>
 			 	 <span class="sp12b"> Nivel </span>
 			 </td>
 			 <td>
 			 	 <span class="sp12b"> : </span>
 			 </td>
 			 <td>
 		 	 	 
 	 		 	<?PHP 
 						 
 					if($habilitado_sistema === FALSE )
 					{

 			   ?> 
 	 		 	
 	 		 	 	    <input type="text" value="" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'categoria', maxlength: 6"  class="formelement-100-12"  />


 	 		 	<?PHP 

 	 		 		}
 	 		 		else
 	 		 		{

 	 		 			echo ' <span class="sp12"> '.(trim($data['syus_categoria']) != '' ? trim($data['syus_categoria']) : '------' ).' </span>';

 	 		 		}
 	 		 	?>

 			 </td> 	
 		</tr>


 		 <tr>
 			 <td>
 			 	 <span class="sp12b"> Descripcion </span>
 			 </td>
 			 <td>
 			 	 <span class="sp12b"> : </span>
 			 </td>
 			 <td>
 		 	 	 
 	 		 	<?PHP 
 						 
 					if($habilitado_sistema === FALSE )
 					{

 			   ?> 
 	 		 	
 	 		 	 		<textarea data-dojo-type="dijit.form.TextArea" class="formelement-150-12" data-dojo-props="name: 'descripcion'" > </textarea>


 	 		 	<?PHP 

 	 		 		}
 	 		 		else
 	 		 		{

 	 		 			echo ' <span class="sp12"> '.(trim($data['syus_descripcion']) != '' ? trim($data['syus_descripcion']) : '------' ).' </span>';

 	 		 		}
 	 		 	?>

 			 </td> 	
 		</tr>

	  

	</table>

	<div align="center" style="margin:10px 0px 0px 0px;">

			<?PHP 
				if($modo != 0)
				{ 
			?>

			

				 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
				   <?PHP 
				      $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
				   ?>
				     <script type="dojo/method" event="onClick" args="evt">
					         	
				     		Users.Ui.btn_registrar_usuario(this,evt);

				     </script>
				     <label class="sp12">
				         
				     		<?PHP echo $boton_label; ?>

				     </label>
				</button>

			

			<?PHP 
				}
				else
				{
					  echo "<span class='sp12b'>".$boton_label."</span>";
				}
			?>

	 </div>


</form>


</div>