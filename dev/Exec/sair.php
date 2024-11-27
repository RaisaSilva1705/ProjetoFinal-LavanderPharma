<?php
    session_start();
    session_destroy();
    header('Location: http://localhost/htdocs/Farmácia/index.php');
    exit;
?>