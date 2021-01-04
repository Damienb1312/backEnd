<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
</head>
<body>
<?php
    session_start();
    //si il y a connexion> afficher ...
    if(isset($_SESSION['id'])){
        // echo 'Bonjour ' . $_SESSION['identifiant']; 
        
        echo    '<table>
                    <tr>
                        <td>
                            <a href="deconnexion.php">Se déconnecter</a>
                        </td>
                        <td>
                            <a href="profil.php">Afficher profil</a>
                        </td>
                    </tr>
                </table>';
        
    }else{

    echo    '<table>
                <tr>
                    <td>
                        <a href="inscription.php">Inscription</a>
                    </td>
                    <td>
                        <a href="connexion.php">Se connecter</a>
                    </td>
                </tr>
            </table>';
    }
        ?>

        
</body>
</html>