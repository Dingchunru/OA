<?php
session_start();
session_destroy();
header('Location: /OA/index.php');
exit;
?>