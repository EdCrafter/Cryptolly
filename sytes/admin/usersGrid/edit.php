<?php
$indB = rand(0, 3);
$indC = rand(4, 7);
function generateRandomPassword($length = 8)
{
    global $indB , $indC;
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersB = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersC = '0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        if ($i == $indB) {
            $password .= $charactersB[rand(0, strlen($charactersB) - 1)];
        }else if ($i == $indC) {
            $password .= $charactersC[rand(0, strlen($charactersC) - 1)];
        } 
        else {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
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
                    $pass = "'".$pass."'";
                    $sql = $db->find("admin")->update(['admin_pass'],[$pass])
                        ->where('user', '=', $_POST["id"])
                        ->sql();
                    $f = fopen('edit.txt', 'a+');
                    fputs($f, $sql);
                    fputs($f, "titihgjk\n");
                    fclose($f);
                    
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


