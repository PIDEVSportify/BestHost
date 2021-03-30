<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActLike;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bridge\Twig\Mime\NotificationEmail;


/**
 * @Route("/activity")
 */
class ActivityController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin", name="activity_index", methods={"GET"})
     */
    public function index(ActivityRepository $activityRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnes= $this->getDoctrine()->getRepository(Activity::class)->findAll();
        $activites = $paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),3
        );
        return $this->render('activity/index.html.twig', [
            'activities' => $activites
        ]);
    }
    /**
     * @Route("/user", name="activity_user")
     */
    public function user(ActivityRepository $activityRepository): Response
    {
        return $this->render('activity/user.html.twig', [
            'activities' => $activityRepository->findAll(),
        ]);



    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/new", name="activity_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityType::class, $activity);
        $form->add('ajouter',SubmitType::class,['attr'=>
            ['class'=>'btn_3']]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('activity_index');
        }

        return $this->render('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/{id}", name="activity_show", methods={"GET"})
     */
    public function show(Activity $activity): Response
    {
        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
        ]);
    }
    /**
     * @Route("/user/{id}", name="us_show")
     */
    public function usershow(Activity $activity): Response
    {
        return $this->render('activity/usershow.html.twig', [
            'activity' => $activity,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/{id}/edit", name="activity_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activity $activity): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->add('Modifier',SubmitType::class,['attr'=>
            ['class'=>'btn_3']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activity_index');
        }

        return $this->render('activity/edit.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="activity_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Activity $activity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activity_index');
    }

    /**
     * @Route("/activity/{id}/like" , name="act_like")
     * @param Activity $activity
     * @param EntityManagerInterface $manager
     * @param ActLikeRepository $likeRepo
     * @return Response
     */
    public function like(Activity $activity, EntityManagerInterface $manager , ActLikeRepository $likeRepo, \Swift_Mailer $mailer) : Response
    {
        $user = $this->getUser();
        if(!$user) return $this->redirectToRoute('login');
        $mail = $user->getUsername();

        if($activity->isLikedByUser($user)){
            $like = $likeRepo->findOneBy([
                'post' => $activity,
                'user'=> $user]);
            $manager->remove($like);
            $manager->flush();

            return $this->redirectToRoute('activity_user');
        }
        $like = new ActLike();
        $like->setPost($activity)
            ->setUser($user);
        $manager->persist($like);
        $manager->flush();
        $email = (new \Swift_Message($activity->getIdAct()))
            ->setFrom('ahmed.jelassi@esprit.tn')
            ->setTo('ahmed.jelassi@esprit.tn')
            ->setBody($activity->getDescription())
        ;
        $mailer->send($email);

        return $this->redirectToRoute('activity_user');

    }
    /**
     * @Route("/recherche", name="recherche")
     */
    public function searchAction(Request $request)
    {

        $data = $request->request->get('search');


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM App\Entity\Activity e WHERE e.Id_act    LIKE :data')
            ->setParameter('data', '%'.$data.'%');


        $events = $query->getResult();

        return $this->render('activity/index.html.twig', [
            'activities' => $events,
        ]);
    }
    /**
     * @Route("/tri", name="tri")
     */
    public function TriAction(Request $request)
    {




        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT e FROM App\Entity\Activity e
    ORDER BY e.Id_act ');



        $candidats = $query->getResult();

        return $this->render('activity/index.html.twig', array(
            'activities' => $candidats));

    }

    /**
     * @Route("/list/{id}", name="activity_list", methods={"GET"})
     */
    public function listp(Activity $produits) : Response
    {
// Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('activity/list.html.twig', [
            'activity' =>$produits,
        ]);

        $html .= '<link type="text/css" href=" /public/assets/css/bootstrap.min.css" rel="stylesheet" media="screen,print" />';

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);



    }

}
