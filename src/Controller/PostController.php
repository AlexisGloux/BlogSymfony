<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/", name="post_index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findLatest2(),
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

    /**
     * @Route("/new", name="post_new", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $post->setCreatedAt(new \DateTime());
                $entityManager->persist($post);
                $entityManager->flush();

                $this->addFlash('success','Post ajouté avec succès'.' ('.$post->getTitle().')');
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


    public function stat(PostRepository $postRepository){
        return $this->render('post/stat.html.twig', [
            'post_count' => $postRepository->count([])
        ]);
    }

}
