 
Modules = {}
Users = Modules.users = {};
 
  
Users.Login = new Class({
    
     Implements: Options,
     
     rq : null,
     
     app: null,
      
     cant_intentos: 0,
     
     captcha_result : 0,
     url: '',
       
     options: {
         
          app : null,
          max_intentos: 1,
          onReady: function(){}
     },
      
      
     initialize: function(options){
        
            this.setOptions(options);
            this.app = this.options.app;
            //this.on_ready = this.options.onReady;
            this.url =  this.app.url_base + this.options.url;
            
             ( function(self){
             
                      self.rq =  new Request.JSON({

                        url: self.app.url_base+'users/auntentificar',

                        method: 'post',

                        onRequest: function(){

                              if(app.loader_show!=null && app.loader_show != undefined)  self.app.loader_show();

                        },

                        onSuccess: function(responseJSON,responseText){

                              

                               if(responseJSON.result=='1')
                               {
                                    alert(responseJSON.mensaje);
                                    $('ui_login_window_c').fade(0);
                                    self.to();
                               }
                               else if(responseJSON.result=='0')
                               {
                                    if(app.loader_hide!=null && app.loader_hide != undefined) self.app.loader_hide();
                                    self.generar_captcha();
                                    $('login_txtpass').set('value','');
                                    $('login_txtcomp').set('value','');
                                    $('login_txtuser').focus();
                                    self.cant_intentos++;
                                    alert(responseJSON.mensaje);
                                    
                                    if(self.cant_intentos==3){
                                       alert('Excedio el numero maximo de intentos permitidos (3)');
                                       $('ui_login_window_c').fade(0);
                                   }
                                    
                               }
                               else{
                                    
                                    if(app.loader_hide!=null && app.loader_hide != undefined) self.app.loader_hide();
                                    self.generar_captcha();
                                    $('login_txtpass').set('value','');
                                    $('login_txtcomp').set('value','');
                                    $('login_txtuser').focus();
                                    alert('Ocurrio un problema durante la operacion');
                                    
                               }

                        },

                        onFailure: function(){
                              
                               if(app.loader_hide!=null && app.loader_hide != undefined) self.app.loader_hide();

                        }
            }) 
              
          })(this);
         
         
         $('ui_login_window_c').setCenter();
         Helpers.form.form_add_sequence([ 'login_txtuser','login_txtpass','login_txtcomp']);
         
         $('login_txtcomp').addEvent('keypress',function(e){
             
             if(e.key=='enter'){
                 $('login_btnIngresar').fireEvent('click');
             }
              
         });
         
         this.generar_captcha();
         
         if($('login_btnIngresar') != null){ 

             $('login_btnIngresar').addEvent('click',function(){
                   
                    
                        var user = $('login_txtuser').get('value').trim(),
                        pass = $('login_txtpass').get('value').trim(),
                        result = $('login_txtcomp').get('value').trim();

                        if(user!= '' && pass != '')
                        {
                            if(result==this.captcha_result ){

                                this.rq.send('access_to='+ user +'&for= '+pass );
                            }
                            else{

                                $('login_txtpass').set('value','');
                                $('login_txtcomp').set('value','');
                                alert('Verifique la comprobación');
                                $('login_txtpass').focus();
                                this.generar_captcha();
                            }
                        }
                        else{
                            $('login_txtuser').focus();
                            alert('Especifique su Usario y Contraseña por favor');
                        }
                  
                    
             }.bind(this));
         } 
         //$('login_txtcomp').addEvent('keypress', function(event){  Helpers.form.onlyNumber(event) } );
        
         $('login_txtuser').set('value','');
         $('login_txtpass').set('value','');
         $('login_txtcomp').set('value','');
         $('login_txtuser').focus();
     }, 
     
     generar_captcha: function(){
          
          var n1 = $random(1,10)
              n2 = $random(1,10),
              ops = ['','+','x'],  
              op = $random(1,2), 
              rs = 0;
          
          rs = (op == 1) ? (n1 + n2) : (n1 * n2);
          
          $('login_spcuestion').set('html','Cuanto es '+n1.toString()+' '+ ops[op]+' '+n2.toString()+' ?');
         
          this.captcha_result  = rs;
     },
   
     to: function(){
          
          document.location.href=this.url;
 
          // ir a la direccion
     },
     
     cerrar_sesion: function(){
         
     }
     
    
});
 