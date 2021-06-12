<?php

include '../models/pedido.php';
include_once '../models/conexion.php';

$pedido = new Pedido();

switch ($_POST['funcion']) {
    case 'nuevoPedido':

        $id_mesa   = $_POST['id_mesa'];
        $entregado = $_POST['entregado'];
        $terminado = $_POST['terminado'];
        $productos = json_decode($_POST['json']);
        $fecha = date('Y-m-d H:i:s');
        
        $pedido->nuevoPedido($id_mesa,$entregado,$terminado);

        /* obtener id de la venta */
        $pedido->ultimoPedido();
        foreach($pedido->objetos as $objeto){
            $idPedido = $objeto->ultimo_pedido;
            echo $idPedido;
        }

        try {
            $db = new Conexion();
            $conexion = $db->pdo;
            $conexion->beginTransaction();
            /* Recorrer todos losproductos...  ->cantidad es traida desde el localStorage */
            foreach ($productos as $prod) {
                $cantidad = $prod->cantidad;

                while($cantidad != 0){
                    $sql = "INSERT INTO det_pedido(det_cant,id_det_prod,id_det_pedido) 
                        VALUES(
                            '$cantidad',
                            '$prod->id_prod',
                            '$idPedido'      
                        )
                    ";
                    $conexion->exec($sql);
                    $cantidad = 0;
                }
            }
            $conexion->commit();

        }catch (Exception $error) {
            $conexion->rollBack();
            $venta->borrar($idVenta);
        echo $error->getMessage();
        }

    break;


    case 'listarPedidos':
        $pedido->listarPedidos();
        $json=array();
        foreach($pedido->objetos as $objeto){

            /* Consular los productos de ese pedido */
            $pedido->listarProdPedido($objeto->id_pedido);
            $idPedido = $objeto->id_pedido;
            $idMesa = $objeto->id_mesa;

            // var_dump($pedido);

            $cant;
            $idProd;

            foreach($pedido->objetos as $objProds){
                $json2[]=array(
                    'cant' => $objProds->det_cant,
                    'idProd' => $objProds->id_det_prod,
                    'idPedido'=>$idPedido,
                    'idMesa'=>$idMesa
                );


                // $cant = $objProds->det_cant;
                // $idProd = $objProds->id_det_prod;
            }
    
            // $json[]=array(
            //     /* id_pedido, id_mesa */
            //     /* '' =>$objeto->ALIAS ASIGNADO */
            //     'idPedido'=>$objeto->id_pedido,
            //     'idMesa'=>$objeto->id_mesa,
            //     'cant'=>$cant,
            //     'idProd'=>$idProd
            // );
        }
        $jsonstring = json_encode($json2);
        echo $jsonstring;

    break;
    
    default:
        # code...
        break;
}