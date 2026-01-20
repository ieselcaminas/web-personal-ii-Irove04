<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Post;
use App\Form\PostFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
    #[Route('/blog/new', name: 'new_post')]
    public function newPost(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        // 1. COMPROBAR SESIÓN: Si no hay usuario, redirigir al login
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 2. PROCESAR LA IMAGEN (FILE)
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Usamos el parámetro que definiste en services.yaml
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... manejar error si falla la subida
                }

                // Guardamos el nombre del archivo en la entidad
                $post->setImage($newFilename);
            }

            // 3. GENERAR SLUG Y OTROS DATOS
            $post->setSlug($slugger->slug($post->getTitle())->lower());
            $post->setPostUser($this->getUser());
            $post->setNumLikes(0);
            $post->setNumComments(0);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('single_post', ["slug" => $post->getSlug()]);        }

        return $this->render('blog/new_post.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('blog/single_post/{slug}', name: 'single_post')]
    public function post(ManagerRegistry $doctrine, $slug): Response
    {
        $repository = $doctrine->getRepository(Post::class);
        $post = $repository->findOneBy(["slug"=>$slug]);
        $recents = $repository->findRecents();
        return $this->render('blog/single_post.html.twig', [
            'post' => $post,
            'recents' => $recents
        ]);
    }


    #[Route('/blog/posts', name: 'posts')]
    public function posts(PostRepository $repository): Response
    {
        // Usamos el repositorio directamente
        $posts = $repository->findAll();

        return $this->render('blog/posts.html.twig', [
            'posts' => $posts,
        ]);
    }


}
