
var Tareas =  {

    Cache  : {

    },

	 _M : { 


	 },

	 _V: {



        visualizar_presupuesto : new Laugo.View.Window({
            
             connect : 'tareas/visualizar_presupuesto',
              
              style : {
                   width :  '700px',
                   height:  '420px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Visualizar presupuesto  ',
              
              onLoad: function(){
                   
 
 
 
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        })

	 }

}