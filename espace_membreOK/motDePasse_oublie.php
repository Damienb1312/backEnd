<?php
    require('PHPMailer/PHPMailerAutoload.php');

    //si utilisateur clique sur bouton "reinitialiser mon mot de passe"
    if(isset($_POST['motDePasse_oublie'])){

        //creation token
        function token_random_string($leng=20){
            $str = '0123456789abcdefghijklmnopqrstuvwxyzABSCDEFGHIJKLMNOPQRSTUVWXYZ';
            $token = '';
            for($i=0;$i<$leng;$i++){
                $token.= $str[rand(0, strlen($str)-1)];  // mise à jour de la variable token concaténé avec la chaine précédemment produite
            }
            return $token;
        }
        
         //si champ utilisateur vide ou si champ mail pas valide> afficher message // filter_var pour vérifier la présence de @ et de la validité
        if(empty($_POST['email']) || !filter_var(($_POST['email']), FILTER_VALIDATE_EMAIL)){
            
            $message = 'Rentrez une adresse email valide';
            
        }else{
            //interroger la bdd pour savoir si email existe ou non dans bdd
            require('include/connexion_bdd.php'); //connexion à la bdd

            $requete = $bdd->prepare('SELECT * FROM terroir.table_membres WHERE email=:email');
            $requete->bindvalue(':email', $_POST['email']);
            $requete->execute();

            //resultat dans un tableau associatif
            $result =$requete->fetch();
            //combien de résultats retournés -> calcul du nombre de lignes retournées avec rowCount();
            $nombre = $requete->rowCount();
            //si nombre est différent de 1, ne pas accepter un utilisateur inscrit 2 fois, un seul membre ou zero membre
            if($nombre!=1){
                //adresse email saisie ne correspond a aucun utilisateur
                $message ="L'adresse email saisie ne correspond à aucun utilisateur de l'espace membre.";
            }else{
                // =1 = 1membre inscrit avec cette adresse email, 1:confirmée ou 2:validée
                if($result['validation']!=1){ //si email ne correspond pas à un membre confirmé
                    
                    $token = token_random_string(20); // fonction qui permet de générer la chaine de caractères
                    
                //code pour l'envoi du mail à l'utilisateur au clic 
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
                $mail->Subject=utf8_decode('Confirmation de votre email');         //objet de l\'email
                $mail->Body=utf8_decode('Afin de valider votre adresse email, merci de cliquer sur le lien suivant:
                <a href="http://localhost/espace_membreOK/verification.php?email='.$_POST['email'].'&token='.$token.'">
                Confirmation
                </a>');   //body de l'email ? >pour transmettre des paramètres à travers l'url

                if(!$mail->send()){ // si mail n'a pas été envoyé, afficher ->
                    $message = "Mail non envoyé";
                    echo 'Erreurs:' . $mail->ErrorInfo;
                } else{
                    $message = "Votre adresse email n'est pas encore confirmée. <br> Un email vous a été envoyé, merci de consulter votre boite email.";
                }
                }else{

                    // envoyer un mail avec le token avec la chaine
                    //de caractère générée aléatoirement 
                    // 1.faire appel à la fonction qui fait appel à la chaine de caractères générée par le token
                    // 2. interroger la table recup_password, car il se peut que l'utilisateur ait deja mis a jour son mot de passe
                    //et par conséquent il existe deja un membre avec l'adresse email saisie > dans ce cas la, faire une mise à jour
                    // 3.l'autre cas il se peut que l'utilisateur fasse une mise à jour pour la première fois de son mot de passe >
                    // > dans ce cas il faudra faire une insertion
                    // 4.avant d'envoyer le mail il faut enregistrer ce token dans la table "recup_password"

                    //1.interroger la base pour voir si il existe un utilisateur ayant l'adresse email saisie ou non
                    $token = token_random_string(20); //token_random_string permet de générer une chaine de caractère aléatoire
                    //2.interroger la table recup password
                    $requete1 = $bdd->prepare('SELECT * FROM terroir.recup_password WHERE email=:email');
                    //spécifier la requête bind value pour faire la liaison avec le paramètre nommé
                    $requete1->bindvalue(':email', $_POST['email']);
                    $requete1->execute();

                    //pour savoir si un utilisateur ayant utilisé cette adresse email ou non, calculer le nombre de lignes ou d'enregistrements
                    $nombre1 = $requete1->rowCount();
                    //test conditionnel si ce nombre = 0 (aucun utilisateur ayant cet email > faire une INSERTION) ou != 0 (si utilisateur existe > faire UPDATE)
                    if($nombre1==0){
                        //3.lorsque qu'il n'existe aucun utilisateur dans la table recup_password dans la réinitialisation du mot de passe
                        $requete2 = $bdd->prepare('INSERT INTO terroir.recup_password (email,token) VALUES (:email,:token)');
                        //lier ces paramètres par le bindvalue
                        $requete2->bindvalue(':email', $_POST['email']);
                        $requete2->bindvalue(':token', $token);
                        
                        $requete2->execute();

                    }else{
                        // si il existe un utilisateur dans la table recup_password. > faire une mise à jour UPDATE
                        $requete3 = $bdd->prepare('UPDATE terroir.recup_password SET token=:token WHERE email=:email');

                        $requete3->bindvalue(':token', $token);
                        $requete3->bindvalue(':email', $_POST['email']);
                        $requete3->execute();
                    }

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
                    $mail->Subject=utf8_decode('Réinitialisation du mot de passe');         //objet de l\'email // lien new_password > va permettre de saisir un nouveau mot de passe
                    $mail->Body=utf8_decode('Afin de réinitialiser votre mot de passe, merci de cliquer sur le lien suivant:
                    <a href="http://localhost/espace_membreOK/new_password.php?email='.$_POST['email'].'&token='.$token.'"> 
                    Réinitialisation du mot de passe
                    </a>');   //body de l'email ? >pour transmettre des paramètres à travers l'url

                    if(!$mail->send()){ // si mail n'a pas été envoyé, afficher ->
                        $message = "Mail non envoyé";
                        echo 'Erreurs:' . $mail->ErrorInfo;
                    } else{
                        $message = "Un email vous a été envoyé pour réinitialiser votre mot de passe. <br>Merci de consulter votre boite email.";
                    }

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
    <title>Reinitialisation</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <link rel="stylesheet" href="css/motDePasse_oublie.css">
</head>
<body>
    <div id="login">
                <h3 class="text-center text-white pt-5">Réinitialisation du mot de passe</h3>
                <h6 class="text-center text-white pt-5">Merci d'entrer votre adresse email.<br> Vous recevrez un email pour 
                    réinitialiser votre mot de passe</h6>
                <div class="container">
                    <div id="login-row" class="row justify-content-center align-items-center">
                        <div id="login-column" class="col-md-6">
                            <div id="login-box" class="col-md-12">

                                <?php if(isset($message)) echo $message; ?>

                                <form id="login-form" class="form" action="" method="post">
                                    <div class="form-group">
                                        <label for="email" class="text-info">Votre adresse Email: </label><br>
                                        <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Exemple@exemple.com">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="motDePasse_oublie" value="Réinitialiser mon mot de passe" class="btn btn-info btn-md">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html>