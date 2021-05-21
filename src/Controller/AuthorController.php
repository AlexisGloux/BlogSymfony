<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Post;
use App\Entity\User;
use App\Form\AuthorType;
use App\Form\PasswordType;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AuthorController
 * @package App\Controller
 * @Route("/", methods="GET")
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
     * @Route("/post/{id}", name="post_show", requirements={"id":"\d+"})
     * @param Post $post
     * @return Response
     * @Cache(expires="tomorrow", public=true, )
     */
    public function showPost(Post $post): Response
    {
//        $post = $postRepository->findOneBy(['id' => $id]);
//
//        // Si l'id du post n'existe pas -> err 404
//        if (!$post)
//            throw $this->createNotFoundException('Article '.$id.' non trouvé');

        $response = $this->render('post/show.html.twig', [
            'post' => $post,
        ]);

        // Gestion du cache :
        // Possibilité 1 : Annotation @Cache()
        // Possibilité 2 : retravailler l'objet de réponse avant le de le return (ex ci-dessous)
        $response->setExpires(new \DateTime('+2 days'));
        $response->setPublic();
        $response->headers->addCacheControlDirective('no-store');

        return $response;
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

    /**
     * @Route("/{id}/edit", name="author_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Author $author
     * @return Response
     */
    public function edit(Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $passwordForm = $this->createForm(PasswordType::class, [], [
            'action' => $this->generateUrl('author_password', [
                'id' => $author->getUser()->getId(),
            ]),
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success','Auteur édité avec succès'.' ('.$author->getName().')');
                return $this->redirectToRoute('author_index', ['id' => $author->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error',$e->getMessage());
                return $this->redirectToRoute('author_edit', ['id' => $author->getId()]);
            }
        }

        return $this->render('author/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
            'password_form' => $passwordForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/password", name="author_password", methods="PUT")
     * @param User $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $manager
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function changePassword(
        User $user,
        Request $request,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $manager,
        AuthorRepository $authorRepository
    ): Response {
        $passwordForm = $this->createForm(PasswordType::class, [], [
            'action' => $this->generateUrl('author_password', [
                'id' => $user->getId(),
            ]),
            'method' => 'PUT',
        ]);

        $passwordForm->handleRequest($request);

        if ($passwordForm->isValid()) {
            $newPassword = $passwordForm->get('password')->getData();
            $user->setPassword($encoder->encodePassword($user, $newPassword));
            $manager->flush();

            $this->addFlash('success', 'mot de passe changé');
        } else {
            foreach ($passwordForm->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirectToRoute('author_edit', [
            'id' => $authorRepository->findOneBy(['user' => $user])->getId(),
        ]);
    }


    public function stat(PostRepository $postRepository): Response
    {
        return $this->render('post/stat.html.twig', [
            'post_count' => $postRepository->count([])
        ]);
    }

}