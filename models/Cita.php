<?php

namespace Model;

class Cita extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioId'];

    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioId = $args['usuarioId'] ?? '';
    }

    public static function obtenerProximaCita($usuarioId) {
        $query = "SELECT citas.fecha, citas.hora, servicios.nombre 
                  FROM citas 
                  INNER JOIN citasservicios ON citas.id = citasservicios.citaid 
                  INNER JOIN servicios ON citasservicios.servicioid = servicios.id 
                  WHERE citas.usuarioid = ? AND citas.fecha >= CURDATE() 
                  ORDER BY citas.fecha, citas.hora 
                  LIMIT 1";

        $stmt = self::$db->prepare($query);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    
}