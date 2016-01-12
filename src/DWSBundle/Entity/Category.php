<?php

namespace DWSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DWSBundle\Entity\CategoryRepository")
 */
class Category
{
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
     * @Assert\NotBlank(message="as.nb")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     *
     * @return Category
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
    
    //Enlaces con otras entidades
    
    //aplicado el borrado en cascada: cascade={"persist","remove"}
    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category", cascade={"persist","remove"})
     * @Assert\NotBlank()
     */
    protected $products;
    
    public function __construct()
    {
    	$this->products = new ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \DWSBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\DWSBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \DWSBundle\Entity\Product $product
     */
    public function removeProduct(\DWSBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
