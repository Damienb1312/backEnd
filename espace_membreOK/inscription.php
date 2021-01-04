<?php

    // si utilisateur clique sur s'inscrire preg-match expression reguliere, voir si utilisateur a spécifié ce qu'il y a entre les guillemets
    if(isset($_POST['inscription'])){
        if(empty($_POST['identifiant']) || !preg_match('/[a-zA-Z0-9]+/', $_POST['identifiant'])){ //si vide ou entrée mal écrite
            $message = 'Votre identifiant doit être une chaîne de caractères (alphanumérique).';
        }else if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ //filter_var verifie si c'est bien un email, FILTER_VALIDATE_EMAIL-> constante pour verification
            $message = 'Rentrez une adresse email valide';   
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
            //stocker le resultat dans une variable qui  va récuperer le 1er enregistrement avec fetch
            $result = $req->fetch();

            //------EMAIL---------
            $reqMail = $bdd->prepare('SELECT * FROM terroir.table_membres WHERE email = :email'); // si le champ email = la variable email
            //bindvalue pour dire ce qu'est cette variable email
            $reqMail->bindvalue(':email', $_POST['email']); // $_POST -> ce que l'utilisateur a entré comme info
            $reqMail->execute();
            //stocker le resultat dans une variable qui va récuperer le 1er enregistrement avec fetch
            $resultMail = $reqMail->fetch();

            // si les 2 1eres conditions ne sont pas vérifiés exécuter le code d'inscription de l'utilisateur
            if($result){
                $message = 'l\'identifiant que vous avez choisi existe déjà';
            }else if($resultMail){
                $message = 'Cet email existe déjà';
            }else{
                //creation token
                function token_random_string($leng=20){
                    $str = '0123456789abcdefghijklmnopqrstuvwxyzABSCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $token = '';
                    for($i=0;$i<$leng;$i++){
                        $token.= $str[rand(0, strlen($str)-1)];  // mise à jour de la variable token concaténé avec la chaine précédemment produite
                    }
                    return $token;
                }
                $token = token_random_string(20); // fonction qui permet de générer la chaine de caractères


                //insertion du mot de passe dans la bdd de manière sécurisée
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                //requete saisie par l'utilisateur à insérer, préparer la requete au lieu de l'insérer directement
                $requete = $bdd->prepare('INSERT INTO terroir.table_membres(identifiant, email, password, token) 
                                            VALUES(:identifiant, :email, :password, :token)');
                //spécifier ce que sont :identifiant :email et :password
                $requete->bindvalue(':identifiant', $_POST['identifiant']);    //bindvalue permet d'attribuer les valeurs a identifiant  
                $requete->bindvalue(':email', $_POST['email']);
                $requete->bindvalue(':password', $password);
                $requete->bindvalue(':token', $token); // lié au resultat de la fonction qui permet de générer ce token

                //execution de la requete
                $requete->execute();
                //envoi du mail à l'utilisateur au clic de l'inscription
                require('PHPMailer/PHPMailerAutoload.php');

                $mail = new PHPMailer();

                $mail->isSMTP(); // mailer peut utiliser le protocole SMTP
                $mail->Host='smtp-mail.outlook.com';  //host pour spécifier le serveur utilisé
                $mail->SMTPAuth = true; //authentification sur true pour l'activer
                $mail->Username='nioshy1@hotmail.com';   //spécifier adresse mail d'expédition
                $mail->Password='Sung1312!';
                $mail->SMTPSecure='tls';  // activer protocole de cryptage
                $mail->Port=587;

                $mail->setFrom('nioshy1@hotmail.com', 'Damien'); //spécifier l'adresse d'expédition
                $mail->addAddress($_POST['email']); // adresse de destination est celle saisie par l'utilisateur
                $mail->isHTML(true); // mail en html
                $mail->Subject='Confirmation de votre email';         //objet de l\'email
                $mail->Body='Afin de valider votre adresse email, merci de cliquer sur le lien suivant:
                <a href="http://localhost/espace_membreOK/verification.php?email='.$_POST['email'].'&token='.$token.'">
                Confirmation
                </a>';   //body de l'email ? >pour transmettre des paramètres à travers l'url

                if(!$mail->send()){ // si mail n'a pas été envoyé, afficher ->
                    $message = "Mail non envoyé";
                    echo 'Erreurs:' . $mail->ErrorInfo;
                } else{
                    $message = "Un email vous a été envoyé, merci de consulter votre boite email.";
                }
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
            <h3 class="text-center text-white pt-5">Je m'inscris</h3>
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
                                    <label for="email" class="text-info">Adresse Email: </label><br>
                                    <input type="email" name="email" id="email" class="form-control"> 
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
                                    <input type="submit" name="inscription" class="btn btn-info btn-md" value="S'inscrire">
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

