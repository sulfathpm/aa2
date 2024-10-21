<?php
    session_start();
    session_destroy();
    header("Location: custmrdshbrd.php"); 
?>