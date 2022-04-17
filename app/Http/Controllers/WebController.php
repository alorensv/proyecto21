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
use Spipu\Html2Pdf\Html2Pdf;

use Illuminate\Support\Facades\Storage;

use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;

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

        ini_set('memory_limit', '8048M');
        
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
            array('filepath' => 'inscripciones/2019.xlsx'), 
        );

        foreach($inscriptions as $inscripcion){
           $filepath = $inscripcion['filepath']; 
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
        
        $inscriptions1 = array( // ok
            array('filepath' => 'revisiones/SGPRT_RA1_ene-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ene-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ene-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ene-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ene-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ene-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ene-2021.xlsx'), 
        );

        $inscriptions2 = array( //ok
            array('filepath' => 'revisiones/SGPRT_RA1_feb-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_feb-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_feb-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_feb-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_feb-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_feb-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_feb-2021.xlsx'),
        );

        $inscriptions3 = array( // ok
            array('filepath' => 'revisiones/SGPRT_RA1_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_mar-2021.xlsx'),
        );

        $inscriptions4 = array( //ok
            array('filepath' => 'revisiones/SGPRT_RA1_abr-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_abr-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_abr-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_abr-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_abr-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_abr-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_abr-2021.xlsx'),
        );

        $inscriptions5 = array( //ok
            array('filepath' => 'revisiones/SGPRT_RA1_may-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_may-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_may-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_may-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_may-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_may-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_may-2021.xlsx'),
        );

        $inscriptions6 = array( // ok
            array('filepath' => 'revisiones/SGPRT_RA1_jun-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jun-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jun-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jun-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jun-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jun-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jun-2021.xlsx'),
        );

        $inscriptions7 = array( //ok
            array('filepath' => 'revisiones/SGPRT_RA1_jul-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jul-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jul-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jul-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jul-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jul-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_jul-2021.xlsx'),
        );

        $inscriptions8 = array(
            array('filepath' => 'revisiones/SGPRT_RA1_ago-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ago-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ago-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ago-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ago-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ago-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_ago-2021.xlsx'),
        );

        $inscriptions9 = array(
            array('filepath' => 'revisiones/SGPRT_RA1_sep-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_sep-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_sep-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_sep-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_sep-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_sep-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_sep-2021.xlsx'),
        );

        $inscriptions10 = array(
            array('filepath' => 'revisiones/SGPRT_RA1_oct-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_oct-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_oct-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_oct-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_oct-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_oct-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_oct-2021.xlsx'),
        );

        $inscriptions11 = array(
            array('filepath' => 'revisiones/SGPRT_RA1_nov-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_nov-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_nov-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_nov-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_nov-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_nov-2020.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RA1_nov-2021.xlsx'),
        );

        $inscriptions12 = array(
            array('filepath' => 'revisiones/SGPRT_RA1_dic-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_dic-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_dic-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_dic-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_dic-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA1_dic-2020.xlsx'),
        );

        //2
        $inscriptionsRA1 = array( //ok
            //array('filepath' => 'revisiones/SGPRT_RA2_ene-2015.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RA2_ene-2016.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RA2_ene-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ene-2018.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RA2_ene-2019.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RA2_ene-2020.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RA2_ene-2021.xlsx'),
        );

        $inscriptionsRA2 = array( //ok
            array('filepath' => 'revisiones/SGPRT_RA2_feb-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_feb-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_feb-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_feb-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_feb-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_feb-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_feb-2021.xlsx'),
        );

        $inscriptionsRA3 = array( // ok
            array('filepath' => 'revisiones/SGPRT_RA2_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_mar-2021.xlsx'),
        );

        $inscriptionsRA4 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_abr-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_abr-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_abr-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_abr-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_abr-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_abr-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_abr-2021.xlsx'),
        );

        $inscriptionsRA5 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_may-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_may-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_may-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_may-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_may-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_may-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_may-2021.xlsx'),
        );

        $inscriptionsRA6 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_jun-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jun-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jun-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jun-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jun-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jun-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jun-2021.xlsx'),
        );

        $inscriptionsRA7 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_jul-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jul-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jul-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jul-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jul-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jul-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_jul-2021.xlsx'),
        );

        $inscriptionsRA8 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_ago-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ago-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ago-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ago-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ago-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ago-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_ago-2021.xlsx'),
        );

        $inscriptionsRA9 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_sep-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_sep-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_sep-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_sep-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_sep-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_sep-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_sep-2021.xlsx'),
        );

        $inscriptionsRA10 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_oct-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_oct-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_oct-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_oct-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_oct-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_oct-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_oct-2021.xlsx'),
        );

        $inscriptionsRA11 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_nov-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_nov-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_nov-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_nov-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_nov-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_nov-2020.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RA2_nov-2021.xlsx'),
        );

        $inscriptionsRA12 = array(
            array('filepath' => 'revisiones/SGPRT_RA2_dic-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_dic-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_dic-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_dic-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_dic-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RA2_dic-2020.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RA2_mar-2021.xlsx'),
        );

        //3
        $inscriptionsRB1 = array(
            //array('filepath' => 'revisiones/SGPRT_RB_ene-2015.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RB_ene-2016.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RB_ene-2017.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RB_ene-2018.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RB_ene-2019.xlsx'),ok
            //array('filepath' => 'revisiones/SGPRT_RB_ene-2020.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RB_ene-2021.xlsx'), //PENDIENTEEEEEEEEEEE MEMORIA
        );

        $inscriptionsRB2 = array(
            //array('filepath' => 'revisiones/SGPRT_RB_feb-2015.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RB_feb-2016.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RB_feb-2017.xlsx'), // ERRORRR
            //array('filepath' => 'revisiones/SGPRT_RB_feb-2018.xlsx'), // ERROR
            //array('filepath' => 'revisiones/SGPRT_RB_feb-2019.xlsx'),
            //array('filepath' => 'revisiones/SGPRT_RB_feb-2020.xlsx'), ?
            //array('filepath' => 'revisiones/SGPRT_RB_feb-2021.xlsx'), //error
        );

        $inscriptionsRB3 = array(
            //array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'), ok
            //array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'), ok
             array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            /*array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'), */
        );

        $inscriptionsRB4 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );

        $inscriptionsRB5 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );

        $inscriptionsRB6 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );

        $inscriptionsRB7 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );

        $inscriptionsRB8 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );

        $inscriptionsRB9 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );

        $inscriptionsRB10 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );

        $inscriptionsRB11 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );

        $inscriptionsRB12 = array(
            array('filepath' => 'revisiones/SGPRT_RB_mar-2015.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2016.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2017.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2018.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2019.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2020.xlsx'),
            array('filepath' => 'revisiones/SGPRT_RB_mar-2021.xlsx'),
        );
        foreach($inscriptionsRB3 as $inscripcion){
           $filepath = $inscripcion['filepath']; 
           $inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
            $objReader->setReadDataOnly(true);

            $objPHPExcel->setActiveSheetIndex(0);
            $sheet = $objPHPExcel->getSheet(0);
            echo " - ".$highestRow = $sheet->getHighestRow(); // Get the total number
            
            //$highestColumn = $sheet ->getHighestColumn();
            //$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            //get data
            $x=0;
            $data = [];
            for ($i=2; $i <= $highestRow ; $i++) {
                $cell = [];
                for ($j=0; $j <= 18 ; $j++) {   
                    if($j == 3 || $j == 4){
                        $dateValue = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i)->getFormattedValue();
                        $cell[] = date("Y-m-d", strtotime($dateValue));
                    }else{
                        $cell[] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue(); 
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