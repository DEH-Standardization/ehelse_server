<?php

/**
 *
 */
abstract class StandardDBMapper extends DBMapper
{
    /**
     * StandardDBMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getSubtopicsByTopicId($id)
    {
        return $this->getSubtopicsByTopicIdDB($id);
    }
}