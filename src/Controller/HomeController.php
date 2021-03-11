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
    public function showHouses(MaisonRepository $repo)
    {
        $houses= $repo->findAll();
         return $this->render('test.html.twig', ['houses'=>$houses]);


    }



    /**
     * @Route ("/tour/{id}",name="showTour")
     */
    public function tour($id,MaisonRepository $repo)
    {
        $house=$repo->find($id);
        return $this->render('threetest.html.twig',['house'=>$house]);

    }
}
