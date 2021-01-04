<?php

function inclureClass($className){

    //code pour charger automatiquement nos class

    //vérification de l'existence d'un fichier
    //__DIR__ const magique qui permet de retourner le dossier du fichier

    if(file_exists($fichier = __DIR__ . '/' . $className . '.php')){

        require $fichier;

    }

}

//mettre le nom de la function en paramètre. Ajout de la function inclureClass à la 
// pile d'autoload
spl_autoload_register('inclureClass');















?>