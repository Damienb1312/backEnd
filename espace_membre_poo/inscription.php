<?php

//on doit utiliser nos class Utilisateurs et UtilisateurManager (méthode manuelle)

// require 'Utilisateurs.php';

// require 'UtilisateurManager.php';

//chargement automatique des class en créant une function/ pile d'autoload qui
//contient une pile de function dont chacune d'entres elles seront appelées automatiquement
//par php

require 'inclureClass.php';

//instanciation d'un objet PDO

$bddPDO = new PDO('mysql:host=localhost;dbname=espace_membre_poo', 'root', '');

//configuration d'un gestionnaire de base de données
//ATTR_ERRMODE = retourne un rapport d'erreur lors des requetes SQL
//ERRMODE_EXCEPTION = retourne une exception
$bddPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//instancier à la class Manager à partir de l'objet manager

$manager = new UtilisateurManager($bddPDO);

if(isset($_POST['nom'])){

    //on récupère les données du formulaire
    // on va instancier un objet Utilisateur à partir de la class Utilisateurs dans lequel
    //on va stocker les infos récupérées à partir de formulaire

    $utilisateur = new Utilisateurs(
        //tableau pour affecter les valeurs saisies dans le formulaire
        [
            'nom' =>$_POST['nom'],
            'prenom'=>$_POST['prenom'],
            'tel'=>$_POST['tel'],
            'mail'=>$_POST['mail']
        ]
        
    );
    
    //si utilisateur est valide

    if($utilisateur->isUserValide()){
        //appel a la function inserer implémenté dans l'UtilisateurManager et l'implémenté 
        //sur l'utilisateur qu'on vient d'instancier ci-dessus avec les données récupérées du formulaire
        $manager->inserer($utilisateur);
        
        echo 'Utilisateur enregistré';

    }else{

        $erreurs = $utilisateur->getErreurs();

    }

}








?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Inscription utilisateur</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper bg-gra-01 p-t-180 p-b-100 font-poppins">
        <div class="wrapper wrapper--w780">
            <div class="card card-3">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Inscrivez-vous !</h2></h2>
                    <form action="inscription.php" method="POST">

                        <?php
                            //in_array -> test si une valeur appartient à 1 tableau ou non -> const + tableau $erreurs
                            //on test si la const NOM_INVALIDE appartient à ce tableau $erreurs
                            if(isset($erreurs) && in_array(Utilisateurs::NOM_INVALIDE, $erreurs)){
                                echo '<p style="color:white;">Le nom est invalide.</p><br>';
                            }

                        ?>

                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="Nom" name="nom">
                        </div>

                        <?php
                            //in_array -> test si une valeur appartient à 1 tableau ou non -> const + tableau $erreurs
                            //on test si la const PRENOM_INVALIDE appartient à ce tableau $erreurs
                            if(isset($erreurs) && in_array(Utilisateurs::PRENOM_INVALIDE, $erreurs)){
                                echo '<p style="color:white;">Le prénom est invalide.</p><br>';
                            }

                        ?>

                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="Prenom" name="prenom">
                        </div>
                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="Telephone" name="tel">
                        </div>

                        <?php
                            //in_array -> test si une valeur appartient à 1 tableau ou non -> const + tableau $erreurs
                            //on test si la const MAIL_INVALIDE appartient à ce tableau $erreurs
                            if(isset($erreurs) && in_array(Utilisateurs::MAIL_INVALIDE, $erreurs)){
                                echo '<p style="color:white;">L\'adresse email est invalide.</p><br>';
                            }

                        ?>

                        <div class="input-group">
                            <input class="input--style-3" type="email" placeholder="Email" name="mail">
                        </div>
                        
                        <div class="p-t-10">
                            <button class="btn btn--pill btn--green" type="submit" name="enregistrer">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->