/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


 

var Conceptos =  {
    
    _M : {
        
         
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

                    window.stateStore = new dojo.store.Memory({
                            data: [
                                {name:"Concepto 1", id:"1"},
                                {name:"Concepto 2", id:"2"},
                                {name:"variable 3 ", id:"3"},
                                {name:"variable 4", id:"4"} 

                            ]
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
               
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }) 
        
    },
    
    Ui : {
        
        Grids: {},
        
        table_main_ready: function(){
            
              
                app.loader_show();
               
                require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                            function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){
                app.loader_hide();             

                             if(dojo.byId('dvtable_variablesview') != null ){ 

                                 if(window.escalafon_grid  === null || window.escalafon_grid  === undefined)  window.escalafon_grid  = (declare([Grid, Selection,Keyboard]));


                                                       var store_trabajadores = Observable(Cache(JsonRest({
                                                                target:"get_trabajadores", 
                                                                idProperty: "id",
                                                                sortParam: 'oby',
                                                                query: function(query, options){

                                                                     

                                                                        return JsonRest.prototype.query.call(this, query, options);
                                                                }
                                                        }), Memory()));

                                                          var colums = { // you can declare columns as an object hash (key translates to field)
                                                                   // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                                    col1: {label:'#'},
                                                                    col2: {label: 'Nombre', sortable: true},
                                                                    col3: {label: 'Aplicable a', sortable: false},
                                                                    col4: {label: 'Tipo', sortable: false},
                                                                    col5: {label: 'Valores', sortable: false},
                                                                    col6: {label: 'Por Defecto', sortable: false}
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



                                                         Variables.Ui.Grids.main  = new  window.escalafon_grid({

                                                                store: store_trabajadores,
                                                                getBeforePut: false,
                                                                columns: colums 


                                                        }, "dvtable_variablesview");

                                              if( Variables.Ui.Grids.main != null){
                                            //Persona.Ui.Grids.comisiones.store.view_persona('6');
                                                 Variables.Ui.Grids.main.refresh();
                                                  // Persona.Ui.Grids.trabajadores.store.query({});
                                                  // Persona.Ui.Grids.trabajadores.cleanup();

                                            }          
                              }



                });
 
        },
        
        btn_preview_concepto : function(btn,evt){
            //alert('Click');
            
           var comps =  dojo.query('.line_parte', dojo.byId('dv_operator_factory'));
          // alert(comps.length);
           
           var ecuacion = "";
            dojo.forEach(comps, function(comp,i){
                 
              //     alert(dojo.query('.hdtipocomponente',comp)[0].value);
                 
                  dojo.forEach( dojo.query('.hdtipocomponente',comp), function(tipo,j){
                        
                       //    alert(tipo.value);
                      //   console.log( i + " - " + j + " | " +tipo.value + " : " +  dojo.query('.hdestadoparentesis',comp)[0].value );
                         switch(tipo.value){
                             
                             case 'cierre_parentesis':
                                //   alert(dojo.query('.hdestadoparentesis',comp)[0].value);
                                   if( dojo.query('.hdestadoparentesis',tipo.parentNode)[0].value=='1' ) ecuacion += ')';
                                     
                             break;
                         
                             case   'abre_parentesis':
                                   
                                   if( dojo.query('.hdestadoparentesis',tipo.parentNode)[0].value=='1' ) ecuacion += '(';
                                   
                             break;
                             
                             case   'operando':
                                   //  alert(tipo.parentNode.innerHTML);
                              //       alert(dojo.query('.dijit ',tipo.parentNode)[0]);
                              /*
                               * 
                               *     _xvarx 
                               *     _xconstx
                               *     _xconcx
                               *    */
                                  ecuacion += '_'+dijit.byNode(  dojo.query('.dijit ',tipo.parentNode)[0]  ).get('value')+'_';  //xoperandox
                                      
                             break;
                             
                             case   'operador':
                                  
                                     ecuacion +=  '_'+dijit.byNode(  dojo.query('.dijit ',tipo.parentNode)[0]  ).get('value')+'_';  //operadorxxxxx
                                 // ecuacion += dijit.byNode(  dojo.query('.seloperador',tipo.parentNode)[0]  ).get('value'); 
                                  
                                     
                             break;
                             
                         }
                        
                  });
                 
            });
             
             //alert(ecuacion);  
             
             Conceptos._M.get_preview.send({'ecuacion' : ecuacion});
             
        } 
        
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

                        value: "CA",
                        store: stateStore,
                        style : "width:150px; font-size:11px;" 

                       },  sel_1 );


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




         }
     
}