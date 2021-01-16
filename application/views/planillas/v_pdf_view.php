
<div class="window_container"> 
<?PHP 

if($visualizar_button)
{

?> 

	<div align="right" style="margin-right:10px;">
		 
		   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		      <?PHP 
		         $this->resources->getImage('page_search.png',array('width' => '14', 'height' => '14'));
		      ?>
			    <script type="dojo/method" event="onClick" args="evt">
 
			          dojo.byId('frmpreviewpdf').target = '_blank';
			          dojo.byId('frmpreviewpdf').submit();
					  dojo.byId('frmpreviewpdf').target = 'framepreviewpdf';
			          

			    </script>
			    <label class="sp12">
			         Visualizar  
			    </label>
		  </button>

	</div>
 
<?PHP 

}

?>

<div style="margin-top:10px;">

	<form id="frmpreviewpdf" action="<?PHP echo $ref; ?>" target="framepreviewpdf" method="post">

			<?PHP
 
			foreach ($params as $name => $value)
			{
			 
				echo '  <input type="hidden" name="'.$name.'" value="'.$value.'" /> ';
			}
			 ?>

	</form>
 
 	<iframe name="framepreviewpdf" src="" width='828' height='440'  />

</div>


</div>