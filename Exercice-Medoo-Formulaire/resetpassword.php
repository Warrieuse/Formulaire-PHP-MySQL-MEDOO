<?php
include 'database.php';
include '.sendemail.php';

require_once 'functions:resetPassword.php';

    /* if (isset($_POST['submit'])) {
        $mail = htmlspecialchars($_POST['mail']);
        
        $recup_data = $database->get("users","*",["mail" => $mail]);
    
        if ($recup_data) {
            //$id_user = $recup_data["id"];// je récupère id de mon user
            //$token = substr(md5(uniqid())0,15);// je crée un token qui est une suite de caractère unique pour cela je le sécurise un max
            //je prend les 15 premiers caractères d'un nbr aléatoire  que je hache à la façon md5 qui et je le retroune.
            
            //$mail = $recup_data;
            //$url_message ="<p>http://localhost:8888/B-PHP/Medoo/Exercice-Medoo-Formulaire/newpassword.php?id=".$id_user."&token=".$token"<a>ici</a></p>";

            //send_mail($mail, "Réinitilisation de votre mot de passe","coucou");
            $pseudo = $recup_data["name"];

            $to      = $recup_data;
            $subject = 'Réinitilisation de votre mot de passe';
            $message = '<p>Bonjour'.$pseudo.'<br> Veuillez trouver votre lien de réinitialisation de votre mot de passe <a href="http://localhost:8888/B-PHP/Medoo/Exercice-Medoo-Formulaire/newpassword.php?id=".$id_user."&token=".$token">ici</a></p>';
            $headers = 'From: marinelaporte@live.fr' . phpversion();

            mail($to, $subject, $message, $headers);

            $error = "<script>alert(\"allez dans votre boite mail , un lien de réinitialisation vient de vous être envoyé\")</script>";
            //header("Location: connection.php");
        }
        else
        {
        $error = "<p style = \"color: red;>\">votre adresse mail ne possède aucun compte chez nous</p>";
        }
    }
*/
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
    <legend>Réinitialisation de votre mot de passe</legend>
        <form action=" " method="POST">
            <table>
            <tr>
                <td style="text-align:right">
                    <label for="mail">Veuillez entrer votre adresse mail : </label>
                </td>
                <td>
                    <input type="email" name="mail" placeholder="email@domaine.fr" required value="

                        <?php if (isset($mail)) {echo $mail;} ?>">
                </td>
            </tr>
            <tr>
                <td style="text-align:right">
                    <input type="submit" value="envoyé" name="submit">
                </td>
            </tr>
            </table>
            <?php
                if( (isset($error)) OR (isset($recup_question)) OR (isset($input_reponse)) OR (isset($input_submit)) )
                {
                    echo $error;
                    echo $recup_question;
                    echo $input_reponse;
                    echo $input_submit;
                };
                ?>
        </form>
</fieldset>
</body>
</html>