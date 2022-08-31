<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{

    #[Route('/users', name: 'listing')]
    public function index(UsersRepository $repo, Request $request): Response
    {

        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);

        $users = $repo->findBySearchFilter($propertySearch);

        return $this->render('users/index.html.twig', [
            'controller_name' => 'Liste des inscrits',
            'formSearch' => $form->createView(),
            'users' => $users
        ]);
    }
}
