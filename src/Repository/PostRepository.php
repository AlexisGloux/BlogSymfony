<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return int|mixed|string
     */
    public function findLatest(){
        return $this->getEntityManager()->createQuery(
            'SELECT post FROM '.Post::class.' post ORDER BY post.createdAt DESC'
        )
            ->setMaxResults(20)
            ->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findLatest2(){
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $keywordName
     * @return int|mixed|string
     */
    public function findByKeywordName(string $keywordName)
    {
        return $this->createQueryBuilder('post')
            ->addSelect('keyword')
            ->join('post.keywords', 'keyword')
            ->andWhere('keyword.name LIKE :name')
            ->getQuery()
            ->setParameter('name', $keywordName.'%')
            ->getResult();
    }

}
