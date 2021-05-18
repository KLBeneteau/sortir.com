<?php


namespace App\Controller;


use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/User", name="User_")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/{id}", name="detail")
     */
    public function detail(int $id, ParticipantRepository $participantRepository) : Response {

        $user = $participantRepository->find($id);

        return $this->render('user/detail.html.twig', compact('user')) ;

    }

    /**
     * @Route("MonProfil", name="MonProfil")
     */
    public function monProfil() : Response {

        return $this->render('user/monProfil.html.twig') ;

    }

}