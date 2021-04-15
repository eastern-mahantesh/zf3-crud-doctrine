<?php

declare(strict_types=1);

namespace Application\Service;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Application\Entity\Post;
use Application\Entity\Comment;
use Application\Entity\Tag;
use Zend\Filter\StaticFilter;
use Doctrine\ORM\EntityManager;
/**
 * The PostManager service is responsible for adding new posts, updating existing
 * posts, adding tags to post, etc.
 */
class PostManager
{
    /**
     * @var EntityManager EntityManager;
     */
    private $entityManager;
    
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @param array $data
     */
    public function addNewPost(array $data)
    {
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);
        $currentDate = date('Y-m-d H:i:s');
        $post->setDateCreated($currentDate);        
        $this->entityManager->persist($post);

        $this->entityManager->flush();
    }

    /**
     * @param ObjectRepository|EntityRepository $post
     * @param array $data
     * This method allows to update data of a single post.
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePost(EntityRepository $post, array $data)
    {
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);

        $this->entityManager->flush();
    }

    /**
     * @param ObjectRepository|EntityRepository $post
     * @return String
     */
    public function getPostStatusAsString($post): String
    {
        switch ($post->getStatus()) {
            case Post::STATUS_DRAFT: return 'Draft';
            case Post::STATUS_PUBLISHED: return 'Published';
        }
        
        return 'Unknown';
    }

    /**
     * @param ObjectRepository|EntityRepository $post
     */
    public function removePost($post) 
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}