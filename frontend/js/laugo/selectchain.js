 /* Package: Laugo
  * Name:  SelectsChain
  * Descripcion:  Dojo plugin que permite encadenar selects valor/modelo/valores
  * Autor: Giordhano Valdez Linares
  * version : 0.1
  *
  */
 
 
dojo.declare('Laugo.SelectsChain',null,{
   
    selects : [],
    functions_first :[],
    exec_first : [],
    // models : [], // {url : , data: }
    
    syncrono : false,
     /*
    chain_automatic: true,
   
    param_id : 'id',
    
    param_label : 'label', */
    
    constructor: function(opts){
          
          
          this.selects = opts.selects;  //
          this.functions_first =  (this.functions_first != null) ? opts.first_fns : [];
           
           
          //
          //
          //
          //this.models = opts.models;
            
          (function(self){
                
                    
               dojo.forEach(self.functions_first, function(el,x){

                    self.exec_first[x] = true;

                    if(dojo.isFunction(el)){
                        self.exec_first[x] = false;
                    }

               });
                
                  dojo.forEach(self.selects, function(select, ind){

                         if( ind< (self.selects.length-1) ){ 
                         /*  {'pagina/pagina1' : ''}
                          *  
                          *  [  
                          *     { element:  seldepartamento, connect : 'viaticos/departamento' , data: {} }
                          *  ]
                          *  
                          *  Models : [
                          *       {  connect :  '' , data: {'departamento' : dijit.byId('sss') ,  }   } 
                          *   ]
                          */ 
                                var element = dijit.byId(select.element);   // elemento actual 
                                dojo.connect( element, 'onChange', function(){


                                      var data_params =  {}  // parametros a enviar en la consulta que llenara el combo continuo


                                      data_params.param_select = dijit.byId(select.element).get('value'); // parametro x defecto, el valor del combo actual

                                           //dojo.isString(it);

                                      var next_data = self.selects[ind+1].data; // tomamos la data secundaria a enviar en la consulta del siguiente combo
                                      var next_element = dijit.byId(self.selects[ind+1].element); // siguiente elemento (select)

                                      for( dato in next_data  ){ // creamos los parametros 

                                           if(dojo.isString( next_data[dato]) ){ // comprobamos el tipo

                                                data_params[dato] = next_data[dato];
                                           }
                                           else{    // tipo elemento entonces se toma el valor
                                                data_params[dato] = next_data[dato].get('value');
                                               //var valor =  (dijit.byId(current_model.data[dato]));

                                           } 
                                       }
                                       // inicamos la consulta ajax
                                       dojo.xhrPost({
                                            sync: self.syncrono,  // x defecto asyncrono
                                            url:   self.selects[ind+1].connect,  //self.models[ind],
                                            timeout: 20000,
                                            content:  data_params, // parametros
                                            handleAs: 'json',
                                            load: function(response) {
                                                 // params.onSuccess(response);


                                                   dojo.forEach( next_element.getOptions(), function(opt,it){

                                                      next_element.removeOption(opt);
                                                   });

                                                   dojo.forEach( response, function(newOption, it){


                                                        next_element.addOption(  { label:  newOption[self.selects[ind+1].struct[1]], value:  newOption[self.selects[ind+1].struct[0]], disabled : false } );

                                                   });
                                                    
                                                      // alert(self.functions_first[ind]);
                                                      if(self.exec_first[ind] === false && self.functions_first != null ){   
                                                           self.functions_first[ind]();
                                                           self.exec_first[ind] =true;
                                                       }
                                                   
                                                   if(dojo.isFunction(select.callback)) select.callback();
                                                   
                                                 
                                                   
                                                   
                                                   // call back other func 
                                            },
                                            error: function(err){
                                               /*   params.onFailure(err); */
                                            },
                                            handle: function(response, ioArgs){
                                               /* params.isRunning = false;
                                                params.onComplete(response, ioArgs); */
                                            }

                                        });

                                });
                         }
                  });
                  
                  
                 /*   
                       if( ind == (self.selects.length-2))
                       {
                       //     alert(self.functions_first[ind+1]);
                           self.functions_first[ind+1]();
                           self.exec_first[ind+1] =true;
                       }
                  */
              
          })(this);
         
          
           dijit.byId(this.selects[0].element).onChange();
          
    }
    
   
   
    
});