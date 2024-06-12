<?php 

namespace Model;

class Servicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Servicio es Obligatorio';
        }
        if(!$this->precio) {
            self::$alertas['error'][] = 'El Precio del Servicio es Obligatorio';
        }
        if(!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'El precio no es válido';
        }

        return self::$alertas;
    }


    public static function obtenerTodosLosServiciosConSolicitudesDelMes($mes, $año) {
        $query = "SELECT servicios.id, servicios.nombre, COUNT(citasservicios.servicioid) AS cantidad 
                  FROM servicios
                  LEFT JOIN citasservicios ON servicios.id = citasservicios.servicioid
                  LEFT JOIN citas ON citasservicios.citaid = citas.id
                  AND MONTH(citas.fecha) = ? AND YEAR(citas.fecha) = ?
                  GROUP BY servicios.id
                  ORDER BY cantidad DESC";

        $stmt = self::$db->prepare($query);
        $stmt->bind_param("ii", $mes, $año);
        $stmt->execute();
        $result = $stmt->get_result();
        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }
        return $servicios;
    }

    public static function obtenerCitasPorDiaDeLaSemana($mes, $año) {
        $query = "SELECT DAYOFWEEK(fecha) as dia_semana, COUNT(id) as cantidad
                  FROM citas
                  WHERE MONTH(fecha) = ? AND YEAR(fecha) = ?
                  GROUP BY dia_semana
                  ORDER BY cantidad DESC";

        $stmt = self::$db->prepare($query);
        $stmt->bind_param("ii", $mes, $año);
        $stmt->execute();
        $result = $stmt->get_result();
        $diasSemana = [];
        while ($row = $result->fetch_assoc()) {
            $diasSemana[] = $row;
        }
        return $diasSemana;
    }

}