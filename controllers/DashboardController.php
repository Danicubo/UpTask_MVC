<?php

namespace Controllers;

use Models\Proyecto;
use Models\Usuario;
use MVC\Router;

class DashboardController {
    public static function index(Router $router){
        session_start();
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId' ,$id);
        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            
            //validacion
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){

                //general url unica
                $proyecto->url = md5( uniqid() );

                //Almacenar proyecto a cliente
                $proyecto->propietarioId = $_SESSION['id'];

                //Guardar proyecto
                $proyecto->guardar();
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }
        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }
    public static function proyecto(Router $router) {
        session_start();
        isAuth();
        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');

        //Revisar que la persona que visita el proyecto sea el propietario
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }
    public static function perfil(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if(empty($alertas)){


                $existeUsuario = Usuario::where('email', $usuario->email);
                
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    //Mensaje error
                    Usuario::setAlerta('error', 'Email cuenta con usuario');
                    $alertas = $usuario->getAlertas();
                } else {
                    //Guarda los cambios en la BD
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Usuario Actualizado Correctamente');
                    $alertas = $usuario->getAlertas();
                    //Asignar nuevo nombre a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }
        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();
            
            if(empty($alertas)) {
                $resultado = $usuario->comprobarPassword();
                if($resultado){
                    //Asignar nuevo password
                    $usuario->password = $usuario->password_nuevo;

                    //Eliminar propiedades no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //Hashear password
                    $usuario->hashPassword();

                    //Guardar password
                    $resultado = $usuario->guardar();
                    if($resultado){
                        Usuario::setAlerta('exito', 'Password Modificado Correctamente');
                        $alertas = Usuario::getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = Usuario::getAlertas();
                }
            }

        }

        $router->render('dashboard/cambiar-password', [
            'alertas' => $alertas,
            'titulo' => 'Cambiar Password',
        ]);
    }
}