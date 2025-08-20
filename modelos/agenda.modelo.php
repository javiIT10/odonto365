<?php
require_once "conexion.php"; // tu clase de conexión PDO

class AgendaModelo {

  public static function obtenerEventosPorEspecialista($especialistaId) {
    $db = Conexion::conectar();
    $sql = "SELECT cita_inicio, cita_fin, cita_codigo
            FROM agenda
            WHERE id_especialista = :id_especialista
              AND cita_status != 'cancelada'";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_especialista", $especialistaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function registrarCita($d) {
    $db = Conexion::conectar();
    $sql = "INSERT INTO agenda
            (id_especialidad, id_especialista, id_usuario, cita_pago, cita_status, cita_codigo, cita_inicio, cita_fin, fecha_creacion)
            VALUES (:id_especialidad, :id_especialista, :id_usuario, :cita_pago, :cita_status, :cita_codigo, :cita_inicio, :cita_fin, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_especialidad", $d['id_especialidad']);   // si no aplica, ajusta el INSERT y estos binds
    $stmt->bindParam(":id_especialista", $d['id_especialista']);
    $stmt->bindParam(":id_usuario", $d['id_usuario']);
    $stmt->bindParam(":cita_pago", $d['cita_pago']);
    $stmt->bindParam(":cita_status", $d['cita_status']);
    $stmt->bindParam(":cita_codigo", $d['cita_codigo']);
    $stmt->bindParam(":cita_inicio", $d['cita_inicio']);
    $stmt->bindParam(":cita_fin", $d['cita_fin']);
    return $stmt->execute();
  }

  public static function existeCodigoCita($codigo) {
    $stmt = Conexion::conectar()->prepare("
        SELECT COUNT(*) FROM agenda WHERE cita_codigo = :codigo
    ");
    $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
  }
}

