<?php
include '../models/caja.php';
include_once '../models/conexion.php';

$caja = new Caja();

switch ($_POST['funcion']) {

    /* Cargar los Detalles y costos de ese pedido 
     como los item de dicha orden y demas*/
    case 1:
        $idPedido = $_POST['ID_ORDEN'];
        $idMesa = $_POST['ID_MESA'];

        /* Carga los items realizados en ese pedido
        que aun no se han pagado 
        */
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
            // $idMesa = $objeto->id_mesa;

            /* Consultar nombre platillo */
            $caja->ConsultarNomProducts($idDetProd);
            foreach ($caja->objetos as $objn) {
                

                $jsonP[] = array(
                    $nomProduct = $objn->nom,
                    $nomPresent = $objn->presnom,
                    $cantidad = $objeto->det_cant,
                    $precio = $objn->precio,
                    $cant_ct_div = $objeto->cant_cuenta_dividida

                );
            }

            $subtotal = $precio * $cant_ct_div;


            $cant = "       
                <div class='input-group inline-group forse'>
                    <div class='input-group-prepend'>
                        <button class='btn btn-outline-secondary btn-minus' iditem='$idDetProd'>
                            <i class='fa fa-minus'></i>
                        </button>
                    </div>
                    <input class='form-control quantity' min='1' max='$cant_ct_div' iditem='$idDetProd' id='id_cant_item_$idDetProd' value='$cant_ct_div' type='number'>
                    <div class='input-group-append'>
                    <div class='input-group-append'>
                        <button class='btn btn-outline-secondary btn-plus' iditem='$idDetProd'>
                            <i class='fa fa-plus'></i>
                        </button>
                    </div>

                    <input type='hidden' value='$precio' class='inputprecio'>
                    <input type='hidden' value='$idDetProd' class='inputiditem'>
                    <input type='hidden' value='$cantidad' class='inputcantoriginal'>

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
                'idMesa' => $idMesa,
                'prods' => $jsonP,
                'template' => $template,
                'subtotal' => $subtotal,
                'idItem' => $idDetProd,
                'cantidad' => $cantidad,
                'cant_ct_div' => $cant_ct_div
            );
        }

        if(empty($json)){

            $caja->cambiarEstPagado($idPedido);
            /* Liberar mesa */
            if($idMesa != -1){
                $caja->desBloquearMesa($idMesa);
            }

            echo 0;

        }else{

            $jsonstring = json_encode($json);
            echo $jsonstring;
        }


    break;


    case 2:

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
        $cantOriginal = 0;

        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');
        $caja->crearVenta($totalVenta, $formaPago, $fecha, $vendedor, $idOrd);

        /* obtener id de la venta */
        $caja->ultimaVenta();
        foreach ($caja->objetos as $objeto) {
            $idVenta = $objeto->ultima_venta;
        }

        try {

            $db = new Conexion();
            $conexion = $db->pdo;
            $conexion->beginTransaction();

            foreach ($itemSelec as $item) {

                $cantidad = intval( $item->cantidad ); 
                $idProd = intval( $item->idItem );
                $totalIngCant = 0;
                $precio = intval( $item->precio);
                $cantOriginal = intval( $item->cantOriginal);


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
                            }
                        } else {
                            /* Cuando no hay ingreds en el inv agragar a descuadre */
                            $conexion->exec("INSERT INTO inv_descuadre(id_venta,fecha_venta,id_ingred,cantidad) VALUES ('$idVenta','$fecha','$ih','$totalIngCant')");
                        }
                    }

                }   /* Fin cuando item tiene ingredientes */

                $caja->agregarDetVenta($cantidad, $idProd, $idVenta);


                $subtotal = $cantidad *  $precio;
                $caja->insertRegVenta( $precio, $cantidad, $subtotal, $idProd, $idVenta);
            
                $caja -> descontarCantidad($idOrd, $idProd, $cantidad);
                $caja -> evaluarCantidadRestante($idOrd, $idProd);
                foreach ($caja->objetos as $it) {

                    if ($it->cant_ct_div != 0) {
                        /* Aun hay items sin pagar en ese pedido por lo tanto
                        enviar respuesta 1 para q recarge la tabla con items
                        pendientes*/
                        echo $response = 1;

                    }else{
                        /* Cambiar el estado de ese item perteneciente a la orden
                        en cuestion a "pagado" */
                        $caja->cambiarEstadoPagoItemPedido($idOrd, $idProd);
                        // echo 'pagado total';

                    }
                }

            }   /* Fin iteracion sobre array items */
            echo $response = 0;

            $conexion->commit();

            ///////////////////////////

        } catch (Exception $error) {
            $conexion->rollBack();
            // $caja->borrar($idVenta);
            echo $error->getMessage();
        }
        

    break;

    case 3:
        $idMesa = 0;

        $caja->cargarMesaOrden($_POST['ID_ORDEN']);
        foreach ($caja->objetos as $obj) {
            $idMesa = $obj -> id_mesa;
        }
        echo $idMesa;

    break;




}