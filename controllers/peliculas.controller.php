<?php
require_once('../models/peliculas.model.php');
require_once('../config/cors.php');

$pelicula = new Clase_Peliculas();
header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos el cuerpo de la solicitud POST
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['op'])) {
        switch ($data['op']) {
            case "todos":
                $datos = $pelicula->todos();
                if ($datos !== false) {
                    echo json_encode($datos);
                } else {
                    echo json_encode(array("error" => "No se encontraron películas."));
                }
                break;
            case "insertar":
                if (isset($data["titulo"], $data["genero"], $data["anio"], $data["director"])) {
                    $titulo = $data["titulo"];
                    $genero = $data["genero"];
                    $anio = $data["anio"];
                    $director = $data["director"];
                    $resultado = $pelicula->insertar($titulo, $genero, $anio, $director);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al insertar la película: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Faltan parámetros para insertar la película."));
                }
                break;
            case "actualizar":
                if (isset($data["id_pelicula"], $data["titulo"], $data["genero"], $data["anio"], $data["director"])) {
                    $id_pelicula = $data["id_pelicula"];
                    $titulo = $data["titulo"];
                    $genero = $data["genero"];
                    $anio = $data["anio"];
                    $director = $data["director"];
                    $resultado = $pelicula->actualizar($id_pelicula, $titulo, $genero, $anio, $director);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al actualizar la película: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Faltan parámetros para actualizar la película."));
                }
                break;
            case "eliminar":
                if (isset($data["id_pelicula"])) {
                    $id_pelicula = $data["id_pelicula"];
                    $resultado = $pelicula->eliminar($id_pelicula);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al eliminar la película: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro 'id_pelicula' para eliminar la película."));
                }
                break;
            case "detalle":
                if (isset($data["id_pelicula"])) {
                    $id_pelicula = $data["id_pelicula"];
                    try {
                        $peliculaDetalle = $pelicula->buscarPorId($id_pelicula);
                        if ($peliculaDetalle) {
                            echo json_encode($peliculaDetalle);
                        } else {
                            echo json_encode(array("resultado" => "error", "error" => "No se encontró la película."));
                        }
                    } catch (Exception $e) {
                        error_log("Error al obtener el detalle de la película: " . $e->getMessage());
                        echo json_encode(array("resultado" => "error", "error" => "Error al obtener el detalle de la película."));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro ID para obtener el detalle de la película."));
                }
                break;
            case "buscarPorTitulo":
                if (isset($data["titulo"])) {
                    $titulo = $data["titulo"];
                    $peliculasEncontradas = $pelicula->buscarPorTitulo($titulo);
                    if ($peliculasEncontradas !== false) {
                        echo json_encode($peliculasEncontradas);
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al buscar películas por título."));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro 'titulo' para buscar películas por título."));
                }
                break;
            default:
                echo json_encode(array("resultado" => "error", "error" => "Operación no válida."));
                break;
        }
    } else {
        echo json_encode(array("resultado" => "error", "error" => "No se especificó la operación."));
    }
} else {
    echo json_encode(array("resultado" => "error", "error" => "Método no permitido."));
}

