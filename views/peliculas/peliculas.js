$(document).ready(function() {
    const nuevaPeliculaBtn = $('#nuevaPeliculaBtn');
    const formularioPelicula = $('#formularioPelicula');
    const closeModalBtn = $('.close');
    let editingId = null; // Variable para almacenar el ID de la película en edición
    let submitting = false; // Variable para controlar el estado de envío del formulario

    function abrirModal() {
        formularioPelicula.css('display', 'block');
    }

    function cerrarModal() {
        formularioPelicula.css('display', 'none');
        editingId = null; 
        $('#peliculaForm')[0].reset(); 
    }

    nuevaPeliculaBtn.click(abrirModal);
    closeModalBtn.click(cerrarModal);

    $(window).click(function(event) {
        if (event.target == formularioPelicula[0]) {
            cerrarModal();
        }
    });

    $('#buscarTitulo').on('input', function(event) {
        const searchTerm = event.target.value.toLowerCase();
        const rows = $('#tablaPeliculas tbody tr');

        rows.each(function() {
            const title = $(this).find('td:nth-child(2)').text().toLowerCase();
            $(this).toggle(title.includes(searchTerm));
        });
    });

    cargarPeliculas();

    // Función para editar una película
    $('#tablaPeliculas').on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        editingId = id; // Guardar el ID de la película en edición
        const row = $(this).closest('tr');
        const titulo = row.find('td:nth-child(1)').text();
        const genero = row.find('td:nth-child(2)').text();
        const anio = row.find('td:nth-child(3)').text();
        const director = row.find('td:nth-child(4)').text();

        // Llenar el formulario con los datos de la película a editar
        $('#titulo').val(titulo);
        $('#genero').val(genero);
        $('#anio').val(anio);
        $('#director').val(director);

        abrirModal(); // Abrir el modal para edición
    });

    // Función para eliminar una película
    $('#tablaPeliculas').on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Está seguro que desea eliminar esta película?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarPelicula(id);
            }
        });
    });

    // Función para enviar la solicitud de eliminación al servidor
    function eliminarPelicula(id) {
        $.ajax({
            url: '../../controllers/peliculas.controller.php',
            type: 'POST',
            data: JSON.stringify({ op: 'eliminar', id_pelicula: id }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response.resultado === "ok") {
                    toastr.success('Película eliminada correctamente');
                    cargarPeliculas(); 
                } else {
                    toastr.error('Error al eliminar película');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error('Error de conexión al servidor. Inténtelo de nuevo más tarde.');
            }
        });
    }

    $('#peliculaForm').submit(function(event) {
        event.preventDefault();

        if (submitting) {
            return; // Evitar envío múltiple si ya se está procesando una solicitud
        }
        
        submitting = true;

        var titulo = $('#titulo').val();
        var genero = $('#genero').val();
        var anio = $('#anio').val();
        var director = $('#director').val();

        var data = {
            titulo: titulo,
            genero: genero,
            anio: anio,
            director: director
        };

        if (editingId) {
            data.op = 'actualizar';
            data.id_pelicula = editingId;
        } else {
            data.op = 'insertar';
        }

        $.ajax({
            url: '../../controllers/peliculas.controller.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response && response.resultado === "ok") {
                    toastr.success('Operación exitosa');
                    cerrarModal(); // Función para cerrar el modal
                    cargarPeliculas(); // Volver a cargar la tabla después de insertar/actualizar
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

function cargarPeliculas() {
    $.ajax({
        url: '../../controllers/peliculas.controller.php',
        type: 'POST',
        data: JSON.stringify({ op: 'todos' }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response !== "No se encontraron películas.") {
                mostrarPeliculas(response);
            } else {
                toastr.warning('No se encontraron películas.');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al cargar las películas.');
        }
    });
}

function mostrarPeliculas(peliculas) {
    var cuerpoTabla = $('#tablaPeliculas tbody');
    cuerpoTabla.empty();

    $.each(peliculas, function(index, pelicula) {
        var fila = '<tr>' +
            '<td>' + pelicula.titulo + '</td>' +
            '<td>' + pelicula.genero + '</td>' +
            '<td>' + pelicula.anio + '</td>' +
            '<td>' + pelicula.director + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-warning btn-editar" data-id="' + pelicula.id_pelicula + '">Editar</button>' +
            '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + pelicula.id_pelicula + '">Eliminar</button>' +
            '</td>' +
            '</tr>';
        cuerpoTabla.append(fila);
    });
}

var currentYear = new Date().getFullYear();
    
    document.getElementById('anio').max = currentYear;

    document.getElementById('#formularioPelicula').addEventListener('submit', function(event) {
        var inputYear = document.getElementById('anio').value;
        
        var year = parseInt(inputYear, 10);

        if (year > currentYear) {
            alert("No se puede ingresar una película en un año que aún no ha llegado.");
            event.preventDefault(); 
        }
    });
