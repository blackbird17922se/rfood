<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">


<!-- --- -->
<!-- Navbar -->
<!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>

      <li id="cat-carrito" class="nav-item dropdown" style="display:none">
        <a class="tx-carrito nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Carrito
        </a> -->
        <!-- --- -->

<!-- **************************************************** -->
  <!-- Navbar -->
  <nav id="barrasup" class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>

      <li id="cat-carrito" class="nav-item dropdown" style="display:none">
        <a class="tx-carrito nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Carrito
        </a>
        <span id="contador" class="contador badge badge-danger"></span>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <table class="carro table table-over text-nowrap p-0">
            <thead class="table-success">
              <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Presentación</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Eliminar</th>
                <!-- <th>Categoría</th> -->

              </tr>
            </thead>
            <tbody id="tbd-lista"></tbody>
          </table>
          <a href="#" id="procesar-pedido" class="btn btn-danger btn-block">Procesar Pedido</a>
          <a href="#" id="vaciar-carrito" class="btn btn-primary btn-block">Vaciar Carrito</a>

        </div>
      </li>



      

      <!-- Menus barra  superior, desabilitados momentaneamente -->

      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->

     

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- <a id="logout" href="#">Cerrar</a> -->
        <button type="button" id="logout" class="btn btn-outline-secondary float-right m-1">Cerrar</button>

        <!-- <a id="logout" href="../controllers/logout.php">Cerrar</a> -->
   
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <!-- *************************************************** -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- <div class="image">
          <img src="../public/img/logo.png" class="logosf d-block" alt="User Image">
        </div> -->
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <!-- <img src="../public/img/logo.png" alt="AdminLTE Logo" class="brand-image" > -->
      <span class="brand-text font-weight-light">Codename Rfood</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->


     

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="adm_cat.php" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Nueva Venta
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="orden.php" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Nueva Orden
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="pedido.php" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Pedidos
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="pedidoterm.php" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Por Recoger
              </p>
            </a>
          </li>

       



          <li class="nav-header">Usuario</li>
          <!-- items que contiene la seccion Ventas -->
          <li class="nav-item">
            <a href="editar_perfil.php" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>
                Mi Perfil
              </p>
            </a>
          </li>

          <?php if($_SESSION['rol'] == 1){?>
          <li class="nav-item">
            <a href="adm_usuario.php" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>
                Gestión de Usuarios
              </p>
            </a>
          </li>

          <?php }?>

       
          <li class="nav-header">Almacen</li>
          <li class="nav-item">
            <a href="adm_product.php" class="nav-link">
              <i class="nav-icon fas fa-pills"></i>
              <p>
                Gestión de productos
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="adm_atr.php" class="nav-link">
              <i class="nav-icon fas fa-vials"></i>
              <p>
                Gestión de Atributos
              </p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="adm_lote.php" class="nav-link">
              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Gestión de Lotes
              </p>
            </a>
          </li> -->

          <!-- <li class="nav-item">
            <a href="adm_riesgo.php" class="nav-link">
              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Lotes en Riesgo
              </p>
            </a>
          </li> -->

          <!-- <li class="nav-header">Compras</li>
          <li class="nav-item">
            <a href="adm_proveed.php" class="nav-link">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Compras a proveedores
              </p>
            </a>
          </li> -->

          <?php if($_SESSION['rol'] == 1){?>
          <li class="nav-header">Ventas</li>
          <!-- items que contiene la seccion Ventas -->
          <li class="nav-item">
            <a href="adm_venta.php" class="nav-link">
              <i class="nav-icon fas fa-notes-medical"></i>
              <p>
                Gestión de Ventas
              </p>
            </a>
          </li>
          <?php } ?>
          <li class="nav-item">
            <a href="adm_venta_dia.php" class="nav-link">
              <i class="nav-icon fas fa-notes-medical"></i>
              <p>
                Ventas del Dia
              </p>
            </a>
          </li>
          <?php if($_SESSION['rol'] == 2){?>
            <!-- MOMENTANEO: REDIRIGIR A OTRA VISTA QUE NO TIENE EL BOTON ELIMINAR VENTA -->
            <li class="nav-header">Ventas</li>
          <!-- items que contiene la seccion Ventas -->
          <li class="nav-item">
            <a href="adm_venta2.php" class="nav-link">
              <i class="nav-icon fas fa-notes-medical"></i>
              <p>
                Gestión de Ventas2
              </p>
            </a>
          </li>
          <?php } ?>



        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>