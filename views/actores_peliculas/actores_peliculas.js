$('#titulo_pelicula').keyup(function() {
    var titulo = $(this).val();
    if (titulo.length === 0) {
        $('#titulo_pelicula_suggestions').hide(); // Hide the list when input is empty
        return; // Exit the function early
    }
    $.ajax({
        url: '../../controllers/peliculas.controller.php',
        type: 'POST',
        data: JSON.stringify({ op: 'buscarPorTitulo', titulo: titulo }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            // Optional: show a loading indicator or disable the input field
        },
        success: function(response) {
            if (response) {
                $('#titulo_pelicula_suggestions').empty();
                $.each(response, function(index, value) {
                    $('<li>').text(value.titulo).appendTo('#titulo_pelicula_suggestions');
                });
                $('#titulo_pelicula_suggestions').show();

                // Agregamos el evento click a los elementos <li>
                $('#titulo_pelicula_suggestions li').click(function() {
                    var selectedTitle = $(this).text();
                    $('#titulo_pelicula').val(selectedTitle);
                    $('#titulo_pelicula_suggestions').hide();
                });
            } else {
                console.error('Error:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

$('#nombre_actor').keyup(function() {
    var nombre = $(this).val();
    if (nombre.length === 0) {
        $('#nombre_actor_suggestions').hide(); // Hide the list when input is empty
        return; // Exit the function early
    }
    $.ajax({
        url: '../../controllers/actores_peliculas.controller.php',
        type: 'POST',
        data: JSON.stringify({ op: 'buscarPorNombre', nombre_actor: nombre }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            // Optional: show a loading indicator or disable the input field
        },
        success: function(response) {
            if (response.status === 'error') {
                console.error('Error:', response.message);
            } else {
                $('#nombre_actor_suggestions').empty();
                $.each(response, function(index, value) {
                    $('<li>').text(value.nombre + ' ' + value.apellido).appendTo('#nombre_actor_suggestions');
                });
                $('#nombre_actor_suggestions').show();

                // Agregamos el evento click a los elementos <li>
                $('#nombre_actor_suggestions li').click(function() {
                    var selectedName = $(this).text();
                    $('#nombre_actor').val(selectedName);
                    $('#nombre_actor_suggestions').hide();
                });
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

$(document).ready(function() {
    const nuevaRelacionBtn = $('#nuevaRelacionBtn');
    const formularioRelacion = $('#formularioRelacion');
    const closeModalBtn = $('.close');
    let editingId = null; // Variable para almacenar el ID de la relación en edición
    let submitting = false; // Variable para controlar el estado de envío del formulario

    function abrirModal() {
        formularioRelacion.css('display', 'block');
    }

    function cerrarModal() {
        formularioRelacion.css('display', 'none');
        editingId = null;
        $('#relacionForm')[0].reset();
    }

    nuevaRelacionBtn.click(abrirModal);
    closeModalBtn.click(cerrarModal);

    $(window).click(function(event) {
        if (event.target == formularioRelacion[0]) {
            cerrarModal();
        }
    });

    $('#buscarTituloPelicula').on('input', function(event) {
        const searchTerm = event.target.value.toLowerCase();
        const rows = $('#tablaPeliculasActores tbody tr');
    
        if (searchTerm === '') {
            rows.show(); 
        } else {
            rows.hide();
            rows.filter(function() {
                const titulo = $(this).find('td:nth-child(1)').text().trim().toLowerCase();
                return titulo.includes(searchTerm);
            }).show(); 
        }
    });
    cargarPeliculasActores();

    $('#buscarActor').on('input', function(event) {
        const searchTerm = event.target.value.toLowerCase();
        const rows = $('#tablaPeliculasActores tbody tr');
    
        if (searchTerm === '') {
            rows.show(); 
        } else {
            rows.hide(); 
            rows.filter(function() {
                const nombreActor = $(this).find('td:nth-child(2)').text().trim().toLowerCase();
                const apellidoActor = $(this).find('td:nth-child(3)').text().trim().toLowerCase();
                const fullName = nombreActor + ' ' + apellidoActor;
                return fullName.includes(searchTerm);
            }).show(); 
        }
    });

    // Función para editar una relación película-actor
    $('#tablaPeliculasActores').on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        editingId = id; 
        const row = $(this).closest('tr');
        const titulo_pelicula = row.find('td:nth-child(1)').text();
        const nombre_actor = row.find('td:nth-child(2)').text();
        const apellido_actor = row.find('td:nth-child(3)').text();
    
        const nombre_completo_actor = `${nombre_actor} ${apellido_actor}`;
    
        $('#titulo_pelicula').val(titulo_pelicula);
        $('#nombre_actor').val(nombre_completo_actor);
    
        abrirModal(); 
    });
    
    

    // Función para eliminar una relación película-actor
    $('#tablaPeliculasActores').on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Está seguro que desea eliminar esta relación película-actor?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarRelacion(id);
            }
        });
    });

    // Función para enviar la solicitud de eliminación al servidor
    function eliminarRelacion(id) {
        $.ajax({
            url: '../../controllers/actores_peliculas.controller.php',
            type: 'POST',
            data: JSON.stringify({ op: 'eliminar', id_actor_pelicula: id }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response.status === "ok") {
                    toastr.success('Relación película-actor eliminada correctamente');
                    cargarPeliculasActores(); 
                } else {
                    toastr.error('Error al eliminar relación película-actor');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error('Error de conexión al servidor. Inténtelo de nuevo más tarde.');
            }
        });
    }

    $('#relacionForm').submit(function(event) {
        event.preventDefault();
    
        if (submitting) {
            return; // Evitar envío múltiple si ya se está procesando una solicitud
        }
        
        submitting = true;
    
        var titulo_pelicula = $('#titulo_pelicula').val();
        var nombre_actor = $('#nombre_actor').val();
    
        var data = {
            op: 'insertar',
            titulo_pelicula: titulo_pelicula,
            nombre_actor: nombre_actor
        };
    
        if (editingId) {
            data.op = 'actualizar';
            data.id_relacion = editingId;
        }
    
        $.ajax({
            url: '../../controllers/actores_peliculas.controller.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                submitting = false;
    
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        toastr.error('Respuesta inválida del servidor.');
                        return;
                    }
                }
    
                if (response && response.status === 'error' && response.message) {
                    toastr.error(response.message);
                } else {
                    toastr.success('Relación guardada correctamente');
                    cerrarModal();
                    cargarPeliculasActores(); 
                }
            },
            error: function(xhr, status, error) {
                submitting = false;
                console.error(error);
                toastr.error('Error de conexión al servidor. Inténtelo de nuevo más tarde.');
            }
        });
    });

    function cargarPeliculasActores() {
        $.ajax({
            url: '../../controllers/actores_peliculas.controller.php',
            type: 'POST',
            data: JSON.stringify({ op: 'todos' }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response !== "No se encontraron relaciones película-actor.") {
                    mostrarPeliculasActores(response);
                } else {
                    toastr.warning('No se encontraron relaciones película-actor.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.warning('Error al cargar las relaciones película-actor.');
            }
        });
    }

    function mostrarPeliculasActores(peliculasActores) {
        const tablaPeliculasActores = $('#tablaPeliculasActores tbody');
        tablaPeliculasActores.empty();
    
        if ($.isArray(peliculasActores) && peliculasActores.length > 0) {
            $.each(peliculasActores, function(index, peliculaActor) {
                const fila = '<tr data-id="' + peliculaActor.id_actor_pelicula + '">' +
                    '<td>' + peliculaActor.titulo_pelicula + '</td>' +
                    '<td>' + peliculaActor.nombre + '</td>' +
                    '<td>' + peliculaActor.apellido + '</td>' +
                    '<td>' +
                    '<button class="btn btn-sm btn-warning btn-editar" data-id="' + peliculaActor.id_pelicula_actor + '">Editar</button>' +
                    '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + peliculaActor.id_pelicula_actor + '">Eliminar</button>' +
                    '</td>' +
                    '</tr>';
                tablaPeliculasActores.append(fila);
            });
        } else {
            const fila = '<tr><td colspan="5" class="text-center">No se encontraron relaciones película-actor.</td></tr>';
            tablaPeliculasActores.append(fila);
        }
    }
});