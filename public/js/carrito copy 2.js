$(document).ready(function(){

    verificarStock()
    contarProductos();
    recuperarLS_car_compra();
    recuperarLS_car();
    calcularTotal()


    funcion = "listarProducts";
    let datatable = $('#tablaProdCat').DataTable( {

        "scrollX": true,


        ajax: "data.json",
        
        "ajax": {
            
            "url":"../controllers/productoController.php",
            "method":"POST",
            "data":{funcion:funcion},
            "dataSrc":""
        },
        "columns": [

            { "defaultContent": `

                <button class="agregar-carrito lote btn btn-sm btn-primary">
                    <i class="fas fa-plus-square mr-2"></i>Agregar
                </button>

            `},
            { "data": "stock" },
            { "data": "nombre" },
            { "data": "compos" },
            { "data": "precio" },
            { "data": "laboratorio" },
            { "data": "codbar" },
       
        ],
        language: espanol,
    } );



    /* CARRITO DE COMPRAS AL HACER CLICK EN EL BOTON DE CADA PRODUCTO "AGREGAR AL CARRITO"*/

        $('#tablaProdCat tbody').on('click','.agregar-carrito',function(){
            let datos = datatable.row($(this).parents()).data();
     

        const ID = datos.id_prod;
        const NOMB = datos.nombre;
        const COMPOS = datos.compos;
        const PRECIO = datos.precio;
        const ADICI = datos.adici;
        const IVA = datos.iva;
        const PLAB = datos.prod_lab;
        const PTIPO = datos.prod_tipo;
        const PPRES = datos.prod_pres;
        const STOCK = datos.stock;

        // console.log("stock:"+STOCK);
        // console.log("lab:"+PLAB);
        // console.log("iva:"+IVA);


        const PRODUCTO = {
            id_prod : ID,
            nombre : NOMB,
            compos : COMPOS,
            adici : ADICI,
            iva : IVA,
            precio : PRECIO,
            laboratorio : PLAB,
            tipo : PTIPO,
            presentacion : PPRES,
            stock : STOCK,
            cantidad : 1
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
                text: 'Ya ingresaste este producto al carrito',
            })
        }else{
            template=`
            <tr prodId="${PRODUCTO.id_prod}">
                <td>${PRODUCTO.id_prod}</td>
                <td>${PRODUCTO.nombre}</td>
                <td>${PRODUCTO.compos}</td>
                <td>${PRODUCTO.adici}</td>
                <td>${PRODUCTO.precio}</td>
                <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
            </tr>
            `;
            $('#tbd-lista').append(template);
            agregarLS(PRODUCTO);
            contarProductos();
        }     
    });



    /* TRABJO EN EL CODBAR****************************** */

    /* BUSCAR EL DATO INGRESADO EN EL CAMPO CODBAR*/
    function buscarCodbar(consulta){
        funcion = 'buscaCodbar';
        console.log('voy a consultar: '+consulta);
        
        $.post('../controllers/productoController.php',{consulta,funcion},(response)=>{
            // console.log(response);

            const RES = JSON.parse(response);
            RES.forEach(result=>{

                const ID = result.id_prod;
                const NOMB = result.nombre;
                const COMPOS =result.compos;
                const PRECIO = result.precio;
                const ADICI = result.adici;
                const IVA = result.iva;
                const PLAB = result.lab_id;
                const PTIPO = result.tipo_id;
                const PPRES = result.pres_id;
                const STOCK = result.stock;

                // console.log(PLAB);

                const PRODUCTOCAR = {
                    id_prod : ID,
                    nombre : NOMB,
                    compos : COMPOS,
                    adici : ADICI,
                    iva : IVA,
                    precio : PRECIO,
                    laboratorio : PLAB,
                    tipo : PTIPO,
                    presentacion : PPRES,
                    stock : STOCK,
                    cantidad : 1
                }

                /* verificar si el producto existe ya en el carr */
                let id_prod;
                let products;
                products = recuperarLS();
                products.forEach(prod=>{
                    if(prod.id_prod === PRODUCTOCAR.id_prod){
                        id_prod = prod.id_prod
                    }
                })

                if(id_prod === PRODUCTOCAR.id_prod){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ya ingresaste este producto al carrito',
                    })
                }else{
                    template=`
                        <tr prodId="${PRODUCTOCAR.id_prod}">
                            <td>${PRODUCTOCAR.id_prod}</td>
                            <td>${PRODUCTOCAR.nombre}</td>
                            <td>${PRODUCTOCAR.compos}</td>
                            <td>${PRODUCTOCAR.adici}</td>
                            <td>${PRODUCTOCAR.precio}</td>
                            <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
                        </tr>
                    `;
                    $('#tbd-lista').append(template);
                    agregarLS(PRODUCTOCAR);
                    contarProductos();
                    // $('#campo_codbar').trigger('reset')

                } 
            })
        })
    }

    function limpiarFormulario() {
        console.log('limpio');
        $('#campo_codbar').val('');
    }

    /* Lee el dato en el codbar y entonces ejecuta busqueda*/
    $(document).on('change','#campo_codbar',function(){
        let valor = $(this).val();
            // console.log('valor es:'+valor);
            if(valor != ''){
            // console.log('no es vacio:'+valor);
            buscarCodbar(valor);
            limpiarFormulario();
        }else{
            buscarCodbar();
        }
    });

 
    /* FIN CODBAR ***************************************/


    $(document).on('click','.borrar-producto',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('prodId');
        ELEM.remove();
        eliminarProdLS(ID);
        contarProductos();
        calcularTotal()
    })

    /* VACIAR CARRITO */
    $(document).on('click','#vaciar-carrito',(e)=>{
        /* borra todos los elementos del tbody */
        $('#tbd-lista').empty();
        eliminarLS();
        contarProductos();
        calcularTotal()
    });

    /* la e es de evento */
    $(document).on('click','#procesar-pedido',(e)=>{
        procesarPedido();
    })

    $(document).on('click','#procesar-compra',(e)=>{
        procesarCompra();
    })

    function recuperarLS(){
        let productos;
        if(localStorage.getItem('productos')===null){
            productos=[];
        }else{
            productos = JSON.parse(localStorage.getItem('productos'));
        }
        return productos;
    }

    function agregarLS(producto){
        let productos;
        productos = recuperarLS();
        productos.push(producto);
        localStorage.setItem('productos',JSON.stringify(productos));
    }

    /* Recupera los datos del LS en el carrito */
    function recuperarLS_car(){
        let productos, id_producto;
        productos = recuperarLS();
        funcion="buscar_id";
        productos.forEach(producto => {
            id_producto =producto.id_prod;
            $.post('../controllers/productoController.php',{funcion,id_producto},(response)=>{
                // console.log('Soy el response de recuperarLS_car: '+ response);
                let template_car='';
                /* decodificar el json response */
                let json = JSON.parse(response);
                template_car=`
                    <tr prodId="${json.id_prod}">
                        <td>${json.id_prod}</td>
                        <td>${json.nombre}</td>
                        <td>${json.compos}</td>
                        <td>${json.adici}</td>
                        <td>${json.precio}</td>
                        <td><button class="btn btn-danger borrar-producto" ><i class="fas fa-times-circle"></i></button></td>
                    </tr>
                `;
                /* no se hace forech porque se esta recibiendo objeto por objeto */
                $('#tbd-lista').append(template_car);          
            });        
        });
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
            location.href = '../views/adm_compra.php';
        }
    }


    /*
     *Funcion para imprimir en la tabla de la vista adm_compra
     *los items que se han almacenado en el local storage
    */
    async function recuperarLS_car_compra(){

        let productos;
        funcion="traer_productos";
        productos = recuperarLS();

        const RESPONSE = await fetch('../controllers/productoController.php',{
            method:'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'funcion='+funcion+'&&productos='+JSON.stringify(productos)
        });
        let resultado = await RESPONSE.text();
        // console.log(resultado);
        $('#lista-compra').append(resultado);          
    }


    /* Evento boton actualizar */
    $(document).on('click','#actualizar',(e)=>{
        let productos, precios;
        precios = document.querySelectorAll('.precio');
        productos = recuperarLS();
        productos.forEach(function(producto,indice){
            producto.precio = precios[indice].textContent;
        });
        localStorage.setItem('productos',JSON.stringify(productos));
        calcularTotal();
    })

    /* evento para que cuando en el campo de cantidad en adm_compra se le modifique la cantidad
    capture ese dato y lo modifique en el local storage */
    $('#cp').keyup((e)=>{
        let id, cantidad, producto, productos, montos, precio;
        /* la var producto capturara toda la fila <tr> en la tabla de compra */
        producto = $(this)[0].activeElement.parentElement.parentElement;
        /* compaarar id y modificar la  coantidad */
        id =$(producto).attr('prodId');
        precio =$(producto).attr('prodPrecio');
        /* Capturar el valor del input de cantidad, pero ya que cada producto genera una fila
        la cual tiene su input cantidad, se realiza lo siguiente: con queryselector
        seleccionara todos los 'input' que vaya generando cada producto */
        cantidad = producto.querySelector('input').value;

        /* Capturar todos los subtotales de la tabla, captura todos los td de clase 'subtotales' */
        montos = document.querySelectorAll('.subtotales');

        /* Recuperar todos los datos del LocalStorage */
        productos = recuperarLS();
        /* foreach para recuperar lo almacenado */
        productos.forEach(function(prod, indice){
            // console.log("hola" + prod.id_prod);
            /* seleccionar y comparar los ida. busca en todos los id el que sea igual al id en el cual se esta editando la cantidad */
            if(prod.id_prod === id){
                /* al indice cantidad (hubicado en el LS) se le asigna el valor ingresado en el input cantidad */
                prod.cantidad = cantidad;
                prod.precio = precio;
                /* capturar el subtotal del producto en cuestion 
                */
                montos[indice].innerHTML = `<h5>${cantidad*precio}</h5>`;
            }
        });
        localStorage.setItem('productos',JSON.stringify(productos));
        calcularTotal()
    })

    function calcularTotal(){

        let productos,
            subtotal,
            totSinDescuento,
            conIgv,
            total = 0,
            igv = 0.19,
            divIva = 1.19;
            pago = 0,
            vuelto=0,
            descuento = 0,
            subProdIva =0,
            subBaseProdIva =0,
            ivaTot = 0,
            ivaTotEx = 0,
            subProdExentoIva=0,
            baseTotal = 0;

        productos = recuperarLS();
        /* este buble recorre los productos en el carrito */
        productos.forEach(producto => {

            let subtotProd = Number(producto.precio*producto.cantidad);


            /* Calcular el Total de los productos con IVA */
            if(producto.iva == 1){
                
                subProdIva += subtotProd;                   /* Total Productos Con IVA Agregando el IVA*/
                subBaseProdIva = subProdIva / divIva;       /* Total Productos Con IVA sin asignar el valor IVA*/
                ivaTot = subBaseProdIva * igv;

            }
            /* Calcular el Total de los productos con IVA */
            if(producto.iva == 0){
                       subProdExentoIva += subtotProd;
                ivaTotEx = subProdExentoIva * 0
                
              

            }
            // else {
            //     subProdExentoIva += subtotProd;
            //     ivaTotEx = subProdExentoIva * 0
            // }

            /* Calcular Total Bases Exentos y agravados con IVA */
            baseTotal = subBaseProdIva + subProdExentoIva;


            /* Calcular el Total de todos los subtotales (con y sin IVA) */
            total+=subtotProd;
        });

        /* Capturar valor imputs */
        pago = $('#pago').val();
        // descuento = 0;

        vuelto=pago-total;
        // console.log(total);
        totSinDescuento= total.toFixed(0)
        conIgv = parseFloat(total * igv).toFixed(0);
        subtotal = parseFloat(total-conIgv).toFixed(0);

        $('#subtotal').html(subtotal);
        $('#subProdIva').html(subProdIva.toFixed(0));
        $('#subBaseProdIva').html(subBaseProdIva.toFixed(0));
        $('#total_sin_descuento').html(totSinDescuento);
        $('.ivaTot').html(ivaTot.toFixed(0));
        $('.ivaTotEx').html(ivaTotEx.toFixed(0));
        $('#total').html(total.toFixed(0));
        $('#vuelto').html(vuelto.toFixed(0));
        $('.ExentoIva').html(subProdExentoIva.toFixed(0));
        $('#baseTotal').html(baseTotal.toFixed(0));
    }

    function procesarCompra(){
        let nombre = $('#cliente').val();
        if(recuperarLS().length == 0){
            Swal.fire({
                icon: 'error',
                title: 'Atencion',
                text: 'El Carrito esta vacio',
            }).then(function(){
                location.href = '../views/adm_cat.php'
            })
        }else{
            verificarStock().then(error=>{
                // console.log(error);
                if(error==0){
                    registrarCompra(nombre);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Venta Realizada',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function(){
                        eliminarLS();
                        location.href = '../views/adm_cat.php'
                    });

                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Atencion',
                        text: 'No hay  stock en algun producto',
                    })
                }
            });
            
        }
    }

    /* Verificar si hay stock */
    async function verificarStock(){
        let productos;
        funcion = 'verificar-stock';
        productos = recuperarLS();

        const RESPONSE = await fetch('../controllers/productoController.php',{
            method:'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'funcion='+funcion+'&&productos='+JSON.stringify(productos)
        });
        let error = await RESPONSE.text();
        return error;
    }

    function registrarCompra(nombre){
        funcion = 'registrarCompra';
        /* capturar el dato en el campo total . get[0]= obtener el primer dato*/
        let total = $('#total').get(0).textContent;

        /* Exentos */
        let totIvaEx = $('.ExentoIva').get(0).textContent;
        let totBaseIvaEx = $('.ExentoIva').get(0).textContent;
        let valIvaEx = $('.ivaTotEx').get(0).textContent;

        /* Gravados con IVA */
        let totIvaAp = $('#subProdIva').get(0).textContent;
        let totBaseIvaAp = $('#subBaseProdIva').get(0).textContent;
        let valIvaAp = $('.ivaTot').get(0).textContent;

        /* Totales */
        let baseTotal = $('#baseTotal').get(0).textContent;
        let ivaTotal = $('.ivaTot').get(0).textContent;

        let productos = recuperarLS();
        // let nomb = "mxpr";
        /* nviar ese producto al controlador */
        let json = JSON.stringify(productos);
        $.post('../controllers/compraController.php',{funcion,total,totIvaEx,totBaseIvaEx,valIvaEx,totIvaAp,totBaseIvaAp,valIvaAp,baseTotal,ivaTotal,json,nombre},(response)=>{
            console.log(response);
        })

    }

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
