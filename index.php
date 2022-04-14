<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login RFood</title>
    <link rel="shortcut icon" href="public/icons/favicon.ico" type="image/x-icon">

    <!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="public/css/login.css">
    <!-- font awess -->
    <!-- <link rel="stylesheet" href="public/css/css/all.min.css"> -->
    <link rel="stylesheet" href="public/css/adminlte.min.css">
</head>
<?php
session_start();
// si session esta vacia..
if(!empty($_SESSION['rol'])){
    header("Location: controllers/loginController.php");
}else{
    session_destroy();

?>
<body id="body-login">
    <div class="container">
        <div class="row" id="row-login">
            <div class="col-lg-4 col-lg-offset-7 col-md-offset-2  col-sm-12">
                <h1 class="logo-login">RFood</h1>
                <div class="box-login">

                    <div class="box-form">

                        <form action="controllers/loginController.php" method="POST">
                            <div class="form-group">
                                <input type="number" placeholder="Identificación del Usuario" title="Escriba el numero de identificación con el cual se registró en RFood" name="user" class="form-control input" required>               
                            </div>
                            <div class="form-group">
                                <input type="password" title="Ingrese su contraseña" placeholder="Contraseña" name="pass" class="form-control input" required>
                            </div>                            
                        </div>
                        <input type="submit" id="btn-log" value="Ingresar">
                    </form>
                </div>
            </div>
        </div> 
    </div>
</body>
</html>

<?php
}

?>