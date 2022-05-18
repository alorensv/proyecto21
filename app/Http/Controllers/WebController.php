<?php

namespace App\Http\Controllers;
use App\Mail\ContactoMailable;
use App\Models\Correo;
use App\Models\Inscripciones;
use App\Models\Permisos;
use App\Models\Prt;
use App\Models\Recall;
use App\Models\Remates;
use App\Models\RevisionesTecnicas;
use App\Models\Servicio;
use App\Models\Transporte;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


use Illuminate\Support\Facades\Storage;

class WebController extends Controller
{

    public function store(Request $request){
        /*$correo = new Correo();

        $correo->name = $request->name;
        $correo->email = $request->email;
        $correo->fono = $request->fono;
        $correo->message = $request->message;

        $correo->save();*/

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $correo = Correo::create($request->all());
        $correo = new ContactoMailable($request->all());
        Mail::to('alorensv@gmail.com')->send($correo);

        return redirect()->route('contacto')->with('info', "Mensaje enviado con éxito");
    }

    public function index($model = false){

        
    }

    public function informe(Request $request){
        $inscripcion = Inscripciones::where("ppu",$request->ppu)->first();
        $revisiones = RevisionesTecnicas::where("ppu",$request->ppu)->get();
        $permisos = Permisos::where("ppu",$request->ppu)->get();
        $remates = Remates::where("ppu",$request->ppu)->get();
        $per = Permisos::where("ppu",$request->ppu)->first();
        $transportes = Transporte::where("ppu",$request->ppu)->get();
        //print_r($inscripcion);


        return view('pages.informe', array('inscripcion'=>$inscripcion, 'revisiones'=>$revisiones, 'permisos'=>$permisos
        , 'remates'=>$remates, 'per'=>$per, 'transportes'=>$transportes));
    }

    public function busqueda(Request $request){

        $model = Servicio::where("ppu",$request->ppu)->get();
        $car = Vehiculo::where("ppu",$request->ppu)->get();
        $datos = Vehiculo::where("ppu",$request->ppu)->first(); 
        
        /* var_dump($datos);
        die; */

        return view('pages.busqueda', array('model'=>$model, 'car'=>$car, 'datos'=>$datos));
    }


