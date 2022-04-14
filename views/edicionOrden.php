<?php
session_start();
if (!empty($_SESSION['rol'])) {
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>
    <!-- Modal categoria -->
    <div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card card-success">

                    <div class="card-header">
                        <h3 class="card-title">Editar Item</h3>
                        <button data-dismiss="modal" aria-label="close" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <div class="card-body">

                        <!-- alertas -->
                        <div class="alert alert-success text-center" id="add-tipo" style="display: none;">
                            <span><i class="fas fa-check m-1"></i>Cantidad registrada correctamente</span>
                        </div>

                        <div class="alert alert-danger text-center" id="noadd-tipo" style="display: none;">
                            <span><i class="fas fa-times m-1"></i>Ya está registrada ese tipo de Cantidad</span>
                        </div>

                        <div class="alert alert-success text-center" id="edit-tipo" style="display: none;">
                            <span><i class="fas fa-check m-1"></i>Cantidad editada correctamente</span>
                        </div>

                        <!-- formulario crear/editar -->
                        <form action="" id="formEditItem">
                            <div class="form-group">
                                <label for="itemCant">Cantidad</label>
                                <input type="text" class="form-control" id="itemCant" name="itemCant" aria-describedby="itemCantHelp" required>
                                <input type="hidden" id="idItem">
                                <small id="itemCantHelp" class="form-text text-muted">Ingrese la nueva cantidad del item.</small>
                            </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn bg-gradient-primary float-right m-1">Guardar</button>
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper cnt-wrp-mdrn">
        <input type="hidden" id="idOrden" value="<?php echo $_GET['idOrden'] ?>">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="row">
                            <img src="../public/icons/applications-other.png">
                            <h1 class="ml-2">Editar Orden</h1>
                        </div>
                        <button class="btn btn-danger salir">Volver</button>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

            
                <!-- Item actuales -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-mdrn">
                            <h2>Items actuales</h2>

                            <div class="card-body">
                                <div class="tab-content">

                                    <!-- panel categoria -->
                                    <div class="tab-pane active" id="categ">
                                        <div class="card card-success card-mdrn">

                                            <div class="card-body p-0 table-responsive">
                                                <table class="table table-hover text-nowrap">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>Cant.</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-active" id="tb-itemOrden"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
<script src="../js/datatables.js"></script>
<script src="../js/edicionOrden.js"></script>