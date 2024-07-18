<?php
require_once("../html/layout.php");
?>

<div class="main-content">
    <div class="header">
        <h2>Películas y Actores</h2>
        <button id="nuevaRelacionBtn">Nueva Relación</button>
    </div>

    <div class="table-container">
        <div class="search-container">
            <div class="search-bar">
            <input type="text" id="buscarTituloPelicula" placeholder="Buscar por título de película...">
            <input type="text" id="buscarActor" placeholder="Buscar por nombre de actor...">
        </div>
        </div>

        <table id="tablaPeliculasActores">
            <thead>
                <tr>
                    <th>Título Película</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertarán los registros -->
            </tbody>
        </table>
    </div>
</div>

<div id="formularioRelacion" class="modal">
    <form id="relacionForm" class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h3>Nueva Relación Película - Actor</h3>

        <label for="titulo_pelicula">Título de la Película:</label>
        <input type="text" id="titulo_pelicula" name="titulo_pelicula" pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s\-.,'&:()!¡¿?]+" required>
        <ul id="titulo_pelicula_suggestions" class="suggestions-list"></ul>

        <label for="nombre_actor">Actor:</label>
        <input type="text" id="nombre_actor" name="nombre_actor" pattern="^[A-Za-zñÑáéíóúÁÉÍÓÚ\s\-]+$" required>
        <ul id="nombre_actor_suggestions" class="suggestions-list"></ul>

        <div class="button-container">
            <button type="submit">Guardar</button>
        </div>
    </form>
</div>



<script src="actores_peliculas.js"></script>
