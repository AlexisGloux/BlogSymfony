<?php


namespace App\Controller;


use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="author_show", requirements={"id":"\d+"})
     * @param int $id
     * @param PostRepository $postRepository
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function show(int $id, PostRepository $postRepository, AuthorRepository $authorRepository): Response
    {

        return $this->render('author/show.html.twig', [
            'articles' => $postRepository->findBy(['writtenBy' => $id]),
            'author' => $authorRepository->find(['id' => $id])
        ]);
    }

    /**
     * @Route("/new", name="author_new", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $aut = new Author();
        $form = $this->createForm(AuthorType::class, $aut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($aut);
                $entityManager->flush();

                $this->addFlash('success','Auteur ajouté avec succès'.' ('.$aut->getName().')');
                return $this->redirectToRoute('author_index');
            } catch (\Exception $e) {
                $this->addFlash('error',$e->getMessage());
                return $this->redirectToRoute('author_new');
            }
        }

        return $this->render('author/new.html.twig', [
            'auteur' => $aut,
            'form' => $form->createView(),
        ]);
    }

}