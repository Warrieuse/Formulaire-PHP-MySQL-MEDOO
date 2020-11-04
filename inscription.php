<?php

include 'database.php';


if(isset($_POST['validation']))
{
    if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mailConf']) AND !empty($_POST['mdp']) AND !empty($_POST['mdpConf']) AND !empty($_POST['statut']))
    {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $mail = htmlspecialchars($_POST['mail']);
        $mailConf = htmlspecialchars($_POST['mailConf']);
        $mdp = ($_POST['mdp']); 
        $mdpConf = ($_POST['mdpConf']);
        $statut = htmlspecialchars($_POST['statut']);
        $CGVD = ($_POST['cgvd']);

        date_default_timezone_set('Europe/Paris');
        $date = strftime('%Y-%m-%d %H:%M:%S');

        $mdpRegex = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/';
        $mdpTest = str_word_count($mdp);

        $pseudolength = strlen($pseudo);

        if($pseudolength <= 255)
        {
            $select_pseudo = $database->select("users","name",["name"=>$pseudo]);

            if($select_pseudo[0] == 0)
            {
                if($mail == $mailConf)
                {
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL))
                    {
                        $select_mail = $database->select("users","mail",["mail"=>$mail]);

                        if ($select_mail[0] == 0)
                        {
                            if (((preg_match($mdpRegex, $mdp)) == 1) AND ($mdpTest == 1)) // test regex mdp 1M 1n 1nbr & 1!.
                            {   
                                if(isset($CGVD))
                                {
                                    if(!empty($statut))
                                    {
                                        if($mdp == $mdpConf) 
                                        {   
                                            $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

                                            $database->insert("users", [
                                                "name" => $pseudo,
                                                "mail" => $mail,
                                                "password" => $mdp,
                                                "date" => $date,
                                                "statut" => $statut,
                                            ]);
                                            $_SESSION['comptecree'] = "Votre compte à bien été crée !! <a href=\"connection.php\"> Me connecter </a>";
                                            header ('location: connection.php');
                                        }
                                        else
                                        {
                                            $error = "<p style = \"color: red;>\">Vos mots de passe ne sont pas identiques.</p>";
                                        }
                                    }
                                    else
                                    {
                                        $error = "<p style = \"color: red;>\">Vous n'avez pas définit votre statut !!!</p>";
                                    }
                                }
                                else
                                {
                                    $error = "<p style = \"color: red;>\">Vous n'avez pas accepté les conditions général de vente !!!</p>";
                                }
                            }
                            else
                            {
                                $error = "<p style = \"color: red;>\">Votre mot de passe n'est pas correct - Regardez les conditions ci-dessus.</p>";
                            } 
                        }
                        else
                        {   
                            
                            $error = "<p style = \"color: red;>\">Cette adresse mail est déjà utilisé.</p>";
                        }
                    }
                    else
                    {
                        $error = "<p style = \"color: red;>\">Votre adresse mail n'est pas valide !</p>";
                    }
                    
                }
                else
                {
                    $error = "<p style = \"color: red;>\">Vos adresses mail ne sont pas identiques.</p>";
                }
            }
            else
            {
                $error = "<p style = \"color: red;>\">Votre pseudo existe déjà !!</p>";
            }
        }
        else
        {
            $error = "<p style = \"color: red;>\">Votre pseudo ne dois pas dépasser 255 caractères</p>";
        }

    }    
    else
    {
        $error = "<p style = \"color: red;>\">Tous les champs doivent être remplis</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création espace membre</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <fieldset class="container">
        <h2>Inscription</h2>
        <br> 

        <form action="" method="POST" >
            <table> <!--un tableau pour que ce soit bien aligné avec un div ou autre il y aurais un décalage-->
                <tr>
                    <td style="text-align:right">
                        <label for="pseudo">Pseudo :</label>
                    </td>
                    <td>
                        <input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" size="50" value="<?php if (isset($pseudo)) {echo $pseudo;}?>" > <!--Si Pseudo Sécurisé est remplis et valide alors on le garde affiché dans le form IDEM pour les uatres champs hormis MDP-->
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label for="mail">Mail :</label>
                    </td>
                    <td>
                        <input type="email" name="mail" id="mail" placeholder="votreemail@domaine.fr" size="50" value="
                        
                        <?php if (isset($mail)) {echo $mail;} ?>" >
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label for="mailConf">Confirmation du mail :</label>
                    </td>
                    <td>
                        <input type="email" name="mailConf" id="mailConf" placeholder="Confirmez votre email" size="50" value="
                        
                        <?php if (isset($mailConf)) {echo $mailConf;} ?>" >
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label for="mdp">Mot de passe :</label>
                    </td>
                    <td>
                        <input type="password" name="mdp" id="mdp" placeholder="EntrezVotreMotDePasse*1" size="50">
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label for="mdpConf">Confirmation du mot de passe :</label>
                    </td>
                    <td>
                        <input type="password" name="mdpConf" id="mdpConf" placeholder="Confirmez votre mot de passe" size="50">
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label>Vous êtes :</label>
                    </td>
                    <td>
                        <input type="radio" name="statut" id="statut" value="particulier">
                        <label for="statut">un particulier</label>
                        <input type="radio" name="statut" id="statut" value="professionnel">
                        <label for="statut">un professionnel</label>
                    </td>
                </tr>

            </table>
            <br><br>
            <div>
                <input type="checkbox" id="cgvd" name="cgvd" required>
                <label for="scales" name="cgvd">Je reconnais avoir pris connaissance des conditions d’utilisation et y adhère totalement.</label>
            </div>

            <br>

            <div>
                <input type="submit" value="Envoyer" name="validation">
            </div>

            <div id="ConditionMdp" style="display:block; text-align:center; text-decoration:none;">
                <ul>Votre mot de passe doit contenir : 
                    <li>Au moins 10 caractères</li>
                    <li>Au moins 1 majuscule</li>
                    <li>Au moins 1 minuscule</li>
                    <li>Au moins 1 chiffre</li>
                    <li>Au moins 1 caractère spécial</li>
                </ul>
            </div>

            </div>

        </form>
        <?php

        if(isset($error))
        {
            echo $error;
        }
        ?>

        <script src="main.js"></script>
    </fieldset>
</body>
</html>