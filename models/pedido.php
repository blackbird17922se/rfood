<?php

include 'conexion.php';

class Pedido{
    var $objetos;

    public function __construct()
    {
        $bd = new Conexion();
        $this->acceso = $bd->pdo;
    }

    function nuevoPedido($id_mesa,$entregado,$terminado){
        $sql = "INSERT INTO pedido (id_mesa, entregado, terminado) VALUES (:id_mesa,:entregado,:terminado)";
        $query = $this->acceso->prepare($sql);
        $query->execute([      
            ':id_mesa'   => $id_mesa,
            ':entregado' => $entregado,
            ':terminado' => $terminado
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

        $sql = "SELECT id_pedido, id_mesa FROM pedido WHERE entregado = 0";
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
} 