<?php
header("Content-Type: application/json");
require_once "../modelos/agenda.modelo.php";

$input = json_decode(file_get_contents("php://input"), true);
$codigo = $input['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(["error" => "No se recibió código"]);
    exit;
}

$existe = AgendaModelo::existeCodigoCita($codigo);

echo json_encode(["existe" => $existe]);

