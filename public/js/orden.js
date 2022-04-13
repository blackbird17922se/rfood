/* 
 * ORDEN
 * Controla las ordenes de Mesero
*/

$(document).ready(function(){

    var funcion        = 0
    var idCat          = "";
    const ID_MESA      = $('#mesaId').val();
    const ITEM_CTRLR   = '../controllers/itemController.php';
    const TIPO_CTRLR   = '../controllers/tipoController.php';
    const PEDIDO_CTRLR = '../controllers/pedidoController.php';

    listarCategs();


    $(".select2").select2({
        placeholder: "Seleccione una opcion",
    });

    $('#cat-carrito').show()

    /* Botones */
    $(document).on('click','.salir',(e)=>{
        eliminarLS();
        window.location.href ='ordenMesas.php'; 
    });

    /* Le carga al listado superor una lista de categorias para que dependiendo
    la categoria que escoja, se muestraen los productos de la misma */ 
    function listarCategs(){
        funcion = "listar_tipos";
        $.post(TIPO_CTRLR,{funcion},(response)=>{
            // console.log(response);
            const TIPOS = JSON.parse(response);
            let template = '';
            TIPOS.forEach(tipo=>{
                template+=`
                    <option value=""></option>
                    <option value="${tipo.id_tipo}">${tipo.nom_tipo}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#prod_tipo').html(template);
        })
    }


    var datatable="";

    mostrarProducts()
    contarProductos();
    recuperarLSRecarga()


    /* OJO---FUNCIONES DEL LOCAL STOG */

    function recuperarLS(){
        let productos;
        if(localStorage.getItem('productos')===null){
            productos=[];
        }else{
            productos = JSON.parse(localStorage.getItem('productos'));

        }
        return productos;
    }

    /* Recupera el localstarage al recargar la pagina */
    function recuperarLSRecarga(){
        let productos;
        if(localStorage.getItem('productos')===null){
            productos=[];
        }else{
            let template="";
            productos = JSON.parse(localStorage.getItem('productos'));
            productos.forEach(prod => {
                template=`
                <tr prodId="${prod.id_prod}">
                    <td>${prod.nombre}</td>
                    <td>${prod.present}</td>
                    <td>${prod.precio}</td>
                    <td>${prod.cantidad}</td>
                    <td class="td_btn_del">
                        <button class="btn btn-danger btn-block borrar-producto">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </td>
                </tr>
                `;
                $('#tbd-lista').append(template);
                
            });

        }
        return productos;
    }

    /* AGREGAR Producto al LOCAL STORAGE */
    function agregarLS(producto){
        let productos;
        productos = recuperarLS();
        productos.push(producto);
        localStorage.setItem('productos',JSON.stringify(productos));
    }

    function eliminarProdLS(ID){
        let productos;
        productos = recuperarLS();
        productos.forEach(function(producto,indice){
            if(producto.id_prod === ID){
                productos.splice(indice,1);
            }
        });
        localStorage.setItem('productos',JSON.stringify(productos));
    }

    function eliminarLS(){
        localStorage.clear();
    }

    /* Agrega el numero de productos que lleva el carrito */
    function contarProductos(){
        let productos;
        let contador = 0;
        productos = recuperarLS();
        productos.forEach(producto=>{
            contador++;
        });
        // return contador;
        $('#contador').html(contador);

    }


    /* Carga los productos en la tabla segun la categoria indicada */
    function mostrarProducts(){
        funcion = 141;
        datatable = $('#tabla_products').DataTable({

            "scrollX": true,
            "order": [[ 1, "asc" ]],
    
            ajax: "data.json",
            
            "ajax": {
                
                "url":ITEM_CTRLR,
                "method":"POST",
                "data":{funcion:funcion, idCat:idCat},
                "dataSrc":""
            },
            "columns": [

                { "data": "cant" },
                { "data": "nombre" },
                { "data": "present" },
                { "data": "precio" }
            ],
            language: espanol,
        });

        datatable.columns.adjust().draw();
       
    }

    function procesarPedido(){
        console.log(ID_MESA);
        let productos;
        productos = recuperarLS();
        if(productos.length === 0){
            Swal.fire({
                icon: 'error',
                title: 'Atención',
                text: 'Debes agregar algún producto al pedido',
            })
        }else{
            funcion = 1;
            let id_mesa = ID_MESA;
            let observ = $('#observ').val();
            let entregado = 0;
            let terminado = 0;
            let pagado    = 0;

        // let productos = recuperarLS();
        /* nviar ese producto al controlador */
            let json = JSON.stringify(productos);
            // console.log(json);
            $.post(PEDIDO_CTRLR,{funcion,id_mesa,json,observ,entregado,terminado,pagado},(response=>{
                console.log(response);
                console.log(`val observ: ${observ}`);

            }));

            /* Bloquear mesa */
            funcion = 10;
            $.post(PEDIDO_CTRLR,{funcion,ID_MESA});

            Swal.fire({
                icon: 'success',
                title: 'Hecho',
                text: 'Orden creada',
            })
            datatable.ajax.reload();
            eliminarLS();
            $(".select2").val('').trigger('change');
            window.location.href ='ordenMesas.php';
        }
    }

    
    // listarProdCons();
    // function listarProdCons(){
    //     funcion = "listarProducts";
    //     $.post('../controllers/ordenController.php',{funcion, idCat},(response)=>{
    //         console.log(response);
   
    //     })
    // }


    $( "#prod_tipo" ).change(function() {  
        datatable.destroy();
        idCat = $('#prod_tipo').val();
        console.log(idCat);
        mostrarProducts()
    });


    /* Evento que controla el input de cantidad y sus flechas */
    $(document).on('click', '.btn-plus, .btn-minus', function(e) {
        const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');
        const input = $(e.target).closest('.input-group').find('input');
        if (input.is('input')) {
        input[0][isNegative ? 'stepDown' : 'stepUp']()
        }
    })
    // $('.btn-plus, .btn-minus').on('click', function(e) {
    //     const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');
    //     const input = $(e.target).closest('.input-group').find('input');
    //     if (input.is('input')) {
    //     input[0][isNegative ? 'stepDown' : 'stepUp']()
    //     }
    // }) 



    /* CARRITO DE COMPRAS AL HACER CLICK EN EL BOTON DE CADA PRODUCTO "AGREGAR AL CARRITO"*/

    $('#tabla_products tbody').off('click','.agregar-carrito').on('click','.agregar-carrito',function(){
        //$('.agregar-carrito').addClass('.vettttttttt');

        let datos = datatable.row($(this).parents()).data();

        const ID      = datos.id_prod;
        const NOMB    = datos.nombre;
        const PRESENT = datos.present;
        const PRECIO  = datos.precio;
        let CANT      = $('#'+ID).val();

        var btn_item = 'btn-item-'+datos.btn_item
        

        console.log(btn_item);

  

        // const CATEG = datos.categ;

        // console.log("id:"+ID+" nom:"+NOMB+" pre:"+PRECIO+" cant:"+CANT);

        const PRODUCTO = {
            id_prod  : ID,
            nombre   : NOMB,
            present  : PRESENT,
            precio   : PRECIO,
            cantidad : CANT,
            // categ : CATEG,
        }

        /* verificar si el producto existe ya en el carr */
        let id_prod;
        let products;
        products = recuperarLS();
        products.forEach(prod=>{
            if(prod.id_prod === PRODUCTO.id_prod){
                id_prod = prod.id_prod
            }
        })

        if(id_prod === PRODUCTO.id_prod){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ya ingresaste este producto al pedido',
            })
        }else if(PRODUCTO.cantidad == 0){
            Swal.fire({
                icon: 'error',
                title: 'Atención',
                text: 'Debe ingresar una cantidad diferente a cero',
            })
        }else{
            template=`
            <tr prodId="${PRODUCTO.id_prod}">
                <td>${PRODUCTO.nombre}</td>
                <td>${PRODUCTO.present}</td>
                <td>${PRODUCTO.precio}</td>
                <td>${PRODUCTO.cantidad}</td>
                <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
            </tr>
            `;
            $('#tbd-lista').append(template);
            agregarLS(PRODUCTO);
            contarProductos();

            //Al agregar el item, desabilitar el boton de agregar
            $("#btn-item-"+datos.btn_item).prop('disabled', true);
        }   
    });


    /* Borrar product de car */
    $(document).on('click','.borrar-producto',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('prodId');
        console.log(ID);
        ELEM.remove();
        eliminarProdLS(ID);
        $("#btn-item-"+ID).prop('disabled', false);
        contarProductos();
        // calcularTotal()
    })

    /* VACIAR EL DESPLEGABLE DEL PEDIDO ACTUAL */
    $(document).on('click','#vaciar-pedido',(e)=>{
        /* borra todos los elementos del tbody */
        $('#tbd-lista').empty();
        eliminarLS();
        contarProductos();
        datatable.ajax.reload();
        $("#prod_tipo").val('').trigger('change');
    });


    /* Click en procesar pedido */
    $(document).on('click','#procesar-orden',(e)=>{
        procesarPedido();
            
        /* Bloquear la mesa y refrescar lista */
/*         if(mesa = -1){
            funcion = 10;
            $.post(PEDIDO_CTRLR,{funcion,mesa},(() =>{
                listarMesas();
            }));
        } */
        eliminarLS();
        contarProductos();
        $('#tbd-lista').empty();
        $(".select2").val('').trigger('change');
    
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