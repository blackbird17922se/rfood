<?php
include 'conexion.php';
class InvTipo{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }

    function crearTipo($nom){
        $sql = "SELECT id_inv_tipo FROM inv_tipo_prod WHERE nom = :nom";
        $query = $this->acceso->prepare($sql);
        $query->execute(array('nom' => $nom));
        $this->objetos=$query->fetchall();
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            $sql = "INSERT INTO inv_tipo_prod(nom) VALUES (:nom)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array('nom' => $nom));
            echo 'add';
        }
    }

    function consultarTipos(){
        if(!empty($_POST['consulta'])){
            $consulta = $_POST['consulta'];
            $sql="SELECT * FROM inv_tipo_prod WHERE nom LIKE :consulta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchall();
            return $this->objetos;
        }else{
            $sql = "SELECT * FROM inv_tipo_prod WHERE nom NOT LIKE '' ORDER BY id_inv_tipo";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchall();
            return $this->objetos;
        }
    }

    function editarTipo($nom,$id_editado){
        $sql = "UPDATE inv_tipo_prod SET nom = :nom WHERE id_inv_tipo = :id_inv_tipo";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_inv_tipo' => $id_editado,':nom' => $nom));
        echo 'edit';
    }

    
    function borrarTipo($id_inv_tipo){
        $sql = "DELETE FROM inv_tipo_prod WHERE id_inv_tipo = :id_inv_tipo";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_inv_tipo' => $id_inv_tipo));
        // echo 'borrado';

        if(!empty($query->execute(array(':id_inv_tipo' => $id_inv_tipo)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }

    }

    function listarInvTipos(){
        $sql="SELECT * FROM inv_tipo_prod ORDER BY nom ASC";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }
}