<?php

require_once __DIR__.'/../../models/EmailSender.php';
class ResetPasswordController extends ResponseController
{
    const PASSWORD_LENGTH = 12;
    const RESET_PASSWORD_ACCEPTED_MESSAGE = 'Password reset request received.';

    /**
     * ResetPasswordController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->path=$path;
        $this->body=$body;
        $this->method=$method;
        if( count($this->path) != 0){
            $this->controller = new ErrorController(new InvalidPathError());
        }
    }

    /**
     * Setting new password for user, and sending email with new password
     * @return DBError|ErrorResponse|null|Response
     */
    protected function create()
    {
       return self::setNewPassword($this->body, EmailSender::RESET_PASSWORD_EMAIL);
    }

    public static function setNewPassword($json, $email_type)
    {
        $response = null;
        $missing_fields = UserController::validateJSONFormat($json, User::REQUIRED_PASSWORD_RESET_FIELD);

        if (!$missing_fields) {
            $user_mapper = new UserDBMapper();
            $email = $json['email'];
            $user = User::fromDBArray($user_mapper->getByEmail($email));

            $password = ResetPasswordController::getRandomString(ResetPasswordController::PASSWORD_LENGTH);
            $json['password'] = $password;

            $id = $user->getId();

            $reset_password_user = User::fromResetPasswordQuery($id, $json);

            if($reset_password_user) {
                $db_response = $user_mapper->resetPassword($reset_password_user);

                if ($db_response instanceof DBError) {
                    $response = new ErrorResponse($db_response);
                } else {
                    $reset_password_user = $user_mapper->getById($id);
                    if ($reset_password_user) {
                        EmailSender::sendEmail($email, $password, $email_type);   // Sending Email notification
                        $response = new Response(json_encode(   // TODO: should this be moved? Might make more sense to use a own class.
                            array('message' => ResetPasswordController::RESET_PASSWORD_ACCEPTED_MESSAGE),
                            JSON_PRETTY_PRINT), Response::STATUS_CODE_ACCEPTED
                        );
                    } else {
                        $response = new ErrorResponse(new NotFoundError());
                    }
                }
            }
            return $response;
        }
        return new Response($response);
    }

    /**
     * Returns random string
     * @param $length
     * @return string
     */
    private static function getRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }

    protected function getAll()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    protected function update()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    protected function get()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }
}