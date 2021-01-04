<?php
require('PHPMailer/PHPMailerAutoload.php');
//S'assurer si des paramètres ont été passé par l'url ou non. ne pas permettre à n'importe qui d'acceder à ce fichier. acceder à ce fichier que
//si l'utilisateur passe par le lien envoyé par email à l'utilisateur. pour cela je fais des tests.

 if($_GET){

    if(isset($_GET['email'])){ // si paramètre nommé email et token dans l'url
        $email = $_GET['email'];
    }
    if(isset($_GET['token'])){
        $token = $_GET['token'];
    }
    //paramètre email ne doit pas être vide donc > vérification de l'email
    if(!empty($email) && !empty($token)){
        //interroger la table recup password pour voir vérifier si ces paramètres passés par l'url existe dans la table recup password. 
        // pour etre sur que l'utilisateur est passé par le lien qu'on lui a envoyé
        require_once('include/connexion_bdd.php');

        $requete = $bdd->prepare('SELECT * FROM terroir.recup_password WHERE email=:email AND token=:token');
        //liaison entre paramètres nommés et à vérifier
        $requete->bindvalue(':email',$email);
        $requete->bindvalue(':token',$token);

        $requete->execute();
        //calculer le nombre de lignes retournées soit 1 ligne soit 0 ligne
        $nombre = $requete->rowCount();
        //vérification
        if($nombre!=1){ //aucun utilisateur dans la table recup password ayant email et token passés par le paramètre
        
            header('location:connexion.php');

        }else{

            //s'assurer que l'utilisateur a validé le formulaire ou non
            if(isset($_POST['new_password'])){ //formulaire validé avec le name du bouton
                //s'assurer que les deux champs password se correspondent
                if(empty($_POST['password']) || $_POST['password']!=$_POST['password2']){
                    $message = 'Rentrez un mot de passe valide';
                }else{ // les deux champs se correspondent
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    // requete de mise à jour dans la table table_membres
                    $requete = $bdd->prepare('UPDATE terroir.table_membres SET password=:password WHERE email=:email');
                    //liaison
                    $requete->bindvalue(':email', $email);
                    $requete->bindvalue('password', $password); // la variable avec le password hashé

                    $result = $requete->execute(); // recuperation de requete dans une variable pour faire des vérifications

                    //si mise à jour bien effectuée, afficher message à l'utilisateur pour dire réinitialisation du MDP ok
                    if($result){ // script JS avec une fenetre pour dire que MDP réinitialisé
                        echo "  <script type = \"text/javascript\">                    
                                    alert('Votre mot de passe est bien réinitialisé');
                                    document.location.href='connexion.php';
                                </script>";
                    }else{
                        $message = "Votre mot de passe n'a pas été réinitialisé";
                        header('location:connexion.php');
                    }

                }
            }

        }


    }

 }else{ // si aucun paramètre passé dans le GET
    header('location:inscription.php');
 }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reinitialisation</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <link rel="stylesheet" href="css/new_password.css">
</head>
<body>
    <div id="login">
                <h3 class="text-center text-white pt-5">Nouveau mot de passe</h3>
                <h6 class="text-center text-white pt-5">Merci d'entrer votre nouveau mot de passe.</h6>
                <div class="container">
                    <div id="login-row" class="row justify-content-center align-items-center">
                        <div id="login-column" class="col-md-6">
                            <div id="login-box" class="col-md-12">

                                <?php if(isset($message)) echo $message; ?>

                                <form id="login-form" class="form" action="" method="post">
                                    <div class="form-group">
                                        <label for="password" class="text-info">Votre nouveau mot de passe: </label><br>
                                        <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Nouveau mot de passe">
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="text-info">Confirmation du nouveau mot de passe: </label><br>
                                        <input type="password" name="password2" id="password2" class="form-control"
                                        placeholder="Confirmer mot de passe">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="new_password" value="Valider mon mot de passe" class="btn btn-info btn-md">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html>