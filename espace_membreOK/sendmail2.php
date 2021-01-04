<?php
    require('PHPMailer/PHPMailerAutoload.php');

    $mail = new PHPMailer();

    $mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP 
    $mail->Host = 'smtp.office365.com'; // Spécifier le serveur SMTP
    $mail->SMTPAuth = true; // Activer authentication SMTP
    $mail->Username = 'nioshy1@hotmail.com'; // Votre adresse email d'envoi
    $mail->Password = 'Sung1312!'; // Le mot de passe de cette adresse email
    $mail->SMTPSecure = 'tls'; // Accepter SSL
    $mail->Port = 587;

    $mail->setFrom('nioshy1@hotmail.com', 'Mailer'); // Personnaliser l'envoyeur
    $mail->addAddress('damienbroggini@hotmail.fr', 'Damien'); // Ajouter le destinataire

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