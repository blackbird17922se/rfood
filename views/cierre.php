<?php
session_start();
if (!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 3)) {
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper cnt-wrp-mdrn">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="row">
                                <img src="../public/icons/accessories-dictionary.png">
                                <h1 class="ml-2">Cierre de Caja</h1>
                            </div>
                        </div>

                        <!-- puede ir el filtrar -->
                        <!--           <div class="col-sm-6 form-group">
              <div class="row">

                <p>Filtrar por: </p>
                <select class="form-control select2 ml-4" aria-label="Default select example" style="width: 50%;">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
              </div>
          </div> -->

                    </div>
                </div><!-- /.container-fluid -->
            </section>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-mdrn">
                            <div class="card-header">
                            <h5> <i class="fas fa-caret-right"></i> <b>Vendedor: </b><span class="val-venta"><?php echo $_SESSION['nom'] ?></span></h5>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">

                                    <!-- panel venta dia -->
                                    <div class="tab-pane active" id="pnlDia">
                                        <div class="card card-success card-mdrn">

                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">

                                                    <div class="form-group">

                                                        <div class="form-group">
                                                            <label for="formaPago">Forma de Pago</label>
                                                            <select id="formaPago" class="form-control select2" style="width: 100%;" required>
                                                                <option value="0">Todos</option>
                                                                <option value="1">Efectivo</option>
                                                                <option value="2">Tarjeta</option>
                                                                <option value="3">Nequi</option>
                                                                <option value="4">Daviplata</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="totalDia">Total</label>
                                                        <h2 class="">$<span id="totalDia"></span></h2>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="card-body p-0 table-responsive">
                                                <table id="tablaVentaDiaGeneral" class="display table table-hover text-nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Codigo Venta</th>
                                                            <th>Cantidad</th>
                                                            <th>Producto</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="card-footer"></div>
                                        </div>
                                    </div>

                                    <!-- Presentacion del producto -->
                                    <div class="tab-pane" id="pnlGeneral">
                                        <div class="card card-success card-mdrn">

                                            <div class="col-md-6 col-sm-12">

                                                <div class="col-md-6 col-sm-12">
                                                    <label for="totalDia">Total</label>
                                                    <h2 class="">$<span id="totalGeneral"></span></h2>
                                                
                                                </div>

                                            </div>
                                            <div class="card-body p-0 table-responsive">
                                                <table id="tabla_venta" class="display table table-hover text-nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Codigo</th>
                                                            <th>Fecha</th>
                                                            <th>Total</th>
                                                            <th>Vendedor</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="card-footer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    // session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../js/datatables.js"></script>
<script src="../js/cierre.js"></script>