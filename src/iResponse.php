<?php

interface iResponse
{
    /**
     * Method returning the body of the response as a string
     * @return string
     */
    public function getBody();

    /**
     * Method returning the content type of the response as a string
     * @return string
     */
    public function getContentType();

    /**
     * Method returning the status code of the response as an integer
     * @return integer
     */
    public function getResponseCode();
}