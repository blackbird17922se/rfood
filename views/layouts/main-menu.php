<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

<!-- <div class="image">
        <img src="../public/img/logo.png" class="logosf d-block" alt="User Image">
      </div> -->
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <!-- <img src="../public/img/logo.png" alt="AdminLTE Logo" class="brand-image" > -->
    <!-- <span class="brand-text font-weight-light">Codename Rfood</span> -->
    <h1 id="logo">RFood</h1>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->


   

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

<!--         <li class="nav-item">
          <a href="orden.php" class="nav-link">
            <img src="../public/icons/text-editor.png" alt="">
            <p>
              Nueva Orden
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="pedido.php" class="nav-link">
            <img src="../public/icons/stack_32.png" alt="Pedidos por recoger">
            <p>
              Pedidos
            </p>
          </a>
        </li> -->
        
        <li class="nav-item">
          <a href="ordenMesas.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-cash-register"></i> -->
            <img src="../public/icons/text-editor.png" alt="">
            <p>
              Ordenes
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="domicilios.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-cash-register"></i> -->
            <img src="../public/icons/softwarecenter.png" alt="">
            <p>
              Domicilios
            </p>
          </a>
        </li>

<!--         <li class="nav-item">
          <a href="pedidoterm.php" class="nav-link">
            <img src="../public/icons/cs-notifications.png" alt="Pedidos por recoger">
            <p>
              Por Recoger
            </p>
          </a>
        </li> -->

        <li class="nav-item">
          <a href="caja.php" class="nav-link">
          <img src="../public/icons/calculator_32.png" title="caja">
            <!-- <i class="nav-icon fas fa-cash-register"></i> -->
            <p>
              Caja
            </p>
          </a>
        </li>

        <li class="nav-header">Usuario</li>
        <!-- items que contiene la seccion Ventas -->
        <li class="nav-item">
          <a href="editar_perfil.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-user-cog"></i> -->
            <img src="layouts/user-home.png" alt="">
            <p>
              Mi Perfil
            </p>
          </a>
        </li>

        <?php if($_SESSION['rol'] == 1){?>
        <li class="nav-item">
          <a href="adm_usuario.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-user-cog"></i> -->
            <img src="layouts/cs-user.png" alt="">
            <p>
              Gestión de Usuarios
            </p>
          </a>
        </li>

        <?php }?>

     
        <li class="nav-header">Menú y Carta</li>
        <li class="nav-item">
          <a href="adm_menu.php" class="nav-link">
          <img src="../public/icons/gtk-paste.png">
            <!-- <i class="nav-icon fas fa-hamburger"></i> -->
            <p>
              Menú
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="atributos_menu.php" class="nav-link">
          <img src="../public/icons/applications-other.png">
            <!-- <i class="nav-icon fas fa-toolbox"></i> -->
            <p>
              Atributos Menú
            </p>
          </a>
        </li>

        <li class="nav-header">Inventario</li>
        <li class="nav-item">
          <a href="ingred.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-pills"></i> -->
            <img src="../public/icons/softwarecenter.png">
            <p>
              Ingredientes
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="inv_atr.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-vials"></i> -->
            <img src="../public/icons/applications-science.png">
            <p>
              Atributos Ingredientes
            </p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="inv_lote.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-cubes"></i> -->
            <img src="../public/icons/network-vpn.png">
            <p>
              Lotes Ingredientes
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="inv_riesgo.php" class="nav-link">
            <i class="nav-icon fas fa-cubes"></i>
            <p>
              Lotes en Riesgo
            </p>
          </a>
        </li>


        <li class="nav-header">Compras</li>
        <li class="nav-item">
          <a href="adm_proveed.php" class="nav-link">
            <i class="nav-icon fas fa-truck"></i>
            <p>
              Compras a proveedores
            </p>
          </a>
        </li>

        <!-- aqui va la gestion de ventas -->
        <li class="nav-header">Ventas</li>
        <li class="nav-item">
          <a href="adm_venta.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-notes-medical"></i> -->
            <img src="../public/icons/accessories-dictionary.png">
            <p>
              Gestión de Ventas
            </p>
          </a>
        </li>

        <!-- OTROS -->
        <li class="nav-header">Otros</li>
        <li class="nav-item">
          <a href="descuadreInventario.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-pills"></i> -->
            <img src="../public/icons/dialog-warning.png">
            <p>
              Descuadre Inventario
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="mesa.php" class="nav-link">
            <img src="../public/icons/edit-select-all.png">
            <p>
              Mesas
            </p>
          </a>
        </li>

        <!-- iNSERTADO A CAUSA DEL MOLESTA BARRA QUE APARECE ABAJO CAUNDO ESTA EN PROD -->
        <li class="nav-item"><a class="nav-link"><p></p></a></li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>