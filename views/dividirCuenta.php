<?php
session_start();
if (!empty($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 || $_SESSION['rol'] == 3)) {
    include_once "layouts/header.php";
    include_once "layouts/nav.php";
?>

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
                            <h1 class="ml-2">Dividir Cuenta</h1>

                                    <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="ck-dividir-cuenta">
                                    <label class="form-check-label" for="ck-dividir-cuenta">
                                        Dividir Cuenta
                                    </label>

                                    <button id="px" >ppd</button>
                                </div>
                        </div>
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
<script>
    $('#formaPago').select2({
        dropdownParent: $('#verOrdenCaja')
    });
</script>