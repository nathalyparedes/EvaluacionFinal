<?php
require_once('../config/conexion.php');

class Clase_PeliculasActores
{
    public function todos()
{
    try {
        $con = new Clase_Conectar();
        $conexion = $con->Procedimiento_Conectar();
        
        $consulta = "SELECT pa.id_pelicula_actor, p.titulo AS titulo_pelicula, a.nombre, a.apellido 
                     FROM Peliculas_Actores pa
                     INNER JOIN Peliculas p ON pa.fk_id_pelicula = p.id_pelicula
                     INNER JOIN Actores a ON pa.fk_id_actor = a.id_actor";
        
        $stmt = $conexion->prepare($consulta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $peliculasActores = array();
        while ($fila = $resultado->fetch_assoc()) {
            $peliculasActores[] = $fila;
        }
        
        return $peliculasActores;
        } catch (Exception $e) {
            throw new RuntimeException("Error en la consulta listarTodos() de Peliculas_Actores: " . $e->getMessage(), 500);
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($conexion)) {
                $conexion->close();
            }
        }
}

    public function insertar($titulo_pelicula, $nombre_actor)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $nombre_apellido = explode(' ', $nombre_actor);
            $nombre_actor = $nombre_apellido[0];
            $apellido_actor = isset($nombre_apellido[1]) ? $nombre_apellido[1] : '';
            
            // Obtener el id_pelicula basado en el titulo_pelicula
            $consulta_pelicula = "SELECT id_pelicula FROM Peliculas WHERE titulo = ?";
            $stmt_pelicula = $conexion->prepare($consulta_pelicula);
            if (!$stmt_pelicula) {
                throw new Exception("Error en la preparación de la consulta de la película: " . $conexion->error);
            }
            $stmt_pelicula->bind_param("s", $titulo_pelicula);
            $stmt_pelicula->execute();
            $stmt_pelicula->bind_result($id_pelicula);
            
            if (!$stmt_pelicula->fetch()) {
                throw new Exception("La película '$titulo_pelicula' no fue encontrada.");
            }
            $stmt_pelicula->close();
            
            // Buscar el actor por nombre y apellido
            $consulta_actor = "SELECT id_actor FROM Actores WHERE nombre = ? AND apellido = ?";
            $stmt_actor = $conexion->prepare($consulta_actor);
            if (!$stmt_actor) {
                throw new Exception("Error en la preparación de la consulta del actor: " . $conexion->error);
            }
            $stmt_actor->bind_param("ss", $nombre_actor, $apellido_actor);
            $stmt_actor->execute();
            $stmt_actor->bind_result($id_actor);
            
            if (!$stmt_actor->fetch()) {
                throw new Exception("El actor '$nombre_actor $apellido_actor' no fue encontrado.");
            }
            $stmt_actor->close();
            
