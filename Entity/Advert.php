<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(min=4)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="author", type="string", length=255)
     * @Assert\Length(min=2)
     */
    private $author;

    /**
     * @var string
     * @ORM\Column(name="content", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var bool
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    private $published = true;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    public function __construct()
    {
      // Default date = today
      $this->date = new \Datetime();
      $this->categories = new ArrayCollection();
    }


    // Getters and Setters

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getDate()
    {
        return $this->date->format('Y-m-d');
    }
    public function setDate($date)
    {
        $this->date = $date;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function setContent($content)
    {
        $this->content = $content;
    }
    public function getPublished()
    {
        return $this->published;
    }
    public function setPublished($bool){
        $this->published = $bool;
    }

    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }
    public function getImage()
    {
        return $this->image;
    }

    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }
    
    /**
    * @param Category $category
    */
    public function removeCategory(Category $category)
    {    
        $this->categories->removeElement($category);
    }
    
    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
    * @param \DateTime $updatedAt
    */
    public function setUpdatedAt(\Datetime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }
   
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
    * @ORM\PreUpdate
    */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    
    
  
}
