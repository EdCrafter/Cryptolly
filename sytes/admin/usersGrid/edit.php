<?php
function generateRandomPassword($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
include('../../../include/db.php');
$db = new DB([
    'host' => 'localhost',
    'user' => 'maxim',
    'password' => 'IPZ221Verdev',
    'db' => 'Cryptolly'
]);

if (isset($_POST['oper'])) {
    if ($_POST['oper'] == 'add') {
        echo json_encode(['success' => false, 'mail' => 'You can not add']);
    } else if ($_POST['oper'] == 'edit') {
        
        $sql = $db->find("members")->select(["email", "admin"])->where("username", "=", $_POST["id"])->sql();
        $r = $db->queryOne($sql);
        if ($r['admin']) {
            echo json_encode(['success' => true, 'mail' => 'User is admin']);
        } else {
            try {
                include_once('../../../include/mail/smtp.php');
                $mail->setFrom('verdev4az@gmail.com', 'Cryptolly');
                $mail->addAddress($r['email']);
                $mail->Subject = 'Your Password';
                $pass = generateRandomPassword();
                $mail->Body    = 'Ваш временный пароль: ' . $pass;
                $pass = password_hash($pass, PASSWORD_DEFAULT);
                $sql = $db->find("admin")->select(['*'])
                    ->where('user', '=', $_POST["id"])
                    ->sql();
                $r = $db->query($sql);
                if ($r) {
                    $sql = $db->find("admin")->update('admin_pass', $pass)
                        ->where('user', '=', $_POST["id"])
                        ->sql();
                    
                    $db->executeQuery($sql);
                } else {
                    $sql = $db->find("admin")
                        ->insert(['user', 'admin_pass'], [$_POST["id"], $pass])
                        ->sql();
                    $db->executeQuery($sql);
                }

                $mail->send();
                echo "Письмо с паролем отправлено успешно.";
            } catch (Exception $e) {
                echo "Не удалось отправить письмо. Ошибка: {$mail->ErrorInfo}";
            }

            
        }
    } else if ($_POST['oper'] == 'del') {
        echo json_encode(['success' => false, 'mail' => 'You can not delete']);
    }
} else {
    Header("HTTP/1.1 400 Bad params");
    echo json_encode(['success' => false]);
}


