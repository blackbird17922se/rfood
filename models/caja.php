<?php

include 'conexion.php';

class Caja{
    var $objetos;

    public function __construct()
    {
        $bd = new Conexion();
        $this->acceso = $bd->pdo;
    }

    /* Listar los pedidos que ya se elaborron y se entregaron a los clientes */
    function listarPedidosCaja(){

        $sql = "SELECT * FROM pedido WHERE entregado = 1 AND terminado = 1";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }

    function cargarDatosPedido($idPedido){
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

            $sql = "SELECT producto.nombre as nom, present.nom AS presnom, prod_pres, precio 
            FROM producto 
            JOIN present ON prod_pres = id_present
            WHERE id_prod = :idProd";
            $query = $this->acceso->prepare($sql);
            $query->execute([':idProd' => $idProd]);
            $this->objetos=$query->fetchall();
            return $this->objetos;
            
        }





} 