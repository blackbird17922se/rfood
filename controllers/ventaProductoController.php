<?php
include '../models/ventaProducto.php';
$ventProd = new VentaProducto();

if($_POST['funcion']=='ver'){
    $id = $_POST['id'];
    $ventProd->ver($id);
    $json=array();
    foreach ($ventProd->objetos as $objeto) {
        $json[]=$objeto;
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}