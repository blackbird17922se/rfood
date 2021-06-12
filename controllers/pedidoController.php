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
        /* pedidos con entrega pendiente */
        $pedido->listarPedidosPendEntrega();
        $json=array();
        $jsonC=array();
        $jsonP=array();
        
        foreach($pedido->objetos as $objeto){
            $jsonP=[];
            $jsonC=[];
            $idPedido = $objeto->id_pedido;

            $pedido->listarProdPedido($idPedido);
            foreach($pedido->objetos as $objP){
                $jsonP[]=array(
                    $objP->id_det_prod,
                    $objP->det_cant,
                    // 'idProd' => $objP->id_det_prod,
                    // 'cant'=>$objP->det_cant,
                );
            }

            $json[]=array(
                'idPedido' => $objeto->id_pedido,
                'idMesa'=>$objeto->id_mesa,
                'prods'=> $jsonP
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;

    // case 'listarPedidos':
    //     // $pedido->listarPedidos();
    //     $json=array();

    //     $pedido->listarProdPedido(10);

    //     $xe = $pedido->objetos;

    //     // foreach($pedido->objetos as $objeto){
    //     //     $json[]=array(
    //     //         'cant' => $objeto->det_cant,
    //     //         'idPedido'=>$objeto->id_det_pedido,
    //     //         'idProd'=>$objeto->id_det_prod,
        
    //     //     );
    //     // }

    //     $jsonstring = json_encode($xe);
    //     // $jsonstring = json_encode($json);
    //     echo $jsonstring;

    // break;
    
    default:
        # code...
        break;
}