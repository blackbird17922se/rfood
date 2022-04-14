<?php

include '../models/pedido.php';
include_once '../models/conexion.php';

$pedido = new Pedido();

switch ($_POST['funcion']) {
    case 'nuevoPedido':

        $id_mesa   = $_POST['id_mesa'];
        $entregado = $_POST['entregado'];
        $terminado = $_POST['terminado'];
        $pagado = $_POST['pagado'];
        $productos = json_decode($_POST['json']);
        $fecha = date('Y-m-d H:i:s');
        
        $pedido->nuevoPedido($id_mesa,$entregado,$terminado, $pagado);

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

                /* Consultar nombre platillo */
                $pedido->ConsultarNomProducts($objP->id_det_prod);
                foreach($pedido->objetos as $objn){

                    $jsonP[]=array(
                        $objn->nom,
                        $objn->presnom,
                        $objP->det_cant,
                        // 'idProd' => $objP->id_det_prod,
                        // 'cant'=>$objP->det_cant,
                    );



                }
                // $nomp = $pedido->nom;
                
          
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

    case 'terminado':
        $idOrden = $_POST['ID'];
        $pedido->cambiarEstTerminado($idOrden);
    break;
    
    default:
        # code...
        break;
}