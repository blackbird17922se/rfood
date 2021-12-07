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
            ':idNuevoItem'    => $idNuevoItem,
            ':idIngred'  => $idIngred,
            ':nomIngred' => $nomIngred,
            ':medida'    => $medida,
            ':cantidad'  => $cantidad,
        ));
        echo 'add_Ingred_item / ';

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

}