<?php

namespace App\Controller;

use App\Entity\OrderPurchase;
use App\Entity\Piece;
use App\Form\OrderPurchaseType;
use App\Repository\OrderPurchaseRepository;
use App\Service\AddingQuantities;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_COMMERCIAL")
 **/
class OrderPurchaseController extends AbstractController
{
    protected $em;
    private $addingQuantities;
    private $pdf;

    public function __construct(EntityManagerInterface $entityManager,AddingQuantities $addingQuantities,Pdf $pdf)
    {
        $this->pdf = $pdf;
        $this->addingQuantities = $addingQuantities;
        $this->em = $entityManager;
    }

    /**
     * @Route("/orderPurchase/add", name="orderpurchase.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $orderPurchase = new OrderPurchase();
        $em = $this->em;
        $form = $this->createForm(OrderPurchaseType::class, $orderPurchase, [
            'action' => $this->generateUrl("orderpurchase.add")
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() && !$request->isXmlHttpRequest()){
            $this->addingQuantities->AddQuantityWhenTwoPieceIdentics($orderPurchase);
            foreach ($orderPurchase->getOrderPurchaseLines() as $orderLine) {
                $orderLine->setPriceCatalog($orderLine->getPiece()->getPriceCatalogue());
            }
            $em->persist($orderPurchase);
            $em->flush();
            $this->addFlash('success_orderpuchase_add', "La commande d'achat " . $orderPurchase->getLibelle() . " a bien été créée avec succès.");
            return $this->redirectToRoute('home');
        }

        return $this->render('order_purchase/add.html.twig', [
            'orderPurchase' => $orderPurchase,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/orderPurchase/{id}", name="orderpurchase.show")
     * @param OrderPurchase $orderPurchase
     * @param OrderPurchaseRepository $orderPurchaseRepository
     * @return Response
     */
    public function show(OrderPurchase $orderPurchase,OrderPurchaseRepository $orderPurchaseRepository): Response
    {
        $orderPurchase = $orderPurchaseRepository->findOneBy(['id' => $orderPurchase->getId()]);
        $totalLinePrice = [];

        foreach($orderPurchase->getOrderPurchaseLines()->toArray() as $orderPurchaseLine){
                array_push($totalLinePrice,$orderPurchaseLine->getPriceCatalog() * $orderPurchaseLine->getQuantity());
        }
        $totalPrice = array_sum($totalLinePrice);
        return $this->render('order_purchase/show.html.twig', [
            'totalPrice' => $totalPrice,
            'orderPurchase' => $orderPurchase
        ]);
    }

    /**
     * @Route("/price/{id}", name="price", methods="GET")
     * @param Piece $piece
     * @return JsonResponse
     */
    public function getPrice(Piece $piece)
    {
        return new JsonResponse($piece->getPriceCatalogue());
    }

