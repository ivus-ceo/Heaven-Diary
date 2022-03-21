<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once 'php-mailer/vendor/autoload.php';

$mail = new PHPMailer(true);

$mail->isSMTP();// Set mailer to use SMTP
$mail->CharSet = "utf-8";// set charset to utf8
$mail->SMTPAuth = true;// Enable SMTP authentication
$mail->SMTPSecure = 'tls';// Enable TLS encryption, `ssl` also accepted

$mail->Host = 'smtp.gmail.com';// Specify main and backup SMTP servers
$mail->Port = 587;// TCP port to connect to
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->isHTML(true);// Set email format to HTML

$mail->Username = 'heavendiarywebsite';// SMTP username
$mail->Password = 'HEAVENdiary03071999';// SMTP password


$mail->setFrom('heavendiarywebsite@gmail.com', 'Heaven Diary');//Your application NAME and EMAIL
$mail->Subject = 'Регистрация на Heaven-Diary'; //Message subject
$mail->isHTML(true);
$mail->Body = '<div style="padding:2em;display:flex;align-items:center;justify-content:center;background-color:#ff2a6b">
  <h3 style="font-family:Arial;color:#ffffff;">Регистрация на Heaven-Diary!</h3>
</div>
<div style="padding:3em;font-weight:lighter;font-family:Arial;background: repeating-linear-gradient(135deg, #fafafa, #fafafa 10px, #fff 10px, #fff 20px);">
  <p>Здравствуйте, '.$user_name.' '.$user_last.', благодарим вас за регистрацию на сайте, она почти завершена! Для того, чтобы ее завершить, нужно активировать ваш аккаунт просто <a href="'.$activation_url.'">перейдя по ссылке</a>. Оставайтесь с нами, чтобы увидеть развитие сайта, предлагайте новые идеи!</p>
  <p style="color:#ff2a6b;text-align:right">С наилучшими пожеланиями, Heaven-Diary</p>
</div>';

$mail->addAddress($user_email, 'Получатель');

$mail->send();

?>
