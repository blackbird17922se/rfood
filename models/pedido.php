<?php

include 'conexion.php';

class Pedido
{
    var $objetos;

    public function __construct()
    {
        $bd = new Conexion();
        $this->acceso = $bd->pdo;
    }

    public function nuevoPedido($id_mesa, $id_mesero, $observ, $entregado, $terminado, $pagado)
    {
        $sql = "INSERT INTO pedido (id_mesa, id_mesero, entregado, terminado, pagado, observ) 
        VALUES (:id_mesa, :id_mesero, :entregado, :terminado, :pagado, :observ)";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':id_mesa'   => $id_mesa,
            ':id_mesero' => $id_mesero,
            ':entregado' => $entregado,
            ':terminado' => $terminado,
            ':pagado'    => $pagado,
            ':observ'    => $observ
        ]);
        echo 'add';
    }

    function ultimoPedido()
    {
        $sql = "SELECT MAX(id_pedido) AS ultimo_pedido FROM pedido";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }


    function listarPedidosPendEntrega()
    {
        $sql = "SELECT 
                id_pedido, 
                pedido.id_mesa, 
                observ,
                mesa.nom AS nom_mesa
            FROM pedido
            INNER JOIN mesa 
                ON mesa.id_mesa = pedido.id_mesa
            WHERE entregado = 0 
            AND terminado = 0
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }


    /* Listar los pedidos que ya fueron terminados por los cocineros
    pero que falta por ser entregados a los clientes */
    function listarPedTerminados()
    {

        $sql = 
            "SELECT 
                id_pedido, 
                pedido.id_mesa, 
                observ,
                mesa.nom AS nom_mesa
            FROM pedido
            INNER JOIN mesa 
                ON mesa.id_mesa = pedido.id_mesa
            WHERE terminado = 1
            AND entregado = 0
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }


    function listarProdPedido($idPedido)
    {
        $sql = 
            "SELECT
                id_det,
                det_cant,
                id_det_prod,
                id_det_pedido,
                producto.nombre AS nombprod,
                producto.prod_pres,
                present.nom AS presnom
            FROM det_pedido 
            INNER JOIN producto
                ON producto.id_prod = det_pedido.id_det_prod
            INNER JOIN present
                ON present.id_present = producto.prod_pres
            WHERE id_det_pedido = :idPedido
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':idPedido' => $idPedido
        ]);
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    /* Consulytar el nombre del producto */
/*     function ConsultarNomProducts($idProd)
    {

        $sql = "SELECT producto.nombre as nom, present.nom AS presnom, prod_pres 
        FROM producto 
        JOIN present ON prod_pres = id_present
        WHERE id_prod = :idProd";
        $query = $this->acceso->prepare($sql);
        $query->execute([':idProd' => $idProd]);
        $this->objetos = $query->fetchall();
        return $this->objetos;
    } */

    function cambiarEstTerminado($idOrden, $id_coc_lider)
    {
        $sql = "UPDATE pedido SET terminado = 1, id_coc_lider = :id_coc_lider 
        WHERE id_pedido = :idPedido";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':idPedido' => $idOrden,
            ':id_coc_lider' => $id_coc_lider
        ]);
        echo 'edit';
    }

    function cambiarEstEntregado($idOrden)
    {
        $sql = "UPDATE pedido SET entregado = 1 WHERE id_pedido = :idPedido";
        $query = $this->acceso->prepare($sql);
        $query->execute([':idPedido' => $idOrden]);
        echo 'edit';
    }

    function cambiarEstPagado($idOrden, $id_cajero)
    {
        $sql = "UPDATE pedido SET pagado = 1, id_cajero = :id_cajero
        WHERE id_pedido = :idPedido";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':idPedido' => $idOrden,
            ':id_cajero' => $id_cajero
        ]);
        echo 'edit';
    }

    /* Bloquear la mesa */
    public function bloquearMesa($id_mesa){
        $sql = "UPDATE mesa SET disponible = 0 WHERE id_mesa=:id_mesa";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_mesa' => $id_mesa));
    }


    /* Desbloquear la mesa */
    public function desBloquearMesa($id_mesa){
        $sql = "UPDATE mesa SET disponible = 1 WHERE id_mesa=:id_mesa";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_mesa' => $id_mesa));
    }

    public function editarCantItem($idOrden,$idItem,$itemCant){
        $sql = 
            "UPDATE det_pedido 
            SET det_cant = :itemCant
            WHERE id_det_pedido = :idOrden
                AND id_det_prod = :idItem
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':idOrden' => $idOrden,
            ':itemCant' => $itemCant,
            ':idItem' => $idItem
        ));
        echo 'edit';

    }

    //Verificar que ese item ya no este asignado a esa orden
    function verificarItemRepetido($idItem,$idOrden){
        $sql = "SELECT id_det_prod
            FROM det_pedido 
            WHERE id_det_pedido = :idOrden
            AND id_det_prod = :idItem
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            'idItem'   => $idItem,
            'idOrden' => $idOrden
        ));
        $this->objetos = $query->fetchall();

        return !empty($this->objetos) ? true : false;
    }


    /* agregarNewItem($idOrden, $newItem->id_prod, $newItem->nombre, $newItem->cantidad); */
    function agregarNewItem($idOrden, $idItem, $cantidad){

        /* Agregar a la tabla ingredintes */
        $sql = 
            "INSERT INTO det_pedido(
                id_det_pedido,
                id_det_prod, 
                det_cant
            )VALUES(
                :idOrden, 
                :idItem,
                :cantidad
            )             
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':idOrden' => $idOrden,
            ':idItem'    => $idItem,
            ':cantidad'    => $cantidad,
        ));
    }

    function borrarItemOrden($idItem){

        $sql = 
        "DELETE FROM det_pedido 
            WHERE id_det_prod = :idItem
        ";

        $query = $this->acceso->prepare($sql);
        $query->execute(array(':idItem' => $idItem));

        if(!empty($query->execute(array(':idItem' => $idItem)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }

    }


    function listarOrdenMesa($idMesa)
    {
        $sql = 
            "SELECT 
                id_pedido 
            FROM pedido

            WHERE entregado = 0 
            AND terminado = 0
            AND pagado = 0
            AND id_mesa = :idMesa
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':idMesa' => $idMesa));
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }


    function listarPedidoMesa($idMesa)
    {
        $sql = 
            "SELECT 
            det_pedido.id_det,
            det_pedido.det_cant,
            det_pedido.id_det_prod,
            det_pedido.id_det_pedido,
            producto.nombre AS nombprod,
            producto.prod_pres,
            present.nom AS presnom,

            pedido.id_pedido, 
            pedido.id_mesa, 
            pedido.observ,
            mesa.nom AS nom_mesa
        FROM pedido
        INNER JOIN mesa 
            ON mesa.id_mesa = pedido.id_mesa
        INNER JOIN det_pedido
            ON det_pedido.id_det_pedido = pedido.id_pedido
            
        INNER JOIN producto
            ON producto.id_prod = det_pedido.id_det_prod
            
       INNER JOIN present
            ON present.id_present = producto.prod_pres
        
        WHERE entregado = 0 
        AND terminado = 0
        AND pagado = 0
        AND mesa.id_mesa = :idMesa
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':idMesa' => $idMesa));
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }


    function listarProdPedidoMesa($idPedido)
    {
        $sql = 
            "SELECT
                id_det,
                det_cant,
                id_det_prod,
                id_det_pedido,
                producto.nombre AS nombprod,
                producto.prod_pres,
                present.nom AS presnom
            FROM det_pedido 
            INNER JOIN producto
                ON producto.id_prod = det_pedido.id_det_prod
            INNER JOIN present
                ON present.id_present = producto.prod_pres
            INNER JOIN pedido
                ON pedido.id_pedido = det_pedido.id_det_pedido
            WHERE id_det_pedido = :idPedido
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':idPedido' => $idPedido
        ]);
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    /* 
                    id_pedido, 
                pedido.id_mesa, 
                observ,
                mesa.nom AS nom_mesa
            FROM pedido
            INNER JOIN mesa 
                ON mesa.id_mesa = pedido.id_mesa
            WHERE entregado = 0 
            AND terminado = 0 */


    function listarMesas(){
        if(!empty($_POST['consulta'])){
            $consulta = $_POST['consulta'];
            $sql="SELECT * FROM mesa WHERE nom LIKE :consulta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchall();
            return $this->objetos;
        }else{
            $sql = "SELECT * FROM mesa WHERE nom NOT LIKE '' ORDER BY nom";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchall();
            return $this->objetos;
        }
    }


    function listarItemsOrden($idPedido)
    {
        $sql = 
            "SELECT
                id_det,
                det_cant,
                id_det_prod,
                id_det_pedido,
                producto.nombre AS nombprod,
                producto.prod_pres,
                present.nom AS presnom,
                pedido.id_mesa
            FROM det_pedido 
            INNER JOIN producto
                ON producto.id_prod = det_pedido.id_det_prod
            INNER JOIN present
                ON present.id_present = producto.prod_pres
            INNER JOIN pedido
                ON pedido.id_pedido = det_pedido.id_det_pedido
            WHERE id_det_pedido = :idPedido
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':idPedido' => $idPedido
        ]);
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    function cargarObservOrden($idOrden)
    {
        $sql = 
            "SELECT observ
            FROM pedido
            WHERE id_pedido = :idOrden
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':idOrden' => $idOrden));
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    
}
