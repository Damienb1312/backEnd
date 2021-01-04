<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <link rel="stylesheet" href="css/profil.css">

    <title>Profil</title>
</head>
    <body>
        <?php
        //demarrer la session avec l'id de l'utilisateur qui s'est inscrit
            session_start();
            
            if(isset($_SESSION['id'])){
        ?>

        <div id="login">
            <h3 class="text-center text-white pt-5">Profil</h3>
            <hr class="hr">
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">

                <table>
                    <tr>
                        <td>Nom d'utilisateur :</td>
                        <td><?=$_SESSION['identifiant']?></td>
                    </tr>
                    <tr>
                        <td>Adresse email :</td>
                        <td><?=$_SESSION['email']?></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="modif_profil.php">Modifier mon profil</a>
                        </td>
                    </tr>
                </table>

            <?php
            }
            ?>
        
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>