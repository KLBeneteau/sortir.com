<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    /**
     * @Route("/sortie/creer", name="sortie_creer")
     */
    public function creer(Request $request, EntityManagerInterface $entityManager, LieuRepository $lieuRepository): Response
    {
        $user = $this->getUser();

        $sortie = new Sortie();
        $sortie->setLieu($lieuRepository->findOneBy([],['id'=>'desc']));
        $sortieForm = $this->createForm(SortieType::class, $sortie);


        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $etatRepository = $entityManager ->getRepository(Etat::class);

            $sortie->setCampus($user->getCampus());

            if ($request->get('submitAction') == 'enregistrer') {
                $sortie->setEtat($etat = $etatRepository ->findOneBy(["libelle"=>"Créée"]));
                $this->addFlash('warning',"Ta sortie est enregistrée ! Pense à la publier ;)");
            }else {
                    $sortie->setEtat($etat = $etatRepository ->findOneBy(["libelle"=>"Ouverte"]));
                    $this->addFlash('success', "Ta sortie a bien été ajoutée !");
                }


            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_creer');
        }

        return $this->render('sortie/creer.html.twig', [
            'sortieForm'=>$sortieForm->createView(),
            'user'=>$user,
            'sortie'=>$sortie
        ]);
    }
}
