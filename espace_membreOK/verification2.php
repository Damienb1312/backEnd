<?php
     require_once 'include/connexion_bdd.php';  // require, si il est inclus dans un autre endroit pas la peine de l'inclure une 2eme fois

//recuperer les parametres passés par l'url > avec GET
if($_GET){

  
    //vérification si ces deux parametres email et token sont vides ou non
    if(isset($_GET['token']) AND isset($_GET['email'])){ // s'assurer que l'on a dans la bdd notre utilisateur en question qui a demandé la validation par email
                              
        $token = $_GET['token'];
        $email = $_GET['email'];
        //> requete qui interroge notre bdd pour voir si il existe un enregistrement qui a cet email et cette valeur de token
        $requete = $bdd->prepare('SELECT email FROM terroir.table_membres WHERE token=:token');   
        $requete->bindvalue(':token',$token);

        $requete->execute();
        
        //calcul du nombre d'enregistrements retournés par cette requete grace à rowCount()
        $nombre=$requete->rowCount();
       
        //calcul de ce nombre pour pouvoir vérifier qu'il existe un enregistrement qui vérifie les conditions de cette requete SQL.
        if($nombre==1){
            $requete=$requete->fetch();
            if($requete['email']==$email)
            {
                $update = $bdd->prepare('UPDATE terroir.table_membres SET `validation`=1 WHERE email=:email'); // mise à jour de la bdd avec une requete
                //lier ces paramètres nommés
                $update->bindvalue(':email',$email); 
                
                $update->execute(); // resultat récupéré dans cette variable
    
                if($update){
                    $message = "Votre adresse email est bien confirmée!"; // puis REDIRECTION vers page de CONNEXION
                    
                }
            }
           
        }
    } 
    if(isset($message)) echo 'message envoyé';
}
 

?>