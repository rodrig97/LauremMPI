var Exporter = {

	    Cache : {


	    },			

		_M : {

			  generar : new Request({
	              
				              type :  'json',				              
				              method: 'post',
				              url : 'exportar/generar_archivo',

				              timeout : 240000,
				              
				              onRequest : function()
				              {
				                  app.loader_show(); 
				              },
				              
				              onSuccess  : function(data)
				              {
				                 
				              	 app.loader_hide(); 

				              	 if(data.result == '1')
				              	 {   	
 
					                 var codigo = data.file;
					                 window.open('docsmpi/exportar/'+codigo);
					             }
					             else
					             {
					             	 app.alert( (data.mensaje || 'Algo no salio bien durante la generación del archivo de exportación'));
					             }	
				                    
				              },
				              
				              onFailure : function()
				              {
				              	 app.loader_hide();     
				              } 
	              
	          }),
	
			  
	  		  data_certificados : new Request({
	                
	  			              type :  'json',				              
	  			              method: 'post',
	  			              url : 'exportar/data_certificados',
	  			              
	  			              onRequest : function()
	  			              {
	  			                  app.loader_show(); 
	  			              },
	  			              
	  			              onSuccess  : function(data)
	  			              {
	  			                 
	  			              	 app.loader_hide(); 

	  			              	 if(data.result == '1')
	  			              	 {   	
	  
	  				                 var codigo = data.file;
	  				                 window.open('docsmpi/exportar/'+codigo);
	  				             }
	  				             else
	  				             {
	  				             	 app.alert( (data.mensaje || 'Algo no salio bien durante la generación del archivo de exportación'));
	  				             }	
	  			                    
	  			              },
	  			              
	  			              onFailure : function()
	  			              {
	  			              	 app.loader_hide();     
	  			              } 
	                
	            }),

			  generar_pdt : new Request({
		          
				              type :  'json',				              
				              method: 'post',
				              url : 'exportar/sunat_pdt',

				              timeout : 600000,
				              
				              onRequest : function()
				              {
				                  app.loader_show(); 
				              },
				              
				              onSuccess  : function(data)
				              {
				                 
				              	 app.loader_hide(); 

				              	 if(data.result == '1')
				              	 {   	

					                 var codigo = data.file;
					                 window.open('docsmpi/exportar/'+codigo);
					             }
					             else
					             {
					             	 app.alert( (data.mensaje || 'Algo no salio bien durante la generación del archivo de exportación'));
					             }	
				                    
				              },
				              
				              onFailure : function()
				              {
				              	 app.loader_hide();     
				              } 
		          
		      }),

      		  exportar_data : new Request({
                    
      			              type :  'json',				              
      			              method: 'post',
      			              url : 'exportar/exportar_data',

      			              timeout : 600000,
      			              
      			              onRequest : function()
      			              {
      			                  app.loader_show(); 
      			              },
      			              
      			              onSuccess  : function(data)
      			              {
      			                 
      			              	 app.loader_hide(); 

      			              	 if(data.result == '1')
      			              	 {   	
      
      				                 var codigo = data.file;
      				                 window.open('docsmpi/exportar/'+codigo);
      				             }
      				             else
      				             {
      				             	 app.alert( (data.mensaje || 'Algo no salio bien durante la generación del archivo de exportación'));
      				             }	
      			                    
      			              },
      			              
      			              onFailure : function()
      			              {
      			              	 app.loader_hide();     
      			              } 
                    
                })  
		
		},

		_V : {

			  visualizar : new Request({
	              
				              type :  'text',
				              
				              method: 'post',
				              
				              url : 'exportar/view',
				              
				              onRequest : function()
				              {
				                    dijit.byId('planilla_reporter_view').set('content','<div class="dv_cargando">Cargando..</div>');
				              },
				              
				              onSuccess  : function(responseText)
				              {
				                   
				                   dijit.byId('planilla_reporter_view').set('content', responseText);


				                   if(dojo.byId('table_reportes_preview') != null)
				                   { 

						                   var myST = new superTable("table_reportes_preview", {
						                                  cssSkin : "sDefault",
						                                  headerRows : 1,
						                                  fixedCols : 0,
						                                  colWidths : [],
						                                  onStart : function()
						                                  {
						                                 
						                                  },
						                                  
						                                  onFinish : function()
						                                  {
						                                    
						                                  }
						                          
						                              });
						           }
				                    
				              },
				              
				              onFailure : function(){
				                  
				              } 
	              
	          }), 


		    
		       exportar_excel : new Laugo.View.Window({
		            
		             connect : 'exportar/exportador_excel',
		              
		              style : {
		                   width :  '700px',
		                   height:  '450px',
		                   'background-color'  : '#FFFFFF'
		              },
		              
		              title: ' Exportar datos en excel ',
		              
		              onLoad: function(){
		                   

		                   dojo.connect( dijit.byId('selexportacion_excel'), 'onChange', function(evt){

		                      	 var opt = dijit.byId('selexportacion_excel').get('value');

		                      	 console.log(opt);

		                      	 var dv_c1 = document.getElementById('dvimpxls_configuracion');

		                      	 var dv_c2 = document.getElementById('dvimpxls_individuo');
 								
		                      	 var dv_c3 = document.getElementById('dvimpxls_individuo_extra');

 								 if(opt == '1')
 								 {	
 								 	// dv_c2.classList.remove('lr_hide');
 								 	// dv_c2.classList.add('lr_show'); 
 
 								 	// dv_c1.classList.remove('lr_show');
 								 	// dv_c1.classList.add('lr_hide');

 								 	dojo.removeClass( dv_c2, 'lr_hide');
 								 	dojo.addClass( dv_c2, 'lr_show');

 								 	dojo.removeClass( dv_c1, 'lr_show');
 								 	dojo.addClass( dv_c1, 'lr_hide');

 								 	dojo.removeClass( dv_c3, 'lr_hide');
 								 	dojo.addClass( dv_c3, 'lr_show');
 								 }
 								 else if( opt == '2' ||  opt == '3' ||  opt == '6')
 								 {
 								 	// dv_c2.classList.remove('lr_hide');
 								 	// dv_c2.classList.add('lr_show'); 

 								 	// dv_c1.classList.remove('lr_hide');
 								 	// dv_c1.classList.add('lr_show');
 								 	dojo.removeClass( dv_c2, 'lr_hide');
 								 	dojo.addClass( dv_c2, 'lr_show');
 								 	
 								 	dojo.removeClass( dv_c1, 'lr_hide');
 								 	dojo.addClass( dv_c1, 'lr_show');

 								 	dojo.removeClass( dv_c3, 'lr_show');
 								 	dojo.addClass( dv_c3, 'lr_hide');
 								 }
 								 else if( opt == '4' ||  opt == '5' )
 								 {
 								 	// dv_c2.classList.remove('lr_show');
 								 	// dv_c2.classList.add('lr_hide'); 

 								 	// dv_c1.classList.remove('lr_hide');
 								 	// dv_c1.classList.add('lr_show');
 								 	dojo.removeClass( dv_c2, 'lr_show');
 								 	dojo.addClass( dv_c2, 'lr_hide');
 								 	
 								 	dojo.removeClass( dv_c1, 'lr_hide');
 								 	dojo.addClass( dv_c1, 'lr_show');

 								 	dojo.removeClass( dv_c3, 'lr_show');
 								 	dojo.addClass( dv_c3, 'lr_hide');

 								 }
 								 else
 								 { 

 								 }

		                       	
		                   });	


							dijit.byId('selexportacion_excel').onChange();
     
		 					
		              },
		              
		              onClose: function(){
		                  
		              //    alert('ventana cerrada');
		                   return true;
		              }
		        })  

 
		},

		Ui: {	

			  btn_preview :  function(btn,evt)
			  {

			  		var data = Exporter.get_data(btn); 
			  		Exporter._V.visualizar.send(data);
 				
			  },

			  btn_generar : function(btn,evt)
			  {
			  	    var data = Exporter.get_data(btn); 
			  	    var nodo = btn.domNode.parentNode; 
 							
			  	    data.generar = dojo.query('.modoreporte', nodo )[0].value;
 
			  		Exporter._M.generar.send(data);

			  },

			  btn_generar_pdt : function(btn,evt)
			  {
			  	    var data = Exporter.get_data(btn); 
			  	    var nodo = btn.domNode.parentNode; 
			  			
			  	    data.generar = dojo.query('.modoreporte', nodo )[0].value;
			  
			  		Exporter._M.generar_pdt.send(data);

			  },

			  btn_exportarexcel : function(btn,evt)
			  {


			  		var data  = dojo.formToObject('frm_exportxls_configuracion');
 					
 					if(confirm('Realmente desea generar el archivo de datos ? '))
 					{
 						Exporter._M.exportar_data.send(data);
 					}
 					
			  }	
 
		},


		get_data : function(btn){
 
		  		var modo = dojo.query('.modoreporte', btn.domNode.parentNode )[0].value;

		  	 
		  		var data  = dojo.formToObject('form_reportes_planilla_filtro');
		 
		  		var	data_report = dojo.formToObject( 'form_reporte_'+ modo );
		  	 
		  		data.modo = modo;
		  		
		  		var planillas = '';
		  		var selection = Planillas.Ui.Grids.planillas_reporte_filtro.selection;
		  		
		   		
		  		for(var i in selection )
		  		{
		  			  if(selection[i] === true)
		  			  {	
				    	  planillas +='_'+ i;
					  }
		  		}

		  		/*
		  		if(planillas == '')
		  		{
						alert('Debe seleccionar una planilla ');

						return 0;
		  		} 
		  		*/

		  		if(data_report.tiporeporte == '')
		  		{
		  			alert('El reporte no es valido');
		  			return 0;
		  		}

		  		for(var x in data_report )
		  		{
		  			data[x] = data_report[x];
		  		}

		  		data.planillas = planillas;

	  		 	return data;
		}


}