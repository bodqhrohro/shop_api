<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="commodities_list")
     */
    public function indexAction(Request $request)
    {
        $doctrine = $this->getDoctrine();

        $repo = $doctrine->getRepository('AppBundle:Commodity');

        $commodities = $repo->findAll();

        return $this->json($commodities);
    }
}