    /**
     * @Route("/orderPurchase/edit/{id}", name="orderpurchase.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param OrderPurchaseRepository $orderPurchaseRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,OrderPurchaseRepository $orderPurchaseRepository)
    {
        $em = $this->em;
        $orderPurchase = $orderPurchaseRepository->find($id);
        $form = $this->createForm(OrderPurchaseType::class, $orderPurchase);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() && !$request->isXmlHttpRequest()){
            $this->addingQuantities->AddQuantityWhenTwoPieceIdentics($orderPurchase);
            foreach ($orderPurchase->getOrderPurchaseLines() as $orderLine) {
                $orderLine->setPriceCatalog($orderLine->getPiece()->getPriceCatalogue());
            }
            if($orderPurchase->getDateDeliveryReal() != null){
                foreach($orderPurchase->getOrderPurchaseLines() as $orderPurchaseLine){
                    $orderPurchaseLineQuantity = $orderPurchaseLine->getQuantity();
                    $pieceQuantity = $orderPurchaseLine->getPiece()->getQuantity();
                    $orderPurchaseLine->getPiece()->setQuantity($pieceQuantity + $orderPurchaseLineQuantity);
                }
            }
            $em->flush();
            $this->addFlash('success_range_edit', "La commande d'achat ". $orderPurchase->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('home');
        };

        return $this->render('order_purchase/edit.html.twig', [
            'orderPurchase' => $orderPurchase,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("orderPurchase/delete/{id}", name="orderpurchase.delete")
     * @param $id
     * @param Request $request
     * @param OrderPurchaseRepository $orderPurchaseRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, OrderPurchaseRepository $orderPurchaseRepository)
    {
        $em = $this->em;
        $orderPurchase = $orderPurchaseRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $orderPurchase->getId(), $request->request->get('_token'))){
            $em->remove($orderPurchase);
            $em->flush();
            $this->addFlash('success_range_delete', "La commmande d'achat " . $orderPurchase->getLibelle() . " a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("orderPurchase/pdf/{id}",  name="pdf")
     * @param OrderPurchase $orderPurchase
     * @return Response
     */
    public function pdfAction(OrderPurchase $orderPurchase)
    {
        $snappy = $this->pdf;
        $snappy->setOption('no-outline', true);
        $snappy->setOption('page-size','LETTER');
        $snappy->setOption('encoding', 'UTF-8');
        $totalLinePrice = [];

        foreach($orderPurchase->getOrderPurchaseLines()->toArray() as $orderPurchaseLine){
            array_push($totalLinePrice,$orderPurchaseLine->getPriceCatalog() * $orderPurchaseLine->getQuantity());
        }

        $totalPrice = array_sum($totalLinePrice);
        $html = $this->renderView('order_purchase/pdf.html.twig', array(
            'id' => $orderPurchase->getId(),
            'orderPurchase' => $orderPurchase,
            'totalPrice' => $totalPrice,
        ));

        $filename = $orderPurchase->getLibelle() . 'PDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }

    /**
     * @Route("/orderPurchaseByMonth/{month}",  name="orderPurchaseByMonth")
     * @param int $month
     * @return array
     */
    public function getAllOrderPurchaseByMonth(int $month){
        $orderPurchasesByMonth = [];
        array_push($orderPurchasesByMonth,$this->getDoctrine()->getRepository('App:OrderPurchase')->findAllOrderPurchaseByMonth($month));
        return $orderPurchasesByMonth;
    }


    /**
     * @Route("/export",  name="export")
     */
    public function export()
    {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () {
            $orderPurchases = $this->getDoctrine()->getRepository('App:OrderPurchase')->findAllOrderPurchaseByMonth();

            $spreadsheet = new Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setTitle('Liste des commandes d\'achat');

            $styleHeaderArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ];

            $styleDataArray = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    ],
                ],
            ];

            $sheet->getCell('A1')->setValue('Numéro')->getStyle()->applyFromArray($styleHeaderArray);
            $sheet->getCell('B1')->setValue('Libellé')->getStyle()->applyFromArray($styleHeaderArray);
            $sheet->getCell('C1')->setValue('Date de livraison prévue')->getStyle()->applyFromArray($styleHeaderArray);
            $sheet->getCell('D1')->setValue('Date de livraison réelle')->getStyle()->applyFromArray($styleHeaderArray);
            $sheet->getCell('E1')->setValue('Fournisseur')->getStyle()->applyFromArray($styleHeaderArray);

            $i = 1;
            foreach($orderPurchases as $orderPurchase){
                $i++;
                $sheet->setCellValue('A'.$i,$orderPurchase->getId())->getStyle('A'.$i)->applyFromArray($styleDataArray);
                $sheet->setCellValue('B'.$i,$orderPurchase->getLibelle())->getStyle('B'.$i)->applyFromArray($styleDataArray);
                $sheet->setCellValue('C'.$i,$orderPurchase->getDateDeliveryPredicted())->getStyle('C'.$i)->applyFromArray($styleDataArray);
                $sheet->setCellValue('D'.$i,$orderPurchase->getDateDeliveryReal() ?? "Vide")->getStyle('D'.$i)->applyFromArray($styleDataArray);
                $sheet->setCellValue('E'.$i,$orderPurchase->getProvider()->getLibelle())->getStyle('E'.$i)->applyFromArray($styleDataArray);
            }


            $writer =  new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="order_purchase.xlsx"');

        return $streamedResponse->send();
    }
}
