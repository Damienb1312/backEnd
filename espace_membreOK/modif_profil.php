<?php
session_start();
//si l'utilisateur est connecté et qu'il existe une session => si id existe. Si il a appuyé sur modification
if(isset($_POST['modification']) AND isset($_SESSION['id'])){

    $id =$_SESSION['id']; 

    if(empty($_POST['identifiant']) || !preg_match('/[a-zA-Z0-9]+/', $_POST['identifiant'])){ //si vide ou entrée mal écrite
        $message = 'Votre identifiant doit être une chaîne de caractères (alphanumérique).';
    }else if(empty($_POST['password']) || $_POST['password'] != $_POST['password2']){
        $message = 'Veuillez rentrer un mot de passe valide';
    }else{
        require_once 'include/connexion_bdd.php';  // require, si il est inclus dans un autre endroit pas la peine de l'inclure une 2eme fois
        
       //avant d'envoyer les informations de l'utilisateur dans la base de donnée il faut vérifier si son 
       //identifiant existe dejà ou non, et pareil pour l'adresse email -> vérification
        // interroger la base de données grâce à une selection

        //-----IDENTIFIANT------
        $req = $bdd->prepare('SELECT * FROM terroir.table_membres WHERE identifiant = :identifiant'); // si le champ identifiant = la variable identifiant
        //bindvalue pour dire ce qu'est cette variable identifiant
        $req->bindvalue(':identifiant', $_POST['identifiant']); // $_POST -> ce que l'utilisateur a entré comme info
        $req->execute();
        //stocker le resultat dans une variable qui va récuperer le 1er enregistrement avec fetch
        $result = $req->fetch();

        if($result){
            $message = 'L\'identifiant que vous avez choisi existe déjà';
        }else{
            //insertion du mot de passe dans la bdd de manière sécurisée
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            //requete saisie par l'utilisateur à insérer
            $requete = $bdd->prepare('UPDATE terroir.table_membres SET identifiant = :identifiant, password = :password WHERE id=:id');
            //spécifier ce que sont :identifiant :email et :password
            $requete->bindvalue(':identifiant', $_POST['identifiant']);    //bindvalue permet d'attribuer les valeurs a identifiant etc 
            $requete->bindvalue(':password', $password);
            $requete->bindvalue(':id', $id); // récupération de la variable id qui est égale à $_SESSION de l'id
            //execution de la requete
            $requete->execute();
            $message = 'Les modifications sont bien effectutées!';
            
            header('location:index_espace_membres.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="css/inscription.css">
</head>
    <body>
        <div id="login">
            <h3 class="text-center text-white pt-5">Je modifie mon profil</h3>
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">

                            <?php if(isset($message)) echo $message; ?>

                            <form id="login-form" class="form" action="" method="post">
                                <div class="form-group">
                                    <label for="identifiant" class="text-info">Identifiant</label><br>
                                    <input type="text" name="identifiant" id="identifiant" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="text-info">Mot de passe:</label><br>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password2" class="text-info">Confirmation du mot de passe:</label><br>
                                    <input type="password" name="password2" id="password2" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="modification" class="btn btn-info btn-md" value="Mettre à jour">
                                    <a href="connexion.php" class="btn btn-info btn-md">Se connecter</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>