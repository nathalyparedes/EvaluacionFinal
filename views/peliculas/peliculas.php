<?php
require_once("../html/layout.php");
?>

<div class="main-content">
    <div class="header">
        <h2>Películas</h2>
        <button id="nuevaPeliculaBtn">Nueva Película</button>
    </div>

    <div class="table-container">
        <div class="search-bar">
            <input type="text" id="buscarTitulo" placeholder="Buscar por título...">
        </div>
    </div>

    <div class="table-container">
        <table id="tablaPeliculas">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Género</th>
                    <th>Año</th>
                    <th>Director</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertarán los registros -->
            </tbody>
        </table>
    </div>
</div>

<div id="formularioPelicula" class="modal">
    <form id="peliculaForm" class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h3>Película</h3>
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s\-.,'&:()!¡¿?]+" required>

        <label for="genero">Género:</label>
        <input type="text" id="genero" name="genero"  pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ,\s]+" title="Debe ingresar solo letras y espacios"  required>

        <label for="anio">Año:</label>
        <input type="number" id="anio" name="anio" title="Ingrese un año válido" required min="1895" max="">

        <label for="director">Director:</label>
        <input type="text" id="director" name="director" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Debe ingresar solo letras y espacios" required>

        <div class="button-container">
            <button type="submit">Guardar</button>
        </div>
    </form>
</div>



<script src="peliculas.js"></script>
