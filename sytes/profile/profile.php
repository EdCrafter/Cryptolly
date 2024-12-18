<!-- use function exif_imagetype;
use const IMAGETYPE_GIF;
use const IMAGETYPE_JPEG;
use const IMAGETYPE_PNG; -->
<?php
include_once("../../include/session.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../components/css/profile.css">
    <link rel="stylesheet" href="../../components/css/members.css">
    <link rel="stylesheet" href="../../include/gallery/plugin.css">
    <script src='../../include/gallery/jquery-3.7.1.js'></script>
    <script src='../../include/gallery/gallery.js'></script>
    <script>
      $(document).ready(function () {
        $('.gallery-img').imageGallery();
      });
    </script>
    <style>
        .gallery-img:not(.gallery-active) {
            display: none;
        }
    </style>
</head>

<body>
    <div class="main_container">
        <div class="container">
            <header>

                <?php
                include("../home/header.php");
                if (!$loggedin) {
                    die("<h1>You need to be logged in to view this page. </h1>
                        <button class='button-container' onclick=\"window.location.href = 'login.php';\"
                        style=\" font-size: x-large; border-radius: 0; margin-top: 30px;\">
                        Get Started
                    </button> ");
                }
                ?>

            </header>
            <?php
            include_once("../../include/showProfile.php");
            include_once("../../include/dataProcessor.php");
            echo "<h1>Your Profile</h1><br>";

            $result = $mysqli->find("profiles")->select(['*'])
                ->where('user', '=', $user)
                ->sql();
            $r = $mysqli->query($result);

            if (isset($_POST['text'])) {
                $text = $_POST['text'];
                $text = preg_replace('/\n/', '//n', $text);
                $text = DataProcessor::sanitizeString($text);
                $text = preg_replace('/\s\s+/', ' ', $text);
                $text = preg_replace('/\/\/n/', '\n', $text);
                if ($r) {
                    $sql = $mysqli->find("profiles")->update('text', $text)
                        ->where('user', '=', $user)
                        ->sql();
                    $mysqli->executeQuery($sql);
                } else {
                    $sql = $mysqli->find("profiles")
                        ->insert(['user', 'text'], [$user, $text])
                        ->sql();
                    $mysqli->executeQuery($sql);
                }
            }
            // else
            // {
            //     if ($r)
            //     {
            //     $text = stripslashes($r[0]['text']);
            //     }
            //     else $text = "";
            // }

            // $text = stripslashes(preg_replace('/\s\s+/', ' ', $text));
            if (isset($_FILES['images']['name'])) {
                if (!file_exists("img/$user")) {
                    mkdir("img/$user");
                }
                else
                {
                    $files = glob("img/$user/*");
                    foreach ($files as $file) {
                        if (is_file($file))
                        {
                            unlink($file);
                        }
                    }
                }
                $total = count($_FILES['images']['name']);
                for ($i = 1; $i <= $total; $i++) {
                    $saveto = "img/$user/$user" . "_" . "$i.jpg";
                    $tmp_name = $_FILES['images']['tmp_name'][$i - 1];
                    move_uploaded_file($tmp_name, $saveto);
                    $type = exif_imagetype($saveto); // Получаем тип файла по его пути
                    $typeok = TRUE;
                    switch ($type) {
                        case IMAGETYPE_GIF:
                            $src = imagecreatefromgif($saveto);
                            break;
                        case IMAGETYPE_JPEG:
                            $src = imagecreatefromjpeg($saveto);
                            break;
                        case IMAGETYPE_PNG:
                            $src = imagecreatefrompng($saveto);
                            break;
                        default:
                            $typeok = FALSE;
                            break;
                    }

                    if ($typeok) {
                        list($w, $h) = getimagesize($saveto);

                        $max = 1000;
                        $tw  = $w;
                        $th  = $h;

                        if ($w > $h && $max < $w) {
                            $th = $max / $w * $h;
                            $tw = $max;
                        } elseif ($h > $w && $max < $h) {
                            $tw = $max / $h * $w;
                            $th = $max;
                        } elseif ($max < $w) {
                            $tw = $th = $max;
                        }

                        $tmp = imagecreatetruecolor($tw, $th);
                        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
                        imageconvolution($tmp, array(
                            array(-1, -1, -1),
                            array(-1, 16, -1), array(-1, -1, -1)
                        ), 8, 0);
                        imagejpeg($tmp, $saveto);
                        imagedestroy($tmp);
                        imagedestroy($src);
                    }
                }
            }

            showProfile($user); ?>


            <form data-ajax='false' method='post' action="<? "profile.php?r=" . $randstr ?>" enctype='multipart/form-data'>
                <h3>Enter or edit your details and/or upload an image</h3>
                <textarea name='text' maxlength="1000" cols="70" rows="10" wrap="hard" autocorrect="on"></textarea><br>
                <h3>Image:</h3>
                <input type='file' name='images[]' multiple>
                <!-- size='14' -->
                <input type='submit' value='Save Profile'>
            </form>
            <?php
            include("../home/footer.php");
            ?>
        </div>
    </div>
</body>

</html>