    public function uploadRemates(){  //ok
        
        $filepath = 'remates/REMATES_NOVIEMBRE_2016.xlsx';

        $inputFileType = PHPExcel_IOFactory::identify($filepath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($filepath);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // Get the total number
        $highestColumn = $sheet ->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        //get data
        $data = [];
        for ($i=2; $i <= $highestRow ; $i++) {
            $cell = [];
            for ($j=0; $j <= $highestColumnIndex ; $j++) {                    
                $cell[] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();                  
            }
            $data[] = $cell;
        }
        
        foreach($data as $fila){
            echo $fila[0]."<br>";
            echo $fila[1]."<br>";

            $ppu = str_replace(' ', '', $fila[3]);  

            $model = new Remates();
            $model->rut_compania    =  $fila[0];
            $model->compania        =  $fila[1];
            $model->tipo            =  $fila[2];
            $model->ppu             =  $ppu;
            $model->marca           =  $fila[4];
            $model->modelo          =  $fila[5];
            $model->anio            =  $fila[6];
            $model->color           =  $fila[7];
            $model->tipo_siniestro  =  $fila[8];
            $model->estado          =  $fila[9];
            $model->tipo_operacion  =  $fila[10];
            $model->fecha_operacion =  $fila[11];
            $model->monto           =  $fila[12];
            $model->save();
        } 
    }

    public function uploadInscripciones(){

        ini_set("memory_limit", "-1");
        set_time_limit(0);
        
        $inscriptions = array(
            //array('filepath' => 'inscripciones/1999.xlsx'),
            /* array('filepath' => 'inscripciones/2000.xlsx'),
            array('filepath' => 'inscripciones/2001.xlsx'),
            array('filepath' => 'inscripciones/2002.xlsx'),
            array('filepath' => 'inscripciones/2003.xlsx'),
            array('filepath' => 'inscripciones/2004.xlsx'),
            array('filepath' => 'inscripciones/2005.xlsx'), */
            /*array('filepath' => 'inscripciones/2006.xlsx'),*/ 
            //array('filepath' => 'inscripciones/2007.xlsx'),
            //array('filepath' => 'inscripciones/2008.xlsx'),
            //array('filepath' => 'inscripciones/2009.xlsx'),
            //array('filepath' => 'inscripciones/2010.xlsx'), ok
            //array('filepath' => 'inscripciones/2011.xlsx'), no
            //array('filepath' => 'inscripciones/2012.xlsx'), ok
            //array('filepath' => 'inscripciones/2013.xlsx'), ok
            //array('filepath' => 'inscripciones/2014.xlsx'), ok
            //array('filepath' => 'inscripciones/2015.xlsx'), ok
            //array('filepath' => 'inscripciones/2016.xlsx'), ok
            //array('filepath' => 'inscripciones/2017.xlsx'), ok          
            //array('filepath' => 'inscripciones/2018.xlsx'), ok
            array('filepath' => 'inscripciones/20194.xlsx'), //parte 1,2,3 y 4 carga final ok
        );

        foreach($inscriptions as $inscripcion){
           echo $filepath = $inscripcion['filepath']; 
           $inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
            $objReader->setReadDataOnly(true);

            $objPHPExcel->setActiveSheetIndex(0);
            $sheet = $objPHPExcel->getSheet(0);
            echo "CANTIDAD ES: ".$highestRow = $sheet->getHighestRow(); // Get the total number
            //$highestColumn = $sheet ->getHighestColumn();
            //$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            //get data
            $data = [];
            $x=0;
            for ($i=2; $i <= $highestRow ; $i++) {
                $cell = [];
                for ($j=0; $j <= 6 ; $j++) {                    
                    $cell[] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();                  
                }

                $ppu = str_replace(' ', '', $cell[0]);           

                if( !empty($cell[0]) ){
                    $model = new Inscripciones();
                    $model->ppu             =  $ppu;
                    $model->tipo_vehiculo   =  trim($cell[1]);
                    $model->marca           =  trim($cell[2]);
                    $model->modelo          =  trim($cell[3]);
                    $model->anio            =  trim($cell[4]); 
                    $model->file            =  $filepath;            
                    $model->save();
                    $x++;
                }                
                //$data[] = $cell;
            }
            echo " total guardadas ".$x."<br>";
        }
    }
    

    public function uploadRevisiones(){

        ini_set("memory_limit", "-1");

        /* $inputFileName = 'revisiones/test.xlsx';
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->getActiveSheet()
            ->setTitle(pathinfo($inputFileName,PATHINFO_BASENAME));

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // Get the total number

        //get data
        $data = [];
        for ($i=2; $i <= $highestRow ; $i++) {
            $cell = [];
            for ($j=1; $j <= 10 ; $j++) {                    
                $cell[] = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();                  
            }
            $data[] = $cell;
        }*/
 

        $revisiones = array(
            array('filepath' => 'revisiones/SGPRT_RA2_ene-2019.xlsx'),
            /* array('filepath' => 'revisiones/SGPRT_RA2_feb-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_abr-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_may-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jun-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jul-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ago-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_sep-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_oct-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_nov-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_dic-2019.xlsx'), */
            /*array('filepath' => 'revisiones/SGPRT_RA2_ene-20192.xlsx'),
             array('filepath' => 'revisiones/SGPRT_RA2_feb-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_mar-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_abr-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_may-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jun-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jul-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ago-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_sep-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_oct-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_nov-20192.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_dic-20192.xlsx') */
        );
        

        foreach($revisiones as $revision){
           echo $filepath = $revision['filepath']; 
           $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filepath);
           $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
           $spreadsheet = $reader->load($filepath);
           /**  Set the worksheet title (to the filename that we've loaded)  **/
           $spreadsheet->getActiveSheet()
               ->setTitle(pathinfo($filepath,PATHINFO_BASENAME));
   
           $spreadsheet->setActiveSheetIndex(0);
           $sheet = $spreadsheet->getSheet(0);
           $highestRow = $sheet->getHighestRow(); // Get the total number

            //get data
            $x=0;
            $data = [];
            for ($i=2; $i <= $highestRow ; $i++) {
                $cell = [];
                for ($j=0; $j <= 18 ; $j++) {   
                    if($j == 3 || $j == 4){
                        $dateValue = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getFormattedValue();
                        $cell[] = date("Y-m-d", strtotime($dateValue));
                    }else{
                        $cell[] = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue(); 
                    }
                }

                if(!empty($cell[1])){
                    $ppu = str_replace(' ', '', $cell[1]);  

                    $model = new RevisionesTecnicas();
                    $model->cod_prt             =  $cell[0];
                    $model->ppu                 =  $ppu;
                    $model->kilometraje         =  trim($cell[2]);
                    $model->fec_revision        =  $cell[3];
                    $model->fec_vencimiento     =  $cell[4];   
                    $model->resultado_crt       =  $cell[5];  
                    $model->identificacion      =  $cell[6];  
                    $model->visual              =  $cell[7];              
                    $model->luces               =  $cell[8];  
                    $model->alineacion          =  $cell[9];  
                    $model->frenos              =  $cell[10]; 
                    $model->holguras            =  $cell[11]; 
                    $model->suspension          =  $cell[12]; 
                    $model->gases               =  $cell[13];  
                    $model->opacidad            =  $cell[14]; 
                    $model->angulo_giro         =  $cell[15]; 
                    $model->ruidos              =  $cell[16]; 
                    $model->file                =  $filepath;
                    $model->save();
                    $x++;
                }       
                //$data[] = $cell;
            }     
            echo " - total registrado :".$x;
      
        }
        
    }

