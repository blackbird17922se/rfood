$(document).ready(function () {

    const FACTORDEN_CTRL = '../controllers/factOrdenController.php';
    const ID_ORDEN        = $('#itemId').val();
    var arreglo = [];
    var itemsPedidoBorr = [];

    cargarDetallesOrden();

    function cargarDetallesOrden(){
        // console.log('domm');

        // const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        // const ID = $(ELEM).attr('idDom');
        const ID_MESA = -1;

        // $('#idMesaSelect').val(IDMESA);
        // $('#idOrdenSelect').val(ID);

        //  console.log('ORD'+ID + ' IDMESA'+IDMESA);
        funcion = 1;
        // idOrdenSel = ID;
        // idMesa = IDMESA;

        $.post(FACTORDEN_CTRL, { funcion, ID_ORDEN, ID_MESA }, (response) => {
            console.log(response);
             console.log("cargarDetallesOrden responde: " + response);
             if (response == 0) {
                console.log("no hay mas item");
                return 1;
                
             } else {
                console.log("carga items");
                const PEDIDOS = JSON.parse(response);
                let templateS = '';
                let total = 0;

                PEDIDOS.forEach(pedido => {

                    // console.log("cant: " + pedido.cantidad);

                    arreglo.push(pedido.idItem);
                    
                    let datosItem = {
                        "idItem": pedido.idItem,
                        "subtotal": pedido.subtotal

                    }

                    itemsPedidoBorr.push(datosItem)

                    templateS += `${pedido.template}'`;

                    total += pedido.subtotal

                    totalS = total;
                    $('#total').html(total.toFixed(0));
                });
                $('#tbody_items_orden').html(templateS);
                
             }
            
        })

        // console.log(arreglo);
        // console.log(itemsPedidoBorr);

        // templateTitulo = `
        //     <span id="tituloDetalle">Detalle de la Orden ${ID_ORDEN}</span>
        // `;
        // $('#tituloDetalle').html(templateTitulo);
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
    });


    function calcularSubtotal(idItem, cantidad, precio){
        let subtotal = cantidad * precio;
        $('#td-subtotal-' + idItem).html(subtotal);
        // console.log(subtotal);
    }

//     $('#tb_items_orden tbody').on('change', '.quantity',function(e){
//         console.log("cambioo");

//     })

//     $('#tb_items_orden tbody').off('click', '.btn-plus, .btn-minus').on('click', '.btn-plus, .btn-minus', function(e) {
//         const idItem = $(this).attr('iditem');

//         console.log(idItem);
//         let cant = $("#id_cant_item_"+idItem).val();

// console.log(cant + " cambio2" + idItem );
// nuevalect(idItem);
//     })

    // function nuevalect(idItem){
    //     let cant = $("#id_cant_item_"+idItem).val();

    //     console.log("nueva cant" + cant);

    // }

   
    // $('#ck-dividir-cuenta').change(function() {

    // })

    // $('.btn-plus').on('click', function(e) {
    //     console.log("cambio3");
    // })




    /* AL MARCAR DIVIDIR CUENTA */
    $('#ck-dividir-cuenta').change(function() {
        // console.log('Checkbox checked!');
        // let isChecked = $('#ck-dividir-cuenta')[0].checked
        // console.log(isChecked);

        if ($('#ck-dividir-cuenta')[0].checked) {
            $(".ck-item-pedido").prop("disabled", false);
            
        } else {
            $(".ck-item-pedido").prop("disabled", true);
            $(".ck-item-pedido").prop("checked", true);
        }

        

    });



    // $('#tb_items_orden tbody').off('click','.tre').on('click','.tre',function(){
    //     let prodId = $(this).attr('pIdItem');

    //     /* obtener el indice del objeto cuya idProd  sea ugual a proId
    //     con esa posicion eliminar */

    //         if ($(this).attr('seleccionado') == "ce") {
    //         alert('The name attribute exists');

    //         $(this).removeAttr('seleccionado');
    //         $(this).addClass('deselect');
    //         console.log(itemsPedido);


    //         itemsPedido.forEach(p => {
    //             console.log("I es: " + p.idItem);
    //             console.log("S es: " + p.subtotal);
    //             if (p.idItem == prodId) {

    //                 console.log("aqui eta!!");
    //             }
    //         })
    //     }
    //     else {
    //         alert('The name attribute does not exist');
    //     }
    //     console.log('ddssrrrrrr');
    // })


    $('#px').on('click', function(e) {
        // console.log("px");
        recorreTabla()

    })


    $('#tb_items_orden tbody').on('click', '.ck-item-pedido', function(e) {

        console.log("ck-item-pedido");

        
        // if ($(this)[0].checked) {
        //     // $(".ck-item-pedido").prop("disabled", false);
        //     console.log("ch");
            
        // } else {
        //     // $(".ck-item-pedido").prop("disabled", true);
        //     // $(".ck-item-pedido").prop("checked", true);
        //     console.log("no ch");
        // }

        // const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');

        // const input = $(e.target).closest('.input-group').find('input');
        // const inputPrecio = $(e.target).closest('.input-group').find('.inputprecio');
        // const inputIdItem = $(e.target).closest('.input-group').find('.inputiditem');

        // if (input.is('input')) {
        //     input[0][isNegative ? 'stepDown' : 'stepUp']()
        // }
        
        // let cantidad = $(input).val()
        // let precio   = $(inputPrecio).val()
        // let idItem   = $(inputIdItem).val()

        // calcularSubtotal(idItem,cantidad, precio);
    });







    function recorreTabla(){


        let itemSelec = []

        let cantidad = 0;
        let idItem = 0;
        let precio = 0;
        let cantOriginal = 0;
        let datosItem = [];
        
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

                // console.log("Datitems:");
                // console.log(datosItem);

                /* Agrega ese row al array de items */
                itemSelec.push(datosItem)

            } else {
                console.log("row no checkeada");
            }

        }   /* Fin recorrido tabla */


        let funcion = 2;

        // console.log("Arr antes de stringif: ");
        // console.log(itemSelec);
        let json = JSON.stringify(itemSelec)
        /* Variables de prueba */
        let total=1;
        let formaPago = 1;

        // console.log("Arr que se le pasa: ");
        // console.log(json);

            
        if (formaPago != 0) {

            /* Facturar la orden */
            $.post(FACTORDEN_CTRL, { funcion, total, ID_ORDEN, formaPago, json }, (response) => {
                console.log(response);
                console.log(ID_ORDEN);

                /* Si responde 0 significa que ya no hay mas items
                 por cancelar. */
                if(cargarDetallesOrden() == 0){
                    console.log("No hay mas items");
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

    }   /* End Function */




});