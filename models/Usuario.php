<?php

namespace Models;
use Models\ActiveRecord;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_nuevo;
    public $password_actual;
    public $token;
    public $confirmado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? '';
    }

    //validar login de usuarios

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = "El email del usuario es obligatorio";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = "El email no válido";
        }
        if(!$this->password){
            self::$alertas['error'][] = "El password del usuario es obligatorio";
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El password debe contener al menos 6 carácteres";
        }
        return self::$alertas;
    }


    //validacion cuentas nuevas
    public function validarNuevaCuenta() {
        if(!$this->nombre){
            self::$alertas['error'][] = "El nombre del usuario es obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][] = "El email del usuario es obligatorio";
        }
        if(!$this->password){
            self::$alertas['error'][] = "El password del usuario es obligatorio";
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El password debe contener al menos 6 carácteres";
        }
        if($this->password !== $this->password2 ){
            self::$alertas['error'][] = "Los passwords no coinciden";
        }

        return self::$alertas;
    }

    public function comprobarPassword() : bool {
        return password_verify($this->password_actual, $this->password);
    }

    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() : void {
        $this->token = uniqid();
    }
    public function validarEmail(){
        if(!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = "El email no válido";
        }
        return self::$alertas;
    }
    public function validarPassword() : array {
        if(!$this->password){
            self::$alertas['error'][] = "El password del usuario es obligatorio";
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El password debe contener al menos 6 carácteres";
        }
        return self::$alertas;
    }

    public function nuevo_password() : array {
        if(!$this->password_actual){
            self::$alertas['error'][] = "El password actual es obligatorio";
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][] = "El password es obligatorio";
        }
        if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][] = "El password debe contener al menos 6 carácteres";
        }
        return self::$alertas;
    }

    public function validar_perfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = "El nombre del usuario es obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][] = "El email del usuario es obligatorio";
        }
        return self::$alertas;
    }
}