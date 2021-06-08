<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WorkShopController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @IsGranted("ROLE_OUVRIER")
 */
class WorkShopController extends AbstractController
{
    /**
     * @Route("/workshop", name="work_shop")
     */
    public function index(): Response
    {
        return $this->render('work_shop/index.html.twig', [
            'controller_name' => 'WorkShopController',
        ]);
    }
}
