<?php

include "PHPMailerAutoload.php";
include "config.php";

$mail = new PHPMailer();

//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'echo';
//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = $mailerUsername;
//Password to use for SMTP authentication
$mail->Password = $mailerPassword;
//Set who the message is to be sent from
$mail->setFrom('loki@dieser-loki.de', 'Marcus "Loki" Jenz');
//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
$mail->addAddress('loki@silpion.de', 'Loki');
//Set the subject line
$mail->Subject = 'PHPMailer GMail SMTP test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//Replace the plain text body with one created manually
$mail->Body = "Das ist ein Test";
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
$mail->addAttachment('LICENSE');
//send the message, check for errors
if (!$mail->send()) {
    echo "\n\n\nMailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
exit(0);