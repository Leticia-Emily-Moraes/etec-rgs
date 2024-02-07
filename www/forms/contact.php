<?php
require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

$name = $_POST["name"];
$email = $_POST["email"];
$message = $_POST["message"];
$subject = $_POST["subject"];
$envio = $_POST["envio"];

$arquivo = "

<html>
<p><b>Nome: </b>$name</p>
<p><b>Email: </b>$email</p>
<p><b>Mensagem: </b>$message</p>
</html>

";

try {
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'etecrgs.websiteuser@gmail.com';
	$mail->Password = 'lmrznelyjxwjlard';
	$mail->Port = 587;

	$mail->setFrom($email, $name);
	$mail->addAddress('e282acad@cps.sp.gov.br', 'Secretaria');
	$mail->addReplyTo($email, $name);

	$mail->isHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $arquivo;
	$mail->AltBody = $message;

	if($mail->send()) {
                echo 'Email enviado com sucesso';
	} else {
                echo 'Email não enviado';
	}
       } catch (Exception $e) {
	echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
}


?>	