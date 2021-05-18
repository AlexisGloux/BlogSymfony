<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_show", requirements={"id":"\d+"})
     */
    public function show(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBy(['id' => $id]);

        // Si l'id du post n'existe pas -> err 404
        if (!$post)
            throw $this->createNotFoundException('Article '.$id.' non trouvé');

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
