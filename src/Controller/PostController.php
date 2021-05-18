<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/post", methods="GET")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", requirements={"id":"\d+"})
     * @param Post $post
     * @return Response
     */
    public function show(Post $post): Response
    {
//        $post = $postRepository->findOneBy(['id' => $id]);
//
//        // Si l'id du post n'existe pas -> err 404
//        if (!$post)
//            throw $this->createNotFoundException('Article '.$id.' non trouvé');

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
