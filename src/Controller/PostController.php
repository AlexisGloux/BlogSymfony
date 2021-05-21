<?php

namespace App\Controller;

use App\client\PunkApiClient;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use App\Service\PostSearcher;
use App\Service\PostSearcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/admin", methods="GET")
 * @IsGranted("ROLE_ADMIN")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $locale = $request->getLocale();

        // Possibilitée de gérer l'acces aux pages avec les roles
        //$this->denyAccessUnlessGranted('ROLE_USER');
        //throw $this->createAccessDeniedException(''); // création d'exception

        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findLatest2(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, AuthorRepository $authorRepository, TranslatorInterface $translator): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'validation_groups' => ['published'],
        ]);
        $form->handleRequest($request);

        // Gestion du User automatiquement
        $user = $this->getUser();
        $author = $authorRepository->findOneBy(['user' => $user]);
        $post->setWrittenBy($author);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($post);
                $entityManager->flush();

                $this->addFlash('success',$translator->trans('post.new.success').' ('.$post->getTitle().')');
                return $this->redirectToRoute('post_index');
            } catch (\Exception $e) {
                $this->addFlash('error',$e->getMessage());
                return $this->redirectToRoute('post_new');
            }
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Post $post
     * @return Response
     * @IsGranted("POST_EDIT", subject="post")
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success','Post édité avec succès'.' ('.$post->getTitle().')');
                return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error',$e->getMessage());
                return $this->redirectToRoute('post_edit', ['id' => $post->getId()]);
            }
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="post_search")
     * @param Request $request
     * @param PostSearcherInterface $postSearcher
     * @return Response
     */
    public function search(Request $request, PostSearcherInterface $postSearcher): Response
    {
        $keywordName = $request->query->get('q');

        return $this->render('post/search.html.twig', [
            'posts' => $postSearcher->search($keywordName)
        ]);
    }

    /**
     * @Route("/api", name="post_api")
     * @return Response
     */
    public function httpClient(PunkApiClient $client): Response
    {

        return $this->render('post/api.html.twig', [
            'content' => $client->random(),
            'content2' => $client->search(35,50)
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function delete(Request $request, Post $post): Response
    {
        try {
            if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($post);
                $entityManager->flush();
                $this->addFlash('success','Post supprimé avec succès'.' ('.$post->getTitle().')');
            }
        } catch (\Exception $e) {
            $this->addFlash('error',$e->getMessage());
            return $this->redirectToRoute('psot_show', ['id' => $post->getId()]);
        }

        return $this->redirectToRoute('post_index');
    }

}
