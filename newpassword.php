<?php
session_start();

include 'database.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>réinitialisation de votre mot de passe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <fieldset class="container">
        <legend style="font-size:2em; font-weight;bold;">Réinitialisation de votre mot de passe</legend>
        <br>

        <form action="" method="POST" >
        <table>
            <tr>
            <td style="text-align:right;"><label for="new_password">Entrez votre nouveau mot de passe : </label></td>
            <td><input type="password" name="new_password" id="new_password" placeholder="Nouveaumotdepasse1$" size="20"></td>
            </tr>
            <tr>
            <td><label for="mdpconnect">Veuillez comfirmer votre nouveau mot de passe : </label></td>
            <td><input type="password" name="new_password_conf" id="new_password_conf" placeholder="Nouveaumotdepasse1" size="20"></td>
            </tr>
            </table>
            <input type="submit" value="valider" name="validationNewPassword">
        </form>
        <?php
        if (isset($_POST['validationNewPassword'])) {
            $new_password = htmlspecialchars($_POST['new_password']);
            $new_password_conf = htmlspecialchars($_POST['new_password_conf']);
            $mdpRegex = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/';
            $mdpTest = str_word_count($new_password);

            if ((((preg_match($mdpRegex, $new_password)) == 1) AND ($mdpTest == 1))) 
            {
                if ($new_password === $new_password_conf) 
                {
                    //je met ma requete
                }
                else
                {
                    $error = "<p style = \"color: red;>\">Attention vos mots de passe ne sont pas identiques</p>";
                }
            }
            else
            {
                $error = "<p style = \"color: red;>\">Attention votre mot de passe ne correspont pas au conditions ci-dessus.</p>";
            }

        }
        ?>
        <div id="ConditionMdp" style="display:block; text-align:center; border: 1px solid red; margin:10px 20px;">
            <ul>Votre mot de passe doit contenir : 
                <li>Au moins 10 caractères</li>
                <li>Au moins 1 majuscule</li>
                <li>Au moins 1 minuscule</li>
                <li>Au moins 1 chiffre</li>
                <li>Au moins 1 caractère spécial</li>
            </ul>
        </div>

        <?php

        if(isset($error))
        {
            echo $error;
        }
        ?>
</body>
</html>