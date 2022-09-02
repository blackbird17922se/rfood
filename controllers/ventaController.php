<?php
require_once('../vendor/autoload.php');
include '../models/venta.php';
$venta = new Venta();

session_start();
$idUsu = $_SESSION['usuario'];


switch ($_POST['funcion']) {

    /* LISTAR VENTAS GENERALES */
    /* Listar todas las ventas desde el origen de los tiempos... */
    case 1:
        $venta->listarVentasGenerales();
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
    // case 3:
    //     $venta->venta_dia_vendor($idUsu);
    //     foreach ($venta->objetos as $objeto) {
    //         $venta_dia_vendor = $objeto->venta_dia_vendor;
    //     }

    //     $venta->venta_dia();
    //     foreach ($venta->objetos as $objeto) {
    //         $venta_dia = $objeto->venta_dia;
    //     }

    //     $venta->venta_mensual();
    //     foreach ($venta->objetos as $objeto) {
    //         $venta_mensual = $objeto->venta_mensual;
    //     }

    //     // $venta->totalVentas();
    //     $venta->venta_anual();
    //     $json = array();
    //     foreach ($venta->objetos as $objeto) {
    //         $json[] = array(
    //             "venta_dia_vendor" => $venta_dia_vendor,
    //             "venta_dia"        => $venta_dia,
    //             "venta_mensual"    => $venta_mensual,
    //             "venta_anual"      => $objeto->venta_anual
    //         );
    //     }
    //     $jsonstring = json_encode($json[0]);
    //     echo $jsonstring;
    //     break;

    // case 4:
    //     date_default_timezone_set('America/Bogota');
    //     $fecha = date('Y-m-d');
    //     $venta->listarVentaDiaGeneral($fecha);
    //     $json = array();
    //     foreach ($venta->objetos as $objeto) {
    //         $json['data'][] = $objeto;
    //     }
    //     $jsonstring = json_encode($json);
    //     echo $jsonstring;


    // break;


        /* Venta Dia */
    // case 5:
    //     date_default_timezone_set('America/Bogota');
    //     $fecha = date('Y-m-d');

    //     $venta->venta_diaria($idUsu, $fecha);


    //     $json = array();
    //     foreach ($venta->objetos as $objeto) {
    //         $json['data'][] = $objeto;
    //     }
    //     $jsonstring = json_encode($json);
    //     echo $jsonstring;
    // break;

    case 6:

        if ($_POST['fecha'] == null) {

            date_default_timezone_set('America/Bogota');
            $fechaN = date('Y-m-d');
            $total = $venta->calcularTotalDia($fechaN, $_POST['formaPago'], $_POST['cajero']);

        } else {
            $total = $venta->calcularTotalDia($_POST['fecha'], $_POST['formaPago'], $_POST['cajero']);
        }
        $jsonstring = json_encode($total);
        echo $jsonstring;

    break;


    /* CALCULA EL TOTAL DE LAS VENTAS GENERALES */
    case 7:
        
        $jsonstring = json_encode($venta->calcularTotalGeneralVenta());
        echo $jsonstring;

    break;


    // case 11:
    //     $fecha = $_POST['selected'];
    //     echo $fecha;
    // break;


    /* Listar las ventas del dia */
    case 12:

        $fecha     = $_POST['fecha'];
        $formaPago = intval($_POST['formaPago']);
        $cajero    = intval($_POST['cajero']);

        // if($cajero == null){
        //     $cajero=0;
        // }


        if ($fecha == null) {
            date_default_timezone_set('America/Bogota');
            $fechaN = date('Y-m-d');
            $venta->listarVentaDiaGeneral($fechaN, $cajero, $formaPago);
            $json = array();
            foreach ($venta->objetos as $objeto) {
                $json['data'][] = $objeto;
            }
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {

            $response = $venta->listarVentaDiaGeneral($fecha, $cajero, $formaPago);

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


    /* Lista las ventas de cada producto del cierre de caja */
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


    /* CALCULAR TOTAL CIERRE DE CAJA
        Calcula el total de las ventas de X Cajero
        La fecha es la de ese dia
        La forma de pago es seleccionable
     */
    case 14:
        date_default_timezone_set('America/Bogota');
        $fechaN = date('Y-m-d');

        $total = $venta->calcularTotalDia($fechaN, $_POST['formaPago'], $idUsu);

        $jsonstring = json_encode($total);
        echo $jsonstring;

    break;

    default:
        # code...
        break;
}