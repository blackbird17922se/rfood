<?php
require_once('../vendor/autoload.php');
include '../models/venta.php';
$venta = new Venta();

session_start();
$idUsu = $_SESSION['usuario'];


switch ($_POST['funcion']) {

        /* LISTAR VENTAS GENERALES */
    case 1:
        $venta->buscar();
        $json = array();
        foreach ($venta->objetos as $objeto) {
            $json['data'][] = $objeto;
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
        break;


        /** CONSULTAR DATOS PRINCIPALES VENTA 
         * Datos como mesero,cocinero,mesa y observaciones
         * de la orden
         */
    case 2:
        // session_start();
        $idUsu = $_SESSION['usuario'];
        $venta->consultarDatosOrden($_POST['id']);
        $json = array();

        foreach ($venta->objetos as $objeto) {

            $json[] = array(
                "mesero"        => $objeto->nom_mesero,
                // "cocineroLider" => $objeto->nom_cocinero,
                'mesa'          => $objeto->nom_mesa,
                'formpago'          => $objeto->formpago,
                // "observ"        => $objeto->observ
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

        break;

        /* Total venta dia totalVentas*/
    case 3:
        $venta->venta_dia_vendor($idUsu);
        foreach ($venta->objetos as $objeto) {
            $venta_dia_vendor = $objeto->venta_dia_vendor;
        }

        $venta->venta_dia();
        foreach ($venta->objetos as $objeto) {
            $venta_dia = $objeto->venta_dia;
        }

        $venta->venta_mensual();
        foreach ($venta->objetos as $objeto) {
            $venta_mensual = $objeto->venta_mensual;
        }

        // $venta->totalVentas();
        $venta->venta_anual();
        $json = array();
        foreach ($venta->objetos as $objeto) {
            $json[] = array(
                "venta_dia_vendor" => $venta_dia_vendor,
                "venta_dia"        => $venta_dia,
                "venta_mensual"    => $venta_mensual,
                "venta_anual"      => $objeto->venta_anual
            );
        }
        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
        break;

    case 4:
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $venta->listarVentaDiaGeneral($fecha);
        $json = array();
        foreach ($venta->objetos as $objeto) {
            $json['data'][] = $objeto;
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;


        break;


        /* Venta Dia */
    case 5:
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');

        $venta->venta_diaria($idUsu, $fecha);


        $json = array();
        foreach ($venta->objetos as $objeto) {
            $json['data'][] = $objeto;
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;

    case 6:

        if ($_POST['fecha'] == null) {

            date_default_timezone_set('America/Bogota');
            $fechaN = date('Y-m-d');
            $total = $venta->calcularTotalDia($fechaN);

        } else {
            $total = $venta->calcularTotalDia($_POST['fecha']);
        }
        $jsonstring = json_encode($total);
        echo $jsonstring;

    break;

    case 7:
        
        $jsonstring = json_encode($venta->calcularTotalGeneralVenta());
        echo $jsonstring;


    break;


    case 8:
        $id = 1;
        $venta->venta_dia_vendor($id);
        foreach ($venta->objetos as $objeto) {
            $venta_dia_vendor = $objeto->venta_dia_vendor;
        }

        $venta->venta_diaria();
        foreach ($venta->objetos as $objeto) {
            $venta_diaria = $objeto->venta_diaria;
        }


        $json = array();
        foreach ($venta->objetos as $objeto) {
            // $json['data'][]=$objeto;
            $json[] = array(
                'venta_dia_vendor' => $venta_dia_vendor,
                'venta_diaria' => $venta_diaria
            );
        }
        $jsonstring = json_encode($json[0]);
        echo $jsonstring;

        break;

        /* REPORTE VENTA PDF rep_venta*/
    case 9:
        // session_start();
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

            $html .= '
                    <tr>
                        <th class="service">' . $objeto->id_venta . '</th>
                        <th class="service">' . $objeto->producto . '</th>
                        <th class="service">' . $objeto->cantidad . '</th>
                        <th class="service">' . $objeto->subtotal . '</th>
                        
                    </tr>
                ';
        }

        $html .= '
                    </tbody>
                </table>
            ';

        $venta->venta_dia_vendor($idUsu);
        foreach ($venta->objetos as $objeto) {
            $html .= '
                    <h1>Total en ventas:<br>$' . $objeto->venta_dia_vendor . '</h1>
                    <h5>Vendedor: ' . $_SESSION['nom'] . '</h5>
                    <h5>Reporte generado el: ' . $fecha . '</h5>
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
        $mpdf->Output("../pdf/pdf-" . $id_venta . $fecha2 . ".pdf", "F");

        $json = array();
        $json[] = array(
            /* '' =>$objeto->ALIAS ASIGNADO */
            'idUsu' => $idUsu,
            'fecha' => $fecha
        );
        $jsonstring = json_encode($json);
        echo $jsonstring;

        break;

        /***********rep_cierre GENERAR AUTOMATICAMENTE UN REPORTE AL CERRAR SESION ************** */
    case 10:
        session_start();
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');

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

            $html .= '
                    <tr>
                        <th class="service">' . $objeto->id_venta . '</th>
                        <th class="service">' . $objeto->producto . '</th>
                        <th class="service">' . $objeto->cantidad . '</th>
                        <th class="service">' . $objeto->subtotal . '</th>
                        
                    </tr>
                ';
        }

        $html .= '
                    </tbody>
                </table>
            ';

        $venta->venta_dia_vendor($idUsu);
        foreach ($venta->objetos as $objeto) {
            $html .= '
                    <h1>Total en ventas:<br>$' . $objeto->venta_dia_vendor . '</h1>
                    <h5>Vendedor: ' . $_SESSION['nom'] . '</h5>
                    <h5>Reporte generado el: ' . $fecha . '</h5>
                ';
        }

        // $css = file_get_contents("../css/pdf.css");
        $mpdf = new \Mpdf\Mpdf();
        // $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [50, 800]]);
        // $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output("../pdf/pdf-" . $_POST['funcion'] . ".pdf", "F");

        $json = array();
        $json[] = array(
            /* '' =>$objeto->ALIAS ASIGNADO */
            'idUsu' => $idUsu,
            'fecha' => $fecha
        );
        $jsonstring = json_encode($json);
        echo $jsonstring;
        break;

    case 11:
        $fecha = $_POST['selected'];
        echo $fecha;
        break;

    case 12:
        $fecha = $_POST['fecha'];

        if ($fecha == null) {
            date_default_timezone_set('America/Bogota');
            $fechaN = date('Y-m-d');
            $venta->listarVentaDiaGeneral($fechaN);
            $json = array();
            foreach ($venta->objetos as $objeto) {
                $json['data'][] = $objeto;
            }
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {

            $response = $venta->listarVentaDiaGeneral($fecha);

            if ($response == null) {
                $valor = "";
                $json = array();

                $json['data'][] = array(
                    'id_venta' => $valor,
                    'total' => $valor,
                    'vendedor' => 'No existen datos'
                );
            } else {

                $json = array();
                foreach ($venta->objetos as $objeto) {
                    $json['data'][] = $objeto;
                }
            }
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }

    break;

    case 13:
        $formaPago = $_POST['formaPago'];
        date_default_timezone_set('America/Bogota');
        $fechaN = date('Y-m-d');
        $json = array();


        if ($formaPago == 0) {

            $venta->listarVentaDiaGeneralCierre($idUsu, $fechaN);
            foreach ($venta->objetos as $objeto) {
                $json['data'][] = $objeto;
            }
            $jsonstring = json_encode($json);
            echo $jsonstring;

            // $venta->listarVentaTipoPago($formaPago);
        } else {
            $response = $venta->listarVentaTipoPago($idUsu, $fechaN, $formaPago);

        if ($response == null) {
            $valor = "";
            $json = array();

            $json['data'][] = array(
                'id_venta' => '¡No existen datos!',
                'cantidad' => '',
                'producto' => '',
                'subtotal' => ''
            );
        } else {

            $json = array();
            foreach ($venta->objetos as $objeto) {
                $json['data'][] = $objeto;
            }
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
        }
        

    break;


    case 14:
        date_default_timezone_set('America/Bogota');
        $fechaN = date('Y-m-d');

        if ($_POST['formaPago'] == 0) {

            $total = $venta->calcularTotalDia($fechaN);

        } else {
            $total = $venta->calcularTotalDiaPorPago($fechaN, $_POST['formaPago']);
        }
        $jsonstring = json_encode($total);
        echo $jsonstring;

    break;

    default:
        # code...
        break;
}

    /* dATOS? */
/*     case 4:
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
    break; */