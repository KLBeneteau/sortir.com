<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted()){
            $etatRepository = $entityManager ->getRepository(Etat::class);

            if ($request->get('submitAction') == 'enregistrer') {
                $sortie->setEtat($etat = $etatRepository ->find('3'));
            }else {
                if ($request->get('submitAction') == 'publier') {
                    $sortie->setEtat($etat = $etatRepository ->find('4'));
                }
            }


            $entityManager->persist($sortie);
            $entityManager->flush();


            $this->addFlash('success','Sortie ajoutée !');
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('sortie/create.html.twig', [
            'sortieForm'=>$sortieForm->createView()
        ]);
    }
}
