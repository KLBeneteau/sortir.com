<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $sortie->setOrganisateur($this->getUser());
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
            'sortie'=>$sortie,
            'modification'=>false
        ]);
    }

    /**
     * @Route("sortie/afficher/{id_sortie}", name="sortie_afficher")
     */
    public function afficher(
        int $id_sortie,
        SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->findOneBy(['id'=>$id_sortie]);

        return $this->render('sortie/afficher.html.twig', [
            'sortie'=>$sortie,
            'participants'=> $sortie->getParticipants()
        ]);
    }

    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modifier")
     */
    public function modifier(int $id,Request $request,SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager) {

        $sortie = $sortieRepository->find($id);

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            if ($request->get('submitAction') == 'enregistrer') {
                $sortie->setEtat($etatRepository ->findOneBy(["libelle"=>"Créée"]));
                $this->addFlash('warning',"Ta sortie est modifiée ! Pense à la publier ;)");
            }else {
                $sortie->setEtat($etatRepository ->findOneBy(["libelle"=>"Ouverte"]));
                $this->addFlash('success', "Ta sortie a bien été modifiée !");
            }

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_modifier',compact('id'));
        }

        return $this->render('sortie/creer.html.twig', [
            'sortieForm'=>$sortieForm->createView(),
            'sortie'=>$sortie,
            'modification'=>true
        ]) ;
    }

    /**
     * @Route("/sortie/supprimer", name="sortie_supprimer")
     */
    public function supprimer(Request $request,SortieRepository $sortieRepository, EntityManagerInterface $entityManager): RedirectResponse
    {

        $sortie = $sortieRepository->findOneBy(['id' => $request->get('sortie_id')]);
        $entityManager->remove($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('main_accueil') ;

    }
    /**
     * @Route("/sortie/inscription/{id_participant}/{id_sortie}", name="sortie_inscription")
     */
    public function inscription(
        int $id_participant,
        int $id_sortie,
        EntityManagerInterface $entityManagerInterface,
        ParticipantRepository $participantRepository,
        SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id_sortie);
        $participant = $participantRepository->find($id_participant);
        if ($participant && $sortie){
            $participant->addInscription($sortie);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Félicication ! vous êtes inscrit à cette sortie');
        }
        return $this->redirectToRoute('sortie_afficher', ['id_sortie'=>$id_sortie]);
    }
    /**
     * @Route("/sortie/desinscription/{id_participant}/{id_sortie}", name="sortie_desinscription")
     */
    public function desinscription(
        int $id_participant,
        int $id_sortie,
        EntityManagerInterface $entityManagerInterface,
        ParticipantRepository $participantRepository,
        SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id_sortie);
        $participant = $participantRepository->find($id_participant);
        if ($participant && $sortie){
            $participant->removeInscription($sortie);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Votre désinscription à la sortie à bien été prise en compte');
        }
        return $this->redirectToRoute('sortie_afficher', ['id_sortie'=>$id_sortie]);

    /**
     * @Route("/sortie/annuler/{id}", name="sortie_annuler")
     */

    public function annuler(int $id, SortieRepository $sortieRepository) : Response {

        $sortie = $sortieRepository->find($id);

        if(!$sortie){
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
        }

        return $this->render('sortie/annuler.html.twig', [
            'sortie'=>$sortie,

        ]) ;
    }
}
