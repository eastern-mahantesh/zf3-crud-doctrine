<?php

declare(strict_types=1);

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Post;
use Application\Repository\Query as RepoQuery;

class PostRepository extends EntityRepository
{
    /**
     * @return Query
     */
    public function findPublishedPosts()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED);
        
        return $queryBuilder->getQuery();
    }
    
    /**
     * @return array
     */
    public function findPostsHavingAnyTag(): array
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED);
        $posts = $queryBuilder->getQuery()->getResult();
        
        return $posts;
    }

    /**
     * @param string $tagName
     * @return Query
     */
    public function findPostsByTag(string $tagName): Query
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->andWhere('t.name = ?2')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED)
            ->setParameter('2', $tagName);
        
        return $queryBuilder->getQuery();
    }        
}