            // Insertar la relación peliculas-actores con los ids obtenidos
            $consulta = "INSERT INTO Peliculas_Actores (fk_id_pelicula, fk_id_actor) VALUES (?, ?)";
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de relación peliculas-actores: " . $conexion->error);
            }
            $stmt->bind_param("ii", $id_pelicula, $id_actor);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar relación peliculas-actores: " . $e->getMessage());
            return "Error al insertar la relación peliculas-actores: " . $e->getMessage();
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }


    public function actualizar($id_pelicula_actor, $titulo_pelicula, $nombre_actor)
{
    try {
        $con = new Clase_Conectar();
        $conexion = $con->Procedimiento_Conectar();
        
        // Obtener el id_pelicula basado en el titulo_pelicula
        $consulta_pelicula = "SELECT id_pelicula FROM Peliculas WHERE titulo = ?";
        $stmt_pelicula = $conexion->prepare($consulta_pelicula);
        if (!$stmt_pelicula) {
            throw new Exception("Error en la preparación de la consulta de la película: " . $conexion->error);
        }
        $stmt_pelicula->bind_param("s", $titulo_pelicula);
        $stmt_pelicula->execute();
        $stmt_pelicula->bind_result($id_pelicula);
        
        // Si la película no existe, manejar el error
        if (!$stmt_pelicula->fetch()) {
            throw new Exception("La película '$titulo_pelicula' no fue encontrada.");
        }
        $stmt_pelicula->close();
        
        // Obtener el id_actor basado en el nombre_actor
        $consulta_actor = "SELECT id_actor FROM Actores WHERE CONCAT(nombre, ' ', apellido) = ?";
        $stmt_actor = $conexion->prepare($consulta_actor);
        if (!$stmt_actor) {
            throw new Exception("Error en la preparación de la consulta del actor: " . $conexion->error);
        }
        $stmt_actor->bind_param("s", $nombre_actor);
        $stmt_actor->execute();
        $stmt_actor->bind_result($id_actor);
        
        // Si el actor no existe, manejar el error
        if (!$stmt_actor->fetch()) {
            throw new Exception("El actor '$nombre_actor' no fue encontrado.");
        }
        $stmt_actor->close();
        
        // Actualizar la relación peliculas-actores con los ids obtenidos
        $consulta = "UPDATE Peliculas_Actores SET fk_id_pelicula = ?, fk_id_actor = ? WHERE id_pelicula_actor = ?";
        $stmt = $conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de actualización de relación peliculas-actores: " . $conexion->error);
        }
        $stmt->bind_param("iii", $id_pelicula, $id_actor, $id_pelicula_actor);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        error_log("Error al actualizar relación peliculas-actores: " . $e->getMessage());
        return "Error al actualizar la relación peliculas-actores: " . $e->getMessage();
    } finally {
        if (isset($conexion)) {
            $conexion->close();
        }
    }
}


    public function eliminar($id_pelicula_actor)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "DELETE FROM Peliculas_Actores WHERE id_pelicula_actor = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id_pelicula_actor);
            
            if ($stmt->execute()) {
                $stmt->close();
                $conexion->close();
                return "ok"; // Devuelve "ok" cuando la eliminación es exitosa
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar relación peliculas-actores: " . $e->getMessage());
            return "Error al eliminar la relación peliculas-actores: " . $e->getMessage();
        }
    }

    public function buscarPorId($id_pelicula_actor)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT pa.*, p.titulo AS titulo_pelicula, a.nombre, a.apellido 
                         FROM Peliculas_Actores pa
                         INNER JOIN Peliculas p ON pa.fk_id_pelicula = p.id_pelicula
                         INNER JOIN Actores a ON pa.fk_id_actor = a.id_actor
                         WHERE pa.id_pelicula_actor = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id_pelicula_actor);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows === 1) {
                    $pelicula_actor = $resultado->fetch_assoc();
                    return $pelicula_actor;
                } else {
                    throw new Exception("No se encontró la relación peliculas-actores.");
                }
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar relación peliculas-actores por ID: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorPelicula($titulo_pelicula)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT pa.*, p.titulo AS titulo_pelicula, a.nombre, a.apellido 
                         FROM Peliculas_Actores pa
                         INNER JOIN Peliculas p ON pa.fk_id_pelicula = p.id_pelicula
                         INNER JOIN Actores a ON pa.fk_id_actor = a.id_actor
                         WHERE p.titulo LIKE ?";
            $stmt = $conexion->prepare($consulta);
            $titulo_pelicula = '%' . $titulo_pelicula . '%';
            $stmt->bind_param("s", $titulo_pelicula);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $peliculasActores = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $peliculasActores[] = $fila;
                }
                return $peliculasActores;
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar relación peliculas-actores por película: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorActor($nombre_actor, $apellido_actor)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT pa.*, p.titulo AS titulo_pelicula, a.nombre, a.apellido 
                         FROM Peliculas_Actores pa
                         INNER JOIN Peliculas p ON pa.fk_id_pelicula = p.id_pelicula
                         INNER JOIN Actores a ON pa.fk_id_actor = a.id_actor
                         WHERE a.nombre = ? AND a.apellido = ?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("ss", $nombre_actor, $apellido_actor);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $peliculasActores = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $peliculasActores[] = $fila;
                }
                return $peliculasActores;
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar relación peliculas-actores por actor: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }
    
    public function buscarNombre($nombre_actor)
{
    try {
        $con = new Clase_Conectar();
        $conexion = $con->Procedimiento_Conectar();

        $consulta = "SELECT nombre, apellido FROM Actores WHERE nombre LIKE ?";
        $stmt = $conexion->prepare($consulta);

        $nombre_param = "%" . $nombre_actor . "%";
        $stmt->bind_param("s", $nombre_param);

        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            $actores = $resultado->fetch_all(MYSQLI_ASSOC);
            return $actores;
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        error_log("Error al buscar actor: " . $e->getMessage());
        return false;
    } finally {
        if (isset($conexion)) {
            $conexion->close();
        }
    }
}
    
}