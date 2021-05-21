<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/admin/ville", name="ville_create")
     */
    public function create(Request $request,VilleRepository $villeRepository,EntityManagerInterface $entityManager) : Response {

        $villeList = $villeRepository->findAvecFiltre($request->get('filtre')) ;

        $ville = new Ville() ;

        $villeForm =$this->createForm(VilleType::class, $ville) ;

        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
            return $this->redirectToRoute('ville_create') ;
        }

        return $this->render('ville/villes.html.twig',[
            'villeList' => $villeList ,
            'villeForm' => $villeForm->createView(),
            'idVilleAModifier' => null
        ]) ;

    }

    /**
     * @Route("admin/ville", name="ville_update")
     */
    public function update(Request $request, VilleRepository $villeRepository, EntityManagerInterface $entityManager) : Response
    {

        $villeList = $villeRepository->findAvecFiltre($request->get('filtre')) ;

        $ville = $villeRepository->find($request->get('ville_id')) ;

        $villeForm =$this->createForm(VilleType::class, $ville) ;

        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('ville_create') ;
        }

        return $this->render('ville/villes.html.twig',[
            'villeList' => $villeList ,
            'villeForm' => $villeForm->createView(),
            'idVilleAModifier' => $ville->getId()
        ]) ;
    }

    /**
     * @Route("admin/ville", name="ville_delete")
     */
    public function delete(Request $request, VilleRepository $villeRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $ville = $villeRepository->findOneBy(['id' => $request->get('ville_id')]);
        $entityManager->remove($ville);
        $entityManager->flush();

        return $this->redirectToRoute('ville_create') ;
    }

}