<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryFormType;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Image;

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

    #[Route('/admin/images', name: 'images')]
    public function images(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/images.html.twig');
    }

    #[Route('/admin/categories', name: 'app_categories')]
    public function categories(ManagerRegistry $doctrine, Request $request): Response
    {
        $repositorio = $doctrine->getRepository(Category::class);
        $categories = $repositorio->findAll();

        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            // MUY IMPORTANTE SIS
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('admin/categories.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);

    }

    #[Route('/admin/list_images', name: 'list_images')]
    public function listaimages(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $images = $doctrine->getRepository(Image::class)->findAll();

        return $this->render('admin/list_images.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/admin/delete_image/{id}', name: 'delete_image')]
    public function deleteImage(Image $image, ManagerRegistry $doctrine): Response
    {
        // 1. Verificamos seguridad
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // 2. Obtenemos el manager de Doctrine y eliminamos la entidad
        $entityManager = $doctrine->getManager();
        $entityManager->remove($image);
        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        // 3. (Opcional) Mensaje de confirmación para el usuario
        $this->addFlash('success', 'Imagen eliminada correctamente.');

        // 4. Redirigimos de vuelta a la lista
        return $this->redirectToRoute('list_images');
    }
}
