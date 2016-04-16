<?php

class EmailSender
{
    const FROM = "From: <noreplay@ehelseEditor.no> \r\n";

    public static function sendResetPasswordEmail($to, $password)
    {
        $is_sent = false;
        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $subject = 'Tilbakestilling av passord';
            $message = "<html>
            <head><title>Glemt passord</title></head>
            <body>
                <h3>Glemt passord</h3>
                <p>Passordet for $to har blitt satt til:</p>
                <h2>$password</h2>
            </body></html>";
            $is_sent = self::send($to,$subject, $message);
        }
        return $is_sent;
    }

    private static function send($to, $subject, $message)
    {
        $is_sent = false;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= EmailSender::FROM;

        try {
            mail($to, $subject, $message, $headers);
            $is_sent = true;
        } catch (Exception $e) {
            print_r($e);
        }
        return $is_sent;
    }

}