<?php

declare(strict_types=1);

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\Application\Repository\PostRepository")
 * @ORM\Table(name="post")
 */
class Post 
{
    const STATUS_DRAFT       = 1; // Draft.
    const STATUS_PUBLISHED   = 2; // Published.

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="title")  
     */
    protected $title;

    /** 
     * @ORM\Column(name="content")  
     */
    protected $content;

    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;

    /**
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;
    
    /**
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle() 
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) 
    {
        $this->title = $title;
    }

    /**
     * @return integer
     */
    public function getStatus() 
    {
        return $this->status;
    }

    /**
     * @param integer $status
     */
    public function setStatus($status) 
    {
        $this->status = $status;
    }   
    
    /**
     * @return string
     */
    public function getContent() 
    {
       return $this->content; 
    }
    
    /**
     * @param type $content
     */
    public function setContent($content) 
    {
        $this->content = $content;
    }
    
    /**
     * @return string
     */
    public function getDateCreated() 
    {
        return $this->dateCreated;
    }
    
    /**
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated) 
    {
        $this->dateCreated = $dateCreated;
    }
}