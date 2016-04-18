<?php

/**
 */
class ModelValidation
{
    // Max length valid in database
    const NAME_MAX_LENGTH = 128;
    const TITLE_MAX_LENGTH = 128;
    const TEXT_MAX_LENGTH = 128;
    const DESCRIPTION_MAX_LENGTH = 1024;
    const COMMENT_MAX_LENGTH = 1024;
    const FIELD_VALUE_MAX_LENGTH = 1024;

    /**
     * Returns a valid title, if title is longer than max length, the excess characters are removed
     * @param $title
     * @return string
     */

    public static function getValidTitle($title)
    {
        return substr($title, 0, self::TITLE_MAX_LENGTH);
    }

    /**
     * Returns a valid description, if title is longer than max length, the excess characters are removed
     * @param $description
     * @return string
     */
    public static function getValidDescription($description)
    {
        return substr($description, 0, self::DESCRIPTION_MAX_LENGTH);
    }

    public static function getValidText($text)
    {
        return substr($text, 0, self::TEXT_MAX_LENGTH);
    }

    /**
     * Returns a valid comment, if title is longer than max length, the excess characters are removed
     * @param $comment
     * @return string
     */
    public static function getValidComment($comment)
    {
        return substr($comment, 0, self::COMMENT_MAX_LENGTH);
    }

    public static function getValidString($value, $max_length)
    {
        return substr($value, 0, $max_length);
    }
    

    /**
     * Returns a valid name, if title is longer than max length, the excess characters are removed
     * @param $name
     * @return string
     */
    public static function getValidName($name)
    {
        return substr($name, 0, self::NAME_MAX_LENGTH);
    }

    /**
     * Returns a valid title, if title is longer than max length, the excess characters are removed
     * @param $title
     * @return string
     */
    public static function getValidFieldValue($field_value)
    {
        return substr($field_value, 0, self::FIELD_VALUE_MAX_LENGTH);
    }

}