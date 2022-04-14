$(document).ready(function(){

    consultarmedidas();

    var funcion;
    var edit = false;   // bandera

    /* Formukario crear editar */
    $('#form-crear-medida').submit(e=>{
        let nom_medida = $('#nom_medida').val();
        let id_editado = $('#id_edit_medida').val();

        if(edit==false){
            funcion="crear";
        }else{
            funcion="editar";
        }

        $.post('../controllers/invMedidaController.php',{nom_medida,id_editado,funcion},(response)=>{
            console.log(response)
            if(response=='add'){
                $('#add-medida').hide('slow');
                $('#add-medida').show(1000);
                $('#add-medida').hide(2000);
                $('#form-crear-medida').trigger('reset');
                consultarmedidas();
            }
            if(response=='noadd'){
                $('#noadd-medida').hide('slow');
                $('#noadd-medida').show(1000);
                $('#noadd-medida').hide(2000);
                $('#form-crear-medida').trigger('reset');
            }
            if(response=='edit'){
                $('#edit-medida').hide('slow');
                $('#edit-medida').show(1000);
                $('#edit-medida').hide(2000);
                $('#form-crear-medida').trigger('reset');
                consultarmedidas();
            }
            edit=false;
        });
        e.preventDefault();
    });


    function consultarmedidas(consulta){
        funcion = 'consultarmedidas';
        // ajax
        $.post('../controllers/invMedidaController.php',{consulta,funcion},(response)=>{
            console.log(response);
            const medidaS = JSON.parse(response);
            let template = '';
            medidaS.forEach(medida=>{
                template+=`
                <tr medidaId="${medida.id_medida}" medidaNom="${medida.nom}">
                    <td>${medida.nom}</td>
                    <td>
                        <button class="editar-medida btn btn-success" title="editar" type="button" data-toggle="modal" data-target="#crearmedida">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
    
                        <button class="borrar-medida btn btn-danger" title="borrar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
            });
            $('#tbd-medidas').html(template);
        })
    }


    // evento para las busquedas
    $(document).on('keyup','#busq-medida',function(){
        let valor = $(this).val();
        if(valor != ''){
            consultarmedidas(valor);
        }else{
            consultarmedidas();
        }
    });

        

    $(document).on('click','.editar-medida',(e)=>{
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('medidaId');
        const NOMB = $(ELEM).attr('medidaNom');
        console.log(ID+NOMB);
        $('#id_edit_medida').val(ID);
        $('#nom_medida').val(NOMB);
        edit=true;
    })


    $(document).on('click','.borrar-medida',(e)=>{
        var funcion = "borrar";
        const ELEM = $(this)[0].activeElement.parentElement.parentElement;
        const ID = $(ELEM).attr('medidaId');
        const NOMB = $(ELEM).attr('medidaNom');
        // console.log(ID + NOMB);

        // Alerta
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger mr-1'
            },
            buttonsStyling: false
          })
          
          swalWithBootstrapButtons.fire({
            title: '¿Eliminar ' + NOMB + '?',
            text: "Esta acción no se podrá deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
          }).then((result) => {
            if (result.value) {
                $.post('../controllers/invMedidaController.php',{ID,funcion},(response)=>{
                    // console.log(response);
                    edit==false;
                    if(response=='borrado'){
                        swalWithBootstrapButtons.fire(
                            'Eliminada ' + NOMB,
                            'medidaoría eliminada correctamente',
                            'success'
                        )
                        consultarmedidas();
                    }else{
                        swalWithBootstrapButtons.fire(
                            '¡No se pudo eliminar '+NOMB+'!',
                            'La medidaoría está siendo usada en algún producto.',
                            'error'
                        )
                    }
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            //   swalWithBootstrapButtons.fire(
            //     'Cancelado',
            //     '',
            //     'error'
            //   )
            }
          })
    });

});