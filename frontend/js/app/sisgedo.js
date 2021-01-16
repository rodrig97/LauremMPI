/* 
 *  Implementacion que se conecta a la base de datos del sisgedo
 */


var Sisgedo = {
     
     
     
     _M : {
         
          get_data: new Laugo.Model({
              connect: 'sisgedo/get_info_documento',

              message_function : function(){

              }
          }) 
          
     },
     
     _V : {
         
         
     },
     
     Ui: {
         
     },

     

     ready : function( dijit_node, div_info_node){
 	
     	  
     	  dojo.connect( dijit_node, 'onKeyPress', function(evt){
             
                 if(evt.charOrCode == dojo.keys.ENTER){
 						
 				 	        Sisgedo._M.get_data.process({ codigo : dijit_node.get('value')});

 				 	 				     div_info_node.innerHTML = Sisgedo._M.get_data.data.html;
                        console.log('Aqui');

                        if(dojo.query('.spquitardoc',div_info_node)[0] != null){ 
                           dojo.connect(dojo.query('.spquitardoc',div_info_node)[0], 'onclick', function(){

                              div_info_node.innerHTML = '';
                           });

                         }
                 }
                            
          });


         dojo.connect( dijit_node, 'onKeyDown', function(evt){
                                  
              if( evt.keyCode == 190 || evt.keyCode == 110 || (evt.keyCode>=48 && evt.keyCode <= 57) || (evt.keyCode>=96 && evt.keyCode <= 105) || evt.keyCode == 8 || evt.keyCode == 9){

                                   }
                                    else{
                                        evt.preventDefault();
                                    }
                           
         });

     },


     desvincular_doc : function(parent){


          dojo.query('#'+parent+' .info_sisgedo')[0].innerHTML = '';

     }
    


    
}