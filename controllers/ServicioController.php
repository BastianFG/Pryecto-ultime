<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;
use Controllers\CitaController;


class ServicioController {
    public static function index(Router $router) {
        session_start();

        isAdmin();

        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {
        session_start();
        isAdmin();
        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            
            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {
        session_start();
        isAdmin();

        if(!is_numeric($_GET['id'])) return;

        $servicio = Servicio::find($_GET['id']);
        $alertas = [];

        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar() {
        session_start();
        isAdmin();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }
    }

    public static function informe(Router $router) {
        session_start();
        isAdmin();
    
        // Obtener el mes y el año actual
        $mes = date('m');
        $año = date('Y');
    
        // Obtener todos los servicios con las solicitudes del mes
        $servicios = Servicio::obtenerTodosLosServiciosConSolicitudesDelMes($mes, $año);
    
        // Obtener los días de la semana más frecuentes para las citas
        $diasFrecuentes = Servicio::obtenerCitasPorDiaDeLaSemana($mes, $año);
    
        // Crear una estructura con todos los días de la semana
        $diasSemana = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];

        // Inicializar el conteo de citas para cada día en 0
        $diasConteo = array_fill_keys(array_keys($diasSemana), 0);

        // Rellenar el conteo con los datos reales
        foreach ($diasFrecuentes as $dia) {
            $diasConteo[$dia['dia_semana']] = $dia['cantidad'];
        }

        $router->render('servicios/informe', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios,
            'diasConteo' => $diasConteo,
            'diasSemana' => $diasSemana
        ]);
    }   
    
}