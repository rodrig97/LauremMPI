<?php

class hojadiariotipo extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'hatd_id',
                                    'code'  => 'hatd_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'hatd_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojadiario_tipo';
    protected $_PREF_TABLE= 'hojadiariodetalle'; 
    
    protected $sql_base =  "";
     
    public function __construct()
    {          
        parent::__construct();

      
    }


 
    public function get( $plati_id,  $registro_desde_hoja = false,  $para_importar = false,  $registrar_marcaciones_diario = false, $mostrar_en_resumen  = false, $enhorario_pordefecto = false  )
    {

        $params = array($plati_id);

        $sql  =" SELECT * 
                 FROM planillas.hojadiario_tipo ht
                 INNER JOIN planillas.hojadiario_tipo_plati htp ON ht.hatd_id = htp.hatd_id AND  htp.htp_estado = 1 AND htp.plati_id = ? 
                 WHERE hatd_estado = 1 ";
       
 
        if($registro_desde_hoja == true )
        {
            $sql.=" AND htp_edicionenhoja = 1 ";
        }

        if($para_importar == true)
        {
            $sql.=" AND htp.vari_id != 0 ";
        }
 
        if($registrar_marcaciones_diario == true )
        {
            $sql.=" AND htp.htp_registrar_marcacion_horas = 1 ";
        }

        if($mostrar_en_resumen == true )
        {
            $sql.=" AND htp.htp_mostrarenresumen = 1 ";
        }

        if($enhorario_pordefecto == true )
        {
            $sql.=" AND htp.htp_enhorario_estadodia_pordefecto = 1 ";
        }

        $sql.=" ORDER BY hatd_orden";
        
        return $this->_CI->db->query($sql, $params)->result_array();

    }
 

    public function getAll( $params = array() ) 
    {
        $params = array();

        $sql  =" SELECT * 
                 FROM planillas.hojadiario_tipo ht
                 WHERE hatd_estado = 1 ORDER BY hatd_orden asc ";
         
        
        return $this->_CI->db->query($sql, $params)->result_array();

    }   
 
    public function get_all_plati($plati_id, $params = array() )
    {

        $sql  =" SELECT * 
                 FROM planillas.hojadiario_tipo ht
                 LEFT JOIN planillas.hojadiario_tipo_plati htp ON ht.hatd_id = htp.hatd_id AND  htp.htp_estado = 1 AND htp.plati_id = ? 
                 WHERE hatd_estado = 1 
               ";
               
        $values = array($plati_id);

        if($params['visualizar'] == '1')
        {
            $sql.=" AND htp.htp_id is not NULL ";
        }        

        $sql.="  ORDER BY hatd_orden asc ";   

        return $this->_CI->db->query($sql, $values)->result_array();
    }

    public function get_params($params = array())
    {
         $values = array();

         $sql = " SELECT * 
                  FROM planillas.hojadiario_tipo ht 
                  WHERE hatd_estado = 1 ";

         if( trim($params['label']) != '')         
         { 
            $sql.=" AND  hatd_label = ? ";
            $values[] = $params['label'];
         }

         $sql.=" ORDER BY hatd_orden asc ";
          
         
         return $this->_CI->db->query($sql, $values)->result_array();   
    }

    public function view($hatd_id = 0)
    {
        $sql =" SELECT * FROM planillas.hojadiario_tipo ht WHERE ht.hatd_id = ? "; 

        $rs = $this->_CI->db->query($sql, array($hatd_id))->result_array();  
        
        return $rs[0];
    }

    public function zxxget($tipo = '1', $edicion_en_hoja = '', $plati_id = '0', $solo_para_importar = false, $solo_registrar_marcaciones = false  )
    {

        $params = array($plati_id);

        $sql  =" SELECT * FROM planillas.hojadiario_tipo ht
                 INNER JOIN planillas.hojadiario_tipo_plati htp ON ht.hatd_id = htp.hatd_id AND  htp.htp_estado = 1 AND htp.plati_id = ? 
                 WHERE hatd_estado = 1 ";
      
        if($tipo !='' )
        {
            $sql.=" AND hatd_tipo = ? ";
            $params[] = $tipo;
        }
 
        if($edicion_en_hoja !='' )
        {
            $sql.=" AND htp_edicionenhoja = ? ";
            $params[] = $edicion_en_hoja;
        }

        if($solo_para_importar == true)
        {
            $sql.=" AND htp.vari_id != 0 ";
        }
        

        if($solo_registrar_marcaciones == true )
        {
            $sql.=" AND htp.htp_registrar_marcacion_horas = 1 ";
        }

        $sql.=" order by hatd_orden";
        
        return $this->_CI->db->query($sql, $params)->result_array();

    }


}
