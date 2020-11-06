<?php
//session_start(); car elle est inclus déjà dans le fichier database.php
include 'database.php';

if (isset($_POST['submit'], $_POST['mail'])) //pour faire simple la je vérifie en même temps si le btn et le champ sont remplis
{  
    if(isset($_POST['mail']))
    {
    $mail = htmlspecialchars($_POST['mail']);
    //je récupère mon mail et je le vérifie s'il est correct et s'il il existe dans la BDD
        if(filter_var($mail,FILTER_VALIDATE_EMAIL))
        {
            $recup_data = $database->get("users","*",["mail" => $mail]);

            if ($recup_data)// à partir de la on as vérifié notre mail et donc il existe bien
            {
                //On récupère la question secrète
                $recup_question = $recup_data['question_secret'];
                //on demande à l'utilisateur d'y répondre
                $input_reponse = "<input type=\"text\" name=\"reponse_secret\" id=\"reponse_secret\" size=\"38\" placeholder=\"Entrez votre réponse\" required>";
                $input_submit = "<input type=\"submit\" value=\"validé\" name=\"submit_secret\">";
                //on recupère la réponse dans la base de donnée
                $recup_reponse = $recup_data['reponse_secret'];
                //on vérifie si la réponse correspont à celle dans la BDD
                if(isset($_POST['submit_secret'])){

                    if (isset($_POST['reponse_secret']) AND ($recup_reponse == $_POST['reponse_secret']))
                    {
                        // si elle sont identiques alors je peut continuer la procédure.
                        $_SESSION['mail'] = $mail;
                    
                        $code_secret ="";// on va sécuriser avec un code de sécurité.
                            for ($i=0; $i < 8; $i++) {
                                $code_secret .= mt_rand(0,9);
                                // je génère un code aléatoire un point devant le égale pour inscrit les chiffres à la suite du ma variable
                            }
                        $_SESSION['code_secret'] = $code_secret;// je le met dans un variable de session pour m'en servir après.

                        // pour sécuriser encore plus le truc je vérifie que mon mail n'est pas déjà en base de donnée RESET
                        $verif_mail_reset_exist = $database->get("reset","*",["mail" => $mail]);

                            if ($verif_mail_reset_exist == 0) {
                                $error = "<p>Voici votre code secret : ".$code_secret." mémorisez-le il vous seras utile plus tard</p>";

                            // j'enregistre mon mail associé avec mon code secret dans une table reset.
                                $insert_data = $database->insert("reset",
                                        [
                                            "mail" => $mail,
                                            "code_secret" => $code_secret,
                                        ]);
                                        header ('location: newpassword.php');
                                    } else {
                                $error = "<p style = \"color: red;>\">la procédure de réinitialisation est déjà en cours</p>";
                            }
                    }
                    else
                    {
                        $error = "<p style = \"color: red;>\">la réponse n'est pas correct</p>";
                    }
                }
                else
                {
                    $error = "<p>Validé votre réponse</p>";
                }
            }
            else
            {
                $error = "<p style = \"color: red;>\">votre adresse mail ne possède aucun compte chez nous</p>";
            }

        }
        else
        {
            $error = "<script>alert(\"Adresse mail invalide !!!\")</script>";

        }
    }
    else
    {
    $error = "<script>alert(\"Veuillez nous renseigner votre email dans le champ\")</script>";
    }
}
?>