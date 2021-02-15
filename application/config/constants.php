<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
 

define('SYSTEM_MODULE_ID',3);
define('SYSTEM_COOKIE_USER_NAME', 'laurem');

define('INSTITUCION_PUBLICA', '1'); // 1 Si 0 No
define('UI_FRONTEND','claro');

define('RUC_INSTITUCION', '20154491873' );
define('SEC_EJEC', '301498' );

define('MOSTRAR_FECHAGENERADO_BOLETA', false); 

define('CONECCION_AFECTACION_PRESUPUESTAL', true);
define('PRESUPUESTAL_SOLO_LECTURA', true);
define('MOSTRAR_DOMINICAL_BOLETAS', true );
define('MOSTRAR_UNDIADES_EN_BOLETA', false );
define('MOSTRAR_RUC_EN_BOLETA', TRUE );
define('BANCO_NACION_DBF', TRUE );
define('BANCO_NACION_TXT', TRUE);
define('TAMANIO_PLANILLA_IMPRESA', 'A3');
define('BOLETA_MULTIPLELABEL_OCUPACION', false);


define('INSTITUCION_AREARH_DOCUMENTO', '-SGRRHH-GAF-MPI');
define('INSTITUCION_DESCRIPCION_JEFE_AREA','La Sub Gerente de Recursos Humanos de la Municipalidad Provincial de Ilo');
define('INSTITUCION_CIUDAD','Ilo');

define('PLANILLA_SEMANA', true); 

define('NUMERO_VARIABLES_IMPORTADOR_EXCEL', 15);
define('PLANILLA_CUENTABANCARIA', true);

define('AFP_QUITARINVALIDEZ_AUTOMATICO', true);
  
define('ESTADOPLANILLA_MINIMO_BANCOS', 3 );
define('ESTADOPLANILLA_MINIMO_AFP', 3 );
define('ESTADOPLANILLA_MINIMO_SUNAT', 3 );
define('ESTADOPLANILLA_MINIMO_REPORTEADOR', 3 ); 

define('PERMISO_RESTRINGICO_MENSAJE', 'Usted no tiene permiso para realizar esta operación, consulte al administrador del sistema');


define('ASISTENCIAS_MODULO_PERMISOS', true);
define('ASISTENCIAS_DIARIO_APLICARHASTA', true); 
define('TURNOS_DIA', 2);

define('REGISTROPLANILLAS_DECLARARSUNAT', '1');

define('SUPER','6b0547d1e54887d606a4b87167faa752');  
define('SUPERID','0');


define('NUMERO_REGISTROS_PARTE_IMPORTACION', 10);

define('NUMERO_CARACTERES_DNI', 8);

define('AFP_EDADINVALIDEZ_LIMITE',65);

define('MULTIPLE_UIT_QUINTA', 7);
define('MONTO_UIT', 4400 );

define('TIPOOPERANDO_VARIABLE','1');
define('TIPOOPERANDO_CONCEPTO','2');
define('TIPOOPERANDO_CONSTANTE','3');


define('CONCEPTOSUNAT_QUINTA','605');

define('CONCEPTOSUNAT_CUARTA','618');


define('PDT_PENSION_ONP','02');

define('PDT_PENSION_OTRO','99');

define('PDT_PAIS','604');
define('PDT_DOCUMENTO_DNI', '01');
define('PDT_CODIGOESTABLECIMIENTO', '0000');

define('PDT_TASA_SCTR','1.55');
define('PDT_EDUCACION_UNIVERSITARIA_COPMPLETA', '13' );
define('PDT_TIPOPAGO_DEPOSITOCUENTA', '2');
define('PDT_OCUPACION_TRABAJADORCONSTRUCCION', '868020');
define('PDT_PERUANO','9589');

define('TIPOCONCEPTO_INGRESO','1');
define('TIPOCONCEPTO_DESCUENTO','2');
define('TIPOCONCEPTO_APORTACION','3');

define('TIPOCONCEPTO_NOAFECTO','0');

define('VARIABLE_PERSONALIZABLE_NO','0');
define('VARIABLE_PERSONALIZABLE_GESTIONDATOS','1');
define('VARIABLE_PERSONALIZABLE_PLANILLA','2');
define('VARIABLE_PERSONALIZABLE_AMBOS','3');


define('MODOVERCALENDARIO_XDEFECTO', '1');
define('MODOVERCALENDARIO_HORASASISTENCIA', '2'); 
define('MODOVERCALENDARIO_TARDANZAS', '3'); 
define('MODOVERCALENDARIO_MARCACION1', '4'); 
define('MODOVERCALENDARIO_MARCACION2', '5'); 
define('MODOVERCALENDARIO_MARCACION3', '6'); 
define('MODOVERCALENDARIO_MARCACION4', '7'); 
define('MODOVERCALENDARIO_PERMISOS', '8'); 
/*
define('TIPOVARIABLE_CALCULO','1');
define('TIPOVARIABLE_PROCESO','2');
 */

