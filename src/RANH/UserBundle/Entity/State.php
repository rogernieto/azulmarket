<?php

namespace RANH\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * State
 *
 * @ORM\Table(name="states")
 * @ORM\Entity(repositoryClass="RANH\UserBundle\Entity\StateRepository")
 */
class State
{
    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="state")
     */

    protected $articles;

    public  function _construct()
    {
        $this->articles = new ArrayCollection();
    }




    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10)
     */
    private $status;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return State
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add articles
     *
     * @param \RANH\UserBundle\Entity\Article $articles
     * @return State
     */
    public function addArticle(\RANH\UserBundle\Entity\Article $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \RANH\UserBundle\Entity\Article $articles
     */
    public function removeArticle(\RANH\UserBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }

    public function __toString()
    {
        return $this->status; // if you have a name property you can do $this->getName();
    }

}
