<?php
include '../models/venta.php';
$venta = new Venta();
session_start();
$idUsu = $_SESSION['usuario'];

if($_POST['funcion']=='listar'){
    $venta->buscar();
    $json=array();
    foreach ($venta->objetos as $objeto) {
        $json['data'][]=$objeto;
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

if($_POST['funcion']=='mostrar_consultas'){

    $id = 1;
    $venta->venta_dia_vendor($id);
    foreach ($venta->objetos as $objeto) {
        $venta_dia_vendor = $objeto->venta_dia_vendor;
    }

    $venta->venta_diaria();
    foreach ($venta->objetos as $objeto) {
        $venta_diaria = $objeto->venta_diaria;
    }


    $json=array();
    foreach ($venta->objetos as $objeto) {
        // $json['data'][]=$objeto;
        $json[]= array(
            'venta_dia_vendor' => $venta_dia_vendor,
            'venta_diaria' => $venta_diaria
        );
    }
    $jsonstring = json_encode($json[0]);
    echo $jsonstring;
}