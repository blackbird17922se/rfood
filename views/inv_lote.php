<?php
session_start();
if(!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

<!-- modal lote -->
<div class="modal fade" id="editarlote" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-success card-mdrn">
                <div class="card-header">
                    <h3 class="card-title">Editar lote</h3>
                    <button data-dismiss="modal" aria-label="close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">

                    <!-- ALERTAS -->
                    <div class="alert alert-success text-center" id="edit-lote" style="display: none;">
                        <span><i class="fas fa-check m-1"></i>Modificacion Exitosa</span>
                    </div>

                    <div class="alert alert-danger text-center" id="noedit-lote" style="display: none;">
                        <span><i class="fas fa-times m-1"></i>No se pudo editar</span>
                    </div>


                    <!-- FORMULARIO -->
                    <form id="form-editar-lote">

                        <div class="form-group">
                            <label for="id_lote">Codigo lote:</label>
                            <label id="id_lote">NombreX</label>
                        </div>


                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="text" class="form-control" id="stock" name="stock" aria-describedby="stockHelp" required>
                        </div>
                        
   <!--                      <div class="form-group">
                            <label for="vencim">Fecha de vencimiento</label>
                            <input type="date" class="form-control" id="vencim" name="vencim" aria-describedby="vencimHelp">
                        </div> -->
    
                        <input type="hidden" id="lote_id_prod">

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
                <img src="../public/icons/network-vpn.png">
                <h1 class="ml-2">Gestión de lotes</h1>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->

    <div class="card card-mdrn">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="entradas">
                    <div class="card card-success card-mdrn">                              
                        <div class="card-body p-0 table-responsive">
                            <table id="tb_lotes" class="display table table-hover text-nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Id Lote</th>
                                        <th>Nombre</th>
                                        <th>Stock</th>
                                        <th>Vencimiento</th>
                                        <th>Proveedor</th>
                                        <th>Acción</th>
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
    <!-- /.content -->

  <!-- /.content-wrapper -->

<?php
include_once "layouts/footer.php";

}else{
    // session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../js/datatables.js"></script>
<script src="../js/inv_lote.js"></script>