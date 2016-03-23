<?php

class User
{
    const MAX_LENGTH_FIRST_NAME = 64;
    const MAX_LENGTH_LAST_NAME = 64;
    private $id, $first_name, $last_name, $password_hash, $email, $profile_image;

    public function __construct($id, $first_name, $last_name, $email, $profile_image, $password_hash)
    {
        $this->id=$id;
        $this->first_name=$first_name;
        $this->last_name=$last_name;
        $this->email=$email;
        $this->profile_image=$profile_image;
    }

    public function toArray()
    {
        return array(
            "id" => $this->id,
            "firstName" => $this->first_name,
            "lastName" => $this->last_name,
            "email" => $this->email,
            "profileImage" => $this->profile_image
        );
    }

    public function setFirstName($first_name)
    {
        return $this->setStringProperty(
            $field="first_name",
            $value=$first_name,
            $max_length=User::MAX_LENGTH_FIRST_NAME,
            $error_message_field_name="First name");
    }

    public function setLastName($last_name)
    {
        return $this->setStringProperty(
            $field="last_name",
            $value=$last_name,
            $max_length=User::MAX_LENGTH_LAST_NAME,
            $error_message_field_name="Last name");
    }

    public function setEmail($email)
    {
        //todo
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
            $db_array['first_name'],
            $db_array['last_name'],
            $db_array['email'],
            $db_array['profile_image'],
            $db_array['password_hash']
        );
    }

    public static function fromJSON($json)
    {
        return new User(
            null,
            $json['firstName'],
            $json['lastName'],
            $json['email'],
            $json['profileImage'],
            null
        );
    }
}