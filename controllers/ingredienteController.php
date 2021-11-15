<?php
// require_once('../vendor/autoload.php');
include '../models/ingrediente.php';
include_once '../models/conexion.php';

$product = new Ingrediente();

/* MOSTRAR PRODUCTOS */
if($_POST['funcion'] == 'listarProducts'){
    $idCat = $_POST['idCat'];
    // echo $idCat;
    $product->listarProductsCateg($idCat);
    $json=array();
    foreach($product->objetos as $objeto){

        $cant = "       
            <div class='input-group inline-group forse'>
                <div class='input-group-prepend'>
                    <button class='btn btn-outline-secondary btn-minus'>
                        <i class='fa fa-minus'></i>
                    </button>
                </div>
                <input class='form-control quantity' min='0' id='".$objeto->id_inv_prod."' value='0' type='number'>
                <div class='input-group-append'>
                    <button class='btn btn-outline-secondary btn-plus'>
                        <i class='fa fa-plus'></i>
                    </button>
                </div>
            </div>

            <button class='agregar-carrito lote btn btn-sm btn-primary'>
                <i class='fas fa-plus-square mr-2'></i>Asignar al Ítem
            </button>
        ";

        $json[]=array(
            'cant'=>$cant,
            'id_prod'=>$objeto->id_inv_prod,
            'medida'=>$objeto->medida,
            'nombre'=>$objeto->nombre,
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

if($_POST['funcion'] == 'guardarIngreds'){

    $productos = json_decode($_POST['json']);
    

    try {
        $db = new Conexion();
        $conexion = $db->pdo;
        $conexion->beginTransaction();
        /* Recorrer todos losproductos...  ->cantidad es traida desde el localStorage */
        foreach ($productos as $prod) {
            $cantidad = $prod->cantidad;
            
            while($cantidad != 0){
                $sql = "INSERT INTO ingrediente(cant,id_ing_prod,id_prod) 
                    VALUES(
                        '$cantidad',
                        '$prod->id_prod',
                        '$prod->id_plato'
                    )
                ";
                $conexion->exec($sql);
                $cantidad = 0;
            }
        }
        $conexion->commit();

    }catch (Exception $error) {
        $conexion->rollBack();
    echo $error->getMessage();
    }

}