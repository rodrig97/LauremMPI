<?php

class documento extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'doc_id',
                                    'code'  => 'doc_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'doc_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'documentos';
    protected $_PREF_TABLE= 'PLANILLA'; 
    
    
    public function __construct(){
          
        parent::__construct();
    }

    public function get_list($tipo = '0',$id){
 
        $sql = " SELECT * FROM rh.documentos WHERE doc_estado = 1 AND fuente_tipo = ? AND fuente_id = ?   ";
        $rs= $this->_CI->db->query($sql, array($tipo, $id))->result_array();

        return $rs;
    }     


    public function eliminar($doc_key){

        $id = $this->get_id($doc_key);

        $sql =" SELECT doc_path FROM rh.documentos  WHERE doc_id = ?  ";
        $rs = $this->_CI->db->query($sql, array($id))->result_array();

        $file = $rs[0]['doc_path'];
 
        unlink('./docsmpi/escalafon/'.$file);

        $sql ="UPDATE rh.documentos  SET doc_estado = 0 WHERE doc_id = ? ";
        $ok= $this->_CI->db->query($sql, array($id));

        return ($ok)  ? true : false;
    }
}