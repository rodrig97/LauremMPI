var tabla_variables_montos = {


  last_row : null,

	_M : {

      actualizar : new Laugo.Model({
  
              connect : 'variables/tabla_actualizar_montos'
  
      }),
  
      ver_tabla_montos : new Request({
          
          type :  'text',
          
          method: 'post',
          
          url : 'variables/ver_tabla_montos',
          
          onRequest : function(){
               app.loader_show(); 
          },
          
          onSuccess  : function(responseText){
              
               app.loader_hide(); 
              
              dijit.byId('dvtablamontos_view').set('content', responseText);   

                
          },
          
          onFailure : function(){
              
          } 
          
      })


	},

	_V : {

		    gestionar : new Laugo.View.Window({
            
             connect : 'variables/tabla_gestionar_montos',
              
              style : {
                   width :  '900px',
                   height:  '450px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Gestionar Tablas de valores ',
              
              onLoad: function(){


                  dojo.connect( dijit.byId('seltablemontos_tables'), 'onChange', function(evt){
                       
                        var v = dijit.byId('seltablemontos_tables').get('value');
                     
                        tabla_variables_montos._M.ver_tabla_montos.send({'view' : v});

                  });

                  dijit.byId('seltablemontos_tables').onChange();

              },

              onclose : function(){

              }
        })

	},

  btn_editar_reglon: function(btn, evt){


      if(this.last_row != null) this.deshabilitar_row(this.last_row);
     
      var cell          = btn.domNode.parentNode.parentNode;
      var row           = cell.parentNode; 
      var dv_containers = dojo.query('.textbox_afp', row);
      var dv_labels     = dojo.query('.dvlabel_afp', row); 
      var last_data     = dojo.query('.last_data', row);  
      var dv_btns       = dojo.query('.dv_btncontent_afp', row);
 

      dojo.setStyle(dv_btns[0],'display','none');
      dojo.setStyle(dv_btns[1],'display','inline');

      dojo.forEach(dv_labels, function(dv,ind){
           dojo.setStyle(dv,'display','none');
      });

      dojo.forEach(dv_containers, function(dv,ind){       
           dojo.setStyle(dv,'display','inline');

           dijit.byNode( dojo.query('.formelement-50-12',dv)[0] ).set('value', last_data[ind].value );

      });

      this.last_row = row;
 
  },

  btn_save_reglon: function(btn, evt){
 

    if(confirm('Realmente desea guardar los cambios?')){

         var cell          = btn.domNode.parentNode.parentNode;
         var row           = cell.parentNode; 

         var view  = dijit.byId('seltablemontos_tables').get('value');

         var celdas = dojo.query( '.datos', row );

         var c = '', k = '', v = ''; 

         dojo.forEach(celdas, function(celda, ind){
             
             v = dijit.byNode( dojo.query('.formelement-50-12',celda)[0] ).get('value');
             k = dojo.query('.keys',celda)[0].value;

             c+='|'+k+'_'+v;

         });  

         if(tabla_variables_montos._M.actualizar.process({data: c, 'view' : view })){

              dijit.byId('seltablemontos_tables').onChange();
         }

     }
 
  },

  btn_cancel_reglon : function(btn, evt){


      var cell          = btn.domNode.parentNode.parentNode;
      var row           = cell.parentNode; 
      
      this.deshabilitar_row(row);

  },


  btn_migrar_vari_addrow : function(btn,evt){


  },


  habilitar_row : function(){


  },


  deshabilitar_row : function(row){
 
      var dv_containers = dojo.query('.textbox_afp', row);
      var dv_labels = dojo.query('.dvlabel_afp', row);  
      var dv_btns       = dojo.query('.dv_btncontent_afp', row);
      
      dojo.setStyle(dv_btns[1],'display','none');
      dojo.setStyle(dv_btns[0],'display','inline');

      dojo.forEach(dv_labels, function(dv,ind){
           dojo.setStyle(dv,'display','inline');
      });

      dojo.forEach(dv_containers, function(dv,ind){       
           dojo.setStyle(dv,'display','none');

      });

  }


}