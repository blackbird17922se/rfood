<?php
session_start();
if (!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 4)) {
  include_once "layouts/header.php";
  include_once "layouts/nav.php";
?>

<!-- modal items en orden de la mesa -->
<div class="modal fade" id="verOrden" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <div class="col-sm-12">
            <div class="row">
            <img src="../public/icons/calculator_32.png" title="caja">
              <h1 class="ml-2">Caja</h1>
            </div>
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
            <div id="tb_Ordenmesas" class="row d-flex align-items-stretch"></div>
          </div>
        </div>
      </div>
    </section>

    <div class="card-body p-0">
                                <div id="cp" class="card-body p-0">
                                    <div class="card-body">
                                        <div id="cb-pedidos" class="row d-flex align-items-stretch"></div>


                                        <div>
                                            <!-- 
                                                class="caja: permite el diseño responsive de la tabla,
                                                llama al archivo caja.css
                                            -->
                                            <table class="caja table table-hover text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Presentación</th>
                                                        <th scope="col">Cantidad</th>
                                                        <th scope="col">Precio Unid.</th>
                                                        <th scope="col">Sub Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lista-compra" class='table-active'>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                    <h3>Forma de pago</h3>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="fpago" id="efectivo" value="0" checked>
                                        <label class="form-check-label" for="inlineRadio1">Efectivo</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="fpago" id="tarjeta" value="1">
                                        <label class="form-check-label" for="inlineRadio2">Tarjeta</label>
                                    </div>

                                    <div id="cpago">
                                    </div>




                                    <div class="row mt-4">

                                        <div class="col-md-4">
                                            <div class="card card-default">
                                                <div class="card-body">
                                                    <div class="info-box mb-3 bg-info">
                                                        <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text text-left">TOTAL</span>
                                                            <span class="info-box-number" id="total"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card card-default">
                                                <div class="card-body">
                                                    <div class="info-box mb-3 bg-success">
                                                        <span class="info-box-icon"><i class="fas fa-money-bill-alt"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text text-left ">INGRESO</span>
                                                            <input type="number" id="pago" min="1" placeholder="Ingresa Dinero" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card card-default">
                                                <div class="card-body">
                                                    <div class="info-box mb-3 bg-info">
                                                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text text-left ">VUELTO</span>
                                                            <span class="info-box-number" id="vuelto">3</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                    </div>
                                </div>
                                <div class="row justify-content-between">

                                    <div class="col-xs-12 col-md-4">
                                        <a href="#" class="btn btn-success btn-block" id="procesar-compra">Realizar venta</a>
                                    </div>
                                </div>
                            </div>
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
<script src="../public/js/cajaNueva.js"></script>