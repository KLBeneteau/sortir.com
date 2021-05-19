<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\CreerProfilType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/creerProfil", name="profil_creerProfil")
     */
    public function creerProfil(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, string $photoDir): Response
    {
        $profil = new Participant();
        $profilForm = $this->createForm(CreerProfilType::class, $profil);

        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()) {
            if ($photo = $profilForm['photo']->getData()) {
                $photoProfil = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($photoDir, $photoProfil);
                    $profil->setPhotoProfil($photoProfil);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
            }
            $entityManager->persist($profil);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été créé');
            return $this->redirectToRoute('main_accueil', ['id' => $profil->getId()]);
        }
        return $this->render('profil/creer-profil.html.twig', [
            'profilForm' => $profilForm->createView(),
            'profil' => $profil
        ]);


    }
    /**
     * @Route("profil/detail/{id}", name="profil_detail")
     */
    public function detail(int $id, ParticipantRepository $participantRepository) : Response {

        $user = $participantRepository->find($id);


        return $this->render('profil/detail.html.twig', compact('user')) ;

    }

}
