<?php
session_start();

include 'database.php';

if(isset($_POST['validationConnect'])) // condition validation sur bouton
{
    $pseudoConnect = htmlspecialchars($_POST['pseudoconnect']);
    $mdpConnect = ($_POST['mdpconnect']);

    // afficher la date réelle de la connexion
    date_default_timezone_set('Europe/Paris');
    $date = strftime('%Y-%m-%d %H:%M:%S');

       // condition tous champs doivent etre remplis
    if(!empty($pseudoConnect) AND !empty($mdpConnect))
    {
        $user = $database->get("users","*",['name' => $pseudoConnect,]);
        //je me connecte à ma bade de donnée je recupère 1 seul element dans ma table users je selectionne tout les champs
        //car par la suite je vais utiliser cette variable... et donc je récupère la ligne qui possède le pseudo
        //que j'ai entré dans l'input , si ce dernier existe  alors user exite et on continue sinon on va dans le else.

        if ($user)
        {
            if(password_verify($mdpConnect,$user['password']))// je récupère le password dans la variable users que je compare avec celui entré dans l'input
            {
                $_SESSION['id'] = $user['id'];
                $_SESSION['pseudo'] = $user['name'];
                $_SESSION['mail'] = $user['mail'];
                header("Location: profil.php?id=".$_SESSION['id']);
            }
            else
            {
                $error = "<p style = \"color: red;\">Votre mot de passe est incorrect !!</p>";
            }
        }
        else
        {
            $error = "<p style = \"color: red;\">Votre identifiant n'existe pas !!</p>";
        }
    }
    else
    {
        $error = "<p style = \"color: red;\">Tous les champs doivent être remplis</p>";
    }
}
else
{
    $error = "<p style = \"font-weight: bold;\">Veuillez entrez votre identifiant et votre mot de passe pour vous connecter</p>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion mon espace</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <fieldset class="container">
        <legend style="font-size:2em; font-weight;bold;">Connexion</legend>
        <br>

        <form action="" method="POST" >
        <table>
            <tr>
            <td style="text-align:right;"><label for="pseudoconnect">Entrez votre pseudo : </label></td>
            <td><input type="text" name="pseudoconnect" id="pseudoconnect" placeholder="Votre pseudo" size="20" value="<?php if (isset($pseudoConnect)) {echo $pseudoConnect;}?>" ></td>
            </tr>
            <tr>
            <td><label for="mdpconnect">Entrez votre mot de passe : </label></td>
            <td><input type="password" name="mdpconnect" id="mdpconnect" placeholder="EntrezVotreMotDePasse*1" alt="Mot de passe" size="20"></td>
            </tr>
            </table>
            <input type="submit" value="connection" name="validationConnect">

        </form>
        <?php

        if(isset($error))
        {
            echo $error;
        }
        ?>

        <script src="main.js"></script></tr>
    </fieldset>
</body>
</html>