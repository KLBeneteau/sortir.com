<?php

namespace App\Controller;

use App\Form\CreationProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/creer-profil", name="creer-profil")
     */
    public function creerProfil(Request $request , EntityManagerInterface $entityManager): Response
    {
        $profil= new Profil();
        $profilForm = $this->createForm(CreationProfilType::class, $profil);

        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()){
            $entityManager->persist($profil)
                ->flush();
        }
        return $this->render('profil/creer-profil.html.twig', [
            'profilForm'=> $profilForm->createView()
        ]);
    }

}
