<?php
header("Content-Type: application/json");
require_once "../modelos/agenda.modelo.php";

$input = json_decode(file_get_contents("php://input"), true);
$especialistaId = $input['especialista_id'] ?? null;

if (!$especialistaId) {
    echo json_encode(["error" => "No se recibió especialista_id"]);
    exit;
}

$eventos = AgendaModelo::obtenerEventosPorEspecialista($especialistaId);
echo json_encode($eventos);


