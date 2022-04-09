<?php
include 'conexion.php';
class Mesa{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso=$db->pdo;
    }

    function crear($nom){
        $sql = "SELECT id_mesa FROM mesa WHERE nom = :nom";
        $query = $this->acceso->prepare($sql);
        $query->execute(array('nom' => $nom));
        $this->objetos=$query->fetchall();
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            $sql = "INSERT INTO mesa(nom) VALUES (:nom)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array('nom' => $nom));
            echo 'add';
        }
    }

    function buscar(){
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

    function borrar($id_mesa){
        $sql = "DELETE FROM mesa WHERE id_mesa=:id_mesa";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_mesa' => $id_mesa));
        // echo 'borrado';

        if(!empty($query->execute(array(':id_mesa' => $id_mesa)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }

    }

    function editar($nom,$id_editado){
        $sql = "UPDATE mesa SET nom = :nom WHERE id_mesa=:id_mesa";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_mesa' => $id_editado,':nom' => $nom));
        echo 'edit';
    }

    function listarMesas(){
        // $consulta = $_POST['consulta'];
        $sql="SELECT * FROM mesa ORDER BY nom ASC";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchall();
        return $this->objetos;
    }
}