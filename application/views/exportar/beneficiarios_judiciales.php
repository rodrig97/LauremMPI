<?PHP 



        $this->load->library(array('App/exportador',
                                   'App/tipoplanilla',
                                   'Catalogos/banco',
                                   'Catalogos/afp',
                                   'App/reporte', 
                                   'App/planilla','zip'));

   /*     $mes = '04'; 

        $parametros_data = array( 'anio' => $this->usuario['anio_ejecucion'] ,'mes' => $mes );*/

        $rs = $this->exportador->beneficiarios_judiciales($parametros_data);


        $nombre_file = 'beneficiarios_judiciales';

        $file_zip = 'docsmpi/exportar/'.$nombre_file.'.zip';
 
        unlink($file_zip); 

        $this->load->library('Excel');

        $this->excel = new Excel();
         
        $hoja = 0;

        $this->excel->setActiveSheetIndex($hoja);

        $this->excel->getActiveSheet()->setTitle('Beneficiarios_judiciales'); // 3500
         
        
        $f = 2;

        $total = 0;
        $fuente = '';
        $benef = '';
        $benef_total = 0;
        $nb= false;

        $this->excel->getActiveSheet()->setCellValue( ('A1'), 'FUENTE'  );
        $this->excel->getActiveSheet()->setCellValue( ('B1'), 'TRABAJADOR' );
        $this->excel->getActiveSheet()->setCellValue( ('C1'), 'DNI '  );
        $this->excel->getActiveSheet()->setCellValue( ('D1'), 'PLANILLA '  );
        $this->excel->getActiveSheet()->setCellValue( ('E1'), 'PERIODO '  ); 
        $this->excel->getActiveSheet()->setCellValue( ('F1'), 'MONTO'  );
        $this->excel->getActiveSheet()->setCellValue( ('G1'), 'BENEFICIARIO'  );
        $this->excel->getActiveSheet()->setCellValue( ('H1'), 'DNI'  );
        $this->excel->getActiveSheet()->setCellValue( ('I1'), 'CUENTA'  );
        $this->excel->getActiveSheet()->setCellValue( ('J1'), 'BANCO'  );
        $this->excel->getActiveSheet()->setCellValue( ('K1'), 'TAREA'  );
        $this->excel->getActiveSheet()->setCellValue( ('L1'), 'SIAF'  );




        foreach($rs as $reg)
        {
            
            if($fuente != $reg['fuente'])
            {

                $this->excel->getActiveSheet()->setCellValue( ('F'.$f), $total  );
                $f+=2;
                $total = 0;

                $fuente = $reg['fuente'];
            }   
 

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('A'.$f), 
                trim($reg['fuente']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );

            $this->excel->getActiveSheet()->setCellValue( ('B'.$f), $reg['trabajador']  );


            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('C'.$f), 
                trim($reg['trabajador_dni']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );

            $this->excel->getActiveSheet()->setCellValue( ('D'.$f), $reg['planilla']  );

            $this->excel->getActiveSheet()->setCellValue( ('E'.$f), $reg['periodo']  );

 

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('F'.$f), 
                trim($reg['total']),
                PHPExcel_Cell_DataType::TYPE_NUMERIC
            );

            $this->excel->getActiveSheet()->setCellValue( ('G'.$f), $reg['beneficiario']  );

            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('H'.$f), 
                trim($reg['beneficiario_dni']),
                PHPExcel_Cell_DataType::TYPE_STRING
            );


            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('I'.$f), 
                trim($reg['cuenta']),
                PHPExcel_Cell_DataType::TYPE_STRING
            ); 


            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('J'.$f), 
                trim($reg['banco']),
                PHPExcel_Cell_DataType::TYPE_STRING
            ); 


            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('K'.$f), 
                trim($reg['tarea_codigo']),
                PHPExcel_Cell_DataType::TYPE_STRING
            ); 
            $this->excel->getActiveSheet()->setCellValueExplicit(
                ('L'.$f), 
                trim($reg['siaf']),
                PHPExcel_Cell_DataType::TYPE_STRING
            ); 


            $total+=(($reg['total'] != '') ? $reg['total'] : 0);

            $benef_total+=(($reg['total'] != '') ? $reg['total'] : 0);
              
            $f++;
        }  

        $path_files = 'docsmpi/exportar/';
        $file_xls = $path_files.'beneficiarios_judiciales'.'.xls';

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save($file_xls);

        $this->zip->read_file($file_xls);    


        // ARCHIVO PARA ABONO MASIVO
        $parametros_data['banco'] = BANCO_NACION;

        $reporte = $this->exportador->beneficiarios_judiciales($parametros_data);
        
        
        if(BANCO_NACION_DBF == FALSE)
        { 
        
              ini_set('auto_detect_line_endings', false);

              $file_txt = $path_files.'judiciales_bancodelanacion.txt';  
              $file_abono_judiciales = $file_txt;           

              foreach($reporte as $reg)
              {
                  $digito = '0'; 
                  $cuenta = str_replace('-', '', trim($reg['cuenta']));
                  if(substr($cuenta,0,1) == '0') $cuenta = substr($cuenta, 1, strlen($cuenta));
                  $cuenta = substr($cuenta,0,10);

                  $deposito = explode('.',sprintf("%01.2f", $reg['total']));
                  $importe  = sprintf("%013s", $deposito[0]).$deposito[1];
                  $linea    = $digito.$cuenta.$importe;
                  $contenido_txt.= $linea."\r\n";
        
              }
        
              if(file_put_contents($file_txt, "\xEF\xBB\xBF".$contenido_txt))
              {   
                  $txt_generado = true;
              } 
              else
              {
                  $txt_generado = false;
              }
            
        }
        else
        {

              $base_dbf = $path_files.'abonosiaf_judiciales.dbf';
              $file_abono_judiciales = $base_dbf;

              $def = array(
                array("NUM_CTA",     "C", 50),
                array("TIPO_DOC",     "C", 5),
                array("NUM_DOC",      "C", 8),
                array("MONTO",    "N", 8,2),
                array("ESTADO", "C", 1)
              );

               unlink($base_dbf);

              // creación
              if (!dbase_create($base_dbf, $def)) {
                echo "Error, no se puede crear la base de datos\n";
              }
              else{ 
         
                 $db = dbase_open($base_dbf, 2);
                 
                 if($db)
                 {
                 
                       foreach($reporte as $reg)
                       {
                           
                           $cuenta = str_replace('-', '', trim($reg['cuenta']));
                           if(substr($cuenta,0,1) == '0') $cuenta = substr($cuenta, 1, strlen($cuenta));
                           $cuenta = substr($cuenta,0,10);
 
                           $cuenta = '0'.$cuenta;
                           $importe = sprintf("%01.2f", $reg['total']);
                            
                           dbase_add_record($db, array(
                                  $cuenta, 
                                  '01', 
                                  $reg['beneficiario_dni'], 
                                  $importe,
                                 'I'
                           ));   
                      
                       }
                      
                       dbase_close($db);
                  }
              }
 
            
        }
        
        $this->zip->read_file($file_abono_judiciales);    
        $this->zip->archive($file_zip);

        echo 'beneficiarios_judiciales';
