<?php
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
        <legend style="font-size:2em; font-weight:bold;">Réinitialisation de votre mot de passe</legend>
        <br>

        <form action="" method="POST" >
        <table>
            <tr>
                <td style="text-align:right;"><label for="code_secret">Entrez votre code secret : </label></td>
                <td><input type="number" name="code_secret" id="code_secret" placeholder="code secret" size="20"></td>
            </tr>
            <tr>
                <td style="text-align:right;"><label for="new_password">Entrez votre nouveau mot de passe : </label></td>
                <td><input type="password" name="new_password" id="new_password" placeholder="Nouveaumotdepasse1$" size="20"></td>
            </tr>
            <tr>
                <td><label for="mdpconnect">Veuillez confirmer votre nouveau mot de passe : </label></td>
                <td><input type="password" name="new_password_conf" id="new_password_conf" placeholder="Nouveaumotdepasse1" size="20"></td>
            </tr>
            </table>
            <input type="submit" value="valider" name="validationNewPassword">
        </form>
        <?php
        if(isset($_POST['code_secret']))
        {
            $recup_data_reset = $database->get("reset","*",["mail" => $mail_user]);
            // je récupère dans la table reset le code secret & id pour les supprimer à la fin du processus.
            $recup_code_secret = htmlspecialchars($recup_data_reset['code_secret']);
            $recup_id = htmlspecialchars($recup_data_reset['id']);
            // je récupère celui inscrit dans le formulaire
            $input_code_secret = htmlspecialchars($_POST['code_secret']);
                // je compare les 2
                if ($input_code_secret === $recup_code_secret ) {

                    return $recup_code_secret;

                    if (isset($_POST['validationNewPassword']) AND (isset($_post['new_password']) AND (isset($_POST['new_password_conf']))))
                    {
                        $new_password = htmlspecialchars($_POST['new_password']);
                        $new_password_conf = htmlspecialchars($_POST['new_password_conf']);
                        $mdpRegex = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/';
                        $mdpTest = str_word_count($new_password);

                        if ((((preg_match($mdpRegex, $new_password)) == 1) AND ($mdpTest == 1)))
                        {
                            if ($new_password === $new_password_conf)
                            {
                                //je récupère le password existant dans la table users celui qui correspond à aux mail de l'utilisateur
                                $get_password =$database->get("users", "password", ["mail" => $mail_user]);
                                //suppression password existant dans DBB
                                $database->delete("users",[
                                    "AND" =>[
                                        "password" => $get_password,
                                    ]
                                ]);
                                //j'insert le new password que je hache au dernier moment
                                $new_password = password_hash($new_password, PASSWORD_DEFAULT);

                                $database->insert("users", [
                                    "password" => $new_password,
                                ]);
                                //je supprime le l'users dans la table reset.
                                $database->delete("reset",[
                                    "AND" =>[
                                        "id" => $recup_id,
                                        "mail" => $mail_user,
                                        "code_secret" => $recup_code_secret,
                                    ]
                                ]);
                                $error = "<p style = \"color: red;>\">Votre mot de passe à été mis à jour.</p>";
                                header ('location: connection.php');
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
                    else
                    {
                        $error = "<p style = \"color: red;>\">Les champs ne sont pas tous remplis !!!</p>";
                    }
                }
                else
                {
                    $error = "<p style = \"color: red;>\">Le code est faux !!!</p>";
                }
        };
        ?>
        <div id="ConditionMdp" style="display:block; text-align:center; border:1px solid red; margin:10px 20px;">
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