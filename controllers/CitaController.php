<?php

namespace Controllers;

use MVC\Router;
use Controllers\ServicioController;
use Model\Cita;

class CitaController {
    public static function index( Router $router ) {

        session_start();

        isAuth();

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }

    public static function proximaCita(Router $router) {
        session_start();

        isAuth();

        // Suponiendo que el ID del usuario está almacenado en la sesión
        $usuarioId = $_SESSION['id'];
        
        // Obtener la próxima cita del usuario
        $proximaCita = Cita::obtenerProximaCita($usuarioId);

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'proximaCita' => $proximaCita
        ]);
    }

}