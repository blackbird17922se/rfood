<?php
// require_once('../vendor/autoload.php');
include '../models/producto.php';
include_once '../models/conexion.php';

$product = new Producto();

/* MOSTRAR PRODUCTOS */
if($_POST['funcion'] == 'listarProducts'){
    $idCat = $_POST['idCat'];
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
                <input class='form-control quantity' min='0' id='".$objeto->id_prod."' value='0' type='number'>
                <div class='input-group-append'>
                    <button class='btn btn-outline-secondary btn-plus'>
                        <i class='fa fa-plus'></i>
                    </button>
                </div>
            </div>

            <button class='agregar-carrito lote btn btn-sm btn-primary'>
                <i class='fas fa-plus-square mr-2'></i>Agregar al pedido
            </button>
        ";

        $json[]=array(
            'id_prod'=>$objeto->id_prod,
            // 'codbar'=>$objeto->codbar,
            'nombre'=>$objeto->nombre,
            'compos'=>$objeto->compos,
            'categ'=>$objeto->categ,    //Categoria
            'idPres'=>$objeto->prod_pres,   //id de la presentacion
            'present'=>$objeto->present,   //nombre de la presentaion
            'precio'=>$objeto->precio,
            'cant'=>$cant,
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}