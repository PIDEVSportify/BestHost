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
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/admin/gerant")
 */
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
            ['class'=>"btn btn-primary btn-block "]]);
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
    /**
     * @Route("/list/{Id_gerant}", name="gerant_list", methods={"GET"})
     */
    public function listp(Gerant $produits) : Response
    {
// Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('gerant/list.html.twig', [
            'gerant' =>$produits,
        ]);

        $html .= '<link type="text/css" href="/public/assets/css/bootstrap.min.css" rel="stylesheet" media="screen,print" />';

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);



    }
    /**
     * @Route("/tri", name="tri_g")
     */
    public function TriAction(Request $request)
    {




        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT e FROM App\Entity\Gerant e
    ORDER BY e.Id_gerant ');



        $candidats = $query->getResult();

        return $this->render('gerant/index.html.twig', array(
            'gerants' => $candidats));

    }
    /**
     * @Route("/recherche", name="recherche_g")
     */
    public function searchAction(Request $request)
    {

        $data = $request->request->get('search');


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM App\Entity\Gerant e WHERE e.Id_gerant    LIKE :data')
            ->setParameter('data', '%'.$data.'%');


        $events = $query->getResult();

        return $this->render('gerant/index.html.twig', [
            'gerants' => $events,
        ]);
    }
}
