<?php

class User
{
    const MAX_LENGTH_NAME = 128;
    const REQUIRED_POST_FIELDS = ['name', 'email'];
    const REQUIRED_PUT_FIELDS = ['name', 'email'];
    const REQUIRED_PASSWORD_PUT_FIELD = ['password'];
    const SQL_INSERT_STATEMENT = "INSERT INTO user(name,profile_image,email) VALUES (:name,:profile_image,:email);";
    const SQL_UPDATE_STATEMENT = "UPDATE user SET name=:name,profile_image=:profile_image,email=:email WHERE id = :id;";
    const SQL_UPDATE_PASSWORD_STATEMENT = "UPDATE user SET password_hash=:password_hash  WHERE id = :id;";
    const SQL_GET_USER_BY_EMAIL = "SELECT * FROM user WHERE email=:email";
    private
        $id,
        $name,
        $profile_image,
        $email,
        $password_hash;

    public static function login($user)
    {
        $GLOBALS["CURRENT_USER"] = $user;
    }

    public static function byEmail($email)
    {
        $mapper = new UserDBMapper();
        $db_array = $mapper->getByEmail($email);
        $user = User::fromDBArray($db_array);
        return $user;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPasswordHash()
    {
        return $this->password_hash;
    }


    public function __construct($id, $name, $email, $profile_image, $password_hash)
    {
        $this->id=$id;
        $this->name = $name;
        $this->email=$email;
        $this->password_hash=$password_hash;
        $this->profile_image=$profile_image;
    }

    public function toArray()
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "profileImage" => $this->profile_image
        );
    }
    public function toDBArray()
    {
        $db_array = array(
            ":name" => $this->name,
            ":profile_image" => $this->profile_image,
            ":email" => $this->email
        );
        if($this->id){
            $db_array[":id"] = $this->id;
        }
        return $db_array;
    }

    public function toResetPasswordDBArray(){
        if(! $this->password_hash){
            die("Missing password");
        }
        return array(
            ":id" => $this->id,
            ":password_hash" => $this->password_hash
        );
    }

    public function setName($name)
    {
        return $this->setStringProperty(
            $field="name",
            $value=$name,
            $max_length=User::MAX_LENGTH_NAME,
            $error_message_field_name="Name");
    }


    public function setEmail($email)
    {
        return $this->setStringProperty(
            $field="email",
            $value=$email,
            $max_length=User::MAX_LENGTH_NAME,
            $error_message_field_name="Email");
    }

    protected function setStringProperty($field, $value, $max_length, $error_massage_field_name)
    {
        $message = null;
        if (strlen($value) > $max_length) {
            $value = ModelValidation::getValidString($value, $max_length);
            $message = "{$error_massage_field_name} is too long. {$error_massage_field_name} set to: {$value}";
        }
        $this[$field] = $value;
        return $message;
    }

    public function toJSON()
    {
        require json_encode(
            $this->toArray(),
            JSON_PRETTY_PRINT);
    }


    public static function fromResetPasswordQuery($id, $json)
    {
        $password = $json['password'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        return new User(
            $id,
            null,
            null,
            null,
            $password_hash
        );
    }


    public static function fromDBArray($db_array)
    {
        return new User(
            $db_array['id'],
            $db_array['name'],
            $db_array['email'],
            $db_array['profile_image'],
            $db_array['password_hash']
        );
    }

    public static function fromJSON($json)
    {
        return new User(
            $json['id'],
            $json['name'],
            $json['email'],
            $json['profileImage'],
            null
        );
    }

    public static function authenticate($email, $password)
    {
        $response = null;
        $user = User::byEmail($email);
        if(password_verify($password, $user->getPasswordHash())){
            $response = $user;
        }
        return $response;
    }
}