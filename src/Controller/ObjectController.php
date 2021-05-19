<?php


namespace App\Controller;


use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObjectController extends AbstractController
{

    /**
     * @Route("/admin/ville", name="objet_ville")
     */
    public function ville(Request $request,VilleRepository $villeRepository,EntityManagerInterface $entityManager) : Response {

        $villeList = $villeRepository->findAvecFiltre($request->get('filtre')) ;
        dump($request->get('filtre'));

        $ville = new Ville() ;
        $villeForm =$this->createForm(VilleType::class, $ville) ;

        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            return $this->redirectToRoute('objet_ville') ;
        }

        return $this->render('object/villes.html.twig',[
            'villeList' => $villeList ,
            'villeForm' => $villeForm->createView()
        ]) ;

    }

    /**
     * @Route("/admin/campus", name="objet_campus")
     */
    public function campus() : Response {

        return $this->render('object/campus.html.twig') ;

    }

}