<?php

declare(strict_types=1);

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\PostForm;
use Application\Entity\Post;
use Application\Form\CommentForm;
use Doctrine\ORM\EntityManager;
use Application\Service\PostManager;

class PostController extends AbstractActionController
{
    /**
     * @var EntityManager EntityManager
     */
    private $entityManager;
    
    /**
     * @var PostManager PostManager
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
    public function addAction() 
    {     
        $form = new PostForm();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->postManager->addNewPost($data);
                return $this->redirect()->toRoute('application');
            }
        }
        
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * @return ViewModel
     */
    public function viewAction() 
    {       
        $postId = (int)$this->params()->fromRoute('id', -1);
        if ($postId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $post = $this->entityManager->getRepository(Post::class)
                ->findOneById($postId);
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
        $form = new CommentForm();
        if($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();
                $this->postManager->addCommentToPost($post, $data);
                return $this->redirect()->toRoute('posts', ['action'=>'view', 'id'=>$postId]);
            }
        }
        return new ViewModel([
            'post' => $post,
            'form' => $form,
            'postManager' => $this->postManager
        ]);
    }

    /**
     * @return ViewModel
     */
    public function editAction() 
    {
        $form = new PostForm();
        $postId = (int)$this->params()->fromRoute('id', -1);
        if ($postId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $post = $this->entityManager->getRepository(Post::class)
                ->findOneById($postId);        
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        } 
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->postManager->updatePost($post, $data);
                return $this->redirect()->toRoute('posts', ['action'=>'admin']);
            }
        } else {
            $data = [
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'status' => $post->getStatus()
            ];
            $form->setData($data);
        }
        
        return new ViewModel([
            'form' => $form,
            'post' => $post
        ]);  
    }

    /**
     * @return ViewModel
     */
    public function deleteAction()
    {
        $postId = (int)$this->params()->fromRoute('id', -1);
        if ($postId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $post = $this->entityManager->getRepository(Post::class)
                ->findOneById($postId);        
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
        $this->postManager->removePost($post);
        
        return $this->redirect()->toRoute('posts', ['action'=>'admin']);
                
    }

    /**
     * @return ViewModel
     */
    public function adminAction()
    {
        $posts = $this->entityManager->getRepository(Post::class)
                ->findBy([], ['dateCreated'=>'DESC']);
        
        return new ViewModel([
            'posts' => $posts,
            'postManager' => $this->postManager
        ]);        
    }
}