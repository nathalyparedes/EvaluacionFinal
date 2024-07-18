<?php
require_once('../models/actores_peliculas.model.php');
require_once('../config/cors.php');

$actores_peliculas = new Clase_PeliculasActores();
header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['op'])) {
        switch ($data['op']) {
            case "todos":
                    $datos = $actores_peliculas->todos();
                    if (isset($datos['error']) && $datos['error']) {
                        echo json_encode(["status" => "error", "message" => $datos['message']]);
                    } else {
                        echo json_encode($datos);
                    }
                    break;
                    case 'insertar':
                        if (isset($data['titulo_pelicula'], $data['nombre_actor'])) {
                            $titulo_pelicula = $data['titulo_pelicula'];
                            $nombre_actor = $data['nombre_actor'];
                            
                            try {
                                $resultado = $actores_peliculas->insertar($titulo_pelicula, $nombre_actor);
                                if ($resultado === "ok") {
                                    echo json_encode(["status" => "ok"]);
                                } else {
                                    throw new Exception($resultado);
                                }
                            } catch (Exception $e) {
                                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                            }
                        } else {
                            echo json_encode(["status" => "error", "message" => "Faltan parámetros para insertar la relación actores-películas."]);
                        }
                        break;
                    
            case 'actualizar':
                if (isset($data['id_relacion'], $data['titulo_pelicula'], $data['nombre_actor'])) {
                                $id_relacion = $data['id_relacion'];
                                $titulo_pelicula = $data['titulo_pelicula'];
                                $nombre_actor = $data['nombre_actor'];
                        
                                try {
                                    $resultado = $actores_peliculas->actualizar($id_relacion, $titulo_pelicula, $nombre_actor);
                                    if ($resultado === "ok") {
                                        echo json_encode(["status" => "ok"]);
                                    } else {
                                        throw new Exception($resultado);
                                    }
                                } catch (Exception $e) {
                                    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                                }
                            } else {
                                echo json_encode(["status" => "error", "message" => "Faltan parámetros para actualizar la relación actores-películas."]);
                            }
                            break;
                        

            case 'eliminar':
                if (isset($data["id_actor_pelicula"])) {
                    $id_actor_pelicula = $data["id_actor_pelicula"];
                    $resultado = $actores_peliculas->eliminar($id_actor_pelicula);
                    if ($resultado === "ok") {
                        echo json_encode(["status" => "ok"]);
                    } else {
                        echo json_encode(["status" => "error", "message" => $resultado]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro ID para eliminar la relación actores-películas."]);
                }
                break;

            case "buscarPorPelicula":
                if (isset($data['titulo_pelicula'])) {
                    $titulo_pelicula = $data['titulo_pelicula'];
                    $actores_peliculas_encontrados = $actores_peliculas->buscarPorPelicula($titulo_pelicula);
                    if ($actores_peliculas_encontrados !== false) {
                        echo json_encode($actores_peliculas_encontrados);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Error al buscar relaciones actores-películas por título de película."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro 'titulo_pelicula' para buscar relaciones actores-películas por título de película."]);
                }
                break;

            case "buscarPorActor":
                if (isset($data['nombre_actor'], $data['apellido_actor'])) {
                    $nombre_actor = $data['nombre_actor'];
                    $apellido_actor = $data['apellido_actor'];
                    $actores_peliculas_encontrados = $actores_peliculas->buscarPorActor($nombre_actor, $apellido_actor);
                    if ($actores_peliculas_encontrados !== false) {
                        echo json_encode($actores_peliculas_encontrados);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Error al buscar relaciones actores-películas por nombre de actor."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Faltan parámetros 'nombre_actor' y 'apellido_actor' para buscar relaciones actores-películas por nombre de actor."]);
                }
                break;

                case "buscarPorNombre":
                    if (isset($data['nombre_actor'])) {
                        $nombre_actor = $data['nombre_actor'];
                        $actores_peliculas_encontrados = $actores_peliculas->buscarNombre($nombre_actor);
                        if ($actores_peliculas_encontrados !== false) {
                            echo json_encode($actores_peliculas_encontrados);
                        } else {
                            echo json_encode(["status" => "error", "message" => "Error al buscar relaciones actores-películas por nombre de actor. Por favor, inténtelo de nuevo."]);
                        }
                    } else {
                        echo json_encode(["status" => "error", "message" => "Falta parámetro 'nombre_actor' para buscar relaciones actores-películas por nombre de actor."]);
                    }
                    break;

            case 'detalle':
                if (isset($data['id_actor_pelicula'])) {
                    $id_actor_pelicula = $data['id_actor_pelicula'];
                    $resultado = $actores_peliculas->buscarPorId($id_actor_pelicula);
                    if ($resultado) {
                        echo json_encode([
                            'id_actor_pelicula' => $resultado['id_actor_pelicula'],
                            'titulo_pelicula' => $resultado['titulo_pelicula'],
                            'nombre_actor' => $resultado['nombre_actor'],
                            'apellido_actor' => $resultado['apellido_actor']
                        ]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "No se encontró la relación actor-película con id $id_actor_pelicula"]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro id_actor_pelicula"]);
                }
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Operación no válida."]);
                break;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se especificó la operación."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

