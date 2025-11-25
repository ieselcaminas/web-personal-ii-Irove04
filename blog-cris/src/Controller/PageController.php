<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', []);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }

    #[Route('/single_post1', name: 'single_post1')]
    public function single_post1(): Response
    {
        return $this->render('page/single_post1.html.twig');
    }

    #[Route('/single_post2', name: 'single_post2')]
    public function single_post2(): Response
    {
        return $this->render('page/single_post2.html.twig');
    }

    #[Route('/contacto', name: 'contacto')]
    public function contacto(): Response
    {
        return $this->render('page/contacto.html.twig');
    }
}
