 
<div id="dvViewName" class="dv_view_name">
      
    <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
      			Tipo de planilla
          </td>
      </tr>
  </table>
</div>


<div style="margin:8px 0px 8px 0px;">

	<input name="view" type="hidden" class="hdkeyview" value="<?PHP echo trim($info['plati_key']); ?>" /> 
 
	<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	   <?PHP 
	      $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
	   ?>
	     <script type="dojo/method" event="onClick" args="evt">
	     
	     </script>
	     <label class="sp11">
	     		 Modificar
	     </label>
	</button>

	<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	   <?PHP 
	      $this->resources->getImage('block.png',array('width' => '14', 'height' => '14'));
	   ?>
	     <script type="dojo/method" event="onClick" args="evt">
	     
	     </script>
	     <label class="sp11">
	     		 Desactivar
	     </label>
	</button>

	<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	   <?PHP 
	      $this->resources->getImage('application_up.png',array('width' => '14', 'height' => '14'));
	   ?>
	     <script type="dojo/method" event="onClick" args="evt">


	     		var codigo_e = dojo.query('.hdkeyview',this.parentNode)[0].value;
	     		
	     		 Tipoplanilla._V.configuracion_sunat.load({'view' : codigo_e, 'modo' : 'tipoplanilla'});

	     </script>
	     <label class="sp11">
	     		Parámetros T-Registro SUNAT
	     </label>
	</button>

</div>


<div class="dvFormBorder">

	<table class="_tablepadding4">
		<tr>
			<td width="120">  
				<span class="sp12b">  Nombre   </span>	
			</td>
			<td width="10">
				<span class="sp12b"> :  </span>
			</td>
			<td>
	 			<span class="sp12"> 
					<?PHP echo ( trim($info['plati_nombre']) == '' ? '------' : trim($info['plati_nombre'])  ); ?>
	 			</span>
			</td>
		</tr>

		<tr>
			<td>  
				<span class="sp12b">  Nombre Trabajador   </span>	
			</td>
			<td>
				<span class="sp12b"> :  </span>
			</td>
			<td>
	 			<span class="sp12"> 
					<?PHP echo ( trim($info['plati_tipoempleado']) == '' ? '------' : trim($info['plati_tipoempleado'])  ); ?>
	 			</span>
			</td>
		</tr>

		<tr>
			<td>  
				<span class="sp12b">  Nombre Abreviado   </span>	
			</td>
			<td>
				<span class="sp12b"> :  </span>
			</td>
			<td>
	 			<span class="sp12"> 
					<?PHP echo ( trim($info['plati_abrev']) == '' ? '------' : trim($info['plati_abrev'])  ); ?>
	 			</span>
			</td>
		</tr>



		<tr>
			<td>  
				<span class="sp12b">  Activo   </span>	
			</td>
			<td>
				<span class="sp12b"> :  </span>
			</td>
			<td>
	 			<span class="sp12"> 
					<?PHP echo ($info['plati_activo'] == '1') ? 'Si' : 'No'; ?>
	 			</span>
			</td>
		</tr>



		<tr>
			<td>  
				<span class="sp12b">  Tiene Categorias   </span>	
			</td>
			<td>
				<span class="sp12b"> :  </span>
			</td>
			<td>
	 			<span class="sp12"> 
					<?PHP echo ($info['plati_tiene_categoria'] == '1') ? 'Si' : 'No'; ?>
	 			</span>
			</td>
		</tr>

	</table>

</div>

<?PHP 
	if($info['plati_tiene_categoria'] == '1')
	{
?>
		<form id="form_tp_categorias" data-dojo-type="dijit.form.Form" data-dojo-props="">

			<input name="view" type="hidden" value="<?PHP echo trim($info['plati_key']); ?>" /> 

		</form>		

		<div style="margin:10px 10px 10px 10px;">

			<div id="dvgtp_categorias">

			</div>

		</div>

		<div align="left" style="margin:5px;">

			<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
			   <?PHP 
			      $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
			   ?>
			     <script type="dojo/method" event="onClick" args="evt">
			     	
			     		var datos = dojo.formToObject('form_tp_categorias');

			     	 	Tipoplanilla._V.nueva_categoria.load(datos);	

			     </script>
			     <label class="sp11">
			     		 Nueva categoria
			     </label>
			</button>


			<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
			   <?PHP 
			      $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
			   ?>
			     <script type="dojo/method" event="onClick" args="evt">
			     
			     </script>
			     <label class="sp11">
			     		 Modificar
			     </label>
			</button>

			<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
			   <?PHP 
			      $this->resources->getImage('block.png',array('width' => '14', 'height' => '14'));
			   ?>
			     <script type="dojo/method" event="onClick" args="evt">
			     
			     </script>
			     <label class="sp11">
			     		 Eliminar
			     </label>
			</button>
		</div>

<?PHP 
	}
?>