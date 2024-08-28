<?php

namespace Controllers;

use Classes\Email;
use Models\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //Verficar que el usuario exista
                $usuario = Usuario::where('email', $auth->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                } else {
                    if(password_verify($_POST['password'], $usuario->password)){
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar
                        header('Location: /proyectos');
                    } else {
                        Usuario::setAlerta('error', 'El password es incorrecto');
                    }
                    
                }
            }


        }
        $alertas = Usuario::getAlertas();
        //render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }    
    public static function logout(){
        
    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario();
    
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                $exiteUsuario = Usuario::where('email',$usuario->email);

                if($exiteUsuario) {
                    Usuario::setAlerta('erorr', 'Usuario ya está registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear el password
                    $usuario->hashPassword();

                    //Elimiar password2
                    unset($usuario->password2);
                    //crear token
                    $usuario->crearToken();
                    //Crear Usuario;
                    $resultado = $usuario->guardar();
                    //Enviar email
                    $email = new Email($usuario->email, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();
                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuairo' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router){
        $alertas = [];   
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                //buscar usuario
                $usuario = Usuario::where('email', $usuario->email);
                if($usuario && $usuario->confirmado){
                    //Generar nuevo token
                    $usuario->crearToken();
                    //eliminar campo de password2
                    unset($usuario->password2);
                    //actualizar usuario
                    $usuario->guardar();
                    //enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //imprimir alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                }else {
                    Usuario::setAlerta('error', 'El usuario no existe  o no está confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Olvidé Password',
            'alertas' => $alertas
        ]);
    }
    public static function restablecer(Router $router){

        $token = s($_GET['token']);
        $mostrar = true;
        if(!$token) header('Location: /');
        
        //Identificar el  usuario con el token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Usuario no válido'); 
            $mostrar = false;
        } 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Añadir nuevo password
            $usuario->sincronizar($_POST);
            
            //validar password
           $alertas = $usuario->validarPassword();

           if(empty($alertas)){
                //hashear password
                $usuario->hashPassword();
                //eliminar token
                $usuario->token = null;
                //guardar usuario en la bd
                $resultado = $usuario->guardar();
                //redireccionar
                if($resultado) {
                    header('Location: /');
                }
           }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Password',
            'alertas' => $alertas, 
            'mostrar' => $mostrar
        ]);
    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada'
        ]);
    }
    public static function confirmar(Router $router){
        $token = s($_GET['token']);
        
        if(!$token)
        {
            header('Location: /');
        }

        //Encontrar usuario con token

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            //No se encontró usuario con ese token
            Usuario::setAlerta('error', 'Token no válido');
        }else {
            //Confirmar cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            //Guardar en la BD
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
            
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}