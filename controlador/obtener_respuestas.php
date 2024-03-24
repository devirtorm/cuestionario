<?php
include '../controlador/controlador.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_encuesta'])) {

    $idEncuesta = $_POST['id_encuesta'];

    // Llama a la función y pasa el id_encuesta
    $preguntas = Datos::obtener_pregunta_modelo($idEncuesta);

    // Devuelve las preguntas en formato JSON
    header('Content-Type: application/json');
    echo json_encode($preguntas);    
} else {
    // Manejar error: método no permitido o falta el id_encuesta
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Solicitud incorrecta.']);
}
