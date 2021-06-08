/* PDF REPORTE VENTA DIARIA */

function cerrar(){
    let funcion = 'totalVentas';

}


$(document).on('click','#logout',(e)=>{


    var usuario, fecha;
    /* datos, traera desde el controlador los datos fecha y id usuario */
    let funcion = 'datos';

    // $.post('../controllers/ventaController.php',{funcion},(resp)=>{
    $.post('../controllers/logout.php',{funcion},(resp)=>{
        // console.log(resp);
        const VALORES = JSON.parse(resp);
    
        VALORES.forEach(valor=>{
            usuario = valor.idUsu
            fecha = valor.fecha
     
            funcion = 'rep_venta';
            $.post('../controllers/logout.php',{funcion,usuario,fecha},(response)=>{        
                window.open('../pdf/pdf-'+usuario+fecha+'.pdf','_blank');
            });
        });
    });
})