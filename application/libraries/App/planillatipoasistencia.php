<?php
 
class planillatipoasistencia extends Table
{
     
    protected $_FIELDS=array(   
                                    'id'          => 'hoplac_id',
                                    'code'        => 'hoplac_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'hoplac_estado'
                            );
    
    protected $_SCHEMA     = 'planillas';
    protected $_TABLE      = 'hojaasistencia_plati_config';
    protected $_PREF_TABLE = 'platihoaconfig'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

    public function registrar($plati_id, $params = array())
    {
        
        $sql = " UPDATE planillas.hojaasistencia_plati_config 
                 SET hoplac_estado = 0, hoplac_fecupdate = now() 
                 WHERE hoplac_estado = 1 AND plati_id = ? ";

        $rs = $this->_CI->db->query($sql, array($plati_id));

        $values = array();

        $values['plati_id'] = $plati_id;
  
        $values['tipo_registro_asistencia'] = ( trim($params['tipo_registro_asistencia']) != '' ? trim($params['tipo_registro_asistencia']) : '0' ); 
        $values['registro_asistencia_diario'] = ( trim($params['registro_asistencia_diario']) != '' ? trim($params['registro_asistencia_diario']) : '0' ); 
        $values['hora_asistencia_pordefecto'] = ( trim($params['hora_asistencia_pordefecto']) != '' ? trim($params['hora_asistencia_pordefecto']) : '0' ); 
        $values['cierre_tareo_manual'] = ( trim($params['cierre_tareo_manual']) != '' ? trim($params['cierre_tareo_manual']) : '0' ); 
        $values['activar_horalimite'] = ( trim($params['activar_horalimite']) != '' ? trim($params['activar_horalimite']) : '0' ); 
        $values['dia_tolerancia_edicion'] = ( trim($params['dia_tolerancia_edicion']) != '' ? trim($params['dia_tolerancia_edicion']) : '0' ); 
        $values['hora_tolerancia_dia_edicion'] = ( trim($params['hora_tolerancia_dia_edicion']) != '' ? trim($params['hora_tolerancia_dia_edicion']) : '0' ); 
        $values['diario_tipo_horatrabajadas'] = ( trim($params['diario_tipo_horatrabajadas']) != '' ? trim($params['diario_tipo_horatrabajadas']) : '0' );      
        $values['maximo_marcaciones'] = ( trim($params['maximo_marcaciones']) != '' ? trim($params['maximo_marcaciones']) : '0' );      
        $values['grupo_trabajadores'] = ( trim($params['grupo_trabajadores']) != '' ? trim($params['grupo_trabajadores']) : '0' );      
        $values['categoria_noespecificar'] = ( trim($params['categoria_noespecificar']) != '' ? trim($params['categoria_noespecificar']) : '0' );      
        $values['ajustar_marcaciones_alhorario'] = ( trim($params['ajustar_marcaciones_alhorario']) != '' ? trim($params['ajustar_marcaciones_alhorario']) : '0' );      
        $values['importacion_buscar_por_ap'] = ( trim($params['importacion_buscar_por_ap']) != '' ? trim($params['importacion_buscar_por_ap']) : '0' );      
        $values['biometrico_habilitado'] = ( trim($params['biometrico_habilitado']) != '' ? trim($params['biometrico_habilitado']) : '0' );      
  
        $rs = parent::registrar($values);
    
        return ($rs) ? true : false;

    }


    public function get($plati_id = 0)
    {

        $sql = " SELECT * FROM planillas.hojaasistencia_plati_config WHERE plati_id = ? AND hoplac_estado = 1 LIMIT 1";

        $rs = $this->_CI->db->query($sql, array($plati_id))->result_array();

        return $rs[0];

    }


}
