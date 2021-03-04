<?php

namespace App\Controller;

use App\Entity\Gerant;
use App\Form\Gerant1Type;
use App\Repository\GerantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GerantController extends AbstractController
{
    /**
     * @Route("/show", name="gerant_index", methods={"GET"})
     */
    public function index(GerantRepository $gerantRepository): Response
    {
        return $this->render('gerant/index.html.twig', [
            'gerants' => $gerantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="gerant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gerant = new Gerant();
        $form = $this->createForm(Gerant1Type::class, $gerant);
        $form->add('ajouter',SubmitType::class,['attr'=>
            ['class'=>'btn_3']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gerant);
            $entityManager->flush();

            return $this->redirectToRoute('gerant_index');
        }

        return $this->render('gerant/new.html.twig', [
            'gerant' => $gerant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{Id_gerant}", name="gerant_show", methods={"GET"})
     */
    public function show(Gerant $gerant): Response
    {
        return $this->render('gerant/show.html.twig', [
            'gerant' => $gerant,
        ]);
    }

    /**
     * @Route("/edit/{Id_gerant}", name="gerant_edit", methods={"GET","POST"})
     */




    public function edit(Request $request, Gerant $gerant): Response
    {
        $form = $this->createForm(Gerant1Type::class, $gerant);
        $form->add('Modifier',SubmitType::class,['attr'=>
            ['class'=>'btn_3']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gerant_index');
        }

        return $this->render('gerant/edit.html.twig', [
            'gerant' => $gerant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{Id_gerant}", name="gerant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Gerant $gerant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gerant->getIdgerant(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gerant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gerant_index');
    }
}
