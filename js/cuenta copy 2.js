$(document).ready(function () {

    const FACTORDEN_CTRL = '../controllers/factOrdenController.php';
    const ID_ORDEN        = $('#itemId').val();
    const PEDIDO_CTRLR = '../controllers/pedidoController.php';
    var ID_MESA = 0;
    var funcion = 0;
    var totalVenta = 0;

    cargarMesaPedido();
    cargarDetallesOrden();
    $(".select2").select2({
        placeholder: "Forma de Pago",
    });
    
    /* Carga la mesa de ese pedido */
    function cargarMesaPedido(){
        funcion = 3;
        $.post(FACTORDEN_CTRL, { funcion, ID_ORDEN}, (response) => {
            // console.log(response);
            const MESA = JSON.parse(response);
            MESA.forEach(dato => {
                ID_MESA=dato.idMesa;

                templateTitulo = `
                    <span id="tituloDetalle">Detalle de la Orden ${ID_ORDEN} en la Mesa ${dato.nomMesa}</span>`;

                    $('#tituloDetalle').html(templateTitulo);
                // $('#totalDia').html(dato.venta_dia);
            })

            // ID_MESA = response;
        })

                // templateTitulo = `
        //     <span id="tituloDetalle">Detalle de la Orden ${ID_ORDEN}</span>
        // `;
    }


    function cargarDetallesOrden(){
        funcion = 1;

        $.post(FACTORDEN_CTRL, { funcion, ID_ORDEN, ID_MESA }, (response) => {
            // console.log(response);
            //  console.log("cargarDetallesOrden responde: " + response);
             if (response == 0) {
                console.log("no hay mas item");
                
                location.href ='caja.php';
                return 1;
                
             } else {
                // console.log("carga items");
                // console.log("la mesa es vv " + ID_MESA);
                const PEDIDOS = JSON.parse(response);
                let templateS = '';
                let total = 0;

                PEDIDOS.forEach(pedido => {

                    templateS += `${pedido.template}'`;

                    total += pedido.subtotal

                    totalVenta = total;
                    // totalS = total;
                    $('#total').html(total.toFixed(0));
                });
                $('#tbody_items_orden').html(templateS); 
            }
        })
        calcularTotal()
    }


    /* Evento que controla el input de cantidad y sus flechas */
    $(document).on('click', '.btn-plus, .btn-minus', function(e) {
        const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');

        const input = $(e.target).closest('.input-group').find('input');
        const inputPrecio = $(e.target).closest('.input-group').find('.inputprecio');
        const inputIdItem = $(e.target).closest('.input-group').find('.inputiditem');

        if (input.is('input')) {
            input[0][isNegative ? 'stepDown' : 'stepUp']()
        }
        
        let cantidad = $(input).val()
        let precio   = $(inputPrecio).val()
        let idItem   = $(inputIdItem).val()

        calcularSubtotal(idItem,cantidad, precio);
        calcularTotal()
    });


    function calcularSubtotal(idItem, cantidad, precio){
        let subtotal = cantidad * precio;
        $('#td-subtotal-' + idItem).html(subtotal);
        $('#inputsubtotal-' + idItem).val(subtotal);
    }


    /* AL MARCAR DIVIDIR CUENTA */
    $('#ck-dividir-cuenta').change(function() {
        // console.log('Checkbox checked!');
        // let isChecked = $('#ck-dividir-cuenta')[0].checked
        // console.log(isChecked);

        if ($('#ck-dividir-cuenta')[0].checked) {
            $(".ck-item-pedido").prop("disabled", false);
            $(".btn-outline-secondary").prop("disabled", false);
            // $(".form-control.quantity").prop("readonly", false);
            // $(".btn-outline-secondary").prop("disabled", false);
            
        } else {
            $(".ck-item-pedido").prop("disabled", true);
            $(".ck-item-pedido").prop("checked", true);
            cargarDetallesOrden();
        }
    });



    /********************************** REALIZAR VENTA ************************************/
    $('#btn-realiza-venta').on('click', function(e) {

        let itemSelec    = []
        let datosItem    = [];
        let cantidad     = 0;
        let idItem       = 0;
        let precio       = 0;
        let cantOriginal = 0;
        
        const tableRows = document.querySelectorAll('#tb_items_orden tr.rowt');
        
        // Recorremos las filas que tengan el class="row"
        // así obviamos la cabecera
        for(let i=0; i<tableRows.length; i++) {
            const row = tableRows[i];
            const tdPrecio = row.querySelector('.inputprecio');
            const tdCantidad = row.querySelector('.quantity');
            const ck = row.querySelector('.ck-item-pedido');
            const inputiditem = row.querySelector('.inputiditem');
            const inputcantoriginal = row.querySelector('.inputcantoriginal');

            cantidad = $(tdCantidad).val();
            idItem = $(inputiditem).val();
            precio = $(tdPrecio).val();
            cantOriginal = $(inputcantoriginal).val();

            /* Si esta check debe insertar ese item al arreglo */
            if ($(ck)[0].checked) {
                console.log("row Checkeada");

                datosItem = {
                    "idItem": idItem,
                    "cantidad": cantidad,
                    "precio": precio,
                    "cantOriginal": cantOriginal,
                }

                /* Agrega ese row al array de items */
                itemSelec.push(datosItem)

            }

        }   /* Fin recorrido tabla */

        let funcion   = 2;
        let json      = JSON.stringify(itemSelec)
        let formaPago = $('#formaPago').val();


        if (formaPago != 0) {

            /* Facturar la orden */
            $.post(FACTORDEN_CTRL, { funcion, totalVenta, ID_ORDEN, formaPago, json }, (response) => {

                /* Si responde 0 significa que ya no hay mas items
                 por cancelar. */
                if(cargarDetallesOrden() == 0){
                    // console.log("No hay mas items 2");

                    /* Cambiar estado de la orden a Pagado */
                    funcion = 9;
                    $.post(PEDIDO_CTRLR, { funcion, ID_ORDEN }, (response) => {
                        console.log(response);

                        /* Si no es un domicilio... Desbloquear mesa*/
                        if (mesa != -1) {
                            funcion = 11;
                            $.post(PEDIDO_CTRLR, { funcion, mesa }, () => {
                                // cargarMesas();
                            });
                        }else{
                            // listarDomiciliosCaja();
                        }
                    });
                    
               


                }else{
                    console.log("hay mas items");
                };
                // const tableRows = document.querySelectorAll('#tb_items_orden tr.rowt');
                // let ltb = tableRows.length - 1;
                // console.log(ltb);

            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Atención',
                text: 'Debes Seleccionar una Forma de pago de la lista.',
            })
        }

        $("#ck-dividir-cuenta").prop("checked", false);
    })
    /***********************************************************************************/ 



    /************************************* CALCULAR TOTAL ******************************/ 
    function calcularTotal(){
        totalVenta= 0;

        const tableRows = document.querySelectorAll('#tb_items_orden tr.rowt');
        
        for(let i=0; i<tableRows.length; i++) {
            const row = tableRows[i];
            const tdPrecio = row.querySelector('.inputsubtotal');

            const ck = row.querySelector('.ck-item-pedido');
            precio = parseInt($(tdPrecio).val());
            if ($(ck)[0].checked) {
                totalVenta += precio;    
            }
            
        }   /* Fin recorrido tabla */
        // console.log("total venta: "+totalVenta);
        $('#total').html(totalVenta);
    }
    /***********************************************************************************/ 


    /************************************* CALCULAR VUELTO ******************************/ 
    $('#pago').keyup((e) => {
        console.log("pagoo");
        calcularVuelto()
    });


    function calcularVuelto() {
        let vuelto = 0;
        let pago = 0;

        pago = $('#pago').val();
        vuelto = pago - totalVenta;
        if (vuelto < 0) {
            vuelto = 0
        }
        $('#vuelto').html(vuelto);
    }
    /***********************************************************************************/ 



    /* Hacer calculo cuando los check de los items cambia */
    $('#tb_items_orden tbody').on('click', '.ck-item-pedido', function(e) {
        // console.log("ck-item-pedido");
        calcularTotal()
    });








});