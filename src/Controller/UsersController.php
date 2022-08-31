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

        // on conditionne l'affichage de toutes les personnes à la recherche préalable
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);
        // si si aucun nom n'est fourni on affiche tous les utilisateurs
        $users = $repo->findAll();
        

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom de l'utilisateur renseigné dans le formulaire
            $name = $propertySearch->getName();
            if ($name != "") {
                //si on a fourni un nom d'utilisateur, on va n'afficher que les utilisateurs ayant ce nom
                $users = $repo->findBy(['lastName' => $name]);
            }
            else {
                $users = $repo->findAll();
            }
        }


        return $this->render('users/index.html.twig', [
            'controller_name' => 'Liste des inscrits',
            'formSearch' => $form->createView(),
            'users' => $users
        ]);
    }
}
