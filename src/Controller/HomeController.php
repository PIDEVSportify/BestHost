<?php

namespace App\Controller;

use App\Repository\MaisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Maison ;


class HomeController extends AbstractController
{


    /**
     * @Route("/test", name="test")
     */
    public function test(): Response
    {
        return $this->render('threetest.html.twig' );
    }


    /**
     * @Route ("/houses",name="maisons")
     */
    public function showHouses()
    {
        

    }



    /**
     * @Route ("/tour/{id}",name="showTour")
     */
    public function tour($id)
    {
        
    }
}
