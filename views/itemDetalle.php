<?php
session_start();
if(!empty($_SESSION['rol']==1 || $_SESSION['rol']==2)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cnt-wrp-mdrn">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">

          
          <div class="col-sm-6 mt-2">
            <h1 id="tit-item"></h1>
            <input type="hidden" id="itemId" value="<?php echo $_GET['id']?>">
            <p>Codigo: <span id="codbar"></span></p>
            <p>Precio: <span id="precio"></span></p>
            <p>¿Tiene IVA?: <span id="iva"></span></p>
          </div>
          <div class="col-sm-6">
            <button class="btn btn-danger salir flt-rgt">Salir</button>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-mdrn">
                    

                    <div class="card-body">
                        <!-- .tab-content>(.tab-pane>.card.card-success>(.card-header>.card-title)+(.card-body)+(.card-footer)) -->
                        <div class="tab-content">

                            <!-- panel tipooria -->
                            <div class="tab-pane active" id="tipo">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <div class="card-title">Ingredientes del Ítem
                                            <button type="button" id="nIngredItem" class="btn bg-gradient-primary btn-sm m-2">Gestionar Ingredientes</button></div>
    
                                    </div>
                                    <div class="card-body p-0 table-responsive">
                                        <table class="table table-hover-mdr10 text-nowrap">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Nombre Ingrediente</th>
                                                    <th>Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-active" id="tb-ingreds-item">
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
<script src="../js/itemDetalle.js"></script>
<!-- <script src="../js/inv_medida.js"></script> -->
