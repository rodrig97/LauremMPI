var Afps = {


  last_row : null,

	_M : {

      actualizar : new Laugo.Model({
              connect : 'afps/actualizar'
      })

	},

	_V : {

		gestionar : new Laugo.View.Window({
            
             connect : 'afps/gestionar',
              
              style : {
                   width :  '900px',
                   height:  '400px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Gestionar AFPS ',
              
              onLoad: function(){

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

      console.log('Editar');

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


        var cell          = btn.domNode.parentNode.parentNode;
        var row           = cell.parentNode; 
        var dv_containers = dojo.query('.textbox_afp', row);

        var c = '';
        if(confirm('Realmente desea guardar los cambios?')){


           dojo.forEach(dv_containers, function(dv,ind){       
        
              c+='_' + dijit.byNode( dojo.query('.formelement-50-12',dv)[0] ).get('value');

           });

           c = row.id+''+c;
           //console.log(row.id+''+c);

           if(Afps._M.actualizar.process({data: c})){

              Afps._V.gestionar.refresh();
           }

        }

  },

  btn_cancel_reglon : function(btn, evt){


      var cell          = btn.domNode.parentNode.parentNode;
      var row           = cell.parentNode; 
      
      this.deshabilitar_row(row);

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