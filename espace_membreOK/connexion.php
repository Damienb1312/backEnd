<?php
//quand on clique sur "se connecter"
if(isset($_POST['connexion'])){
    $email      = $_POST['email'];
    $password   = $_POST['password'];

    require_once 'include/connexion_bdd.php';

    //requete pour interroger la bdd
    $requete = $bdd->prepare('SELECT * FROM terroir.table_membres WHERE email=:email');
    $requete->execute(array('email'=>$email));
    //recup le resultat de la requete dans un tableau grace a fetch
    $result = $requete->fetch();

    if(!$result){
        $message = "Merci de rentrer une adresse email valide.";
    }elseif($result['validation']==0){
        //creation token
        function token_random_string($leng=20){
            $str = '0123456789abcdefghijklmnopqrstuvwxyzABSCDEFGHIJKLMNOPQRSTUVWXYZ';
            $token = '';
            for($i=0;$i<$leng;$i++){
                $token.= $str[rand(0, strlen($str)-1)];  // mise à jour de la variable token concaténé avec la chaine précédemment produite
            }
            return $token;
        }
        $token = token_random_string(20);

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
            $message = "Votre adresse email n'est pas encore confirmée.
            Veuillez consulter votre boite email pour confirmer votre adresse email.";
        }
    }else{
        //verifier mot de passe hashé
        $passwordIsOk = password_verify($password,$result['password']);
        //si cela est vrai, > permettre à l'utilisateur de se connecter
        if($passwordIsOk){
            session_start();
            $_SESSION['id'] = $result['id'];
            $_SESSION['identifiant'] = $result['identifiant'];
            $_SESSION['email'] = $email; //email deja vérifié
            //redirection vers page d'accueil -------INSERER PAGE ACCUEIL TERROIR------------------------------
            
            //si utilisateur coche case se souvenir de moi
            if(isset($_POST['se_souvenir'])){

                //creer un cookie si il clique sur la case
                setcookie("email", $_POST['email']);
                setcookie("password", $_POST['password']);
            }else{
                //si utilisateur est deja identifié
                if(isset($_COOKIE['email'])){
                    setcookie($_COOKIE['email'], "");
                }
            }if(isset($_COOKIE['password'])){
                setcookie($_COOKIE['password '], "");
            }  
            header('location:index_espace_membres.php');  //10min43 video23    
        }else{
            $message = "Rentrez un mot de passe valide!";
        }
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <link rel="stylesheet" href="css/connexion.css">
    <title>Connexion</title>
</head>
<body>
<div id="login">
            <h3 class="text-center text-white pt-5">Je me connecte</h3>
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">

                            <?php if(isset($message)) echo $message; ?>

                            <form id="login-form" class="form" action="" method="post">
                                <div class="form-group">
                                    <label for="email" class="text-info">Adresse Email: </label><br>
                                    <input type="email" name="email" id="email" class="form-control" 
                                    value=<?php if(isset($_COOKIE['email'])){echo $_COOKIE['email'];}?>>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="text-info">Mot de passe:</label><br>
                                    <input type="password" name="password" id="password" class="form-control"
                                    value=<?php if(isset($_COOKIE['password'])){echo $_COOKIE['password'];}?>>
                                </div>
                                <div class="form-group">
                                    <label for="se_souvenir" class="text-info">Se souvenir de moi:
                                        <input type="checkbox" name="se_souvenir" id="se_souvenir">
                                    </label>

                                </div>
                                <div class="form-group">
                                    <input type="submit" name="connexion" value="Se connecter" class="btn btn-info btn-md">
                                    <a href="motDePasse_oublie.php">Mot de passe oublié</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>