<?php
    require_once 'include/connexion_bdd.php';  // require, si il est inclus dans un autre endroit pas la peine de l'inclure une 2eme fois
//recuperer les parametres passés par l'url > avec GET
if($_GET){
    if(isset($_GET['email'])){
        $email = $_GET['email'];
    }
    if(isset($_GET['token'])){
        $token = $_GET['token'];
    }
    //vérification si ces deux parametres email et token sont vides ou non
    if(!empty($email) && !empty($token)){ // s'assurer que l'on a dans la bdd notre utilisateur en question qui a demandé la validation par email
                                            //> requete qui interroge notre bdd pour voir si il existe un enregistrement qui a cet email et cette valeur de token
        $requete = $bdd->prepare('SELECT * FROM terroir.table_membres WHERE email=:email');                                        
        $requete->bindvalue(':email',$email);
        $requete->execute();
        //calcul du nombre d'enregistrements retournés par cette requete grace à rowCount()
        $nombre=$requete->rowCount();
        //calcul de ce nombre pour pouvoir vérifier qu'il existe un enregistrement qui vérifie les conditions de cette requete SQL.
        if($nombre==1){
            $update = $bdd->prepare('UPDATE terroir.table_membres SET validation=:validation, token=:token
                                        WHERE email=:email'); // mise à jour de la bdd avec une requete
            //lier ces paramètres nommés
            $update->bindvalue(':validation',1); 
            $update->bindvalue(':token','valide');  //valide > indicateur qui va permettre de dire que l'adresse email a été validée
            $update->bindvalue(':email',$email); 
            $resultUpdate=$update->execute(); // resultat récupéré dans cette variable
            if($resultUpdate){
                echo "<script type=\"text/javascript\">
                    alert('Votre adresse email est bien confirmée');
                    document.location.href='connexion.php';
                </script>";
                
            }
        }
    } 
    if(isset($message)) echo $message;  
}

?>