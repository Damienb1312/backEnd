<?php
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
    $mail->addAddress('nioshy1@hotmail.com'); // adresse de destination
    $mail->isHTML(true); // mail en html
    $mail->Subject='Cet email est un test';         //objet de l'email
    $mail->Body='Afin de valider votre adresse email, merci de cliquer sur le lien suivant';   //body de l'email

    if(!$mail->send()){ // si mail n'a pas été envoyé, afficher ->
        echo "Mail non envoyé";
        echo 'Erreurs:' . $mail->ErrorInfo;
    } else{
        echo "Votre mail a bien été envoyé";
    }
    
    

?>