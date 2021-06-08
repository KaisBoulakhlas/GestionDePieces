<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @IsGranted("ROLE_COMMERCIAL")
 */
class CommercialController extends AbstractController
{
    /**
     * @Route("/commercial", name="commercial")
     */
    public function index(): Response
    {
        return $this->render('commercial/index.html.twig', [
            'controller_name' => 'CommercialController',
        ]);
    }
}
