<?php

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

// pour récupérer les informations de l'utilisateur pour chaque ligne
//la ligne <a href="?modifier=',$utilisateur->getId(),'">Modifier</a>
//on va mettre un test indiquant que si ce parametre 'modifier' transmis par l'url, on va récupérer
//les données par la method getUnUtilisateur implémentée dans UtilisateurManager
// on va récupérer les informations de l'id qui doivent correspondrent au parametre 'modifier'

if (isset($_GET['modifier'])) {

    //manager est une instance de la class UtilisateurManager

    $utilisateur = $manager->getUtilisateur((int) $_GET['modifier']);
}

//tableau associatif, si on récupère le nom -> nouvel objet utilisateur
if (isset($_POST['nom'])) {
    $utilisateur = new Utilisateurs(
        [

            'nom'       => $_POST['nom'],
            'prenom'    => $_POST['prenom'],
            'tel'       => $_POST['tel'],
            'mail'      => $_POST['mail'],

        ]
    );

    if (isset($_POST['id'])) {
        //si id existe => appeler le setter de l'id pour le mettre à jour
    
        $utilisateur->setId($_POST['id']);
        
        
    }
    
    //test si l'utilisateur est valide si l'objet manager est mis à jour

    if ($utilisateur->isUserValide()) {
        // $manager = new UtilisateurManager($bddPDO);
        $manager->mettreAjour($utilisateur);
        
        $message = 'Utilisateur mis à jour';
    }else{
        $erreurs = $utilisateur->getErreurs();
    }

}

// va supprimer l'id rentré dans l'url à partir de l'instance Manager

if (isset($_GET['supprimer'])) {

    $manager->supprimer((int) $_GET['supprimer']);

    $message ='Utilisateur bien supprimé';

}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
    <!-- Required meta tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

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
    <style type="text/css">

        table, td {
            border:1px solid black;
        }

        table {
            margin: auto;
            text-align: center;
            border-collapse: collapse;
        }

        td {
            padding: 3px;
        }

    </style>

</head>
<body>
    <p><a href="index.php">Accéder à l'accueil du site</a></p>

    <div class="page-wrapper bg-gra-01 p-t-180 p-b-100 font-poppins">
        <div class="wrapper wrapper--w780">
            <div class="card card-3">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Modification d'un membre</h2></h2>
                    <form action="administration.php" method="POST">

                        <?php
                            //in_array -> test si une valeur appartient à 1 tableau ou non -> const + tableau $erreurs
                            //on test si la const NOM_INVALIDE appartient à ce tableau $erreurs
                            if(isset($erreurs) && in_array(Utilisateurs::NOM_INVALIDE, $erreurs)){
                                echo '<p style="color:white;">Le nom est invalide.</p><br>';
                            }

                        ?>

                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="Nom" name="nom" 
                            value="<?php if(isset($utilisateur)) echo $utilisateur->getNom();?>">
                        </div>

                        <?php
                            //in_array -> test si une valeur appartient à 1 tableau ou non -> const + tableau $erreurs
                            //on test si la const PRENOM_INVALIDE appartient à ce tableau $erreurs
                            if(isset($erreurs) && in_array(Utilisateurs::PRENOM_INVALIDE, $erreurs)){
                                echo '<p style="color:white;">Le prénom est invalide.</p><br>';
                            }

                        ?>

                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="Prenom" name="prenom"
                            value="<?php if(isset($utilisateur)) echo $utilisateur->getPrenom();?>">
                        </div>

                        <div class="input-group">
                            <input class="input--style-3" type="text" placeholder="Telephone" name="tel"
                            value="<?php if(isset($utilisateur)) echo $utilisateur->getTel();?>">
                        </div>

                        <?php
                            //in_array -> test si une valeur appartient à 1 tableau ou non -> const + tableau $erreurs
                            //on test si la const MAIL_INVALIDE appartient à ce tableau $erreurs
                            if(isset($erreurs) && in_array(Utilisateurs::MAIL_INVALIDE, $erreurs)){
                                echo '<p style="color:white;">L\'adresse email est invalide.</p><br>';
                            }

                        ?>

                        <div class="input-group">
                            <input class="input--style-3" type="email" placeholder="Email" name="mail"
                            value="<?php if(isset($utilisateur)) echo $utilisateur->getMail();?>">
                        </div>
                        
                            <!-- Pour récupérer l'id de l'utilisateur -->
                        <?php
                                // si un objet utilisateur existe
                            if (isset($utilisateur)) {
                        ?>
                                <!-- champs de formulaire qui est caché -->
                                <!-- la valeur est l'id récupéré par l'url -->
                                <input type="hidden" name="id" 
                                value="<?= $utilisateur->getId()?>">

                        <?php
                            }
                        ?>

                        <div class="p-t-10">
                            <button class="btn btn--pill btn--green" type="submit" name="modifier" value="Modifier">Modifier</button>
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
    </div>



    <table>

        <?php

            if(isset($message)){
                echo $message;
            }

        ?>

        <tr>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Telephone</th>
            <th>Adresse email</th>
            <th>Modification</th>
            <th>Suppression</th>
        </tr>

        <?php

            // on va parcourir le résultat de notre requete pour récupérer le contenu
            // de la table utilisateurs en récupérant ses données
            //on se connecte à la class UtilisateurManager
            // on appelle la function getListeUtilisateurs dans UtilisateurManager.php à partir
            //de l'objet instancié manager de la class UtilisateurManager
            foreach ($manager->getListeUtilisateurs() as $utilisateur){
                
                echo    '<tr><td>', $utilisateur->getNom(), '</td><td>',
                                    $utilisateur->getPrenom(), '</td><td>',
                                    $utilisateur->getTel(), '</td><td>',
                                    $utilisateur->getMail(),'</td>
                                    <td>
                                        <a href="?modifier=',$utilisateur->getId(),'">modifier</a>
                                    </td>
                                    <td>
                                        <a href="?supprimer=',$utilisateur->getId(),'">supprimer</a>
                                    </td>
                        </tr>';

            }


        ?>
    </table>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>
    </body>
    </html>