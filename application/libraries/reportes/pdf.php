<?php

 
Class PDF extends Fpdf{

    public $paginador = false;

    public $logo = true;

    function Header()
    {
        
        if($this->logo)
        { 
            $this->SetFont('Arial','',4);
     
            $this->Image('frontend/images/institucion/logo_institucion.jpg',  5,  5, 48,12);

            $this->setXY(17,14);        

            $this->Cell( 260 ,4, 'Sistema Integral de Getion de Recursos Humanos ', ' ',0,'L',false); 
            $this->ln();
        }
    }
    
    function Footer()
    {
        
        //$this->SetFont('Arial','',4);
       	$this->SetY(-14);		
    	$this->SetFont('Arial', 'B', 5);
    	$this->Cell(-3);
         
        $hora = date('H').':'.date('i').':'.date('s');
        $dia = date('d').'/'.date('m').'/'.date('Y');
         
         
        if($this->paginador)
        { 
         //   $this->Cell(100,4,'Generado el  '.date('d/m/Y').' a las '.date("H:i:s"), 0,0,'L'); 
         //   $this->ln();
            $this->Cell(0,5,'Pagina '.$this->PageNo().' de {nb} ',0,0,'C'); 
        
        }
    }
    
    
    public function print_celda($value, $x, $y, $dim, $alto_linea = 4, $align='C', $max_chars = 50)
    {
           
          //  $this->Cell(40,6, 'adas dasdasda ','TRL',0,'C', false);
            $this->setXY($x,$y);

            $lines = wordwrap($value, $max_chars ,'_');
            $lines = explode('_',$lines);
            foreach($lines as $k => $line){
                if(strlen($line)> $max_chars){
                    $lines[$k] = chunk_split($line, ($max_chars-3)).'..';
                }
            }
            
            
            $px = $x;
            $py = $y;
            
            $n_lineas = sizeof($lines);
            
            $border = 'TRL';
            
            if( $n_lineas > 1){

                foreach($lines as $k =>  $line){

                    if($k == 0){

                       $border = 'TRL';
                    }
                    else{
                        $border = 'RL';
                    }
                    
                    $this->Cell($dim,$alto_linea, $line,$border,0, $align, false);
                    $py+=$alto_linea;
                    $this->setXY($px,$py);
                }
            }
            else{
                $this->Cell($dim,$alto_linea, $value,$border,0, $align , false);
            }
            
            return $n_lineas;

    } 
    
    
    
    public function emparejar($xi,$yi, $n_lineas, $altura_linea )
    {
        
          $this->setXY($xi,$yi);
          
          for($i = 0; $i<=$n_lineas; $i++){
              
              
                    $border = ($i == $n_lineas) ? 'BRL' : 'RL';
                    
                    $this->Cell($dim,$alto_linea, $line,$border,0,'C', false);
                    $py+=$alto_linea;
                    $this->setXY($px,$py);
              
              
          }
          
        
    }
 
     
}