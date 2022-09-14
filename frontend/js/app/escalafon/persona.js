/*
 * @Module:  Sisgerhu.persona
 *
 *
 * */

var Persona = {
  /*
        component: new Laugo.Component({
            }),
    */

  window_upload_file: null,

  Stores: {
    individuos: null,
  },

  /* REMOTE MODELOS
   *
   *   _M.trabajador =  new Laugo.BackEnd.Model({ 'controller' : 'escalafon', 'actions' : ['registrar','eliminar']
   *
   *    });
   *
   *   Escalafon._M.trabajador.process('registrar',data);
   *
   *   Laugo.BackEnd.ProvideData.connect();
   *
   * */

  _M: {
    get_info_dni: new Laugo.Model({
      connect: "escalafon/get_info_dni",

      message_function: function (msm, obj) {}, // Especifico message funcition para evitar llamar a message function x defecto
    }),

    registrar: new Laugo.Model({
      connect: "escalafon/registrar_nuevo",
    }),

    registrar_situlaboral: new Laugo.Model({
      connect: "historiallaboral/registrar_situlab",
    }),

    actualizar_situlaboral: new Laugo.Model({
      connect: "historiallaboral/actualizar_situacionlaboral",
    }),

    actualizar_infopersonal: new Laugo.Model({
      connect: "escalafon/actualizar_infopersonal",
    }),

    registrar_comision: new Laugo.Model({
      connect: "escalafon/registrar_comision",
    }),

    registrar_licencia: new Laugo.Model({
      connect: "escalafon/registrar_licencia",
    }),

    registrar_vacaciones: new Laugo.Model({
      connect: "escalafon/registrar_vacaciones",
    }),

    registrar_descansomedico: new Laugo.Model({
      connect: "escalafon/registrar_descansomedico",
    }),

    registrar_vacaciones: new Laugo.Model({
      connect: "escalafon/registrar_vacaciones",
    }),

    registrar_permiso: new Laugo.Model({
      connect: "escalafon/registrar_permiso",
    }),

    registrar_falta: new Laugo.Model({
      connect: "escalafon/registrar_falta",
    }),

    registrar_tardanza: new Laugo.Model({
      connect: "escalafon/registrar_tardanza",
    }),

    registrar_academico: new Laugo.Model({
      connect: "escalafon/registrar_academico",
    }),

    registrar_familiar: new Laugo.Model({
      connect: "escalafon/registrar_familiar",
    }),

    activar_desactivar_estudiante: new Laugo.Model({
      connect: "escalafon/activar_desactivar_estudiante",
    }),

    /* MODELS DESACTIVATE */

    delete_situlaboral: new Laugo.Model({
      connect: "historiallaboral/delete",
    }),

    delete_comision: new Laugo.Model({
      connect: "comisionservicio/delete",
    }),

    delete_descanso: new Laugo.Model({
      connect: "descansosmedicos/delete",
    }),

    delete_licencia: new Laugo.Model({
      connect: "licencias/delete",
    }),

    delete_vacaciones: new Laugo.Model({
      connect: "vacaciones/delete",
    }),

    delete_permiso: new Laugo.Model({
      connect: "permisos/delete",
    }),

    delete_falta: new Laugo.Model({
      connect: "faltas/delete",
    }),

    delete_tardanza: new Laugo.Model({
      connect: "tardanzas/delete",
    }),

    delete_academico: new Laugo.Model({
      connect: "estudios/delete",
    }),

    delete_familiar: new Laugo.Model({
      connect: "familiares/delete",
    }),

    delete_documento: new Laugo.Model({
      connect: "archivosescalafon/delete",
    }),

    accion_estudios: new Laugo.Model({
      connect: "escalafon/ui_legajo_accion",
    }),

    cesar_trabajador: new Laugo.Model({
      connect: "historiallaboral/cesar",
    }),

    activar_directo: new Laugo.Model({
      connect: "historiallaboral/activar_directo",

      message_function: function (msm, obj) {},
    }),

    gestion_conceptos: new Laugo.Model({
      connect: "conceptos/gestion_rapida",

      message_function: function (msm, obj) {},
    }),

    
    personal_accion: new Laugo.Model({
      connect: "escalafon/personal_accion",
    }),
    estudios_accion: new Laugo.Model({
      connect: "escalafon/estudios_accion",
    }),
    capacitacion_accion: new Laugo.Model({
      connect: "escalafon/capacitacion_accion",
    }),
    laboral_accion: new Laugo.Model({
      connect: "escalafon/laboral_accion",
    }),
    meritos_accion: new Laugo.Model({
      connect: "escalafon/meritos_accion",
    }),
    demeritos_accion: new Laugo.Model({
      connect: "escalafon/demeritos_accion",
    }),
    cargaFam_accion: new Laugo.Model({
      connect: "escalafon/cargaFam_accion",
    }),
    
    /*
     *
     * var familar = new Laugo.Entiti({
     *
     *      'controler' : 'familiar'
     *
     *      connect: {
     *                   'registrar' : '',
     *                }
     *
     *
     * });
     *
     **/

    table_comisiones: new Request({
      url: "escalafon/get_comisiones",

      method: "post",

      type: "text",

      onRequest: function () {
        //  dojo.byId('').innerHTML = '<div class="loader_div1"> </div>';
        dojo.setStyle(
          dojo.query(".table_loader", dojo.byId("dvcomision_table"))[0],
          "display",
          "block"
        );
        dojo.setStyle(
          dojo.query(".table_data", dojo.byId("dvcomision_table"))[0],
          "display",
          "none"
        );
      },

      onSuccess: function (responseText) {
        var table = dojo.query(".table_data", dojo.byId("dvcomision_table"))[0];

        dojo.setStyle(
          dojo.query(".table_loader", dojo.byId("dvcomision_table"))[0],
          "display",
          "none"
        );
        dojo.setStyle(table, "display", "block");

        dojo.query(".table_data", dojo.byId("dvcomision_table"))[0].innerHTML =
          responseText;

        dojo.parser.parse(table);
      },

      onFailure: function () {},
    }),

    get_completardatos_trabajadores: new Request({
      url: "escalafon/get_trabajadores_table",

      onRequest: function () {},

      onSuccess: function (responseText) {
        dojo.byId("dvtable_trabajadores_completar").innerHTML = responseText;
      },

      onFailure: function () {},
    }),

    get_view_explorarxfechas: new Request({
      url: "escalafon/explorar_por_fechas/view",

      onRequest: function () {},

      onSuccess: function (responseText) {},

      onFailure: function () {},
    }),

    get_carreras: new Request({
      type: "json",

      method: "post",

      url: "estudios/get_carreras",

      onRequest: function () {
        app.loader_show();
      },

      onSuccess: function (responseJSON) {
        app.loader_hide();

        dijit.byId("selAcademico_carrera").store.data = [];
        if (responseJSON.length > 0) {
          responseJSON.push({ name: "No especificar", id: "999999" });
          dijit.byId("selAcademico_carrera").store.data = responseJSON;
          dijit.byId("selAcademico_carrera").set("value", "999999");
        }
      },

      onFailure: function () {},
    }),

    get_centros: new Request({
      type: "json",

      method: "post",

      url: "estudios/get_centros",

      onRequest: function () {
        app.loader_show();
      },

      onSuccess: function (responseJSON) {
        app.loader_hide();

        dijit.byId("selAcademico_centroestudios").store.data = [];
        if (responseJSON.length > 0) {
          responseJSON.push({ name: "No especificar", id: "0" });
          dijit.byId("selAcademico_centroestudios").store.data = responseJSON;
          dijit.byId("selAcademico_centroestudios").set("value", "0");
        }
      },

      onFailure: function () {},
    }),
  },

  /* VISTAS || INTERFACES  */
  _V: {
    nuevo_contrato: new Laugo.View.Window({
      connect: "contratos/nuevo",

      style: {
        width: "420px",
        height: "390px",
        "background-color": "#FFFFFF",
      },

      title: "Registro de nuevo contrato laboral",

      onLoad: function () {},

      onClose: function () {},
    }),

    nueva_situlaboral: new Laugo.View.Window({
      connect: "escalafon/ver_situlaboral_nuevo",

      style: {
        width: "600px",
        height: "480px",
        "background-color": "#FFFFFF",
      },

      title: "Registro de nueva situación laboral",

      onLoad: function () {
        // sets current dates

        var fecha = $_currentDate();

        dojo.forEach(["calhisdesde", "calhistermino"], function (cal, ind) {
          if (dijit.byId(cal) != null) dijit.byId(cal).set("value", fecha);
        });

        /*
                   if(dojo.byId('trhis_terminocontrato') != null) dojo.setStyle( dojo.byId('trhis_terminocontrato'), 'display','none');

                  
                  if ( dijit.byId('chhis_actual') != null ){
                      
                      dojo.connect( dijit.byId('chhis_actual'), 'onChange' , function(e){

                             if(dijit.byId('chhis_actual').get('checked') ){
                                  
                                 dojo.setStyle(dojo.byId('dvhis_calhasta'), 'display', 'none');
                                 dojo.setStyle( dojo.byId('trhis_terminocontrato'), 'display','table-row');
                                     
                             }
                             else{
                                dojo.setStyle(dojo.byId('dvhis_calhasta'), 'display', 'inline');
                                 dojo.setStyle( dojo.byId('trhis_terminocontrato'), 'display','none');

                                  dijit.byId('chhis_termino').set('checked', '');
                             }
                          
                     });
                     
                  }

                  if ( dijit.byId('chhis_termino') != null ){
                      
                       dojo.connect( dijit.byId('chhis_termino'), 'onChange' , function(e){
                          
                             if(dijit.byId('chhis_termino').get('checked') ){
                                  
                                  dojo.setStyle(dojo.byId('dvhis_termino'), 'display', 'none');
                                  
                             }
                             else{
                                  dojo.setStyle(dojo.byId('dvhis_termino'), 'display', 'inline');
                             }
                          
                      });
                     
                  }*/

        if (dijit.byId("chhis_actual") != null) {
          dojo.connect(dijit.byId("chhis_actual"), "onChange", function (e) {
            if (dijit.byId("chhis_actual").get("checked")) {
              dojo.setStyle(
                dojo.byId("dvtermino_contrato"),
                "display",
                "inline"
              );
            } else {
              dojo.setStyle(dojo.byId("dvtermino_contrato"), "display", "none");
              dijit.byId("chhis_termino").set("checked", "");
            }
          });
        }

        if (dijit.byId("chcarnet_presento") != null) {
          dojo.connect(
            dijit.byId("chcarnet_presento"),
            "onChange",
            function (e) {
              if (dijit.byId("chcarnet_presento").get("checked")) {
                dojo.setStyle(
                  dojo.byId("dv_carnetPresento"),
                  "display",
                  "inline"
                );
              } else {
                dojo.setStyle(
                  dojo.byId("dv_carnetPresento"),
                  "display",
                  "none"
                );
              }
            }
          );
        }

        if (dijit.byId("chhis_termino") != null) {
          dojo.connect(dijit.byId("chhis_termino"), "onChange", function (e) {
            if (dijit.byId("chhis_termino").get("checked")) {
              dojo.setStyle(dojo.byId("dvhis_termino"), "display", "none");
            } else {
              dojo.setStyle(dojo.byId("dvhis_termino"), "display", "inline");
            }
          });
        }

        if (dijit.byId("chhis_proyecto") != null) {
          dojo.connect(dijit.byId("chhis_proyecto"), "onChange", function (e) {
            if (dijit.byId("chhis_proyecto").get("checked")) {
              dojo.setStyle(dojo.byId("dvhis_proyecto"), "display", "inline");
            } else {
              dojo.setStyle(dojo.byId("dvhis_proyecto"), "display", "none");
            }
          });
        }

        Persona.Ui.reset_form_historial();

        if (dijit.byId("selhis_laboral") != null) {
          dojo.connect(dijit.byId("selhis_laboral"), "onChange", function () {
            var cv = dijit.byId("selhis_laboral").get("value");

            //dijit.byId('form_info_historial').reset();
            Persona.Ui.reset_form_historial();

            dijit.byId("selhis_laboral").set("value", cv);

            var v = dijit.byId("selhis_laboral").get("value");

            dojo.setStyle(dojo.byId("tr_carnet_cc"), "display", "none");

            if (v != 0) {
              dojo.setStyle(dojo.byId("trhis_periodo"), "display", "table-row");
              dojo.setStyle(
                dojo.byId("trhis_terminocontrato"),
                "display",
                "table-row"
              );

              dojo.setStyle(dojo.byId("trhis_doc"), "display", "table-row");
              dojo.setStyle(
                dojo.byId("trhis_infosisgedo"),
                "display",
                "table-row"
              );
              dojo.setStyle(dojo.byId("trhis_obs"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_monto"), "display", "table-row");
            }

            if (v == app._consts.sitlab_practicantes) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
            }

            if (v == app._consts.sitlab_inversiones) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              // dojo.setStyle( dojo.byId('trhis_proy'), 'display','table-row');
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }

            if (
              v == app._consts.sitlab_nombrados ||
              v == app._consts.sitlab_contratados
            ) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_plaza"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }

            if (
              v == app._consts.sitlab_contratados ||
              v == app._consts.sitlab_construccion
            ) {
              //dojo.setStyle( dojo.byId('trhis_proy'), 'display','table-row');
            }

            if (v == app._consts.sitlab_cas_func) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }

            if (v == app._consts.sitlab_cas_inv) {
              //dojo.setStyle( dojo.byId('trhis_proy'), 'display','table-row');
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }

            if (
              v == app._consts.sitlab_obrenombrados ||
              v == app._consts.sitlab_obrecontratados
            ) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              // dojo.setStyle( dojo.byId('trhis_cargo'), 'display','table-row');
            }

            if (v == app._consts.sitlab_construccion) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("tr_carnet_cc"), "display", "table-row");
              dijit
                .byId("selhis_depe")
                .set("value", app._consts.depe_ejecucion_proy);
              dijit.byId("selhis_cargo").set("value", "0");
              dijit.byId("selhis_depe").set("readOnly", true);
              dijit.byId("selhis_cargo").set("readOnly", true);
            }

            if (v == app._consts.sitlab_pensionistas) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dijit.byId("selhis_depe").set("value", "0");
              dijit.byId("selhis_cargo").set("value", "0");
              dijit.byId("selhis_depe").set("readOnly", true);
              dijit.byId("selhis_cargo").set("readOnly", true);
            }

            if (v == app._consts.sitlab_regidores) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dijit
                .byId("selhis_depe")
                .set("value", app._consts.depe_regidores);
              dijit
                .byId("selhis_cargo")
                .set("value", app._consts.cargo_regidor);
              dijit.byId("selhis_depe").set("readOnly", true);
              dijit.byId("selhis_cargo").set("readOnly", true);
            }

            if (v == app._consts.sitlab_requerimiento) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }
          });
        }
      },

      onClose: function () {},
    }),

    editar_situlaboral: new Laugo.View.Window({
      connect: "escalafon/ver_situlaboral_editar",

      style: {
        width: "600px",
        height: "480px",
        "background-color": "#FFFFFF",
      },

      title: "Editar el registro de situación laboral",

      onLoad: function () {
        // sets current dates

        if (
          dojo.query(".on_unload_window", this.wd_dialog.domNode)[0] != null
        ) {
          var msm = dojo.query(".on_unload_window", this.wd_dialog.domNode)[0]
            .value;
          app.alert(msm);
          this.close();
          return false;
        }

        var fecha = $_currentDate();

        dojo.forEach(
          ["calhisdesde", "calhishasta", "calhistermino"],
          function (cal, ind) {
            if (dijit.byId(cal) != null) dijit.byId(cal).set("value", fecha);
          }
        );

        if (dijit.byId("chhis_actual") != null) {
          dojo.connect(dijit.byId("chhis_actual"), "onChange", function (e) {
            if (dijit.byId("chhis_actual").get("checked")) {
              dojo.setStyle(
                dojo.byId("dvtermino_contrato"),
                "display",
                "inline"
              );
            } else {
              dojo.setStyle(dojo.byId("dvtermino_contrato"), "display", "none");
              dijit.byId("chhis_termino").set("checked", "");
            }
          });
        }

        if (dijit.byId("chhis_termino") != null) {
          dojo.connect(dijit.byId("chhis_termino"), "onChange", function (e) {
            if (dijit.byId("chhis_termino").get("checked")) {
              dojo.setStyle(dojo.byId("dvhis_termino"), "display", "none");
            } else {
              dojo.setStyle(dojo.byId("dvhis_termino"), "display", "inline");
            }
          });
        }

        if (dijit.byId("chcarnet_presento") != null) {
          dojo.connect(
            dijit.byId("chcarnet_presento"),
            "onChange",
            function (e) {
              if (dijit.byId("chcarnet_presento").get("checked")) {
                dojo.setStyle(
                  dojo.byId("dv_carnetPresento"),
                  "display",
                  "inline"
                );
              } else {
                dojo.setStyle(
                  dojo.byId("dv_carnetPresento"),
                  "display",
                  "none"
                );
              }
            }
          );
        }

        if (dijit.byId("chhis_proyecto") != null) {
          dojo.connect(dijit.byId("chhis_proyecto"), "onChange", function (e) {
            if (dijit.byId("chhis_proyecto").get("checked")) {
              dojo.setStyle(dojo.byId("dvhis_proyecto"), "display", "inline");
            } else {
              dojo.setStyle(dojo.byId("dvhis_proyecto"), "display", "none");
            }
          });
        }

        Persona.Ui.reset_form_historial();

        if (dijit.byId("selhis_laboral") != null) {
          dojo.connect(dijit.byId("selhis_laboral"), "onChange", function () {
            var cv = dijit.byId("selhis_laboral").get("value");

            //dijit.byId('form_info_historial').reset();
            Persona.Ui.reset_form_historial();

            dijit.byId("selhis_laboral").set("value", cv);

            dojo.setStyle(dojo.byId("tr_carnet_cc"), "display", "none");

            var v = dijit.byId("selhis_laboral").get("value");

            if (v != 0) {
              dojo.setStyle(dojo.byId("trhis_periodo"), "display", "table-row");
              dojo.setStyle(
                dojo.byId("trhis_terminocontrato"),
                "display",
                "table-row"
              );

              dojo.setStyle(dojo.byId("trhis_doc"), "display", "table-row");
              dojo.setStyle(
                dojo.byId("trhis_infosisgedo"),
                "display",
                "table-row"
              );
              dojo.setStyle(dojo.byId("trhis_obs"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_monto"), "display", "table-row");
            }

            if (v == app._consts.sitlab_practicantes) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
            }

            if (v == app._consts.sitlab_inversiones) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              //dojo.setStyle( dojo.byId('trhis_proy'), 'display','table-row');
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }

            if (
              v == app._consts.sitlab_nombrados ||
              v == app._consts.sitlab_contratados
            ) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_plaza"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }

            if (
              v == app._consts.sitlab_contratados ||
              v == app._consts.sitlab_construccion
            ) {
              //dojo.setStyle( dojo.byId('trhis_proy'), 'display','table-row');
            }

            if (v == app._consts.sitlab_cas_func) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }

            if (v == app._consts.sitlab_cas_inv) {
              //dojo.setStyle( dojo.byId('trhis_proy'), 'display','table-row');
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }

            if (
              v == app._consts.sitlab_obrenombrados ||
              v == app._consts.sitlab_obrecontratados
            ) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              // dojo.setStyle( dojo.byId('trhis_cargo'), 'display','table-row');
            }

            if (v == app._consts.sitlab_construccion) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("tr_carnet_cc"), "display", "table-row");
              dijit
                .byId("selhis_depe")
                .set("value", app._consts.depe_ejecucion_proy);
              dijit.byId("selhis_cargo").set("value", "0");
              dijit.byId("selhis_depe").set("readOnly", true);
              dijit.byId("selhis_cargo").set("readOnly", true);
            }

            if (v == app._consts.sitlab_pensionistas) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dijit.byId("selhis_depe").set("value", "0");
              dijit.byId("selhis_cargo").set("value", "0");
              dijit.byId("selhis_depe").set("readOnly", true);
              dijit.byId("selhis_cargo").set("readOnly", true);
            }

            if (v == app._consts.sitlab_regidores) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dijit
                .byId("selhis_depe")
                .set("value", app._consts.depe_regidores);
              dijit
                .byId("selhis_cargo")
                .set("value", app._consts.cargo_regidor);
              dijit.byId("selhis_depe").set("readOnly", true);
              dijit.byId("selhis_cargo").set("readOnly", true);
            }

            if (v == app._consts.sitlab_requerimiento) {
              dojo.setStyle(dojo.byId("trhis_depe"), "display", "table-row");
              dojo.setStyle(dojo.byId("trhis_cargo"), "display", "table-row");
            }
          });
        }

        /* CARGANDO DATOS */

        var temp = function () {
          dijit.byId("calhistermino").constraints.min = dijit
            .byId("calhisdesde")
            .get("value");
        };

        dijit
          .byId("selhis_laboral")
          .set("value", dojo.byId("hdhledit_tipo").value);

        if (dojo.byId("hdhledit_vigente").value == "1") {
          dijit.byId("chhis_actual").set("checked", "checked");
          dijit.byId("chhis_actual").set("readOnly", true);
        }

        fecha = dojo.byId("hdhledit_fechaini").value;
        parts = fecha.split("/");
        fecha = new Date(
          parts[2],
          parseInt(parts[1]) - 1,
          parseInt(parts[0]),
          0,
          0,
          0
        );
        dijit.byId("calhisdesde").set("value", fecha);

        if (dojo.byId("hdhledit_fechatermino").value == "") {
          dijit.byId("chhis_termino").set("checked", "checked");
        } else {
          fecha = dojo.byId("hdhledit_fechatermino").value;
          parts = fecha.split("/");
          fecha = new Date(
            parts[2],
            parseInt(parts[1]) - 1,
            parseInt(parts[0]),
            0,
            0,
            0
          );
          dijit.byId("calhistermino").set("value", fecha);
        }

        if (document.getElementById("hdhledit_presento_carnet") != null) {
          if (dojo.byId("hdhledit_presento_carnet").value == "1") {
            dijit.byId("chcarnet_presento").set("checked", "checked");
          }

          fecha = dojo.byId("hdhledit_carnetfecha_inicio").value;
          parts = fecha.split("/");
          fecha = new Date(
            parts[2],
            parseInt(parts[1]) - 1,
            parseInt(parts[0]),
            0,
            0,
            0
          );
          dijit.byId("calCarnetDesde").set("value", fecha);

          fecha = dojo.byId("hdhledit_carnetfecha_fin").value;
          parts = fecha.split("/");
          fecha = new Date(
            parts[2],
            parseInt(parts[1]) - 1,
            parseInt(parts[0]),
            0,
            0,
            0
          );
          dijit.byId("calCarnetHasta").set("value", fecha);
        }

        dijit
          .byId("txthis_plaza")
          .set("value", dojo.byId("hdhledit_plaza").value);

        dijit
          .byId("txthis_documento")
          .set("value", dojo.byId("hdhledit_doc").value);

        dijit.byId("txthis_aut").set("value", dojo.byId("hdhledit_aut").value);

        if (dojo.byId("hdhledit_depe").value != "0")
          dijit
            .byId("selhis_depe")
            .set("value", dojo.byId("hdhledit_depe").value);

        if (dojo.byId("hdhledit_cargo").value != "0")
          dijit
            .byId("selhis_cargo")
            .set("value", dojo.byId("hdhledit_cargo").value);

        dijit
          .byId("dvhis_descripcion")
          .set("value", dojo.byId("hdhledit_descripcion").value);

        dojo.connect(dijit.byId("calhisdesde"), "onChange", temp);
      },

      onClose: function () {},
    }),

    retirar: new Laugo.View.Window({
      connect: "historiallaboral/retirar",

      style: {
        width: "400px",
        height: "290px",
        "background-color": "#FFFFFF",
      },

      title: " Cesar al Trabajador",

      onLoad: function () {
        if (
          dojo.query(".on_unload_window", this.wd_dialog.domNode)[0] != null
        ) {
          var msm = dojo.query(".on_unload_window", this.wd_dialog.domNode)[0]
            .value;
          app.alert(msm);
          this.close();
          return false;
        }

        var fecha = $_currentDate();
        dijit.byId("calcese_fecha").set("value", fecha);
      },

      onClose: function () {},
    }),

    view_situacion_laboral: new Laugo.View.Window({
      connect: "historiallaboral/view",

      style: {
        width: "420px",
        height: "550px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion del registro de situacion laboral ",

      onLoad: function () {
        Persona.Ui.Forms.trabajador_info.ready();
      },

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_comision: new Laugo.View.Window({
      connect: "comisionservicio/view",

      style: {
        width: "450px",
        height: "350px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion de la comision de servicio ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_vacaciones: new Laugo.View.Window({
      connect: "vacaciones/view",

      style: {
        width: "450px",
        height: "350px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion de vacaciones",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_licencia: new Laugo.View.Window({
      connect: "licencias/view",

      style: {
        width: "450px",
        height: "400px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion de la licencia ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_descanso: new Laugo.View.Window({
      connect: "descansosmedicos/view",

      style: {
        width: "450px",
        height: "400px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion del Descanso Medico ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_permiso: new Laugo.View.Window({
      connect: "permisos/view",

      style: {
        width: "450px",
        height: "450px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion del Permiso  ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_falta: new Laugo.View.Window({
      connect: "faltas/view",

      style: {
        width: "420px",
        height: "350px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion sobre la Inasistencia  ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_tardanzas: new Laugo.View.Window({
      connect: "tardanzas/view",

      style: {
        width: "420px",
        height: "350px",
        "background-color": "#FFFFFF",
      },

      title: "Tardanza  ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_estudios: new Laugo.View.Window({
      connect: "estudios/view",

      style: {
        width: "450px",
        height: "450px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion de la actividad academica   ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_familiar: new Laugo.View.Window({
      connect: "familiares/view",

      style: {
        width: "450px",
        height: "450px",
        "background-color": "#FFFFFF",
      },

      title: "Informacion del familiar registrado   ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    view_adjuntar_doc: new Laugo.View.Window({
      connect: "archivosescalafon/view_subir",

      style: {
        width: "400px",
        height: "180px",
        "background-color": "#FFFFFF",
      },

      title: " Adjuntar documento  ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    registrar_nuevo: new Laugo.View.Window({
      connect: "escalafon/ui_registrar",

      style: {
        width: "650px",
        height: "550px",
        "background-color": "#FFFFFF",
      },

      title: "Registrar nueva Persona ",

      onLoad: function () {
        Persona.Ui.Forms.trabajador_info.ready();

        var fecha = $_currentDate();

        dojo.forEach(["calhisdesde", "fip_fechanac"], function (cal, ind) {
          if (dijit.byId(cal) != null) dijit.byId(cal).set("value", fecha);
        });

        dijit.byId("calhistermino").set("value", null);

        /*
                    var ubicacion_selects  = new Laugo.SelectsChain({ 
                                                                                                                                                //value, label
                            selects : [{element: 'fip_departamento', connect: 'peru/departamentos', struct: ['departamento','nombre'], data: {}, callback: null},
                                       {element: 'fip_provincia',    connect: 'peru/provincias',    struct: ['provincia','nombre'], data: {},  callback: null},
                                       {element: 'fip_distrito',     connect: 'peru/distritos',     struct: ['distrito','nombre'], data: {'departamento' : dijit.byId('fip_departamento')},  callback: null}]

                        }); // sys7.mpi.gob.pe/ws/catal
                     
                     (function(){ 
                              
                            var f_depar_def = function(){
                                
                                dijit.byId('fip_departamento').set('value', app._consts.ubica_moquegua );
                            }
                            
                            var f_prov_def =  function(){
                                 
                                dijit.byId('fip_provincia').set('value',  app._consts.ubica_ilo_provincia );
                            }
                            
                            var f_dis_def =  function(){
                                
                                dijit.byId('fip_distrito').set('value', app._consts.ubica_ilo_distrito );
                            }
                      
                            var ubicacion_selects  = new Laugo.SelectsChain({ 
                                                                                                                                                    //value, label
                                selects : [{element: 'fip_departamento', connect: 'peru/departamentos', struct: ['departamento','nombre'], data: {}, callback: null},
                                           {element: 'fip_provincia',    connect: 'peru/provincias',    struct: ['provincia','nombre'], data: {},  callback: null},
                                           {element: 'fip_distrito',     connect: 'peru/distritos',     struct: ['distrito','nombre'], data: {'departamento' : dijit.byId('fip_departamento')},  callback: null}],

                                
                                first_fns: [f_depar_def,f_prov_def,f_dis_def]
                             
                                } ); // sys7.mpi.gob.pe/ws/catal
                  
                  })();
                  
                      */
      },

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    gestion_rapida_deconceptos: new Laugo.View.Window({
      connect: "escalafon/gestion_rapida_de_conceptos",

      style: {
        width: "420px",
        height: "390px",
        "background-color": "#FFFFFF",
      },

      title: "Gestión rápida de conceptos",

      onLoad: function () {
        var conceptos = dojo.query(".ch_gestion_concepto"),
          estado = false;

        dojo.forEach(conceptos, function (el, ind) {
          dojo.connect(dijit.byNode(el), "onClick", function () {
            data = {};
            data.concepto = dojo.query(".concepto", el.parentNode)[0].value;
            data.trabajador = dojo.query(".trabajador", el.parentNode)[0].value;
            data.estado = dijit.byNode(el).get("value") ? "1" : "0";

            Persona._M.gestion_conceptos.process(data);
          });
        });
      },

      onClose: function () {},
    }),

    full_info_persona: new Laugo.View.Window({
      connect: "escalafon/ui_full_info",

      style: {
        width: "850px",
        height: "550px",
        "background-color": "#FFFFFF",
      },

      title: " Informacion del empleado ",

      onLoad: function () {
        if (
          dijit.byId("wd_newpersona_container") == null ||
          dijit.byId("wd_newpersona_container") == undefined
        ) {
          return false;
        }

        dijit
          .byId("wd_newpersona_container")
          .resize({ w: 830, h: 450, l: 0, t: 0 }, { w: 1000, h: 400 });

        var panel1 = dijit.byId("wd_newpersona_tab_tab1");
        var tabs = dijit.byId("wd_newpersona_tabs");
        var panel2 = dijit.byId("wd_newpersona_tab_tab2");

        tabs.selectChild(panel2);
        tabs.selectChild(panel1);

        Persona.Ui.Forms.trabajador_info.ready(2);
        Persona.Ui.Forms.trabajador_info.disabled();
        Persona.Ui.Forms.trabajador_info.store();

        var empkey = dojo.byId("hdPersInfoKey").value;

        Persona.Ui.ready_new_licencia();

        if (dojo.byId("perscomision_loaded") != null) {
          (function () {
            var first = true;

            var data = dojo.byId("dv_fullinfo_default_data");

            var f_depar_def = function () {
              var nd = dojo.query(".departamento", data)[0];

              if (nd != "" && nd != null)
                dijit.byId("fip_departamento").set("value", nd);
            };

            var f_prov_def = function () {
              var nd = dojo.query(".provincia", data)[0];

              if (nd != "" && nd != null)
                dijit.byId("fip_provincia").set("value", nd);
            };

            var f_dis_def = function () {
              var nd = dojo.query(".distrito", data)[0];

              if (nd != "" && nd != null)
                dijit.byId("fip_distrito").set("value", nd);
            };
          })();
        }

        if (dijit.byId("selfaltar_tipo") != null) {
          dojo.connect(dijit.byId("selfaltar_tipo"), "onChange", function (e) {
            if (dijit.byId("selfaltar_tipo").get("value") == "1") {
              dojo.setStyle(dojo.byId("trfaltar_time"), "display", "table-row");
            } else {
              dojo.setStyle(dojo.byId("trfaltar_time"), "display", "none");
            }
          });
        }

        if (dojo.byId("table_academico_data") != null) {
          var rows = dojo.query(".row_form", dojo.byId("table_academico_data"));

          var tipo_estudios = {
            especial: 3,
            primaria: 5,
            secundaria: 7,
            tecnico: 9,
            tecnico_superior: 11,
            universitario: 13,
            bachiller: 14,
            titulado: 15,
            maestria: 17,
            grado_maestria: 18,
            doctorado: 20,
            grado_doctorado: 21,
          };

          var only_view_rows = function (o_v) {
            //

            if ((o_v != undefined && o_v != null) || o_v == false) {
              if (o_v == false) o_v = [];
              o_v[o_v.length] = 0;
            } else {
              o_v = [];
            }

            dojo.forEach(rows, function (r, ix) {
              dojo.setStyle(rows[ix], "display", "table-row");

              if (o_v.length > 0) {
                if (dojo.indexOf(o_v, ix) == -1) {
                  dojo.setStyle(rows[ix], "display", "none");
                }
              }
            });
          };

          if (dijit.byId("selAcademicoTiEst") != null) {
            dojo.connect(
              dijit.byId("selAcademicoTiEst"),
              "onChange",
              function () {
                var v = dijit.byId("selAcademicoTiEst").get("value");

                switch (Number(v)) {
                  case tipo_estudios.primaria:
                  case tipo_estudios.secundaria:
                    only_view_rows([1, 11, 13]);
                    break;
                  case tipo_estudios.especial:
                  case tipo_estudios.tecnico:
                    only_view_rows([1, 4, 11, 13]);
                    break;
                  case tipo_estudios.tecnico_superior:
                    only_view_rows([2, 3, 11, 13]);
                    break;
                  case tipo_estudios.universitario:
                    only_view_rows([2, 3, 11, 13]);
                    break;
                  case tipo_estudios.bachiller:
                  case tipo_estudios.titulado:
                    only_view_rows([2, 3, 11, 13]);
                    break;
                  case tipo_estudios.maestria:
                  case tipo_estudios.doctorado:
                    only_view_rows([2, 4, 11, 13]);
                    break;
                  case tipo_estudios.grado_maestria:
                  case tipo_estudios.grado_doctorado:
                    only_view_rows([2, 4, 11, 13]);
                    break;
                  case 0:
                    only_view_rows(false);
                    break;
                }
              }
            );

            only_view_rows(false);
          }
        }

        if (dijit.byId("selfami_parentesco") != null) {
          dojo.connect(
            dijit.byId("selfami_parentesco"),
            "onChange",
            function (e) {
              if (dijit.byId("selfami_parentesco").get("value") == "4") {
                dojo.setStyle(
                  dojo.byId("trfami_estudiante"),
                  "display",
                  "table-row"
                );
              } else {
                dojo.setStyle(
                  dojo.byId("trfami_estudiante"),
                  "display",
                  "none"
                );
              }
            }
          );
        }

        require([
          "dgrid/List",
          "dgrid/OnDemandGrid",
          "dgrid/Selection",
          "dgrid/editor",
          "dgrid/Keyboard",
          "dgrid/extensions/Pagination",
          "dojo/_base/declare",
          "dojo/store/JsonRest",
          "dojo/store/Observable",
          "dojo/store/Cache",
          "dojo/store/Memory",
          "dojo/domReady!",
        ], function (
          List,
          Grid,
          Selection,
          editor,
          Keyboard,
          Pagination,
          declare,
          JsonRest,
          Observable,
          Cache,
          Memory
        ) {
          window.escalafon_grid = declare([Grid, Selection, Keyboard]);

          if (dojo.byId("dvhistorial_table") != null) {
            var store_historial_lab = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_historial",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            var colums = {
              // you can declare columns as an object hash (key translates to field)
              // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
              col1: { label: "#" },
              col2: { label: "Situacion Laboral", sortable: false },
              area: { label: "Area", sortable: false },
              cargo: { label: "Cargo", sortable: false },
              col4: { label: "Inicio de Contrato", sortable: false },
              col5: { label: "Termino de Contrato", sortable: false },
              col6: { label: "Fecha Cese", sortable: false },
              col7: { label: "Activo", sortable: false },
              /*   col4: {label: 'Hasta', sortable: false},
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

            Persona.Ui.Grids.historial_laboral = new window.escalafon_grid(
              {
                loadingMessage: "Cargando",
                store: store_historial_lab,
                getBeforePut: false,
                columns: colums,
              },
              "dvhistorial_table"
            );
          }

          if (dojo.byId("dvcomision_table") != null) {
            var store_comisiones = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_comisiones",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var data = dojo.formToObject("formComisionesSearch");
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    for (x in data) {
                      query[x] = data[x];
                    }

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            store_comisiones.view_persona = function (emp) {
              /* No se carga la data en el grid, falta algo */

              return store_comisiones.query({ empleado: emp }, {});
            };

            var colums = {
              // you can declare columns as an object hash (key translates to field)
              // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
              col1: { label: "#" },
              col2: { label: "Documento", sortable: false },
              col3: { label: "Destino", sortable: false },
              col4: { label: "Desde", sortable: false },
              col5: { label: "Hasta", sortable: false },
              col6: { label: "Motivo", sortable: false },
            };

            Persona.Ui.Grids.comisiones = new window.escalafon_grid(
              {
                loadingMessage: "Cargando",
                store: store_comisiones,
                getBeforePut: false,
                columns: colums,
              },
              "dvcomision_table"
            );
          }

          if (dojo.byId("dvvacaciones_table") != null) {
            var store_vacaciones = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_vacaciones",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var emp = dojo.byId("hdPersInfoKey").value;
                    query.empleado = emp;

                    var data = dojo.formToObject("formVacacionesSearch");
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    for (x in data) {
                      query[x] = data[x];
                    }

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            store_vacaciones.view_persona = function (emp) {
              /* No se carga la data en el grid, falta algo */

              return store_vacaciones.query({ empleado: emp }, {});
            };

            var colums = {
              // you can declare columns as an object hash (key translates to field)
              // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
              col1: { label: "#" },
              col2: { label: "Documento", sortable: false },
              col3: { label: "Desde", sortable: false },
              col4: { label: "Hasta", sortable: false },
              col5: { label: "Descripcion", sortable: false },
            };

            Persona.Ui.Grids.vacaciones = new window.escalafon_grid(
              {
                loadingMessage: "Cargando",
                store: store_vacaciones,
                getBeforePut: false,
                columns: colums,
              },
              "dvvacaciones_table"
            );
          }

          if (dojo.byId("dvdescanso_table") != null) {
            var store_descansos = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_descansosmedicos",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var data = dojo.formToObject("formDescansosSearch");
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    for (x in data) {
                      query[x] = data[x];
                    }

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            store_descansos.view_persona = function (emp) {
              /* No se carga la data en el grid, falta algo */

              return store_descansos.query({ empleado: emp }, {});
            };

            var colums = {
              // you can declare columns as an object hash (key translates to field)
              // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
              col1: { label: "#" },
              col2: { label: "Documento", sortable: false },
              col3: { label: "Desde", sortable: false },
              col4: { label: "Hasta", sortable: false },
              col6: { label: "Dias", sortable: false },
              col7: { label: "Des/Obs", sortable: false },
            };

            Persona.Ui.Grids.descansos = new window.escalafon_grid(
              {
                loadingMessage: "Cargando",
                store: store_descansos,
                getBeforePut: false,
                columns: colums,
              },
              "dvdescanso_table"
            );
          }

          if (dojo.byId("dvlicencia_table") != null) {
            var store_licencias = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_licencias",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var data = dojo.formToObject("formLicenciasSearch");
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    for (x in data) {
                      query[x] = data[x];
                    }

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            var colums = {
              // you can declare columns as an object hash (key translates to field)
              // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
              col1: { label: "#" },
              col2: { label: "Documento", sortable: false },
              col3: { label: "Desde", sortable: false },
              col4: { label: "Hasta", sortable: false },
              col5: { label: "Tipo", sortable: false },
              col6: { label: "Observacion", sortable: false },
              /* col6: {
                                                                                                                                label: "Step", 
                                                                                                                                sortable: false,
                                                                                                                                field: "_item",
                                                                                                                                formatter: testFormatter
                                                                                                                            },
                                                                                                                    col7: 'Column 5' */
            };

            Persona.Ui.Grids.licencias = new window.escalafon_grid(
              {
                loadingMessage: "Cargando",
                store: store_licencias,
                getBeforePut: false,
                columns: colums,
              },
              "dvlicencia_table"
            );
          }

          if (dojo.byId("dvpermiso_table") != null) {
            var store_permisos = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_permisos",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            var colums = {
              // you can declare columns as an object hash (key translates to field)
              // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
              col1: { label: "#" },
              col2: { label: "Documento", sortable: false },
              col3: { label: "Fecha ", sortable: false },
              hsalida: { label: "Hora de salida", sortable: false },
              hingreso: { label: "Hora de regreso", sortable: false },
              col5: { label: "Motivo", sortable: false },
              /* col6: {
                                                                                            label: "Step", 
                                                                                            sortable: false,
                                                                                            field: "_item",
                                                                                            formatter: testFormatter
                                                                                        },
                                                                                col7: 'Column 5' */
            };

            Persona.Ui.Grids.permisos = new window.escalafon_grid(
              {
                loadingMessage: "Cargando",
                store: store_permisos,
                getBeforePut: false,
                columns: colums,
              },
              "dvpermiso_table"
            );
          }

          if (dojo.byId("dvfaltas_table") != null) {
            var store_faltar = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_faltas",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            var colums = {
              // you can declare columns as an object hash (key translates to field)
              // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
              col1: { label: "#" },
              col2: { label: "Desde", sortable: false },
              col3: { label: "Hasta", sortable: false },
              col4: { label: "Justificada", sortable: false },
              col5: { label: "Justificacion", sortable: false },
              col6: { label: "Observacion", sortable: false },
              /*   col4: {label: 'Hasta', sortable: false},
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

            Persona.Ui.Grids.faltas = new window.escalafon_grid(
              {
                loadingMessage: "Cargando",
                store: store_faltar,
                getBeforePut: false,
                columns: colums,
              },
              "dvfaltas_table"
            );
          }

          if (dojo.byId("dvtardanza_table") != null) {
            var store_tardanza = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_tardanzas",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            var colums = {
              col1: { label: "#" },
              col2: { label: "Dia", sortable: false },
              col3: { label: "Minutos", sortable: false },
              col4: { label: "Observacion", sortable: false },
            };

            Persona.Ui.Grids.tardanzas = new window.escalafon_grid(
              {
                store: store_tardanza,
                getBeforePut: false,
                columns: colums,
              },
              "dvtardanza_table"
            );
          }

          if (dojo.byId("dvfamiliar_table") != null) {
            var store_familiares = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_familiares",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            var colums = {
              col1: { label: "#" },
              col2: { label: "Parentesco", sortable: false },
              col3: { label: "Nombre", sortable: false },
              col4: { label: "Dni", sortable: false },
              col5: { label: "Estado Civil", sortable: false },
              col6: { label: "Edad", sortable: false },
              col7: { label: "Estudiante", sortable: false },
            };

            Persona.Ui.Grids.familia = new window.escalafon_grid(
              {
                store: store_familiares,
                getBeforePut: false,
                columns: colums,
              },
              "dvfamiliar_table"
            );
          }

          if (dojo.byId("dvacademico_table") != null) {
            var store_academico = Observable(
              Cache(
                JsonRest({
                  target: app.getUrl() + "escalafon/get_academico",
                  idProperty: "id",
                  sortParam: "oby",
                  query: function (query, options) {
                    var emp = dojo.byId("hdPersInfoKey").value;

                    query.empleado = emp;

                    return JsonRest.prototype.query.call(this, query, options);
                  },
                }),
                Memory()
              )
            );

            var colums = {
              // you can declare columns as an object hash (key translates to field)
              // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
              col1: { label: "#" },
              col2: { label: "Tipo", sortable: false },
              col3: { label: "Especialidad/Nombre", sortable: false },
              col4: { label: "Centro de Estudio", sortable: false },
              col5: { label: "Periodo", sortable: false },
              /*  col5: {label: 'Fecha Fin', sortable: false},
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

            Persona.Ui.Grids.academico = new window.escalafon_grid(
              {
                store: store_academico,
                getBeforePut: false,
                columns: colums,
              },
              "dvacademico_table"
            );
          }
        });

        //   Sisgedo.ready(  dijit.byId(dojo.query('#trhis_doc input[name="documento"]')[0].id) , dojo.query('#trhis_infosisgedo .info_sisgedo')[0]   );

        /*  if(dojo.query('#trcs_doc input[name="documento"]')[0] != null )  Sisgedo.ready(  dijit.byId(dojo.query('#trcs_doc input[name="documento"]')[0].id) , dojo.query('#trcs_infosisgedo .info_sisgedo')[0]   );   

                if(dojo.query('#trli_doc input[name="documento"]')[0] != null )  Sisgedo.ready(  dijit.byId(dojo.query('#trli_doc input[name="documento"]')[0].id) , dojo.query('#trli_infosisgedo .info_sisgedo')[0]   );   
                
                if(dojo.query('#trperm_doc input[name="documento"]')[0] != null )  Sisgedo.ready(  dijit.byId(dojo.query('#trperm_doc input[name="documento"]')[0].id) , dojo.query('#trperm_infosisgedo .info_sisgedo')[0]   ); 

*/
        // if( Persona.Ui.Grids.comisiones != null) Persona.Ui.Grids.comisiones.refresh();

        if (Persona.Ui.Grids.historial_laboral != null) {
          //Persona.Ui.Grids.comisiones.store.view_persona('6');
          Persona.Ui.Grids.historial_laboral.refresh();
        }

        if (Persona.Ui.Grids.comisiones != null) {
          //Persona.Ui.Grids.comisiones.store.view_persona('6');
          Persona.Ui.Grids.comisiones.refresh();
        }

        if (Persona.Ui.Grids.descansos != null) {
          //Persona.Ui.Grids.descansos.store.view_persona('6');
          Persona.Ui.Grids.descansos.refresh();
        }

        if (Persona.Ui.Grids.licencias != null) {
          //Persona.Ui.Grids.comisiones.store.view_persona('6');
          Persona.Ui.Grids.licencias.refresh();
        }

        if (Persona.Ui.Grids.permisos != null) {
          //Persona.Ui.Grids.comisiones.store.view_persona('6');
          Persona.Ui.Grids.permisos.refresh();
        }
        if (Persona.Ui.Grids.faltas != null) {
          //Persona.Ui.Grids.comisiones.store.view_persona('6');
          Persona.Ui.Grids.faltas.refresh();
        }

        if (Persona.Ui.Grids.tardanzas != null) {
          //Persona.Ui.Grids.comisiones.store.view_persona('6');
          Persona.Ui.Grids.tardanzas.refresh();
        }

        if (Persona.Ui.Grids.academico != null) {
          //Persona.Ui.Grids.comisiones.store.view_persona('6');
          Persona.Ui.Grids.academico.refresh();
        }

        if (Persona.Ui.Grids.familia != null) {
          //Persona.Ui.Grids.comisiones.store.view_persona('6');
          Persona.Ui.Grids.familia.refresh();
        }

        if (dijit.byId("selAcademico_carrera") != null) {
          require([
            "dojo/_base/declare",
            "dojo/store/JsonRest",
            "dojo/store/Observable",
            "dojo/store/Cache",
            "dojo/store/Memory",
            "dojo/domReady!",
          ], function (declare, JsonRest, Observable, Cache, Memory) {
            var memoryStore = new Memory({});
            var restStore = new JsonRest({
              target: app.getUrl() + "escalafon/provide/especialidades",
              idProperty: "value",
              sortParam: "oby",
              query: function (query, options) {
                return dojo.store.JsonRest.prototype.query.call(
                  this,
                  query,
                  options
                );
              },
            });

            Persona.Stores.especialidades = new Cache(restStore, memoryStore);
            Persona.Stores.especialidades.query({});

            dijit
              .byId("selAcademico_especialidad")
              .set("store", Persona.Stores.especialidades);

            dojo.connect(
              dijit.byId("selAcademico_centroestudios"),
              "onChange",
              function (evt) {
                var codigo = dijit
                  .byId("selAcademico_centroestudios")
                  .get("value");
                //console.log('centro: '+codigo);
                Persona._M.get_carreras.send({ view: codigo });
              }
            );

            dojo.connect(
              dijit.byId("selAcademicoTiEst"),
              "onChange",
              function (evt) {
                var codigo = dijit.byId("selAcademicoTiEst").get("value");
                Persona._M.get_centros.send({ view: codigo });
              }
            );
          });
        }

        var fecha = $_currentDate();

        dojo.forEach(["calfamiliar_fecnac"], function (cal, ind) {
          if (dijit.byId(cal) != null) dijit.byId(cal).set("value", fecha);
        });
      },

      onClose: function () {
        //    alert('ventana cerrada');
        return true;
      },
    }),

    full_info_persona_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_full",

      style: {
        width: "950px",
        height: "750px",
        "background-color": "#FFFFFF",
      },

      title: " Informacion del empleado ",

      onLoad: function () {},

      onClose: function () {
        //    alert('ventana cerrada');

        return true;
      },
    }),

    agregar_estudios_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_estudios",

      style: {
        width: "650px",

        "background-color": "#FFFFFF",
      },

      title: " AGREGAR ESTUDIO ",

      onLoad: function () {
       
      },

      onClose: function () {
        return true;
      },
    }),

    actualizar_estudios_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_estudios",

      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },

      title: " ACTUALIZAR ESTUDIO ",

      onLoad: function () {},

      onClose: function () {
        return true;
      },
    }),

    agregar_capacitacion_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_capacitacion",
  
      style: {
        width: "650px",
        
        "background-color": "#FFFFFF",
      },
  
      title: " AGREGAR CAPACITACION ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),
  
    actualizar_capacitacion_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_capacitacion",
  
      style: {
        width: "650px",
        
        "background-color": "#FFFFFF",
      },
  
      title: " ACTUALIZAR CAPACITACION ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),
  
   
    agregar_laboral_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_laboral",
  
      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },
  
      title: " AGREGAR EXPERIENCIA LABORAL ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),
  
    actualizar_laboral_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_laboral",
  
      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },
  
      title: " ACTUALIZAR  EXPERIENCIA LABORAL ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),

    agregar_meritos_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_meritos",
  
      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },
  
      title: " AGREGAR MERITOS ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),
  
    actualizar_meritos_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_meritos",
  
      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },
  
      title: " ACTUALIZAR MERITOS ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),
  
    agregar_demeritos_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_demeritos",
  
      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },
  
      title: " AGREGAR DEMERITOS ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),
  
    actualizar_demeritos_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_demeritos",
  
      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },
  
      title: " ACTUALIZAR DEMERITOS ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),

    agregar_cargaFam_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_cargaFam",
  
      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },
  
      title: " AGREGAR CARGA FAMILIAR ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),
  
    actualizar_cargaFam_legajo: new Laugo.View.Window({
      connect: "escalafon/ui_legajo_cargaFam",
  
      style: {
        width: "650px",
       
        "background-color": "#FFFFFF",
      },
  
      title: " ACTUALIZAR CARGA FAMILIAR ",
  
      onLoad: function () {},
  
      onClose: function () {
        return true;
      },
    }),
    
  },

  

  /* FUNCTIONS ALIAS MODELS  */

  registrar: function (data) {
    return this._M.registrar.process(data);
  },

  actualizar_info: function (data, rfo) {
    rfo = rfo != null ? rfo : false;

    return this._M.actualizar_infopersonal.process(data, rfo);
  },

  consultar_dni: function (data) {
    return this._M.get_info_dni.process(data, true);
  },

  get_comisiones: function (data) {
    // this._M.table_comisiones.send(data);
  },

  /* Functions alias views */

  get_view_nuevo: function () {
    Persona._V.registro_nuevo_view.send({});
  },

  get_table_trabajadores: function () {
    Persona._M.get_completardatos_trabajadores.send({});
  },

  onupload_file_adjunto: function (data) {
    // console.log('Data');
    if (data.exito == "1") {
      dojo.setStyle(dojo.byId("dv_files_buttonsubir"), "display", "block");
      dojo.setStyle(dojo.byId("dv_files_subir"), "display", "none");
      Persona._V.view_adjuntar_doc.close();
      Persona._V[Persona.window_upload_file].refresh();

      alert("Archivo adjuntado correctamente");
    } else {
      dojo.setStyle(dojo.byId("dv_files_buttonsubir"), "display", "none");
      dojo.setStyle(dojo.byId("dv_files_subir"), "display", "block");

      var myDialog = new dijit.Dialog({
        title: "Atenci&oacute;n",
        content:
          '<div style="padding: 4px 4px 4px 4px;"> ' + data.error + "</div>",
        style: "width: 350px",
      });
      myDialog.show();
    }
  },

  quitar_documento: function (doc_id, window) {
    console.log("quitar_documento :" + doc_id);

    if (confirm("Realmente desea eliminar este archivo? ")) {
      if (Persona._M.delete_documento.process({ key: doc_id })) {
        Persona._V[window].refresh();
      }
    }
  },
  personal_accion: function (data) {
    return this._M.personal_accion.store(data);
  },
  estudios_accion: function (data) {
    return this._M.estudios_accion.store(data);
  },

  capacitacion_accion: function (data) {
    return this._M.capacitacion_accion.store(data);
  },

  laboral_accion: function (data) {
    return this._M.laboral_accion.store(data);
  },

  meritos_accion: function (data) {
    return this._M.meritos_accion.store(data);
  },

  demeritos_accion: function (data) {
    return this._M.demeritos_accion.store(data);
  },

  cargaFam_accion: function (data) {
    return this._M.cargaFam_accion.store(data);
  },
  
};
