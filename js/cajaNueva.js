$(document).ready(function () {

    var funcion = 0;
    var totalS = 0;
    var arreglo = [];
    var itemsPedido = [];
    var remainingArr =[]

    var btnDividirCuenta = document.getElementById("btn_dividir_cuenta");

    const CAJA_CONTROLLER = '../controllers/cajaController.php';
    

    $(".select2").select2({
        placeholder: "Seleccione una opcion",
    });

    cargarMesas();
    listarDomiciliosCaja();
    calcularVuelto()


    function cargarMesas(consulta) {
        funcion = 9;

        // ajax
        $.post(CAJA_CONTROLLER, { consulta, funcion }, (response) => {
            // console.log(response);
            const mesaS = JSON.parse(response);
            let templateMesa = '';
            let templateOrden = '';

            let tempButtons = '';

            mesaS.forEach(mesa => {
                let tieneOrden = 0;
                let tempItemsOrden = '';
                // let tempProducts = '';
                // console.log(mesa.prods);
                let idOrden = 0;

                if (mesa.prods == 0) {
                    tieneOrden = 0;
                    tempItemsOrden += `
                    <h2 class="lead"><b>Disponible</b></h2>
                    `;
                } else {

                    tieneOrden = 1;


                    mesa.prods.forEach(item => {
                        // console.log('item[0]'+item[0]);
                        idOrden = item[0]

                        tempItemsOrden += `
                        <h2 class="lead"><b>Orden Numero: ${item[0]} </b></h2>
                        `;
                    });
                }

                if (tieneOrden == 1) {
                    tempButtons = `

                        <button class='selItem btn btn-sm btn-primary' type="button" idOrden="${idOrden}">
                            <i class='fas fa-plus-square mr-2'></i>Seleccionar
                        </button>

                    `;

                } else {
                    tempButtons = `
                        <button class='nuevaOrden btn btn-sm btn-primary'>
                            <i class='fas fa-plus-square mr-2'></i>Nueva Orden
                        </button>
                    `;
                }

                templateMesa += `

                <div mesaId="${mesa.id_mesa}" idOrden="${idOrden}" mesaNom="${mesa.nomMesa}" class="col-12 col-sm-6 col-md-3 align-items-stretch">
                    <div class="card bg-dark-10">
                        <div class="card-header border-bottom-0">Mesa: ${mesa.nomMesa}
                        </div>
                
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-12">
                                    ${tempItemsOrden}
                                </div>                           
                            </div>
                            ${tempButtons}
                            
                        </div>                        
                    </div>
                </div>
                `;
            });
            $('#tb_Ordenmesas').html(templateMesa);
        })
    }

    /* Lista los domicilios */
    function listarDomiciliosCaja() {
        funcion = 10;

        $.post(CAJA_CONTROLLER, { funcion }, (response) => {
            // console.log('list dom resp: ' + response);

            const PEDIDOS = JSON.parse(response);
            let template = '';

            PEDIDOS.forEach(pedido => {

                template += `
        
                    <div idDom="${pedido.idPedido}" class="col-12 col-sm-12 col-md-2 align-items-stretch">
                        <div class="card bg-dark-10">
                            <div class="card-header border-bottom-0">Orden: ${pedido.idPedido}</div>

                            <div class="card-body pt-0">

                                <button class='selDomicilio btn btn-sm btn-primary' type="button" data-toggle="modal" data-target="#verOrdenCaja">
                                    <i class='fas fa-plus-square mr-2'></i>Seleccionar
                                </button>

                            </div>                        
                        </div>
                    </div>
                `;
            });
            $('#tb_domicios').html(template);
        });
    }

    /* Cargar los datos y costos de ese pedido */
    $(document).off('click', '.selItem').on('click', '.selItem', (e) => {
        // console.log('selecc pedido');

        const ELEM   = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        var ID     = $(ELEM).attr('idOrden');

        console.log("es " + ID);

        window.location.href ='dividirCuenta.php' + "?id=" + ID;

        // const ELEM   = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        // const ID     = $(ELEM).attr('idOrden');
        // const IDMESA = $(ELEM).attr('mesaId');
        // const NOM_MESA = $(ELEM).attr('mesaNom');

        // $('#idMesaSelect').val(IDMESA);
        // $('#idOrdenSelect').val(ID);
        

        // // console.log('ORD'+ID + ' IDMESA'+IDMESA);
        // funcion = 5;
        // // idOrdenSel   = ID;
        // // idMesa       = IDMESA;

        // /* Carga los items que aun no se han pagado que contiene esa orden */
        // $.post(CAJA_CONTROLLER, { funcion, ID, IDMESA }, (response) => {
        //     // console.log(response);
        //     const PEDIDOS = JSON.parse(response);
        //     let templateS = '';
        //     let total = 0;
            

        //     PEDIDOS.forEach(pedido => {

        //         templateS += `${pedido.template}'`;
        //         console.log(pedido.idItem);
        //         arreglo.push(pedido.idItem);

        //         let datosItem = {
        //             "idItem": pedido.idItem,
        //             "subtotal": pedido.subtotal

        //         }

        //         itemsPedido.push(datosItem)
        //         // itemsPedido.push(subtotal = pedido.subtotal)

        //         // itemsPedido.idItem = pedido.idItem;
        //         // itemsPedido.subtotal = pedido.subtotal;

        //         total += pedido.subtotal

        //         totalS = total;
        //         $('#total').html(total.toFixed(0));
        //     });
        //     $('#lista-compra').html(templateS);

        //     templateTitulo = `
        //         <span id="tituloDetalle">Detalle de la Orden en mesa ${NOM_MESA}</span>
        //     `;
        //     $('#tituloDetalle').html(templateTitulo);
        //     // console.log(arreglo);
            
        // })
    });

    /* Cargar los datos y costos de ese domicilio */
    $(document).off('click', '.selDomicilio').on('click', '.selDomicilio', (e) => {
        console.log('domm');

        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('idDom');
        const IDMESA = -1;

        $('#idMesaSelect').val(IDMESA);
        $('#idOrdenSelect').val(ID);

         console.log('ORD'+ID + ' IDMESA'+IDMESA);
        funcion = 5;
        idOrdenSel = ID;
        idMesa = IDMESA;

        $.post(CAJA_CONTROLLER, { funcion, ID, IDMESA }, (response) => {
             console.log(response);
            const PEDIDOS = JSON.parse(response);
            let templateS = '';
            let total = 0;

            PEDIDOS.forEach(pedido => {

                templateS += `${pedido.template}'`;

                total += pedido.subtotal

                totalS = total;
                $('#total').html(total.toFixed(0));
            });
            $('#lista-compra').html(templateS);
        })

        templateTitulo = `
            <span id="tituloDetalle">Detalle de la Orden ${ID}</span>
        `;
        $('#tituloDetalle').html(templateTitulo);

    });

    function calcularVuelto() {
        let vuelto = 0;
        let pago = 0;

        pago = $('#pago').val();
        vuelto = pago - totalS;
        $('#vuelto').html(vuelto);
    }


    $('#pago').keyup((e) => {
        calcularVuelto()
    });

    /* Vaciar la tabla y demas campos */
    function vaciarTabla() {
        $('#lista-compra').empty();
        $('#pago').val('');
    }

    /* ´------------------ */
    $(document).on('click', '#procesar-compra', (e) => {
        // console.log('ncompra');

        // funcion = 6;

        let IDMESA      = $('#idMesaSelect').val();
        let idOrdSel  = $('#idOrdenSelect').val();
        let formaPago = $('#formaPago').val();
        let total     = $('#total').get(0).textContent;
        let itemSelec = [];

        console.log('idOrdSelSelect: ' + idOrdSel + ' -- idMesaSelect' + IDMESA);

        console.log(arreglo);
        arreglo.forEach(item =>{
            console.log(item);
            let isChecked = $('#ck-'+item)[0].checked;

            if(isChecked){
                itemSelec.push(item);
            }

            console.log(isChecked);
        })
        
        console.log(itemSelec);
        formaPago = 1;
        funcion = 11;


        if (formaPago != 0) {
            $.post(CAJA_CONTROLLER, { funcion, total, idOrdSel, formaPago, itemSelec }, (response) => {
                console.log(response);

                /* cARGAR LA TABLA CON LOS ITEMS RESTANTES */
                funcion = 5;
                // idOrdenSel   = ID;
                // idMesa       = IDMESA;
                let ID =idOrdSel;

                /* Carga los items que aun no se han pagado que contiene esa orden */
                $.post(CAJA_CONTROLLER, { funcion, ID, IDMESA }, (response) => {
                    console.log(response);
                    const PEDIDOS = JSON.parse(response);
                    let templateS = '';
                    let total = 0;

                    PEDIDOS.forEach(pedido => {

                        templateS += `${pedido.template}'`;
                        console.log(pedido.idItem);
                        arreglo.push(pedido.idItem);

                        total += pedido.subtotal

                        totalS = total;
                        $('#total').html(total.toFixed(0));
                    });
                    $('#lista-compra').html(templateS);

                    // templateTitulo = `
                    //     <span id="tituloDetalle">Detalle de la Orden en mesa ${}</span>
                    // `;
                    // $('#tituloDetalle').html(templateTitulo);
                    console.log(arreglo);
                })


            });

                ////////////////////////////////////////////
            // $.post(CAJA_CONTROLLER, { funcion, total, idOrdSel, formaPago }, (response) => {
            //     console.log(response);

            //     /* Cambiar estado de la orden a Pagado */
            //     funcion = 9;
            //     $.post(PEDIDO_CTRLR, { funcion, idOrdSel }, (response) => {
            //         console.log(response);

            //         /* Si no es un domicilio... Desbloquear mesa*/
            //         if (mesa != -1) {
            //             funcion = 11;
            //             $.post(PEDIDO_CTRLR, { funcion, mesa }, () => {
            //                 cargarMesas();
            //             });
            //         }else{
            //             listarDomiciliosCaja();
            //         }
            //     });
            // });

            // $('#verOrdenCaja').modal('hide');

            // Swal.fire({
            //     title: 'Venta Realizada',
            //     text: "¿Desea imprimir recibo?",
            //     icon: 'success',
            //     showCancelButton: true,
            //     confirmButtonText: 'Imprimir',
            //     cancelButtonText: 'Cancelar',
            //     reverseButtons: true
            // }).then((result) => {
            //     if (result.value) {

            //         let funcion = "ultimaVenta";
            //         $.post(CAJA_CONTROLLER, { funcion }, (response) => {
            //             console.log(response);


            //             $.ajax({
            //                 url: 'ticket.php',
            //                 type: 'POST',
            //                 success: function (resp) {
            //                     if (resp == 1) {
            //                         alert('imprime..');
            //                         vaciarTabla();
            //                     } else {
            //                         alert('error..');
            //                         vaciarTabla()
            //                     }
            //                 }
            //             })
            //         });


            //         console.log("selecciono imprimir");
            //     } else if (result.dismiss === Swal.DismissReason.cancel) {
            //         console.log("selecciono no imprimir");

            //         $.ajax({
            //             url: 'ticketc.php',
            //             type: 'POST',
            //             success: function (resp) {
            //                 if (resp == 1) {
            //                     alert('abre..');
            //                     vaciarTabla();
            //                 } else {
            //                     // alert('error..');
            //                     vaciarTabla()
            //                 }
            //             }
            //         })
            //         vaciarTabla()
            //     }
            // });

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Atención',
                text: 'Debes Seleccionar una Forma de pago de la lista.',
            })

        }



    });
    /* jajajajaj
    text: 'Debes Seleccionar una Forma de pago de la lista. ¡no se admite pago en especie!',
     */


    // listarProdCons();
    // function listarProdCons(){
    //     funcion = "listarPedidos";
    //     $.post(PEDIDOS_CTRL,{funcion},(response)=>{
    //         // console.log(response);

    //     })
    // }


    /* ******************************* DIVIDIR CUENTAS ************************************** */
    /* EVENTOS CHECK DE DIVIDIR CUENTA */
    $(btnDividirCuenta).on('click', function () {
        window.location.href ='dividirCuenta.php' + "?id=" + $('#idOrdenSelect').val();
    });






    $('#ck-dividir-cuenta').change(function() {
        // console.log('Checkbox checked!');
        let isChecked = $('#ck-dividir-cuenta')[0].checked
        // console.log(isChecked);

        if (isChecked) {
            $(".ck-item-pedido").prop("disabled", false);
            
        } else {
            $(".ck-item-pedido").prop("disabled", true);
            $(".ck-item-pedido").prop("checked", true);
        }

    });


    $('#tb-items-orden tbody').off('click','.tre').on('click','.tre',function(){
        // $(this).is(':checked')
        let prodId = $(this).attr('pIdItem');
        // console.log("prodId: " + prodId);

        // if ($('#tb-items-orden tbody .tre').attr('seleccionado') == "ce") {

        /* obtener el indice del objeto cuya idProd  sea ugual a proId
        con esa posicion eliminar */

            if ($(this).attr('seleccionado') == "ce") {
            alert('The name attribute exists');

            $(this).removeAttr('seleccionado');
            $(this).addClass('deselect');
            console.log(itemsPedido);

//             let indiceDeTres = itemsPedido.idItem.indexOf(prodId);
// console.log(indiceDeTres);

            itemsPedido.forEach(p => {
                console.log("I es: " + p.idItem);
                console.log("S es: " + p.subtotal);
                if (p.idItem == prodId) {
                    // p.;
                    

                    // let indiceDeTres =p.indexOf(p.idItem);
                    // console.log(indiceDeTres)
                    console.log("aqui eta!!");
                }
            })

            // let indiceDeTres = itemsPedido.indexOf(itemsPedido.idItem == prodId);

            // remainingArr = itemsPedido.filter(data => data.idItem != prodId);
            // console.log(remainingArr);
            // console.log(indiceDeTres);

        }
        else {
            alert('The name attribute does not exist');
        }

        console.log('ddssrrrrrr');
    })


    // $('#tbx tr').filter(':has(:checkbox:checked)').find('td').each(function() {
    //     console.log("dssdsd"); // this = td element });
    // })


