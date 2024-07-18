$(document).ready(function() {
    const nuevoActorBtn = $('#nuevoActorBtn');
    const formularioActor = $('#formularioActor');
    const closeModalBtn = $('.close');
    let editingId = null; // Variable para almacenar el ID del actor en edición
    let submitting = false; // Variable para controlar el estado de envío del formulario

    function abrirModal() {
        formularioActor.css('display', 'block');
    }

    function cerrarModal() {
        formularioActor.css('display', 'none');
        editingId = null;
        $('#actorForm')[0].reset();
    }

    nuevoActorBtn.click(abrirModal);
    closeModalBtn.click(cerrarModal);

    $(window).click(function(event) {
        if (event.target == formularioActor[0]) {
            cerrarModal();
        }
    });

    $('#buscarNombre').on('input', function(event) {
        const searchTerm = event.target.value.toLowerCase();
        const rows = $('#tablaActores tbody tr');

        rows.each(function() {
            const nombre = $(this).find('td:nth-child(1)').text().toLowerCase();
            $(this).toggle(nombre.includes(searchTerm));
        });
    });

    cargarActores();

    // Función para editar un actor
    $('#tablaActores').on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        editingId = id; // Guardar el ID del actor en edición
        const row = $(this).closest('tr');
        const nombre = row.find('td:nth-child(1)').text();
        const apellido = row.find('td:nth-child(2)').text();
        const fechaNacimiento = row.find('td:nth-child(3)').text();
        const nacionalidad = row.find('td:nth-child(4)').text();

        // Llenar el formulario con los datos del actor a editar
        $('#nombre').val(nombre);
        $('#apellido').val(apellido);
        $('#fechaNacimiento').val(fechaNacimiento);
        $('#nacionalidad').val(nacionalidad);

        abrirModal(); // Abrir el modal para edición
    });

    // Función para eliminar un actor
    $('#tablaActores').on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Está seguro que desea eliminar este actor?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarActor(id);
            }
        });
    });

    // Función para enviar la solicitud de eliminación al servidor
    function eliminarActor(id) {
        $.ajax({
            url: '../../controllers/actores.controller.php',
            type: 'POST',
            data: JSON.stringify({ op: 'eliminar', id_actor: id }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response.resultado === "ok") {
                    toastr.success('Actor eliminado correctamente');
                    cargarActores(); // Volver a cargar la tabla después de eliminar
                } else {
                    toastr.error('Error al eliminar actor');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error('Error de conexión al servidor. Inténtelo de nuevo más tarde.');
            }
        });
    }

    $('#actorForm').submit(function(event) {
        event.preventDefault();

        if (submitting) {
            return; // Evitar envío múltiple si ya se está procesando una solicitud
        }

        submitting = true;

        var nombre = $('#nombre').val();
        var apellido = $('#apellido').val();
        var fechaNacimiento = $('#fechaNacimiento').val();
        var nacionalidad = $('#nacionalidad').val();

        var data = {
            nombre: nombre,
            apellido: apellido,
            fecha_nacimiento: fechaNacimiento,
            nacionalidad: nacionalidad
        };

        if (editingId) {
            data.op = 'actualizar';
            data.id_actor = editingId;
        } else {
            data.op = 'insertar';
        }

        $.ajax({
            url: '../../controllers/actores.controller.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response.resultado === "ok") {
                    toastr.success('Operación exitosa');
                    cerrarModal(); // Función para cerrar el modal
                    cargarActores(); // Volver a cargar la tabla después de insertar/actualizar
                } else {
                    toastr.error('Error al procesar la solicitud: ' + (response.error || 'Error desconocido'));
                }
            },
            complete: function() {
                submitting = false;
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error('Error de conexión al servidor. Inténtelo de nuevo más tarde.');
                submitting = false;
            }
        });
    });
});

function cargarActores() {
    $.ajax({
        url: '../../controllers/actores.controller.php',
        type: 'POST',
        data: JSON.stringify({ op: 'todos' }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response !== "No se encontraron actores.") {
                mostrarActores(response);
            } else {
                toastr.warning('No se encontraron actores.');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al cargar los actores.');
        }
    });
}

function mostrarActores(actores) {
    var cuerpoTabla = $('#tablaActores tbody');
    cuerpoTabla.empty();

    $.each(actores, function(index, actor) {
        var fila = '<tr>' +
            '<td>' + actor.nombre + '</td>' +
            '<td>' + actor.apellido + '</td>' +
            '<td>' + actor.fecha_nacimiento + '</td>' +
            '<td>' + actor.nacionalidad + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-warning btn-editar" data-id="' + actor.id_actor + '">Editar</button>' +
            '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + actor.id_actor + '">Eliminar</button>' +
            '</td>' +
            '</tr>';
        cuerpoTabla.append(fila);
    });
}
