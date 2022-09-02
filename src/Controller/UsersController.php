<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\PropertySearch;
use App\Form\EditUserFormType;
use App\Form\PropertySearchType;
use App\Repository\UsersRepository;
use Symfony\Component\DomCrawler\Form;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{

    #[Route('/users', name: 'listing')]
    public function index(UsersRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {

        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);
        $data = $repo->findBySearchFilter($propertySearch);
        $users = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            9 // Nombre de résultats par page
        );

        return $this->render('users/index.html.twig', [
            'controller_name' => 'Liste des inscrits',
            'formSearch' => $form->createView(),
            'users' => $users
        ]);
    }

    #[Route('/user/{id}', name: 'user_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'controller_name' => 'Profil Utilisateur ',
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, UsersRepository $repo): Response
    {
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->add($user, true);

            return $this->redirectToRoute('listing', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/edit.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $usersRepository->remove($user, true);
        }

        return $this->redirectToRoute('listing', [], Response::HTTP_SEE_OTHER);
    }
}
