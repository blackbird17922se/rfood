<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <h1 id="logo">RFood</h1>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

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

        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 3) { ?>
        <li class="nav-item">
          <a href="caja.php" class="nav-link">
            <img src="../public/icons/calculator_32.png" title="caja">
            <!-- <i class="nav-icon fas fa-cash-register"></i> -->
            <p>
              Caja
            </p>
          </a>
        </li>
        <?php } ?>

        <li class="nav-header">Usuario</li>
        <!-- items que contiene la seccion Ventas -->
        <li class="nav-item">
          <a href="perfilUsuario.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-user-cog"></i> -->
            <img src="layouts/user-home.png" alt="">
            <p>
              Mi Perfil
            </p>
          </a>
        </li>

        <?php if ($_SESSION['rol'] == 1) { ?>
          <li class="nav-item">
            <a href="usuarios.php" class="nav-link">
              <!-- <i class="nav-icon fas fa-user-cog"></i> -->
              <img src="layouts/cs-user.png" alt="">
              <p>
                Usuarios
              </p>
            </a>
          </li>

        <?php } ?>

        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
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
          <a href="atrIngredientes.php" class="nav-link">
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

        <?php } ?>

        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 3) { ?>
        <!-- aqui va la gestion de ventas -->
        <li class="nav-header">Ventas</li>
        <li class="nav-item">
          <a href="ventas.php" class="nav-link">
            <!-- <i class="nav-icon fas fa-notes-medical"></i> -->
            <img src="../public/icons/accessories-dictionary.png">
            <p>
              Gestión de Ventas
            </p>
          </a>
        </li>
        <?php } ?>

        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
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

        <li class="nav-item">
          <a href="proveedores.php" class="nav-link">
          <img src="../public/icons/applications-other.png">
            <p>
              Proveedores
            </p>
          </a>
        </li>

        <?php } ?>

        <!-- iNSERTADO A CAUSA DEL MOLESTA BARRA QUE APARECE ABAJO CAUNDO ESTA EN PROD -->
        <li class="nav-item"><a class="nav-link">
            <p></p>
          </a></li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>