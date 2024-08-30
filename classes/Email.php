<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '693a1a0ec89382';
        $mail->Password = 'e391148a845777';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu Cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola" . $this->email . "</strong> has creado tu cuenta en UpTask, solo 
        debes confirmarla en el siguiente enlace:</p>";
        $contenido .= "<p>Preciona aquí: <a href='http://localhost:3000/confirmar?token=" . $this->token ."'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si no creaste esta cuenta, haz caso omiso a este correo</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;
        //enviar mail
        $mail->send();
    }
    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '693a1a0ec89382';
        $mail->Password = 'e391148a845777';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Restablece tu password';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->email . "</strong> has solicitado restablecer tu password, solo 
        debes presionar en el siguiente enlace:</p>";
        $contenido .= "<p>Preciona aquí: <a href='http://localhost:3000/restablece?token=" . $this->token ."'>Restablecer Password</a></p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;
        //enviar mail
        $mail->send();
    }
}