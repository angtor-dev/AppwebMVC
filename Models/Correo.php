<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "Models/Model.php";


//required files
require 'public/lib/phpmailer/src/Exception.php';
require 'public/lib/phpmailer/src/PHPMailer.php';
require 'public/lib/phpmailer/src/SMTP.php';

class Correo extends Model
{

  public function sendUrl($destinatario, $url)
  {
    $mail = new PHPMailer(true);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
      $mail->SMTPAuth = true;                                   //Enable SMTP authentication
      $mail->Username = 'llamasdefuego.iglesia@gmail.com';                     //SMTP username
      $mail->Password = 'xqghnzcxmncdinai';                               //SMTP password
      $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
      $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom($destinatario, 'Llamas de Fuego');
      $mail->addAddress($destinatario);     //Add a recipient
      $mail->addReplyTo('llamasdefuego.iglesia@gmail.com', 'Llamas de Fuego');

      //$mail->AddEmbeddedImage('./resources/img/casawhite.jpg', 'csr');

      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = "Cambio de contraseña";
      $mail->Body = 'Has solicitado cambiar tu contraseña. Por favor, haz clic en el siguiente enlace para establecer una nueva contraseña. Este enlace es válido por 30 minutos: ' . $url;
      $mail->AltBody = 'Has solicitado cambiar tu contraseña. Por favor, copia y pega el siguiente enlace en tu navegador para establecer una nueva contraseña. Este enlace es válido por 30 minutos: ' . $url;
      ;


      $mail->send();
      echo json_encode(array('msj' => 'Envio de url para cambiar contraseña', 'text' => 'Se envio una url de acceso a cambio de contraseña a el correo asociado a este usuario'));

    } catch (Exception $e) {
      echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
    }
  }

}