//     $('#lista-compra td').click(function(){ 

// console.log("xe");
//     })



//     $('#tb-items-orden').click(function(){ $('td.ck-item-pedido:checked').parents('tr').each(      
//         console.log("dsghhhh")
//     );
// })

    //   $( '#lista-compra tr td .ck-item-pedido' ).on( 'click', function() {

    // $( 'tbody tr td> input[type=checkbox]' ).on( 'click', function() {
    //     console.log("eeee");
    //     if( $(this).is(':checked') ){
    //         // Hacer algo si el checkbox ha sido seleccionado
    //         alert("El checkbox con valor " + $(this).val() + " ha sido seleccionado");
    //     } else {
    //         // Hacer algo si el checkbox ha sido deseleccionado
    //         alert("El checkbox con valor " + $(this).val() + " ha sido deseleccionado");
    //     }
    // });

    $('#tb-items-orden td.ck-item-pedido input[type=checkbox]').parents('tr').change(function() {
        let xe = $(this).find('input:checkbox').checked
        console.log('sssaa'+xe);
        console.log('Checkbox checked!');
        let isChecked = $('.ck-item-pedido').checked
        console.log('chek del item es '+isChecked);

        // if (isChecked) {
        //     $(".ck-item-pedido").prop("disabled", false);
            
        // } else {
        //     $(".ck-item-pedido").prop("disabled", true);
        //     $(".ck-item-pedido").prop("checked", true);
        // }

    });


    
    
    // $("input:checkbox").change(function() {
    //     let xe = $(this).find('input:checkbox').checked
    //     console.log('sssaa'+xe);
    //     console.log('Checkbox checked!');
    //     let isChecked = $('.ck-item-pedido').checked
    //     console.log('chek del item es '+isChecked);

    //     // if (isChecked) {
    //     //     $(".ck-item-pedido").prop("disabled", false);
            
    //     // } else {
    //     //     $(".ck-item-pedido").prop("disabled", true);
    //     //     $(".ck-item-pedido").prop("checked", true);
    //     // }

    // });


    

})



