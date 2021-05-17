<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {

        return new Response('Bonjour</body>');
    }

    /**
     * @Route(
     *     "/hello/{name}",
     *     defaults={"name": "Utt"},
     *     requirements={"name": "[a-z]*"},
     *     methods={"GET", "POST", "PUT"}
     * )
     */
    public function hello(string $name): Response
    {

        return $this->render('index/hello.html.twig', [
            'name' => $name
        ]);
    }
}
