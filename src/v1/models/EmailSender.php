<?php

class EmailSender
{
    const REGISTER_EMAIL = 1;
    const RESET_PASSWORD_EMAIL = 2;
    const FROM = "From: <mime@refkat.eu> \r\n";

    /**
     * Sends email to email address $to by email type
     * @param $to
     * @param $password
     * @param $email_type
     * @return bool
     */
    public static function sendEmail($to, $password, $email_type)
    {
        switch ($email_type) {
            case 1:
                return self::sendRegisterEmail($to, $password);
            case 2:
                return self::sendResetPasswordEmail($to, $password);
            default:
                return false;
        }
    }

    /**
     * Sends reset password email
     * @param $to
     * @param $password
     * @return bool
     */
    private static function sendResetPasswordEmail($to, $password)
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
            $is_sent = self::send($to, $subject, $message);
        }
        return $is_sent;
    }

    /**
     * Sends register email
     * @param $to
     * @param $password
     * @return bool
     */
    private static function sendRegisterEmail($to, $password)
    {
        $is_sent = false;
        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $subject = 'Registrert ny bruker hos e-helse';
            $message = "<html>
            <head><title>Registrert ny bruker</title></head>
            <body>
                <h3>Registrert ny bruker</h3>
                <p>Din -epsostadresse ($to) er registrert hos e-helse.</p>
                <p>Midlertidig passordet er satt til:</p>
                <h2>$password</h2>
                <p>Vennligst bytt passord ved første innlogging.</p>
            </body></html>";
            $is_sent = self::send($to, $subject, $message);
        }
        return $is_sent;
    }

    /**
     * Sends teh email
     * @param $to
     * @param $subject
     * @param $message
     * @return bool
     */
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