<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        return $this->render('default/dashboard.html.twig');
    }
}
