<?php

namespace App\Controller;

use App\Entity\Range;
use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    /**
     * @Route("range/{id}/operations", name="range.operations.index")
     * @param OperationRepository $operationRepository
     * @return Response
     */
    public function index(OperationRepository $operationRepository) : Response
    {
        $datas =  $operationRepository->findAll();
        return $this->render('operation/index.html.twig/', [
            'operations' => $datas
        ]);
    }
}
