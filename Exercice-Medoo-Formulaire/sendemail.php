<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once 'PHPMailerAutoload.php';

/*******************
$to : adresse email du destinataire (ex : "kevindu93@gmail.com")
$subject : sujet de mail (ex : "Votre lien de réinitialisation du mot de passe")
$body : corps du mail (peut contenir des balises html)
retourne true si le mail a bien été envoyé, false sinon
*******************/
function send_mail($to, $subject, $body)
{
	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';
	$username         = "promodevweb@gmail.com"; // on utilise un compte gmail créé pour l'occasion
	$password         = 'tetris2020';  // vous pourrez utiliser ce compte-ci ou paramétrer le votre si vous le souhaitez
	// dans ce cas pensez à aller dans les paramètres de sécurité de votre compte et diminuer la sécurité pour permettre l'authentification via des applications tierces
	
	$mail->IsSMTP();
	$mail->SMTPOptions = array(
		'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
		)
	);
	//server settings
	$mail->SMTPDebug  = 2;  // mettez 2 pour avoir toutes les infos concernant l'envoi du mail sous la forme d'un echo
	$mail->SMTPAuth   = true;// car on utilise l'authentification
	$mail->SMTPSecure = 'tls';
	$mail->Host       = 'smtp.gmail.com';// j'utilise une adresse gmail
	$mail->Port       = 587;
	$mail->Username   = $username;// mon mail qui va envoyer le mail enregistré au dessus
	$mail->Password   = $password;// idem mais mdp

	//recipients & contents
	$mail->SetFrom($username, $username); //mon envoi seras fais sont le nom de mon mail je peut le modifier si je
	//souhaite envoyer plutot en cachat mon reel email
	$mail->AddReplyTo($username,$username);// si user clic qur repondre alors il epondras à cette adresse mail
	$mail->Subject    = $subject; // contenu du message qui seras affiché
	$mail->MsgHTML($body);// ensuite que contient le message donc le body de ma page
	$address = $to; // l'adresse du destinataire  devient une autre variable.
	$mail->AddAddress($address, $username);// à qui je souhaite envoyer l'adresse mail et , son nom

	return $mail->Send();// et donc j'envoi ce mail
}

?>