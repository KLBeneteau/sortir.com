<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie_list")
     */
    public function list(): Response
    {
        return $this->render('sortie/list.html.twig', [

        ]);
    }

    /**
     * @Route("/sortie/creer", name="sortie_create")
     */
    public function create(): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        return $this->render('sortie/create.html.twig', [
            'sortieForm'=>$sortieForm->createView()
        ]);
    }
}
