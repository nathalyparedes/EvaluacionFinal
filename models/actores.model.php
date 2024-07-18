<?php
require_once('../config/conexion.php');

class Clase_Actores
{
    public function todos()
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "SELECT * FROM Actores";
            $resultado = mysqli_query($conexion, $consulta);
            
            if ($resultado === false) {
                throw new Exception(mysqli_error($conexion));
            }
            
            $actores = array();
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $actores[] = $fila;
            }
            
            return $actores;
        } catch (Exception $e) {
            error_log("Error en la consulta todos(): " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function insertar($nombre, $apellido, $fecha_nacimiento, $nacionalidad)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "INSERT INTO Actores (nombre, apellido, fecha_nacimiento, nacionalidad) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("ssss", $nombre, $apellido, $fecha_nacimiento, $nacionalidad);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar actor: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function actualizar($id_actor, $nombre, $apellido, $fecha_nacimiento, $nacionalidad)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "UPDATE Actores SET nombre=?, apellido=?, fecha_nacimiento=?, nacionalidad=? WHERE id_actor=?";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("ssssi", $nombre, $apellido, $fecha_nacimiento, $nacionalidad, $id_actor);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al actualizar actor: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function eliminar($id_actor)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "DELETE FROM Actores WHERE id_actor=?";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("i", $id_actor);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar actor: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorId($id_actor)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT * FROM Actores WHERE id_actor=?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id_actor);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows === 1) {
                    $actor = $resultado->fetch_assoc();
                    return $actor;
                } else {
                    throw new Exception("No se encontró el actor.");
                }
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar actor por ID: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }
}
