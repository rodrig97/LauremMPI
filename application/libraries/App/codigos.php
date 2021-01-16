<?php

class codigos extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'de_id',
                                    'code'  => '',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => ''
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'docs_codigo';
    protected $_PREF_TABLE= ''; 
        
    private $_TIPO_DOCS = array(

                                    'planilla'  =>  'planilla_codigo',
                                    'planilla'  =>  'asistencia_codigo'
                                );


    public function get_currrent_codigo($tipo_documento){


         if($this->_TIPO_DOCS[$tipo_documento] == '') return '0';   

          $sql = "  SELECT  ".$this->_TIPO_DOCS[$tipo_documento]." FROM  planillas.docs_codigo LIMIT 1";
          $rs = $this->_CI->db->query($sql, array());

          return $rs[0][$this->_TIPO_DOCS[$tipo_documento]];  

    }

    public function set_codigo($tipo_documento){


        $sql ="  UPDATE planillas.docs_codigo SET  ".$this->_TIPO_DOCS[$tipo_documento]." = ".$this->_TIPO_DOCS[$tipo_documento]." + 1  ";
        $rs = $this->_CI->db->query($sql, array());

        return $rs;

    }

}