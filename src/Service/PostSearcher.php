<?php


namespace App\Service;


use App\Entity\Post;
use App\Repository\PostRepository;

class PostSearcher implements PostSearcherInterface
{

    /**
     * @var PostRepository
     */
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param string|null $keyword
     * @return Post
     */
    public function search(?string $keyword): array
    {
        $posts = [];
        if ($keyword)
            $posts = $this->postRepository->findByKeywordName($keyword);

        return $posts;
    }

}