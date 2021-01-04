<?php

    $bdd = new PDO('mysql:dbname =terroir; host=localhost', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
?>