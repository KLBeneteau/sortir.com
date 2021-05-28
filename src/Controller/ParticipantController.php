<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/admin/participant", name="participant_afficherPage")
     */
    public function afficherPage(ParticipantRepository $participantRepository): Response
    {
        $participantsList = $participantRepository->findAll();
        return $this->render('participant/gestionAdmin.html.twig', [
            'participantsList' => $participantsList
        ]);
    }

    /**
     * @Route("/admin/participant/supprimer", name="participant_delete")
     */
    public function delete(Request $request,
                           EntityManagerInterface $entityManager,
                           ParticipantRepository $participantRepository,
                           SortieRepository  $sortieRepository): Response
    {

       $participantAsupprimer = $participantRepository->findOneBy(['id' => $request->get('participant_id')]);

       //récupère la liste des sortie organisé par mon utilisateur a suprimer, pour les suprimer ensuite
       $listeSortieASupprimer = $sortieRepository->findByOrganisateur($participantAsupprimer);

       foreach ($listeSortieASupprimer as $sortie) {
           $entityManager->remove($sortie);
           $entityManager->flush();
       }

       $entityManager->remove($participantAsupprimer);
       $entityManager->flush();

       return $this->redirectToRoute('participant_afficherPage');
    }


}
