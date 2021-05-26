<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieTempoController extends AbstractController
{
    /**
     * @Route("/sortie/afficher/{id_participant}/{id_sortie}", name="sortie_afficher")
     */
    public function inscription(int $id_participant, int $id_sortie, EntityManagerInterface $entityManagerInterface, ParticipantRepository $participantRepository, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id_sortie);
        $participant = $participantRepository->find($id_participant);
        if (!$participant && !$sortie){
            $participant->addInscription($sortie);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Félicication ! vous êtes inscrit à cette sortie');
        }
        return $this->render('sortie/afficher.html.twig', [
            'sortie'=>$sortie,
        ]);
    }
    /**
     * @Route("/sortie/afficher/{id_participant}/{id_sortie}", name="sortie_afficher")
     */
    public function desinscription(int $id_participant, int $id_sortie, EntityManagerInterface $entityManagerInterface, ParticipantRepository $participantRepository, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id_sortie);
        $participant = $participantRepository->find($id_participant);
        if ($participant && $sortie){
            $participant->removeInscription($sortie);
            $entityManagerInterface->remove($sortie);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Votre désinscription à la sortie à bien été prise en compte');
        }
        return $this->render('sortie/afficher.html.twig', [
            'sortie'=>$sortie,
        ]);
    }

}
