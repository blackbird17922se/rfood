<?php
include '../models/caja.php';
include_once '../models/conexion.php';

$caja = new Caja();

switch ($_POST['funcion']) {

    /* Lista los pedidos para el restaurante */
    case 2:
        /* cajas con entrega pendiente */
        $caja->listarPedidosCaja();
        $json = array();

        foreach ($caja->objetos as $objeto) {

            $json[] = array(
                'idPedido' => $objeto->id_pedido,
                'idMesa' => $objeto->id_mesa,
                'nomMesa' => $objeto->nom_mesa,
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;

    /* Cargar los Detalles y costos de ese pedido */
    case 5:
        $idPedido = $_POST['ID'];
        $idMesa = $_POST['IDMESA'];
        /* pedidos con entrega pendiente */
        $caja->cargarDatosPedido($idPedido);
        $json = array();
        $jsonC = array();
        $jsonP = array();

        $nomProduct = "";
        $nomPresent = "";
        $cantidad = "";
        $precio = "";
        // $total="";

        foreach ($caja->objetos as $objeto) {
            $jsonP = [];
            $jsonC = [];
            $idDetProd = $objeto->id_det_prod;

            /* Consultar nombre platillo */
            $caja->ConsultarNomProducts($idDetProd);
            foreach ($caja->objetos as $objn) {

                $jsonP[] = array(
                    $nomProduct = $objn->nom,
                    $nomPresent = $objn->presnom,
                    $cantidad = $objeto->det_cant,
                    $precio = $objn->precio,

                );
            }

            $subtotal = $precio * $cantidad;

            // $total = $total + $subtotal;

            /* prueba  */
            $template = "       
                <tr prodId='$objeto->id_det_pedido' prodPrecio=''>
                    <td>$nomProduct</td>
                    <td>$nomPresent</td>
                    <td>$cantidad</td>
                    <td>$precio</td>
                    <td>$subtotal</td>
                    
                </tr>
            ";
            /* fin */


            $json[] = array(
                'idPedido' => $objeto->id_det_pedido,
                'idMesa' => $idMesa,
                'prods' => $jsonP,
                'template' => $template,
                'subtotal' => $subtotal
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;

    /* funcion,total,idOrdSel,formaPago */
    case 'registrarVenta':
        session_start();

        $totalVenta = $_POST['total'];
        $idOrd = $_POST['idOrdSel'];
        $formaPago = $_POST['formaPago'];

        $idPedido = $idOrd;
        $idMesero = "";
        $idCocineroLider = "";
        $vendedor = $_SESSION['usuario'];

        $cantPedido = 0;
        $cantIngred = 0;
        $totalStock = 0;
        $response = 0;
        $totalIngred = 0;
        $cantidad = 0;


        /* Cargar id mesero y cocinero */
        $caja->cargarMeseroCocinero($idPedido);

        foreach ($caja->objetos as $objId) {
            $idMesero = $objId->id_mesero;
            $idCocineroLider = $objId->id_coc_lider;
        }


        /* integra sfarma */
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');
        $caja->crearVenta($totalVenta, $formaPago, $fecha, $vendedor, $idMesero, $idCocineroLider, $idOrd);

        /* obtener id de la venta */
        $caja->ultimaVenta();
        foreach ($caja->objetos as $objeto) {
            $idVenta = $objeto->ultima_venta;
            // echo $idVenta;
        }

        /* +++ */

        try {

            /* NINC */
            $db = new Conexion();
            $conexion = $db->pdo;
            $conexion->beginTransaction();

            /* Consultar los detalles de ese pedido para conocer la cantidad */
            $caja->cargarDatosPedido($idOrd);

            foreach ($caja->objetos as $detalle) {

                $cantidad = $detalle->det_cant; //Cantidad del plato solicitado
                $cantidad2 = $detalle->det_cant;
                $idProd = $detalle->id_det_prod;
                $totalIngCant = 0;

                while ($cantidad2 > 0) {

                    /* Consultar ingredientes */
                    $caja->listarIngredsItem($detalle->id_det_prod);

                    /**
                     * CUANDO EL PRODUCTO O ITEM DE CARTA TIENE INGREDIENTES
                     * Procede a descontar los ingredientes del inventario
                     * se añade esta condicion dado a que hay items que
                     * no tienen ingredientes del inv (como la cervezaa)
                     */
                    if ($caja->objetos != null) {

                        foreach ($caja->objetos as $ingred) {
                            $ih = $ingred->id_ingr;

                            $ingCant = $ingred->cant_ingr;   //Cantidad del ingrediente

                            $totalIngCant = $cantidad * $ingCant;

                            /* Descontar productos */
                            /* seleccionael lote mas proximo a vencer */
                            $sql = "SELECT * FROM inv_lote WHERE vencim = (SELECT MIN(vencim) FROM inv_lote WHERE lote_id_prod = :id) AND lote_id_prod = :id";
                            $query = $conexion->prepare($sql);
                            $query->execute(array(
                                ':id' => $ingred->id_ingr
                            ));

                            $lote = $query->fetchall();

                            /**
                             * CUANDO HAY INGREDIENTES DEL ITEM EN EL INVENTARIO
                             */
                            if ($lote != null) {
                                foreach ($lote as $lote) {
                                    if ($totalIngCant < $lote->stock) {
                                        $conexion->exec("UPDATE inv_lote SET stock = stock - '$totalIngCant' WHERE id_lote = '$lote->id_lote'");
                                    }

                                    /* ingred->cant pedida es igual a la ingred->cant en el stock */
                                    if ($totalIngCant == $lote->stock) {
                                        $conexion->exec("DELETE FROM inv_lote WHERE id_lote = '$lote->id_lote'");
                                    }

                                    /* Cuaando la ingred->cant pedida es superior a la ingred->cant del stock de un lote
                                        y debe eliminar ese lote y consumir los productos del siguiente lote*/
                                    if ($totalIngCant > $lote->stock) {
                                        $conexion->exec("DELETE FROM inv_lote WHERE id_lote = '$lote->id_lote'");
                                        $cantidad -= $lote->stock;
                                    }
                                    --$cantidad2;
                                }
                            } else {
                                $conexion->exec("INSERT INTO inv_descuadre(id_venta,fecha_venta,id_ingred,cantidad) VALUES ('$idVenta','$fecha','$ih','$totalIngCant')");
                                $cantidad2 = 0;
                            }
                        }
                    } else {
                        //agredet regular
                        
                        $cantidad2 = 0;
                    }

                }
                $caja->agregarDetVenta($cantidad, $idProd, $idVenta);
                $precio = 0;
                // $caja->consultarDatosProducto($idProd);
                $caja->consultarPrecio($idProd);
                foreach ($caja->objetos as $objPr) {
                    $precio = $objPr->precio;
                }
                $subtotal = $cantidad * $precio;
                $caja->insertRegVenta($precio, $cantidad, $subtotal, $idProd, $idVenta);
                /* venta_prod(precio,cant,subtotal,prod_id_prod,venta_id_venta)  */
            }
            echo $response = 0;

            $conexion->commit();


        } catch (Exception $error) {
            $conexion->rollBack();
            // $caja->borrar($idVenta);
            echo $error->getMessage();
        }




        break;

        /* PARA ACCEDER A LA ULTIMA VENTA */
    case 'ultimaVenta':
        $caja->ultimaVenta();
        foreach ($caja->objetos as $objeto) {
            $idVenta = $objeto->ultima_venta;
            $_SESSION['idUltimaVenta'] = $idVenta;
            echo $idVenta;
        }
        break;


    case 'verificarStock':
        $cantPedido = 0;
        $cantIngred = 0;
        $totalStock = 0;
        $response = 0;
        $totalIngred = 0;
        $cantidad = 0;

        $idOrdSel = $_POST['idOrdSel'];

        /* nn */
        try {
            $db = new Conexion();
            $conexion = $db->pdo;
            $conexion->beginTransaction();

            /* Consultar los detalles de ese pedido para conocer la cantidad */
            $caja->cargarDatosPedido($idOrdSel);

            foreach ($caja->objetos as $detalle) {

                $cantidad = $detalle->det_cant; //Cantidad del plato solicitado
                $cantidad2 = $detalle->det_cant;
                $idProd = $detalle->id_det_prod;
                $totalIngCant = 0;

                while ($cantidad2 >= 1) {

                    /* Consultar ingredientes */
                    $caja->listarIngredsItem($detalle->id_det_prod);

                    /**
                     * CUANDO EL PRODUCTO O ITEM DE CARTA TIENE INGREDIENTES
                     * Procede a descontar los ingredientes del inventario
                     * se añade esta condicion dado a que hay items que
                     * no tienen ingredientes del inv (como la cervezaa)
                     */
                    if ($caja->objetos != null) {

                        foreach ($caja->objetos as $ingred) {
                            $ih = $ingred->id_ingr;

                            $ingCant = $ingred->cant_ingr;   //Cantidad del ingrediente

                            $totalIngCant = $cantidad * $ingCant;

                            /* Descontar productos */
                            /* seleccionael lote mas proximo a vencer */
                            $sql = "SELECT * FROM inv_lote WHERE vencim = (SELECT MIN(vencim) FROM inv_lote WHERE lote_id_prod = :id) AND lote_id_prod = :id";
                            $query = $conexion->prepare($sql);
                            $query->execute(array(
                                ':id' => $ingred->id_ingr
                            ));

                            $lote = $query->fetchall();

                            /**
                             * CUANDO HAY INGREDIENTES DEL ITEM EN EL INVENTARIO
                             */
                            if ($lote != null) {
                                foreach ($lote as $lote) {
                                    if ($totalIngCant < $lote->stock) {
                                        $conexion->exec("UPDATE inv_lote SET stock = stock - '$totalIngCant' WHERE id_lote = '$lote->id_lote'");
                                    }

                                    /* ingred->cant pedida es igual a la ingred->cant en el stock */
                                    if ($totalIngCant == $lote->stock) {
                                        $conexion->exec("DELETE FROM inv_lote WHERE id_lote = '$lote->id_lote'");
                                    }

                                    /* Cuaando la ingred->cant pedida es superior a la ingred->cant del stock de un lote
                                        y debe eliminar ese lote y consumir los productos del siguiente lote*/
                                    if ($totalIngCant > $lote->stock) {
                                        $conexion->exec("DELETE FROM inv_lote WHERE id_lote = '$lote->id_lote'");
                                        $cantidad -= $lote->stock;
                                    }
                                    $cantidad2--;
                                }
                            } else {
                                $cantidad2 = 0;
                            }
                        }
                    } else {
                        $cantidad2 = 0;
                    }
                }
            }
            echo $response = 0;

            $conexion->commit();
        } catch (Exception $error) {
            $conexion->rollBack();
            // $caja->borrar($idVenta);
            echo $error->getMessage();
        }
        break;



    default:
        # code...
        break;
}
