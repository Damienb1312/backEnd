<?php
session_start();
//condition pour dire qu'on ne peut se deconnecter que si on est connecté.
if(isset($_SESSION['id'])){
    session_unset(); // permet de détruire les variables de la session
    session_destroy();


header('location:index_espace_membres.php');
}else{
    echo 'Vous n\'etes pas connectés'; // si utilisateur n'est pas connecté qu'il va sur la page de deconnexion
    
}

?>