<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
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
    public function delete(Request $request, EntityManagerInterface $entityManager, ParticipantRepository $participantRepository): Response
    {

       $participant = $participantRepository->findOneBy(['id' => $request->get('participant_id')]);
       $entityManager->remove($participant);
       $entityManager->flush();

       return $this->redirectToRoute('gestion');
    }
}
