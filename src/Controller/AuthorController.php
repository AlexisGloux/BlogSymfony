<?php


namespace App\Controller;


use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController
 * @package App\Controller
 * @Route("/author", methods="GET")
 */
class AuthorController extends AbstractController
{

    /**
     * @Route("/", name="author_index")
     * @return Response
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="author_show")
     * @return Response
     */
    public function show(string $id, PostRepository $postRepository): Response
    {
        return $this->render('author/show.html.twig', [
            'articles' => $postRepository->findAllArticlesByAuthorId($id),
        ]);
    }

}