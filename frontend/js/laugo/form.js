/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



dojo.declare('Laugo.Form',null,{
    
    
    app: null, //Laugo.App Instance
 
    form_id: null,
    form: null,
    
    _state: 1, // por defecto enabled
    
    default_values : {
         
    },
    
    //provide_view: 'escalafon/form_nuevo',
     
    style_elements: {
      
         onDisabled: {
              'background-color ' : '#ffffcc'
         }
      
    },
    
    last_entorno: 0,
    
    _widgets : [],
        
    _memory : {},
    
    not_elements : [],

    onReady : function(){},
      
    constructor: function(opts){
       
        if( opts.form_id != null){
             
             this.form_id = opts.form_id;
    
        }
       
        if(this.app == null && opts.app != null){
             
             this.app = opts.app;
        }
        
        if(opts.onReady != null && dojo.isFunction(opts.onReady) ){
             
             this.onReady = opts.onReady;
             
        }
      
    },
    
    
    ready: function(entorno){
          
          this.last_entorno = entorno;
          
          this.form = dijit.byId(this.form_id); 

          if(dijit.byId(this.form_id) != null)
          { 
              this._widgets = this.form._getDescendantFormWidgets();
     
              this.onReady(entorno);
                
              var self = this;
            
              dojo.forEach(this._widgets, function(f,ind){
                    f.onChange();
                });

              delete self;
          }
    },
    
    store : function(){
        
       //alert(fields);
       
        var self = this;
        
        dojo.forEach(this._widgets, function(f,ind){
             self._memory[ind] =  f.get('value');
        });
        
        delete self;
    },
    
    
    restore: function(){
        
        var self = this;
        dojo.forEach(this._widgets, function(f,ind){
              f.set('value',  self._memory[ind] );
        });
          delete self;
    },
    
    disabled: function(){
          
         
         //alert(fields);
         dojo.forEach(this._widgets, function(f,ind){
              //alert(f);
              f.set('readOnly', true);
              f.set('style','background-color:#ffffc3');

         });
         
    },
    
    enabled: function(){
        
 
         dojo.forEach(this._widgets, function(f,ind){
           //   alert(f);
              f.set('readOnly', false);
              f.set('style','background-color: #FFFFFF');

         });
        
    }, 
    
    get_data: function(){
         //  alert(this.form);
         return  dojo.formToObject(this.form_id);
          
    },
    
    reset: function(){
          this.form.reset();
    },
    
    
    pattern_buttons_ready: function(toolbar){
          
         //var btn_edit =  dijit.byNode(dojo.query('.', toolbar)[0]);  
         
         
         
    }
    
   
    
    
    
});
 