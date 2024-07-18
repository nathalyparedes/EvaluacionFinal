<?php
require_once('../config/conexion.php');

class Clase_Peliculas
{
    public function todos()
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "SELECT * FROM Peliculas";
            $resultado = mysqli_query($conexion, $consulta);
            
            if ($resultado === false) {
                throw new Exception(mysqli_error($conexion));
            }
            
            $peliculas = array();
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $peliculas[] = $fila;
            }
            
            return $peliculas;
        } catch (Exception $e) {
            error_log("Error en la consulta todos(): " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function insertar($titulo, $genero, $anio, $director)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "INSERT INTO Peliculas (titulo, genero, anio, director) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("ssis", $titulo, $genero, $anio, $director);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar película: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function actualizar($id_pelicula, $titulo, $genero, $anio, $director)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "UPDATE Peliculas SET titulo=?, genero=?, anio=?, director=? WHERE id_pelicula=?";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("ssisi", $titulo, $genero, $anio, $director, $id_pelicula);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al actualizar película: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function eliminar($id_pelicula)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "DELETE FROM Peliculas WHERE id_pelicula=?";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("i", $id_pelicula);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar película: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorId($id_pelicula)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT * FROM Peliculas WHERE id_pelicula=?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $id_pelicula);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows === 1) {
                    $pelicula = $resultado->fetch_assoc();
                    return $pelicula;
                } else {
                    throw new Exception("No se encontró la película.");
                }
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar película por ID: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorTitulo($titulo)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT * FROM Peliculas WHERE titulo LIKE ?";
            $stmt = $conexion->prepare($consulta);
            $tituloBusqueda = "%" . $titulo . "%";
            $stmt->bind_param("s", $tituloBusqueda);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $peliculas = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $peliculas[] = $fila;
                }
                return $peliculas;
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar películas por título: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }
}

