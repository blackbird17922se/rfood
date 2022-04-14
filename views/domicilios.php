<?php
session_start();
if (!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 4)) {
  include_once "layouts/header.php";
  include_once "layouts/nav.php";
?>

  <!-- modal items en orden de la mesa -->
  <div class="modal fade" id="verOrden" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">

        <div class="card card-mdrn">
          <div class="card-header">
            <div class="card-title">Detalle de la Orden</div>
            <button data-dismiss="modal" aria-label="close" class="close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-hover-mdr10 text-nowrap">
              <thead class="table-success">
                <tr>
                  <th>Cantidad</th>
                  <th>Producto</th>
                  <th>Presentacion</th>
                </tr>
              </thead>
              <tbody class="table-active" id="registros"></tbody>
            </table>

            <div class="form-group">
              <label for="observCli">Observaciones del Pedido: </label>
              <p id="observCli"></p>
            </div>
          </div>
          <div class="card-footer">
            <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cnt-wrp-mdrn">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <div class="row">
              <img src="../public/icons/softwarecenter.png" alt="">
              <h1 class="ml-2">Domicilios</h1>
            </div>
          </div>
          <div class="col-sm-6">
            <button id="nuevoDomicilio" class='btn btn-sm btn-success'>
              <i class='fas fa-plus-square mr-2'></i>Nuevo Domicilio
            </button>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


    <!-- ********************** TABLA Pedidos ************************* -->
    <!-- Main content -->
    <section>
      <div class="container-fluid">
        <div class="card card-success card-mdrn">
          <div class="card-body">
            <div id="tb_domicios" class="row d-flex align-items-stretch"></div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
  include_once "layouts/footer.php";
} else {
  session_destroy();
  header("Location: ../index.php");
}
?>
<script src="../public/js/datatables.js"></script>
<script src="../public/js/domicilios.js"></script>