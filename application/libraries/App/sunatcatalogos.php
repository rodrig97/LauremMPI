<?php

class sunatcatalogos extends Table{
    
    protected $_FIELDS=array(   
                         
                            );
     
    protected $_SCHEMA = 'sunat_estructuras';
    
    private $_tables = array();  

    public function __construct()
    {
          
        parent::__construct();
            
        $this->tables  = array( 

                'ocupacion_sector_publico'           => array('tabla10_ocupacion_sectorpublico', false ),
                'regimen_pensionario'                => array('tabla11_regimenpensionario', true),
                'tipo_contrato'                      => array('tabla12_tipocontrato', false),
                'periocidad_remuneracion'            => array('tabla13_periocidad_remuneracion', false),
                'entidades_prestadores_saludeps'     => array('tabla14_entidades_prestadores_saludeps', false),
                'situacion_trabajador'               => array('tabla15_situacion_trabajador', false),
                'tipopago'                           => array('tabla16_tipopago', false),
                'motivobaja'                         => array('tabla17_motivobaja', false),
                'categoria_ocupacional'              => array('tabla24_categoria_ocupacional', true),
                'convenio_doble_tributacion'         => array('tabla25_convenio_doble_tributacion', false),
                'emisor_doc'                         => array('tabla25_emisor_doc', false),
                'codigofono'                         => array('tabla29_codigofono', false),
                'ocupacion_sectorprivado'            => array('tabla30_ocupacion_sectorprivado', false),
                'pliegopresupuestal'                 => array('tabla31_pliegopresupuestal', true),
                'regimendesalud'                     => array('tabla32_regimendesalud', false),
                'regimenlaboral'                     => array('tabla33_regimenlaboral', true),
                'tipodoc'                            => array('tabla3_tipodoc', false),
                'nacionalidad'                       => array('tabla4_nacionalidad', false),
                'via'                                => array('tabla5_via', false),
                'zona'                               => array('tabla6_zona', false),
                'tipotrabajador'                     => array('tabla8_tipotrabajador', true),
                'niveleducativo'                     => array('tabla9_niveleducativo'),
                'establecimientos'                   => array('establecimientos')
        );  
 
    }

    public function get($catalogo)
    {
 
        if(in_array( $catalogo, array_keys($this->tables) ))
        {
            $p = $this->tables[$catalogo];
        }
        else
        {
             return array();
        }

        $tabla = $p[0];

        $sql = " SELECT * FROM sunat_estructuras.".$tabla." WHERE estado = 1 "; 
        
        if($p[1] === true )
        {   
            if(INSTITUCION_PUBLICA == '1')
            {

                $sql.=" AND publico = '1' ";
            }
            else if(INSTITUCION_PUBLICA == '0')
            {
                $sql.=" AND privado = '1' ";
            }
            else 
            {
                $sql.=" AND otras_entidades = '1' ";
            }
        }

        $sql.=" ORDER BY codigo ";


        return $this->_CI->db->query($sql, array())->result_array();

    }

}