<?php


namespace App\Controller;

use App\Form\FiltreAccueilType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="main_accueil")
     */
    public function accueil(Request $request, SortieRepository $sortieRepository) : Response {

// sans filtres
           $sortiesList = $sortieRepository->recherchesSorties();


           $sortiesForm = $this->createForm(FiltreAccueilType::class);
        if($sortiesForm->handleRequest($request)->isSubmitted() && $sortiesForm->isValid()) {
            $criteria = $sortiesForm->getData(); //entre du formulaire

            dd($criteria);
            $sorties = $sortieRepository->accueil($criteria);
        }



        return $this->render('main/accueil.html.twig', [
            'sortiesForm' => $sortiesForm->createView(),
            'sortiesList' => $sortiesList
        ]);





    }
}