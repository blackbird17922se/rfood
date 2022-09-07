$(document).ready(function () {

    const VENTA_CTRLR = '../controllers/ventaController.php';
    const USUARIO_CTRL = '../controllers/usuarioController.php';
    var funcion = 0;
    var datatable = "";

    var fecha     = $("input#fecha").val();
    var cajero    = $("#list-cajero").val();
    var formaPago = $("#formaPago").val();


    listarCajeros();
    // VV(fecha,formaPago,cajero);
    listarVentaDia(fecha,formaPago,cajero);
    

    $(".select2").select2();

    /* listarVentaDia Consola */
    
    function VV(fecha,formaPago,cajero){

        funcion = 12;
        $.post(VENTA_CTRLR,{funcion,fecha,formaPago,cajero},(response)=>{
            console.log('vv rwsp: '+response);
        })

        // listarVentaDia(fecha,formaPago,cajero)
    }



    /* ******************************** DETECTA CAMBIO EN LOS INPUTS ******************************** */

    $("input#fecha").on("change", function () {
        datatable.destroy();
        fecha = $(this).val();
        listarVentaDia(fecha,formaPago,cajero)
    })


    $( "#list-cajero" ).change(function() {  
        console.log("chance cajero");
        datatable.destroy();
        cajero = $(this).val();
        listarVentaDia(fecha,formaPago,cajero)
    });


    $( "#formaPago" ).change(function() {  
        console.log("chance formaPago");
        datatable.destroy();
        formaPago = $(this).val();
        listarVentaDia(fecha,formaPago,cajero)
    });

    /* ********************************************************************************************** */



    /**
     * Cargar las ventas generales solo al seleccionar la pestaña, 
     * esto con el fin de mejorar rendimiento 
    */
    $('#btn-general').on("click", function () {
        datatable.destroy();
        listarVentaGeneral()
    })




    /* ******************************** LISTAR DATOS INPUTS ******************************** */
    /* Lista los cajeros para poder filtar por el mismo */ 
    function listarCajeros(){
        funcion = 9;
        $.post(USUARIO_CTRL,{funcion},(response)=>{
            // console.log(response);
            const CAJEROS = JSON.parse(response);

            let optDefault = `<option selected="selected" value="${0}">Todos</option>`;

            let template = '';
            CAJEROS.forEach(cajero=>{
                template+=`
                    <option value="${cajero.id}">${cajero.nombres}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#list-cajero').html(optDefault + template);
        })
    }

    /* ********************************************************************************************** */



    /* ******************************** CARGAR DATOS TABLA ******************************** */
    /* Listar las ventas del dia */
    function listarVentaDia(fecha,formaPago,cajero) {
        // console.log("vvv del diaaaaaaaaaaaaa");
        let funcion = 12;

        // console.log("formaPago " + formaPago);
        // console.log("cajero " + cajero);

        datatable = $('#tablaVentaDiaGeneral').DataTable({

            "order": [[0, "desc"]],
            ajax: "data.json",

            "ajax": {
                "url": VENTA_CTRLR,
                "method": "POST",
                "data": { 
                    funcion:   funcion, 
                    fecha:     fecha,
                    formaPago: formaPago,
                    cajero:    cajero
                }
            },

        "columns": [

            { "data": "id_venta" },
            { "data": "total" },
            { "data": "vendedor" },
            {
                "data": "imageUrl",
                "render": function (data, type, row) {
                    if (row.vendedor == 'No existen datos') {

                        return '';
                    }
                    else {
                        return `
                            <button class=" btn btn-transp-dis"><img src="../public/icons/printx32.png" alt=""></i></button>
                            <button class="ver btn btn-transp" type="button" data-toggle="modal" data-target="#vista-venta"><img src="../public/icons/dprint-prvx32.png" alt=""></button>
                            <button class=" btn btn-transp-dis"><img src="../public/icons/delete_32.png" alt=""></button>
                        `;
                    }
                }
            },
        ],

            language: espanol
        });
        
        // calcularTotal(fecha,formaPago,cajero);


        // "columns": [

        //     { "data": "id_venta" },
        //     { "data": "total" },
        //     { "data": "vendedor" },
        //     {
        //         "data": "imageUrl",
        //         "render": function (data, type, row) {
        //             if (row.vendedor == 'No existen datos') {

        //                 return '';
        //             }
        //             else {
        //                 return `
        //                     <button class=" btn btn-transp-dis"><img src="../public/icons/printx32.png" alt=""></i></button>
        //                     <button class="ver btn btn-transp" type="button" data-toggle="modal" data-target="#vista-venta"><img src="../public/icons/dprint-prvx32.png" alt=""></button>
        //                     <button class=" btn btn-transp-dis"><img src="../public/icons/delete_32.png" alt=""></button>
        //                 `;
        //             }
        //         }
        //     },
        // ],
         
    }

    /* Listar todas las ventas desde el origen de los tiempos... */
    function listarVentaGeneral() {
        funcion = 1;
        datatable = $('#tabla_venta').DataTable({

            "order": [[1, "desc"]],

            "ajax": {
                "url": VENTA_CTRLR,
                "method": "POST",
                "data": { funcion: funcion }
            },
            "columns": [

                { "data": "id_venta" },
                { "data": "fecha" },
                { "data": "total" },
                { "data": "vendedor" },
                {
                    "defaultContent": `
                    <button class=" btn btn-transp-dis"><img src="../public/icons/printx32.png" alt=""></i></button>
                    <button class="ver btn btn-transp" type="button" data-toggle="modal" data-target="#vista-venta"><img src="../public/icons/dprint-prvx32.png" alt=""></button>
                    <button class=" btn btn-transp-dis"><img src="../public/icons/delete_32.png" alt=""></button>
                    
                `},

            ],
            language: espanol
        });

        calcularTotalGeneral()
    }


    /* Controlan eventos al hacer clic en ver detalle de la orden 
        tanto en la pestaña ventas dia como generales.
    */
    $('#tablaVentaDiaGeneral tbody').on('click', '.ver', function () {
        let datos = datatable.row($(this).parents()).data();
        let id = datos.id_venta;
        listarDetalleVenta(datos, id)

    });


    $('#tabla_venta tbody').on('click', '.ver', function () {
        let datos = datatable.row($(this).parents()).data();
        let id = datos.id_venta;
        listarDetalleVenta(datos, id)
    });


    /** CONSULTAR DATOS PRINCIPALES VENTA 
     * Datos como mesero,cocinero,mesa y observaciones de la orden
    */
    function listarDetalleVenta(datos, id) {
        let formaPago = 0;
        funcion = 2
        $.post(VENTA_CTRLR, { funcion, id }, (response) => {
            // console.log(response);
            const encargados = JSON.parse(response);


            encargados.forEach(encargado => {

                $('#mesero').html(encargado.mesero);
                $('#fecha').html(encargado.fecha);
                $('#mesa').html(encargado.mesa);

                formaPago = parseInt(encargado.formpago);

                switch (formaPago) {
                    case 1:
                        formaPago = 'Efectivo'
                        break;

                    case 2:
                        formaPago = 'Tarjeta'
                        break;
                    case 3:
                        formaPago = 'Nequi'
                        break;

                    case 4:
                        formaPago = 'Daviplata'
                        break;

                    default:
                        formaPago = 'ERROR FATAL DEL SISTEMA'
                        break;
                }
                $('#medPago').html(formaPago);
            })
        });

        funcion = 'ver';
        // console.log('detalle vent'+id);

        $('#codigo_venta').html(datos.id_venta);
        // $('#fecha').html(datos.fecha);
        $('#cajero').html(datos.vendedor);
        $('#total').html(datos.total);
        $.post('../controllers/ventaProductoController.php', { funcion, id }, (response) => {
            // console.log(response);

            let registros = JSON.parse(response);
            let template = "";
            $('#registros').html(template);

            registros.forEach(registro => {
                template += `
                 <tr>
                     <td>${registro.cant}</td>
                     <td>${registro.precio}</td>
                     <td>${registro.producto}</td>
                     <td>${registro.presentacion}</td>
                     <td>${registro.tipo}</td>
                     <td>${registro.subtotal}</td>
                 </tr>
                 `;
                $('#registros').html(template);

            });
            // console.log(response);   
        })
        // console.log('dd');
        // listarDetalleVenta();


    }


    /* Total */
    // function calcularTotal(fecha,formaPago,cajero){
    //     funcion = 6;
    //     $.post(VENTA_CTRLR,{funcion,fecha,formaPago,cajero},(response) => {
    //         console.log("calculo total"+response);
    //         const totales = JSON.parse(response);
    //         totales.forEach(total => {
    //             $('#totalDia').html(total.venta_dia);
    //         })
    //     })
    // }

    function calcularTotalGeneral(){
        funcion = 7;
        $.post(VENTA_CTRLR,{funcion},(response) => {
            const totales = JSON.parse(response);
            totales.forEach(total => {
                $('#totalGeneral').html(total.venta_dia);
            })
        })
    }



    /* BORRAR VENTA */
    $('#tabla_venta tbody').on('click', '.borrar', function () {
        let datos = datatable.row($(this).parents()).data();
        let id = datos.id_venta;
        funcion = 'borrar_venta';
        /* Alerta */
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success m-1',
                cancelButton: 'btn btn-danger m-1'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: '¿Está seguro que desea eliminar la venta ' + id + '?',
            text: "Esta acción ya no se podrá deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.post('../controllers/detalleVentaController.php', { id, funcion }, (response) => {
                    // console.log(response);
                    // edit==false;
                    if (response == 'delete') {
                        swalWithBootstrapButtons.fire(
                            'Eliminado ' + ID + '!',
                            'La venta ha sido eliminada.',
                            'success'
                        )
                        // buscar_lote();
                    } else if (response == 'nodelete') {
                        swalWithBootstrapButtons.fire(
                            'Error al eliminar ' + ID,
                            'No se pudo eliminar la venta.',
                            'error'
                        )
                    }
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
    }); //Fin eliminar



    /* ***********************************PDF********************************* */
    /* PDF REPORTE VENTA DIARIA */
    $(document).on('click', '#btn_reporte_venta', (e) => {
        var usuario, fecha;
        /* datos, traera desde el controlador los datos fecha y id usuario */
        funcion = 'datos';

        $.post(VENTA_CTRLR, { funcion }, (resp) => {
            // console.log(resp);
            const VALORES = JSON.parse(resp);
            console.log("te" + resp);

            VALORES.forEach(valor => {
                usuario = valor.idUsu
                fecha = valor.fecha

                funcion = 'rep_venta';
                $.post(VENTA_CTRLR, { funcion, usuario, fecha }, (response) => {
                    console.log("tr" + response);
                    // window.open('../pdf/pdf-'+funcion+'.pdf','_blank');
                    window.open('../pdf/pdf-' + usuario + fecha + '.pdf', '_blank');

                });
            });
        });
    })


    /* CODIGO DE GENERAR PDF */
    $('#tabla_venta tbody').on('click', '.imprimir', function () {
        let datos = datatable.row($(this).parents()).data();
        let id = datos.id_venta;
        $.post('../controllers/PDFController.php', { id }, (response) => {
            console.log(response);
            window.open('../pdf/pdf-' + id + '.pdf', '_blank');
        });
    });





});




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