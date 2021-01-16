
var Trabajadores =  {

    Cache  : {
         trabajador_gestion_datos : null

    },

	 _M : { 


           vincular_concepto: new Laugo.Model({
                  
                 connect: 'trabajadores/vincular_concepto'
           
           }),


           desvincular_concepto: new Laugo.Model({
                 
                 connect: 'trabajadores/desvincular_concepto'
           
           }),
            
             
           guardar_valorvariable : new Laugo.Model({

                connect : 'trabajadores/guardarvariable',
              
                message_function: function(mensaje, data){
                    
                }

           }),


           actualizar_presupuestal : new Laugo.Model({

                 connect : 'trabajadores/actualizar_presupuestal' 
           }),


           actualizar_info: new Laugo.Model({
                  
                 connect: 'trabajadores/actualizar_info_ar'
           
           }),


           actualizar_concepto: new Laugo.Model({
                  
                 connect: 'conceptos/gestion_rapida',
              
                message_function: function(mensaje, data){
                    
                }
           
           }),
 
          
           get_ff_tarea : new Request({
              
              type :  'json',
              
              method: 'post',
              
              url : 'tareas/get_fuentes',
              
              onRequest : function(){
            
                   app.loader_show(); 
            
              },
              
              onSuccess  : function(responseJSON){
                  
                   app.loader_hide(); 
              
                   dojo.forEach( dijit.byId('selgd_selfuente').getOptions(), function(opt,it){

                        dijit.byId('selgd_selfuente').removeOption(opt);
                   });

                   dojo.forEach( responseJSON, function(newOption, it){
 
                        dijit.byId('selgd_selfuente').addOption(  {label:  newOption.fuente_nombre, value: newOption.fuente_codigo, disabled : false} );

                   });
                     
              },
              
              onFailure : function(){
                  
              } 
              
          })

	 },

	 _V : { 

 	    gestionar_datos : new Request({
              
	              type :  'text',
	              
	              method: 'post',
	              
	              url : 'trabajadores/gestionar_datos/trabajador',
	              
	              onRequest : function(){
	                    dijit.byId('dv_vipla_detalle').set('content','<div class="dv_cargando">Cargando..</div>');
	              },
	              
	              onSuccess  : function(responseText){

	                   dijit.byId('dv_vipla_detalle').set('content',responseText);
	                   

                    dojo.forEach(dojo.query('.td_conceptoempleado_retencion'), function(el,ind){

                          dojo.connect(el, 'onclick', function(evt){
                               
                               var data = {}
                              
                               data.detalle =  dojo.query('.hdempconkey', el.parentNode)[0].value;

                               Conceptos.vincular_beneficiario(data);
                                      
                          }); 

                    });
  


                     dojo.forEach(dojo.query('.txtpladet_vari'), function(box,ind){
                             
                             var btn_save = dojo.query('.btnpladet_savevar', box.parentNode.parentNode)[0];
       
                              

                              dojo.connect(box, 'onkeydown', function(evt){
                                 
                                 
                                   if( (evt.keyCode >= 37 && evt.keyCode <= 40) || evt.keyCode  == 46 || evt.keyCode == 190 || evt.keyCode == 110 || (evt.keyCode>=48 && evt.keyCode <= 57) || (evt.keyCode>=96 && evt.keyCode <= 105) || evt.keyCode == 8 || evt.keyCode == 9){

                                   }
                                    else{
                                        evt.preventDefault();
                                    }
                                   
                              //    console.log('escribieron: ' +  dijit.byNode(box).get('value') ); 
                             });

                             dojo.connect(box, 'onkeyup', function(evt){
                                 
                                  var hd_valor_ini =   dojo.query('.hdpladet_varvd', box.parentNode)[0].value;
                                 
                                  if(parseFloat(dijit.byNode(box).get('value'))  != parseFloat(hd_valor_ini)){
                                       dijit.byNode(btn_save).set('disabled', false);
                                  }
                                  else{
                                        dijit.byNode(btn_save).set('disabled', true);
                                  }
                              //    console.log('escribieron: ' +  dijit.byNode(box).get('value') ); 
                             });


                             dojo.connect(box, 'onkeypress', function(evt){
                                 
                                 //  console.log(evt.charOrCode);
                                    if(evt.charOrCode == dojo.keys.ENTER){
                                      
                                       dijit.byNode(btn_save).onClick();
                                    }
                                    else  if(evt.charOrCode == dojo.keys.ESCAPE){
 
                                         var hd_valor_ini =   dojo.query('.hdpladet_varvd', box.parentNode)[0].value;
                                         this.value = hd_valor_ini;
                                         dijit.byNode(this).set('value',hd_valor_ini);
                                    }
                              //    console.log('escribieron: ' +  dijit.byNode(box).get('value') ); 
                             });




                           
                       });  

  
                      dojo.connect( dijit.byId('selgd_tarea'), 'onChange', function(evt){
                           
                            var codigo = dijit.byId('selgd_tarea').get('value');
                          //  console.log('tarea: '+codigo);
                            Trabajadores._M.get_ff_tarea.send({'view' : codigo});
                          
                      });

 
                      dojo.forEach(dojo.query('.spVariable_personalizar'), function(el,ind){


                          dojo.connect( el, 'onclick', function(evt){
                            

  //                               Trabajadores._V.calendarizar_variable.load({});
                         
                          
                        });


                      });
                     
                  
 
                  //    dojo.connect( dijit.byId('sp_test_dg1') );
	                   
	              },
	              
	              onFailure : function(){
	                  
	              } 
	              
	   }),



     gestionar_datos_rapida : new Request({
              
                type :  'text',
                
                method: 'post',
                
                url : 'trabajadores/gestionar_datos/directo',
                
                onRequest : function(){
                      dijit.byId('dv_vipla_detalle').set('content','<div class="dv_cargando">Cargando..</div>');
                },
                
                onSuccess  : function(responseText){

                     dijit.byId('dv_vipla_detalle').set('content',responseText);
                     
 
                            if(dojo.byId('fip_hasessalud_ar')!= null){

                                 dojo.connect(  dijit.byId('fip_hasessalud_ar'),'onChange',function(e){
                              
                                         var parent = dijit.byId('fip_hasessalud_ar').domNode.parentNode;

                                         if(dijit.byId('fip_hasessalud_ar').get('value')=='1'){

                                              dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');
   
                                         }else{

                                             dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                         }
                                }); 

                                dijit.byId('fip_hasessalud_ar').onChange(); 
                            }

                           
                           if(dojo.byId('fip_hascbanco_ar')!= null){  

                                dojo.connect(  dijit.byId('fip_hascbanco_ar'),'onChange',function(e){

                                         var parent = dijit.byId('fip_hascbanco_ar').domNode.parentNode;
                                      
                                             if(dijit.byId('fip_hascbanco_ar').get('value')=='1'){

                                                  dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');
                                       
                                             }else{

                                                 dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                             }
                                }); 

                                dijit.byId('fip_hascbanco_ar').onChange();     
                           }

 
                       /*   if(dojo.byId('fip_haspension_ar')!= null){  

                                 dojo.connect(  dijit.byId('fip_haspension_ar'),'onChange',function(e){
                              
                                         var parent = dijit.byId('fip_haspension_ar').domNode.parentNode;
                              
                                         if(dijit.byId('fip_haspension_ar').get('value')=='1'){

                                              dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');
                                              
                                         }else{

                                             dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                         }
                                }); 

                                dijit.byId('fip_haspension_ar').onChange();  
                          }*/


                          if(dojo.byId('fip_tipopension_ar')!= null){  

                                dojo.connect(  dijit.byId('fip_tipopension_ar'),'onChange',function(e){
                              
                                         var parent = dijit.byId('fip_tipopension_ar').domNode.parentNode;
                              
                                         if(dijit.byId('fip_tipopension_ar').get('value')=='2'){

                                              dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'block');
                              
                                         }else{

                                             dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                         }
                                }); 

                                dijit.byId('fip_tipopension_ar').onChange();  

                          }


                          if(dijit.byId('fip_grupo') != null)
                          {
                             var g =  dojo.byId('hd_grupo_id').value;
                             dijit.byId('fip_grupo').set('value', g);
                          }


                          
                          if(dijit.byId('fip_ocupacion') != null)
                          {
                             var g =  dojo.byId('hd_ocupacion_id').value;
                             dijit.byId('fip_ocupacion').set('value', g);
                          }
                        


                         if(dojo.byId('selsuspension_presento')!= null){

                              dojo.connect(  dijit.byId('selsuspension_presento'),'onChange',function(e){
                            
                                      if(dijit.byId('selsuspension_presento').get('value')=='1'){

                                           dojo.setStyle( dojo.byId('div_suspension_fecha'),'display', 'inline');
                         
                                      }else{
                                          
                                           dojo.setStyle( dojo.byId('div_suspension_fecha'),'display', 'none');

                                           var fecha_d_susp = $_currentDate();
                                           dijit.byId('calSuspension_fecha').set('value',  fecha_d_susp   );
                                      }
                             }); 

                             dijit.byId('selsuspension_presento').onChange(); 
  
                             var fecha_registrada = dojo.byId('hdSuspension_fecha_registrada').value; 
                          
                             if (fecha_registrada != '' && fecha_registrada != '//' && fecha_registrada != null) {
                               
                               var parts= fecha_registrada.split('/');
                               fecha_d_susp = new Date(parts[2], (parseInt(parts[1])-1), parseInt(parts[0]), 0,0,0);  
                               dijit.byId('calSuspension_fecha').set('value',  fecha_d_susp);  

                             }
  
                         }

                         dojo.forEach(dojo.query('.chdirecto_checks'), function(node,ind){
                             
                             var node_checkbox = dijit.byNode(node);
                       
                             dojo.connect(node_checkbox, 'onChange', function(evt){
                                  
                                
                                  var est =  (node_checkbox.get('value') ? '1' : '0'),
                                      conc = dojo.query('.hddirecto_concepto', node.parentNode)[0].value,
                                      emp  = dojo.query('.hddiecto_empleado', node.parentNode)[0].value;



                                  node_checkbox.set('disabled', true);

                                    
                                  if(Trabajadores._M.actualizar_concepto.process({'trabajador' : emp,
                                                                                  'concepto'   : conc,
                                                                                  'estado'     : est}))
                                  {
                                     

                                  }  
                                  
                                  node_checkbox.set('disabled', false);
                                  
                                  
                             });  
                             
         
                        });
                       

  
                     
                },
                
                onFailure : function(){
                    
                } 
                
     }),




        add_concepto_trabajador : new Laugo.View.Window({
            
             connect : 'trabajadores/add_concepto',
              
              style : {
                   width :  '700px',
                   height:  '420px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Vincular concepto remunerativo al trabajador ',
              
              onLoad: function(){
                        
                        console.log('Aqui');

                        app.loader_show();

                        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                    function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){
            
                                     app.loader_hide();             

                                     if(dojo.byId('dv_planillaempleado_addconcepto') != null )
                                     { 

                                         if(window.escalafon_grid  === null || window.escalafon_grid  === undefined)  window.escalafon_grid  = (declare([Grid, Selection,Keyboard]));


                                                               var store = Observable(Cache(JsonRest({
                                                                        target:"conceptos/provide/main", 
                                                                        idProperty: "id",
                                                                        sortParam: 'oby',
                                                                        query: function(query, options){

                                                                              var data = dojo.formToObject('frm_searchconceptos_adddetalle');
                                                                              for(x in data) query[x] = data[x];

                                                                              return JsonRest.prototype.query.call(this, query, options);
                                                                        }
                                                                }), Memory()));

                                                                  var colums = { // you can declare columns as an object hash (key translates to field)
                                                                           // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                                            col1: {label:'#', sortable: true},
                                                                            col2: {label: 'Nombre', sortable: false},
                                                                            col3: {label: 'Tipo', sortable: false},
                                                                            col4: {label: 'Aplicable a', sortable: false},
                                                                            col5: {label: 'Grupo', sortable: false},
                                                                            col6: {label: 'Descripcion', sortable: false} 
                                                                     
                                                                    };



                                                                 Trabajadores.Ui.Grids.add_conceptos  = new  window.escalafon_grid({

                                                                        store: store,
                                                                        getBeforePut: false,
                                                                        columns: colums 


                                                                }, "dv_planillaempleado_addconcepto");

                                                                 console.log(Trabajadores.Ui.Grids.add_conceptos);

                                                   if( Trabajadores.Ui.Grids.add_conceptos != null){
                                                    //Persona.Ui.Grids.comisiones.store.view_persona('6');
                                                         Trabajadores.Ui.Grids.add_conceptos.refresh();
                                                          // Persona.Ui.Grids.trabajadores.store.query({});
                                                          // Persona.Ui.Grids.trabajadores.cleanup();

                                                    }          
                                      }



                        });


 
 
 
 
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),



        calendarizar_variable   : new Laugo.View.Window({
            
             connect : 'trabajadores/calendarizar_variable',
              
              style : {
                   width :  '720px',
                   height:  '200px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Calendario de la variable ',
              
              onLoad: function(){
                  
 
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        })

	 },

	 Ui : {


	 	  Grids : {


	 	  		add_conceptos  : null
	 	  },

   		  btn_showaddconcepto_click : function(btn,evt){
             
             var ep_k =  dojo.query('.hdptrabajador_empkey' , btn.domNode.parentNode)[0].value;
            Trabajadores._V.add_concepto_trabajador.load({'trabajador' :  ep_k });
             
         },


         btn_addconcepto_click: function(btn,evt){

             var codigo = '';      
                    
            for(var i in Trabajadores.Ui.Grids.add_conceptos.selection){
                  codigo = i;
            }
    
            if(codigo != '')      
            {
                var empkey =  dojo.query('.hdaddconc_key', btn.parentNode)[0].value; 


                if(Trabajadores._M.vincular_concepto.process({'trabajador' : empkey, 'concepto' : codigo}) ){

                   
                      Trabajadores._V.gestionar_datos.send({'trabajador' : Trabajadores.Cache.trabajador_gestion_datos});
                }
 

            }
            else{
                alert('Debe seleccionar un registro');
            }

         },


         btn_save_valorvariable : function ( btn,evt){


            var parent = btn.domNode.parentNode;

            var data = {}

            data.vari = dojo.query('.hdvari_key', parent)[0].value; 
            data.tra = dojo.query('.hdtra_key', parent)[0].value; 

            data.valor = dijit.byNode(dojo.query('.txtpladet_vari', parent.parentNode)[0]).get('value');
            //console.log('Click me');

            if(data.valor != ''){ 

              if(Trabajadores._M.guardar_valorvariable.process(data)){
                    //console.log('here');
                    //console.log( dijit.byNode( dojo.query('.btnpladet_savevar',parent)[0] ) );

                    dijit.byNode( dojo.query('.btnpladet_savevar',parent)[0] ).set('disabled',true);
                    //console.log(dojo.query('.hdpladet_varvd',parent.parentNode));

                    dojo.query('.hdpladet_varvd',parent.parentNode)[0].value= Trabajadores._M.guardar_valorvariable.data.valor;
              }

            }

         },


         btn_desvincularconcepto_click :  function (btn,evt){

              var parent = btn.domNode.parentNode;
              var eck =   dojo.query('.hdgdt_conckey', parent)[0].value; 

             // console.log('Empconk: '+ empconc_key); 

              if(Trabajadores._M.desvincular_concepto.process({'eck' : eck})){

                  Trabajadores._V.gestionar_datos.send({'trabajador' : Trabajadores.Cache.trabajador_gestion_datos});
              } 


         },


         btn_actualizar_presupuestal_click : function(btn,evt){

              var tarea_id = dijit.byId('selgd_tarea').get('value'),
                  fuente_id  = dijit.byId('selgd_selfuente').get('value'),
                  anio  = dijit.byId('selgd_anio').get('value'),
                  tra =   dojo.query('.hdtra_key', btn.domNode.parentNode)[0].value;

              // console.log('Tarea: '+tarea_id+' Fuente_id: '+fuente_id);
               

               if( Trabajadores._M.actualizar_presupuestal.process({'tra' :  tra, 'tarea' : tarea_id, 'fuente' : fuente_id, 'anio' : anio}) ){

                  Trabajadores._V.gestionar_datos.send({'trabajador' : Trabajadores.Cache.trabajador_gestion_datos});
               }


         },



         btn_ver_presupuesto_click : function(btn,evt){

                 var data = {}

                  data.tarea = dijit.byId('selgd_tarea').get('value');
                  data.fuente  = dijit.byId('selgd_selfuente').get('value');
                  data.anio  = dijit.byId('selgd_anio').get('value');
                  data.tra =   dojo.query('.hdtra_key', btn.domNode.parentNode)[0].value;

                
                Tareas._V.visualizar_presupuesto.load(data);
               
                 

         }

	  } 

}


/* 


		Presentation  / window / panel 


 	 Presentation.bind(panel);

 	 Presentation. 

	new Laugo.
*/