<?php
require_once "conexion.php";

class AgendaModelo {
    static public function obtenerEventosPorEspecialista($especialistaId) {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT cita_inicio, cita_fin FROM agenda WHERE id_especialista = :id");
        $stmt->bindParam(":id", $especialistaId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

