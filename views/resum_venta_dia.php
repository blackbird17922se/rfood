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
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Resumen de ventas del día</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section>
            <div class="container-fluid">
                <div class="card card-success">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="ul-header-ventas">
                                    <li class="tool-item-li"> <i class="fas fa-caret-right"></i> <b>Vendedor: </b><span class="val-venta"><?php echo $_SESSION['nom'] ?></span></li>
                                    <li class="tool-item-li"> <i class="fas fa-caret-right"></i><b>Ventas del Día: </b>$<span class="val-venta" id="venta_dia_vendor">0</span></li>
                                    <!-- <li class="tool-item-li"> <i class="fas fa-caret-right"></i><b>Numero de ventas del Día: </b><span class="val-venta" id="info-cant-number">0</span></li> -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="tb_venta_dia" class="display table table-hover text-nowrap" style="width:100%">
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
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->




<?php
    include_once "layouts/footer.php";
} else {
    session_destroy();
    header("Location: ../index.php");
}
?>

<script src="../js/datatables.js"></script>
<script src="../js/venta_dia.js"></script>