define('ESTADOPLANILLA_ANULADA','0');
define('ESTADOPLANILLA_ELABORADA','1');
define('ESTADOPLANILLA_PROCESADA','2');
define('ESTADOPLANILLA_FINALIZADO','3');


define('ESTADO_PLANILLA_CERRADA', ESTADOPLANILLA_FINALIZADO);

 
define('PROCENDENCIA_CONCEPTO_DEL_TRABAJADOR','1');
define('PROCENDENCIA_CONCEPTO_DEL_TIPOPLANILLA','2');
define('PROCENDENCIA_CONCEPTO_DE_LA_PLANILLA','3');
define('PROCENDENCIA_CONCEPTO_POR_ESTAR_RELACIONADO','4'); 

define('PROCENDENCIA_VARIABLE_VALOR_XDEFECTO', '1');
define('PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO', '2');
define('PROCENDENCIA_VARIABLE_VALOR_DESDE_XLS', '3');
define('PROCENDENCIA_VARIABLE_HOJAASISTENCIA', '4');
define('PROCENDENCIA_VARIABLE_SISTEMA', '5');

define('PENSION_AFP','2');
define('PENSION_SNP','1');

define('AFP_FLUJO','1');
define('AFP_SALDO','2');

define('TIPOPLANILLA_INVERSIONES' , '1' );
define('TIPOPLANILLA_NOMBRADOS' , '2' );
define('TIPOPLANILLA_CONTRATADOS' , '3' );
define('TIPOPLANILLA_OBRENOMBRADOS' , '4' );
define('TIPOPLANILLA_OBRECONTRATADOS' , '5' );
define('TIPOPLANILLA_CASINV' , '6' );
define('TIPOPLANILLA_CASFUNC' , '7' );
define('TIPOPLANILLA_PRACTICANTE' , '8' );
define('TIPOPLANILLA_CONSCIVIL' , '9' );
define('TIPOPLANILLA_REGIDORES' , '10' );
define('TIPOPLANILLA_PENSIONSITAS' , '11' );


define('OPCIONIMPRESION_MOSTRAR' , '1');
define('OPCIONIMPRESION_NOMOSTRAR', '3' );
define('OPCIONIMPRESION_MAYORACERO', '2');
 

define('TIPOLICENCIA_CONGOCE', 1);
define('TIPOLICENCIA_SINGOCE', 2);
define('TIPOLICENCIA_SINDICAL', 3);
       
// 11;"TARDANZA"      
// 61;"LICENCIA POR FALLECIMIENTO"
// 62;"SUSPENSION TEMPORAL"


define('ASISDET_NOCONSIDERADO','0');
define('ASISDET_ASISTENCIA','1');
define('ASISDET_PERMISOSPARTICULARES','2');
define('ASISDET_DESCANSOMEDICO','3');
define('ASISDET_COMISIONSERV','4');
define('ASISDET_LICENCIASIND','5');
define('ASISDET_VACACIONES','6');
define('ASISDET_FALTA','7'); 
define('ASISDET_FALTAJUSTIFICADA','8'); 
define('ASISDET_LICENCIAGOCE','9'); 
define('ASISDET_LICENCIASINGOCE','10');
define('ASISDET_LICENCIA_SINDICAL','15');
define('ASISDET_TARDANZAS','11');  
define('ASISDET_CONTRATOVENCIDO','12');  
define('ASISDET_INDEFINIDO','13');  
define('ASISDET_DESCANSOSEMANAL','20');  

define('ASISDET_CITAMEDICA','54');
define('ASISDET_LICE_ONOMASTICO','56');
define('ASISDET_CITAJUDICIAL','57');
define('ASISDET_LICE_CAPACITACION','59');
define('ASISDET_LICE_PATERNIDAD','60');
define('ASISDET_LICE_FALLECIMIENTO','61');
define('ASISDET_SUSPENSION_TEMPORAL','62');

define('ASISDET_FALTA_X_TARDANZA', '14' );

define('ASISDET_COLORCELESTE_DIANOLABORABLE',  '#deeeff');

define('HOJAASIS_ESTADO_ELABORAR', '1');
define('HOJAASIS_ESTADO_TERMINADO', '2');
define('HOJAASIS_ESTADO_IMPORTADO', '3');


define('REPORTETIPO_OTROS', '0');
define('REPORTETIPO_SUNAT', '1');
define('REPORTETIPO_SIAF', '4');

define('RESOURCEVARS_TIPOSTRABAJADOR_CS','1');


define('TIPOINDIVIDUO_TRABAJADOR','1');
define('TIPOINDIVIDUO_BENEFICIARIO','2');
 

define('QUINTA_BASE', '39');
define('QUINTA_OTROS_INGRESOS', '40');
define('QUINTA_TOTAL_INGRESOS', '41');
define('QUINTA_DESCUENTO', '42');

