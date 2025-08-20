<?php
header("Content-Type: application/json");
require_once "../modelos/agenda.modelo.php";

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
  echo json_encode(["success" => false, "message" => "No se recibieron datos"]);
  exit;
}

$datos = [
  "id_especialidad" => $input['id_especialidad'] ?? null, // si aplica
  "id_especialista" => $input['id_especialista'] ?? null,
  "id_usuario"      => $input['id_usuario'] ?? null,
  "cita_pago"       => $input['cita_pago'] ?? "pendiente",
  "cita_status"     => "pendiente",
  "cita_codigo"     => $input['cita_codigo'] ?? null,
  "cita_inicio"     => $input['cita_inicio'] ?? null,
  "cita_fin"        => $input['cita_fin'] ?? null
];

foreach (["id_especialista","id_usuario","cita_codigo","cita_inicio","cita_fin"] as $campo) {
  if (empty($datos[$campo])) {
    echo json_encode(["success" => false, "message" => "Falta el campo $campo"]);
    exit;
  }
}

$ok = AgendaModelo::registrarCita($datos);

echo json_encode([
  "success" => $ok,
  "message" => $ok ? "Cita preagendada correctamente" : "Error al registrar la cita"
]);

