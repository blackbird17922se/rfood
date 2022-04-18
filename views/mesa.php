<?php
session_start();
if (!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2)) {
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

    <!-- Modal Mesas -->
    <div class="modal fade" id="crearmesa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card card-success card-mdrn">
                    <div class="card-header">
                        <h3 class="card-title">Mesa</h3>
                        <button data-dismiss="modal" aria-label="close" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- alertas -->
                        <div class="alert alert-success text-center" id="add-mesa" style="display: none;">
                            <span><i class="fas fa-check m-1"></i>Mesa registrada correctamente</span>
                        </div>

                        <div class="alert alert-danger text-center" id="noadd-mesa" style="display: none;">
                            <span><i class="fas fa-times m-1"></i>Ya está registrada esa Mesa</span>
                        </div>

                        <div class="alert alert-success text-center" id="edit-mesa" style="display: none;">
                            <span><i class="fas fa-check m-1"></i>Mesa editada correctamente</span>
                        </div>
                        <form action="" id="form-crear-mesa">
                            <div class="form-group">
                                <label for="nom_mesa">Identificación de la Mesa</label>
                                <input type="text" class="form-control" id="nom_mesa" name="nom_mesa" aria-describedby="nom_mesaHelp" required>
                                <input type="hidden" id="id_edit_mesa">
                                <small id="nom_mesaHelp" class="form-text text-muted">ingrese un numero o codigo de la Mesa.</small>
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
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="row">
                            <img src="../public/icons/edit-select-all.png">
                            <h1 class="ml-2">Mesas</h1>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-success card-mdrn">
                            <div class="card-header">
                                <div class="card-title">Buscar Mesa
                                    <button type="button" data-toggle="modal" data-target="#crearmesa" class="btn bg-gradient-primary btn-sm m-2">Crear Mesa</button>
                                </div>
                                <div class="input-group">
                                    <input id="busq-mesa" type="text" class="form-control float-left" placeholder="Ingrese nombre">
                                    <div class="input-group-append">
                                        <buttom class="btn btn-default"><i class="fas fa-search"></i></buttom>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover text-nowrap">
                                    <thead class="table-success">
                                        <tr>
                                            <th>Numero de Mesa</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-active" id="tbd-mesas">
                                    </tbody>
                                </table>
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
<script src="../js/mesa.js"></script>