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

    /* Cargar los Detalles y costos del Domicilio */
    case 5:
        $idPedido = $_POST['ID_ORDEN'];
        $idMesa = -1;
        /* pedidos con entrega pendiente */
        // $caja->cargarDatosPedido($idPedido);
        $caja->cargarItemsPedidoSinPago($idPedido);
        $json = array();
        $jsonC = array();
        $jsonP = array();

        $nomProduct = "";
        $nomPresent = "";
        $cantidad = 0;
        $precio = 0;
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
                <tr prodId='$objeto->id_det_pedido' pIdItem='$idDetProd' prodPrecio='' class='tre' seleccionado='ce'>
                    <td><input class='form-check-input ck-item-pedido cb' type='checkbox' id='ck-$idDetProd' checked disabled></td>
                    <td>$nomProduct</td>
                    <td>$nomPresent</td>
                    <td>$cantidad</td>
                    <td>$precio</td>
                    <td class='xr'>$subtotal</td>
                    
                </tr>
            ";
            /* fin */


            $json[] = array(
                'idPedido' => $objeto->id_det_pedido,
                'idMesa' => $idMesa,
                'prods' => $jsonP,
                'template' => $template,
                'subtotal' => $subtotal,
                'idItem' => $idDetProd
            );
        }

        /////
        if(empty($json)){
            $caja->cambiarEstPagado($idPedido);
            echo 0;
        }else{
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
    break;
    

    case 6:
        session_start();

        $idOrd      = intval( $_POST['idOrdSel'] );
        $idPedido   = $idOrd;
        $response   = 0;
        $cantidad   = 0;
        $idProd     = 0;
        // $cantPedido = 0;
        // $cantIngred = 0;
        // $totalStock = 0;
        // $response = 0;
        // $totalIngred = 0;

        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');

        $caja -> crearVenta(
            intval( $_POST['total'] ),
            intval( $_POST['formaPago'] ),
            $fecha,
            intval( $_SESSION['usuario'] ),
            $idOrd
        );

        // $caja->crearVenta($totalVenta, $formaPago, $fecha, $vendedor, $idOrd);

        /* obtener id de la venta */
        $caja->ultimaVenta();
        foreach ($caja->objetos as $objeto) {
            $idVenta = $objeto->ultima_venta;
            // echo $idVenta;
        }

        try {

            $db = new Conexion();
            $conexion = $db->pdo;
            $conexion->beginTransaction();

            /* Consultar los detalles de ese pedido para conocer la cantidad */
            $caja->cargarDatosPedido($idOrd);

            foreach ($caja->objetos as $detalle) {  // Iterar producto por producto

                $cantidad = intval( $detalle->det_cant ); //Cantidad del plato solicitado
                $idProd = intval( $detalle->id_det_prod ); // Id Plato
                // $cantidad2 = intval( $detalle->det_cant );
                // $totalIngCant = 0;

                
                /* Consultar ingredientes */
                $caja->listarIngredsItem($idProd);

                /**
                 * CUANDO EL PRODUCTO O ITEM DE CARTA TIENE INGREDIENTES
                 * Procede a descontar los ingredientes del inventario
                 * se añade esta condicion dado a que hay items que
                 * no tienen ingredientes del inv (como la cervezaa)
                 */
                if ($caja->objetos != null) {

                    foreach ($caja->objetos as $ingred) {
                        
                        $itemCantCont = $cantidad;            // Contador Cantidad de ese item (2 cafes o 1 hamb...)
                        $ingCantCont  = $ingred->cant_ingr;   //Contador Cantidad del ingrediente X que conforma ese item

                        while($itemCantCont != 0){

                            $ingCantCont = $ingred->cant_ingr;   //Cantidad del ingrediente
                       
                            while($ingCantCont != 0){

                                /* seleccionael lote mas proximo a vencer */
                                $sql = "SELECT id_lote, stock  FROM inv_lote WHERE vencim = (SELECT MIN(vencim) FROM inv_lote WHERE lote_id_prod = :id) AND lote_id_prod = :id";
                                $query = $conexion->prepare($sql);
                                $query->execute(array(
                                    ':id' => $ingred->id_ingr
                                ));

                                $lote = $query->fetchall();

                                /** CUANDO HAY INGREDIENTES DEL ITEM EN EL INVENTARIO */
                                if ($lote != null) {
                                    foreach ($lote as $lote) {
                                        switch ($ingCantCont) {
                                            case $ingCantCont < $lote->stock:
                                                $conexion->exec("UPDATE inv_lote SET stock = stock - '$ingCantCont' WHERE id_lote = '$lote->id_lote'");
                                                $ingCantCont = 0;
                                            break;
        
                                            case $ingCantCont == $lote->stock:
                                                $conexion->exec("DELETE FROM inv_lote WHERE id_lote = '$lote->id_lote'");
                                                $ingCantCont = 0;
                                            break;
        
                                            case $ingCantCont> $lote->stock:
                                                $conexion->exec("DELETE FROM inv_lote WHERE id_lote = '$lote->id_lote'");
                                                $ingCantCont -= $lote->stock;
                                            break;
                                        }
                                    }
                                } else {
                                    /* Cuando no hay ingreds en el inv agragar a descuadre */
                                    $conexion->exec("INSERT INTO inv_descuadre(id_venta,fecha_venta,id_ingred,cantidad) VALUES ('$idVenta','$fecha','$ingred->id_ingr','$ingCantCont')");
                                    $ingCantCont = 0;
                                }   
                            }
                            $itemCantCont -= 1;
                        } /* fin iteracion cantidad de cada item */

                    }

                } /* Fin cuando item tiene ingredientes */

                
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
            }   /* Fin iteracion producto sobre producto */
            
            $caja->cambiarEstPagado($idOrd);
            echo $response = 0;
            // $conexion->rollBack();
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

    /* Listar mesas con ordenes */
    case 9:
        $caja->listarMesas();
        $json=array();
        $jsonP=array();

        foreach($caja->objetos as $objeto){
            $objeto->nom;   //nombre mesa
            $jsonP=[];

            $caja->listarOrdenMesa($objeto->id_mesa);
            foreach($caja->objetos as $objP){
                
                $jsonP[]=array(
                    $objP->id_pedido,
                );
            }

            $json[]=array(
                'id_mesa'=>$objeto->id_mesa,
                'nomMesa'=>$objeto->nom,
                'prods'=> $jsonP
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;

    /* Lista los pedidos para el restaurante */
    case 10:
        /* cajas con entrega pendiente */
        $caja->listarDomiciliosCaja();
        $json = array();

        foreach ($caja->objetos as $objeto) {

            $json[] = array(
                'idPedido' => $objeto->id_pedido
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;


    /* Nuevo procesar venta caja */
    case 11:
        //itemSelec
        $itemSelec = array();

        session_start();

        $itemSelec = $_POST['itemSelec'];
        $totalVenta = $_POST['total'];
        $idOrd = $_POST['idOrdSel'];
        $formaPago = $_POST['formaPago'];
        $idPedido = $idOrd;
        $vendedor = $_SESSION['usuario'];

        $cantPedido = 0;
        $cantIngred = 0;
        $totalStock = 0;
        $response = 0;
        $totalIngred = 0;
        $cantidad = 0;

        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');
        $caja->crearVenta($totalVenta, $formaPago, $fecha, $vendedor, $idOrd);

        /* obtener id de la venta */
        $caja->ultimaVenta();
        foreach ($caja->objetos as $objeto) {
            $idVenta = $objeto->ultima_venta;
        }



            foreach ($itemSelec as $item) {

                try {

                    $db = new Conexion();
                    $conexion = $db->pdo;
                    $conexion->beginTransaction();


                    /* Carga los datos de un item segun el pedido al que pertenezca */
                    $caja->cargarDatosItemPedido($idOrd, $item);


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


                    /* Cambiar el estado de ese item perteneciente a la orden
                    en cuestion a "pagado" */
                    $caja->cambiarEstadoPagoItemPedido($idOrd, $item);


                    echo $response = 0;
        
                    $conexion->commit();

                } catch (Exception $error) {
                    $conexion->rollBack();
                    // $caja->borrar($idVenta);
                    echo $error->getMessage();
                }

            }
    break;



    default:
        # code...
        break;
}
