<?php

include "PHPMailerAutoload.php";


$mail = new PHPMailer();
// Will be overridden by config.php
$config = [
    'mailerUsername' => "",
    'mailerPassword' => "",
    'files' => [],
    'content' => '',
    'recipients' => [],
    'subject' => "",
    'from' => [],
];
include "config.php";
$verbosity = 0;
function startsWith($haystack, $needle)
{
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

//Option Handling
function startsWith2($hayStack, array $needles)
{
    foreach ($needles as $needle) {

        if (startsWith($hayStack, $needle)) {
            return true;
        }
    }

    return false;
}


foreach ($argv as $arg) {
    if (startsWith2($arg, ["--help", "-h"])) {
        printHelp();
        exit(0);
    }
    if (startsWith2($arg, ["--body", "-b"])) {
        $_tmp = explode("=", $arg, 2);
        $config['content'] = $_tmp[1];
    }
    if (startsWith2($arg, ["--body", "-b"])) {
        $_tmp = explode("=", $arg, 2);
        $config['content'] = $_tmp[1];
    }
    if (startsWith2($arg, ["--subject", "-s"])) {
        $_tmp = explode("=", $arg, 2);
        $config['subject'] = $_tmp[1];
    }
    if (startsWith2($arg, ["--recipient", "-r"])) {
        $_tmp = explode("=", $arg, 2);
        $rec = $_tmp[1];

        $rec = explode("=>", $rec, 2);
        if (count($rec) == 2) {
            $config['recipients'][$rec[0]] = $rec[1];
        } else {
            $config['recipients'][$rec[0]] = "";
        }
    }
    if (startsWith2($arg, ["--from", "-f"])) {
        $_tmp = explode("=", $arg, 2);
        $rec = $_tmp[1];

        $rec = explode("=>", $rec, 2);
        if (count($rec) == 2) {
            $config['from'][$rec[0]] = $rec[1];
        } else {
            $config['from'][$rec[0]] = "";
        }
    }
    if (startsWith2($arg, ["--verbose"])) {
        $_tmp = explode("=", $arg, 2);
        $verbosity = $_tmp[1];
    }
    if (startsWith2($arg, ["-v"])) {
        $verbosity+=substr_count($arg, "v");

    }
    if (startsWith2($arg, ["--config", "-c"])) {
        $_tmp = explode("=", $arg, 2);
        include $_tmp[1];
    }

}

//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = $verbosity;
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
$mail->Username = $config['mailerUsername'];
//Password to use for SMTP authentication
$mail->Password = $config['mailerPassword'];
//Set who the message is to be sent from
foreach ($config['from'] as $address=>  $name) {

    $mail->setFrom($address, $name);
}
//$mail->setFrom('loki@dieser-loki.de', 'Marcus "Loki" Jenz');
//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to

foreach ($config['recipients'] as $address=>  $name) {

    $mail->addAddress($address, $name);
}


//$mail->addAddress('loki@silpion.de', 'Loki');
//Set the subject line
$mail->Subject = $config['subject'];
$mail->Body = "This is a Test-Email";
//$mail->AltBody = 'Das ist ein Test';

if ($config['content']) {
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $pathInfo = pathinfo($config['content']);

    $mail->msgHTML(file_get_contents($config['content']), dirname(__FILE__));
}
//exit(0);
//Replace the plain text body with one created manually
//Attach an image file
foreach($config['files'] as $file) {
    $mail->addAttachment($file);
}

//send the message, check for errors
if (!$mail->send()) {
    echo "\n\n\nMailer Error: ".$mail->ErrorInfo."\n";
} else {
    echo "Message sent!\n";
}
exit(0);


function printHelp(){
    echo"
    usage: php mailer.php [options]
    Options:
    --help -h   Print this Help
    --config -c ConfigFile in the form cof config.php.dist
    --body -b Templatefile for the EmailBody (e.g. html File)
    --subject -s the Subject of the Email
    --recipient -r  -r=\"foo@example.com=>Mr Foo\"
    --from -f -f=\"foo@example.com=>Mr Foo\"
    --verbose -v increase verbosity
    ";
}