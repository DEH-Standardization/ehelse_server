<?php

class User
{
    const MAX_LENGTH_NAME = 128;
    const REQUIRED_POST_FIELDS = ['name', 'email'];
    const SQL_INSERT_STATEMENT = "INSERT INTO user VALUES (?,?,?,?,?);";
    private
        $id,
        $name,
        $profile_image,
        $email,
        $password_hash;

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
        return array(
            $this->id,
            $this->name,
            $this->profile_image,
            $this->email,
            $this->password_hash
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
            null,
            $json['name'],
            $json['email'],
            $json['profileImage'],
            null
        );
    }
}