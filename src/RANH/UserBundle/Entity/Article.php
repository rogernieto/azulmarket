<?php

namespace RANH\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="RANH\UserBundle\Entity\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\ManyToOne(targetEntity="State", inversedBy="articles")  
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     */

    protected $state;


    /**
     * @ORM\ManyToOne(targetEntity="SubCategory", inversedBy="articles")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id")
     */

    protected $subcategories;
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
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="state_id", type="integer")
     */
    private $stateId;

    /**
     * @var integer
     *
     * @ORM\Column(name="subcategory_id", type="integer")
     */
    private $subcategoryId;


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
     * Set name
     *
     * @param string $name
     * @return Article
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set stateId
     *
     * @param integer $stateId
     * @return Article
     */
    public function setStateId($stateId)
    {
        $this->stateId = $stateId;

        return $this;
    }

    /**
     * Get stateId
     *
     * @return integer 
     */
    public function getStateId()
    {
        return $this->stateId;
    }

    /**
     * Set subcategoryId
     *
     * @param integer $subcategoryId
     * @return Article
     */
    public function setSubcategoryId($subcategoryId)
    {
        $this->subcategoryId = $subcategoryId;

        return $this;
    }

    /**
     * Get subcategoryId
     *
     * @return integer 
     */
    public function getSubcategoryId()
    {
        return $this->subcategoryId;
    }

    /**
     * Set state
     *
     * @param \RANH\UserBundle\Entity\State $state
     * @return Article
     */
    public function setState(\RANH\UserBundle\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \RANH\UserBundle\Entity\State 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set subcategories
     *
     * @param \RANH\UserBundle\Entity\SubCategory $subcategories
     * @return Article
     */
    public function setSubcategories(\RANH\UserBundle\Entity\SubCategory $subcategories = null)
    {
        $this->subcategories = $subcategories;

        return $this;
    }

    /**
     * Get subcategories
     *
     * @return \RANH\UserBundle\Entity\SubCategory 
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }





}
