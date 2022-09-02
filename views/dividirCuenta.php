<?php
session_start();
if (!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 3)) {
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

<link rel="stylesheet" href="../public/css/caja.css">

<input type="hidden" id="itemId" value="<?php echo $_GET['id']?>">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper cnt-wrp-mdrn">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="row">
                            <img src="../public/icons/calculator_32.png" title="caja">
                            <h1 class="ml-2" id="tituloDetalle"></h1>


                                <!-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="ck-dividir-cuenta">
                                    <label class="form-check-label" for="ck-dividir-cuenta">
                                        Dividir Cuenta
                                    </label>

                                    <button id="px" >ppd</button>
                                </div> -->
                        </div>

                        <!-- ----------- -->
                        <div class="row" id="cards-cuentas">

                            <div class="col-md-4">
                                <div class="info-box mb-3">
                                    <span class="info-box-text text-left">TOTAL</span>
                                    <span class="info-box-number" id="total"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-box mb-3 bg-success">
                                    <span class="info-box-text text-left sp-ingreso">INGRESO</span>
                                    <input type="number" id="pago" min="1" placeholder="Ingresa Dinero" class="form-control">
       
                                </div>
        
                            </div>

                            <div class="col-md-4">
                                <div class="info-box mb-3 bg-info">
                                    <span class="info-box-text text-left ">VUELTO</span>
                                    <span class="info-box-number" id="vuelto"></span>
       
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-4 col-sm-12">

                                <div class="form-group">
                                    <!-- <label for="formaPago">Forma de Pago</label> -->
                                    <select id="formaPago" class="form-control select2" style="width: 100%;" required>
                                        <option value=""></option>
                                        <option value="1">Efectivo</option>
                                        <option value="2">Tarjeta</option>
                                        <option value="3">Nequi</option>
                                        <option value="4">Daviplata</option>
                                    </select>
                                </div>

                            </div>


                            <div class="col-md-4 col-sm-12" id="ck-dividir-cnt-cont">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="ck-dividir-cuenta">
                                        <label class="form-check-label" for="ck-dividir-cuenta">
                                            Dividir Cuenta
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 col-sm-12">
                                <a href="#" class="btn btn-success" id="px">Realizar venta</a>
                            </div>

                        </div>



                        <!-- ----------- -->


                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- ********************** TABLA Pedidos ************************* -->
        <!-- Main content -->
        <section>
            <div class="container-fluid">
                <div class="card card-success card-mdrn">
                    <div class="card-body">
                        <table id="tb_items_orden" class="display table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Presentación</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Precio Unid.</th>
                                    <th scope="col">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_items_orden"></tbody>
                        </table>              
                    </div>

                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </section>

    </div>

        


<?php
    include_once "layouts/footer.php";
} else {
    session_destroy();
    header("Location: ../index.php");
}
?>
<script src="../js/datatables.js"></script>
<script src="../js/cuenta.js"></script>
<!-- <script>
    $('#formaPago').select2({
        dropdownParent: $('#verOrdenCaja')
    });
</script> -->