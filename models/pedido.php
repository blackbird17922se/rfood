<?php

include 'conexion.php';

class Pedido{
    var $objetos;

    public function __construct()
    {
        $bd = new Conexion();
        $this->acceso = $bd->pdo;
    }

    function nuevoPedido($id_mesa, $id_mesero, $observ, $entregado,$terminado, $pagado){
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

    function ultimoPedido(){
        $sql="SELECT MAX(id_pedido) AS ultimo_pedido FROM pedido";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    function listarPedidosPendEntrega(){

        $sql = "SELECT id_pedido, id_mesa, observ FROM pedido WHERE entregado = 0 AND terminado = 0";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }


    /* Listar los pedidos que ya fueron terminados por los cocineros
    pero que falta por ser entregados a los clientes */
    function listarPedTerminados(){

        $sql = "SELECT id_pedido, id_mesa FROM pedido WHERE terminado = 1 AND entregado = 0";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }


    function listarProdPedido($idPedido){
        $sql = "SELECT * FROM det_pedido WHERE id_det_pedido = :idPedido";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':idPedido' => $idPedido
        ]);
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }

    /* Consulytar el nombre del producto */
    function ConsultarNomProducts($idProd){

        $sql = "SELECT producto.nombre as nom, present.nom AS presnom, prod_pres 
        FROM producto 
        JOIN present ON prod_pres = id_present
        WHERE id_prod = :idProd";
        $query = $this->acceso->prepare($sql);
        $query->execute([':idProd' => $idProd]);
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }

    function cambiarEstTerminado($idOrden, $id_coc_lider){
        $sql = "UPDATE pedido SET terminado = 1, id_coc_lider = :id_coc_lider 
        WHERE id_pedido = :idPedido";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':idPedido'=> $idOrden,
            ':id_coc_lider'=> $id_coc_lider
        ]);
        echo 'edit';
    }

    function cambiarEstEntregado($idOrden){
        $sql = "UPDATE pedido SET entregado = 1 WHERE id_pedido = :idPedido";
        $query = $this->acceso->prepare($sql);
        $query->execute([':idPedido'=> $idOrden]);
        echo 'edit';
    }

    function cambiarEstPagado($idOrden, $id_cajero){
        $sql = "UPDATE pedido SET pagado = 1, id_cajero = :id_cajero
        WHERE id_pedido = :idPedido";
        $query = $this->acceso->prepare($sql);
        $query->execute([
            ':idPedido'=> $idOrden,
            ':id_cajero'=> $id_cajero
        ]);
        echo 'edit';
    }
} 