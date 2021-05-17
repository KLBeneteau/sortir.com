<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Sortie", name="Sortie_")
 */
class SortieController extends AbstractController
{

    /**
     * @Route("Detail/{id}", name="Detail")
     */
    public function detail() : Response {

        return $this->render('sortie/detail.html.twig') ;

    }

    /**
     * @Route("Ajouter", name="Ajouter")
     */
    public function ajouter() : Response {

        return $this->render('sortie/ajouter.html.twig') ;

    }

    /**
     * @Route("Modifier", name="Modifier")
     */
    public function modifier() : Response {

        return $this->render('sortie/ajouter.html.twig') ;
        //Appelle le meme fichier twig que ajouter  !

    }

    /**
     * @Route("Annuler", name="Annuler")
     */
    public function annuler() : Response {

        return $this->render('sortie/annuler.html.twig') ;

    }



}