<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Repository\MissionRepository;

class HomeController extends AbstractController
{

    /**
     * @Route("/calendrier", name="calendrier")
     */
    public function index(MissionRepository $missionRepository)
    {
        return $this->render('home/calendar.html.twig', [
            'controller_name' => 'HomeController',
            'missions' => $missionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function calendrier()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
     /**
     * @Route("/parametre", name="parametre")
     */
    public function page()
    {
        return $this->render('home/parametre.html.twig');
    }
     /**
     * @Route("/", name="infoclient")
     */
    public function client(AuthorizationCheckerInterface $authChecker)
    {
         if(true===$authChecker->isGranted('IS_AUTHENTICATED_FULLY'))
         return $this->redirectToRoute('home');
        return $this->render('home/client.html.twig');
    }
}
