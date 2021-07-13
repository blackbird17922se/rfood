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

        $sql = "SELECT * FROM pedido WHERE entregado = 1 AND terminado = 1 AND pagado = 0";
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


    function crearVenta($total, $formaPago, $fecha,$vendedor, $idMesero, $idCocineroLider){

        $sql = "INSERT INTO venta(total, formpago, fecha, vendedor, id_mesero, id_coc_lider) 
        VALUES (:total, :formaPago, :fecha, :vendedor, :id_mesero, :id_coc_lider)";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':fecha'        => $fecha,
            ':total'        => $total,
            ':formaPago'   => $formaPago,
            ':vendedor'     => $vendedor,
            ':id_mesero'    => $idMesero,
            ':id_coc_lider' => $idCocineroLider
        ));
        echo 'add';
    }


    function ultimaVenta(){
        $sql="SELECT MAX(id_venta) AS ultima_venta FROM venta";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    function agregarDetVenta($cantidad, $idProd, $idVenta){

        $sql = "INSERT INTO det_venta(det_cant, id_det_prod, id_det_venta) 
        VALUES (:det_cant, :id_det_prod, :id_det_venta)";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':det_cant'       => $cantidad,
            ':id_det_prod'        => $idProd,
            ':id_det_venta'  => $idVenta
        ));
        echo 'add';
    }


    // function consultarDatosProducto($idProd){

    //     $sql = "SELECT id_prod, codbar, producto.nombre as nombre, compos, iva, precio, tipo_prod.nom AS tipo, present.nom AS presentacion, prod_tipo, prod_pres
    //     FROM producto
    //     JOIN tipo_prod ON prod_tipo = id_tipo_prod
    //     JOIN present ON prod_pres = id_present AND producto.nombre 
    //     WHERE id_prod = :id_prod";
    //     $query = $this->acceso->prepare($sql);
    //     $query->execute([':id_prod' => $idProd]);
    //     $this->objetos=$query->fetchall();
    //     return $this->objetos;
        
    // }

    function consultarPrecio($idProd){
        $sql = "SELECT precio FROM producto WHERE id_prod = :id_prod";
        $query = $this->acceso->prepare($sql);
        $query->execute([':id_prod'=>$idProd]);
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }


    function insertRegVenta($precio, $cantidad, $subtotal, $idProd, $idVenta){
        $sql = "INSERT INTO venta_prod(precio,cant,subtotal,prod_id_prod,venta_id_venta)  
        VALUES (:precio, :cant, :subtotal, :prod_id_prod, :venta_id_venta)";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':precio'       => $precio,
            ':cant'       => $cantidad,
            ':subtotal'       => $subtotal,
            ':prod_id_prod'        => $idProd,
            ':venta_id_venta'  => $idVenta
        ));
        echo 'add';

    }


    /* cargar los id del mesero y coninero encargados de ese pedido */
    function cargarMeseroCocinero($idPedido){
        $sql = "SELECT id_mesero, id_coc_lider FROM pedido WHERE id_pedido = :idPedido";
        $query = $this->acceso->prepare($sql);
        $query->execute([':idPedido'=>$idPedido]);
        $this->objetos=$query->fetchall();
        return $this->objetos;

    }




} 