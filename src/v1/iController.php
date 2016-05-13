<?php

interface iController
{
    /**
     * Method returning the response to the current request.
     * @return Response response to current request
     */
    public function getResponse();
}