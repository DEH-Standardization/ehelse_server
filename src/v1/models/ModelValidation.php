<?php

/**
 */
class ModelValidation
{
    // Max length valid in database
    const NAME_MAX_LENGTH = 512;
    const TITLE_MAX_LENGTH = 512;
    const TEXT_MAX_LENGTH = 512;
    const DESCRIPTION_MAX_LENGTH = 2048;
    const COMMENT_MAX_LENGTH = 2048;
    const FIELD_VALUE_MAX_LENGTH = 2048;
    const URL_MAX_LENGTH = 512;
    const ABBREVIATION_MAX_LENGTH = 128;
    const DEADLINE_MAX_LENGTH = 1024;
    const INTERNAL_ID_MAX_LENGTH = 256;
    const HIS_NUMBER_MAX_LENGTH = 256;
    const EMAIL_MAX_LENGTH = 256;

    /**
     * Returns a his email, if email is longer than max length, the excess characters are removed
     * @param $email
     * @return string
     */
    public static function getValidEmail($email)
    {
        if ($email == null || !(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            return null;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        }
        return substr($email, 0, self::EMAIL_MAX_LENGTH);
    }

    /**
     * Returns a his number, if his number is longer than max length, the excess characters are removed
     * @param $his_number
     * @return string
     */
    public static function getValidHisNumber($his_number)
    {
        if ($his_number == null) {
            return null;
        }
        return substr($his_number, 0, self::HIS_NUMBER_MAX_LENGTH);
    }

    /**
     * Returns a internal id, if internal id is longer than max length, the excess characters are removed
     * @param $internal_id
     * @return string
     */
    public static function getValidInternalId($internal_id)
    {
        if ($internal_id == null) {
            return null;
        }
        return substr($internal_id, 0, self::INTERNAL_ID_MAX_LENGTH);
    }

    /**
     * Returns a deadline, if deadline is longer than max length, the excess characters are removed
     * @param $deadline
     * @return string
     */
    public static function getValidDeadline($deadline)
    {
        if ($deadline == null) {
            return null;
        }
        return substr($deadline, 0, self::DEADLINE_MAX_LENGTH);
    }


    /**
     * Returns a valid title, if title is longer than max length, the excess characters are removed
     * @param $title
     * @return string
     */
    public static function getValidTitle($title)
    {
        if ($title == null) {
            return null;
        }
        return substr($title, 0, self::TITLE_MAX_LENGTH);
    }

    /**
     * Returns a valid description, if description is longer than max length, the excess characters are removed
     * @param $description
     * @return string
     */
    public static function getValidDescription($description)
    {
        if ($description == null) {
            return null;
        }
        return substr($description, 0, self::DESCRIPTION_MAX_LENGTH);
    }

    /**
     * Returns a valid text, if text is longer than max length, the excess characters are removed
     * @param $text
     * @return null|string
     */
    public static function getValidText($text)
    {
        if ($text == null) {
            return null;
        }
        return substr($text, 0, self::TEXT_MAX_LENGTH);
    }

    /**
     * Returns a valid comment, if comment is longer than max length, the excess characters are removed
     * @param $comment
     * @return string
     */
    public static function getValidComment($comment)
    {
        if ($comment == null) {
            return null;
        }
        return substr($comment, 0, self::COMMENT_MAX_LENGTH);
    }

    /**
     * Return valid string, if string is longer than max length, the excess characters are removed
     * @param $value
     * @param $max_length
     * @return string
     */
    public static function getValidString($value, $max_length)
    {
        return substr($value, 0, $max_length);
    }


    /**
     * Returns a valid name, if name is longer than max length, the excess characters are removed
     * @param $name
     * @return string
     */
    public static function getValidName($name)
    {
        if ($name == null) {
            return null;
        }
        return substr($name, 0, self::NAME_MAX_LENGTH);
    }

    /**
     * Returns a valid field value, if field value is longer than max length, the excess characters are removed
     * @param $title
     * @return string
     */
    public static function getValidFieldValue($field_value)
    {
        if ($field_value == null) {
            return null;
        }
        return substr($field_value, 0, self::FIELD_VALUE_MAX_LENGTH);
    }

    /**
     * Returns a valid url, if url is longer than max length, the excess characters are removed
     * @param $url
     * @return null|string
     */
    public static function getValidURL($url)
    {
        if ($url == null) {
            return null;
        }
        return substr($url, 0, self::URL_MAX_LENGTH);
    }

    /**
     * Returns a valid abbreviation, if abbreviation is longer than max length, the excess characters are removed
     * @param $abbreviation
     * @return null|string
     */
    public static function getValidAbbreviation($abbreviation)
    {
        if ($abbreviation == null) {
            return null;
        }
        return substr($abbreviation, 0, self::URL_MAX_LENGTH);
    }

}