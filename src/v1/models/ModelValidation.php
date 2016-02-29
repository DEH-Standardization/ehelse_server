<?php

/**
 */
class ModelValidation
{
    public static function getValidTitle($title)
    {
        return substr($title, 0, self::getTitleMaxLength());
    }

    public static function getValidDescription($description)
    {

        return substr($description, 0, self::getDescriptionMaxLength());
    }

    public static function getValidContent($content)
    {
        return substr($content, 0, self::getContentMaxLength());
    }

    public static function getTitleMaxLength()
    {
        return 128;
    }

    public static function getDescriptionMaxLength()
    {
        return 1024;
    }

    public static function getContentMaxLength()
    {
        return 4096;
    }
}