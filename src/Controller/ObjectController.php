<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObjectController extends AbstractController
{

    /**
     * @Route("Villes", name="Villes")
     */
    public function villes() : Response {

        return $this->render('object/villes.html.twig') ;

    }

    /**
     * @Route("Campus", name="Campus")
     */
    public function campus() : Response {

        return $this->render('object/campus.html.twig') ;

    }

}