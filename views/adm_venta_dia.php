<?php
session_start();
if(!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 3)){
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cnt-wrp-mdrn">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Ventas del Dia
              <!-- <button id="btn-crear" type="button" data-toggle="modal" data-target="#crearlote" class="btn bg-gradient-primary ml-2" title="editar">Crear Producto</button> -->
            </h1>
            <h4>Vendedor: <?php echo $_SESSION['nom']?></h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Ventas del Dia</li>
            </ol>
          </div>
        </div>

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="venta_dia_vendor">0</h3>

                <p>Venta del Día por Vendedor</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="venta_dia">0</h3>

                <p>Venta Total del Día</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="venta_mensual">0</h3>

                <p>Venta Mensual</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="venta_anual">0</h3>

                <p>Venta Anual</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>


<!-- TABLA VENTA DIARIA -->
    <section>
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">VENTA DIARIA</h3>
                </div>

                <div class="card-body">

      

                    <button id="btn_reporte_venta" type="button" class="btn bg-gradient-success ml-2 mb-3">Reporte Venta</button>

                    <table id="tb_venta_dia" class="display table table-hover text-nowrap mt-3" style="width:100%">
                        <thead>
                            <tr>
                                <th>Codigo Venta</th>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    
                </div>

                <div class="card-footer">
                </div>
            </div>
        </div>
    </section>

  </div>
  <!-- /.content-wrapper -->

  


<?php
include_once "layouts/footer.php";

}else{
    // session_destroy();
    header("Location: ../index.php");
}
?>

<script src="../js/datatables.js"></script>
<script src="../js/venta.js"></script>