
<table> 
<?PHP 
  
  var_dump($trabajadores);		
  $c = 0;
  foreach($trabajadores as $reg){  
  	$c++;
?>
	<tr> 
		 <td> <?PHP echo $c; ?> </td>		
		 <td> <?PHP echo $reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '-$reg['indiv_nombres'];   ?> </td>
		 
	</tr>
	

<?PHP 
}
?>

</table>