    public function uploadPRT(){
        
        $filepath = 'prt/PRT_2021.xlsx';

        $inputFileType = PHPExcel_IOFactory::identify($filepath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($filepath);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // Get the total number
        $highestColumn = $sheet ->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        //get data
        $data = [];
        for ($i=5; $i <= $highestRow ; $i++) {
            $cell = [];
            for ($j=0; $j <= $highestColumnIndex ; $j++) {                    
                $cell[] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();                  
            }
            $data[] = $cell;
        }
        
        foreach($data as $fila){
            echo $fila[0]."<br>";
            echo $fila[1]."<br>";

            if( !empty($fila[0]) ){
                $model = new Prt();
                $model->cod_prt          =  $fila[0];
                $model->region           =  $fila[1];
                $model->comuna           =  $fila[2];
                $model->concesionario    =  $fila[3];
                $model->direccion        =  $fila[4];
                $model->save();
            }

            
        } 
    }

    public function uploadPermisos(){
        
        //$filepath = 'permisos/VIII/CONCEPCION_2015.xlsx';
        //$filepath = 'permisos/VIII/CONCEPCION_2016.xlsx';
        //$filepath = 'permisos/VIII/CONCEPCION_2017.xlsx';
        //$filepath = 'permisos/VIII/CONCEPCION_2018.xlsx';
        $filepath = 'permisos/VIII/CONCEPCION_2019_2021.xlsx';

        $inputFileType = PHPExcel_IOFactory::identify($filepath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($filepath);
        $objReader->setReadDataOnly(true);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // Get the total number
        //$highestColumn = $sheet ->getHighestColumn();
        //$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        echo $highestRow."<br>";
        
        //get data
        $j=0;
        $data = [];
        for ($i=5; $i <= $highestRow ; $i++) {
            $cell = [];
            for ($j=0; $j <= 6 ; $j++) {                    
                $cell[] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();                  
            }
            //echo $cell[0]." - ".$cell[3]."<br>";
            $ppu = str_replace(' ', '', $cell[0]);             

            if( !empty($cell[0]) ){
                $model = new Permisos();
                $model->ppu          =  $ppu;
                $model->region       =  $cell[1];
                $model->comuna       =  $cell[2];
                $model->anio_pc      =  $cell[3];
                $model->tipo_pago    =  $cell[4];
                $model->cod_sii      =  $cell[5];
                $model->valor_total  =  $cell[6];
                $model->save();
                $j++;
            }
            //$data[] = $cell;
        }

        echo " total es ".$j;
        
    }

    public function uploadRecall(){
        
        $filepath = 'recall/SAIP_2927.xlsx';

        $inputFileType = PHPExcel_IOFactory::identify($filepath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($filepath);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // Get the total number
        $highestColumn = $sheet ->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        //get data
        $data = [];
        for ($i=5; $i <= $highestRow ; $i++) {
            $cell = [];
            for ($j=0; $j <= $highestColumnIndex ; $j++) {                    
                $cell[] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();                  
            }
            $data[] = $cell;
        }
        
        foreach($data as $fila){
            echo $fila[0]."<br>";
            echo $fila[1]."<br>";

            if( !empty($fila[0]) ){
                $model = new Recall();
                $model->anio                =  $fila[0];
                $model->fecha_notificacion  =  $fila[1];
                $model->nombre_producto     =  $fila[2];
                $model->marca               =  $fila[3];
                $model->categoria           =  $fila[4];
                $model->subcategoria        =  $fila[5];
                $model->modelo              =  $fila[6];
                $model->lote                =  $fila[7];
                $model->pais_origen         =  $fila[8];
                $model->unidades            =  $fila[9];
                $model->totales             =  $fila[10];
                $model->descripcion         =  $fila[11];
                $model->nombre_fabricante   =  $fila[12];
                $model->nombre_importador   =  $fila[13];
                $model->sector_ocurrencia   =  $fila[14];
                $model->nombre_producto     =  $fila[15];
                $model->riesgo_asociado     =  $fila[16];
                $model->otro_riesgo         =  $fila[17];
                $model->tipo_accidentes     =  $fila[18];
                $model->num_casos_totales   =  $fila[19];
                $model->existencia_casos_chile  =  $fila[20];
                $model->existencia_casos_mundo  =  $fila[21];
                $model->fecha_sernac            =  $fila[22];
                $model->save();
            }
            
        } 
    }

    
    public function uploadTransporte(){  
        
        $filepath = 'transporte/Datos_Respuesta_AN001T0014186.xlsx';

        $inputFileType = PHPExcel_IOFactory::identify($filepath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($filepath);
        $objReader->setReadDataOnly(true);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getSheet(0);
        echo "total es: ".$highestRow = $sheet->getHighestRow(); // Get the total number
        //$highestColumn = $sheet ->getHighestColumn();
        //$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        //get data
        $x=0;
        $data = [];
        for ($i=2; $i <= $highestRow ; $i++) {
            $cell = [];
            for ($j=0; $j <= 12 ; $j++) {                    
                $cell[] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();                  
            }

            if(!empty($cell[4])){
                echo $ppu = str_replace(' ', '', $cell[4]);  

                $model = new Transporte();
                $model->region          =  $cell[0];
                $model->folio           =  $cell[1];
                $model->categoria       =  $cell[2];
                $model->tipo_servicio   =  $cell[3];
                $model->ppu             =  $ppu;
                $model->fecha_ingreso   =  $cell[5];
                $model->estado_ppu      =  $cell[6];
                $model->fecha_cancelacion  =  $cell[7];
                $model->marca           =  $cell[8];
                $model->modelo          =  $cell[9];
                $model->anio_fab        =  $cell[10];
                $model->save();
                $x++;
            }

        }

        echo " se guardaron ".$x;
        

    }


}