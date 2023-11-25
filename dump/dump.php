<?php

$path = __DIR__;
echo "$path<br>";

// Включаем путь к mysqldump
$mysqldumpPath = "C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe";

$cmd = "$mysqldumpPath -h localhost -u root -pIPZ221Verdev -v cryptolly > \"$path\\dump.sql\""; 

exec("icacls \"$path\" /grant:r Users:(OI)(CI)F /T");
exec("type nul > \"$path\\dump.sql\"");

echo "$cmd<br>";

exec($cmd, $output, $status); 

if ($status) {
    echo "Error";
} else {
    echo "<pre>";
    foreach ($output as $line) {
        echo htmlspecialchars("$line\n");
    }
    echo "</pre>";
}
?>
