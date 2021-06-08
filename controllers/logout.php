<?php
require_once('../vendor/autoload.php');
include '../models/venta.php';
$venta = new Venta();

session_start();
$idUsu = $_SESSION['usuario'];

if($_POST['funcion']=='datos'){
    date_default_timezone_set('America/Bogota');
    $fecha2 = date('Y-m-d H:i:s');
    $nfecha =  str_replace(':','-',$fecha2);


    $json=array();
    $json[]=array(
        'idUsu' => $idUsu,
        'fecha' => $nfecha
    );
    $jsonstring = json_encode($json);
    echo $jsonstring;

}


/* REPORTE VENTA PDF */
if($_POST['funcion']=='rep_venta'){

    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');
    $fecha2 = date('Y-m-d');
  
    $html = '

        <header>
            <h1>Reporte venta</h1>
            <div id="project">
            </div>
        </header>
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Producto</th>
                    <th>Cant</th>
                    <th>sub</th>
                  
                </tr>
            </thead>
            <tbody>
    ';

    $venta->venta_diaria($idUsu);
    $contador = 0;
    foreach ($venta->objetos as $objeto) {
        $contador++;
    
        $html.='
            <tr>
                <th class="service">'.$objeto->id_venta.'</th>
                <th class="service">'.$objeto->producto.'</th>
                <th class="service">'.$objeto->cantidad.'</th>
                <th class="service">'.$objeto->subtotal.'</th>
                
            </tr>
        ';      
    }

    $html.='
            </tbody>
        </table>
    ';

    $venta->venta_dia_vendor($idUsu);
    foreach ($venta->objetos as $objeto) {
        $html.='
            <h1>Total en ventas:<br>$'.$objeto->venta_dia_vendor.'</h1>
            <h5>Vendedor: '.$_SESSION['nom'].'</h5>
            <h5>Reporte generado el: '.$fecha.'</h5>
        ';
    }

    $id_venta = $_POST['usuario'];
    $fecha2 = $_POST['fecha'];
    // $css = file_get_contents("../css/pdf.css");
    $mpdf = new \Mpdf\Mpdf();
    // $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [50, 800]]);
    // $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
    // $mpdf->Output("../pdf/pdf-".$_POST['funcion'].".pdf","F");
    $mpdf->Output("../pdf/pdf-".$id_venta.$fecha2.".pdf","F");


    $json=array();
    $json[]=array(
        'idUsu' => $idUsu,
        'fecha' => $fecha
    );

    $jsonstring = json_encode($json);
    echo $jsonstring;

    session_start();
    session_destroy();
    header("Location: ../index.php");

}