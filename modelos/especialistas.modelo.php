<?php
require_once "conexion.php";

Class ModeloEspecialistas
{
    /* =============== MOSTRAR ESPECIALISTAS =============== */
    static public function mdlMostrarEspecialistas($rutaEspecialidad)
    {
        try {
            $db = Conexion::conectar();

            $sql = "SELECT 
                        s.id AS id,
                        s.nombre AS nombre,
                        e.especialidad_nombre AS especialidad,
                        s.imagen AS foto,
                        s.descripcion AS descripcion,
                        s.certificaciones AS certificaciones
                    FROM especialidades e
                    INNER JOIN especialistas s 
                        ON s.id_especialidad = e.especialidad_id
                    WHERE e.ruta = :ruta";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(":ruta", $rutaEspecialidad, PDO::PARAM_STR);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Convertir 'certificaciones' en array
            foreach ($resultados as &$fila) {
                $fila['certificaciones'] = array_map('trim', explode('|', $fila['certificaciones']));
            }

            return $resultados;

        } catch (PDOException $e) {
            error_log("Error en mdlMostrarEspecialistas: " . $e->getMessage());
            return [];
        }
    }


}