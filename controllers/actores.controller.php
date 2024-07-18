<?php
require_once('../models/actores.model.php');
require_once('../config/cors.php');

$actor = new Clase_Actores();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['op'])) {
        switch ($data['op']) {
            case "todos":
                $datos = $actor->todos();
                if ($datos !== false) {
                    echo json_encode($datos);
                } else {
                    echo json_encode(array("error" => "No se encontraron actores."));
                }
                break;
            case "insertar":
                if (isset($data["nombre"], $data["apellido"], $data["fecha_nacimiento"], $data["nacionalidad"])) {
                    $nombre = $data["nombre"];
                    $apellido = $data["apellido"];
                    $fecha_nacimiento = $data["fecha_nacimiento"];
                    $nacionalidad = $data["nacionalidad"];
                    $resultado = $actor->insertar($nombre, $apellido, $fecha_nacimiento, $nacionalidad);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al insertar el actor: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Faltan parámetros para insertar el actor."));
                }
                break;
            case "actualizar":
                if (isset($data["id_actor"], $data["nombre"], $data["apellido"], $data["fecha_nacimiento"], $data["nacionalidad"])) {
                    $id_actor = $data["id_actor"];
                    $nombre = $data["nombre"];
                    $apellido = $data["apellido"];
                    $fecha_nacimiento = $data["fecha_nacimiento"];
                    $nacionalidad = $data["nacionalidad"];
                    $resultado = $actor->actualizar($id_actor, $nombre, $apellido, $fecha_nacimiento, $nacionalidad);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al actualizar el actor: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Faltan parámetros para actualizar el actor."));
                }
                break;
            case "eliminar":
                if (isset($data["id_actor"])) {
                    $id_actor = $data["id_actor"];
                    $resultado = $actor->eliminar($id_actor);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al eliminar el actor: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro 'id_actor' para eliminar el actor."));
                }
                break;
            case "detalle":
                if (isset($data["id_actor"])) {
                    $id_actor = $data["id_actor"];
                    try {
                        $actorDetalle = $actor->buscarPorId($id_actor);
                        if ($actorDetalle) {
                            echo json_encode($actorDetalle);
                        } else {
                            echo json_encode(array("resultado" => "error", "error" => "No se encontró el actor."));
                        }
                    } catch (Exception $e) {
                        error_log("Error al obtener el detalle del actor: " . $e->getMessage());
                        echo json_encode(array("resultado" => "error", "error" => "Error al obtener el detalle del actor."));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro ID para obtener el detalle del actor."));
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

