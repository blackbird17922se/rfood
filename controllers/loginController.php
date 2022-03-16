<?php
include_once "../models/usuario.php";
session_start();
$user = $_POST['user'];
$pass = $_POST['pass'];

$usuario = new Usuario();

// si hay una sesion en curso..
if(!empty($_SESSION['rol'])){
    switch ($_SESSION['rol']) {
        // enrutar
        case 1:
            header("Location: ../views/main.php");
        break;
        case 2:
            header("Location: ../views/main.php");
        break;
    }  

}else{

    // ejecutar consulta
    if(!empty($usuario->logIn($user,$pass)=="logueado")){
        $usuario->obtenerDatosLog($user);
        foreach($usuario->objetos as $objeto){
            $_SESSION['usuario'] = $objeto->id_usu;
            $_SESSION['rol'] = $objeto->rol;
            $_SESSION['nom'] = $objeto->nom;
        }

        $token = sha1(uniqid(rand(), true));

        setcookie("tk",$token,time()+(60*60*24*31),"/");
        $_SESSION['token'] = $token;


        switch ($_SESSION['rol']) {
            
            // admin
            case 1:
                header("Location: ../views/orden.php");
            break;

            // SubAdmin
            case 2:
                header("Location: ../views/orden.php");
            break;

            // Cajero
            case 3:
                header("Location: ../views/caja.php");
            break;

            // Cocinero Lider
            case 4:
                header("Location: ../views/pedido.php");
            break;

            // Mesero
            case 5:
                header("Location: ../views/orden.php");
            break;
        }  
    }else{
        header("Location: ../index.php");
    }
}