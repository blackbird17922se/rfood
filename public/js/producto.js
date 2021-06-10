$(document).ready(function(){
    var funcion;
    var edit = false;   // bandera
    var nomProd =""; // Contiene el nombre del producto al ser creado, usada al asignar lote auto

    /* buscara los campos de lista desplegable con la clase select2 + la funcion interna select2*/
    $('.select2').select2();

    /* Variable que almacena la DataTable.                             */
    /* lee los datos de la BD y los distribuye en sus filas y columnas */

    funcion = "listarProducts";
    let datatable = $('#tabla_products').DataTable( {

        "scrollX": true,
        "order": [[ 2, "asc" ]],



        ajax: "data.json",
        
        "ajax": {
            
            "url":"../controllers/productoController.php",
            "method":"POST",
            "data":{funcion:funcion},
            "dataSrc":""
        },
        "columns": [

            { "defaultContent": `

                <button class="editar btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#crearproduct">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button class="borrar btn btn-sm btn-danger">
                    <i class="fas fa-trash-alt"></i>
                </button>

            `},
            { "data": "codbar" },
            { "data": "tipo" },
            { "data": "nombre" },
            { "data": "presentacion" },
            { "data": "precio" },
            { "data": "compos" }
        ],
        language: espanol,
    } );


    // listarProdCons();
    // function listarProdCons(){
    //     funcion = "listarProducts";
    //     $.post('../controllers/productoController.php',{funcion},(response)=>{
    //         console.log(response);
   
    //     })
    // }


    /******************************************************************************/
    /* Estas funciones carga los datos a las listas desplegables del formulario   */
    /* de registrar y editar                                                      */
    /******************************************************************************/

    listar_tipos();
    function listar_tipos(){
        funcion = "listar_tipos";
        $.post('../controllers/tipoController.php',{funcion},(response)=>{
            // console.log(response);
            const TIPOS = JSON.parse(response);
            let template = '';
            TIPOS.forEach(tipo=>{
                template+=`
                    <option value="${tipo.id_tipo}">${tipo.nom_tipo}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#prod_tipo').html(template);
        })
    }



    listar_presents();
    function listar_presents(){
        funcion = "listar_presents";
        $.post('../controllers/presentacionController.php',{funcion},(response)=>{
            // console.log(response);
            const PRESENTS = JSON.parse(response);
            let template = '';
            PRESENTS.forEach(present=>{
                template+=`
                    <option value="${present.id_present}">${present.nom_present}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#prod_pres').html(template);
        })
    }



    /******************************************************************************/
    /* Funciones de Crear, generar alertas, Editar y Eliminar.    */
    /*                                                                            */
    /* datatable.ajax.reload(): Recarga automaticamente la tabla al efectuar      */
    /* el crud.                                                                   */
    /******************************************************************************/

    /******************************************************************************/

    $('#crearproduct').on('shown.bs.modal', function(){
        $('#codbar').focus();
    })

    $(document).on('click','#btn-crear',(e)=>{
        // console.log("click en br crear");
        $('#codbar').attr("type","number");
        // $('#form-crear-product').trigger('reset');

        edit = false;   // bandera
    });

    /******************************************************************************/
    /* CREAR Y ALERTAS */
    $('#form-crear-product').submit(e=>{
        /* recibir los datos del formulario al hacer click en el boton submit */
        /* val(): obtiene el valor en el imput */
        let id = $('#id_edit-prod').val()
        let codbar = $('#codbar').val();
        let nombre = $('#nombre').val();
        let compos = $('#compos').val();    //Ingredientes
        let prod_tipo = $('#prod_tipo').val();  // Categoria
        let prod_pres = $('#prod_pres').val();
        let precio = $('#precio').val();

        if($('#iva').is(':checked')){
            $('#iva').prop("value","1");
        }else{
            $('#iva').prop("value","0");
        }
        let iva = $('#iva').val();

       
        if(edit==true){
            funcion="editar";
            console.log("editar");
        }else{
            funcion="crear";
            console.log("crear");
        }
        
        $.post('../controllers/productoController.php',{funcion,id,codbar,nombre,compos,prod_tipo,prod_pres,precio,iva},(response)=>{

            console.log(response);
            if(response=='add'){
                nomProd = nombre;
                $('#add-product').hide('slow');
                $('#add-product').show(1000);
                $('#add-product').hide(2000);

                /* Vaciar campos */
                $('#codbar').val('');
                $('#nombre').val('');
                $('#compos').val('');
                $('#adici').val('');
                $('#precio').val('');

                $('#crearproduct').modal('hide');    //cerrar el modal prod"

                datatable.ajax.reload();

                // $('#crearlote').modal();    //Desplegar el modal de "lote"
                // // $('#form-crear-product').trigger('reset');

                // asignarLoteAutomatic(); // Ejecutar asignacion de lote al prod. creado
  
            }
            if(response=='edit'){
                $('#edit-product').hide('slow');
                $('#edit-product').show(1000);
                $('#edit-product').hide(2000);

                /* Vaciar campos */
                $('#codbar').val('');
                $('#nombre').val('');
                $('#compos').val('');
                $('#adici').val('');
                $('#precio').val('');

                // $('#form-crear-product').trigger('reset');
                datatable.ajax.reload();
            }
            if(response=='noadd'){
                $('#noadd-product').hide('slow');
                $('#noadd-product').show(1000);
                $('#noadd-product').hide(2000);
                $('#form-crear-product').trigger('reset');
                datatable.ajax.reload();
            }
            if(response=='noedit'){
                $('#noadd-product').hide('slow');
                $('#noadd-product').show(1000);
                $('#noadd-product').hide(2000);
                $('#form-crear-product').trigger('reset');
                datatable.ajax.reload();
            }
            edit = false;
        })
        e.preventDefault();
    });

    /******************************************************************************/

    $('#tabla_products tbody').on('click','.editar',function(){
        let datos = datatable.row($(this).parents()).data();

        let id= datos.id_prod;
        let codbad= datos.codbad;
        let nombre= datos.nombre;
        let compos= datos.compos;
        // let adici= datos.adici;
        let iva= datos.iva;
        let precio= datos.precio;
        // let prod_lab= datos.prod_lab;
        let prod_tipo= datos.prod_tipo;
        let prod_pres= datos.prod_pres;

        /* Si el valor de iva proveniente de la bd es true (1) entonces añadir o no la */
        /* propiedad checked al input de iva, de esa manera aparece o no chequeado.    */
        if(iva == 1){
            $('#iva').prop("checked",true);
        }else{
            $('#iva').prop("checked",false);
        }

        /* Los $(#...) Son los identificadores del formulario */
        $('#id_edit-prod').val(id);
        $('#nombre').val(nombre);
        $('#codbad').val(codbad);
        $('#compos').val(compos);
        // $('#adici').val(adici);

        let nval = 0;   // nval: nuevo valor del iva

        if($('#iva').is(':checked')){
            $('#iva').prop("value","1");
            nval = 1;
            $('#iva').val(nval).trigger('change');
        }else{
            $('#iva').prop("value","0");
            nval = 0;
            $('#iva').val(nval).trigger('change');
        }

        $('#precio').val(precio);
        // $('#prod_lab').val(prod_lab).trigger('change');
        $('#prod_tipo').val(prod_tipo).trigger('change');
        $('#prod_pres').val(prod_pres).trigger('change');
        $('#codbar').attr("type","hidden");
        $('#labcodbar').hide();

        edit = true;   // bandera
    });

    /******************************************************************************/
    /* FUNCION BORRAR */

    $('#tabla_products tbody').on('click','.borrar',function(){
        let datos = datatable.row($(this).parents()).data();
        let id= datos.id_prod;
        let nom= datos.nombre;
        var funcion = "borrar";
        /* Alerta */
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success m-1',
              cancelButton: 'btn btn-danger m-1'
            },
            buttonsStyling: false
        })
         
        swalWithBootstrapButtons.fire({
            title: '¿Está seguro que desea eliminar el producto '+nom+'?',
            text: "Esta acción ya no se podrá deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                console.log(id);
                $.post('../controllers/productoController.php',{id,funcion},(response)=>{
                    datatable;
                    if(response=='borrado'){

                        swalWithBootstrapButtons.fire(
                            'Eliminado '+nom+'!',
                            'El producto ha sido eliminado.',
                            'success'
                        )
                        datatable.ajax.reload();
                      
                    }else{
                        swalWithBootstrapButtons.fire(
                            'Error al eliminar '+nom,
                            'El producto está siendo usado en un lote o venta.',
                            'error'
                        )
                        datatable.ajax.reload();
                    }
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
    });


    /******************************************************************************/
    /* Generar un pdf con los productos del inventario                            */
    /******************************************************************************/
    $(document).on('click','#btn-reporte',(e)=>{
        funcion = 'rep_prod';
        $.post('../controllers/productoController.php',{funcion},(response)=>{
            console.log(response);
            /* Blanc es para que abra una estaña nueva */
            window.open('../pdf/pdf-'+funcion+'.pdf','_blank');
        });
    })  
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