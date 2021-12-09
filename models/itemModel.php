<?php

/** 
 * itemModel.php
 * Consultas que administra los item del menu o carta
*/

include 'conexion.php';
class ItemModel{
    var $objetos;
    var $tabla = 'ingred';
    
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }


    function crearItem($codbar, $cat_item, $nombreItem, $pres_item, $precio, $iva){

        $sql = "SELECT id_prod FROM producto WHERE codbar = :codbar";

        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':codbar'       => $codbar
        ));
        $this->objetos=$query->fetchall();
        /* Si encuentra el producto entonces no agregarlo */
        if(!empty($this->objetos)){
            return false;
        }else{
            $sql = "INSERT INTO producto(codbar, nombre, prod_tipo, prod_pres, precio, iva) 
            VALUES (:codbar, :nombre, :prod_tipo, :prod_pres, :precio, :iva)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(
                ':codbar'       => $codbar,
                ':nombre'       => $nombreItem,
                ':prod_tipo'    => $cat_item,
                ':prod_pres'    => $pres_item,
                ':precio'       => $precio,
                ':iva'          => $iva
            ));

            return true;
        }


        
    }


    function listarItems(){

        $sql = "SELECT id_prod, codbar, producto.nombre as nombre,  iva, precio, tipo_prod.nom AS tipo, present.nom AS presentacion, prod_tipo, prod_pres
        FROM producto
        JOIN tipo_prod ON prod_tipo = id_tipo_prod
        JOIN present ON prod_pres = id_present AND producto.nombre NOT LIKE ''
        ORDER BY producto.nombre
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }


    /* Listar productos recibiendo la categoria de producto a listar.
    Usada en la toma de la orden */
    function listarItemsCateg($idCat){

        $sql = "SELECT id_prod, codbar, tipo_prod.nom AS categ, nombre,  prod_pres, present.nom AS present, precio 
        FROM producto 
        JOIN tipo_prod ON prod_tipo = id_tipo_prod
        JOIN present ON prod_pres = id_present
        WHERE prod_tipo = :idCat";
        $query = $this->acceso->prepare($sql);
        $query->execute([':idCat' => $idCat]);
        $this->objetos=$query->fetchall();
        return $this->objetos;
        
    }


    function editarItem($id,$nombre, $prod_tipo,$prod_pres,$precio,$iva){
        $sql = "UPDATE producto SET
                nombre    = :nombre, 
                prod_tipo = :prod_tipo, 
                prod_pres = :prod_pres,
                precio    = :precio, 
                iva       = :iva
            WHERE id_prod = :id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':id'        => $id,
            ':nombre'    => $nombre,
            ':prod_tipo' => $prod_tipo,
            ':prod_pres' => $prod_pres,
            ':precio'    => $precio,
            ':iva'       => $iva,
        ));
        echo 'edit';
    }


    function borrarItem($id){
        $sql = "DELETE FROM producto WHERE id_prod = :id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id));

        if(!empty($query->execute(array(':id' => $id)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }
    }


    public function listarDatosItem($idItem){
        $sql = "SELECT codbar, nombre, iva, precio
        FROM producto
        WHERE id_prod = :id
      
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id'=>$idItem));
        $this->objetos=$query->fetchall();
        return $this->objetos;

    }


    function cargarUltimoItemReg(){

        $sql="SELECT MAX(id_prod) AS ultimoreg FROM producto";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;

    }



    /* *************************************************************************************************************** */
    /* ************************************** FUNCIONES MANEJO INGREDS ITEM ****************************************** */
    /* *************************************************************************************************************** */

    public function listarIngredsItem($idItem){
        $sql = "SELECT * FROM ITEMENUINGR WHERE ID_ITEM = :idItem";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':idItem' => $idItem));
        $this->objetos=$query->fetchall();
        return $this->objetos;

    }


    function agregarNIngredItem($idNuevoItem, $idIngred, $nomIngred, $medida, $cantidad){

        /* Agregar a la tabla ingredintes */
        $sql = "INSERT INTO ITEMENUINGR(ID_ITEM, ID_INGR, NOM_INGR,
            MEDIDA_INGR, CANT_INGR)
            VALUES(
                :idNuevoItem, :idIngred, :nomIngred, :medida, :cantidad
            )              
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':idNuevoItem' => $idNuevoItem,
            ':idIngred'    => $idIngred,
            ':nomIngred'   => $nomIngred,
            ':medida'      => $medida,
            ':cantidad'    => $cantidad,
        ));
    }


    function editarCantIngredItem($ingred_cant, $id_editado){
        $sql = 
        "UPDATE ITEMENUINGR 
            SET CANT_INGR = :ingred_cant 
            WHERE ID_IT_MENU_INGR = :id_editado
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            ':id_editado'  => $id_editado, 
            ':ingred_cant' => $ingred_cant
        ));
        echo 'edit';
    }


    function borrarIngredItem($id_ingred){

        $sql = 
        "DELETE FROM ITEMENUINGR 
            WHERE ID_IT_MENU_INGR = :id_ingred
        ";

        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_ingred' => $id_ingred));

        if(!empty($query->execute(array(':id_ingred' => $id_ingred)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }

    }


    //Verificar que ese ingrediente ya no este asignado a ese item
    function verificarIngredRepetido($idIngred,$idItemMenu){
        $sql = "SELECT ID_INGR
            FROM ITEMENUINGR 
            WHERE ID_ITEM = :idItemMenu
            AND ID_INGR = :idIngred
        ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(
            'idIngred'   => $idIngred,
            'idItemMenu' => $idItemMenu
        ));
        $this->objetos = $query->fetchall();

        return !empty($this->objetos) ? true : false;
    }

}