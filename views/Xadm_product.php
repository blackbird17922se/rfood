<?php
session_start();
if(!empty($_SESSION['rol']==1)){
    include_once "layouts/header.php";
?>

    <title>Producto</title>
    <?php
    include_once "layouts/nav.php";
    ?>

<!-- modal lote -->
<div class="modal fade" id="crearproducto" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">XpProd</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">

                    <!-- ALERTAS -->
                    <div class="alert alert-success text-center" id="add-usuario" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Usuario Registrado</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noadd-usuario" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>Usuario Existente</span>
                    </div>

                    <div class="alert alert-success text-center" id="edit-usuario" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Modificacion Exitosa</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noedit-usuario" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>No se pudo editar</span>
                    </div>


                    <!-- FORMULARIO -->
                    <form id="form-crear-producto">

                                  <!-- XP LISTA -->
                        <div class="form-group">
                            <label for="laboratorio">XeP producto</label>
                            <select name="laboratorio" id="laboratorio" class="form-control select2"></select>
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
            <h1>Gestión de xp <button id="button-crear" type="button" data-toggle="modal" data-target="#crearproducto" class="btn bg-gradient-primary ml-2">Nuevo xp</button></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Gestión de Usuarios</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section>
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Buscar usuario</h3>
                    <div class="input-group">
                        <input id="buscar-producto" type="text" class="form-control float-left" placeholder="Ingrese el nombre del usuario">
                        <div class="input-group-append">
                            <buttom class="btn btn-default"><i class="fas fa-search"></i></buttom>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div id="productos" class="row d-flex align-items-stretch">

                    </div> 
                </div>

                <div class="card-footer">
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
include_once "layouts/footer.php";
}
else{
    header("Location: ../index.php");
}
?>
<script src="../js/producto.js"></script>
