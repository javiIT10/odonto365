<?php
/* header("Content-Type: application/json");

// Leer JSON enviado por fetch
$input = json_decode(file_get_contents("php://input"), true);
$especialistaId = $input['especialista_id'] ?? null;

// Si no llega especialista_id, devolver vacío
if (!$especialistaId) {
    echo json_encode([]);
    exit;
}

// Mock de eventos ocupados (los que antes estaban en agenda.php)
$eventos = [
    ['cita_inicio' => '2025-08-10 09:00', 'cita_fin' => '2025-08-10 11:00'],
    ['cita_inicio' => '2025-08-11 15:00', 'cita_fin' => '2025-08-11 16:00'],
    ['cita_inicio' => '2025-08-12 12:00', 'cita_fin' => '2025-08-12 16:00'],
    ['cita_inicio' => '2025-08-13 08:00', 'cita_fin' => '2025-08-13 10:00'],
    ['cita_inicio' => '2025-08-14 14:00', 'cita_fin' => '2025-08-14 17:00'],
    ['cita_inicio' => '2025-08-15 10:00', 'cita_fin' => '2025-08-15 12:00'],
    ['cita_inicio' => '2025-08-16 11:00', 'cita_fin' => '2025-08-16 14:00'],
    ['cita_inicio' => '2025-08-18 08:00', 'cita_fin' => '2025-08-18 09:00'],
    ['cita_inicio' => '2025-08-18 13:00', 'cita_fin' => '2025-08-18 15:00'],
    ['cita_inicio' => '2025-08-19 09:00', 'cita_fin' => '2025-08-19 12:00'],
    ['cita_inicio' => '2025-08-19 16:00', 'cita_fin' => '2025-08-19 17:00'],
    ['cita_inicio' => '2025-08-20 11:00', 'cita_fin' => '2025-08-20 13:00'],
    ['cita_inicio' => '2025-08-21 08:00', 'cita_fin' => '2025-08-21 10:00'],
    ['cita_inicio' => '2025-08-21 15:00', 'cita_fin' => '2025-08-21 17:00'],
    ['cita_inicio' => '2025-08-22 10:00', 'cita_fin' => '2025-08-22 14:00'],
    ['cita_inicio' => '2025-08-23 12:00', 'cita_fin' => '2025-08-23 16:00']
];

echo json_encode($eventos); */


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


