<?php
namespace Application\Service;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Application\Entity\Post;
use Application\Entity\Comment;
use Application\Entity\Tag;
use Zend\Filter\StaticFilter;

/**
 * The PostManager service is responsible for adding new posts, updating existing
 * posts, adding tags to post, etc.
 */
class PostManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;
    
    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * This method adds a new post.
     */
    public function addNewPost($data) 
    {
        // Create new Post entity.
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);
        $currentDate = date('Y-m-d H:i:s');
        $post->setDateCreated($currentDate);        
        
        // Add the entity to entity manager.
        $this->entityManager->persist($post);
        
        // Add tags to post
        //$this->addTagsToPost($data['tags'], $post);
        
        // Apply changes to database.
        $this->entityManager->flush();
    }
    
    /**
     * This method allows to update data of a single post.
     */
    public function updatePost($post, $data) 
    {
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);
        
        // Add tags to post
//        $this->addTagsToPost($data['tags'], $post);
        
        // Apply changes to database.
        $this->entityManager->flush();
    }

    
    /**
     * Returns status as a string.
     */
    public function getPostStatusAsString($post) 
    {
        switch ($post->getStatus()) {
            case Post::STATUS_DRAFT: return 'Draft';
            case Post::STATUS_PUBLISHED: return 'Published';
        }
        
        return 'Unknown';
    }


    /**
     * Removes post and all associated comments.
     */
    public function removePost($post) 
    {
        // Remove associated comments

        $this->entityManager->remove($post);
        
        $this->entityManager->flush();
    }

}



