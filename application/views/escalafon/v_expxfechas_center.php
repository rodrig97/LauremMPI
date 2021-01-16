 
<div class="dv_busqueda_personalizada">
  	
  	<div> 
 		<span class="sp12b"> <?PHP echo $header['titulo'] ?></span>
  	</div>		
 
</div>

<input type="hidden" id="hdexplorarxfechas_tipo" value="<?PHP echo $tipo; ?>" />
 
<div id="dv_explorarxfechas" class="<?PHP echo $class_table; ?>"></div>

<div align="right" style="margin:8px 15px 0 0;">

     <button  data-dojo-type="dijit.form.Button" class="dojobtnfs_12"> 
            <?PHP 
               $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
              ?>
           <label class="lbl10">Ver Detalle</label>
           <script type="dojo/method" event="onClick" args="evt">
                              Persona.Ui.btn_exfverdetalle_click(this,evt);   
           </script>
     </button>

</div>

