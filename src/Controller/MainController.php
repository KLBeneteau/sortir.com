<?php


namespace App\Controller;

use App\Form\FiltreAccueilType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="main_accueil")
     */
    public function accueil(Request $request, SortieRepository $sortieRepository)
    {

        $sortiesForm = $this->createForm(FiltreAccueilType::class);
        return $this->render('main/accueil.html.twig', [
            'sortiesForm' => $sortiesForm->createView()
        ]);

        $sortiesList = $sortieRepository->recherchesSorties();
        return $this->render('main/accueil.html.twig', [
           'sortiesList' => $sortiesList
        ]);



    }
}