/* DATATABLE A ESPAÑOL */
let espanol = {
    "aria": {
        "sortAscending": "Activar para ordenar la columna de manera ascendente",
        "sortDescending": "Activar para ordenar la columna de manera descendente"
    },
    "autoFill": {
        "cancel": "Cancelar",
        "fill": "Rellene todas las celdas con <i>%d&lt;\\\/i&gt;<\/i>",
        "fillHorizontal": "Rellenar celdas horizontalmente",
        "fillVertical": "Rellenar celdas verticalmentemente"
    },
    "buttons": {
        "collection": "Colección",
        "colvis": "Visibilidad",
        "colvisRestore": "Restaurar visibilidad",
        "copy": "Copiar",
        "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
        "copySuccess": {
            "1": "Copiada 1 fila al portapapeles",
            "_": "Copiadas %d fila al portapapeles"
        },
        "copyTitle": "Copiar al portapapeles",
        "csv": "CSV",
        "excel": "Excel",
        "pageLength": {
            "-1": "Mostrar todas las filas",
            "1": "Mostrar 1 fila",
            "_": "Mostrar %d filas"
        },
        "pdf": "PDF",
        "print": "Imprimir"
    },
    "decimal": ",",
    "emptyTable": "No se encontraron resultados",
    "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
    "infoThousands": ",",
    "lengthMenu": "Mostrar _MENU_ registros",
    "loadingRecords": "Cargando...",
    "paginate": {
        "first": "Primero",
        "last": "Último",
        "next": "Siguiente",
        "previous": "Anterior"
    },
    "processing": "Procesando...",
    "search": "Buscar:",
    "searchBuilder": {
        "add": "Añadir condición",
        "button": {
            "0": "Constructor de búsqueda",
            "_": "Constructor de búsqueda (%d)"
        },
        "clearAll": "Borrar todo",
        "condition": "Condición",
        "data": "Data",
        "deleteTitle": "Eliminar regla de filtrado",
        "leftTitle": "Criterios anulados",
        "logicAnd": "Y",
        "logicOr": "O",
        "rightTitle": "Criterios de sangría",
        "title": {
            "0": "Constructor de búsqueda",
            "_": "Constructor de búsqueda (%d)"
        },
        "value": "Valor"
    },
    "searchPanes": {
        "clearMessage": "Borrar todo",
        "collapse": {
            "0": "Paneles de búsqueda",
            "_": "Paneles de búsqueda (%d)"
        },
        "count": "{total}",
        "countFiltered": "{shown} ({total}",
        "emptyPanes": "Sin paneles de búsqueda",
        "loadMessage": "Cargando paneles de búsqueda",
        "title": "Filtros Activos - %d"
    },
    "select": {
        "1": "%d fila seleccionada",
        "_": "%d filas seleccionadas",
        "cells": {
            "1": "1 celda seleccionada",
            "_": "$d celdas seleccionadas"
        },
        "columns": {
            "1": "1 columna seleccionada",
            "_": "%d columnas seleccionadas"
        }
    },
    "thousands": ",",
    "zeroRecords": "No se encontraron resultados",
    "datetime": {
        "previous": "Anterior",
        "next": "Proximo",
        "hours": "Horas",
        "minutes": "Minutos",
        "seconds": "Segundos",
        "unknown": "-",
        "amPm": [
            "am",
            "pm"
        ]
    },
    "editor": {
        "close": "Cerrar",
        "create": {
            "button": "Nuevo",
            "title": "Crear Nuevo Registro",
            "submit": "Crear"
        },
        "edit": {
            "button": "Editar",
            "title": "Editar Registro",
            "submit": "Actualizar"
        },
        "remove": {
            "button": "Eliminar",
            "title": "Eliminar Registro",
            "submit": "Eliminar",
            "confirm": {
                "_": "¿Está seguro que desea eliminar %d filas?",
                "1": "¿Está seguro que desea eliminar 1 fila?"
            }
        },
        "error": {
            "system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\\\\\/a&gt;).&lt;\\\/a&gt;<\/a>"
        },
        "multi": {
            "title": "Múltiples Valores",
            "info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, hacer click o tap aquí, de lo contrario conservarán sus valores individuales.",
            "restore": "Deshacer Cambios",
            "noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo."
        }
    }
} 