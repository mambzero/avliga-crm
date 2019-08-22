<?php


namespace AppBundle\Service;


use AppBundle\Repository\ActivityRepositoryInterface;

class ActivityService implements ActivityServiceInterface
{
    private $activityRepository;

    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Returns activity log data.
     * Orders, Reports and Returns.
     *
     * @return array
     */
    public function log(): array
    {
        return $this->activityRepository->getLogData();
    }
}