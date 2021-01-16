
var Tipoplanilla =  {

    Cache  : {

    	 main : null 
        
    },

	_M : { 


		registrar_categoria : new Laugo.Model({
		   connect  : 'tipoplanillas/registrar_categoria'
		}),

		actualizar_categoria : new Laugo.Model({
		   connect  : 'tipoplanillas/actualizar_categoria'
		}),
 
		eliminar_categoria : new Laugo.Model({
		   connect  : 'tipoplanillas/eliminar_categoria'
		}),
 
		actualizar_parametros_sunat : new Laugo.Model({
		   connect  : 'tipoplanillas/actualizar_parametros_sunat'
		})

    },

	_V : { 

		    view : new Request({
		              
			          type :  'text',
			          method: 'post',
			          
			          url : 'tipoplanillas/view',
			          
			          onRequest : function()
			          {
			                dijit.byId('dvgtp_view').set('content','<div class="dv_cargando">Cargando..</div>');
			          },
			          
			          onSuccess  : function(responseText)
			          {
								
			          		dijit.byId('dvgtp_view').set('content', responseText);


			          		require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
			          		            function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


			          		            if(dojo.byId('dvgtp_categorias') != null )
			          		            { 

			          		                 if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


			                                    var store = Observable(Cache(JsonRest({
			                                            
			                                            target: app.getUrl() +"tipoplanillas/get_categorias", 
			                                            idProperty: "id",
			                                            sortParam: 'oby',
			                                            query: function(query, options){
			                                                  

			                                            		var data = dojo.formToObject('form_tp_categorias');

			                                            		for(x in data ){
			                                            		  query[x] = data[x];
			                                            		}

			                                                    return JsonRest.prototype.query.call(this, query, options);
			                                            }

			                                    }), Memory()));
			                                     
			                                    
			                                    var colums = {  

			                                      		    col1: {label: '#', sortable: true },
			                                                col2: {label: 'Categoria', sortable: false },
			                                                col3: {label: 'Descripción', sortable: false} 
			                                               
			                                     };

			                                    Tipoplanilla.Ui.Grids.categorias  = new  window.escalafon_grid({

			                                            store: store,
			                                            getBeforePut: false,
			                                            columns: colums,
			                                            showFooter :true 


			                                    }, "dvgtp_categorias");

			          		                    if( Tipoplanilla.Ui.Grids.categorias != null){
			          		                             
		      		                                 Tipoplanilla.Ui.Grids.categorias.refresh();
		      		                                
		      		                            }          
			          		              
			          		            }
		 
			          		});

			          },
			          
			          onFailure : function(){
			              
			          } 
			              
		    }),	

			

			configuracion_sunat : new Laugo.View.Window({
			   
			    connect : 'tipoplanillas/configuracion_sunat',
			    
			    style : {
			         width : '600px',
			         height : '600px',
			         'background-color' : '#FFFFFF'
			    },
			    
			    title : ' Configuracion SUNAT T-Registro',
			    
			    onLoad : function(){


			              
			    }
			   
			}), 
 

		    nueva_categoria : new Laugo.View.Window({
		       
		        connect : 'tipoplanillas/nueva_categoria',
		        
		        style : {
		             width : '400px',
		             height : '200px',
		             'background-color' : '#FFFFFF'
		        },
		        
		        title : ' Nueva Categoria ',
		        
		        onLoad : function(){


		                  
		        }
		       
		    }) 

	 },

	 Ui : {


	 	  Grids : {
 
	 	  		main  : null,
	 	  		categorias : null
	 	  },

   		
	      btn_showaddconcepto_click : function(btn,evt)
	      {
	       

	      } 

	  } 

}
 