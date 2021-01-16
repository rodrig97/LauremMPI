/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var Acciones = {
     
     last_edit_button: null,
     
     acti_current_k: '',
     
    _Models : {
       
       rq_get_acciones : new Request({
           
            url:  'acciones/display_acciones',
            
            method: 'post',
            
            type : 'text',
            
            onRequest: function(){
                app.loader_show();
          
            },
            
            onSuccess: function(responseText){
                app.loader_hide();
                
                 Acciones.Ui.wd_new_acc = new dijit.Dialog({
                                title: "Programaci&oacute;n de Acciones",
                                content:  responseText, 
                                style: "width: 900px; height: 450px; background-color:#FFFFFF;",
                                onCancel : function(){
                                        
                                        dijit.registry.destroy_childrens(  Acciones.Ui.wd_new_acc.domNode );
                                        //if(Actividades.objetivo_current){}
                                         Actividades.get_actividades( Actividades.get_actividades_id, Actividades.get_actividades_env  );
                                }
                            });
                            
                
                 Acciones.Ui.wd_new_acc.show();
            },
            
            onFailure: function(){
                 alert('Error on acciones.get_acciones [FAILURE]');
                 app.loader_hide();
            }
           
       }),
       
       rq_registrar_accion : new Request({
           
            url:  'acciones/registrar',
            
            method: 'post',
            
            type : 'json',
            
            onRequest: function(){
                app.loader_show();
                
            },
            
            onSuccess: function(responseJSON){
                app.loader_hide();
                
                alert(responseJSON.mensaje);
                if(responseJSON.result == '1'){
                    dijit.registry.destroy_childrens(   Acciones.Ui.wd_new_acc.domNode );  
                    Acciones.Ui.wd_new_acc.destroy();
                    Acciones.display_acciones(responseJSON.actividad);
                } 
            },
            
            onFailure: function(){
                 alert('Error on acciones.rq_registrar_accion [FAILURE]');
                 app.loader_hide();
            }
           
       }),
       
       rq_delete : new Request({
           
            url:  'acciones/eliminar',
            
            method: 'post',
            
            type : 'json',
            
            onRequest: function(){
                app.loader_show();
                
            },
            
            onSuccess: function(responseJSON){
                app.loader_hide();
                
                alert(responseJSON.mensaje);
                if(responseJSON.result == '1'){
                   // var acti_current = dojo.byId('hd_actiacckey').value;
                    dijit.registry.destroy_childrens(   Acciones.Ui.wd_new_acc.domNode );  
                    Acciones.Ui.wd_new_acc.destroy();
                    Acciones.display_acciones(Acciones.acti_current_k);
                } 
            },
            
            onFailure: function(){
                 alert('Error on acciones.eliminar [FAILURE]');
                 app.loader_hide();
            }
           
       }),
       
        rq_update : new Request({
           
            url:  'acciones/actualizar',
            
            method: 'post',
            
            type : 'json',
            
            onRequest: function(){
                app.loader_show();
                
            },
            
            onSuccess: function(responseJSON){
                app.loader_hide();
                
                alert(responseJSON.mensaje);
                if(responseJSON.result == '1'){
                 //   var acti_current = dojo.byId('hd_actiacckey').value;
                    dijit.registry.destroy_childrens(   Acciones.Ui.wd_new_acc.domNode );  
                    Acciones.Ui.wd_new_acc.destroy();
                    Acciones.display_acciones(Acciones.acti_current_k);
                } 
            },
            
            onFailure: function(){
                 alert('Error on acciones.actualizar [FAILURE]');
                 app.loader_hide();
            }
           
       })
      
      
    },
    
    Ui: {
        
        wd_new_acc : null,
        
        btn_newacc_click : function(btn,e){
             
             var acti  = dojo.query('.hdActiSelect',btn.domNode.parentNode)[0].value;
             var  nombre = dijit.byId('txtproac_accnombre').get('value');
             
             if(dojo.trim(nombre)!=''){  
                 
                 if(confirm('Realmente desea registrar la accion?')){  
                    Acciones.registrar(acti, nombre);
                 }
             }
             else{
                 alert('Por favor complete el nombre de la accion');
             }
            
        },
        
        btn_edit_click : function(btn,e){
              
              Acciones.cancel_last_edit();
              Acciones.last_edit_button = btn;
              var row_parent = btn.domNode.parentNode.parentNode;
               
              dojo.style( dojo.query('.btnacc_edit',row_parent)[0],'display','none');
              dojo.style( dojo.query('.btnacc_canceledit',row_parent)[0],'display','block');
              dojo.style( dojo.query('.btnacc_save',row_parent)[0],'display','block');
              
              dojo.style( dojo.query('.dvacc_nombre',row_parent)[0],'display','none');
              dojo.style( dojo.query('.txtacc_nombre',row_parent)[0],'display','block');
               
              
              
        },
        
        btn_canceledit_click : function(btn,e){
             
              var row_parent = btn.domNode.parentNode.parentNode;
              
              dijit.byNode(dojo.query('.txtacc_nombre',row_parent)[0]).set('value',  dojo.trim(dojo.query('.dvacc_nombre',row_parent)[0].innerHTML)  );
              
              dojo.style( dojo.query('.btnacc_canceledit',row_parent)[0],'display','none');
              dojo.style( dojo.query('.btnacc_save',row_parent)[0],'display','none');
              dojo.style( dojo.query('.btnacc_edit',row_parent)[0],'display','block');
              
              dojo.style( dojo.query('.dvacc_nombre',row_parent)[0],'display','block');
              dojo.style( dojo.query('.txtacc_nombre',row_parent)[0],'display','none');
              
        },
        
        btn_save_click : function(btn,e){
              
              var row_parent = btn.domNode.parentNode.parentNode;
              var cell_parent = btn.domNode.parentNode;
              
              var acc = dojo.query('.hdacc_key',cell_parent)[0].value;
              var n_nombre =  dijit.byNode(dojo.query('.txtacc_nombre',row_parent)[0]).get('value');
              
              //alert(acc+' '+n_nombre);
              if(dojo.trim(n_nombre)!=''){ 
                
                 if(confirm('Realmente desea actualizar la accion? ')) Acciones.actualizar(acc,n_nombre);
              }
              else{
                  alert('El nombre de la accion es obligatorio');
              }
        },
        
        btn_delete_click : function(btn,e){
             Acciones.cancel_last_edit();
            var cell_parent = btn.domNode.parentNode;
            var acc = dojo.query('.hdacc_key',cell_parent)[0].value;
            
            if(confirm('Realmente desea elminar esta accion? ')){ 
                 Acciones.eliminar(acc);
            }
           
        }
        
    },
    
    cancel_last_edit : function(){
         
          if( this.last_edit_button != null && this.last_edit_button.domNode != null ){ 
              
              var row_cancel_button =  this.last_edit_button.domNode.parentNode.parentNode; 
              
              dijit.byNode(dojo.query('.btnacc_canceledit',row_cancel_button)[0]).onClick();
              //dojo.query('.btnproa_canceledit',div_cancel_button)[0].onClick(); 
              
          }
          
    },
    
    display_acciones: function(actividad){
        this.acti_current_k = actividad;
        this._Models.rq_get_acciones.send({'acti': actividad});
    },
    
    registrar : function(actividad,nombre){
        this._Models.rq_registrar_accion.send({'nombre': nombre,'acti': actividad});
    },
    
    eliminar: function(accion){
        this._Models.rq_delete.send({'accion': accion})
    },
    
    actualizar: function(accion,nombre){
        
       
        this._Models.rq_update.send({'accion' : accion,'nombre' : nombre});
        
    }
    
    
     
}