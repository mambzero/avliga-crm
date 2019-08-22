<?php

namespace AppBundle\Controller;

use AppBundle\Service\ActivityServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends Controller
{
    private $activityService;

    public function __construct(ActivityServiceInterface $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * @Route("/activity/log", name="activity_log", methods={"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function log()
    {
        return $this->render('default/log.html.twig', [
            'data' => $this->activityService->log()
        ]);
    }
}
