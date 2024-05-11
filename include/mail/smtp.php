<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php'; 
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
$config = require 'config.php';

// Инициализация объекта PHPMailer
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com'; // SMTP сервер
$mail->SMTPAuth   = true;
$mail->Username   = 'verdev4az@gmail.com'; // Ваше имя пользователя SMTP
$mail->Password = $config['smtp_password'];     // Ваш пароль SMTP
$mail->SMTPSecure = 'tls';              // Используйте ssl или tls
$mail->Port       = 587;                // Порт SMTP сервера

?>
