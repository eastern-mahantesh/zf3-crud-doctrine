<?php

declare(strict_types=1);

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Application\Entity\Post;
use Doctrine\ORM\EntityManager;
use Application\Service\PostManager;

class IndexController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * Post manager.
     * @var PostManager
     */
    private $postManager;

    /**
     * @param EntityManager $entityManager
     * @param PostManager $postManager
     */
    public function __construct($entityManager, $postManager)
    {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
    }

    /**
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        $page = $this->params()->fromQuery('page', 1);
        $tagFilter = $this->params()->fromQuery('tag', null);
        
        if ($tagFilter) {
            $query = $this->entityManager->getRepository(Post::class)
                    ->findPostsByTag($tagFilter);
        } else {
            $query = $this->entityManager->getRepository(Post::class)
                    ->findPublishedPosts();
        }
        
        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);        
        $paginator->setCurrentPageNumber($page);
                       
        return new ViewModel([
            'posts' => $paginator,
            'postManager' => $this->postManager
        ]);
    }

    /**
     * @return ViewModel
     */
    public function aboutAction(): ViewModel
    {   
        $appName = 'Blog';
        $appDescription = 'A simple blog application for the Using Zend Framework 3 book';
        
        return new ViewModel([
            'appName' => $appName,
            'appDescription' => $appDescription
        ]);
    }
}