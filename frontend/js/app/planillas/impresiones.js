
var Impresiones = {
    
     _M : {
         
     },
     
     _V : {
         
         
          preview : new Laugo.View.Window({
               
              connect : 'impresiones/view/',
              
              style : {
                   width :  '850px',
                   height:  '540px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Previsualización ',
              
              onLoad: function()
              {
                  
                  dojo.byId('frmpreviewpdf').submit();
              },
              
              onClose: function()
              {
                  return true;
              }
              
          })
         
     },
     
     Ui : {
         
         btn_previewboleta_click : function(btn,evt){
             
             
             var detalle_key = dojo.byId('plaempdetpro_id').value;
             Impresiones._V.preview.load({'mode' : 'boleta_de_pago', 'detalle' : detalle_key});
         }, 

         
         btn_masivoboletas_click : function(btn,evt){
             
             
             var planilla_key = dojo.byId('hdviewplanilla_id').value;
             Impresiones._V.preview.load({'mode' : 'reporte_masivo', 'planilla' : planilla_key});
         },

         btn_resumenplanilla_click : function(btn,evt){
             
             
             var planilla_key = dojo.byId('hdviewplanilla_id').value;
             Impresiones._V.preview.load({'mode' : 'resumen_planilla', 'planilla' : planilla_key});
         },

         btn_resumendetallado_click : function(btn,evt){
             
             
             var planilla_key = dojo.byId('hdviewplanilla_id').value;
             Impresiones._V.preview.load({'mode' : 'resumen_planilla_detallado', 'planilla' : planilla_key});
         },


         btn_resumencontable_click : function(btn,evt){
             
             
             var planilla_key = dojo.byId('hdviewplanilla_id').value;
             Impresiones._V.preview.load({'mode' : 'resumen_contable', 'planilla' : planilla_key});
         },


         btn_resumenpresupuestal_planilla_click : function(btn,evt){
              
             var data = dojo.formToObject('form_afectacion_datos');
             var planilla_key = dojo.byId('hdviewplanilla_id').value;
             
             Impresiones._V.preview.load({'mode' : 'resumen_presupuestal_planilla', 'planilla' : planilla_key});
         }
        
         
     }
    
}


