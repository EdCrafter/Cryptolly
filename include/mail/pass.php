<?php
$to = "weprevden@gmail.com";
$subject = "Ваш пароль";
$password = generateRandomPassword();
$message = "Ваш временный пароль: $password";
$headers = "From: verdev4az@gmail.com" . "\r\n" .
    "Reply-To: verdev4az@gmail.com" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();

$mailSent = mail($to, $subject, $message, $headers);

if ($mailSent) {
    echo "Письмо с паролем отправлено успешно.";
} else {
    echo "Не удалось отправить письмо.";
    if (error_get_last()) {
        echo "Ошибка: " . error_get_last()['message'];
    }
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


