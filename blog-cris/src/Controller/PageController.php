<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

final class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        // Crear formulario de registro
        $user = new User();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);

        return $this->render('page/index.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }

    #[Route('/single_post1', name: 'single_post1')]
    public function single_post1(Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirect($this->generateUrl('index', [
                'login_required' => 1,
                'redirect_to' => 'single_post1'
            ]));
        }

        return $this->render('page/single_post1.html.twig');
    }

    #[Route('/single_post2', name: 'single_post2')]
    public function single_post2(Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirect($this->generateUrl('index', [
                'login_required' => 1,
                'redirect_to' => 'single_post2'
            ]));
        }

        return $this->render('page/single_post2.html.twig');
    }

    #[Route('/contacto', name: 'contacto')]
    public function contacto(): Response
    {
        return $this->render('page/contacto.html.twig');
    }
}
