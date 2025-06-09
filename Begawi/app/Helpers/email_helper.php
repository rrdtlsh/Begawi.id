<?php
// app/Helpers/email_helper.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_email($toEmail, $toName, $subject, $body)
{
    $mail = new PHPMailer(true);
    $emailConfig = config('Email');

    try {
        //Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = $emailConfig->SMTPHost;
        $mail->SMTPAuth = true;
        $mail->Username = $emailConfig->SMTPUser;
        $mail->Password = $emailConfig->SMTPPass;
        $mail->SMTPSecure = $emailConfig->SMTPCrypto;
        $mail->Port = $emailConfig->SMTPPort;

        //Penerima
        $mail->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
        $mail->addAddress($toEmail, $toName);

        //Konten
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        log_message('error', "Email gagal terkirim. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}