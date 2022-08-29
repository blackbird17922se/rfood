<?php
include '../models/caja.php';
include_once '../models/conexion.php';

$caja = new Caja();

switch ($_POST['funcion']) {

    /* Cargar los Detalles y costos de ese pedido 
     como los item de dicha orden y demas*/
    case 1:
        $idPedido = $_POST['ID_ORDEN'];
        // $idMesa = $_POST['ID_MESA'];
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


            $cant = "       
                <div class='input-group inline-group forse'>
                    <div class='input-group-prepend'>
                        <button class='btn btn-outline-secondary btn-minus' iditem='$idDetProd'>
                            <i class='fa fa-minus'></i>
                        </button>
                    </div>
                    <input class='form-control quantity' min='1' max='$cantidad' iditem='$idDetProd' id='id_cant_item_$idDetProd' value='$cantidad' type='number'>
                    <div class='input-group-append'>
                    <div class='input-group-append'>
                        <button class='btn btn-outline-secondary btn-plus' iditem='$idDetProd'>
                            <i class='fa fa-plus'></i>
                        </button>
                    </div>

                    <input type='hidden' value='$precio' class='inputprecio'>
                    <input type='hidden' value='$idDetProd' class='inputiditem'>

                </div>
            ";

            // $total = $total + $subtotal;

            /* prueba  */
            $template = "       
                <tr prodId='$objeto->id_det_pedido' pIdItem='$idDetProd' prodPrecio='' class='tre rowt' seleccionado='ce'>
                    <td><input class='form-check-input ck-item-pedido cb' type='checkbox' id='ck-$idDetProd' checked disabled></td>
                    <td>$nomProduct</td>
                    <td>$nomPresent</td>
                    <td>$cant</td>
                    <td>$precio</td>
                    <td id='td-subtotal-$idDetProd' class='xr'>$subtotal</td>
                    
                </tr>
            ";
            /* fin */


            $json[] = array(
                'idPedido' => $objeto->id_det_pedido,
                // 'idMesa' => $idMesa,
                'prods' => $jsonP,
                'template' => $template,
                'subtotal' => $subtotal,
                'idItem' => $idDetProd,
                'cantidad' => $cantidad
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;

    break;


    case 2:

        //itemSelec
        $itemSelec = array();

        session_start();

        // $itemSelec = $_POST['itemSelec'];
        $itemSelec = json_decode($_POST['json']);
        $totalVenta = $_POST['total'];
        $idOrd = $_POST['ID_ORDEN'];
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

                    /////////////////////////////
                    $cantidad = intval( $item->cantidad ); 
                    // $cantidad = $item->cantidad; //Cantidad del plato solicitado
                    $cantidad2 = intval( $item->cantidad );
                    $idProd = intval( $item->idItem );
                    $totalIngCant = 0;
                    $precio = intval( $item->precio);


                    while ($cantidad2 > 0) {
        
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
                        // $precio = 0;
                        // $caja->consultarDatosProducto($idProd);
                        // $caja->consultarPrecio($idProd);
                        // foreach ($caja->objetos as $objPr) {
                        //     $precio = $objPr->precio;
                        // }

                        $subtotal = $cantidad *  $precio;
                        $caja->insertRegVenta( $precio, $cantidad, $subtotal, $idProd, $idVenta);
                        /* venta_prod(precio,cant,subtotal,prod_id_prod,venta_id_venta)  */
                    


                    /* Cambiar el estado de ese item perteneciente a la orden
                    en cuestion a "pagado" */
                    $caja->cambiarEstadoPagoItemPedido($idOrd, $idProd);


                    echo $response = 0;
        
                    $conexion->commit();

            } catch (Exception $error) {
                $conexion->rollBack();
                // $caja->borrar($idVenta);
                echo $error->getMessage();
            }


                    ///////////////////////////



             

            }

    break;




}