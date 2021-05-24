<?php


namespace App\Controller;

use App\Form\FiltreAccueilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="main_accueil")
     */
    public function accueil(Request $request)
    {

        $sortiesForm = $this->createForm(FiltreAccueilType::class);
        return $this->render('main/accueil.html.twig', [
            'sortiesForm' => $sortiesForm->createView()
        ]);






        //$donnees = new ();
        //$rechercheDonnees = $this->createForm(::class, $donnees);
        //return $this->render('main/accueil.html.twig',[
         //  'rechercheDonneesForm' => $rechercheDonnees->createView()
       // ]);




       // $campusFiltre = new Campus();
        //$campusList = $campusRepository->findAll($request->get('filtre'));
        //$campusBisForm = $this->createForm(CampusBisType::Class,$campusFiltre);
        //$campusBisForm->handleRequest($request);
        //return $this->render('main/accueil.html.twig',[
           // 'campusList' => $campusList,
           // 'campusBisForm' => $campusBisForm->createView()

        //]);
    }
}