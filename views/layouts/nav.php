<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

<!-- **************************************************** -->
  <!-- Navbar -->
  <nav id="barrasup" class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->

    <!-- <p><?php echo $_COOKIE['tk'] ?></p> -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="main.php" class="nav-link">Inicio</a>
      </li>

      <li id="cat-carrito" class="nav-item dropdown" style="display:none">
        <a class="tx-carrito nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Pedido
        </a>
        <span id="contador" class="contador badge badge-danger"></span>
        <div class="dropdown-menu mdl-mdrn dropdown-menu-orden" aria-labelledby="navbarDropdownMenuLink">
          <table class="carro table table-over text-nowrap p-0">
            <thead class="table-success">
              <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Eliminar</th>
              </tr>
            </thead>
            <tbody id="tbd-lista"></tbody>
          </table>
          <div class="form-group">
            <label for="observ">Observaciones</label>
            <textarea class="form-control" id="observ" rows="3"></textarea>
          </div>

          <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
          <a href="#" id="procesar-orden" class="btn btn-outline-danger btn-block">Procesar Pedido</a>
          <a href="#" id="vaciar-pedido" class="btn btn-outline-primary btn-block">Vaciar Pedido</a>

        </div>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <?php
          $urlActual = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

          if($urlActual != 'localhost/rfood/views/resum_venta_dia.php'){
            ?>
            <a href="resum_venta_dia.php" class="btn btn-outline-secondary">Cerrar</a>
            <?php
          }else{
            ?>
            <a id="logout" href="../controllers/logout.php"><img src="../public/icons/close_32.png" title="Cerrar Sesión" alt=""></a>
            <?php
          }
        ?>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <!-- *************************************************** -->

<?php
  include_once "layouts/main-menu.php";
?>