<?php
session_start();
if (!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2)) {
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

    <!-- modal Detalle de la venta -->
    <div class="modal fade" id="vista-venta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <div class="card card-mdrn">
                    <div class="card-header">
                        <div class="card-title">Detalle de la venta</div>
                        <button data-dismiss="modal" aria-label="close" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body table-responsive">
                        <!-- ii -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="codigo_venta">Codigo Venta: </label>
                                    <span id="codigo_venta"></span>
                                </div>
                                <div class="form-group">
                                    <label for="fecha">Fecha: </label>
                                    <span id="fecha"></span>
                                </div>

                                <div class="form-group">
                                    <label for="mesa">Mesa: </label>
                                    <span id="mesa"></span>
                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label for="mesero">Mesero: </label>
                                    <span id="mesero"></span>
                                </div>

                                <div class="form-group">
                                    <label for="cajero">Cajero: </label>
                                    <span id="cajero"></span>
                                </div>

                                <div class="form-group">
                                    <label for="medPago">Medio de Pago: </label>
                                    <span id="medPago"></span>
                                </div>
                            </div>
                        </div>
                        <!-- ff -->
                        <table class="table table-hover-mdr10 text-nowrap">
                            <thead class="table-success">
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Precio Unidad</th>
                                    <th>Producto</th>
                                    <th>Presentacion</th>
                                    <th>Tipo</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="table-active" id="registros"></tbody>
                        </table>

                        <div class="float-right input-group-append">
                            <h3 class="m-3">Total: </h3>
                            <h3 class="m-3" id="total"></h3>
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
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="row">
                                <img src="../public/icons/accessories-dictionary.png">
                                <h1 class="ml-2">Gestión de Ventas</h1>
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
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a href="#pnlDia" class="nav-link active" data-toggle="tab">Ventas del Día</a></li>
                                    <li class="nav-item"><a id="btn-general" href="#pnlGeneral" class="nav-link" data-toggle="tab">General</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">

                                    <!-- panel venta dia -->
                                    <div class="tab-pane active" id="pnlDia">
                                        <div class="card card-success card-mdrn">

                                            <div class="row">
                                                <div class="col-md-4 col-sm-12">

                                                    <div class="form-group">
                                                        <label for="fecha">Fecha</label>
                                                        <input type="date" name="fecha" id="fecha" class="form-control">

                                                    </div>
                                                </div>


                                                <div class="col-md-4 col-sm-12">
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
                                                
                                                
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="list-cajero">Cajero</label>
                                                        <select id="list-cajero" class="form-control select2" style="width: 100%;"></select>
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
                                                            <th>Codigo</th>
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
<script src="../js/venta.js"></script>