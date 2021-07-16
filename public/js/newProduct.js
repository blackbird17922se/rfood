/* 
 * ORDEN
 * Controla las ordenes de Mesero
*/

$(document).ready(function(){

    var funcion, idCat="";

    
    $(".select2").select2({
        placeholder: "Seleccione una opcion",
    });

    $('#ing-carrito').show();


    /***********************************************
     * Lista el tipo y la presentacion del producto (o plato)
    ************************************************/ 
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


    /***********************************************
     * Lista los tipos de ingredientes  que puede llevar el producto a
     * crear (Panes, Carnes, Lacteos...)
    ************************************************/ 
    listarTipoIngred();
    function listarTipoIngred(){
        funcion = "listarTipoIngred";
        $.post('../controllers/invTipoController.php',{funcion},(response)=>{
            console.log(response);
            const TIPOS = JSON.parse(response);
            let template = '';
            TIPOS.forEach(tipo=>{
                template+=`
                    <option value=""></option>
                    <option value="${tipo.id_tipo}">${tipo.nom_tipo}</option>
                `;
            });
            /* id del campo que contiene el listado */
            $('#tipo_ing').html(template);
        })
    }



    var datatable="";

    mostrarProducts()
    contarProductos();
    recuperarLSRecarga()


    /* OJO---FUNCIONES DEL LOCAL STOG */

    function recuperarLS(){
        let ingreds;
        if(localStorage.getItem('ingreds')===null){
            ingreds=[];
        }else{
            ingreds = JSON.parse(localStorage.getItem('ingreds'));

        }
        return ingreds;
    }

    /* Recupera el localstarage al recargar la pagina */
    function recuperarLSRecarga(){
        let ingreds;
        if(localStorage.getItem('ingreds')===null){
            ingreds=[];
        }else{
            let template="";
            ingreds = JSON.parse(localStorage.getItem('ingreds'));
            ingreds.forEach(prod => {
                template=`
                <tr prodId="${prod.id_prod}">
                    <td>${prod.id_prod}</td>
                    <td>${prod.nombre}</td>
                    <td>${prod.medida}</td>
                    <td>${prod.cantidad}</td>
                    <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
                </tr>
                `;
                $('#tbd-lista-ing').append(template);
                
            });

        }
        return ingreds;
    }

    /* AGREGAR Producto al LOCAL STORAGE */
    function agregarLS(ingred){
        let ingreds;
        ingreds = recuperarLS();
        ingreds.push(ingred);
        localStorage.setItem('ingreds',JSON.stringify(ingreds));
    }

    function eliminarProdLS(ID){
        let ingreds;
        ingreds = recuperarLS();
        ingreds.forEach(function(ingred,indice){
            if(ingred.id_prod === ID){
                ingreds.splice(indice,1);
            }
        });
        localStorage.setItem('ingreds',JSON.stringify(ingreds));
    }

    function eliminarLS(){
        localStorage.clear();
    }

    /* Agrega el numero de productos que lleva el carrito */
    function contarProductos(){
        let ingreds;
        let contador = 0;
        ingreds = recuperarLS();
        ingreds.forEach(ingred=>{
            contador++;
        });
        // return contador;
        $('#contador').html(contador);

    }


    /* Carga los productos en la tabla segun la categoria indicada */
    function mostrarProducts(){
        funcion = "listarProducts";
        datatable = $('#tabla_products').DataTable({

            "scrollX": true,
            "order": [[ 2, "asc" ]],
    
            ajax: "data.json",
            
            "ajax": {
                
                "url":"../controllers/ingredienteController.php",
                "method":"POST",
                "data":{funcion:funcion, idCat:idCat},
                "dataSrc":""
            },
            "columns": [

           
                { "data": "cant" },
                { "data": "medida" },
                { "data": "nombre" },
                { "data": "id_prod" },
           
            ],
            language: espanol,
        });

        datatable.columns.adjust().draw();
       
    }

    function procesarPedido(){
        let productos;
        productos = recuperarLS();
        if(productos.length === 0){
            Swal.fire({
                icon: 'error',
                title: 'Atencion',
                text: 'El Carrito esta vacio',
            })
        }else{
            funcion = 'guardarIngreds';

        /* nviar ese producto al controlador */
            let json = JSON.stringify(productos);
            console.log(json);
            $.post('../controllers/ingredienteController.php',{funcion,json},(response=>{
                console.log(response);
            }));


            
            // productos = JSON.parse(localStorage.getItem('productos'));
            // productos.forEach(prod => {
            //     console.log(prod.id_prod);

            //     let id_mesa = $('#mesa').val();
            //     let id_prod = prod.id_prod;
            //     let entregado = 0;
            //     let terminado = 0;

            //     $.post('../controllers/pedidoController.php',{funcion,id_mesa,id_prod,entregado,terminado},(response=>{
            //         console.log(response);
            //         $('#tbd-lista-ing').empty();
            //         eliminarLS();
            //         contarProductos()
            //     }));

            // })




            // location.href = '../views/adm_compra.php';
        }
    }

    
    listarProdCons();
    function listarProdCons(){
        // console.log("listarProdCons");
        // console.log(idCat);

        funcion = "listarProducts";
        $.post('../controllers/ingredienteController.php',{funcion, idCat},(response)=>{
            console.log("prod: " + response);
   
        })
    }


    $( "#tipo_ing" ).change(function() {  
        datatable.destroy();
        idCat = $('#tipo_ing').val();
        // console.log(idCat);
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
        let datos = datatable.row($(this).parents()).data();

        const medida = datos.medida;
        const NOMB    = datos.nombre;
        const ID      = datos.id_prod;
        let CANT      = $('#'+ID).val();
        const PLATO   = $('#id_plato').val();

  

        // const CATEG = datos.categ;

        console.log("id:"+ID+" nom:"+NOMB+" cant:"+CANT);

        const PRODUCTO = {
            id_prod  : ID,
            nombre   : NOMB,
            medida  : medida,
            cantidad : CANT,
            id_plato : PLATO
        }

        /* verificar si el producto existe ya en el carr */
        let id_prod;
        let ingreds;
        ingreds = recuperarLS();
        ingreds.forEach(prod=>{
            if(prod.id_prod === PRODUCTO.id_prod){
                id_prod = prod.id_prod
            }
        })

        if(id_prod === PRODUCTO.id_prod){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ya ingresaste este producto al carrito',
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
                <td>${PRODUCTO.id_prod}</td>
                <td>${PRODUCTO.nombre}</td>
                <td>${PRODUCTO.medida}</td>
                <td>${PRODUCTO.cantidad}</td>
                <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
            </tr>
            `;
            $('#tbd-lista-ing').append(template);
            agregarLS(PRODUCTO);
            contarProductos();
        }   
    });


    /* Borrar product de car */
    $(document).on('click','.borrar-producto',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('prodId');
        ELEM.remove();
        eliminarProdLS(ID);
        contarProductos();
        // calcularTotal()
    })

    /* VACIAR CARRITO */
    $(document).on('click','#vaciar-carrito-ing',(e)=>{
        /* borra todos los elementos del tbody */
        $('#tbd-lista-ing').empty();
        eliminarLS();
        contarProductos();
        // calcularTotal()
    });


    /* Click en procesar pedido */
    $(document).on('click','#procesar-ing',(e)=>{
            $('#tipo_ing').select2("val","");

            procesarPedido();
            eliminarLS();
            contarProductos();
  
    
    })





    /* ************************INTEGRACION****************************************** */


    function registrarProduct(){

        funcion = 'registrarProduct';

        // let id = $('#id_edit-prod').val()
        let codbar = $('#codbar').val();
        let prod_tipo = $('#prod_tipo').val();  // Categoria
        let nombre = $('#nombre').val();
        let prod_pres = $('#prod_pres').val();
        let precio = $('#precio').val();

        if($('#iva').is(':checked')){
            $('#iva').prop("value","1");
        }else{
            $('#iva').prop("value","0");
        }
        let iva = $('#iva').val();

        // console.log("-" +codbar + "-" + nombre + "-" + prod_pres);


        let producto = recuperarLS();
        console.log(producto);

        /* nviar ese producto al controlador */
        let jsonIngreds = JSON.stringify(producto);
        $.post('../controllers/productoController.php',{funcion, codbar, prod_tipo, nombre, prod_pres, precio, iva, jsonIngreds},(response)=>{
            console.log(response);
        })

    }


    function procesarProduct(){

        if(recuperarLS().length == 0){
            Swal.fire({
                icon: 'error',
                title: 'Atencion',
                text: 'El Carrito esta vacio',
            }).then(function(){
                location.href = '../views/caja.php'
            })
        }else{
            registrarProduct();

                    /* INSERT */
                    Swal.fire({
                        title: 'Venta Realizada',
                        text: "¿Desea imprimir recibo?",
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Imprimir',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {

                        /* Si desea imprimir... */
                        if (result.value) {

                            funcion = "ultimaVenta";
                            $.post('../controllers/compraController.php',{funcion},(response)=>{
                                // console.log(response);
                                $.ajax({
                                    url: 'ticket.php',
                                    type: 'POST',
                                    success: function(resp){

                                        if(resp == 1){
                                            console.log('imprime...');
                                            vaciarTabla();
                                        }else{
                                            console.log("error impresion");
                                            vaciarTabla();
                                        }

                                    }
                                })
                            });

                        /* Si no desea impresion... */
                        } else if (result.dismiss === Swal.DismissReason.cancel) {

                            $.ajax({
                                url: 'ticketc.php',
                                type: 'POST',
                                success: function(resp){
                                    console.log("no imprime");
                                    
                                    if(resp==1){
                                        console.log("abrio registradora");
                                        vaciarTabla();
                                    }else{
                                        console.log("error al abrir registradora");
                                        vaciarTabla();
                                    }
                                }
                            })

                            console.log("otra");
                            vaciarTabla();
                        }
                    });
            
        }
    }

    $(document).on('click','#procesarProd',(e)=>{
        console.log("procesarProd");
        procesarProduct();
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