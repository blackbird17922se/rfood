<?php
session_start();
if (!empty($_SESSION['rol'])) {
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper cnt-wrp-mdrn">
        <input type="hidden" id="mesaId" value="<?php echo $_GET['mesaId'] ?>">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="row">
                            <img src="../public/icons/applications-other.png">
                            <h1 class="ml-2">Nueva Orden</h1>
                        </div>
                        <button class="btn btn-danger salir">Volver</button>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                
                <!-- impl agregar item -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-mdrn">
                            <h2>Agregar Items</h2>
                            <div class="card-header">
                                <div class="form-group">
                                    <label for="prod_tipo">Categoría del producto</label>
                                    <select id="prod_tipo" class="form-control select2" style="width: 100%;" required></select>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- ENTRADAS -->
                                    <div class="tab-pane active" id="entradas">
                                        <div class="card card-success card-mdrn">
                                            <div class="card-body p-0 table-responsive">
                                                <table id="tabla_products" class="display table table-hover text-nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Acción</th>
                                                            <th>Nombre</th>
                                                            <th>Presentación</th>
                                                            <th>Precio</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
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
<script src="../public/js/datatables.js"></script>
<script src="../public/js/orden.js"></script>