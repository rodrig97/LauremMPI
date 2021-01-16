/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


 

var Conceptos =  {
    
    Cache:  {
      
        id_view_info : null
      
    },
    
    _M : {
          
         store_conceptos_variables : null,
         store_conceptos_descuentos : null,
               
          get_view : new Request({
              
              type : 'text',
              
              method : 'post',
              
              url : 'conceptos/view',
              
              onRequest: function(){
                  
                  dijit.byId('dv_concepto_panelizq').set('content','<div class="sp12b"> Cargando.. </div>');
                  
              },
              
              onSuccess : function (responseText) {
                  dijit.byId('dv_concepto_panelizq').set('content',responseText);
                  
              },
              
              onFailure : function (){
                  
              }
             
         }),
         
         get_preview : new Request({
            
             method : 'post',
              
            
             url: 'conceptos/preview',
             // <editorfold defaultstate="collapsed" desc="Metodo Main">
             onRequest: function(){
                 
                 
             },
             // </editorfold>
             onSuccess: function(responseText){
                  
                  alert(responseText);
                  
             },
             
             onFailure: function(){
                 
             }
              
         }),
         
         registrar : new Laugo.Model({
              connect : 'conceptos/guardar'
         }),
         
         
         actualizar : new Laugo.Model({
              connect : 'conceptos/actualizar'
         }),


         eliminar : new Laugo.Model({
              connect : 'conceptos/delete'
         }),
          
         
         guardar_detalleconcepto : new Laugo.Model({
             
              connect : 'conceptos/guardar_detalleplanilla',
              
              message_function: function(mensaje, data){
                  
              }
            
         }),
         
         
         agregar_detalleempleado : new Laugo.Model({
             
             connect : 'conceptos/vincular_a_detalle'
             
         }),

         vincular_beneficiario : new Laugo.Model({

             connect : 'conceptos/vincular_a_beneficiario'

         }),

         actualizar_valor_forall : new Laugo.Model({

             connect : 'conceptos/actualizar_detalle_planilla' 
         }),


         ajustar_min_max : new Laugo.Model({

             connect : 'conceptos/actualizar_ajuste_minmax' 
         }),

         quitar_min_max : new Laugo.Model({

             connect : 'conceptos/quitar_ajuste_minmax' 
         })
         
          
    }, 
     
    _V: {
        
       
        
        nuevo_concepto : new Laugo.View.Window({
            
             connect : 'conceptos/nuevo_concepto',
              
              style : {
                   width :  '900px',
                   height:  '550px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Registrar nuevo Concepto ',
              
              onLoad: function(){
                  


                    var operator_lines = dojo.query('.concepto_line', dojo.byId('dv_operator_factory'))[0]

                    var sels =  dojo.query('.concepto_operador', operator_lines);   

                    var sel_m =sels[0],
                        sel_d = sels[1];
                        
                
                       /* 
                        window.stateStore = new dojo.store.Memory({
                             data: [
                                {name:"Concepto 1", id:"1"},
                                {name:"Concepto 2", id:"2"},
                                {name:"variable 3 ", id:"3"},
                                {name:"variable 4", id:"4"} 

                            ]
                        });*/
                       
                       dojo.connect(dijit.byId('sel_newconc_tipopla'), 'onChange', function(e){
                        
                             Conceptos._M.store_conceptos_variables.query({});
                       });
                       
 

                     var n_line =  dojo.create('div', {

                              innerHTML  : '',
                              className :  "concepto_line"

                         }); 

                     dojo.place(n_line, dojo.byId('dv_operation_base') , 'after');

                      Conceptos.fn_add_line(n_line);


                      dojo.connect(dojo.byId('delete_last_operation'),'click', function(){
                            
                             var partes_ops= dojo.query('.line_parte', dojo.byId('dv_operator_factory'));
                             
                             if((partes_ops.length > 1) && (  partes_ops[partes_ops.length -1 ] != null ) ){
                                 
                                 
                            if(partes_ops[partes_ops.length-2] != null) dijit.byId( dojo.query('.hdidselectoperador', partes_ops[partes_ops.length-2] )[0].value ).set('value','¿');
                                dojo.destroy(partes_ops[partes_ops.length-1]);
                             } 
                          
                      });



                       dojo.connect(dijit.byId('selconcepto_afecto'), 'onChange', function(e){
                        
                             if(dijit.byId('selconcepto_afecto').get('value') == '1')
                             {
                                 
                                 dojo.setStyle(dojo.byId('tr_concepto_nuevo_tipo'),'display', 'table-row');
                                 dojo.setStyle(dojo.byId('tr_concepto_nuevo_sunat'),'display', 'table-row');

                                 dijit.byId('selconcepto_clasificador').set('value','0');
                                 dijit.byId('selconcepto_sunat').set('value','0');
                                 dijit.byId('selconceptogrupo').set('value','0');

                             }
                             else
                             {
                                dojo.setStyle(dojo.byId('tr_concepto_nuevo_tipo'),'display', 'none');
                                dojo.setStyle(dojo.byId('tr_concepto_nuevo_sunat'),'display', 'none');

                                dijit.byId('selconcepto_clasificador').set('value','0');
                                dijit.byId('selconcepto_sunat').set('value','0');
                                dijit.byId('selconceptogrupo').set('value','0');
                             }
                       });
                       


              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),
        
        
        modificar_concepto : new Laugo.View.Window({
            
             connect : 'conceptos/modificar_concepto',
              
             style : {
                   width :  '900px',
                   height:  '550px',
                   'background-color'  : '#FFFFFF'
             },
              
              title: ' Modificar Concepto Remunerativo ',
              
              onLoad: function(){
                  
                  
                   var operaciones_linea = dojo.query('.concepto_line', dojo.byId('dv_operator_factory'));
                   var operaciones_parte = dojo.query('.line_parte', dojo.byId('dv_operator_factory'));
                     
                   dojo.forEach( operaciones_parte, function(parte, ind ){
                        
                          var partes = dojo.query('.operator_comp', parte),
                              pa_1 = partes[0], 
                              pa_2 = partes[2],
                              pa_1_lbl =  dojo.query('label',pa_1)[0], 
                              pa_2_lbl = dojo.query('label',pa_2)[0],
                              ope = partes[3],
                              seloperador = dojo.query('.seloperador',ope)[0] ;
                          
                           // Evento para parentesis 
                          dojo.connect(pa_1_lbl, 'onclick', function(e){
                                   
                                  if(dojo.query('.hdestadoparentesis',pa_1)[0].value=='0') 
                                  { 
                                     dojo.removeClass( pa_1_lbl, 'operator_parentesis');
                                     dojo.addClass( pa_1_lbl, 'operator_parentesis_select');
                                     dojo.query('.hdestadoparentesis',pa_1)[0].value= '1';
                                  }
                                  else{ 
                                     dojo.removeClass( pa_1_lbl, 'operator_parentesis_select');
                                     dojo.addClass(  pa_1_lbl, 'operator_parentesis');
                                     dojo.query('.hdestadoparentesis',pa_1)[0].value= '0';
                                     
                                  }
                            
                          });
                          
                           dojo.connect(pa_2_lbl, 'onclick', function(e){
                                   
                                  if(dojo.query('.hdestadoparentesis',pa_2)[0].value=='0') 
                                  { 
                                     dojo.removeClass( pa_2_lbl, 'operator_parentesis');
                                     dojo.addClass( pa_2_lbl, 'operator_parentesis_select');
                                     dojo.query('.hdestadoparentesis',pa_2)[0].value= '1';
                                  }
                                  else{ 
                                     dojo.removeClass( pa_2_lbl, 'operator_parentesis_select');
                                     dojo.addClass(  pa_2_lbl, 'operator_parentesis');
                                     dojo.query('.hdestadoparentesis',pa_2)[0].value= '0';
                                     
                                  }
                            
                          });
                        
                         
                          dojo.connect(dijit.byNode(seloperador),'onChange',function(evt){
                                
                                 var partes_ops         = dojo.query('.line_parte', dojo.byId('dv_operator_factory'));
                                 var lineas_operaciones = dojo.query('.concepto_line', dojo.byId('dv_operator_factory'));
                                  
                                 if( partes_ops[partes_ops.length - 1 ].id == parte.id && ( this.get('value') != '¿' )  ){ // Si el ID del ultimo fragmento (parte operacion), es igual al ID del fragmento Y el combo tiene valor 0 
                                      
                                        console.log('Conforme yo agrego uno mas hay : '+ partes_ops.length+' ');
                                        
                                        if( (partes_ops.length % 2) == 1 ){ 
                                        
                                            console.log('Nueva parte ');
                                            Conceptos.fn_add_line(lineas_operaciones[ lineas_operaciones.length - 1]);
                                        
                                        }
                                        else{
                                        
                                            console.log('Nueva linea');
                                             
                                            var n_line =  dojo.create('div', {

                                                  innerHTML  : '',
                                                  className :  "concepto_line"

                                             },   lineas_operaciones[ lineas_operaciones.length - 1] , 'after'); // Creamos una nueva linea 
 
                                             Conceptos.fn_add_line(n_line);
                                        }                                     
                                 }
                                
                          });
                        
                   });
                   
                   var parentesis = dojo.query('.operator_comp', dojo.byId('dv_operator_factory'));
                  
                  
                  
                  
   
                   dojo.connect(dojo.byId('delete_last_operation'),'onclick', function(){
                            
                             var partes_ops= dojo.query('.line_parte', dojo.byId('dv_operator_factory'));
                             var lineas_operaciones = dojo.query('.concepto_line', dojo.byId('dv_operator_factory'));
                             
                             if((partes_ops.length > 1) && (  partes_ops[partes_ops.length -1 ] != null ) ){
                                  
                                //  console.log(dojo.query('.seloperador', partes_ops[partes_ops.length-2] )[0]);
                                 
                                if(partes_ops[partes_ops.length-2] != null){
                                    
                                 //    var node_sel =  dijit.byId( dojo.query('.hdidselectoperador', partes_ops[partes_ops.length-2] )[0].value );
                                    
                                    if(dojo.query('.hdidselectoperador', partes_ops[partes_ops.length-2] )[0] != null ){ 
                                        
                                        var set_node = dojo.query('.hdidselectoperador', partes_ops[partes_ops.length-2] )[0].value;
                                        /*
                                          console.log(' Select : ' +set_node);
                                          console.log(dijit.byId(set_node));
                                        */
                                        dijit.byId(set_node).set('value','¿');
                                    }
                                    //dijit.byNode( dojo.query('.seloperador', partes_ops[partes_ops.length-2] )[0] ).set('value','¿');
                                    
                                } 

                                dijit.registry.destroy_childrens( partes_ops[ partes_ops.length - 1] ); 
                                dojo.destroy(partes_ops[partes_ops.length-1]);
                                
                                if(dojo.query('.line_parte', dojo.byId('dv_operator_factory')).length % 2==0)
                                {
                                    // eliminar la ultima linea
                                    dijit.registry.destroy_childrens( lineas_operaciones[ lineas_operaciones.length - 1] ); 
                                    dojo.destroy(lineas_operaciones[ lineas_operaciones.length - 1]);
                                }
                                
                             } 
                          
                   });



                   dojo.connect(dijit.byId('selconcepto_afecto'), 'onChange', function(e)
                   {
                    
                         if(dijit.byId('selconcepto_afecto').get('value') == '1')
                         {
                             
                             dojo.setStyle(dojo.byId('tr_concepto_nuevo_tipo'),'display', 'table-row');
                             dojo.setStyle(dojo.byId('tr_concepto_nuevo_sunat'),'display', 'table-row');

                             dijit.byId('selconcepto_clasificador').set('value','0');
                             dijit.byId('selconcepto_sunat').set('value','0');
                             dijit.byId('selconceptogrupo').set('value','0');

                         }
                         else
                         {
                            dojo.setStyle(dojo.byId('tr_concepto_nuevo_tipo'),'display', 'none');
                            dojo.setStyle(dojo.byId('tr_concepto_nuevo_sunat'),'display', 'none');

                            dijit.byId('selconcepto_clasificador').set('value','0');
                            dijit.byId('selconcepto_sunat').set('value','0');
                            dijit.byId('selconceptogrupo').set('value','0');
                         }
                   });


                 // dijit.byId('selconcepto_afecto').onChange();
                  
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),
        
        
        condiciones : new Laugo.View.Window({
            
             connect : 'conceptos/condiciones',
              
             style : {
                   width :  '600px',
                   height:  '700px',
                   'background-color'  : '#FFFFFF'
             },
              
              title: ' Especificar condiciones ',
              
              onLoad: function(){
                  
                  
                   var operaciones_linea = dojo.query('.concepto_line', dojo.byId('dv_operator_factory'));
                   var operaciones_parte = dojo.query('.line_parte', dojo.byId('dv_operator_factory'));
                     
                   dojo.forEach( operaciones_parte, function(parte, ind ){
                        
                          var partes = dojo.query('.operator_comp', parte),
                              pa_1 = partes[0], 
                              pa_2 = partes[2],
                              pa_1_lbl =  dojo.query('label',pa_1)[0], 
                              pa_2_lbl = dojo.query('label',pa_2)[0],
                              ope = partes[3],
                              seloperador = dojo.query('.seloperador',ope)[0] ;
                          
                           // Evento para parentesis 
                          dojo.connect(pa_1_lbl, 'onclick', function(e){
                                   
                                  if(dojo.query('.hdestadoparentesis',pa_1)[0].value=='0') 
                                  { 
                                     dojo.removeClass( pa_1_lbl, 'operator_parentesis');
                                     dojo.addClass( pa_1_lbl, 'operator_parentesis_select');
                                     dojo.query('.hdestadoparentesis',pa_1)[0].value= '1';
                                  }
                                  else{ 
                                     dojo.removeClass( pa_1_lbl, 'operator_parentesis_select');
                                     dojo.addClass(  pa_1_lbl, 'operator_parentesis');
                                     dojo.query('.hdestadoparentesis',pa_1)[0].value= '0';
                                     
                                  }
                            
                          });
                          
                           dojo.connect(pa_2_lbl, 'onclick', function(e){
                                   
                                  if(dojo.query('.hdestadoparentesis',pa_2)[0].value=='0') 
                                  { 
                                     dojo.removeClass( pa_2_lbl, 'operator_parentesis');
                                     dojo.addClass( pa_2_lbl, 'operator_parentesis_select');
                                     dojo.query('.hdestadoparentesis',pa_2)[0].value= '1';
                                  }
                                  else{ 
                                     dojo.removeClass( pa_2_lbl, 'operator_parentesis_select');
                                     dojo.addClass(  pa_2_lbl, 'operator_parentesis');
                                     dojo.query('.hdestadoparentesis',pa_2)[0].value= '0';
                                     
                                  }
                            
                          });
                        
                         
                          dojo.connect(dijit.byNode(seloperador),'onChange',function(evt){
                                
                                 var partes_ops         = dojo.query('.line_parte', dojo.byId('dv_operator_factory'));
                                 var lineas_operaciones = dojo.query('.concepto_line', dojo.byId('dv_operator_factory'));
                                  
                                 if( partes_ops[partes_ops.length - 1 ].id == parte.id && ( this.get('value') != '¿' )  ){ // Si el ID del ultimo fragmento (parte operacion), es igual al ID del fragmento Y el combo tiene valor 0 
                                      
                                        console.log('Conforme yo agrego uno mas hay : '+ partes_ops.length+' ');
                                        
                                        if( (partes_ops.length % 2) == 1 ){ 
                                        
                                            console.log('Nueva parte ');
                                            Conceptos.fn_add_line(lineas_operaciones[ lineas_operaciones.length - 1]);
                                        
                                        }
                                        else{
                                        
                                            console.log('Nueva linea');
                                             
                                            var n_line =  dojo.create('div', {

                                                  innerHTML  : '',
                                                  className :  "concepto_line"

                                             },   lineas_operaciones[ lineas_operaciones.length - 1] , 'after'); // Creamos una nueva linea 
 
                                             Conceptos.fn_add_line(n_line);
                                        }                                     
                                 }
                                
                          });
                        
                   });
                   
                   var parentesis = dojo.query('.operator_comp', dojo.byId('dv_operator_factory'));
                   
                   dojo.connect(dojo.byId('delete_last_operation'),'onclick', function(){
                            
                             var partes_ops= dojo.query('.line_parte', dojo.byId('dv_operator_factory'));
                             var lineas_operaciones = dojo.query('.concepto_line', dojo.byId('dv_operator_factory'));
                             
                             if((partes_ops.length > 1) && (  partes_ops[partes_ops.length -1 ] != null ) ){
                                
                                if(partes_ops[partes_ops.length-2] != null){
                                    
                                 //    var node_sel =  dijit.byId( dojo.query('.hdidselectoperador', partes_ops[partes_ops.length-2] )[0].value );
                                    
                                    if(dojo.query('.hdidselectoperador', partes_ops[partes_ops.length-2] )[0] != null ){ 
                                        
                                        var set_node = dojo.query('.hdidselectoperador', partes_ops[partes_ops.length-2] )[0].value;
                                     
                                        dijit.byId(set_node).set('value','¿');
                                    }
                                 
                                } 

                                dijit.registry.destroy_childrens( partes_ops[ partes_ops.length - 1] ); 
                                dojo.destroy(partes_ops[partes_ops.length-1]);
                                
                                if(dojo.query('.line_parte', dojo.byId('dv_operator_factory')).length % 2==0)
                                {
                                    // eliminar la ultima linea
                                    dijit.registry.destroy_childrens( lineas_operaciones[ lineas_operaciones.length - 1] ); 
                                    dojo.destroy(lineas_operaciones[ lineas_operaciones.length - 1]);
                                }
                                
                             } 
                          
                   });
                  
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),
        
        add_concepto_trabajador : new Laugo.View.Window({
            
             connect : 'detalletrabajador/add_concepto_planilla',
              
              style : {
                   width :  '700px',
                   height:  '420px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Agregar concepto remunerativo al detalle de la planilla ',
              
              onLoad: function(){
                  

                        app.loader_show();

                        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                    function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){
                        app.loader_hide();             

                                     if(dojo.byId('dv_planillaempleado_addconcepto') != null ){ 

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
                                                                           /* col5: {label: 'Fecha Fin', sortable: false},
                                                                            col6: {label: 'Actual', sortable: false},
                                                                             col4: {label: 'Hasta', sortable: false},
                                                                            hingreso: {label: 'Hora Ingreso', sortable: false},
                                                                            col5: {label: 'Motivo', sortable: false}
                                                                           col6: {
                                                                                        label: "Step", 
                                                                                        sortable: false,
                                                                                        field: "_item",
                                                                                        formatter: testFormatter
                                                                                    },
                                                                            col7: 'Column 5' */
                                                                    };



                                                                 Conceptos.Ui.Grids.planilladetalle_buscar  = new  window.escalafon_grid({
                                                                        loadingMessage : 'Cargando',
                                                                        store: store,
                                                                        getBeforePut: false,
                                                                        columns: colums 


                                                                }, "dv_planillaempleado_addconcepto");

                                                       if( Conceptos.Ui.Grids.planilladetalle_buscar != null)
                                                       {
                                                   
                                                         Conceptos.Ui.Grids.planilladetalle_buscar.refresh();
                                                          
                                                       }          
                                      }



                        });


 
 
 
 
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),



        gestion_conceptosvariables_trabajador : new Laugo.View.Window({
            
             connect : 'trabajadores/',
              
              style : {
                   width :  '700px',
                   height:  '420px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: '  ',
              
              onLoad: function(){
                   
  
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),


        restringir_minimo_maximo : new Laugo.View.Window({
            
             connect : 'conceptos/configurar_restriccion_montos',
              
              style : {
                   width :  '450px',
                   height:  '330px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Configuración de ajuste de Monto Mínimo y Máximo',
              
              onLoad: function(){
                   
  
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),



        vincular_beneficiario : new Laugo.View.Window({
            
             connect : 'conceptos/vincular_beneficiario',
              
              style : {
                   width :  '550px',
                   height:  '350px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: 'Vincular Beneficiario',
              
              onLoad: function(){
                    
                    require([ "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                              function(declare, JsonRest, Observable, Cache, Memory){
 
                             var memoryStore = new Memory({});
                             var restStore = new JsonRest({

                                      target:"escalafon/provide/individuos/todos", 
                                      idProperty: "value",
                                      sortParam: 'oby',
                                      query: function(query, options){
                                          
                                         
                                          return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                      }

                                }); 
                              Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
                              Persona.Stores.individuos.query({});

                              //dijit.byId('selvb_persona').startup();   

                              dijit.byId('selvb_persona').set('store',Persona.Stores.individuos);

                        });


              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),


        modificacion_valores_planilla : new Laugo.View.Window({
            
             connect : 'conceptos/modificacion_valores_planilla',
              
              style : {
                   width :  '600px',
                   height:  '200px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: 'Modificar valores de los conceptos y variables',
              
              onLoad: function(){
                   
  
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),


        view_calculo : new Laugo.View.Window({

             connect : 'conceptos/view_calculo',
             
             style : {
                 width :  '600px',
                 height : '350px',
                 'background-color' : '#FFFFFF'
             }, 

             title : ' Calculo del concepto ',

             onLoad : function(){

             }        
        })   

 
    },
    
    Ui : {
        
        Grids: {
            
            main : null,
            planilladetalle_buscar : null
            
        },
        
        table_main_ready: function(){
            
              
                app.loader_show();
               
                require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                            function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){
                app.loader_hide();             

                             if(dojo.byId('dvtable_conceptosview') != null ){ 

                                 if(window.escalafon_grid  === null || window.escalafon_grid  === undefined)  window.escalafon_grid  = (declare([Grid, Selection,Keyboard]));


                                                       var store = Observable(Cache(JsonRest({
                                                                target:"conceptos/provide/main", 
                                                                idProperty: "id",
                                                                sortParam: 'oby',
                                                                query: function(query, options){

                                                                        var data = dojo.formToObject('frm_searchconceptos_main');
                                                                        
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
                                                                   /* col5: {label: 'Fecha Fin', sortable: false},
                                                                    col6: {label: 'Actual', sortable: false},
                                                                     col4: {label: 'Hasta', sortable: false},
                                                                    hingreso: {label: 'Hora Ingreso', sortable: false},
                                                                    col5: {label: 'Motivo', sortable: false}
                                                                   col6: {
                                                                                label: "Step", 
                                                                                sortable: false,
                                                                                field: "_item",
                                                                                formatter: testFormatter
                                                                            },
                                                                    col7: 'Column 5' */
                                                            };



                                                         Conceptos.Ui.Grids.main  = new  window.escalafon_grid({
                                                                loadingMessage : 'Cargando',
                                                                store: store,
                                                                getBeforePut: false,
                                                                columns: colums 


                                                        }, "dvtable_conceptosview");

                                              if( Conceptos.Ui.Grids.main != null){
                                            //Persona.Ui.Grids.comisiones.store.view_persona('6');
                                                 Conceptos.Ui.Grids.main.refresh();
                                                  // Persona.Ui.Grids.trabajadores.store.query({});
                                                  // Persona.Ui.Grids.trabajadores.cleanup();

                                            }          
                              }



                });
 
        },
        
        btn_preview_concepto : function(btn,evt){
             
             var ecuacion = Conceptos.fn_generar_ecuacion();   
             Conceptos._M.get_preview.send({'ecuacion' : ecuacion});
             
        },
        
        
        btn_guardar_concepto : function(btn,evt){
             
             var data  = dojo.formToObject('formNuevoConcepto');
             
             data.ecuacion = Conceptos.fn_generar_ecuacion();
             
              console.log('GRUPO: '+data.grupo);
              if(data.grupo == ''){
                    
                   data.grupo_label = dijit.byId('selconceptogrupo').get('displayedValue');
              }

             if(data.ecuacion == false){
                  console.log('La ecuacion no procede: '+ data.ecuacion); 
             }
             else{ 
                 
                 if(confirm('Realmente desea registrar el concepto remunerativo ? ')){
                     
                    if(Conceptos._M.registrar.process(data)){
                            
                            Conceptos._V.nuevo_concepto.close();
                            
                            if(Conceptos.Ui.Grids.main != null) Conceptos.Ui.Grids.main.refresh();
                            
                     }
                 } 
             }
              
        },
        
         
        btn_actualizar_concepto : function(btn,evt)
        {
              
            var data  = dojo.formToObject('formNuevoConcepto');
             
            data.ecuacion = Conceptos.fn_generar_ecuacion();
              
            data.view = dojo.query('.hdobjectkey', btn.parentNode)[0].value;
            
            if(data.grupo == '')
            {
                   data.grupo_label = dijit.byId('selconceptogrupo').get('displayedValue');
            }
             
            if(data.ecuacion == false)
            {
                  console.log('La ecuacion no procede: '+ data.ecuacion); 
            }
            else
            {  
             
                 if(confirm('Realmente desea guardar los cambios realizados ? '))
                 {
                     
                    if(Conceptos._M.actualizar.process(data))
                    {
                  
                            Conceptos._V.modificar_concepto.close();
                            if(Conceptos.Ui.Grids.main != null) Conceptos.Ui.Grids.main.refresh();
                            
                           Conceptos.Ui.get_view(Conceptos.Cache.id_view_info);
                    }

                 } 
           }
              
        },


        btn_eliminar_click : function(btn,evt){


            if(confirm('Realmente desea eliminar el concepto, esto podria tener efectos sobre los conceptos relacionados'))
            {

                var view = dojo.query('.hdconcview_id',btn.domNode.parentNode)[0].value;

                if(Conceptos._M.eliminar.process({ 'view' : view }))
                {

                    Conceptos.Ui.Grids.main.refresh();
                    Conceptos._M.get_view.reload();
                }
            }

        },
        
        
        btn_filtrartabla_main : function(btn,evt){
             
              Conceptos.Ui.Grids.main.refresh();
        },
          
         btn_getview_concepto_editar : function(btn,evt){
            
               var conc_k = dojo.query('.hdconcview_id', dojo.byId('dv_concepto_info_detalle'))[0].value;
            
               Conceptos._V.modificar_concepto.load({'view' : conc_k });
            
         },
         
          btn_showaddconcepto_click : function(btn,evt){
             
            var ep_k =  dojo.query('.hdpladet_empkey' , btn.domNode.parentNode)[0].value;
            Conceptos._V.add_concepto_trabajador.load({'detalle' : ep_k });
             
         },
         
         
         btn_addconcepto_detalle_click : function(btn,evt){
             
             
            var codigo = '';      
                    
            for(var i in Conceptos.Ui.Grids.planilladetalle_buscar.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                var empkey =  dojo.query('.hdaddconc_key', btn.parentNode)[0].value,
                    forall = dijit.byId('chk_addconc_forall').get('value');



                if(Conceptos._M.agregar_detalleempleado.process({'conc' : codigo, 'detalle' : empkey, 'forall' : ( (forall=='') ? '0' : '1' ), 'planilla' : dojo.byId('hdviewplanilla_id').value }))
                {
                     
                    Planillas._M.get_detalleplanilla_variables.send({'codigo' : Planillas.Cache.detalleplanilla_detalle_id, 'pla_key' : Planillas.Cache.detalleplanilla_plakey});  

                } 
            }
            else{
                alert('Debe seleccionar un registro');
            }
             
             
         },


         btn_addconcepto_detalle_filtrar : function(btn,evt){

              Conceptos.Ui.Grids.planilladetalle_buscar.refresh();

              

         },
         


         btn_vincularbeneficiario_click  : function(btn,evt){

              var data  = dojo.formToObject('frmvbj_vincular');
                  
              if(confirm('Realmente desea realizar esta operacion ? ')){     
                  if(Conceptos._M.vincular_beneficiario.process(data)){
                        
                       Trabajadores._V.gestionar_datos.send({'trabajador' : Trabajadores.Cache.trabajador_gestion_datos});
                       Conceptos._V.vincular_beneficiario.close(); 

                  }  

              }

         },

         btn_modificar_detalle_all : function(btn,evt){

              var planilla = dojo.byId('hdviewplanilla_id').value;
              Conceptos._V.modificacion_valores_planilla.load({'planilla': planilla});

         },

         btn_actualizar_estado_forall: function(btn,evt){
            
            var planilla = dojo.byId('hdviewplanilla_id').value,
                concepto = dijit.byId('addforall_concepto').get('value'),
                data = {};

            if(concepto != ''){

               if(confirm('Realemente desea actualizar el estado para todos los trabajdores? ')){

                   var estado = dijit.byId('addforall_estadoconcepto').get('value');

                   data = {

                       'planilla' : planilla,
                       'concepto' : concepto,
                       'estado'   : ((estado == '') ? '0' : '1' ) 

                   } 

                   if(Conceptos._M.actualizar_valor_forall.process(data)){

                        Conceptos._V.modificacion_valores_planilla.close();
                         Planillas.Ui.Grids.planillas_detalle.refresh();
                         dijit.byId('dv_vipla_detalle').set('content','');
                   } 
               }

            }
            else{
                alert('Verifique el concepto a modificar');
            }

         },
 
         btn_actualizar_variable_forall: function(btn,evt){
          
            var planilla = dojo.byId('hdviewplanilla_id').value;

         },

        
         btn_config_restringir_minmax : function(btn,evt){

              var data = {}
              
              data.view = dojo.query('.hdconckey',btn.domNode.parentNode)[0].value;  

              Conceptos._V.restringir_minimo_maximo.load(data);

         },
          
         get_view : function(concepto){
             Conceptos.Cache.id_view_info = concepto;
             Conceptos._M.get_view.send({'concepto' : concepto});
         }
        
    },
     
     
    fn_generar_ecuacion: function(){
        
      
             
           var comps =  dojo.query('.line_parte', dojo.byId('dv_operator_factory')),
               ecuacion = "";
            
            var o_id = '';
            dojo.forEach(comps, function(comp,i){
                
                  dojo.forEach( dojo.query('.hdtipocomponente',comp), function(tipo,j){
                      
                         switch(tipo.value){
                             
                             case 'cierre_parentesis':
                           
                                 if( dojo.query('.hdestadoparentesis',tipo.parentNode)[0].value=='1' ) ecuacion += ')';
                                     
                             break;
                         
                             case   'abre_parentesis':
                                   
                                 if( dojo.query('.hdestadoparentesis',tipo.parentNode)[0].value=='1' ) ecuacion += '(';
                                   
                             break;
                             
                             case   'operando':
                                   
                                  o_id = dijit.byNode(  dojo.query('.dijit ',tipo.parentNode)[0]  ).get('value');
                             
                                  if(o_id == '') o_id= 'cs|' +dijit.byNode(  dojo.query('.dijit ',tipo.parentNode)[0]  ).get('displayedValue');
                             
                                  ecuacion += '_'+ o_id +'_';  //xoperandox
                                      
                             break;
                             
                             case   'operador':
                                  
                                  ecuacion +=  '_'+dijit.byNode(  dojo.query('.dijit ',tipo.parentNode)[0]  ).get('value')+'_';  //operadorxxxxx
                                 
                             break;
                             
                         }
                        
                  });
                 
            });
              
         return ecuacion;
        
    },
     
    fn_add_line: function(element_insert){
         
               // alert('Inert new parte'); 

                // DIV CONTENEDOR DEL FRAGMENTO   )( operando )( operador
                var parte =  dojo.create('div', {

                      id : 'oparte' + dojo.query('.line_parte', dojo.byId('dv_operator_factory')).length,
                      innerHTML  : '',
                      'class' :  "line_parte"

                }, element_insert,'last' ); 

 



                     /*** SEGUNDO PARENTESIS "(" */     
                     var dv_2 =  dojo.create('div', {  // Contenedor del segundo parentesis DV_2

                         className : 'operator_comp'

                     },parte,'last' )


                     dojo.create('input',{ // SEGUNDO HD tipo componente

                        'class' : 'hdtipocomponente',
                        'type'   : 'hidden',
                        'value'  : 'abre_parentesis'

                     },dv_2,'last');

                     var hdestpa2 =  dojo.create('input',{ // SEGUNDO HD que almacena el estado del parentesis

                        'class' : 'hdestadoparentesis',
                        'type'   : 'hidden',
                        'value'  : '0'

                     },dv_2,'last');


                     var p_abre_1 = dojo.create('label', {

                          innerHTML : '(',
                          className : 'operator_parentesis'

                     },dv_2,'last');

                         dojo.connect(p_abre_1,'click', function(){  // EVENTO CLICK PARA EL SEGUNDO PARENTESIS

                               if(hdestpa2.value == '0'){

                                    dojo.removeClass( this, 'operator_parentesis');
                                    dojo.addClass( this, 'operator_parentesis_select');
                                    hdestpa2.value =  '1';
                              }else{
                                   dojo.addClass( this, 'operator_parentesis');
                                   dojo.removeClass( this, 'operator_parentesis_select');
                                   hdestpa2.value =  '0';
                              }


                         });

                     /* FINAL SEGUNDO PARENTESIS */




                     /* OPERANDO */    

                     var dv_3 =  dojo.create('div', { // CONTENEDOR PARA EL OPERANDO DIV_3

                         className : 'operator_comp'

                     },parte,'last' )


                     dojo.create('input',{  // TERCER HD TIpo de componente 

                        'class' : 'hdtipocomponente',
                        'type'   : 'hidden',
                        'value'  : 'operando'

                     },dv_3,'last');



                     var sel_1 = dojo.create('div', { // PRIMER SELECT OPERANDO

                          innerHTML : '',
                          className  : 'seloperando' 


                     },dv_3,'last');    
                     
                      var select_operando = new dijit.form.FilteringSelect({ // PRIMER FILTERING PARA EL OPERANDO
                          /*
                        value: "id",
                        label : "nombre",*/
                        store: Conceptos._M.store_conceptos_variables,
                        style : "width:150px; font-size:11px;",
                        autoComplete: false,
                        highlightMatch: "all",  
                        queryExpr:"*${0}*"

                       },  sel_1 ); 
                       
                       
                     select_operando.startup();   
                  //   console.log(   Conceptos._M.store_conceptos_variables);
                  
                    /*FINAL PRIMER OPERANDO */     



                     // TERCER PARENTESIS ")"

                       var dv_4 =  dojo.create('div', { // CONTENEDOR PARA EL SEGUNDO PARENTESIS )

                             className : 'operator_comp'

                         },parte,'last' )


                         dojo.create('input',{  // cuarto HD tipo de componente

                            'class' : 'hdtipocomponente',
                            'type'   : 'hidden',
                            'value'  : 'cierre_parentesis'

                         },dv_4,'last');

                         var hdestpa4 =  dojo.create('input',{ // cuarto HD estado del parentesis

                            'class' : 'hdestadoparentesis',
                            'type'   : 'hidden',
                            'value'  : '0'

                         },dv_4,'last');



                        var p_cierre_2 =  dojo.create('label', { // parentesis )

                          innerHTML : ')',
                          className : 'operator_parentesis'

                         },dv_4,'last');

                             dojo.connect(p_cierre_2,'click', function(){ // Eventos para el segundo parentesis )

                                    if(hdestpa4.value == '0'){

                                            dojo.removeClass( this, 'operator_parentesis');
                                            dojo.addClass( this, 'operator_parentesis_select');
                                            hdestpa4.value =  '1';
                                      }else{
                                           dojo.addClass( this, 'operator_parentesis');
                                           dojo.removeClass( this, 'operator_parentesis_select');
                                           hdestpa4.value =  '0';
                                      }

                             });


 


                         /* OPERADOR */ 

                         var dv_6 =  dojo.create('div', {

                             className : 'operator_comp'

                         },parte,'last' )

                         dojo.create('input',{

                            'class' : 'hdtipocomponente',
                            'type'   : 'hidden',
                            'value'  : 'operador'

                         },dv_6,'last');



                           var ope_1 = dojo.create('select', {

                                  innerHTML : '',
                                  className : 'seloperador'

                             },dv_6,'last'); 


                             var ope_1_d = new dijit.form.Select({
                                style : "width:32px; font-size:12px;",
                                options: [
                                  {label: '',  value: '¿'},
                                  {label: '+', value: '+', selected: false},
                                  {label: '-', value: '-'},
                                  {label: 'X', value: 'x'},
                                  {label: '/', value: '/'}
                                ]
                              }, ope_1);
                              
                              dojo.create('input',{

                                'class' : 'hdidselectoperador',
                                'type'   : 'hidden',
                                'value'  : ope_1_d.get('id')

                             },dv_6,'last');
                               

                             dojo.connect(ope_1_d,'onChange', function(){


                                  var partes_ops= dojo.query('.line_parte', dojo.byId('dv_operator_factory'));

                                  if(partes_ops[partes_ops.length -1 ].id == parte.id && ( ope_1_d.get('value') != '¿' )  ){ // Si el ID del ultimo fragmento (parte operacion), es igual al ID del fragmento Y el combo tiene valor 0 




                                      if( (dojo.query('.line_parte', dojo.byId('dv_operator_factory')).length % 2) == 1 ){ 
                                          // Si la cantidad de fragmentos entre 2 da residuo entonces no agrega otra linea


                                        Conceptos.fn_add_line(element_insert);

                                      }else{

                                         var lineas_operaciones = dojo.query('.concepto_line', dojo.byId('dv_operator_factory'));

                                         var n_line =  dojo.create('div', {

                                              innerHTML  : '',
                                              className :  "concepto_line"

                                         },   lineas_operaciones[ lineas_operaciones.length - 1] , 'after'); // Creamos una nueva linea 

                                         //dojo.place(n_line, dojo.byId('dv_operator_factory') , 'last');

                                         Conceptos.fn_add_line(n_line);

                                      }

                                  }

                             });




         }, 
         
         generate_ops : function(ecuacion){
             
             ecuacion  = ecuacion || {};
             
             // (  OPERANDO ) OPERADOR
             
             for(var op in ecuacion){
                  
             }
              
         },
         
         
         update_detalleplanilla : function(data){
             
             return Conceptos._M.guardar_detalleconcepto.process(data);
         },
 

         vincular_beneficiario : function(data){

              Conceptos._V.vincular_beneficiario.load(data);
         }


         
}