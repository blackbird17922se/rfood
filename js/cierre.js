$(document).ready(function () {

    const VENTA_CTRLR = '../controllers/ventaController.php';
    var funcion = 0;
    var datatable = "";
    var formaPago = $("#formaPago").val();
    // var formaPago = 1

    console.log(formaPago);

    $( "#formaPago" ).change(function() {  
        console.log('cambio pago');
        datatable.destroy();
        formaPago = $(this).val();
        listarVentaPagoGeneral()
        calcularTotal(formaPago)
    });


    // VV();
    listarVentaPagoGeneral();


    // $( "#formaPago" ).change(function() {  
    //     console.log('cambio pago');
    //     datatable.destroy();
    //     formaPago = $(this).val();
    //     listarVentaPagoGeneral()
    // });

    /* Cuando el usuario seleciona una formaPago del input */
    // $("#formaPago").on("change", function () {
    //     console.log('cambio pago');
    //     datatable.destroy();
    //     formaPago = $(this).val();
    //     listarVentaPagoGeneral()
    // })


    /* Listar las ventas del dia de todos los cajeros */
    function listarVentaPagoGeneral() {
        let funcion = 13;

        console.log("listarVentaPagoGeneral");

        datatable = $('#tablaVentaDiaGeneral').DataTable({

            "order": [[0, "desc"]],
            ajax: "data.json",

            "ajax": {
                "url": VENTA_CTRLR,
                "method": "POST",
                "data": { funcion: funcion, formaPago: formaPago }
            },

            "columns": [
    
                { "data": "id_venta" },
                { "data": "cantidad" },
                { "data": "producto" },
                { "data": "subtotal" }
                
            ],
            language: espanol
        });
        
        calcularTotal(formaPago)
         
    }


    /* Total */
    function calcularTotal(formaPago){
        console.log("Calcular Total forma pago:" + formaPago);
        console.log('cakculo fevcha');
        funcion = 14;
        $.post(VENTA_CTRLR,{funcion,formaPago},(response) => {
            console.log(response);
            const totales = JSON.parse(response);
            totales.forEach(total => {
                $('#totalDia').html(total.venta_dia);
            })
        })
    }

    // function calcularTotalGeneral(){
    //     funcion = 7;
    //     $.post(VENTA_CTRLR,{funcion},(response) => {
    //         const totales = JSON.parse(response);
    //         totales.forEach(total => {
    //             $('#totalGeneral').html(total.venta_dia);
    //         })
    //     })
    // }



    /* listarVentaPagoGeneral Consola */

    function VV(){

        funcion = 13;
        $.post(VENTA_CTRLR,{funcion,formaPago},(response)=>{
            console.log('vv rwsp: '+response);
            console.log('heloooooo');

        })

        listarVentaPagoGeneral()
    }


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