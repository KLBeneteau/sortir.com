<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionController extends AbstractController
{
    /**
     * @Route("/admin/gestion", name="gestion")
     */
    public function gestion(ParticipantRepository $participantRepository): Response
    {
        $participantsList = $participantRepository->findAll();
        return $this->render('gestion/gestion.html.twig', [
            'participantsList' => $participantsList
        ]);
    }

    /**
     * @Route("/admin/gestion/supprimer", name="participant_delete")
     */
    public function delete(Request $request,
                           EntityManagerInterface $entityManager,
                           ParticipantRepository $participantRepository,
                           SortieRepository  $sortieRepository): Response
    {

       $participantAsupprimer = $participantRepository->findOneBy(['id' => $request->get('participant_id')]);

       //récupère la liste des sortie organisé par mon utilisateur a suprimer, pour les suprimer ensuite
       $listeSortieASupprimer = $sortieRepository->findByOrganisateur($participantAsupprimer);

       //traitement: suprimer toutes les sorties récupérées.

        return $this->render('gestion/gestion.html.twig', [
            'listeSortieASupprimer' => $listeSortieASupprimer
        ]);
       $entityManager->remove($participantAsupprimer);
       $entityManager->flush();

       return $this->redirectToRoute('gestion');
    }


}
