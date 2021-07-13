<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

<!-- Modal Tipo producto (lacteos,carnes...) -->
<div class="modal fade" id="crear-tipo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">

                <div class="card-header">
                    <h3 class="card-title">Tipo del producto</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                
                <div class="card-body">

                    <!-- alertas -->
                    <div class="alert alert-success text-center" id="add-tipo" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Tipo registrado correctamente</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noadd-tipo" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Ya está registrada ese tipo de Tipo</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit-tipo" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Tipo editada correctamente</span>
                    </div>

                    <!-- formulario crear/editar -->
                    <form action="" id="form-crear-tipo">
                        <div class="form-group">
                            <label for="nom_tipo">Tipo</label>
                            <input type="text" class="form-control" id="nom_tipo" name="nom_tipo" aria-describedby="nom_tipoHelp" required>
                            <input type="hidden" id="id_edit_tipo">
                            <small id="nom_tipoHelp" class="form-text text-muted">Ingrese el nombre de la Tipo del producto o platillo.</small>
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

<!-- Modal medida -->
<div class="modal fade" id="crearmedida" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Medida</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <!-- alertas -->
                    <div class="alert alert-success text-center" id="add-medida" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Medida registrada correctamente</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noadd-medida" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Ya está registrada ese tipo de Medida</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit-medida" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Medida editada correctamente</span>
                    </div>
                    <form action="" id="form-crear-medida">
                        <div class="form-group">
                            <label for="nom_medida">Nombre de la Medida</label>
                            <input type="text" class="form-control" id="nom_medida" name="nom_medida" aria-describedby="nom_medidaHelp" required>
                            <input type="hidden" id="id_edit_medida">
                            <small id="nom_medidaHelp" class="form-text text-muted">ingrese nombre Medida.</small>
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
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestion Atributos INV</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Gestion Atributos inv</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a href="#tipo" class="nav-link active" data-toggle="tab">Tipos de productos</a></li>
                            <li class="nav-item"><a href="#medida" class="nav-link" data-toggle="tab">Medida del Producto</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- .tab-content>(.tab-pane>.card.card-success>(.card-header>.card-title)+(.card-body)+(.card-footer)) -->
                        <div class="tab-content">

                            <!-- panel tipooria -->
                            <div class="tab-pane active" id="tipo">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <div class="card-title">Buscar Tipo
                                            <button type="button" data-toggle="modal" data-target="#crear-tipo" class="btn bg-gradient-primary btn-sm m-2">Crear Tipo</button></div>
                                        <div class="input-group">
                                            <input id="busq-tipo" type="text" class="form-control float-left" placeholder="Ingrese nombre">
                                            <div class="input-group-append">
                                                <buttom class="btn btn-default"><i class="fas fa-search"></i></buttom>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0 table-responsive">
                                        <table class="table table-hover text-nowrap">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Nombre Tipo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-active" id="tbd-tipos">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>
                            
                            <!-- medidaacion del producto -->
                            <div class="tab-pane" id="medida">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <div class="card-title">Buscar Medida<button type="button" data-toggle="modal" data-target="#crearmedida" class="btn bg-gradient-primary btn-sm m-2">Crear medida</button></div>
                                        <div class="input-group">
                                            <input id="busq-medida" type="text" class="form-control float-left" placeholder="Ingrese nombre">
                                            <div class="input-group-append">
                                                <buttom class="btn btn-default"><i class="fas fa-search"></i></buttom>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0 table-responsive">
                                        <table class="table table-hover text-nowrap">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Medida</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-active" id="tbd-medidas">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
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

}else{
    // session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../public/js/inv_tipo.js"></script>
<script src="../public/js/inv_medida.js"></script>
