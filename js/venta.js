$(document).ready(function () {

    const VENTA_CTRLR = '../controllers/ventaController.php';
    var funcion       = 0;
    mostrar_consultas()
    // totalVentas()

/*     function totalVentas(){
        let funcion = 'totalVentas';
        $.post(VENTA_CTRLR,{funcion},(response)=>{
            console.log(response);
            const VENTASDIA = JSON.parse(response);
            $('#venta_dia_vendor').html(VENTASDIA.venta_dia_vendor);
            $('#venta_dia').html(VENTASDIA.venta_dia);
            $('#venta_mensual').html(VENTASDIA.venta_mensual);
            $('#venta_anual').html(VENTASDIA.venta_anual);

        });
    } */

    // Tabla Venta Dia
    function mostrar_consultas(){
        let funcion = 'mostrar_consultas';

        let datatable = $('#tb_venta_dia').DataTable( {

            // "order": [[ 1, "desc" ]],
            
            "ajax": {
                "url":"../controllers/ventaController.php",
                "method":"POST",
                "data":{funcion:funcion}
            },
            "columns": [
    
                { "data": "id_venta" },
                { "data": "cantidad" },
                { "data": "producto" },
                { "data": "subtotal" }
                
            ],
            language: espanol
        } );
    }


    /* PDF REPORTE VENTA DIARIA */
    $(document).on('click','#btn_reporte_venta',(e)=>{
        var usuario, fecha;
        /* datos, traera desde el controlador los datos fecha y id usuario */
        funcion = 'datos';

        $.post(VENTA_CTRLR,{funcion},(resp)=>{
            // console.log(resp);
            const VALORES = JSON.parse(resp);
            console.log("te"+resp);
       
            VALORES.forEach(valor=>{
                usuario = valor.idUsu
                fecha = valor.fecha

                funcion = 'rep_venta';
                $.post(VENTA_CTRLR,{funcion,usuario,fecha},(response)=>{  
                    console.log("tr"+response);      
                    // window.open('../pdf/pdf-'+funcion+'.pdf','_blank');
                    window.open('../pdf/pdf-'+usuario+fecha+'.pdf','_blank');
        
                });
            });
        });
    })

            
    funcion = 1;
    let datatable = $('#tabla_venta').DataTable( {

        "order": [[ 1, "desc" ]],
        
        "ajax": {
            "url":VENTA_CTRLR,
            "method":"POST",
            "data":{funcion:funcion}
        },
        "columns": [

            { "data": "id_venta" },
            { "data": "fecha" },
            { "data": "total" },
            { "data": "vendedor" },
            { "defaultContent": `
                <button class=" btn btn-transp-dis"><img src="../public/icons/printx32.png" alt=""></i></button>
                <button class="ver btn btn-transp" type="button" data-toggle="modal" data-target="#vista-venta"><img src="../public/icons/dprint-prvx32.png" alt=""></button>
                <button class=" btn btn-transp-dis"><img src="../public/icons/delete_32.png" alt=""></button>
                
            `}, 

        ],
        language: espanol
    } );
    /* originales sin disable:
        <button class="imprimir btn btn-transp"><img src="../public/icons/printx32.png" alt=""></i></button>
        <button class="ver btn btn-transp" type="button" data-toggle="modal" data-target="#vista-venta"><img src="../public/icons/dprint-prvx32.png" alt=""></button>
        <button class="borrar btn btn-transp"><img src="../public/icons/delete_32.png" alt=""></button>
    */


    /* DETALLE DE LA VENTA */
    /* seleccionar elid de esa fila para consultar sus valores */
    $('#tabla_venta tbody').on('click','.ver',function(){

        let datos = datatable.row($(this).parents()).data();
        let id= datos.id_venta;

        /** CONSULTAR DATOS PRINCIPALES VENTA 
         * Datos como mesero,cocinero,mesa y observaciones de la orden
        */
        funcion = 2
        $.post(VENTA_CTRLR,{funcion,id},(response)=>{
            console.log(response);
            const encargados = JSON.parse(response);
            

            encargados.forEach(encargado=>{
                // console.log(encargado.observ);
                $('#mesero').html(encargado.mesero);
                // $('#coc_lider').html(encargado.cocineroLider);
                $('#mesa').html(encargado.mesa);
                // $('#observCli').html(encargado.observ);

                let formaPago = encargado.formpago;
                /* Evaluar que tipo de pago es */

                switch (formaPago) {
                    case '1':
                        formaPago = 'Efectivo'
                    break;
                    
                    case '2':
                        formaPago = 'Tarjeta'
                    break;
                    case '3':
                        formaPago = 'Nequi'
                    break;
                    
                    case '4':
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
        $('#fecha').html(datos.fecha);
        $('#cajero').html(datos.vendedor);
        $('#total').html(datos.total);
        $.post('../controllers/ventaProductoController.php',{funcion,id},(response)=>{
            console.log(response);

            let registros = JSON.parse(response);
            let template ="";
            $('#registros').html(template);

            registros.forEach(registro => {
                template+=`
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
    });

    /* CODIGO DE GENERAR PDF */
    $('#tabla_venta tbody').on('click','.imprimir',function(){
        let datos = datatable.row($(this).parents()).data();
        let id= datos.id_venta;
        $.post('../controllers/PDFController.php',{id},(response)=>{
            console.log(response);
            window.open('../pdf/pdf-'+id+'.pdf','_blank');
        });
    });


    /* BORRAR VENTA */
    $('#tabla_venta tbody').on('click','.borrar',function(){
        let datos = datatable.row($(this).parents()).data();
        let id= datos.id_venta;
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
            title: '¿Está seguro que desea eliminar la venta '+id+'?',
            text: "Esta acción ya no se podrá deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.post('../controllers/detalleVentaController.php',{id,funcion},(response)=>{
                    // console.log(response);
                    // edit==false;
                    if(response=='delete'){
                        swalWithBootstrapButtons.fire(
                            'Eliminado '+ID+'!',
                            'La venta ha sido eliminada.',
                            'success'
                        )
                        // buscar_lote();
                    }else if(response=='nodelete'){
                        swalWithBootstrapButtons.fire(
                            'Error al eliminar '+ID,
                            'No se pudo eliminar la venta.',
                            'error'
                        )
                    }
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
    }); //Fin eliminar



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