define('GRUPOVC_RETENCIONJUDICIAL', '14');
define('GRUPOVC_AFP', '13');
define('GRUPOVC_AFP_APORTE', '34');
define('GRUPOVC_ONP', '15');
  

define('FUENTETIPODOC_COMISION','1');

define('FUENTETIPODOC_HISTORIAL','2');

define('FUENTETIPODOC_LICENCIAS','3');
 
define('FUENTETIPODOC_PERMISOS','4');

define('FUENTETIPODOC_FALTAR','5');
define('FUENTETIPODOC_ACADEMICO','6');
 
define('FUENTETIPODOC_DESCANSOMEDICO','7');
 
define('FUENTETIPODOC_VACACIONES','8');

define('TAREO_VARIABLE_ASISTENCIA','134');
define('TAREO_IMPORTADO','1');

  
define('OPERADOR_ESCALAFON','1'); 
define('OPERADOR_PLANILLAS','2');
 
define('MINMAX_RANGO_ESTATICO','0'); 
define('MINMAX_RANGO_DINAMICO','1'); 


 
define('MINMAX_MODO_NORMAL','0'); 
define('MINMAX_MODO_ACUMULADO','1'); 
define('MINMAX_MODO_ASEGURABLE_AFP', '2');

define('ADD_CONCS_RELACIONADOS_NO_VINCULADOS_TRABAJADOR', '0');

define('CONFIG_SUPPORT_AFP_CONCEPTOS',  1 );

define('XLS_IMPORTACION_PERSONALZIADA', 99);

define('FAMILIAR_HIJO','4');


define('PLATI_AFECTARDINERO_MODO_SALDO','0');
define('PLATI_AFECTARDINERO_MODO_PREAFECTACION','1');


define('BANCO_NACION' , '4');
define('BANCO_INTERBANK' , '5');
define('BANCO_SCOTIABANK' , '6');
define('BANCO_CAJAAQP' , '7');

 
define('SALTO_LINEA','\n');

define('PLANILLA_AFECTACION_ESPECIFICADA', '1');
define('PLANILLA_AFECTACION_ESPECIFICADA_X_DETALLE', '2');

define('VARI_ASISTENCIA_CS', '391' );

define('IMPRESION_POSY_BOLETA1', 10);
define('IMPRESION_POSY_BOLETA2', 150);



define('SUPORTA_DESCUENTO_SALDOPRESUPUESTAL', 1 );


define('NACIONALIDAD_PERUANA', 1);
 

define('ASISDET_HORARIO_HORARIOALTERNATIVO', true);


define('ASIDET_PERMISO_CERRADOPORUSUARIO', false);
define('ASIDET_PERMISOFLUJO_ON', false);

define('ASIDET_PERMISOESTADO_SOLICITADO', 1);
define('ASIDET_PERMISOESTADO_AUTORIZADO', 2);
define('ASIDET_PERMISOESTADO_APROBADO',   3);
define('ASIDET_PERMISOESTADO_RETORNO',   4);



define('PERIODOPAGO_SEMANA', 1);
define('PERIODOPAGO_QUINCENA', 2);
define('PERIODOPAGO_MENSUAL', 3);
define('PERIODOPAGO_DIARIO', 4);

define('VALOR_UIT',4400);

define('TIPOCALCULO_QUINTA', '1');

 
define('QUINTACATEGORIA_PROCEDENCIA_PLANILLA', 1);

define('MODULO_QUINTA_CATEGORIA', true);
define('PROCESAR_QUINTA_CATEGORIA', true);
define('MODULO_QUINTA_CONFIGURACION_COMPLETA', true);

define('MODULO_CUARTA_CATEGORIA', true);

define('TIPOREGISTRO_ASISTENCIA_TAREO', 1);
define('TIPOREGISTRO_ASISTENCIA_MODULO', 2);


define('QUINTA_TIPO_CONCEPTO_PROYECTABLE', 1);
define('QUINTA_TIPO_CONCEPTO_NOPROYECTABLE', 2);
define('QUINTA_TIPO_CONCEPTO_AMBOS', 3);


define('QUINTA_PROYECCION_GRATIFICACION_REM', 1);
define('QUINTA_PROYECCION_GRATIFICACION_AGUINALDO', 2);

define('LUGAR_DE_TRABAJO_PALACIO' ,'1');
define('LUGAR_DE_TRABAJO_PERIFERICO' ,'2');

/*

|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_FREAD_MODE', 7070);
define('DIR_WRITE_MODE', 07070);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
define('DATE_VARIANT'							, "SERVER_NAME"); // truncates existing file data, use with care 
define('FOPEN_DATE_MODE_BITS',					'101');


/* End of file constants.php */
/* Location: ./application/config/constants.php */


 define('VARIABLE_TOTAL_HIJOS_CONSTRUCCION_CIVIL',139);
 define('EDAD_MAXIMA_ESCOLARIDAD',22);
 