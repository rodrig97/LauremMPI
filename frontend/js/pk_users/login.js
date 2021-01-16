 
/*
 *  MODULO LOGIN 
 *  
 */
 

if( app == undefined || app == null) 
{
    console.write('Debes instanciar la clase App.Main con el objeto app');
}

Users = {} 

Users.Login  =  {
    
     destinity_url : app.getUrl(),
    
    captcha_result : 0,  
    ready : function (){
            
          $_chain_inputs(['login_txtuser','login_txtpass','login_txtcomp','login_btnLogin']);
           
          self  = this;
          var rq = new Request({
                          
                         method: 'post',
                         
                         type: 'json',
                         
                         url:  app.getUrl()+"users/auntentificar",

                         onRequest: function(){
                              // alert('cargando');
                              
                              app.loader_show();
                         },

                         onSuccess: function(responseJSON){
                               
                               app.loader_hide();
                               
                               if(responseJSON.result=='1')
                               {
                                    alert(responseJSON.mensaje);
                                    //dojo.byId('ui_login_window_c').fade(0);
                                    dojo.fadeOut({node: 'ui_login_window_c' }).play();
                                    self.to();
                               }
                               else if(responseJSON.result=='0')
                               {
                                   // if(app.loader_hide!=null && app.loader_hide != undefined) self.app.loader_hide();
                                    self.generar_captcha();
                                    dojo.byId('login_txtpass').value='';
                                    dojo.byId('login_txtcomp').value='';
                                    dojo.byId('login_txtuser').focus();
                                    self.cant_intentos++;
                                    alert(responseJSON.mensaje);
                                    
                                    if(self.cant_intentos==3){
                                       alert('Excedio el numero maximo de intentos permitidos (3)');
                                    //   dojo.byId('ui_login_window_c').fade(0);
                                       dojo.fadeOut({node: 'ui_login_window_c' }).play();
                                   }
                                    
                               }
                               else{
                                    
                                    //if(app.loader_hide!=null && app.loader_hide != undefined) self.app.loader_hide();
                                    self.generar_captcha();
                                    dojo.byId('login_txtpass').value = '';
                                    dojo.byId('login_txtcomp').value = '';
                                    dojo.byId('login_txtuser').focus();
                                    alert('Ocurrio un problema durante la operacion');
                                    
                               }
                                
                               
                         },
                         
                         onFailure: function(){
                             alert('Ocurrio un problema durante la comunicacion  con el servidor');
                         }

           });
          
          // Login button click
           dojo.connect( dijit.byId('login_btnLogin'),'onClick',function(){

                            var user =  dojo.trim(dojo.byId('login_txtuser').value),
                                pass =  dojo.trim(dojo.byId('login_txtpass').value),
                                result = dojo.trim(dojo.byId('login_txtcomp').value);

                                if(user!= '' && pass != '')
                                {
                                   // alert(self.captcha_result);
                                    if(result== self.captcha_result ){

                                         rq.send({ 
                                                     'access_to': dijit.byId('login_txtuser').value,
                                                     'pass':      dijit.byId('login_txtpass').value,
                                                     'anio':  dijit.byId('sellogin_anio').get('value')
                                                 });
                                    }
                                    else{

                                        dojo.byId('login_txtpass').value = '';
                                        dojo.byId('login_txtcomp').value = '';
                                        alert('Verifique la comprobación');
                                        dojo.byId('login_txtpass').focus();
                                        self.generar_captcha();
                                    }
                                }
                                else{
                                    dojo.byId('login_txtuser').focus();
                                    alert('Especifique su Usario y Contraseña por favor');
                                }
 
            });
 
       
          //dijit.byId('login_btnLogin').onClick();
          
          this.generar_captcha();
          dojo.byId('login_txtuser').value = '';
          dojo.byId('login_txtpass').value = '';
          dojo.byId('login_txtcomp').value = '';
          dojo.byId('login_txtuser').focus();   
             
          $_setCenter('ui_login_window_c');
          

    },
    
    
    generar_captcha: function(){
         
          
         var n1 = $_random(1,10)
              n2 = $_random(1,10),
              ops = ['','+','x'],  
              op = $_random(1,2), 
              rs = 0;
          
          rs = (op == 1) ? (n1 + n2) : (n1 * n2);
        
          dojo.attr('login_spcuestion','innerHTML','Cuanto es '+n1.toString()+' '+ ops[op]+' '+n2.toString()+' ?');
          //dojo.byId('login_spcuestion').innerHTML = 'olitas';
          this.captcha_result  = rs;
         
          
     },
   
     to: function(){
          
          document.location.href=  this.destinity_url;
 
          // ir a la direccion
     },
     
     cerrar_sesion: function(){
         
     }
      
}
