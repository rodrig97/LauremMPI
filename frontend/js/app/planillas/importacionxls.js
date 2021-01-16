
var importacionxls = {
 	
 	window_bar_loader : null,
 	
 	procesando : false,

 	indices_importacion : {

 			maximo : 0,
 			actual : 0
 	},

 	resumen_importacion : {},

 	resumen_importacion_total : 0,

	_M : {

		explorar_archivo : new Request({
		    
		        type: 'json',

		        method: 'post',
		        
		        url : 'importacionxls/explorar',
		        
		        onRequest : function()
		        {
		             app.loader_show(); 
		        },
		        
		        onSuccess  : function(data)
		        {	

		        	importacionxls.indices_importacion.maximo = data.numeregistros;
		        	importacionxls.indices_importacion.actual = 1;

		        //console.log(importacionxls.indices_importacion.maximo);
		            
		           dijit.byId('importcs_viewfile').set('content', data.html_table);
   				  

   					if(dojo.byId('hbxlstable_loaded') != null)
   					{ 

			           var Table = new superTable("table_resumen_xls", {
			                            cssSkin : "sDefault",
			                            headerRows : 1,
			                            fixedCols  : 1,
			                            colWidths  : [],
			                            onStart : function () {
			                           
			                            },
			                            onFinish : function () {
			                              
			                            }
			        
			                       });

		           	}

		           dijit.byId('importcs_result').set('content', data.html_result);
  
		           app.loader_hide();   
		              
		        },
		        
		        onFailure : function(){

		            app.loader_hide();    
		            alert('Algo no salio bien.');
		            
		        } 
		        
		 }),

	 	
		explorar_archivo_trabajadores : new Request({
		    
		        type: 'json',

		        method: 'post',
		        
		        url : 'importacionxls/explorar_importacion_trabajadores',
		        
		        onRequest : function()
		        {
		             app.loader_show(); 
		        },
		        
		        onSuccess  : function(data)
		        {
		            
		           dijit.byId('importcs_viewfile').set('content', data.html_table);
   			

   					if(dojo.byId('hbxlstable_loaded') != null)
   					{ 

			           var Table = new superTable("table_resumen_xls", {
			                            cssSkin : "sDefault",
			                            headerRows : 1,
			                            fixedCols  : 1,
			                            colWidths  : [],
			                            onStart : function () {
			                           
			                            },
			                            onFinish : function () {
			                              
			                            }
			        
			                       });

		           	}

		           dijit.byId('importcs_result').set('content', data.html_result);
  
		           app.loader_hide();   
		              
		        },
		        
		        onFailure : function(){

		            app.loader_hide();    
		            alert('Algo no salio bien.');
		            
		        } 
		        
		 }),	


		importar : new Request({
				    
				        type: 'json',

				        method: 'post',
				        
				        url : 'importacionxls/importar',
				        
				        onRequest : function()
				        {
				            // app.loader_show(); 
				             
				             if( importacionxls.indices_importacion.actual == 1 )
				             {
				             	 importacionxls.procesando = true;
/*
				              	 importacionxls.window_bar_loader = new dijit.Dialog({
				             	               title:   'Procesando',
				             	               content:  '<div data-dojo-type="dijit.layout.ContentPane"> <div id="pbimportarexcel" data-dojo-type="dijit.ProgressBar" data-dojo-props=""></div>  </div>', 
				             	               style:  'width: 300px; height:80px',
				             	               onCancel : function(){
				             	                      if( importacionxls.procesando == true )
				             	                      {
				             	                      	  this.stop();
				             	                      	  
				             	                      	  console.log('NO aun NO O');
				             	                      	  return false;
				             	                      }
				             	                      else
				             	                      {
				             	                      	   dijit.registry.remove( 'pbimportarexcel' ); 
				             	                      	   console.log('Ahora si');
				             	                      }
				             	               }
				             	           });

				              	 importacionxls.window_bar_loader.show();*/

				              	 app.loader_pb_show();
				             	           		
				             }
				        },
				        
				        onSuccess  : function(data)
				        {
				      
				           //app.loader_hide(); 

				           if( importacionxls.indices_importacion.actual == 1 )
				           {
				           		dijit.byId('pbimportarexcel').set('maximum', importacionxls.indices_importacion.maximo );
				           }
 
				           if(data.result == '1')
				           {	


 							    importacionxls.indices_importacion.actual = data.punto_limite; 

 							    importacionxls.resumen_importacion_total += data.total_rows;
				           	
				           		dijit.byId('pbimportarexcel').set('value', importacionxls.indices_importacion.actual );
 								
 							   //console.log(data.resumen);

 							    for( d in data.resumen  )
 							    {	
 							    	//console.log(d);
 							    	 
 							    	 if(importacionxls.resumen_importacion[d] == null) 
 							    	 {
 							    	 	importacionxls.resumen_importacion[d] = {}
 							    	 }

 							    	 for( v in data.resumen[d] )
 							    	 {	
 							    	 
 							    	 	// console.log(v + ' : ' + data.resumen[d][v]);

 							    	 	 if(importacionxls.resumen_importacion[d][v] == null) 
 							    	 	 {
 							    	 	 	importacionxls.resumen_importacion[d][v] = data.resumen[d][v];
 							    	 	 }
 							    	 	 else
 							    	 	 {
 							    	 	 	importacionxls.resumen_importacion[d][v]+= data.resumen[d][v]; 	 
 							    	 	 }
 
 							    	 }
 							    }


 								if( (importacionxls.indices_importacion.actual - 1 ) >= importacionxls.indices_importacion.maximo )
 								{

		 								importacionxls.procesando = false;
		 								
		 								
		 								app.loader_pb_hide(); 	

		 								importacionxls._V.configuracion.load({});


		  				
		 							//console.log(importacionxls.resumen_importacion);

						           	 	app.view_load(null, { view : 'main/white_board', 

						           	 						  data : {},

						           	 						  fn : function(){}

						           	 						 });
									 	
									 	 var html = data.html_result + ' <div class="dv_busqueda_personalizada"> <div>  <b> Registros importados : </b> '+ importacionxls.resumen_importacion_total +'</div> <ul style="margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; list-style:none;"> ';

									 	 for( d in importacionxls.resumen_importacion  )
									 	 {	

									 	 	 html+='<li> <b>De la Columna: '+d+'</b> <ul  style="margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; list-style:none;"> ';

									 	 	 for( v in importacionxls.resumen_importacion[d]  )
									 	 	 {

									 	 	 	   html+=" <li> A la variable " + v + "  un total de : "+ importacionxls.resumen_importacion[d][v] +"</li> ";
									 	 	 }

									 	 	 html+=" </ul> </li> ";

									 	 }

									 	 html+=" </ul> </div> ";


 				          				 app.alert(html);
						         }
						         else
						         {

						         	 	importacionxls.importar();
						         }
 				           }
 				           else
 				           {		
 				           		importacionxls._M.explorar_archivo.reload();
 				           		
 				           }

				           
				        },
				        
				        onFailure : function(){

				            app.loader_hide();    
				            
				            alert('Algo no salio bien, este error podria traer problemas por favor contactarse con el programador del sistema. Error [Importar a Planilla desde excel] ');
				            
				        } 
				        
				 }),



		importar_trabajadores : new Request({
				    
				        type: 'json',

				        method: 'post',
				        
				        url : 'importacionxls/importar_trabajadores',
				        
				        onRequest : function()
				        {
				             app.loader_show(); 
				        },
				        
				        onSuccess  : function(data)
				        {
				      
				           app.loader_hide(); 
 
				           if(data.result == '1')
				           {
 							 	app.view_load(null, { view : 'main/white_board', 

				           	 						  data : {},

				           	 						  fn : function(){}

				           	 						 });
 				           }
 				           else
 				           {		
 				           		importacionxls._M.explorar_archivo_trabajadores.reload();
 				           		
 				           }

 				           app.alert(data.html_result);
				           
				        },
				        
				        onFailure : function(){

				            app.loader_hide();    
				            alert('Algo no salio bien.');
				            
				        } 
				        
				 })  


	},

	_V : {


		configuracion : new Laugo.View.Window({
            
             connect : 'importacionxls/configuracion',
              
              style : {
                   width :  '680px',
                   height:  '350px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Especificar detalles de la importacion ',
              
              onLoad: function()
              {
  
              		dojo.setStyle( dojo.byId('dvimpxls_configuracion') ,'display', 'none');

              		dojo.connect( dijit.byId('selimpxls_modo') , 'onChange', function(){

              				var modo = dijit.byId('selimpxls_modo').get('value');
 							
 							var const_configuracion_personalizada = 99;

              				if( modo == const_configuracion_personalizada )
              				{
              				 	dojo.setStyle( dojo.byId('dvimpxls_configuracion') ,'display', 'block');			
              				}	
              				else
              				{
              					dojo.setStyle( dojo.byId('dvimpxls_configuracion') ,'display', 'none');

              				}	

              		});


              		dojo.connect( dijit.byId('selimpxls_by'), 'onChange', function(){
 
              			 if(dijit.byId('selimpxls_by').get('value') == 'mes')
              			 {
	 
              			 		dijit.byId('selimpxls_vinculacion').set('value', 'no_vincular');

              			 		dijit.byId('selimpxls_vinculacion').set('readOnly', true );
              			 }
              			 else
              			 {
              			 	   dijit.byId('selimpxls_vinculacion').set('readOnly', false );
              			 }
 
              		});

              },

              onclose : function()
              {

              }
        }) 


	},

	Ui : {


		btn_importacion_click : function(btn,evt){


			var datos = dojo.formToObject('frm_importxls_configuracion');
 
			importacionxls._V.configuracion.close();
 
			app.view_load(null, { view : 'importacionxls/panel', 

								  data : datos,

								  fn : function(){
 
								  		var dims = app.get_dims_body_app(),
								  		    desc_altura = (dims.h > 700) ? (200) : 0;
								  		     
								  		dijit.byId('xlsimportacion_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});								  }

								});

		},


		btn_importar_click : function(btn,evt)
		{
				importacionxls.resumen_importacion = {}

				importacionxls.resumen_importacion_total = 0;

				importacionxls.importar();
 
		},


		btn_importar_trabajadores_click : function(btn,evt)
		{


			var datos_importacion = dojo.formToObject('frm_xls_import');
			
			var datos_config      =  dojo.formToObject('form_config_importacion');

			for(x in datos_config )
			{
				datos_importacion[x] = datos_config[x];
			}

			importacionxls._M.importar_trabajadores.send(datos_importacion);
		
		}

	},


	importar : function(){


			var datos_importacion = dojo.formToObject('frm_xls_import');
			
			var datos_config      =  dojo.formToObject('form_config_importacion');

			for(x in datos_config )
			{
				datos_importacion[x] = datos_config[x];
			}

			datos_importacion['pi'] =  importacionxls.indices_importacion.actual;

		//console.log(importacionxls.indices_importacion.actual);

			importacionxls._M.importar.send(datos_importacion);

	},

	on_end_upload_file_xls : function(data){
	     
	     // 

	      var datos = dojo.formToObject('form_config_importacion');
 	      datos.view = data.data.key;
 
	      if(data.exito == '1')
	      {

	      	  if(datos.importar_trabajadores == '1')
	      	  {
	      	  	 importacionxls._M.explorar_archivo_trabajadores.send(datos);
	      	  }
	      	  else
	      	  {

		          importacionxls._M.explorar_archivo.send(datos);	      	  	
	      	  }

	          dojo.byId('hdxls_panel_currentview').value = datos.view;

	      }
	      else
	      {
 				alert(data.mensaje);
	      }

  	}	 



}