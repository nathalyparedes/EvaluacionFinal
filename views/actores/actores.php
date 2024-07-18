<?php
require_once("../html/layout.php");
?>


<div class="main-content">
    <div class="header">
        <h2>Actores</h2>
        <button id="nuevoActorBtn">Nuevo Actor</button>
    </div>

    <div class="table-container">
        <div class="search-bar">
            <input type="text" id="buscarNombre" placeholder="Buscar por nombre...">
        </div>
    </div>

    <div class="table-container">
        <table id="tablaActores">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Nacionalidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertarán los registros con JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<div id="formularioActor" class="modal">
    <form id="actorForm" class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h3>Actor</h3>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Debe ingresar solo letras y espacios" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Debe ingresar solo letras y espacios" required>

        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fechaNacimiento" name="fechaNacimiento" required>

        <label for="nacionalidad">Nacionalidad:</label>
        <input type="text" id="nacionalidad" name="nacionalidad" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Debe ingresar solo letras y espacios" required>

        <div class="button-container">
            <button type="submit">Guardar</button>
        </div>
    </form>
</div>

<script src="actores.js"></script>
