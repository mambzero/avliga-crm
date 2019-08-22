<?php


namespace AppBundle\Service;


interface ActivityServiceInterface
{
    /**
     * Returns activity log data.
     * Orders, Reports and Returns.
     *
     * @return array
     */
    public function log(): array;
}