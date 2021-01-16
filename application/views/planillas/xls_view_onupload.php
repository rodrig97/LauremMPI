<html>
    <head>
        
        <script> 
             
            function __conect_parent(){
                 //alert('completado');
                 var data =  {};
                
                 <?PHP echo $call_function; ?>( <?PHP echo json_encode($data);?> );
            }
        </script>
    </head>
    
    <body <?PHP  if(isset($data))echo ' onload="__conect_parent();" ' ?> >
        
    </body>
</html>