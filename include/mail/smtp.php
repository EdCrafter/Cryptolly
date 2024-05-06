<?php
include_once("../createDB.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php'; 
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
$config = require 'config.php';

// Инициализация объекта PHPMailer
$mail = new PHPMailer(true);

try {
    // Настройки сервера SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // SMTP сервер
    $mail->SMTPAuth   = true;
    $mail->Username   = 'verdev4az@gmail.com'; // Ваше имя пользователя SMTP
    $mail->Password = $config['smtp_password'];     // Ваш пароль SMTP
    $mail->SMTPSecure = 'tls';              // Используйте ssl или tls
    $mail->Port       = 587;                // Порт SMTP сервера

    // Настройки сообщения
    $mail->setFrom('verdev4az@gmail.com', 'Your Name');
    $mail->addAddress('weprevden@gmail.com');
    $mail->Subject = 'Your Password';
    $pass = generateRandomPassword();
    $sql = $mysqli->find("admin")->select(['*'])
            ->where('user','=', $_POST["user"])
            ->sql();
    $r = $mysqli->query($sql);
    if ($r)
    {
        $sql = $mysqli->find("admin")->update('password' ,$pass)
            ->where('user','=', $_POST["user"])
            ->sql();
        $mysqli->executeQuery($sql);
    }
    else {
        $sql = $mysqli->find("admin")
            ->insert(['user','password'],[$_POST["user"],$pass])
            ->sql();
        $mysqli->executeQuery($sql);
    }
    $mail->Body    = 'Ваш временный пароль: ' . $pass;

    $mail->send();
    echo "Письмо с паролем отправлено успешно.";
} catch (Exception $e) {
    echo "Не удалось отправить письмо. Ошибка: {$mail->ErrorInfo}";
}

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>