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
     * @ORM\Column(name="location", type="string", length=255)
     * @Assert\Length(min=4)
     */
    private $location;

    /**
     * @ORM\Column(name="lat", type="string", nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(name="lng", type="string", nullable=true)
     */
    private $lng;

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
     * @var string
     * @ORM\Column(name="email", type="string", length=32)
     * @Assert\Email(
     *    message = "L'email '{{ value }}' n'est pas valide.",
     *    checkMX = true)
     */ 
    private $email ="exemple@exemple.com";

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
     * @var \Doctrine\Common\Collections\Collection|categories[]
     * @ORM\JoinTable(name="advert_categories",
     *      joinColumns={@ORM\JoinColumn(name="advert_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
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
    public function getLocation()
    {
        return $this->location;
    }
    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLat()
    {
        return $this->lat;
    }
    public function setLat($lat)
    {
        $this->lat = $lat;
    }
        public function getLng()
    {
        return $this->lng;
    }
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    public function getAuthor()
    {
        return $this->author;
    }
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function setContent($content)
    {
        $this->content = $content;
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

    public function removeAllCategories()
    {
        $this->categories = null; 
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
