$(document).on('click','#logout',(e)=>{
    let funcion = 'datos';

    var usuario, fecha;
    /* datos, traera desde el controlador los datos fecha y id usuario */

    // $.post('../controllers/ventaController.php',{funcion},(resp)=>{
    $.post('../controllers/logout.php',{funcion},(resp)=>{
        console.log(resp);
        const VALORES = JSON.parse(resp);
    
        VALORES.forEach(valor=>{
            usuario = valor.idUsu
            fecha = valor.fecha
     
            funcion = 'rep_venta';
            $.post('../controllers/logout.php',{funcion,usuario,fecha},(response)=>{  
                console.log(response);      
                window.open('../pdf/pdf-'+usuario+fecha+'.pdf','_blank');
            });
            cerrar();
        });
    });


})

/* Por una extraña razon, si elimino la funcion salir, 
entra en error. tener en cuenta que el if.. salir ya fue quitado del model */
function cerrar(){
    funcion = "salir";
    $.post('../controllers/logout.php',{funcion},(respon)=>{
        window.location.href = '../index.php';
    });


}

