

var Biometrico = {


	_V : {



		biometricos : new Laugo.View.Window({
		         
	          connect : 'asistenciabiometrico/biometricos',
	          
	          style : {
	              width :  '400px',
	               height:  '300px',
	               'background-color' : '#FFFFFF'
	          },
	          
	          title : ' Especificar biometrico',
	          
	          onLoad : function(){


	          		/*var calendars = ['cal_bio_desde','cal_bio_hasta'];
	          	    var fecha = $_currentDate();
	          		
	          		dojo.forEach(calendars, function(cal,ind){dijit.byId(cal).set('value',  fecha   );});*/

	          }
		         
		}), 


		biometrico_panel : new Laugo.View.Window({
		         
	          connect : 'asistenciabiometrico/biometrico_panel',
	          
	          style : {
	              width :  '1000px',
	               height:  '550px',
	               'background-color' : '#FFFFFF'
	          },
	          
	          title : ' Especificar biometrico',
	          
	          onLoad : function(){

 					 

	          }
		         
		}) 


	},

	_M : {


		explorar_archivo : new Request({
		    
		        type: 'json',

		        method: 'post',
		        
		        url : 'asistenciabiometrico/explorar',
		        
		        onRequest : function()
		        {
		             app.loader_show(); 
		        },
		        
		        onSuccess  : function(data)
		        {
		            
		           dijit.byId('biometricopanel_viewfile').set('content', data.html_table);
   			

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

		           dijit.byId('biometricopanel_result').set('content', data.html_result);
  
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
				        
				        url : 'asistenciabiometrico/cargar_datos',
				        
				        onRequest : function()
				        {
				             app.loader_show(); 
				        },
				        
				        onSuccess  : function(data)
				        {
				      
				           app.loader_hide(); 
  
				           if(data.result == '1')
				           {
						   	 	 Biometrico._V.biometrico_panel.close();
				           }
				           else
				           {		
				           		importacionxls._M.explorar_archivo.reload();
				           }

				           app.alert(data.html_result);
				        	   
				        },
				        
				        onFailure : function(){

				            app.loader_hide();    
				            alert('Algo no salio bien.');
				            
				        } 
				        
				 })


	},


	Ui : {



		btn_cargar_panel_biometrico : function(btn,evt)
		{
 
		     var data =  dojo.formToObject('form_biometrico_cargardatos');

		     app.view_load('',{'view' : 'asistenciabiometrico/biometrico_panel',
		                       'data' : data,
		                       'fn' : function(){
		                               

		                               var dims = app.get_dims_body_app(),
		                                   desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
		                                
		                                dijit.byId('biometricopanel_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
		                                 

		                        }

		                       });


		     Biometrico._V.biometricos.close();

		},

		btn_importar_click : function(btn,evt)
		{
 		 	
			var data = dojo.formToObject('frm_xls_biometrico_import');

			Biometrico._M.importar.send(data);
		}

		

	},


	on_end_upload_file_xls : function(data)
	{
	
	    var datos = dojo.formToObject('form_config_importacion');
	      datos.view = data.data.key;
	
	    if(data.exito == '1')
	    { 
	      
 			datos.biometrico = dojo.byId('hdbiometrico_view_current').value;


	        Biometrico._M.explorar_archivo.send(datos);   

	        dojo.byId('hdxls_panel_currentview').value = datos.view;

	    }
	    else
	    {
	      alert(data.mensaje);
	    }
	}                


}