<?php

class quintacategoria
{
    
    private $_CI;
 
    public function __construct()
    {

        $this->_CI=& get_instance(); 
        $this->_CI->load->database(); 
     
    }
 
}