<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\CreerProfilType;
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
     * @Route("/profil/gerer", name="profil_gerer")
     */
    public function gerer(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, string $photoDir): Response
    {
        $profil = new Participant();
        $profilForm = $this->createForm(CreerProfilType::class, $profil);

        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()) {
            $profil->setPassword(
                $passwordEncoder->encodePassword(
                    $profil,
                    $profilForm->get('plainPassword')->getData()
                )
            );
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
            return $this->redirectToRoute('profil_editer', ['participant' => $profil->getId()]);
        }
        return $this->render('profil/gerer-profil.html.twig', [
            'profilForm' => $profilForm->createView(),
            'profil' => $profil
        ]);


    }
    /**
     * @Route("/profil/editer/{participant}", name="profil_editer")
     */
    public function editer(Request $request, ParticipantRepository $participantRepository, Participant $participant , string $photoDir): Response
    {
        $profilForm = $this->createForm(CreerProfilType::class, $participant);

        $profilForm->handleRequest($request);
        if ($profilForm->isSubmitted() && $profilForm->isValid()) {
            if ($photo = $participant['photo']->getData()) {
                $photoProfil = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($photoDir, $photoProfil);
                    $participant->setPhotoProfil($photoProfil);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
            }

        }
        return $this->render('profil/gerer-profil.html.twig', [
            'profilForm' => $profilForm->createView(),
            'profil' => $participant
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
