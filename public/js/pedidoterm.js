$(document).ready(function(){

    var funcion = 0;
    const PEDIDO_CTRL = '../controllers/pedidoController.php';

    listarPedTerminados()

    /* Lista los pedidos Terminados */
    function listarPedTerminados(){
        funcion = 5;

        $.post(PEDIDO_CTRL,{funcion},(response)=>{
            // console.log(response);

            const PEDIDOS = JSON.parse(response);
            let templateS = '';
            
            PEDIDOS.forEach(pedido=>{

                let tempProducts = '';
                pedido.prods.forEach(item=>{

                    /* item[0] = nombre del platillo
                    /* item[1] = presentacion del platillo
                    * item[2] = cantidad del platillo
                    */
                    tempProducts+=`
                    <h2 class="lead"><b>${item[2]} </b><b>${item[0]}</b> ${item[1]}</h2>
                    `;
                });

                templateS+=`
        
                    <div idPedido="${pedido.idPedido}" class="col-12 col-sm-6 col-md-6 align-items-stretch">
                        <div class="card bg-dark-10">
                            <div class="card-header border-bottom-0">Orden Numero: ${pedido.idPedido}
                                <h2 class="lead"><b>Mesa: ${pedido.nomMesa}</b></h2>
                            </div>

                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12">
                                        <h2 class="lead"><b>${tempProducts}</b></h2>
                                    </div>                           
                                </div>

                                <button class='entregado btn btn-sm btn-primary'>
                                    <i class='fas fa-plus-square mr-2'></i>Plato entregado
                                </button>

                            </div>                        
                        </div>
                    </div>
                `;   
            });
            $('#cb-pedidos').html(templateS);
        });
    }


    /* AL HACER CLICK EN EL BOTON DE entregado */
    $(document).on('click','.entregado',(e)=>{
        funcion = 'entregado';
        console.log("entregado");
        const ELEM = $(this)[0].activeElement.parentElement.parentElement.parentElement;
        const ID = $(ELEM).attr('idPedido');
        console.log(ID);

        $.post(PEDIDO_CTRL,{funcion,ID},(response)=>{
            console.log(response);
            listarPedTerminados()
        })

    });


    // listarProdCons();
    // function listarProdCons(){
    //     funcion = "listarPedidos";
    //     $.post(PEDIDO_CTRL,{funcion},(response)=>{
    //         // console.log(response);
   
    //     })
    // }

})


/* USAR SI EL CLIENTE QUIERE UNA DATATABLE: */
// let datatable = $('#tabla_pedidos').DataTable( {

//     "scrollX": true,
//     "order": [[ 0, "asc" ]],



//     ajax: "data.json",
    
//     "ajax": {
        
//         "url":"../controllers/pedidoController.php",
//         "method":"POST",
//         "data":{funcion:funcion},
//         "dataSrc":""
//     },
//     "columns": [

//         { "data": "idPedido" },
//         { "data": "idMesa" },
//         { "data": "cant" },
//         { "data": "idProd" }
//     ],
//     language: espanol,
// } );



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