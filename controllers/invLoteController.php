<?php
include '../models/invLote.php';
$lote = new InvLote();

switch ($_POST['funcion']) {

    case 'crear':       
        $lote_id_prod = $_POST['lote_id_prod'];
        $lote_id_prov = $_POST['lote_id_prov'];
        $stock = $_POST['stock'];
        $vencim = $_POST['vencim'];

        $lote->crear($lote_id_prod,$lote_id_prov,$stock,$vencim);
    break;
    

    case 'cargarLotes':
        $lote->cargarLotes();
        $json=array();
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');

        /* operaciones de fechas */
        $fecha_actual = new DateTime($fecha);
        foreach($lote->objetos as $objeto){
            $vencimiento = new DateTime($objeto->vencim);
            $diferencia = $vencimiento->diff($fecha_actual);

            /* pasarrle parametros para que calcule la diferencia en meses o dias */
            $anio = $diferencia->y;
            $mes = $diferencia->m;
            $dia = $diferencia->d;
            /* esto soluciona el problema cuando la fecha exedio la fecha de vencimiento, pero muestra estado light */
            $verificado = $diferencia->invert;
            $estado = 'success';

        
                if($mes <= 3 && $anio ==0){
                    $estado = 'danger';
                }
                if($mes > 6){
                    $estado = 'success';
                }
                if($mes < 6 && $mes >= 3 && $anio == 0){
                    $estado = 'warning';
                }


            $json[]=array(

                /* '' =>$objeto->ALIAS ASIGNADO */
                'id_lote'    =>$objeto->id_lote,
                'nombre'     =>$objeto->prod_nom,
                'vencim'     =>$objeto->vencim,
                'prov_nom'   =>$objeto->prov_nom,
                'stock'      =>$objeto->stock,
                'tipo'       =>$objeto->tipo_nom,
                'medida_nom' =>$objeto->medida_nom,
                'mes'        => $mes,
                'dia'        => $dia,
                'anio'       => $anio,
                'estado'     => $estado,
                /* si es 1, la diferencia es positiva, si es 0 indica diferencia negativa. o sea los dias que lleva de vencido */
                'invert'     => $verificado
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    break;
    
    
    case 'editar':     
        $lote_id_prod = $_POST['lote_id_prod'];
        $stock = $_POST['stock'];

        $lote->editar($lote_id_prod,$stock);
    break;

    
    case 'borrar':     
        $id_lote = $_POST['ID'];
        $lote->borrar($id_lote);
    break;
    
    default:
        # code...
        break;
}