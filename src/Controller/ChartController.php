<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * Class ChartController
 * @package App\Controller
 * @Route("/admin",name="admin_")
 * @IsGranted ("ROLE_ADMIN")
 */



class ChartController extends AbstractController
{
    /**
     * @Route("/showStats", name="showStats")
     * @param ChartBuilderInterface $chartBuilder
     * @return Response
     */
    public function createCharts(ChartBuilderInterface $chartBuilder,UserRepository $repository): Response
    {

        $em= $this->getDoctrine()->getManager();
        $con= $em->getConnection();
        $query = "SELECT count(*) as count ,Concat(MONTH(u.created_at),CONCAT('-',YEAR(u.created_at)))  as date FROM user u  group by  date ";
        $stmt=$con->prepare($query);
        $stmt->execute();
        $data=$stmt->fetchAll() ;

        foreach ($data as $elem)
        {
            $count[]=$elem['count'];
            $date[]=$elem['date'];
        }


        $users_chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $users_chart->setData([
            'labels' =>  $date,
            'datasets' => [
                [
                    'label' => 'Utilisateurs par mois',
                    'backgroundColor' => ['rgba(210, 103,103,0.7)','rgba(129, 35,35,0.7)','rgba(180, 82,193,0.7)'
                        ,'rgba(159, 227,183,0.7)','rgba(92, 94,161,0.7)'],
                    'borderColor' => 'rgb(255, 255, 255)',
                    'hoverBackgroundColor' => 'rgb(217, 17,17)',
                    'data' => $count,
                ],

            ],
        ]);

        $users_chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0]],
                ],
            ],



        ]);


        $query = "SELECT count(*) as count ,u.roles   FROM user u  group by  u.roles ";
        $stmt=$con->prepare($query);
        $stmt->execute();
        $data=$stmt->fetchAll() ;


             unset($count);
            foreach($data as $elem)
            {
                $count[]=$elem['count'];
                $category[]=$elem['roles'];
            }

        $users_category_chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $users_category_chart->setData([
            'labels' =>  $category,
            'datasets' => [
                [
                    'label' => 'Role',
                    'backgroundColor' => ['rgb(210, 103,103)','rgb(129, 35,35)','rgb(180, 82,193)'
                        ,'rgb(159, 227,183)','rgb(92, 94,161)'],
                    'borderColor' => 'rgb(255, 255, 255)',
                    'hoverBackgroundColor' => 'rgb(155, 155,155)',
                    'data' => $count,
                ],

            ],
        ]);




        return $this->render('admin/showStatistics.html.twig', [
            'chart' => $users_chart,'chart_cat'=>$users_category_chart
        ]);
    }

}
