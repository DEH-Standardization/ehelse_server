<?php

interface iResponse
{
    public function getBody();

    public function getContentType();

    public function getStatusCode();
}