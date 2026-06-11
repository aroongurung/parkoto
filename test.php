<?php
echo "mysqli loaded: ";
echo (extension_loaded('mysqli') ? 'YES' : 'NO');
echo "<br>";
echo "Config file: " . php_ini_loaded_file();
?>