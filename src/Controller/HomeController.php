<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UsersRepository $repo): Response
    {
        
        $users=$repo->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $users
        ]);
    }
}
