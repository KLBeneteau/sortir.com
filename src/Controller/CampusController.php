<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/admin/campus", name="campus_create")
     */
    public function create(Request $request,CampusRepository $campusRepository,EntityManagerInterface $entityManager) : Response {

        $campusList = $campusRepository->findAvecFiltre($request->get('filtre')) ;

        $campus = new Campus() ;

        $campusForm =$this->createForm(CampusType::class, $campus) ;

        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()) {
               $entityManager->persist($campus);
               $entityManager->flush();
               return $this->redirectToRoute('campus_create') ;
            }

        return $this->render('campus/campus.html.twig',[
            'campusList' => $campusList ,
            'campusForm' => $campusForm->createView(),
            'idCampusAModifier' => null
        ]) ;

    }

    /**
     * @Route("admin/campus", name="campus_update")
     */
    public function update(Request $request, CampusRepository $campusRepository, EntityManagerInterface $entityManager) : Response
    {

        $campusList = $campusRepository->findAvecFiltre($request->get('filtre')) ;

        $campus = $campusRepository->find($request->get('campus_id')) ;

        $campusForm =$this->createForm(CampusType::class, $campus) ;

        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('campus_create') ;
        }

        return $this->render('campus/campus.html.twig',[
            'campusList' => $campusList ,
            'campusForm' => $campusForm->createView(),
            'idCampusAModifier' => $campus->getId()
        ]) ;
    }

    /**
     * @Route("admin/campus", name="campus_delete")
     */
    public function delete(Request $request, CampusRepository $campusRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $campus = $campusRepository->findOneBy(['id' => $request->get('campus_id')]);
        $entityManager->remove($campus);
        $entityManager->flush();

        return $this->redirectToRoute('campus_create') ;
    }

}